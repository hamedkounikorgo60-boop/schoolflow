<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Reçu Global</title>

<style>
body{
    font-family: Arial, sans-serif;
    font-size:14px;
}

h1,h2,h3{
    text-align:center;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

table th,
table td{
    border:1px solid #000;
    padding:8px;
}

.total{
    margin-top:20px;
    text-align:right;
    font-size:18px;
    font-weight:bold;
}
</style>

</head>
<body>

<h2>🏫 Suivi Scolaire</h2>
<h3>REÇU GLOBAL</h3>

<p>
    <strong>Élève :</strong>
    {{ $eleve->nom }} {{ $eleve->prenoms }}
</p>

<p>
    <strong>Matricule :</strong>
    {{ $eleve->matricule }}
</p>

<p>
    <strong>Classe :</strong>
    {{ $eleve->classe->nom }}
</p>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Montant</th>
        </tr>
    </thead>

    <tbody>
        @foreach($paiements as $p)
        <tr>
            <td>
                {{ \Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}
            </td>

            <td>
                {{ ucfirst($p->type_paiement) }}
            </td>

            <td>
                {{ number_format($p->montant,0,',',' ') }}
                FCFA
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="total">
    Total payé :
    {{ number_format($total,0,',',' ') }}
    FCFA
</div>

</body>
</html>
