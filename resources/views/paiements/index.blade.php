@extends('layouts.app')
@section('title', 'Paiements')
@section('content')

{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-semibold mb-0">Paiements</h5>
        <small class="text-muted">Historique de tous les paiements enregistrés</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('gestionnaire.paiements.impaye') }}"
           class="btn btn-outline-danger btn-sm">
            ⚠ Impayés
        </a>
        <a href="{{ route('gestionnaire.paiements.create') }}"
           class="btn btn-success btn-sm">
            + Nouveau paiement
        </a>
    </div>
</div>

{{-- Cartes stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 fs-4">💰</div>
                    <div>
                        <div class="text-muted small">Total encaissé</div>
                        <div class="fw-bold fs-5 text-success">
                            {{ number_format($paiements->sum('montant'), 0, ',', ' ') }} F
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 fs-4">🧾</div>
                    <div>
                        <div class="text-muted small">Nb paiements</div>
                        <div class="fw-bold fs-5 text-primary">{{ $paiements->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 fs-4">📅</div>
                    <div>
                        <div class="text-muted small">Ce mois</div>
                        <div class="fw-bold fs-5 text-info">
                            {{ number_format($paiements->where('date_paiement', '>=', now()->startOfMonth())->sum('montant'), 0, ',', ' ') }} F
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 fs-4">🏫</div>
                    <div>
                        <div class="text-muted small">Scolarités</div>
                        <div class="fw-bold fs-5 text-warning">
                            {{ $paiements->where('type_paiement', 'scolarite')->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filtres --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">Tous les types</option>
                    <option value="scolarite"   {{ request('type') == 'scolarite'   ? 'selected' : '' }}>Scolarité</option>
                    <option value="inscription" {{ request('type') == 'inscription' ? 'selected' : '' }}>Inscription</option>
                    <option value="cantine"     {{ request('type') == 'cantine'     ? 'selected' : '' }}>Cantine</option>
                    <option value="transport"   {{ request('type') == 'transport'   ? 'selected' : '' }}>Transport</option>
                    <option value="fournitures" {{ request('type') == 'fournitures' ? 'selected' : '' }}>Fournitures</option>
                    <option value="autre"       {{ request('type') == 'autre'       ? 'selected' : '' }}>Autre</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Trimestre</label>
                <select name="trimestre" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    <option value="1" {{ request('trimestre') == '1' ? 'selected' : '' }}>Trimestre 1</option>
                    <option value="2" {{ request('trimestre') == '2' ? 'selected' : '' }}>Trimestre 2</option>
                    <option value="3" {{ request('trimestre') == '3' ? 'selected' : '' }}>Trimestre 3</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Recherche élève</label>
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Nom ou matricule..." value="{{ request('q') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filtrer</button>
                <a href="{{ route('gestionnaire.paiements.index') }}" class="btn btn-outline-secondary btn-sm">✕</a>
            </div>
        </form>
    </div>
</div>

{{-- Tableau --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4 py-3">N° Reçu</th>
                    <th class="px-4 py-3">Élève</th>
                    <th class="px-4 py-3">Classe</th>
                    <th class="px-4 py-3">Montant</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Trimestre</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paiements as $paiement)
                @php
                    $typeBadge = match($paiement->type_paiement) {
                        'scolarite'   => 'bg-primary',
                        'inscription' => 'bg-success',
                        'cantine'     => 'bg-warning text-dark',
                        'transport'   => 'bg-info text-dark',
                        'fournitures' => 'bg-secondary',
                        default       => 'bg-dark',
                    };
                @endphp
                <tr>
                    <td class="px-4 py-3">
                        <span class="font-monospace text-muted small">{{ $paiement->recu_numero }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="fw-semibold">{{ $paiement->eleve->nom }} {{ $paiement->eleve->prenoms }}</div>
                        <div class="text-muted small">{{ $paiement->eleve->matricule }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge bg-light text-dark border">
                            {{ $paiement->eleve->classe->nom ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="fw-bold text-success">
                            {{ number_format($paiement->montant, 0, ',', ' ') }} F
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge {{ $typeBadge }}">
                            {{ ucfirst($paiement->type_paiement) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge bg-light text-dark border">T{{ $paiement->trimestre }}</span>
                    </td>
                    <td class="px-4 py-3 text-muted small">
                        {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="d-flex gap-2">
                            <a href="{{ route('gestionnaire.paiements.recu', $paiement) }}"
                               class="btn btn-outline-primary btn-sm" title="Voir le reçu">
                                🧾 Reçu
                            </a>
                            <a href="{{ route('gestionnaire.paiements.telecharger', $paiement) }}"
                               class="btn btn-outline-success btn-sm" title="Télécharger PDF">
                                ⬇ PDF
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        Aucun paiement enregistré.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $paiements->withQueryString()->links() }}</div>

@endsection
