@props(['classes', 'classeId', 'trimestre', 'submitLabel' => 'Filtrer'])
<div class="card stat-card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <select name="classe_id" class="form-select">
                    <option value="">-- Choisir une classe --</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                            {{ $classe->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="trimestre" class="form-select">
                    <option value="1" {{ $trimestre == 1 ? 'selected' : '' }}>Trimestre 1</option>
                    <option value="2" {{ $trimestre == 2 ? 'selected' : '' }}>Trimestre 2</option>
                    <option value="3" {{ $trimestre == 3 ? 'selected' : '' }}>Trimestre 3</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">{{ $submitLabel }}</button>
            </div>
        </form>
    </div>
</div>
