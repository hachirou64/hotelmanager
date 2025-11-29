<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de paiement - Hôtel Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .receipt-details {
            background-color: white;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .receipt-details h3 {
            margin-top: 0;
            color: #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
        }
        .highlight {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 3px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Hôtel Manager</h1>
        <h2>Reçu de paiement</h2>
    </div>

    <div class="content">
        <p>Cher(e) {{ $client->prenom }} {{ $client->nom }},</p>

        <p>Nous vous confirmons la réception de votre paiement. Voici les détails de votre transaction :</p>

        <div class="receipt-details">
            <h3>Détails du paiement</h3>
            <div class="highlight">
                <strong>Numéro de facture :</strong> #{{ $invoice->id_facture }}<br>
                <strong>Date de paiement :</strong> {{ \Carbon\Carbon::parse($payment->date_paiement)->format('d/m/Y H:i') }}<br>
                <strong>Montant payé :</strong> {{ number_format($payment->montant_paye, 2, ',', ' ') }} €<br>
                <strong>Méthode de paiement :</strong> {{ $payment->mode_paiement }}<br>
                <strong>Statut :</strong> <span style="color: green; font-weight: bold;">Payé</span>
            </div>
        </div>

        <div class="receipt-details">
            <h3>Informations client</h3>
            <div class="highlight">
                <strong>Nom :</strong> {{ $client->nom }}<br>
                <strong>Prénom :</strong> {{ $client->prenom }}<br>
                <strong>Email :</strong> {{ $client->adresse_email }}<br>
                <strong>Téléphone :</strong> {{ $client->telephone }}
            </div>
        </div>

        <p>Si vous avez des questions concernant ce paiement, n'hésitez pas à nous contacter.</p>

        <p>Cordialement,<br>
        L'équipe Hôtel Manager</p>
    </div>

    <div class="footer">
        <p>Cette facture a été générée automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Hôtel Manager - Système de gestion hôtelière</p>
    </div>
</body>
</html>
