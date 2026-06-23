@extends('layouts.app')
@section('title', 'Classement')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-semibold mb-0">Classement des élèves</h5>
    @if(auth()->user()->role === 'enseignant')
        <a href="{{ route('enseignant.notes.create') }}" class="btn btn-primary btn-sm">+ Saisir une note</a>
    @endif
</div>
<div class="card stat-card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <select name="classe_id" class="form-select">
                    <option value="">-- Choisir une classe --</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ $classe_id == $classe->id ? 'selected' : '' }}>
                            {{ $classe->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="trimestre" class="form-select">
                    <option value="1" {{ $trimestre == 1 ? 'selected' : '' }}>Trimestre 1</option>
                    <option value="2" {{ $trimestre == 2 ? 'selected' : '' }}>Trimestre 2</option>
                    <option value="3" {{ $trimestre == 3 ? 'selected' : '' }}>Trimestre 3</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Afficher</button>
            </div>
        </form>
    </div>
</div>
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
                    <td>
                        @if($eleve->moyenne !== null)
                            @if($eleve->moyenne >= 16)
                                <span class="badge bg-success">Excellent</span>
                            @elseif($eleve->moyenne >= 14)
                                <span class="badge bg-primary">Très bien</span>
                            @elseif($eleve->moyenne >= 12)
                                <span class="badge bg-info">Bien</span>
                            @elseif($eleve->moyenne >= 10)
                                <span class="badge bg-warning">Passable</span>
                            @else
                                <span class="badge bg-danger">Insuffisant</span>
                            @endif
                        @endif
                    </td>
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
