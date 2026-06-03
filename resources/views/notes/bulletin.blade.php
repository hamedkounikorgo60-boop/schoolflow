<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a1a1a; padding: 20px; }

        .header { text-align: center; border-bottom: 3px solid #4f46e5; padding-bottom: 12px; margin-bottom: 16px; }
        .header h1 { font-size: 20px; color: #4f46e5; font-weight: bold; }
        .header h2 { font-size: 13px; color: #555; margin-top: 4px; }
        .header p { font-size: 11px; color: #888; margin-top: 2px; }

        .info-grid { display: table; width: 100%; margin-bottom: 16px; border: 1px solid #e5e7eb; border-radius: 6px; }
        .info-row { display: table-row; }
        .info-cell { display: table-cell; padding: 6px 12px; border-bottom: 1px solid #f3f4f6; width: 50%; }
        .info-label { font-weight: bold; color: #6b7280; font-size: 10px; text-transform: uppercase; }
        .info-value { color: #111; font-size: 12px; margin-top: 2px; }

        table.notes { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table.notes thead tr { background: #4f46e5; color: white; }
        table.notes thead th { padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; }
        table.notes tbody tr:nth-child(even) { background: #f9fafb; }
        table.notes tbody tr:hover { background: #eff6ff; }
        table.notes td { padding: 7px 10px; border-bottom: 1px solid #f0f0f0; font-size: 11px; }
        table.notes td.num { text-align: center; font-weight: 600; }
        table.notes td.coef { text-align: center; color: #6b7280; }

        .appreciation { background: #f8f9fa; border-left: 4px solid #4f46e5; padding: 10px 14px; margin-bottom: 16px; border-radius: 0 6px 6px 0; }
        .appreciation p { font-size: 11px; color: #555; }
        .appreciation strong { color: #4f46e5; }

        .result-box { display: table; width: 100%; margin-bottom: 20px; }
        .result-cell { display: table-cell; width: 33%; text-align: center; border: 1px solid #e5e7eb; padding: 10px; }
        .result-cell:not(:last-child) { border-right: none; }
        .result-cell .label { font-size: 10px; color: #9ca3af; text-transform: uppercase; margin-bottom: 4px; }
        .result-cell .val { font-size: 20px; font-weight: bold; color: #4f46e5; }
        .result-cell .sub { font-size: 10px; color: #6b7280; margin-top: 2px; }

        .mention-badge { display: inline-block; padding: 3px 12px; border-radius: 99px; font-size: 11px; font-weight: bold; }
        .mention-excellent { background: #dcfce7; color: #166534; }
        .mention-tres-bien { background: #dbeafe; color: #1e40af; }
        .mention-bien { background: #fef9c3; color: #92400e; }
        .mention-assez-bien { background: #ffedd5; color: #c2410c; }
        .mention-passable { background: #fee2e2; color: #991b1b; }

        .signatures { display: table; width: 100%; margin-top: 30px; }
        .sig-cell { display: table-cell; width: 33%; text-align: center; }
        .sig-line { border-top: 1px solid #aaa; margin: 40px 20px 6px; }
        .sig-label { font-size: 10px; color: #6b7280; }

        .footer { text-align: center; font-size: 9px; color: #aaa; margin-top: 20px; border-top: 1px solid #eee; padding-top: 8px; }

        .note-low { color: #dc2626; }
        .note-mid { color: #d97706; }
        .note-high { color: #16a34a; }
    </style>
</head>
<body>

{{-- En-tête --}}
<div class="header">
    <h1>🏫 Suivi Scolaire — Cycle Primaire</h1>
    <h2>BULLETIN DE NOTES</h2>
    <p>Année scolaire 2025–2026 &nbsp;|&nbsp; {{ ucfirst(str_replace('_', ' ', $trimestre)) }}</p>
</div>

{{-- Infos élève --}}
<div class="info-grid">
    <div class="info-row">
        <div class="info-cell">
            <div class="info-label">Nom & Prénoms</div>
            <div class="info-value" style="font-weight:bold;">{{ strtoupper($eleve->nom) }} {{ $eleve->prenoms }}</div>
        </div>
        <div class="info-cell">
            <div class="info-label">Matricule</div>
            <div class="info-value">{{ $eleve->matricule }}</div>
        </div>
    </div>
    <div class="info-row">
        <div class="info-cell">
            <div class="info-label">Classe</div>
            <div class="info-value">{{ $eleve->classe->libelle ?? '—' }}</div>
        </div>
        <div class="info-cell">
            <div class="info-label">Date de naissance</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') }}</div>
        </div>
    </div>
</div>

{{-- Résultats globaux --}}
<div class="result-box">
    <div class="result-cell">
        <div class="label">Moyenne générale</div>
        <div class="val">{{ number_format($moyenneGenerale, 2) }}/20</div>
        <div class="sub">
            <span class="mention-badge mention-{{ Str::slug($mention) }}">{{ $mention }}</span>
        </div>
    </div>
    <div class="result-cell">
        <div class="label">Rang dans la classe</div>
        <div class="val">{{ $rang }}<sup>{{ $rang == 1 ? 'er' : 'ème' }}</sup></div>
        <div class="sub">sur {{ $totalEleves }} élèves</div>
    </div>
    <div class="result-cell">
        <div class="label">Total points pondérés</div>
        <div class="val">{{ number_format($totalPoints, 1) }}</div>
        <div class="sub">/ {{ $totalCoefs }} coefficients</div>
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
    Bulletin généré le {{ now()->format('d/m/Y à H:i') }} &nbsp;|&nbsp; Suivi Scolaire &copy; {{ date('Y') }}
</div>

</body>
</html>
