@extends('layouts.app')
@section('title', 'Enseignants')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Enseignants</h3>
        <p class="text-muted mb-0">Assignez les classes et les matières (cours) à chaque enseignant.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('gestionnaire.matieres.index') }}" class="btn btn-outline-primary btn-sm">
            📚 Matières
        </a>
        <a href="{{ route('gestionnaire.enseignants.create') }}" class="btn btn-primary btn-sm">
            + Nouvel enseignant
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="px-4 py-3">Enseignant</th>
                    <th class="px-4 py-3">Classes</th>
                    <th class="px-4 py-3">Matières enseignées</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enseignants as $ens)
                <tr>
                    <td class="px-4 py-3">
                        <div class="fw-semibold">{{ $ens->name }}</div>
                        <div class="text-muted small">{{ $ens->email }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @forelse($ens->classes->unique('id') as $classe)
                            <span class="badge bg-success-subtle text-success border me-1 mb-1">
                                {{ $classe->nom }} ({{ $classe->niveau }})
                            </span>
                        @empty
                            <span class="text-muted small">Aucune classe</span>
                        @endforelse
                    </td>
                    <td class="px-4 py-3">
                        @forelse($ens->enseignant?->matieres ?? [] as $matiere)
                            <span class="badge bg-primary-subtle text-primary border me-1 mb-1">
                                {{ $matiere->nom }}
                            </span>
                        @empty
                            <span class="text-muted small">Aucune matière</span>
                        @endforelse
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('gestionnaire.enseignants.edit', $ens) }}"
                           class="btn btn-warning btn-sm">
                            Assigner classes & cours
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        Aucun enseignant.
                        <a href="{{ route('gestionnaire.enseignants.create') }}">Ajouter le premier</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
