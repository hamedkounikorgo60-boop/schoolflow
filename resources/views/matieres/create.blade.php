@extends('layouts.app')
@section('title', 'Ajouter une matière')
@section('content')

<div class="row justify-content-center">
<div class="col-md-6">

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('enseignant.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Tableau de bord</a>
    <span class="text-muted">/</span>
    <a href="{{ route('enseignant.matieres.index') }}" class="text-muted text-decoration-none">Matières</a>
    <span class="text-muted">/</span>
    <span class="fw-semibold">Ajouter</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-success text-white py-3 px-4">
        <h6 class="mb-0 fw-semibold">📚 Nouvelle matière</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('enseignant.matieres.store') }}" method="POST">
            @csrf

            {{-- Nom --}}
            <div class="mb-3">
                <label class="form-label fw-medium">Nom de la matière <span class="text-danger">*</span></label>
                <input type="text" name="nom"
                       class="form-control @error('nom') is-invalid @enderror"
                       value="{{ old('nom') }}"
                       placeholder="Ex: Mathématiques, Français..."
                       required>
                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Coefficient --}}
            <div class="mb-3">
                <label class="form-label fw-medium">Coefficient <span class="text-danger">*</span></label>
                <div class="d-flex gap-2 flex-wrap">
                    @foreach([1, 2, 3, 4, 5] as $coef)
                    <div>
                        <input type="radio" class="btn-check" name="coefficient"
                               id="coef{{ $coef }}" value="{{ $coef }}"
                               {{ old('coefficient') == $coef ? 'checked' : '' }} required>
                        <label class="btn btn-outline-success" for="coef{{ $coef }}">
                            {{ $coef }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('coefficient')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            {{-- Niveau --}}
            <div class="mb-3">
                <label class="form-label fw-medium">Niveau <span class="text-danger">*</span></label>
                <select name="niveau" class="form-select @error('niveau') is-invalid @enderror" required>
                    <option value="">-- Choisir un niveau --</option>
                    @foreach(['CP1','CP2','CE1','CE2','CM1','CM2'] as $niv)
                        <option value="{{ $niv }}" {{ old('niveau') == $niv ? 'selected' : '' }}>
                            {{ $niv }}
                        </option>
                    @endforeach
                </select>
                @error('niveau')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Filière --}}
            <div class="mb-4">
                <label class="form-label fw-medium">Filière / Section <span class="text-danger">*</span></label>
                <div class="d-flex gap-2 flex-wrap">
                    @foreach(['Générale', 'Sciences', 'Lettres', 'Technique', 'Autre'] as $fil)
                    <div>
                        <input type="radio" class="btn-check" name="filiere"
                               id="fil{{ $loop->index }}" value="{{ $fil }}"
                               {{ old('filiere') == $fil ? 'checked' : '' }} required>
                        <label class="btn btn-outline-secondary" for="fil{{ $loop->index }}">
                            {{ $fil }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('filiere')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success px-4">
                    ✓ Enregistrer
                </button>
                <a href="{{ route('enseignant.matieres.index') }}"
                   class="btn btn-outline-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection
