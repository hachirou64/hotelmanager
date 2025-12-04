<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Invoice;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Reservations statistics
        $reservations = Reservation::whereBetween('date_debut', [$startDate, $endDate])->get();
        $totalReservations = $reservations->count();
        $confirmedReservations = $reservations->where('statut', 'confirmée')->count();
        $cancelledReservations = $reservations->where('statut', 'annulée')->count();

        // Revenue statistics
        $payments = Payment::whereBetween('created_at', [$startDate, $endDate])->where('statut', 'complété')->get();
        $totalRevenue = $payments->sum('montant');
        $totalInvoices = Invoice::whereBetween('created_at', [$startDate, $endDate])->count();

        // Room statistics
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('statut', 'occupée')->count();
        $availableRooms = Room::where('statut', 'libre')->count();

        // Client statistics
        $totalClients = Client::count();
        $newClients = Client::whereBetween('created_at', [$startDate, $endDate])->count();

        // Monthly revenue for chart
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $revenue = Payment::whereBetween('created_at', [$monthStart, $monthEnd])
                            ->where('statut', 'complété')
                            ->sum('montant');

            $monthlyRevenue[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // Room type occupancy
        $roomTypes = \App\Models\RoomType::with('rooms')->get();
        $roomTypeStats = [];
        foreach ($roomTypes as $type) {
            $total = $type->rooms->count();
            $occupied = $type->rooms->where('statut', 'occupée')->count();
            $occupancyRate = $total > 0 ? round(($occupied / $total) * 100, 1) : 0;

            $roomTypeStats[] = [
                'type' => $type->nom_type,
                'total' => $total,
                'occupied' => $occupied,
                'occupancy_rate' => $occupancyRate
            ];
        }

        return view('reports.index', compact(
            'startDate',
            'endDate',
            'totalReservations',
            'confirmedReservations',
            'cancelledReservations',
            'totalRevenue',
            'totalInvoices',
            'totalRooms',
            'occupiedRooms',
            'availableRooms',
            'totalClients',
            'newClients',
            'monthlyRevenue',
            'roomTypeStats'
        ));
    }
}
