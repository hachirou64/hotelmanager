<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\MomoService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $momo;

    public function __construct(MomoService $momo)
    {
        $this->momo = $momo;
    }

    /**
     * Show a simple payment form to collect telephone for MOMO.
     */
    public function showPaymentForm(Reservation $reservation)
    {
        $amount = $this->calculateAmountForReservation($reservation);
        return view('payments.pay', ['reservation' => $reservation, 'amount' => $amount]);
    }

    /**
     * Initiate a payment for a reservation. Creates a Payment record (pending) and calls MOMO service.
     */
    public function initiate(Request $request, Reservation $reservation)
    {
        // Only allow initiating payment for reservations in 'en cours' or 'confirmée'
        if (! in_array($reservation->statut, ['en cours', 'confirmée'])) {
            return response()->json(['error' => 'Paiement non autorisé pour ce statut.'], 422);
        }

        $request->validate([
            'telephone' => 'required|string',
            'provider' => 'nullable|in:mtn,moov,celtis',
        ]);

        $amount = $this->calculateAmountForReservation($reservation);

        DB::beginTransaction();
        try {
            $invoice = $reservation->invoice;
            if (! $invoice) {
                // create an invoice if missing
                $invoice = Invoice::create([
                    'id_reservation' => $reservation->id_reservation,
                    'id_client' => $reservation->id_client,
                    'date_facture' => now()->toDateString(),
                    'montant_total' => $amount,
                    'statut_paiement' => 'impayée',
                ]);
            }

            $payment = Payment::create([
                'id_facture' => $invoice->id_facture,
                'date_paiement' => now()->toDateString(),
                'montant_paye' => 0,
                'mode_paiement' => 'MOMO',
                'transaction_id' => null,
                'provider' => 'momo',
                'status' => 'pending',
            ]);

            // Initiate through MOMO service (callback will be payments.webhook)
            $callback = URL::route('payments.webhook');
            $provider = $request->input('provider', 'mtn');

            $res = $this->momo->initiatePayment($request->input('telephone'), (float) $amount, $callback, [
                'reservation_id' => $reservation->id_reservation,
                'payment_id' => $payment->id_paiement,
            ], $provider);

            // Store transaction id and provider payload
            $payment->transaction_id = $res['transaction_id'] ?? null;
            $payment->metadata = $res['provider_payload'] ?? null;
            $payment->save();

            DB::commit();

            // Redirect back with success message for the client to check their phone
            return redirect()->back()->with('success', 'Paiement initié. Veuillez vérifier votre téléphone pour confirmer le paiement.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Impossible d\'initier le paiement. Veuillez réessayer.');
        }
    }

    /**
     * Webhook endpoint for MOMO provider to notify payment result.
     * Expected JSON payload should include: transaction_id, status, amount, metadata (containing reservation/payment ids)
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        // Verify webhook signature if configured
        $secret = config('momo.webhook_secret');
        if ($secret) {
            $signatureHeader = $request->header('X-MOMO-SIGNATURE') ?: $request->header('X-Signature');
            if (! $signatureHeader) {
                return response()->json(['error' => 'missing signature'], 400);
            }
            $computed = hash_hmac('sha256', json_encode($payload), $secret);
            if (! hash_equals($computed, $signatureHeader)) {
                report(new \Exception('Invalid webhook signature')); 
                return response()->json(['error' => 'invalid signature'], 403);
            }
        }

        // Basic validation
        if (empty($payload['transaction_id'])) {
            return response()->json(['error' => 'transaction_id missing'], 400);
        }

        // Find payment by transaction_id or metadata.payment_id
        $payment = Payment::where('transaction_id', $payload['transaction_id'])->first();
        if (! $payment && ! empty($payload['metadata']['payment_id'])) {
            $payment = Payment::find($payload['metadata']['payment_id']);
        }

        if (! $payment) {
            // Optionally: log unknown transaction for manual reconciliation
            report(new \Exception('Unknown payment webhook: ' . json_encode($payload)));
            return response()->json(['error' => 'payment not found'], 404);
        }

        // Idempotency: only process if status is pending
        if ($payment->status !== 'pending') {
            return response()->json(['ok' => true]);
        }

        $status = $payload['status'] ?? ($payload['payment_status'] ?? 'failed');

        DB::beginTransaction();
        try {
            if (in_array($status, ['success', 'paid', 'OK'])) {
                $payment->status = 'success';
                $payment->montant_paye = $payload['amount'] ?? $payment->montant_paye;
                $payment->save();

                // Update invoice
                $invoice = $payment->invoice;
                if ($invoice) {
                    $invoice->statut_paiement = 'payée';
                    $invoice->save();
                }

                // Update reservation statut to confirmée and refresh room status
                $reservationId = $payload['metadata']['reservation_id'] ?? ($invoice->id_reservation ?? null);
                if ($reservationId) {
                    $reservation = Reservation::find($reservationId);
                    if ($reservation) {
                        $reservation->statut = 'confirmée';
                        $reservation->save();
                        // Refresh room status
                        app(\App\Http\Controllers\ReservationController::class)->refreshRoomStatus($reservation->id_chambre);
                    }
                }

                // Send payment receipt email to client
                if ($invoice && $invoice->client) {
                    try {
                        Mail::to($invoice->client->adresse_email)->send(new PaymentReceiptMail($invoice->client, $invoice, $payment));
                    } catch (\Exception $e) {
                        // Log the error but don't fail the payment process
                        \Log::error('Failed to send payment receipt email: ' . $e->getMessage());
                    }
                }
            } else {
                $payment->status = 'failed';
                $payment->metadata = array_merge((array) $payment->metadata, ['webhook_payload' => $payload]);
                $payment->save();
            }

            DB::commit();
            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['error' => 'processing error'], 500);
        }
    }

    protected function calculateAmountForReservation(Reservation $reservation)
    {
        // Very simple calculation based on room type base price * nights.
        $room = $reservation->room->load('roomType');
        $prix = $room->roomType->prix_base ?? 0;
        $debut = \Carbon\Carbon::parse($reservation->date_debut);
        $fin = \Carbon\Carbon::parse($reservation->date_fin);
        $nights = max(1, $debut->diffInDays($fin));
        return $prix * $nights;
    }
}

