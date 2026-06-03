@extends('layouts.app')

@section('title', 'Gestion des classes')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Gestion des classes</h3>

    <a href="{{ route('gestionnaire.classes.create') }}"
       class="btn btn-primary">
        + Nouvelle classe
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nom</th>
                    <th>Niveau</th>
                    <th>Frais</th>
                    <th>Élèves</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($classes as $classe)
                <tr>
                    <td>
                        <strong>{{ $classe->nom }}</strong>
                    </td>

                    <td>{{ $classe->niveau }}</td>

                    <td>
                        <div><strong>Scolarité :</strong> {{ number_format($classe->frais_scolarite,0,',',' ') }} FCFA</div>
                        <div><strong>Inscription :</strong> {{ number_format($classe->frais_inscription,0,',',' ') }} FCFA</div>
                        <div><strong>Cantine :</strong> {{ number_format($classe->frais_cantine,0,',',' ') }} FCFA</div>
                        <div><strong>Transport :</strong> {{ number_format($classe->frais_transport,0,',',' ') }} FCFA</div>
                        <div><strong>Fournitures :</strong> {{ number_format($classe->frais_fournitures,0,',',' ') }} FCFA</div>
                        <div><strong>Autres :</strong> {{ number_format($classe->autres_frais,0,',',' ') }} FCFA</div>
                    </td>

                    <td>
                        <span class="badge bg-primary">
                            {{ $classe->eleves_count ?? $classe->eleves->count() }}
                        </span>
                    </td>

                    <td>
                        <a href="{{ route('gestionnaire.classes.edit', $classe) }}"
                           class="btn btn-warning btn-sm">
                            Modifier
                        </a>

                        <form action="{{ route('gestionnaire.classes.destroy', $classe) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Supprimer cette classe ?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Aucune classe enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

@endsection
