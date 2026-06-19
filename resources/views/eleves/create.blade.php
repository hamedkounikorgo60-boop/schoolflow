@extends('layouts.app')
@section('title', 'Inscrire un élève')
@section('content')
<div class="card stat-card mx-auto" style="max-width:700px">
    <div class="card-header text-white fw-semibold" style="background:#4338ca">
        Inscription d'un nouvel élève
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('gestionnaire.eleves.store') }}" enctype="multipart/form-data">
            @csrf
            @include('eleves._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Inscrire</button>
                <a href="{{ route('gestionnaire.eleves.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
