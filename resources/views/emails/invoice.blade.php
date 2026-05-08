<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $invoice->id_facture }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-details div {
            width: 45%;
        }
        .invoice-details h3 {
            margin-top: 0;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Hôtel Manager</h1>
        <p>Adresse: [Votre Adresse]</p>
        <p>Téléphone: [Votre Téléphone]</p>
        <p>Email: [Votre Email]</p>
    </div>

    <div class="invoice-details">
        <div>
            <h3>Facture à:</h3>
            <p>{{ $invoice->client->nom }} {{ $invoice->client->prenom }}</p>
            <p>{{ $invoice->client->adresse_email }}</p>
            <p>{{ $invoice->client->telephone }}</p>
        </div>
        <div>
            <h3>Détails de la Facture</h3>
            <p><strong>Numéro de Facture:</strong> #{{ $invoice->id_facture }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->date_facture)->format('d/m/Y') }}</p>
            <p><strong>Statut:</strong> {{ ucfirst($invoice->statut_paiement) }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Réservation Chambre {{ $invoice->reservation->room->numero_chambre }} ({{ $invoice->reservation->room->roomType->nom_type }})</td>
                <td>{{ \Carbon\Carbon::parse($invoice->reservation->date_debut)->diffInDays(\Carbon\Carbon::parse($invoice->reservation->date_fin)) }} nuits</td>
                <td>{{ $invoice->reservation->room->roomType->prix_base }} €</td>
                <td>{{ $invoice->montant_total }} €</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <p>Total: {{ $invoice->montant_total }} €</p>
    </div>

    <div class="footer">
        <p>Merci pour votre séjour chez Hôtel Manager.</p>
        <p>Pour toute question, contactez-nous.</p>
    </div>
</body>
</html>
