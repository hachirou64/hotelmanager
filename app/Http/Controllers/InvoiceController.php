<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function download(Invoice $invoice)
    {
        // Ensure the invoice belongs to the authenticated client if not admin
        if (auth()->check() && auth()->user()->role->nom_role !== 'admin') {
            if ($invoice->id_client !== auth()->user()->client->id_client) {
                abort(403);
            }
        }

        $pdf = Pdf::loadView('emails.invoice', compact('invoice'));

        return $pdf->download('facture_' . $invoice->id_facture . '.pdf');
    }
}
