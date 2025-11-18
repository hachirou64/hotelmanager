<?php

namespace App\Http\Controllers;

use App\Models\HotelParameter;
use Illuminate\Http\Request;

class SettingsViewController extends Controller
{
    public function index()
    {
        $parameters = HotelParameter::all();
        return view('settings', compact('parameters'));
    }
}
