@extends('layouts.app')

@section('title', 'Favoris - FrenchVerbs')

@section('content')
@php
$selectedGroup = $selectedGroup ?? 'all';
$searchQuery = $searchQuery ?? '';
$favoriteVerbIds = $favoriteVerbIds ?? [];
$cardsUiVersion = (int) ($cardsUiVersion ?? 2);
$useConjugatedVerb = $cardsUiVersion === 3;
$favoritesIndexRouteName = $useConjugatedVerb ? 'favorites.index_v3' : 'favorites.index';
$cardsIndexRouteName = $useConjugatedVerb ? 'cards.index_v3' : 'cards.index';
$showRouteName = $useConjugatedVerb ? 'cards.show_v3' : 'cards.show';
@endphp

<div class="section-header">
    <div class="section-badge">
        <i class="ph ph-star"></i>
        <span>Apprentissage</span>
    </div>
    <h1 class="section-title">Mes favoris</h1>
    <p class="section-description">Retrouvez vos verbes enregistrés pour réviser plus vite.</p>
</div>

<form method="GET" action="{{ route($favoritesIndexRouteName) }}" class="form-card" style="margin-bottom: 18px;">
    <div class="form-row">
        <div class="form-group" style="flex: 1;">
            <label class="form-label" for="q">Rechercher</label>
            <input type="text" class="form-input" id="q" name="q" value="{{ $searchQuery }}"
                placeholder="Infinitif ou traduction (ex: manger, to eat)">
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
            <a href="{{ route($favoritesIndexRouteName) }}" class="btn btn-secondary">
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

<div class="cards-grid-rami" id="cards-grid">
    @foreach($verbs as $verb)
    @php
    $conjugation = $verb->getPresentConjugation();
    $isAuxiliary = in_array(mb_strtolower($verb->infinitive), ['être', 'avoir'], true);
    $suitSymbol = $verb->suit_symbol;
    $badgeText = $isAuxiliary ? 'Aux' : $verb->group;
    $suitTitle = $verb->suit_title;
    $isFavorited = in_array((int) $verb->id, $favoriteVerbIds, true);
    @endphp

    <article class="rami-card" data-group="{{ $verb->group }}" data-suit="{{ $verb->suit ?? 'spade' }}" data-verb-id="{{ $verb->id }}"
        data-pattern="{{ $verb->pattern ?? 'plain' }}" data-href="{{ route($showRouteName, $verb) }}" tabindex="0">
        <button type="button" class="rami-card-favorite {{ $isFavorited ? 'is-active' : '' }}"
            data-favorite-verb-id="{{ $verb->id }}" aria-label="Retirer des favoris"
            title="Retirer des favoris" onclick="event.stopPropagation();">
            <i class="ph {{ $isFavorited ? 'ph-fill ph-star' : 'ph-star' }}"></i>
        </button>

        <div class="rami-card-index-top">
            <div class="rami-card-index-pronoun">
                @if($suitSymbol !== '')
                <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
                @endif
                <span>{{ $verb->pronounLabel('je') }}</span>
            </div>
            <div class="rami-card-index-verb">{{ $conjugation ? $verb->formatConjugation('je', (string) ($conjugation->je ?? '')) : '' }}</div>
        </div>

        <span class="rami-card-badge">
            @if($suitSymbol !== '')
            <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
            @endif
            <span>{{ $badgeText }}</span>
        </span>

        <div class="rami-card-center">
            <div class="rami-card-illustration">
                @if($verb->illustration_path)
                <img src="{{ asset($verb->illustration_path) }}" alt="Illustration du verbe {{ $verb->infinitive }}">
                @endif
            </div>
        </div>

        <div class="rami-card-verb">
            @if($useConjugatedVerb)
            <div class="rami-card-verb-text rami-card-verb-text-v3">
                <div class="rami-card-verb-main-row">
                    <span class="rami-card-verb-main">{{ mb_strtoupper($conjugation ? $verb->formatConjugation('je', (string) ($conjugation->je ?? '')) : $verb->infinitive) }}</span>
                    <button class="btn-audio-player btn-icon-small" data-text="{{ $verb->infinitive }}"
                        title="Écouter la prononciation" onclick="event.stopPropagation();">
                        <i class="ph-bold ph-speaker-high"></i>
                    </button>
                </div>

            </div>
            @else
            <div class="rami-card-verb-text">
                {{ mb_strtoupper($verb->infinitive) }}
                <button class="btn-audio-player btn-icon-small" data-text="{{ $verb->infinitive }}"
                    title="Écouter la prononciation" onclick="event.stopPropagation();">
                    <i class="ph-bold ph-speaker-high"></i>
                </button>
            </div>
            @endif
        </div>

        <div class="rami-card-index-bottom">
            <div class="rami-card-index-pronoun">
                @if($suitSymbol !== '')
                <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
                @endif
                <span>{{ $verb->pronounLabel('je') }}</span>
            </div>
            <div class="rami-card-index-verb">{{ $conjugation ? $verb->formatConjugation('je', (string) ($conjugation->je ?? '')) : '' }}</div>
        </div>
    </article>
    @endforeach
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
    <i class="ph-duotone ph-star" style="font-size: 64px; color: var(--color-text-muted);"></i>
    <p style="color: var(--color-text-muted);">Aucun favori pour le moment.</p>
    <a href="{{ route($cardsIndexRouteName) }}" class="btn btn-primary">
        <i class="ph ph-cards"></i>
        Explorer les cartes
    </a>
</div>
@endif
@endsection
