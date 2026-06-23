<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a1a1a; padding: 12px; line-height: 1.5; }

        .header-ecole { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 6px; margin-bottom: 8px; }
        .header-ecole .nom { font-size: 13px; font-weight: bold; color: #4f46e5; }
        .header-ecole .ligne { font-size: 8px; color: #666; margin-top: 1px; }

        .header { text-align: center; border-bottom: 3px solid #4f46e5; padding-bottom: 8px; margin-bottom: 10px; }
        .header h1 { font-size: 14px; color: #4f46e5; font-weight: bold; }
        .header h2 { font-size: 11px; color: #555; margin-top: 2px; }
        .header p { font-size: 9px; color: #888; margin-top: 2px; }

        table { width: 100%; border-collapse: collapse; }
        table.meta-info { margin-bottom: 8px; border: 1px solid #d1d5db; }
        table.meta-info th { background: #f3f4f6; padding: 4px; text-align: left; font-size: 7px; font-weight: bold; color: #6b7280; border: 1px solid #d1d5db; }
        table.meta-info td { padding: 4px; border: 1px solid #e5e7eb; font-size: 9px; }

        table.notes { margin-bottom: 10px; border: 1px solid #d1d5db; }
        table.notes thead tr { background: #4f46e5; color: white; }
        table.notes thead th { padding: 5px; text-align: left; font-size: 9px; font-weight: 600; border: 1px solid #4f46e5; }
        table.notes tbody tr:nth-child(even) { background: #f9fafb; }
        table.notes tbody td { padding: 4px; border-bottom: 1px solid #e5e7eb; font-size: 9px; }
        table.notes td.num { text-align: center; font-weight: 600; }
        table.notes td.coef { text-align: center; color: #6b7280; }
        table.notes tfoot tr { background: #f3f4f6; font-weight: bold; }
        table.notes tfoot td { padding: 4px; border: 1px solid #d1d5db; font-size: 9px; }

        .result-box { width: 100%; margin-bottom: 12px; }
        .result-cell { text-align: center; border: 1px solid #d1d5db; padding: 6px; background: #fafbfc; width: 32%; display: inline-block; margin-right: 0.5%; vertical-align: top; }
        .result-cell .label { font-size: 7px; color: #9ca3af; text-transform: uppercase; margin-bottom: 2px; display: block; }
        .result-cell .val { font-size: 16px; font-weight: bold; color: #4f46e5; margin: 2px 0; display: block; }
        .result-cell .sub { font-size: 8px; color: #6b7280; display: block; }

        .mention-badge { display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 8px; font-weight: bold; }
        .mention-Excellent { background: #dcfce7; color: #166534; }
        .mention-Très-bien { background: #dbeafe; color: #1e40af; }
        .mention-Bien { background: #fef9c3; color: #92400e; }
        .mention-Assez-bien { background: #ffedd5; color: #c2410c; }
        .mention-Passable { background: #fee2e2; color: #991b1b; }

        .appreciation { background: #f8f9fa; border-left: 3px solid #4f46e5; padding: 6px; margin-bottom: 10px; }
        .appreciation p { font-size: 9px; color: #555; line-height: 1.5; }

        .signatures { width: 100%; margin-top: 16px; }
        .sig-cell { text-align: center; width: 32%; display: inline-block; margin-right: 0.5%; vertical-align: top; }
        .sig-line { border-top: 1px solid #999; margin: 20px 10px 3px; }
        .sig-label { font-size: 8px; color: #6b7280; margin-top: 2px; display: block; }

        .footer { text-align: center; font-size: 8px; color: #999; margin-top: 10px; border-top: 1px solid #ddd; padding-top: 4px; }

        .note-low { color: #dc2626; }
        .note-mid { color: #d97706; }
        .note-high { color: #16a34a; }
    </style>
</head>
<body>

{{-- Entête de l'école --}}
<div class="header-ecole">
    <div class="nom">{{ $ecole['nom'] ?? 'SchoolFlow' }}</div>
    <div class="ligne">{{ $ecole['adresse'] ?? '' }}</div>
    <div class="ligne">Tél: {{ $ecole['telephone'] ?? '' }} | Email: {{ $ecole['email'] ?? '' }}</div>
</div>

{{-- Métadonnées du bulletin --}}
<table class="meta-info">
    <tr>
        <th>N° Bulletin</th>
        <th>Généré le</th>
        <th>Heure</th>
        <th>Niveau</th>
    </tr>
    <tr>
        <td>{{ $bulletinNumero }}</td>
        <td>{{ now()->format('d/m/Y') }}</td>
        <td>{{ now()->format('H:i') }}</td>
        <td>{{ $eleve->classe->niveau ?? '—' }}</td>
    </tr>
</table>

{{-- Infos élève --}}
<table class="meta-info">
    <tr>
        <th>Nom & Prénoms</th>
        <th>Matricule</th>
        <th>Classe</th>
        <th>Date de naissance</th>
    </tr>
    <tr>
        <td><strong>{{ strtoupper($eleve->nom) }} {{ $eleve->prenoms }}</strong></td>
        <td>{{ $eleve->matricule }}</td>
        <td>{{ $eleve->classe->libelle ?? '—' }}</td>
        <td>{{ \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') }}</td>
    </tr>
</table>

{{-- Résultats globaux --}}
<div class="result-box">
    <div class="result-cell">
        <div class="label">Moyenne générale</div>
        <div class="val">{{ number_format($moyenneGenerale, 2) }}/20</div>
        <div class="sub"><span class="mention-badge mention-{{ str_replace(' ', '-', $mention) }}">{{ $mention }}</span></div>
    </div>
    <div class="result-cell">
        <div class="label">Rang</div>
        <div class="val">{{ $rang }}<sup>{{ $rang == 1 ? 'er' : 'ème' }}</sup></div>
        <div class="sub">sur {{ $totalEleves }} élèves</div>
    </div>
    <div class="result-cell">
        <div class="label">Points pondérés</div>
        <div class="val">{{ number_format($totalPoints, 1) }}</div>
        <div class="sub">/ {{ $totalCoefs }} coef.</div>
    </div>
</div>

{{-- Tableau des notes --}}
<table class="notes">
    <thead>
        <tr>
            <th>Matière</th>
            <th style="text-align:center;">Coef.</th>
            <th style="text-align:center;">Note /20</th>
            <th style="text-align:center;">Points</th>
            <th>Appréciation</th>
        </tr>
    </thead>
    <tbody>
        @foreach($notes as $note)
        @php
            $pts = $note->note * $note->matiere->coefficient;
            $cls = $note->note >= 14 ? 'note-high' : ($note->note >= 10 ? 'note-mid' : 'note-low');
            $app = $note->note >= 16 ? 'Excellent' : ($note->note >= 14 ? 'Très bien' : ($note->note >= 12 ? 'Bien' : ($note->note >= 10 ? 'Assez bien' : 'Insuffisant')));
        @endphp
        <tr>
            <td>{{ $note->matiere->nom }}</td>
            <td class="coef">{{ $note->matiere->coefficient }}</td>
            <td class="num {{ $cls }}">{{ number_format($note->note, 2) }}</td>
            <td class="num">{{ number_format($pts, 2) }}</td>
            <td style="color:#6b7280;">{{ $app }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background:#f3f4f6; font-weight:bold;">
            <td>TOTAL</td>
            <td class="coef">{{ $totalCoefs }}</td>
            <td class="num">{{ number_format($moyenneGenerale, 2) }}</td>
            <td class="num">{{ number_format($totalPoints, 1) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

{{-- Appréciation générale --}}
<div class="appreciation">
    <p>
        <strong>Appréciation générale :</strong>
        @if($moyenneGenerale >= 16)
            Élève très brillant(e). Félicitations pour ces excellents résultats. Continuez ainsi !
        @elseif($moyenneGenerale >= 14)
            Très bons résultats. Élève sérieux(se) et travailleur(se). Encouragements du conseil.
        @elseif($moyenneGenerale >= 12)
            Bons résultats dans l'ensemble. Des efforts supplémentaires permettront d'atteindre l'excellence.
        @elseif($moyenneGenerale >= 10)
            Résultats passables. Des efforts sont nécessaires pour progresser davantage.
        @else
            Résultats insuffisants. Un travail sérieux et régulier est indispensable pour la suite.
        @endif
    </p>
</div>

{{-- Signatures et infos finales --}}
<table style="margin-top:10px;">
    <tr>
        <td style="text-align:left; font-size:8px; color:#6b7280;"><strong>Fait le :</strong> {{ now()->format('d/m/Y') }}</td>
        <td style="text-align:right; font-size:8px; color:#6b7280;">Bulletin n° {{ $bulletinNumero }}</td>
    </tr>
</table>

{{-- Signatures --}}
<div class="signatures">
    <div class="sig-cell">
        <div class="sig-line"></div>
        <div class="sig-label">Le Directeur</div>
    </div>
    <div class="sig-cell">
        <div class="sig-line"></div>
        <div class="sig-label">L'Enseignant(e)</div>
    </div>
    <div class="sig-cell">
        <div class="sig-line"></div>
        <div class="sig-label">Parent / Tuteur</div>
    </div>
</div>

<div class="footer">
    Bulletin généré le {{ now()->format('d/m/Y à H:i') }} | SchoolFlow &copy; {{ date('Y') }}
</div>

</body>
</html>
