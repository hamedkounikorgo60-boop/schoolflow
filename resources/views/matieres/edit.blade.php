@extends('layouts.app')
@section('title', 'Modifier une matière')
@section('content')

<div class="row justify-content-center">
<div class="col-md-6">

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('enseignant.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Tableau de bord</a>
    <span class="text-muted">/</span>
    <a href="{{ route('enseignant.matieres.index') }}" class="text-muted text-decoration-none">Matières</a>
    <span class="text-muted">/</span>
    <span class="fw-semibold">Modifier</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-warning text-dark py-3 px-4">
        <h6 class="mb-0 fw-semibold">✏️ Modifier la matière</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('enseignant.matieres.update', $matiere) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom"
                       class="form-control @error('nom') is-invalid @enderror"
                       value="{{ old('nom', $matiere->nom) }}" required>
                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Coefficient <span class="text-danger">*</span></label>
                <div class="d-flex gap-2 flex-wrap">
                    @foreach([1,2,3,4,5] as $coef)
                    <div>
                        <input type="radio" class="btn-check" name="coefficient"
                               id="ecoef{{ $coef }}" value="{{ $coef }}"
                               {{ old('coefficient', $matiere->coefficient) == $coef ? 'checked' : '' }} required>
                        <label class="btn btn-outline-success" for="ecoef{{ $coef }}">{{ $coef }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Niveau <span class="text-danger">*</span></label>
                <select name="niveau" class="form-select @error('niveau') is-invalid @enderror" required>
                    @foreach(['CP1','CP2','CE1','CE2','CM1','CM2'] as $niv)
                        <option value="{{ $niv }}"
                            {{ old('niveau', $matiere->niveau) == $niv ? 'selected' : '' }}>
                            {{ $niv }}
                        </option>
                    @endforeach
                </select>
                @error('niveau')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Filière <span class="text-danger">*</span></label>
                <div class="d-flex gap-2 flex-wrap">
                    @foreach(['Générale','Sciences','Lettres','Technique','Autre'] as $fil)
                    <div>
                        <input type="radio" class="btn-check" name="filiere"
                               id="efil{{ $loop->index }}" value="{{ $fil }}"
                               {{ old('filiere', $matiere->filiere) == $fil ? 'checked' : '' }} required>
                        <label class="btn btn-outline-secondary" for="efil{{ $loop->index }}">{{ $fil }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning px-4">✓ Mettre à jour</button>
                <a href="{{ route('enseignant.matieres.index') }}"
                   class="btn btn-outline-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection
