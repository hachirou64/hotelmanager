<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomViewController extends Controller
{
    public function index()
    {
        $rooms = Room::with('roomType')->get();
        return view('rooms', compact('rooms'));
    }
}
