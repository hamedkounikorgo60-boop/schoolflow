@php
    $ecole = config('ecole');
    $dateRecu = \Carbon\Carbon::parse($paiement->date_paiement);
    $heureRecu = $paiement->created_at
        ? \Carbon\Carbon::parse($paiement->created_at)->format('H:i')
        : now()->format('H:i');
    $parentLabel = ($eleve->genre === 'F' ? 'Madame' : 'Monsieur') . ' ' . $eleve->nom;
    $montantVerse = (float) $paiement->montant;
    $fraisTotalClasse = (float) ($fraisTotalClasse ?? 0);
    $totalPayeTousTypes = (float) ($totalPayeTousTypes ?? 0);
    $resteClasse = (float) ($resteClasse ?? 0);
    $surplus = max(0, $totalPayeTousTypes - $fraisTotalClasse);
    $typeLabel = ucfirst($paiement->type_paiement);
    $periodeLabels = [1 => '1er trimestre', 2 => '2e trimestre', 3 => '3e trimestre'];
    $periode = $periodeLabels[(int) $paiement->trimestre] ?? 'Trimestre ' . $paiement->trimestre;
@endphp

<div class="recu-wrapper">
    <div class="recu-header">
        <div class="ecole-nom">{{ $ecole['nom'] }}</div>
        <div class="ecole-ligne">{{ $ecole['adresse'] }}</div>
        <div class="ecole-ligne">
            Tél : {{ $ecole['telephone'] }}
            | Email : {{ $ecole['email'] }}
            | BP : {{ $ecole['bp'] }}
        </div>
    </div>

    <hr class="recu-sep">

    <div class="recu-titre">REÇU DE PAIEMENT</div>

    <table class="recu-meta" cellpadding="0" cellspacing="0">
        <tr>
            <td class="meta-label">N° Reçu :</td>
            <td class="meta-value">{{ $paiement->recu_numero }}</td>
        </tr>
        <tr>
            <td class="meta-label">Date :</td>
            <td class="meta-value">{{ $dateRecu->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Heure :</td>
            <td class="meta-value">{{ $heureRecu }}</td>
        </tr>
        <tr>
            <td class="meta-label">Élève :</td>
            <td class="meta-value">{{ $eleve->nom }} {{ $eleve->prenoms }}</td>
        </tr>
        <tr>
            <td class="meta-label">Matricule :</td>
            <td class="meta-value">{{ $eleve->matricule }}</td>
        </tr>
        <tr>
            <td class="meta-label">Classe :</td>
            <td class="meta-value">{{ $eleve->classe->nom ?? '—' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Parent :</td>
            <td class="meta-value">{{ $parentLabel }}</td>
        </tr>
        <tr>
            <td class="meta-label">Motif :</td>
            <td class="meta-value">{{ $typeLabel }}</td>
        </tr>
        <tr>
            <td class="meta-label">Période :</td>
            <td class="meta-value">{{ $periode }}</td>
        </tr>
    </table>

    <div class="recu-montant-box">
        <div class="montant-label">MONTANT VERSÉ (ce paiement)</div>
        <div class="montant-value">{{ number_format($montantVerse, 0, ',', ' ') }} FCFA</div>
    </div>

    <table class="recu-summary" cellpadding="0" cellspacing="0">
        <tr>
            <td class="summary-item">
                <span class="summary-label">Total frais classe (année) :</span>
                <strong>{{ number_format($fraisTotalClasse, 0, ',', ' ') }} FCFA</strong>
            </td>
            <td class="summary-sep">|</td>
            <td class="summary-item">
                <span class="summary-label">Total payé (année) :</span>
                <strong>{{ number_format($totalPayeTousTypes, 0, ',', ' ') }} FCFA</strong>
            </td>
            <td class="summary-sep">|</td>
            <td class="summary-item">
                <span class="summary-label">Reste à payer :</span>
                <strong class="{{ $resteClasse <= 0 ? 'reste-zero' : 'reste-du' }}">
                    {{ number_format($resteClasse, 0, ',', ' ') }} FCFA
                </strong>
            </td>
        </tr>
    </table>

    @if($surplus > 0)
    <div class="recu-alert-surplus">
        Attention : le total payé dépasse les frais de la classe de
        <strong>{{ number_format($surplus, 0, ',', ' ') }} FCFA</strong>.
    </div>
    @endif

    <table class="recu-footer-table" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td class="recu-observation" align="left">
                <strong>Observation :</strong> {{ $paiement->observation ?: '—' }}
            </td>
            <td class="recu-fait-le" align="right">
                Fait le {{ $dateRecu->format('d/m/Y') }}
            </td>
        </tr>
    </table>
</div>
