@extends('layouts.app')
@section('title', 'Classement')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-semibold mb-0">Classement des élèves</h5>
    @if(auth()->user()->role === 'enseignant')
        <a href="{{ route('enseignant.notes.create') }}" class="btn btn-primary btn-sm">+ Saisir une note</a>
    @endif
</div>
<x-trimestre-filter :classes="$classes" :classe-id="$classe_id" :trimestre="$trimestre" submit-label="Afficher" />
@if($eleves->count() > 0)
<div class="card stat-card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Rang</th>
                    <th>Élève</th>
                    <th>Moyenne générale</th>
                    <th>Mention</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eleves as $rang => $eleve)
                <tr class="{{ $rang == 0 ? 'table-warning' : '' }}">
                    <td class="fs-5">
                        @if($rang == 0) 🥇
                        @elseif($rang == 1) 🥈
                        @elseif($rang == 2) 🥉
                        @else <span class="text-muted fw-medium">{{ $rang + 1 }}</span>
                        @endif
                    </td>
                    <td class="fw-semibold">{{ $eleve->nom }} {{ $eleve->prenoms }}</td>
                    <td class="fw-bold text-primary">{{ $eleve->moyenne ?? 'N/A' }}/20</td>
                    <td><x-mention-badge :moyenne="$eleve->moyenne" /></td>
                    <td>
                        <a href="{{ route('bulletin.show', [
                                'eleve'     => $eleve->id,
                                'trimestre' => $trimestre,
                            ]) }}"
                           class="btn btn-sm btn-outline-secondary"
                           title="Télécharger le bulletin PDF">
                            📄 Bulletin
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@elseif($classe_id)
<div class="alert alert-info">Aucune note enregistrée pour cette classe.</div>
@endif
@endsection
