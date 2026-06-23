@extends('layouts.app')
@section('title', 'Parents')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Parents</h3>
        <p class="text-muted mb-0">G&eacute;rez les comptes parents et assignez leurs enfants.</p>
    </div>
    <a href="{{ route('gestionnaire.parents.create') }}" class="btn btn-primary btn-sm">
        + Nouveau parent
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="px-4 py-3">Parent</th>
                    <th class="px-4 py-3">T&eacute;l&eacute;phone</th>
                    <th class="px-4 py-3">Enfants</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parents as $parent)
                <tr>
                    <td class="px-4 py-3">
                        <div class="fw-semibold">{{ $parent->name }}</div>
                        <div class="text-muted small">{{ $parent->email }}</div>
                    </td>
                    <td class="px-4 py-3">
                        {{ $parent->telephone ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        @forelse($parent->eleves as $eleve)
                            <span class="badge bg-info-subtle text-info border me-1 mb-1">
                                {{ $eleve->nom }} {{ $eleve->prenoms }}
                                @if($eleve->classe)
                                    ({{ $eleve->classe->nom }})
                                @endif
                            </span>
                        @empty
                            <span class="text-muted small">Aucun enfant assign&eacute;</span>
                        @endforelse
                    </td>
                    <td class="px-4 py-3">
                        <div class="d-flex gap-1">
                            <a href="{{ route('gestionnaire.parents.show', $parent) }}"
                               class="btn btn-outline-info btn-sm" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('gestionnaire.parents.edit', $parent) }}"
                               class="btn btn-warning btn-sm" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('gestionnaire.parents.destroy', $parent) }}"
                                  onsubmit="return confirm('Supprimer ce parent ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        Aucun parent enregistr&eacute;.
                        <a href="{{ route('gestionnaire.parents.create') }}">Ajouter le premier</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
