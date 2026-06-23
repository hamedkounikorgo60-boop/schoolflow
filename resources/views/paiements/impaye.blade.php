@extends('layouts.app')

@section('title', 'Élèves Impayés')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-danger mb-0">
                Élèves en retard de paiement
            </h2>
            <small class="text-muted">
                Liste des élèves ayant un solde restant à payer
            </small>
        </div>

        <a href="{{ route('gestionnaire.paiements.index') }}"
           class="btn btn-primary">
            ← Retour aux paiements
        </a>
    </div>

    <div class="card border-0 shadow">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Liste des impayés</h5>
        </div>

        <div class="card-body p-0">

        @if($impayes->count())

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Élève</th>
                                <th>Classe</th>
                                <th>Frais total</th>
                                <th>Montant payé</th>
                                <th>Reste à payer</th>
                                <th>Statut</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($impayes as $index => $impaye)

                        <tr>
                             <td>{{ $index + 1 }}</td>

                        <td>
                          <strong>
                        {{ $impaye->eleve->nom }}
                        {{ $impaye->eleve->prenoms }}
                            </strong>
                        </td>

                        <td>
                                <span class="badge bg-primary">
                                    {{ $impaye->eleve->classe->nom }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-info">
                                    {{ $impaye->type }}
                                </span>
                            </td>

                            <td>
                                {{ number_format($impaye->frais_total, 0, ',', ' ') }}
                                FCFA
                            </td>

                            <td class="text-success fw-bold">
                                {{ number_format($impaye->total_paye, 0, ',', ' ') }}
                                FCFA
                            </td>

                            <td class="text-danger fw-bold">
                                {{ number_format($impaye->reste, 0, ',', ' ') }}
                                FCFA
                            </td>

                            <td>
                                <span class="badge bg-danger">
                                    Impayé
                                </span>
                            </td>
                        </tr>

                        @endforeach
                        </tbody>

                    </table>
                </div>

            @else

                <div class="text-center py-5">
                    <h4 class="text-success">
                        Aucun impayé trouvé
                    </h4>

                    <p class="text-muted">
                        Tous les élèves sont à jour dans leurs paiements.
                    </p>
                </div>

            @endif

        </div>
    </div>

</div>
@endsection
