<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Http\Request;

class PersonnelViewController extends Controller
{
    public function index()
    {
        $personnels = Personnel::with('user')->get();
        return view('personnel', compact('personnels'));
    }
}
