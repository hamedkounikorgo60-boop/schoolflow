@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('content')

@php
    $nbEleves    = \App\Models\Eleve::count();
    $nbClasses   = \App\Models\Classe::count();
    $recettes    = \App\Models\Paiement::whereMonth('date_paiement', now()->month)->sum('montant');
    $nbImpayes   = \App\Models\Eleve::with(['classe','paiements'])->get()
                    ->filter(fn($e) => $e->paiements->sum('montant') < ($e->classe->frais_scolarite ?? 0))
                    ->count();
    $nbParents   = \App\Models\User::where('role', 'parent')->count();
@endphp

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#eff6ff">
                <i class="bi bi-people-fill" style="color:#2563eb"></i>
            </div>
            <div>
                <div class="stat-value">{{ $nbEleves }}</div>
                <div class="stat-label">Élèves inscrits</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#f0fdf4">
                <i class="bi bi-building" style="color:#16a34a"></i>
            </div>
            <div>
                <div class="stat-value">{{ $nbClasses }}</div>
                <div class="stat-label">Classes actives</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fffbeb">
                <i class="bi bi-cash-stack" style="color:#d97706"></i>
            </div>
            <div>
                <div class="stat-value" style="font-size:1.2rem">
                    {{ number_format($recettes, 0, ',', ' ') }}
                </div>
                <div class="stat-label">Recettes ce mois (F)</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fef2f2">
                <i class="bi bi-exclamation-triangle-fill" style="color:#dc2626"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#dc2626">{{ $nbImpayes }}</div>
                <div class="stat-label">Élèves en impayé</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header" style="background:#eff6ff;color:#1d4ed8">
                <i class="bi bi-people me-2"></i>Gestion des élèves
            </div>
            <div class="card-body d-flex flex-column gap-2 p-3">
                <a href="{{ route('gestionnaire.eleves.index') }}"
                   class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-list-ul"></i> Liste des élèves
                </a>
                <a href="{{ route('gestionnaire.eleves.create') }}"
                   class="btn btn-outline-primary d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus"></i> Inscrire un élève
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header" style="background:#f0fdf4;color:#15803d">
                <i class="bi bi-cash-stack me-2"></i>Paiements
            </div>
            <div class="card-body d-flex flex-column gap-2 p-3">
                <a href="{{ route('gestionnaire.paiements.create') }}"
                   class="btn btn-success d-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle"></i> Nouveau paiement
                </a>
                <a href="{{ route('gestionnaire.paiements.index') }}"
                   class="btn btn-outline-success d-flex align-items-center gap-2">
                    <i class="bi bi-receipt"></i> Liste des paiements
                </a>
                <a href="{{ route('gestionnaire.paiements.impaye') }}"
                   class="btn btn-outline-danger d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-circle"></i> Voir les impayés
                    @if($nbImpayes > 0)
                        <span class="badge bg-danger ms-auto">{{ $nbImpayes }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header" style="background:#faf5ff;color:#7c3aed">
                <i class="bi bi-person-hearts me-2"></i>Gestion des parents
            </div>
            <div class="card-body d-flex flex-column gap-2 p-3">
                <a href="{{ route('gestionnaire.parents.index') }}"
                   class="btn btn-outline-primary d-flex align-items-center gap-2">
                    <i class="bi bi-list-ul"></i> Liste des parents
                    <span class="badge bg-primary ms-auto">{{ $nbParents }}</span>
                </a>
                <a href="{{ route('gestionnaire.parents.create') }}"
                   class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus"></i> Ajouter un parent
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header" style="background:#f0f9ff;color:#0369a1">
                <i class="bi bi-trophy me-2"></i>Pédagogie
            </div>
            <div class="card-body d-flex flex-column gap-2 p-3">
                <a href="{{ route('gestionnaire.notes.classement') }}"
                   class="btn btn-outline-secondary d-flex align-items-center gap-2">
                    <i class="bi bi-trophy"></i> Classement par classe
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
