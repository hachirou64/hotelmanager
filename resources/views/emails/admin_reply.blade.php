<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Réponse à votre message de contact</title>
</head>
<body>
    <p>Bonjour {{ $contactMessage->name }},</p>

    <p>Merci pour votre message que vous nous avez envoyé :</p>
    <blockquote>
        <p>{{ $contactMessage->message }}</p>
    </blockquote>

    <p>Voici notre réponse :</p>
    <blockquote style="border-left: 3px solid #ccc; padding-left: 10px; color: #555;">
        <p>{{ $replyContent }}</p>
    </blockquote>

    <p>Nous restons à votre disposition pour toute autre question.</p>

    <p>Cordialement,</p>
    <p>L'équipe de l'Hôtel Manager</p>
</body>
</html>
