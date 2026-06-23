@extends('layouts.app')
@section('title', 'Nouveau parent')
@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('gestionnaire.parents.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Parents</a>
    <span class="text-muted">/</span>
    <span class="fw-semibold">Nouveau parent</span>
</div>

<div class="card border-0 shadow-sm mx-auto" style="max-width:720px">
    <div class="card-header bg-primary text-white py-3 px-4">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-person-plus me-2"></i>Cr&eacute;er un compte parent</h6>
        <small class="opacity-75">Le mot de passe servira &agrave; la connexion sur l'application mobile.</small>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('gestionnaire.parents.store') }}">
            @csrf

            <h6 class="fw-semibold text-muted mb-3">Identit&eacute; & connexion</h6>
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
                    <label class="form-label fw-medium">T&eacute;l&eacute;phone</label>
                    <input type="text" name="telephone"
                           class="form-control @error('telephone') is-invalid @enderror"
                           value="{{ old('telephone') }}" placeholder="Ex: 70 12 34 56">
                    @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Adresse</label>
                    <input type="text" name="adresse"
                           class="form-control @error('adresse') is-invalid @enderror"
                           value="{{ old('adresse') }}" placeholder="Ex: Ouagadougou, Secteur 30">
                    @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>

            <h6 class="fw-semibold text-muted mb-3">Enfants (optionnel)</h6>
            <p class="text-muted small">S&eacute;lectionnez les &eacute;l&egrave;ves qui sont les enfants de ce parent. Seuls les &eacute;l&egrave;ves non assign&eacute;s sont affich&eacute;s.</p>

            @if($eleves->isEmpty())
                <p class="text-muted small">Aucun &eacute;l&egrave;ve disponible. <a href="{{ route('gestionnaire.eleves.create') }}">Inscrire un &eacute;l&egrave;ve</a> d'abord.</p>
            @else
                <div class="row g-2 mb-4">
                    @foreach($eleves as $eleve)
                    <div class="col-md-6">
                        <div class="form-check border rounded p-2">
                            <input class="form-check-input" type="checkbox"
                                   name="eleve_ids[]" value="{{ $eleve->id }}"
                                   id="eleve{{ $eleve->id }}"
                                   {{ in_array($eleve->id, old('eleve_ids', [])) ? 'checked' : '' }}>
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
                <button type="submit" class="btn btn-primary px-4">Cr&eacute;er le parent</button>
                <a href="{{ route('gestionnaire.parents.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</div>

@endsection
