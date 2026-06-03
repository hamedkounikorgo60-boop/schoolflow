@extends('layouts.app')
@section('title', 'Modifier un élève')
@section('content')
<div class="card stat-card mx-auto" style="max-width:700px">
    <div class="card-header bg-warning fw-semibold">
        Modifier : {{ $eleve->nom }} {{ $eleve->prenoms }}
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('gestionnaire.eleves.update', $eleve) }}"
              enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Matricule *</label>
                    <input type="text" name="matricule" class="form-control"
                           value="{{ old('matricule', $eleve->matricule) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Classe *</label>
                    <select name="classe_id" class="form-select" required>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}"
                                {{ old('classe_id', $eleve->classe_id) == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nom *</label>
                    <input type="text" name="nom" class="form-control"
                           value="{{ old('nom', $eleve->nom) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Prénoms *</label>
                    <input type="text" name="prenoms" class="form-control"
                           value="{{ old('prenoms', $eleve->prenoms) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Date de naissance *</label>
                    <input type="date" name="date_naissance" class="form-control"
                           value="{{ old('date_naissance', $eleve->date_naissance) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Lieu de naissance *</label>
                    <input type="text" name="lieu_naissance" class="form-control"
                           value="{{ old('lieu_naissance', $eleve->lieu_naissance) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Genre *</label>
                    <select name="genre" class="form-select" required>
                        <option value="M" {{ old('genre', $eleve->genre) == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('genre', $eleve->genre) == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Téléphone</label>
                    <input type="text" name="telephone" class="form-control"
                           value="{{ old('telephone', $eleve->telephone) }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Adresse</label>
                    <input type="text" name="adresse" class="form-control"
                           value="{{ old('adresse', $eleve->adresse) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Statut *</label>
                    <select name="statut" class="form-select" required>
                        <option value="actif" {{ old('statut', $eleve->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ old('statut', $eleve->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Photo</label>
                    @if($eleve->photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/'.$eleve->photo) }}"
                                 class="rounded-circle" width="50" height="50" style="object-fit:cover">
                        </div>
                    @endif
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="redoublant" class="form-check-input"
                               id="redoublant" {{ old('redoublant', $eleve->redoublant) ? 'checked' : '' }}>
                        <label class="form-check-label" for="redoublant">Élève redoublant</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-warning">Modifier</button>
                <a href="{{ route('gestionnaire.eleves.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
