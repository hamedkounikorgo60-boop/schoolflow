@extends('layouts.app')
@section('title', 'Dashboard Enseignant')
@section('content')

{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-semibold mb-0">Tableau de bord — Enseignant</h5>
        <small class="text-muted">Bienvenue, {{ $user->name }} · {{ now()->translatedFormat('l d F Y') }}</small>
    </div>
    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
        ● Connecté
    </span>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 fs-3">📝</div>
                <div>
                    <div class="text-muted small">Notes saisies</div>
                    <div class="fw-bold fs-4 text-primary">{{ $notesCount }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 fs-3">🏫</div>
                <div>
                    <div class="text-muted small">Classes concernées</div>
                    <div class="fw-bold fs-4 text-success">{{ $nbClasses }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 fs-3">📚</div>
                <div>
                    <div class="text-muted small">Matières disponibles</div>
                    <div class="fw-bold fs-4 text-warning">{{ $matieres->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- Dernières notes saisies --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <span class="fw-semibold">📋 Dernières notes saisies</span>
                <a href="{{ route('enseignant.notes.create') }}" class="btn btn-primary btn-sm">
                    + Saisie groupée
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Élève</th>
                            <th class="px-4 py-3">Matière</th>
                            <th class="px-4 py-3">Trimestre</th>
                            <th class="px-4 py-3 text-center">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dernieresNotes as $note)
                        @php
                            $cls = $note->note >= 14 ? 'text-success' : ($note->note >= 10 ? 'text-warning' : 'text-danger');
                        @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-medium">{{ $note->eleve->nom }} {{ $note->eleve->prenoms }}</div>
                                <div class="text-muted small">{{ $note->eleve->classe->nom ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $note->matiere->nom }}</td>
                            <td class="px-4 py-3">
                                <span class="badge bg-light text-dark border">
                                    {{ ucfirst(str_replace('trimestre', 'T', $note->trimestre)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center fw-bold {{ $cls }}">
                                {{ $note->note }}/20
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                Aucune note saisie pour le moment.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Matières & Actions rapides --}}
    <div class="col-md-4">

        {{-- Actions rapides --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-bottom py-3">
                <span class="fw-semibold">⚡ Actions rapides</span>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('enseignant.notes.create') }}"
                   class="btn btn-outline-primary text-start">
                    📝 Saisie groupée par classe
                </a>
                <a href="{{ route('enseignant.notes.index') }}"
                   class="btn btn-outline-secondary text-start">
                    📊 Voir les moyennes
                </a>
                <a href="{{ route('enseignant.notes.index') }}"
                   class="btn btn-outline-info text-start">
                    🏆 Classement
                </a>
                <a href="{{ route('enseignant.matieres.index') }}"
                    class="btn btn-outline-success text-start">
            📚 Gérer les matières
        </a>
            </div>
        </div>

        {{-- Classes assignées --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-bottom py-3">
                <span class="fw-semibold">🏫 Mes classes</span>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($classes as $classe)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4">
                        <span class="small fw-medium">{{ $classe->nom }} <span class="text-muted">({{ $classe->niveau }})</span></span>
                    </li>
                    @empty
                    <li class="list-group-item text-muted small px-4">Aucune classe assignée.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Matières --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <span class="fw-semibold">📚 Mes matières</span>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($matieres->take(8) as $matiere)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4">
                        <span class="small fw-medium">{{ $matiere->nom }}</span>
                        <span class="badge bg-primary-subtle text-primary">
                            Coef. {{ $matiere->coefficient }}
                        </span>
                    </li>
                    @empty
                    <li class="list-group-item text-muted small px-4">Aucune matière.</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
</div>

@endsection
