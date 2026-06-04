@extends('layouts.app')
@section('title', 'Notes & Moyennes')
@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('enseignant.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Tableau de bord</a>
    <span class="text-muted">/</span>
    <span class="fw-semibold">Notes & Moyennes</span>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-semibold mb-0">Notes & Moyennes</h5>
    <a href="{{ route('enseignant.notes.create') }}" class="btn btn-primary btn-sm">
        + Saisie groupée
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small text-muted mb-1">Classe</label>
                <select name="classe_id" class="form-select form-select-sm">
                    <option value="">-- Choisir une classe --</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ $classe_id == $classe->id ? 'selected' : '' }}>
                            {{ $classe->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Trimestre</label>
                <select name="trimestre" class="form-select form-select-sm">
                    @foreach([1,2,3] as $t)
                        <option value="{{ $t }}" {{ $trimestre == $t ? 'selected' : '' }}>
                            Trimestre {{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm w-100">Afficher</button>
            </div>
        </form>
    </div>
</div>

@if($eleves->count() > 0)
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4 py-3">Rang</th>
                    <th class="px-4 py-3">Élève</th>
                    <th class="px-4 py-3">Moyenne</th>
                    <th class="px-4 py-3">Mention</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eleves as $rang => $eleve)
                @php
                    $mention = match(true) {
                        $eleve->moyenne >= 16 => ['label'=>'Excellent',  'class'=>'bg-success'],
                        $eleve->moyenne >= 14 => ['label'=>'Très bien',  'class'=>'bg-primary'],
                        $eleve->moyenne >= 12 => ['label'=>'Bien',       'class'=>'bg-info'],
                        $eleve->moyenne >= 10 => ['label'=>'Assez bien', 'class'=>'bg-warning'],
                        default               => ['label'=>'Insuffisant','class'=>'bg-danger'],
                    };
                @endphp
                <tr>
                    <td class="px-4 py-3 fs-5">
                        @if($rang == 0) 🥇
                        @elseif($rang == 1) 🥈
                        @elseif($rang == 2) 🥉
                        @else <span class="text-muted">{{ $rang + 1 }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 fw-semibold">{{ $eleve->nom }} {{ $eleve->prenoms }}</td>
                    <td class="px-4 py-3 fw-bold text-primary">{{ $eleve->moyenne ?? 'N/A' }}/20</td>
                    <td class="px-4 py-3">
                        @if($eleve->moyenne !== null)
                            <span class="badge {{ $mention['class'] }}">{{ $mention['label'] }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@elseif($classe_id)
<div class="alert alert-info">Aucun élève trouvé pour cette classe.</div>
@endif

@endsection
