@extends('layouts.app')
@section('title', 'Nouvel enseignant')
@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('gestionnaire.enseignants.index') }}" class="btn btn-outline-secondary btn-sm">← Enseignants</a>
    <span class="text-muted">/</span>
    <span class="fw-semibold">Nouvel enseignant</span>
</div>

<div class="card border-0 shadow-sm mx-auto" style="max-width:720px">
    <div class="card-header bg-primary text-white py-3 px-4">
        <h6 class="mb-0 fw-semibold">👨‍🏫 Créer un compte enseignant</h6>
        <small class="opacity-75">Le mot de passe servira à la première connexion sur la page de login.</small>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('gestionnaire.enseignants.store') }}">
            @csrf

            <h6 class="fw-semibold text-muted mb-3">Identité & connexion</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nom complet <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">E-mail <span class="text-danger">*</span></label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           minlength="8" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Confirmer le mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Téléphone</label>
                    <input type="text" name="telephone" class="form-control"
                           value="{{ old('telephone') }}" placeholder="Ex: 70 12 34 56">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Spécialité</label>
                    <input type="text" name="specialite" class="form-control"
                           value="{{ old('specialite') }}" placeholder="Ex: Primaire, Français…">
                </div>
            </div>

            <hr>

            <h6 class="fw-semibold text-muted mb-3">Affectations (optionnel)</h6>
            <p class="text-muted small">Vous pourrez les modifier plus tard depuis la liste des enseignants.</p>

            <div class="mb-4">
                <label class="form-label fw-medium">Classes</label>
                <div class="row g-2">
                    @foreach($classes as $classe)
                    <div class="col-md-6">
                        <div class="form-check border rounded p-2">
                            <input class="form-check-input" type="checkbox"
                                   name="classe_ids[]" value="{{ $classe->id }}"
                                   id="classe{{ $classe->id }}"
                                   {{ in_array($classe->id, old('classe_ids', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="classe{{ $classe->id }}">
                                {{ $classe->nom }} ({{ $classe->niveau }})
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Matières enseignées</label>
                @if($matieres->isEmpty())
                    <p class="text-muted small">
                        Aucune matière dans le catalogue.
                        <a href="{{ route('gestionnaire.matieres.create') }}">Créer une matière</a> d'abord.
                    </p>
                @else
                <div class="row g-2">
                    @foreach($matieres as $matiere)
                    <div class="col-md-6">
                        <div class="form-check border rounded p-2">
                            <input class="form-check-input" type="checkbox"
                                   name="matiere_ids[]" value="{{ $matiere->id }}"
                                   id="mat{{ $matiere->id }}"
                                   {{ in_array($matiere->id, old('matiere_ids', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="mat{{ $matiere->id }}">
                                {{ $matiere->nom }} <span class="text-muted small">(Coef. {{ $matiere->coefficient }})</span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Créer l'enseignant</button>
                <a href="{{ route('gestionnaire.enseignants.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</div>

@endsection
