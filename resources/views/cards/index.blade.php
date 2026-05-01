@extends('layouts.app')

@section('title', 'Cartes de Conjugaison - FrenchVerbs')

@section('content')
<div class="section-header">
    <div class="section-badge">
        <i class="ph ph-cards"></i>
        <span>Jeu de Cartes Premium</span>
    </div>
    <h1 class="section-title">Cartes de Conjugaison</h1>
    <p class="section-description">
        Maîtrisez la conjugaison française avec nos cartes style rami,
        conçues pour un apprentissage ludique et élégant.
    </p>
    <p class="section-description" style="margin-top: 8px; font-size: 0.95rem;">
        Couleurs : ♠ Pique · <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span> Carreaux · ♣ Trèfle · ♥ Cœur
    </p>
    <div style="margin-top: 12px; display: flex; justify-content: center;">
        <button type="button" class="btn btn-secondary" id="beginner-guide-open"
            style="padding: 6px 12px; font-size: 0.85rem; display: none;">
            <i class="ph ph-question"></i>
            Guide débutant
        </button>
    </div>
</div>

@php
$selectedGroup = $selectedGroup ?? 'all';
$searchQuery = $searchQuery ?? '';
$favoriteVerbIds = $favoriteVerbIds ?? [];
$cardsUiVersion = (int) ($cardsUiVersion ?? 2);
$useConjugatedVerb = $cardsUiVersion === 3;
$indexRouteName = $useConjugatedVerb ? 'cards.index_v3' : 'cards.index';
$printDeckRouteName = $useConjugatedVerb ? 'cards.print_deck_v3' : 'cards.print_deck';
$showRouteName = $useConjugatedVerb ? 'cards.show_v3' : 'cards.show';
$printDeckParams = array_filter([
    'group' => $selectedGroup !== 'all' ? $selectedGroup : null,
    'q' => $searchQuery !== '' ? $searchQuery : null,
]);
$printSingleRouteName = $useConjugatedVerb ? 'cards.print_single_v3' : 'cards.print_single';
$printSingleBackRouteName = $useConjugatedVerb ? 'cards.print_single_back_v3' : 'cards.print_single_back';
@endphp

<div style="margin: 0 auto 18px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; max-width: 900px;">
    <a href="{{ route($printDeckRouteName, $printDeckParams) }}" class="btn btn-secondary" target="_blank">
        <i class="ph ph-printer"></i>
        Imprimer deck (9/A4)
    </a>
    <a href="{{ route($printDeckRouteName, array_merge($printDeckParams, ['include_back' => 1])) }}" class="btn btn-secondary" target="_blank">
        <i class="ph ph-squares-four"></i>
        Recto/verso
    </a>
    <a href="{{ route($useConjugatedVerb ? 'cards.print_deck_back_v3' : 'cards.print_deck_back', array_merge($printDeckParams, ['back_color' => 'classic-red', 'back_pattern' => 'classic-red', 'back_only' => 1])) }}" class="btn btn-secondary" target="_blank" style="color: #c41e3a; border-color: #c41e3a;">
        <i class="ph ph-diamond"></i>
        Dos Rouge
    </a>
    <a href="{{ route($useConjugatedVerb ? 'cards.print_deck_back_v3' : 'cards.print_deck_back', array_merge($printDeckParams, ['back_color' => 'classic-blue', 'back_pattern' => 'classic-blue', 'back_only' => 1])) }}" class="btn btn-secondary" target="_blank" style="color: #1a3a6b; border-color: #1a3a6b;">
        <i class="ph ph-diamond"></i>
        Dos Bleu
    </a>
    <a href="{{ route('cards.rules') }}" class="btn btn-secondary">
        <i class="ph ph-book-open"></i>
        Règles & mini-jeux
    </a>
</div>

<div class="alert alert-info" id="beginner-guide" style="display: none; max-width: 900px; margin-left: auto; margin-right: auto;">
    <i class="ph ph-info"></i>
    <div style="flex: 1;">
        <div style="font-weight: 700; margin-bottom: 4px;">Guide rapide</div>
        <div style="opacity: 0.9;">
            Couleurs : ♠ Pique · <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span> Carreaux · ♣ Trèfle · ♥ Cœur
        </div>
    </div>
    <button type="button" class="alert-close" id="beginner-guide-close" aria-label="Masquer le guide" title="Masquer">
        <i class="ph ph-x"></i>
    </button>
</div>

