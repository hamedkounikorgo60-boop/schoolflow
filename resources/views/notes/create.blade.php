@extends('layouts.app')

@section('title', 'Saisie des notes')

@section('content')
<div class="card stat-card mx-auto" style="max-width:500px">
    <div class="card-header bg-info text-white fw-semibold">
        Saisir une note
    </div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('gestionnaire.notes.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-medium">Élève *</label>
                <select name="eleve_id" class="form-select" required>
                    <option value="">-- Choisir un élève --</option>
                    @foreach($eleves as $eleve)
                        <option value="{{ $eleve->id }}" {{ old('eleve_id') == $eleve->id ? 'selected' : '' }}>
                            {{ $eleve->nom }} {{ $eleve->prenoms }}
                            ({{ $eleve->classe->nom ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Matière *</label>
                <select name="matiere_id" class="form-select" required>
                    <option value="">-- Choisir une matière --</option>
                    @foreach($matieres as $matiere)
                        <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                            {{ $matiere->nom }}
                            (Coeff. {{ $matiere->coefficient }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Trimestre *</label>
                <select name="trimestre" class="form-select" required>
                    <option value="">-- Choisir --</option>

                    <option value="trimestre1"
                        {{ old('trimestre') == 'trimestre1' ? 'selected' : '' }}>
                        Trimestre 1
                    </option>

                    <option value="trimestre2"
                        {{ old('trimestre') == 'trimestre2' ? 'selected' : '' }}>
                        Trimestre 2
                    </option>

                    <option value="trimestre3"
                        {{ old('trimestre') == 'trimestre3' ? 'selected' : '' }}>
                        Trimestre 3
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Note (sur 20) *</label>

                <input
                    type="number"
                    name="note"
                    class="form-control"
                    value="{{ old('note') }}"
                    min="0"
                    max="20"
                    step="0.5"
                    required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-info text-white">
                    Enregistrer
                </button>

                <a href="{{ route('gestionnaire.notes.index') }}"
                   class="btn btn-secondary">
                    Voir les notes
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
