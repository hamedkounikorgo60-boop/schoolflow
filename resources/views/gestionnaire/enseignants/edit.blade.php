@extends('layouts.app')
@section('title', 'Assigner — ' . $user->name)
@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('gestionnaire.enseignants.index') }}" class="btn btn-outline-secondary btn-sm">← Enseignants</a>
    <span class="text-muted">/</span>
    <span class="fw-semibold">{{ $user->name }}</span>
</div>

<div class="card border-0 shadow-sm mx-auto" style="max-width:720px">
    <div class="card-header bg-warning py-3 px-4">
        <h6 class="mb-0 fw-semibold">👨‍🏫 Classes et matières — {{ $user->name }}</h6>
        <small class="text-dark opacity-75">Plusieurs enseignants peuvent enseigner la même classe avec des matières différentes.</small>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('gestionnaire.enseignants.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="form-label fw-semibold">Classes assignées</label>
                <p class="text-muted small">Cochez toutes les classes que cet enseignant gère.</p>
                <div class="row g-2">
                    @foreach($classes as $classe)
                    <div class="col-md-6">
                        <div class="form-check border rounded p-2">
                            <input class="form-check-input" type="checkbox"
                                   name="classe_ids[]" value="{{ $classe->id }}"
                                   id="classe{{ $classe->id }}"
                                   {{ in_array($classe->id, old('classe_ids', $assignedClasses)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="classe{{ $classe->id }}">
                                <strong>{{ $classe->nom }}</strong>
                                <span class="text-muted">({{ $classe->niveau }})</span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($classes->isEmpty())
                    <p class="text-muted small">Aucune classe enregistrée.</p>
                @endif
            </div>

            <hr>

            <div class="mb-4">
                <label class="form-label fw-semibold">Matières / cours enseignés</label>
                <p class="text-muted small">
                    Ex. : un enseignant fait l’Anglais, un autre les Maths sur la même classe.
                    <a href="{{ route('gestionnaire.matieres.create') }}">Ajouter une matière au catalogue</a>
                </p>
                <div class="row g-2">
                    @foreach($matieres as $matiere)
                    <div class="col-md-6">
                        <div class="form-check border rounded p-2">
                            <input class="form-check-input" type="checkbox"
                                   name="matiere_ids[]" value="{{ $matiere->id }}"
                                   id="mat{{ $matiere->id }}"
                                   {{ in_array($matiere->id, old('matiere_ids', $assignedMatieres)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="mat{{ $matiere->id }}">
                                {{ $matiere->nom }}
                                <span class="badge bg-light text-dark border">Coef. {{ $matiere->coefficient }}</span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($matieres->isEmpty())
                    <p class="text-muted small">
                        Aucune matière dans le catalogue.
                        <a href="{{ route('gestionnaire.matieres.create') }}">Créer une matière</a>
                    </p>
                @endif
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning px-4">Enregistrer les affectations</button>
                <a href="{{ route('gestionnaire.enseignants.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</div>

@endsection
