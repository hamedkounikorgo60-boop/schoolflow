@extends('layouts.app')
@section('title', 'Parent — ' . $user->name)
@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('gestionnaire.parents.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Parents</a>
    <span class="text-muted">/</span>
    <span class="fw-semibold">{{ $user->name }}</span>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-person me-2"></i>Informations du parent</h6>
            </div>
            <div class="card-body p-4">
                <dl class="mb-0">
                    <dt class="text-muted small">Nom complet</dt>
                    <dd class="fw-semibold">{{ $user->name }}</dd>

                    <dt class="text-muted small">E-mail</dt>
                    <dd>{{ $user->email }}</dd>

                    <dt class="text-muted small">T&eacute;l&eacute;phone</dt>
                    <dd>{{ $user->telephone ?? '&mdash;' }}</dd>

                    <dt class="text-muted small">Adresse</dt>
                    <dd>{{ $user->adresse ?? '&mdash;' }}</dd>

                    <dt class="text-muted small">Inscrit le</dt>
                    <dd>{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                </dl>

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('gestionnaire.parents.edit', $user) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header py-3 px-4" style="background:#e0f2fe;color:#0369a1">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-people me-2"></i>Enfants ({{ $user->eleves->count() }})</h6>
            </div>
            <div class="card-body p-0">
                @if($user->eleves->isEmpty())
                    <div class="text-center text-muted py-5">
                        Aucun enfant assign&eacute; &agrave; ce parent.
                        <br>
                        <a href="{{ route('gestionnaire.parents.edit', $user) }}">Assigner des enfants</a>
                    </div>
                @else
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">Matricule</th>
                                <th class="px-4 py-3">&Eacute;l&egrave;ve</th>
                                <th class="px-4 py-3">Classe</th>
                                <th class="px-4 py-3">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->eleves as $eleve)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="badge bg-light text-dark border">{{ $eleve->matricule }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('gestionnaire.eleves.show', $eleve) }}" class="text-decoration-none">
                                        <div class="fw-semibold">{{ $eleve->nom }} {{ $eleve->prenoms }}</div>
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    @if($eleve->classe)
                                        <span class="badge bg-success-subtle text-success border">
                                            {{ $eleve->classe->nom }}
                                        </span>
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge {{ $eleve->statut === 'actif' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($eleve->statut) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
