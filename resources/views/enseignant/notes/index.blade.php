@extends('layouts.app')
@section('title', 'Notes & Moyennes')
@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('enseignant.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Tableau de bord</a>
    <span class="text-muted">/</span>
    <span class="fw-semibold">Notes & Moyennes</span>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-semibold mb-0">Notes & Moyennes</h5>
    <a href="{{ route('enseignant.notes.create') }}" class="btn btn-primary btn-sm">
        + Saisie groupée
    </a>
</div>

<x-trimestre-filter :classes="$classes" :classe-id="$classe_id" :trimestre="$trimestre" submit-label="Afficher" />

@if($eleves->count() > 0)
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4 py-3">Rang</th>
                    <th class="px-4 py-3">Élève</th>
                    <th class="px-4 py-3">Moyenne</th>
                    <th class="px-4 py-3">Mention</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eleves as $rang => $eleve)
                <tr>
                    <td class="px-4 py-3 fs-5">
                        @if($rang == 0) 🥇
                        @elseif($rang == 1) 🥈
                        @elseif($rang == 2) 🥉
                        @else <span class="text-muted">{{ $rang + 1 }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 fw-semibold">{{ $eleve->nom }} {{ $eleve->prenoms }}</td>
                    <td class="px-4 py-3 fw-bold text-primary">{{ $eleve->moyenne ?? 'N/A' }}/20</td>
                    <td class="px-4 py-3"><x-mention-badge :moyenne="$eleve->moyenne" /></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@elseif($classe_id)
<div class="alert alert-info">Aucun élève trouvé pour cette classe.</div>
@endif

@endsection
