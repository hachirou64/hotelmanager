<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class BillingViewController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['reservation', 'client'])->get();
        return view('billing', compact('invoices'));
    }
}
