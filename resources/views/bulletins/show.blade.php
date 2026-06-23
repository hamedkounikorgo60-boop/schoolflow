@extends('layouts.app')

@section('title','Bulletin scolaire')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-primary text-white text-center">
            <h3>🏫 BULLETIN SCOLAIRE</h3>
            <h5>Trimestre {{ $trimestre }}</h5>
        </div>

        <div class="card-body">

            <p>
                <strong>Élève :</strong>
                {{ $eleve->nom }} {{ $eleve->prenoms }}
            </p>

            <p>
                <strong>Classe :</strong>
                {{ $eleve->classe->nom ?? '-' }}
            </p>

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>Matière</th>
                        <th>Coef</th>
                        <th>Note</th>
                        <th>Points</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($notes as $note)

                    <tr>
                        <td>{{ $note->matiere->nom }}</td>

                        <td>{{ $note->matiere->coefficient }}</td>

                        <td>{{ $note->note }}/20</td>

                        <td>
                            {{ $note->note * $note->matiere->coefficient }}
                        </td>
                    </tr>

                @endforeach

                </tbody>

            </table>

            <div class="row mt-4">

                <div class="col-md-4">
                    <div class="alert alert-info">
                        Moyenne : <strong>{{ $moyenne }}/20</strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="alert alert-success">
                        Rang : <strong>{{ $rang }}</strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="alert alert-warning">

                        @if($moyenne >= 10)
                            Admis
                        @else
                            Redouble
                        @endif

                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection
