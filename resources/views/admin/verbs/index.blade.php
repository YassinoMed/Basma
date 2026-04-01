@extends('layouts.app')

@section('title', 'Verbes - Administration')

@section('content')
@php
$selectedGroup = $selectedGroup ?? 'all';
$searchQuery = $searchQuery ?? '';
@endphp

<div class="section-header">
    <div class="section-badge">
        <i class="ph ph-gear"></i>
        <span>Administration</span>
    </div>
    <h1 class="section-title">Verbes</h1>
    <p class="section-description">Activer ou désactiver des verbes.</p>
</div>

<form method="GET" action="{{ route('admin.verbs.index') }}" class="form-card" style="margin-bottom: 18px;">
    <div class="form-row">
        <div class="form-group" style="flex: 1;">
            <label class="form-label" for="q">Rechercher</label>
            <input type="text" class="form-input" id="q" name="q" value="{{ $searchQuery }}"
                placeholder="Infinitif ou traduction">
        </div>
        <div class="form-group" style="display: flex; align-items: flex-end; gap: 10px;">
            @if($selectedGroup !== 'all')
            <input type="hidden" name="group" value="{{ $selectedGroup }}">
            @endif
            <button type="submit" class="btn btn-primary">
                <i class="ph ph-magnifying-glass"></i>
                Chercher
            </button>
            @if($searchQuery !== '' || $selectedGroup !== 'all')
            <a href="{{ route('admin.verbs.index') }}" class="btn btn-secondary">
                <i class="ph ph-x"></i>
                Réinitialiser
            </a>
            @endif
        </div>
    </div>
</form>

<div class="filter-tabs" id="filter-tabs">
    <a class="filter-tab {{ $selectedGroup === 'all' ? 'active' : '' }}"
        href="{{ request()->fullUrlWithQuery(['group' => null, 'page' => 1]) }}">
        <i class="ph ph-squares-four"></i>
        Tous
    </a>
    <a class="filter-tab {{ $selectedGroup === '1er' ? 'active' : '' }}"
        href="{{ request()->fullUrlWithQuery(['group' => '1er', 'page' => 1]) }}">
        1er groupe
    </a>
    <a class="filter-tab {{ $selectedGroup === '2ème' ? 'active' : '' }}"
        href="{{ request()->fullUrlWithQuery(['group' => '2ème', 'page' => 1]) }}">
        2ème groupe
    </a>
    <a class="filter-tab {{ $selectedGroup === '3ème' ? 'active' : '' }}"
        href="{{ request()->fullUrlWithQuery(['group' => '3ème', 'page' => 1]) }}">
        3ème groupe
    </a>
</div>

<div class="form-card form-card-wide" style="margin-top: 16px;">
    <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach($verbs as $verb)
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-lg); background: var(--color-bg-card);">
            <div style="min-width: 0;">
                <div style="font-weight: 800; letter-spacing: 0.04em;">
                    <a href="{{ route('cards.show', $verb) }}" style="color: inherit; text-decoration: none;">
                        {{ mb_strtoupper($verb->infinitive) }}
                    </a>
                    <span style="margin-left: 8px; color: var(--color-text-muted); font-weight: 600;">
                        ({{ $verb->group }})
                    </span>
                </div>
                @if($verb->infinitive_translation)
                <div style="color: var(--color-text-muted); margin-top: 2px;">
                    {{ $verb->infinitive_translation }}
                </div>
                @endif
                <div style="margin-top: 6px; color: var(--color-text-muted);">
                    Statut:
                    <strong style="color: {{ $verb->is_active ? 'var(--color-success)' : 'var(--color-danger)' }};">
                        {{ $verb->is_active ? 'actif' : 'inactif' }}
                    </strong>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 10px; flex-shrink: 0;">
                <a class="btn btn-secondary" href="{{ route('admin.verbs.edit', $verb) }}">
                    <i class="ph ph-pencil"></i>
                    Modifier
                </a>
                <form method="POST" action="{{ route('admin.verbs.toggle', $verb) }}">
                    @csrf
                    <button type="submit" class="btn {{ $verb->is_active ? 'btn-secondary' : 'btn-primary' }}">
                        {{ $verb->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

@if(method_exists($verbs, 'hasPages') && $verbs->hasPages())
<div class="flex-center gap-4 mt-8">
    @if($verbs->previousPageUrl())
    <a class="btn btn-secondary" href="{{ $verbs->previousPageUrl() }}">
        <i class="ph ph-arrow-left"></i>
        Précédent
    </a>
    @endif

    <span style="color: var(--color-text-muted);">
        Page {{ $verbs->currentPage() }} / {{ $verbs->lastPage() }}
    </span>

    @if($verbs->nextPageUrl())
    <a class="btn btn-secondary" href="{{ $verbs->nextPageUrl() }}">
        Suivant
        <i class="ph ph-arrow-right"></i>
    </a>
    @endif
</div>
@endif

@if($verbs->count() === 0)
<div class="flex-center" style="min-height: 300px; flex-direction: column; gap: var(--spacing-4);">
    <i class="ph-duotone ph-cards" style="font-size: 64px; color: var(--color-text-muted);"></i>
    <p style="color: var(--color-text-muted);">Aucun verbe trouvé.</p>
</div>
@endif
@endsection
