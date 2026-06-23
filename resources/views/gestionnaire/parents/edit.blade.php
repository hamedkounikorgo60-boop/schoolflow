@extends('layouts.app')
@section('title', 'Modifier — ' . $user->name)
@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('gestionnaire.parents.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Parents</a>
    <span class="text-muted">/</span>
    <span class="fw-semibold">{{ $user->name }}</span>
</div>

<div class="card border-0 shadow-sm mx-auto" style="max-width:720px">
    <div class="card-header bg-warning py-3 px-4">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-pencil me-2"></i>Modifier le parent &mdash; {{ $user->name }}</h6>
        <small class="text-dark opacity-75">Modifiez les informations et l'affectation des enfants.</small>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('gestionnaire.parents.update', $user) }}">
            @csrf
            @method('PUT')

            <h6 class="fw-semibold text-muted mb-3">Identit&eacute; & connexion</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nom complet <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">E-mail <span class="text-danger">*</span></label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nouveau mot de passe</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           minlength="8" placeholder="Laisser vide pour ne pas changer">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control" minlength="8">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">T&eacute;l&eacute;phone</label>
                    <input type="text" name="telephone"
                           class="form-control @error('telephone') is-invalid @enderror"
                           value="{{ old('telephone', $user->telephone) }}" placeholder="Ex: 70 12 34 56">
                    @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Adresse</label>
                    <input type="text" name="adresse"
                           class="form-control @error('adresse') is-invalid @enderror"
                           value="{{ old('adresse', $user->adresse) }}" placeholder="Ex: Ouagadougou, Secteur 30">
                    @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>

            <h6 class="fw-semibold text-muted mb-3">Enfants assign&eacute;s</h6>
            <p class="text-muted small">Cochez les &eacute;l&egrave;ves qui sont les enfants de ce parent.</p>

            @if($eleves->isEmpty())
                <p class="text-muted small">Aucun &eacute;l&egrave;ve disponible.</p>
            @else
                <div class="row g-2 mb-4">
                    @foreach($eleves as $eleve)
                    <div class="col-md-6">
                        <div class="form-check border rounded p-2">
                            <input class="form-check-input" type="checkbox"
                                   name="eleve_ids[]" value="{{ $eleve->id }}"
                                   id="eleve{{ $eleve->id }}"
                                   {{ in_array($eleve->id, old('eleve_ids', $assignedEleves)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="eleve{{ $eleve->id }}">
                                <strong>{{ $eleve->nom }} {{ $eleve->prenoms }}</strong>
                                @if($eleve->classe)
                                    <span class="text-muted small">({{ $eleve->classe->nom }})</span>
                                @endif
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning px-4">Enregistrer les modifications</button>
                <a href="{{ route('gestionnaire.parents.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</div>

@endsection
