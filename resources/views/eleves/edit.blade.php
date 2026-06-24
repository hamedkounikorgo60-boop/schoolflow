@extends('layouts.app')
@section('title', 'Modifier un élève')
@section('content')
<div class="card stat-card mx-auto" style="max-width:700px">
    <div class="card-header bg-warning fw-semibold">
        Modifier : {{ $eleve->nom }} {{ $eleve->prenoms }}
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('gestionnaire.eleves.update', $eleve) }}"
              enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('eleves._form', ['eleve' => $eleve])
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-warning">Modifier</button>
                <a href="{{ route('gestionnaire.eleves.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
