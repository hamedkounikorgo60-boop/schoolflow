@extends('layouts.app')
@section('title', 'Nouvelle matière')
@section('content')

<div class="row justify-content-center">
<div class="col-md-6">

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('gestionnaire.matieres.index') }}" class="btn btn-outline-secondary btn-sm">← Matières</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white py-3 px-4">
        <h6 class="mb-0 fw-semibold">📚 Nouvelle matière (catalogue)</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('gestionnaire.matieres.store') }}" method="POST">
            @csrf
            @if($classe ?? null)
                <input type="hidden" name="classe_id" value="{{ $classe->id }}">
                <div class="alert alert-info small py-2">
                    Niveau prérempli pour la classe <strong>{{ $classe->nom }}</strong> ({{ $classe->niveau }}).
                </div>
            @endif
            @include('gestionnaire.matieres._form', ['classe' => $classe ?? null])
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">Enregistrer</button>
                <a href="{{ route('gestionnaire.matieres.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection
