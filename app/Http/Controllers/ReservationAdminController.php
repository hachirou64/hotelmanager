<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationAdminController extends Controller
{
    /**
     * Display a listing of reservations for admin.
     */
    public function index(Request $request)
    {
    // Basic stub: load reservations with client and room relationships
    // Use DB column names (date_debut) for ordering
    $reservations = Reservation::with(['client', 'room'])->orderBy('date_debut', 'desc')->get();

        return view('reservations', compact('reservations'));
    }
}
