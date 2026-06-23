@extends('layouts.app')
@section('title', 'Matières')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('enseignant.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Tableau de bord</a>
        <span class="text-muted">/</span>
        <span class="fw-semibold">Matières</span>
    </div>
    <a href="{{ route('enseignant.matieres.create') }}" class="btn btn-success btn-sm">
        + Ajouter une matière
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Matière</th>
                    <th class="px-4 py-3">Coefficient</th>
                    <th class="px-4 py-3">Niveau</th>
                    <th class="px-4 py-3">Filière</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matieres as $matiere)
                <tr>
                    <td class="px-4 py-3 text-muted small">{{ $matiere->id }}</td>
                    <td class="px-4 py-3 fw-semibold">{{ $matiere->nom }}</td>
                    <td class="px-4 py-3">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                            Coef. {{ $matiere->coefficient }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $matiere->niveau ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $matiere->filiere ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <div class="d-flex gap-2">
                            <a href="{{ route('enseignant.matieres.edit', $matiere) }}"
                               class="btn btn-warning btn-sm">✏️ Modifier</a>
                            <form action="{{ route('enseignant.matieres.destroy', $matiere) }}"
                                  method="POST" style="display:inline"
                                  onsubmit="return confirm('Supprimer cette matière ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">🗑 Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        Aucune matière enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
