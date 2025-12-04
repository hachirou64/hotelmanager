<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Client;
use App\Http\Requests\StoreReservationRequest;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // Admin index (API-ish JSON) - optional
    public function index()
    {
        return Reservation::with(['client', 'room.roomType'])->get();
    }

    // Show booking form (web)
    public function create($roomId)
    {
        $room = Room::findOrFail($roomId);
        return view('book-room', compact('room'));
    }

    // Handle form submit from public booking page (web)
    public function store(StoreReservationRequest $request)
    {
        $data = $request->validated();

        // Basic availability check using DB column names - only consider confirmed (paid) reservations
        $overlap = Reservation::where('id_chambre', $data['id_chambre'])
            ->where('statut', 'confirmée')
            ->where(function ($q) use ($data) {
                $q->where('date_debut', '<', $data['date_fin'])
                  ->where('date_fin', '>', $data['date_debut']);
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['id_chambre' => 'La chambre n\'est pas disponible pour ces dates.'])->withInput();
        }

        // Determine id_client: prefer provided id_client, otherwise
        // try to resolve from authenticated user or from the provided email.
        // For anonymous reservations, id_client can be null.
        $clientId = $data['id_client'] ?? null;

        if (empty($clientId)) {
            // If a logged-in user exists, try to associate by user_id or email
            if ($request->user()) {
                $user = $request->user();
                $email = $user->email ?? null;

                // First try to find existing client by user_id
                $client = Client::where('user_id', $user->id)->first();

                if (!$client && $email) {
                    // If not found by user_id, try by email and link to user
                    $client = Client::where('adresse_email', $email)->first();
                    if ($client) {
                        // Link existing client to user account
                        $client->update(['user_id' => $user->id]);
                    }
                }

                if (!$client) {
                    // Create new client linked to user
                    // Try to split user name into nom/prenom when possible
                    $userName = $user->name ?? null;
                    $nom = $userName;
                    $prenom = '';
                    if ($userName && str_contains($userName, ' ')) {
                        $parts = preg_split('/\s+/', trim($userName));
                        $prenom = array_shift($parts);
                        $nom = implode(' ', $parts) ?: $prenom;
                    }

                    $client = Client::create([
                        'user_id' => $user->id,
                        'nom' => $nom ?? '',
                        'prenom' => $prenom ?? '',
                        'adresse_email' => $email,
                        'telephone' => $request->input('client_telephone') ?? '',
                    ]);
                }
                $clientId = $client->id_client;
            }

            // If still missing and form provided an email, create/find client by that email
            if (empty($clientId) && $request->filled('client_email')) {
                $client = Client::firstOrCreate([
                    'adresse_email' => $request->input('client_email')
                ], [
                    'nom' => $request->input('client_nom') ?? '',
                    'prenom' => $request->input('client_prenom') ?? '',
                    'telephone' => $request->input('client_telephone') ?? '',
                ]);
                $clientId = $client->id_client;
            }
        }

        // For anonymous reservations, clientId can be null

        // Create reservation and mark room as occupied in a transaction
        try {
            DB::beginTransaction();

            $reservation = Reservation::create([
                'id_client' => $clientId,
                'id_chambre' => $data['id_chambre'],
                'date_debut' => $data['date_debut'],
                'date_fin' => $data['date_fin'],
                'demandes_speciales' => $data['notes'] ?? null,
                'statut' => 'en cours',
            ]);

            // Refresh room status based on reservations and dates
            $this->refreshRoomStatus($data['id_chambre']);

            DB::commit();

            // Redirect user to payment page to complete MOMO payment
            return redirect()->route('reservations.pay.form', $reservation)->with('info', 'Votre réservation est créée. Veuillez procéder au paiement pour la confirmer.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error and return with an error message
            report($e);
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la création de la réservation. Veuillez réessayer.'])->withInput();
        }
    }

    public function show(Reservation $reservation)
    {
        return $reservation->load(['client', 'room.roomType']);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'id_client' => 'required|exists:clients,id_client',
            'id_chambre' => 'required|exists:rooms,id_chambre',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'statut' => 'required|in:confirmée,en cours,annulée',
            'demandes_speciales' => 'nullable|string',
        ]);

        $reservation->update($request->all());

        // After updating statut or dates, refresh the room status
        if ($reservation->id_chambre) {
            $this->refreshRoomStatus($reservation->id_chambre);
        }

        return $reservation;
    }

    public function destroy(Reservation $reservation)
    {
        $roomId = $reservation->id_chambre;
        $reservation->delete();

        // After deletion, refresh room status (it may become free)
        if ($roomId) {
            $this->refreshRoomStatus($roomId);
        }

        return response()->noContent();
    }

    /**
     * Recalculate and update the room status based on current confirmed (paid) reservations.
     * If there is any confirmed reservation that covers today, mark 'occupée', else 'libre'.
     */
    private function refreshRoomStatus($roomId)
    {
        try {
            $today = now()->toDateString();

            $hasActiveToday = Reservation::where('id_chambre', $roomId)
                ->where('statut', 'confirmée')
                ->where('date_debut', '<=', $today)
                ->where('date_fin', '>', $today)
                ->exists();

            $hasFuture = Reservation::where('id_chambre', $roomId)
                ->where('statut', 'confirmée')
                ->where('date_debut', '>', $today)
                ->exists();

            $room = Room::find($roomId);
            if ($room) {
                if ($hasActiveToday) {
                    $room->statut = 'occupée';
                } elseif ($hasFuture) {
                    // mark as reserved for future confirmed bookings
                    $room->statut = 'réservée';
                } else {
                    $room->statut = 'libre';
                }
                $room->save();
            }
        } catch (\Exception $e) {
            report($e);
        }
    }
}
