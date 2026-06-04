@extends('layouts.app')
@section('title', 'Modifier matière')
@section('content')

<div class="row justify-content-center">
<div class="col-md-6">

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('gestionnaire.matieres.index') }}" class="btn btn-outline-secondary btn-sm">← Matières</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-warning py-3 px-4">
        <h6 class="mb-0 fw-semibold">Modifier : {{ $matiere->nom }}</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('gestionnaire.matieres.update', $matiere) }}" method="POST">
            @csrf
            @method('PUT')
            @include('gestionnaire.matieres._form')
            <div class="d-flex flex-wrap gap-2 mt-4">
                <button type="submit" class="btn btn-warning px-4">Modifier</button>
                <a href="{{ route('gestionnaire.matieres.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
                @if($matiere->notes_count > 0)
                    <span class="btn btn-outline-danger disabled ms-auto" title="Des notes existent">
                        Supprimer ({{ $matiere->notes_count }} note(s) liée(s))
                    </span>
                @else
                    <form action="{{ route('gestionnaire.matieres.destroy', $matiere) }}"
                          method="POST" class="ms-auto"
                          onsubmit="return confirm('Supprimer cette matière ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger px-4">Supprimer</button>
                    </form>
                @endif
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection
