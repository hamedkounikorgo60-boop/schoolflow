@extends('layouts.app')
@section('title', 'Inscrire un élève')
@section('content')
<div class="card stat-card mx-auto" style="max-width:700px">
    <div class="card-header text-white fw-semibold" style="background:#4338ca">
        Inscription d'un nouvel élève
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('gestionnaire.eleves.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Matricule *</label>
                    <input type="text" name="matricule" class="form-control"
                           value="{{ old('matricule') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Classe *</label>
                    <select name="classe_id" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}"
                                {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nom *</label>
                    <input type="text" name="nom" class="form-control"
                           value="{{ old('nom') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Prénoms *</label>
                    <input type="text" name="prenoms" class="form-control"
                           value="{{ old('prenoms') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Date de naissance *</label>
                    <input type="date" name="date_naissance" class="form-control"
                           value="{{ old('date_naissance') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Lieu de naissance *</label>
                    <input type="text" name="lieu_naissance" class="form-control"
                           value="{{ old('lieu_naissance') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Genre *</label>
                    <select name="genre" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        <option value="M" {{ old('genre') == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('genre') == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Téléphone</label>
                    <input type="text" name="telephone" class="form-control"
                           value="{{ old('telephone') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Adresse</label>
                    <input type="text" name="adresse" class="form-control"
                           value="{{ old('adresse') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Statut *</label>
                    <select name="statut" class="form-select" required>
                        <option value="actif" selected>Actif</option>
                        <option value="inactif">Inactif</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Photo</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="redoublant" class="form-check-input"
                               id="redoublant" {{ old('redoublant') ? 'checked' : '' }}>
                        <label class="form-check-label" for="redoublant">Élève redoublant</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Inscrire</button>
                <a href="{{ route('gestionnaire.eleves.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