<div class="form-card" id="recently-viewed" style="display: none; margin-bottom: 18px;">
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
        <div style="font-weight: 800; letter-spacing: 0.04em; text-transform: uppercase; font-size: 0.9rem;">
            <i class="ph ph-clock-counter-clockwise"></i>
            Récemment consultés
        </div>
        <button type="button" class="btn btn-secondary" id="recently-viewed-clear" style="padding: 6px 12px; font-size: 0.85rem;">
            <i class="ph ph-trash"></i>
            Effacer
        </button>
    </div>
    <div id="recently-viewed-list" style="margin-top: 12px; display: flex; flex-wrap: wrap; gap: 10px;"></div>
</div>

<form method="GET" action="{{ route($indexRouteName) }}" class="form-card" style="margin-bottom: 18px;">
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
            <a href="{{ route($indexRouteName) }}" class="btn btn-secondary">
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
        Tous les verbes
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

<!-- Grille des cartes style Rami -->
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
        @auth
        <button type="button" class="rami-card-favorite {{ $isFavorited ? 'is-active' : '' }}"
            data-favorite-verb-id="{{ $verb->id }}" aria-label="{{ $isFavorited ? 'Retirer des favoris' : 'Ajouter aux favoris' }}"
            title="{{ $isFavorited ? 'Retirer des favoris' : 'Ajouter aux favoris' }}" onclick="event.stopPropagation();">
            <i class="ph {{ $isFavorited ? 'ph-fill ph-star' : 'ph-star' }}"></i>
        </button>
        @else
        <a class="rami-card-favorite" href="{{ route('login') }}" aria-label="Se connecter pour ajouter aux favoris"
            title="Se connecter pour ajouter aux favoris" onclick="event.stopPropagation();">
            <i class="ph ph-star"></i>
        </a>
        @endauth

        <!-- Index haut gauche -->
        <div class="rami-card-index-top">
            <div class="rami-card-index-pronoun">
                @if($suitSymbol !== '')
                <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
                @endif
                <span>{{ $verb->pronounLabel('je') }}</span>
            </div>
            <div class="rami-card-index-verb">{{ $conjugation ? $verb->formatConjugation('je', (string) ($conjugation->je ?? '')) : '' }}</div>
        </div>

        <!-- Badge groupe -->
        <span class="rami-card-badge">
            @if($suitSymbol !== '')
            <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
            @endif
            <span>{{ $badgeText }}</span>
        </span>

        <!-- Illustration centrale -->
        <div class="rami-card-center">
            <div class="rami-card-illustration">
                @if($verb->illustration_path)
                <img src="{{ asset($verb->illustration_path) }}" alt="Illustration du verbe {{ $verb->infinitive }}">
                @endif
            </div>
        </div>

        <!-- Verbe conjugué en bas -->
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

        @if($loop->iteration <= 2)
        <div class="rami-card-micro-legend" aria-hidden="true">
            ♠ pique · <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span> carreaux · ♣ trèfle · ♥ cœur
        </div>
        @endif

        <div class="rami-card-index-bottom">
            <div class="rami-card-index-pronoun">
                @if($suitSymbol !== '')
                <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
                @endif
                <span>{{ $verb->pronounLabel('je') }}</span>
            </div>
            <div class="rami-card-index-verb">{{ $conjugation ? $verb->formatConjugation('je', (string) ($conjugation->je ?? '')) : '' }}</div>
        </div>

        <!-- Boutons d'action directs (hover) -->
        <div class="rami-card-actions" style="position: absolute; bottom: 8px; right: 8px; display: flex; gap: 6px; z-index: 10; opacity: 0; transition: opacity var(--duration-200);">
            <a href="{{ route($printSingleBackRouteName, ['verb' => $verb->infinitive]) }}" class="btn btn-icon-small" title="Imprimer les versos (8/A4)" target="_blank" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(4px);">
                <i class="ph ph-squares-four"></i>
            </a>
            <a href="{{ route($printSingleRouteName, ['verb' => $verb->infinitive]) }}" class="btn btn-icon-small" title="Imprimer le verbe (8/A4)" target="_blank" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(4px);">
                <i class="ph ph-printer"></i>
            </a>
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
    <i class="ph-duotone ph-cards" style="font-size: 64px; color: var(--color-text-muted);"></i>
    <p style="color: var(--color-text-muted);">Aucune carte de verbe disponible.</p>
    <a href="{{ route('cards.create') }}" class="btn btn-primary">
        <i class="ph ph-plus"></i>
        Créer une carte
    </a>
</div>
@endif
@endsection
