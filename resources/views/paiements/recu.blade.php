@extends('layouts.app')

@section('title', 'Reçu global')

@section('content')
<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-success text-white text-center">
            <h2>🏫 Suivi Scolaire</h2>
            <h4>REÇU GLOBAL</h4>
            <small>Tous les paiements de l'élève</small>
        </div>

        <div class="card-body">

            <h5>{{ $eleve->nom }} {{ $eleve->prenoms }}</h5>

            <p>
                <strong>Matricule :</strong>
                {{ $eleve->matricule }}
            </p>

            <p>
                <strong>Classe :</strong>
                {{ $eleve->classe->nom }}
            </p>

            <hr>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Montant</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($paiements as $p)
                    <tr>
                        <td>
                            {{ \Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}
                        </td>

                        <td>
                            {{ ucfirst($p->type_paiement) }}
                        </td>

                        <td>
                            {{ number_format($p->montant,0,',',' ') }} FCFA
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-end mt-4">
                <h3 class="text-success">
                    Total payé :
                    {{ number_format($total,0,',',' ') }} FCFA
                </h3>
            </div>

        </div>

        <div class="card-footer text-center">

            <a href="{{ route('gestionnaire.paiements.index') }}"
               class="btn btn-secondary">
                Retour
            </a>

        </div>

    </div>

</div>
@endsection
