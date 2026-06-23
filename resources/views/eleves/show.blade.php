@extends('layouts.app')
@section('title', 'Fiche élève')
@section('content')
<div class="card stat-card mx-auto" style="max-width:700px">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Fiche de {{ $eleve->nom }} {{ $eleve->prenoms }}</span>
        <a href="{{ route('gestionnaire.eleves.index') }}" class="btn btn-sm btn-light">Retour</a>
    </div>
    <div class="card-body">
        <div class="d-flex gap-4 mb-4">
            <div class="flex-shrink-0">
                @if($eleve->photo)
                    <img src="{{ asset('storage/'.$eleve->photo) }}"
                         class="rounded-circle" width="90" height="90" style="object-fit:cover">
                @else
                    <div class="rounded-circle bg-primary bg-opacity-25 d-flex align-items-center
                                justify-content-center text-primary fw-bold"
                         style="width:90px;height:90px;font-size:32px">
                        {{ strtoupper(substr($eleve->nom,0,1)) }}
                    </div>
                @endif
            </div>
            <div class="flex-grow-1">
                <table class="table table-sm">
                    <tr><th class="text-muted fw-normal" style="width:160px">Matricule</th><td>{{ $eleve->matricule }}</td></tr>
                    <tr><th class="text-muted fw-normal">Nom complet</th><td>{{ $eleve->nom }} {{ $eleve->prenoms }}</td></tr>
                    <tr><th class="text-muted fw-normal">Date de naissance</th><td>{{ $eleve->date_naissance }}</td></tr>
                    <tr><th class="text-muted fw-normal">Lieu de naissance</th><td>{{ $eleve->lieu_naissance }}</td></tr>
                    <tr><th class="text-muted fw-normal">Genre</th><td>{{ $eleve->genre == 'M' ? 'Masculin' : 'Féminin' }}</td></tr>
                    <tr><th class="text-muted fw-normal">Classe</th><td>{{ $eleve->classe->nom ?? '-' }}</td></tr>
                    <tr><th class="text-muted fw-normal">Téléphone</th><td>{{ $eleve->telephone ?? '-' }}</td></tr>
                    <tr><th class="text-muted fw-normal">Adresse</th><td>{{ $eleve->adresse ?? '-' }}</td></tr>
                    <tr><th class="text-muted fw-normal">Redoublant</th><td>{{ $eleve->redoublant ? 'Oui' : 'Non' }}</td></tr>
                    <tr>
                        <th class="text-muted fw-normal">Statut</th>
                        <td>
                            <span class="badge bg-{{ $eleve->statut == 'actif' ? 'success' : 'secondary' }}">
                                {{ $eleve->statut }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('gestionnaire.eleves.edit', $eleve) }}" class="btn btn-warning btn-sm">Modifier</a>
            <form method="POST" action="{{ route('gestionnaire.eleves.destroy', $eleve) }}"
                  onsubmit="return confirm('Supprimer cet élève ?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">Supprimer</button>
            </form>
        </div>
    </div>
</div>
@endsection
