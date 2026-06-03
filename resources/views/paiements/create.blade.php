@extends('layouts.app')
@section('title', 'Nouveau paiement')
@section('content')

<div class="row justify-content-center">
<div class="col-md-7">

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('gestionnaire.paiements.index') }}" class="text-muted text-decoration-none">← Paiements</a>
    <span class="text-muted">/</span>
    <span class="fw-semibold">Nouveau paiement</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-success text-white py-3 px-4">
        <h6 class="mb-0 fw-semibold">💳 Enregistrer un paiement</h6>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('gestionnaire.paiements.store') }}">
            @csrf

            {{-- Élève --}}
            <div class="mb-3">
                <label class="form-label fw-medium">Élève <span class="text-danger">*</span></label>
                <select name="eleve_id" class="form-select @error('eleve_id') is-invalid @enderror" required>
                    <option value="">-- Choisir un élève --</option>
                    @foreach($eleves as $eleve)
                        <option value="{{ $eleve->id }}"
                            {{ old('eleve_id', request('eleve_id')) == $eleve->id ? 'selected' : '' }}>
                            {{ $eleve->nom }} {{ $eleve->prenoms }}
                            ({{ $eleve->classe->nom ?? '-' }})
                            — {{ $eleve->matricule }}
                        </option>
                    @endforeach
                </select>
                @error('eleve_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Type de paiement --}}
            <div class="mb-3">
                <label class="form-label fw-medium">Type de paiement <span class="text-danger">*</span></label>
                <div class="row g-2" id="type-options">
                    @php
                        $types = [
                            ['val' => 'scolarite',   'label' => 'Scolarité',    'icon' => '🏫', 'color' => 'primary'],
                            ['val' => 'inscription', 'label' => 'Inscription',  'icon' => '📝', 'color' => 'success'],
                            ['val' => 'cantine',     'label' => 'Cantine',      'icon' => '🍽️', 'color' => 'warning'],
                            ['val' => 'transport',   'label' => 'Transport',    'icon' => '🚌', 'color' => 'info'],
                            ['val' => 'fournitures', 'label' => 'Fournitures',  'icon' => '📚', 'color' => 'secondary'],
                            ['val' => 'autre',       'label' => 'Autre',        'icon' => '💼', 'color' => 'dark'],
                        ];
                    @endphp
                    @foreach($types as $type)
                    <div class="col-4">
                        <input type="radio" class="btn-check" name="type_paiement"
                               id="type_{{ $type['val'] }}" value="{{ $type['val'] }}"
                               {{ old('type_paiement') == $type['val'] ? 'checked' : '' }} required>
                        <label class="btn btn-outline-{{ $type['color'] }} w-100 py-3" for="type_{{ $type['val'] }}">
                            <div class="fs-4">{{ $type['icon'] }}</div>
                            <div class="small fw-medium mt-1">{{ $type['label'] }}</div>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('type_paiement')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            {{-- Montant + Trimestre --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Montant (FCFA) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">F</span>
                        <input type="number" name="montant"
                               class="form-control @error('montant') is-invalid @enderror"
                               value="{{ old('montant') }}" min="1"
                               placeholder="Ex: 25000" required>
                    </div>
                    @error('montant')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Trimestre <span class="text-danger">*</span></label>
                    <select name="trimestre" class="form-select @error('trimestre') is-invalid @enderror" required>
                        <option value="">-- Choisir --</option>
                        <option value="1" {{ old('trimestre') == '1' ? 'selected' : '' }}>Trimestre 1</option>
                        <option value="2" {{ old('trimestre') == '2' ? 'selected' : '' }}>Trimestre 2</option>
                        <option value="3" {{ old('trimestre') == '3' ? 'selected' : '' }}>Trimestre 3</option>
                    </select>
                    @error('trimestre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Mois (optionnel) --}}
            <div class="mb-3">
                <label class="form-label fw-medium">Mois concerné <span class="text-muted small">(optionnel)</span></label>
                <select name="mois" class="form-select">
                    <option value="">-- Non spécifié --</option>
                    @foreach(['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'] as $mois)
                        <option value="{{ $mois }}" {{ old('mois') == $mois ? 'selected' : '' }}>{{ $mois }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date --}}
            <div class="mb-4">
                <label class="form-label fw-medium">Date de paiement <span class="text-danger">*</span></label>
                <input type="date" name="date_paiement"
                       class="form-control @error('date_paiement') is-invalid @enderror"
                       value="{{ old('date_paiement', date('Y-m-d')) }}" required>
                @error('date_paiement')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Boutons --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success px-4">
                    ✓ Enregistrer le paiement
                </button>
                <a href="{{ route('gestionnaire.paiements.index') }}"
                   class="btn btn-outline-secondary px-4">
                    Annuler
                </a>
            </div>

        </form>
    </div>
</div>

</div>
</div>

@endsection
