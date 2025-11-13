<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        return Invoice::with(['reservation', 'client'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_reservation' => 'required|exists:reservations,id_reservation',
            'id_client' => 'required|exists:clients,id_client',
            'date_facture' => 'required|date',
            'montant_total' => 'required|numeric|min:0',
            'statut_paiement' => 'required|in:payée,impayée',
            'export_format' => 'nullable|in:PDF,Excel',
        ]);

        return Invoice::create($request->all());
    }

    public function show(Invoice $invoice)
    {
        return $invoice->load(['reservation', 'client']);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'id_reservation' => 'required|exists:reservations,id_reservation',
            'id_client' => 'required|exists:clients,id_client',
            'date_facture' => 'required|date',
            'montant_total' => 'required|numeric|min:0',
            'statut_paiement' => 'required|in:payée,impayée',
            'export_format' => 'nullable|in:PDF,Excel',
        ]);

        $invoice->update($request->all());
        return $invoice;
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->noContent();
    }
}
