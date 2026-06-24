@props(['moyenne'])
@if($moyenne !== null)
    <span class="badge {{ \App\Services\MoyenneService::mentionBadgeClass($moyenne) }}">
        {{ \App\Services\MoyenneService::mention($moyenne) }}
    </span>
@else
    <span class="text-muted small">Pas de notes</span>
@endif
