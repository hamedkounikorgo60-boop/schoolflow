@extends('layouts.app')
@section('title', 'Reçu de paiement')

@section('content')
<div class="no-print d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('gestionnaire.paiements.index') }}" class="btn btn-outline-secondary btn-sm">← Paiements</a>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">🖨️ Imprimer</button>
        <a href="{{ route('gestionnaire.paiements.telecharger', $paiement) }}"
           class="btn btn-success btn-sm">📥 Télécharger PDF</a>
    </div>
</div>

<div class="card border-0 shadow-sm mx-auto" style="max-width:720px">
    <div class="card-body p-0">
        @include('paiements._recu_styles')
        @include('paiements._recu_document')
    </div>
</div>
@endsection
