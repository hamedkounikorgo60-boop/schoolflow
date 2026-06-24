@extends('layouts.app')
@section('title', 'Notes et moyennes')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-semibold mb-0">Notes et moyennes</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('gestionnaire.notes.classement') }}" class="btn btn-info btn-sm text-white">Classement</a>
        <a href="{{ route('gestionnaire.notes.create') }}" class="btn btn-primary btn-sm">+ Saisir une note</a>
    </div>
</div>
<x-trimestre-filter :classes="$classes" :classe-id="$classe_id" :trimestre="$trimestre" />
@if($eleves->count() > 0)
<div class="card stat-card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Rang</th><th>Élève</th><th>Moyenne</th><th>Appréciation</th></tr>
            </thead>
            <tbody>
                @foreach($eleves as $rang => $eleve)
                <tr>
                    <td>
                        <span class="badge {{ $rang == 0 ? 'bg-warning' : 'bg-light text-dark' }}">
                            {{ $rang + 1 }}
                        </span>
                    </td>
                    <td class="fw-medium">{{ $eleve->nom }} {{ $eleve->prenoms }}</td>
                    <td class="fw-bold">{{ $eleve->moyenne ?? 'N/A' }}/20</td>
                    <td><x-mention-badge :moyenne="$eleve->moyenne" /></td>
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
