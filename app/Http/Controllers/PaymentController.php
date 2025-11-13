<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return Payment::with('invoice')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_facture' => 'required|exists:invoices,id_facture',
            'date_paiement' => 'required|date',
            'montant_paye' => 'required|numeric|min:0',
            'mode_paiement' => 'required|in:CB,espèces,virement',
        ]);

        return Payment::create($request->all());
    }

    public function show(Payment $payment)
    {
        return $payment->load('invoice');
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'id_facture' => 'required|exists:invoices,id_facture',
            'date_paiement' => 'required|date',
            'montant_paye' => 'required|numeric|min:0',
            'mode_paiement' => 'required|in:CB,espèces,virement',
        ]);

        $payment->update($request->all());
        return $payment;
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->noContent();
    }
}
