@extends('layouts.app')
@section('title', 'Saisir les notes')
@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('enseignant.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Tableau de bord</a>
    <span class="text-muted">/</span>
    <a href="{{ route('enseignant.notes.index') }}" class="text-muted text-decoration-none">Notes</a>
    <span class="text-muted">/</span>
    <span class="fw-semibold">Saisie groupée</span>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($classes->isEmpty())
    <div class="alert alert-warning">
        Aucune classe ne vous est assignée. Contactez le gestionnaire pour vous rattacher à une ou plusieurs classes.
    </div>
@elseif($matieres->isEmpty())
    <div class="alert alert-warning">
        Aucune matière ne vous est assignée.
        <a href="{{ route('enseignant.matieres.create') }}">Ajoutez une matière</a>
        ou demandez au gestionnaire de vous en attribuer.
    </div>
@else

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white py-3 px-4">
        <h6 class="mb-0 fw-semibold">📝 Saisie groupée par classe</h6>
    </div>
    <div class="card-body p-4">
        <form method="GET" action="{{ route('enseignant.notes.create') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-medium">Classe <span class="text-danger">*</span></label>
                <select name="classe_id" class="form-select" required onchange="this.form.submit()">
                    <option value="">-- Choisir une classe --</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                            {{ $classe->nom }} ({{ $classe->niveau }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium">Matière <span class="text-danger">*</span></label>
                <select name="matiere_id" class="form-select" required onchange="this.form.submit()"
                        {{ !$classeId ? 'disabled' : '' }}>
                    <option value="">
                        {{ $classeId ? '-- Choisir une matière --' : '-- Choisir une classe d\'abord --' }}
                    </option>
                    @foreach($matieres as $matiere)
                        <option value="{{ $matiere->id }}" {{ $matiereId == $matiere->id ? 'selected' : '' }}>
                            {{ $matiere->nom }} (Coef. {{ $matiere->coefficient }})
                        </option>
                    @endforeach
                </select>
                @if($classeId && $matieres->isEmpty())
                    <div class="text-warning small mt-1">Aucune matière pour ce niveau sur votre profil.</div>
                @endif
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium">Trimestre <span class="text-danger">*</span></label>
                <select name="trimestre" class="form-select" onchange="this.form.submit()">
                    @foreach(['trimestre1','trimestre2','trimestre3'] as $t)
                        <option value="{{ $t }}" {{ $trimestre == $t ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('trimestre', 'Trimestre ', $t)) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

@if($classeId && $matiereId && $eleves->isNotEmpty())
<form method="POST" action="{{ route('enseignant.notes.storeBulk') }}">
    @csrf
    <input type="hidden" name="classe_id" value="{{ $classeId }}">
    <input type="hidden" name="matiere_id" value="{{ $matiereId }}">
    <input type="hidden" name="trimestre" value="{{ $trimestre }}">

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-4">
            <span class="fw-semibold">{{ $eleves->count() }} élève(s)</span>
            <small class="text-muted">Laissez vide pour ne pas modifier une note</small>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Élève</th>
                        <th class="px-4 py-3" style="width:180px">Note /20</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eleves as $i => $eleve)
                    <tr>
                        <td class="px-4 py-3 text-muted">{{ $i + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="fw-medium">{{ $eleve->nom }} {{ $eleve->prenoms }}</div>
                            <div class="text-muted small">{{ $eleve->matricule ?? '' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="input-group input-group-sm">
                                <input type="number"
                                       name="notes[{{ $eleve->id }}]"
                                       class="form-control @error('notes.'.$eleve->id) is-invalid @enderror"
                                       value="{{ old('notes.'.$eleve->id, $notesExistantes[$eleve->id] ?? '') }}"
                                       min="0" max="20" step="0.25"
                                       placeholder="—">
                                <span class="input-group-text">/20</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white d-flex gap-2 py-3 px-4">
            <button type="submit" class="btn btn-primary px-4">✓ Enregistrer toutes les notes</button>
            <a href="{{ route('enseignant.dashboard') }}" class="btn btn-outline-secondary px-4">Annuler</a>
        </div>
    </div>
</form>
@elseif($classeId && $matiereId)
    <div class="alert alert-info">Aucun élève actif dans cette classe.</div>
@elseif($classeId)
    <div class="alert alert-info">Choisissez une matière et un trimestre pour afficher la grille de saisie.</div>
@else
    <div class="alert alert-info">Choisissez une classe pour commencer la saisie groupée.</div>
@endif

@endif

@endsection
