@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4">Dashboard Admin</h3>

    <div class="row">

        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6>Total Élèves</h6>
                    <h2>{{ $totalEleves }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6>Enseignants</h6>
                    <h2>{{ $totalEnseignants }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6>Paiements</h6>
                    <h2>{{ number_format($totalPaiements, 0, ',', ' ') }} FCFA</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6>Impayés</h6>
                    <h2>{{ $impayes }}</h2>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
