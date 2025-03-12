<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement</title>
</head>

<body>
    <h1>Reçu de Paiement</h1>
    <p>Nom du Client: {{ $payment->customer_name }}</p>
    <p>Email: {{ $payment->customer_email }}</p>
    <p>Montant: {{ $payment->amount }} EUR</p>
    <p>Statut: {{ $payment->status }}</p>
</body>

</html>