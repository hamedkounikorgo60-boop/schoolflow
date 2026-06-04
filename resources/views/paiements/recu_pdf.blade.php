<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu {{ $paiement->recu_numero }}</title>
    @include('paiements._recu_styles')
</head>
<body>
    @include('paiements._recu_document')
</body>
</html>
