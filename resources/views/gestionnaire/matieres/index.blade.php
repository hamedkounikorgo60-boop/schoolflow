@extends('layouts.app')
@section('title', 'Catalogue des matières')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Catalogue des matières</h3>
        <p class="text-muted mb-0">Matières disponibles à assigner aux enseignants.</p>
    </div>
    <a href="{{ route('gestionnaire.matieres.create', $classeId ? ['classe_id' => $classeId] : []) }}"
       class="btn btn-primary btn-sm">+ Nouvelle matière</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('gestionnaire.matieres.index') }}" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small text-muted mb-1">Filtrer par classe</label>
                <select name="classe_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Toutes les matières</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classeId == $c->id ? 'selected' : '' }}>
                            {{ $c->nom }} — niveau {{ $c->niveau }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                @if($classe)
                    <p class="small text-muted mb-0">
                        Affichage des matières du niveau <strong>{{ $classe->niveau }}</strong>
                        (classe {{ $classe->nom }}).
                    </p>
                @else
                    <p class="small text-muted mb-0">Chaque matière est liée à un niveau (CP1, CM2…).</p>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="px-4 py-3">Matière</th>
                    <th class="px-4 py-3">Coef.</th>
                    <th class="px-4 py-3">Niveau</th>
                    <th class="px-4 py-3">Enseignants</th>
                    <th class="px-4 py-3">Notes</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matieres as $matiere)
                <tr>
                    <td class="px-4 py-3 fw-semibold">{{ $matiere->nom }}</td>
                    <td class="px-4 py-3">{{ $matiere->coefficient }}</td>
                    <td class="px-4 py-3">{{ $matiere->niveau }}</td>
                    <td class="px-4 py-3">
                        <span class="badge bg-secondary">{{ $matiere->enseignants_count }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge {{ $matiere->notes_count > 0 ? 'bg-warning text-dark' : 'bg-light text-muted border' }}">
                            {{ $matiere->notes_count }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="d-flex gap-2">
                            <a href="{{ route('gestionnaire.matieres.edit', $matiere) }}"
                               class="btn btn-warning btn-sm">Modifier</a>
                            <form action="{{ route('gestionnaire.matieres.destroy', $matiere) }}"
                                  method="POST"
                                  onsubmit="return confirm('Supprimer « {{ $matiere->nom }} » ({{ $matiere->niveau }}) ?');">
                                @csrf
                                @method('DELETE')
                                @if($classeId)
                                    <input type="hidden" name="classe_id" value="{{ $classeId }}">
                                @endif
                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        @if($matiere->notes_count > 0) disabled title="Des notes existent pour cette matière" @endif>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        @if($classe)
                            Aucune matière pour le niveau {{ $classe->niveau }}.
                            <a href="{{ route('gestionnaire.matieres.create', ['classe_id' => $classe->id]) }}">En ajouter une</a>
                        @else
                            Aucune matière.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
