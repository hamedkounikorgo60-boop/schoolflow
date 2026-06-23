<div class="mb-3">
    <label class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
           value="{{ old('nom', $matiere->nom ?? '') }}" required>
    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label fw-medium">Coefficient <span class="text-danger">*</span></label>
    <select name="coefficient" class="form-select" required>
        @foreach([1,2,3,4,5] as $c)
            <option value="{{ $c }}" {{ old('coefficient', $matiere->coefficient ?? '') == $c ? 'selected' : '' }}>{{ $c }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-medium">Niveau <span class="text-danger">*</span></label>
    <select name="niveau" class="form-select" required>
        @foreach(['CP1','CP2','CE1','CE2','CM1','CM2'] as $niv)
            <option value="{{ $niv }}" {{ old('niveau', $matiere->niveau ?? optional($classe)->niveau) == $niv ? 'selected' : '' }}>{{ $niv }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-medium">Filière <span class="text-danger">*</span></label>
    <select name="filiere" class="form-select" required>
        @foreach(['Générale','Sciences','Lettres','Technique','Autre'] as $fil)
            <option value="{{ $fil }}" {{ old('filiere', $matiere->filiere ?? 'Générale') == $fil ? 'selected' : '' }}>{{ $fil }}</option>
        @endforeach
    </select>
</div>
