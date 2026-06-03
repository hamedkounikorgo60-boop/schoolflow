@extends('layouts.app')
@section('title', 'Nouvelle classe')
@section('content')
<div class="card stat-card mx-auto" style="max-width:500px">
    <div class="card-header text-white fw-semibold" style="background:#4338ca">Créer une classe</div>
    <div class="card-body">
        <form method="POST" action="{{ route('gestionnaire.classes.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-medium">Nom *</label>
                <input type="text" name="nom" class="form-control"
                       value="{{ old('nom') }}" placeholder="Ex: CP1-A" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Niveau *</label>
                <select name="niveau" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    @foreach(['CP1','CP2','CE1','CE2','CM1','CM2'] as $n)
                        <option value="{{ $n }}" {{ old('niveau') == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-medium">Frais de scolarité (FCFA) *</label>
                <input type="number" name="frais_scolarite" class="form-control"
                       value="{{ old('frais_scolarite') }}" min="0" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Créer</button>
                <a href="{{ route('gestionnaire.classes.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
