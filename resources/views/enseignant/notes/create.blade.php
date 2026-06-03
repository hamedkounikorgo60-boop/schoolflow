@extends('layouts.app')
@section('title', 'Saisir une note')
@section('content')

<div class="row justify-content-center">
<div class="col-md-7">

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('enseignant.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Tableau de bord</a>
    <span class="text-muted">/</span>
    <a href="{{ route('enseignant.notes.index') }}" class="text-muted text-decoration-none">Notes</a>
    <span class="text-muted">/</span>
    <span class="fw-semibold">Saisir une note</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white py-3 px-4">
        <h6 class="mb-0 fw-semibold">📝 Saisie de note</h6>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('enseignant.notes.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-medium">Classe</label>
                <select id="filtre-classe" class="form-select">
                    <option value="">-- Filtrer par classe --</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Élève <span class="text-danger">*</span></label>
                <select name="eleve_id" id="select-eleve"
                        class="form-select @error('eleve_id') is-invalid @enderror" required>
                    <option value="">-- Choisir un élève --</option>
                    @foreach($eleves as $eleve)
                        <option value="{{ $eleve->id }}"
                                data-classe="{{ $eleve->classe_id }}"
                                {{ old('eleve_id') == $eleve->id ? 'selected' : '' }}>
                            {{ $eleve->nom }} {{ $eleve->prenoms }}
                            ({{ $eleve->classe->nom ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('eleve_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Matière <span class="text-danger">*</span></label>
                <select name="matiere_id"
                        class="form-select @error('matiere_id') is-invalid @enderror" required>
                    <option value="">-- Choisir une matière --</option>
                    @foreach($matieres as $matiere)
                        <option value="{{ $matiere->id }}"
                                {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                            {{ $matiere->nom }} (Coef. {{ $matiere->coefficient }})
                        </option>
                    @endforeach
                </select>
                @error('matiere_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Trimestre <span class="text-danger">*</span></label>
                <div class="d-flex gap-2">
                    @foreach([1,2,3] as $t)
                    <div class="flex-fill">
                        <input type="radio" class="btn-check" name="trimestre"
                               id="trim{{ $t }}" value="trimestre{{ $t }}"
                               {{ old('trimestre') == 'trimestre'.$t ? 'checked' : '' }} required>
                        <label class="btn btn-outline-primary w-100" for="trim{{ $t }}">
                            Trimestre {{ $t }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('trimestre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Note /20 <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" name="note" id="input-note"
                           class="form-control form-control-lg @error('note') is-invalid @enderror"
                           value="{{ old('note') }}" min="0" max="20" step="0.25"
                           placeholder="Ex: 14.5" required>
                    <span class="input-group-text fw-bold">/20</span>
                </div>
                <div class="mt-2">
                    <div class="progress" style="height:6px;">
                        <div id="note-bar" class="progress-bar bg-success" style="width:0%"></div>
                    </div>
                    <div id="note-label" class="text-muted small mt-1">Entrez une note</div>
                </div>
                @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">✓ Enregistrer</button>
                <a href="{{ route('enseignant.dashboard') }}"
                   class="btn btn-outline-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<script>
document.getElementById('filtre-classe').addEventListener('change', function() {
    const classeId = this.value;
    document.querySelectorAll('#select-eleve option').forEach(opt => {
        if (!opt.value) return;
        opt.style.display = (!classeId || opt.dataset.classe == classeId) ? '' : 'none';
    });
    document.getElementById('select-eleve').value = '';
});

document.getElementById('input-note').addEventListener('input', function() {
    const val = parseFloat(this.value);
    const bar = document.getElementById('note-bar');
    const lbl = document.getElementById('note-label');
    if (isNaN(val)) { bar.style.width='0%'; lbl.textContent='Entrez une note'; return; }
    bar.style.width = (val / 20 * 100) + '%';
    bar.className = 'progress-bar ' + (val >= 14 ? 'bg-success' : val >= 10 ? 'bg-warning' : 'bg-danger');
    const mention = val >= 16 ? 'Excellent' : val >= 14 ? 'Très bien' : val >= 12 ? 'Bien' : val >= 10 ? 'Assez bien' : 'Insuffisant';
    lbl.textContent = mention + ' (' + val + '/20)';
    lbl.className = 'small mt-1 ' + (val >= 14 ? 'text-success' : val >= 10 ? 'text-warning' : 'text-danger');
});
</script>
@endsection
