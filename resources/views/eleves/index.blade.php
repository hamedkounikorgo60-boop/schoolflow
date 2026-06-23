@extends('layouts.app')
@section('title', 'Liste des élèves')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-semibold mb-0">Liste des élèves</h5>
    <a href="{{ route('gestionnaire.eleves.create') }}" class="btn btn-primary btn-sm">+ Nouvel élève</a>
</div>
<div class="card stat-card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Photo</th><th>Matricule</th><th>Nom complet</th>
                    <th>Classe</th><th>Genre</th><th>Statut</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($eleves as $eleve)
                <tr>
                    <td>
                        @if($eleve->photo)
                            <img src="{{ asset('storage/'.$eleve->photo) }}"
                                 class="rounded-circle" width="38" height="38" style="object-fit:cover">
                        @else
                            <div class="rounded-circle bg-primary bg-opacity-25 d-flex align-items-center
                                        justify-content-center text-primary fw-bold"
                                 style="width:38px;height:38px;font-size:16px">
                                {{ strtoupper(substr($eleve->nom,0,1)) }}
                            </div>
                        @endif
                    </td>
                    <td class="font-monospace small">{{ $eleve->matricule }}</td>
                    <td class="fw-medium">{{ $eleve->nom }} {{ $eleve->prenoms }}</td>
                    <td>{{ $eleve->classe->nom ?? '-' }}</td>
                    <td>{{ $eleve->genre == 'M' ? 'Masculin' : 'Féminin' }}</td>
                    <td>
                        <span class="badge badge-pill bg-{{ $eleve->statut == 'actif' ? 'success' : 'secondary' }}">
                            {{ $eleve->statut }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('gestionnaire.eleves.show', $eleve) }}"
                           class="btn btn-sm btn-outline-info py-0">Voir</a>
                        <a href="{{ route('gestionnaire.eleves.edit', $eleve) }}"
                           class="btn btn-sm btn-outline-warning py-0">Modifier</a>
                        <form method="POST" action="{{ route('gestionnaire.eleves.destroy', $eleve) }}"
                              class="d-inline" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger py-0">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Aucun élève inscrit.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $eleves->links() }}</div>
@endsection
