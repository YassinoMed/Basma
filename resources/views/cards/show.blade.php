@extends('layouts.app')

@section('title', $verb->infinitive . ' - Conjugaison - FrenchVerbs')

@section('content')
@php
$conjugation = $verb->getPresentConjugation();
$isAuxiliary = in_array(mb_strtolower($verb->infinitive), ['être', 'avoir'], true);
$cardsUiVersion = (int) ($cardsUiVersion ?? 2);
$useConjugatedVerb = $cardsUiVersion === 3;
$indexRouteName = $useConjugatedVerb ? 'cards.index_v3' : 'cards.index';
$showRouteName = $useConjugatedVerb ? 'cards.show_v3' : 'cards.show';
$printRouteName = $useConjugatedVerb ? 'cards.print_v3' : 'cards.print';
$printBackRouteName = $useConjugatedVerb ? 'cards.print_back_v3' : 'cards.print_back';
$pronouns = [
    'je' => 'JE',
    'tu' => 'TU',
    'il_elle_on' => 'IL',
    'nous' => 'NOUS',
    'vous' => 'VOUS',
    'ils_elles' => 'ILS',
];
$requestedPronoun = (string) request()->query('p', 'je');
$pronounKey = array_key_exists($requestedPronoun, $pronouns) ? $requestedPronoun : 'je';
$pronounLabel = $verb->pronounLabel($pronounKey);
$pronounValue = $conjugation ? $verb->formatConjugation($pronounKey, (string) ($conjugation->{$pronounKey} ?? '')) : '';
$frontSuitSymbol = $verb->suit_symbol !== '' ? $verb->suit_symbol : '♠';
$groupBadge = $verb->group === '1er'
    ? '1ER GROUPE'
    : ($verb->group === '2ème' ? '2ÈME GROUPE' : '3ÈME GROUPE');
$groupThemeColor = match (true) {
    $isAuxiliary => 'var(--rami-group-1-color)',
    $verb->group === '1er' => 'var(--rami-group-1-color)',
    $verb->group === '2ème' => 'var(--rami-group-2-color)',
    $verb->group === '3ème' => 'var(--rami-group-3-color)',
    default => 'var(--rami-group-1-color)',
};
$suitSymbol = $verb->suit_symbol;
$badgeText = $isAuxiliary ? 'Auxiliaire' : ($verb->group . ' groupe');
$suitTitle = $verb->suit_title;
@endphp

<div id="verb-show-root" data-verb-id="{{ $verb->id }}" data-verb-infinitive="{{ $verb->infinitive }}"
    data-verb-group="{{ $verb->group }}" data-verb-url="{{ route($showRouteName, $verb) }}">
<div class="mb-8">
    <a href="{{ route($indexRouteName) }}" class="btn btn-secondary">
        <i class="ph ph-arrow-left"></i>
        Retour aux cartes
    </a>
    <span style="margin-left: 10px;">
        @auth
        <button type="button" class="btn btn-secondary favorite-toggle {{ !empty($isFavorited) ? 'is-active' : '' }}"
            data-favorite-verb-id="{{ $verb->id }}">
            <i class="ph {{ !empty($isFavorited) ? 'ph-fill ph-star' : 'ph-star' }}"></i>
            <span>{{ !empty($isFavorited) ? 'Retirer des favoris' : 'Ajouter aux favoris' }}</span>
        </button>
        <a class="btn btn-secondary" href="{{ route('cards.duplicate', $verb) }}" style="margin-left: 10px;">
            <i class="ph ph-copy"></i>
            <span>Dupliquer</span>
        </a>
        <button type="button" class="btn btn-secondary" id="flashcard-toggle" style="margin-left: 10px;">
            <i class="ph ph-cards"></i>
            <span>Mode flashcard</span>
        </button>
        <button type="button" class="btn btn-secondary" id="flashcard-random" style="margin-left: 10px; display: none;">
            <i class="ph ph-shuffle"></i>
            <span>Aléatoire (même groupe)</span>
        </button>
        @else
        <a class="btn btn-secondary" href="{{ route('login') }}">
            <i class="ph ph-star"></i>
            <span>Se connecter pour ajouter aux favoris</span>
        </a>
        @endauth
    </span>
    @if($verb->example_sentence)
    <div style="margin-top: 10px; color: var(--color-text-primary); font-size: 1rem; font-weight: 600;">
        {{ $verb->example_sentence }}
    </div>
    @endif
    <div style="margin-top: 10px; color: var(--color-text-muted); font-size: 0.95rem;">
        Couleurs : ♠ Pique · <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span> Carreaux · ♣ Trèfle · ♥ Cœur
    </div>
</div>

<div class="filter-tabs" style="justify-content: center; margin-bottom: 18px;">
    @foreach($pronouns as $key => $label)
    <a class="filter-tab {{ $key === $pronounKey ? 'active' : '' }}"
        href="{{ route($showRouteName, $verb) . '?p=' . urlencode($key) }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div id="flashcard-stage" tabindex="0" data-verb-id="{{ $verb->id }}" data-verb-group="{{ $verb->group }}">
    <div class="flashcard-surface">
        <div id="flashcard-front" class="flashcard-face flashcard-face-front">
            <div class="flashcard-portrait">
                <div class="flashcard-portrait-badge">{{ $groupBadge }}</div>

                <div class="flashcard-portrait-corner flashcard-portrait-corner-top">
                    <div class="flashcard-portrait-corner-row">
                        <div class="flashcard-portrait-suit">{{ $frontSuitSymbol }}</div>
                        <div class="flashcard-portrait-corner-text">
                            <div class="flashcard-portrait-pronoun">
                                @if($pronounKey === 'il_elle_on')
                                <span class="flashcard-portrait-pronoun-stack">
                                    <span>{{ $pronounLabel }}</span>
                                    <span class="flashcard-portrait-pronoun-sub">ELLE</span>
                                </span>
                                @else
                                {{ $pronounLabel }}
                                @endif
                            </div>
                            <div class="flashcard-portrait-form">{{ $pronounValue }}</div>
                        </div>
                    </div>
                </div>

                <div class="flashcard-portrait-center" aria-hidden="true"></div>

                <div class="flashcard-portrait-lower">
                    @if($verb->illustration_description)
                    <div class="flashcard-portrait-caption">{{ $verb->illustration_description }}</div>
                    @endif

                    @if($useConjugatedVerb)
                    <div class="flashcard-portrait-infinitive-hint">
                        <button type="button" class="btn-audio-player btn-icon-small flashcard-portrait-audio" data-text="{{ $verb->infinitive }}"
                            title="Écouter la prononciation" aria-label="Écouter la prononciation">
                            <i class="ph-bold ph-speaker-high"></i>
                        </button>
                    </div>
                    @else
                    <div class="flashcard-portrait-infinitive">
                        <span>{{ mb_strtoupper($verb->infinitive) }}</span>
                        <button type="button" class="btn-audio-player btn-icon-small flashcard-portrait-audio" data-text="{{ $verb->infinitive }}"
                            title="Écouter la prononciation">
                            <i class="ph-bold ph-speaker-high"></i>
                        </button>
                    </div>
                    @endif
                    <div class="flashcard-portrait-form-big">{{ $useConjugatedVerb ? mb_strtoupper($pronounValue) : $pronounValue }}</div>
                </div>

                <div class="flashcard-portrait-corner flashcard-portrait-corner-bottom" aria-hidden="true">
                    <div class="flashcard-portrait-corner-row">
                        <div class="flashcard-portrait-suit">{{ $frontSuitSymbol }}</div>
                        <div class="flashcard-portrait-corner-text">
                            <div class="flashcard-portrait-pronoun">
                                @if($pronounKey === 'il_elle_on')
                                <span class="flashcard-portrait-pronoun-stack">
                                    <span>{{ $pronounLabel }}</span>
                                    <span class="flashcard-portrait-pronoun-sub">ELLE</span>
                                </span>
                                @else
                                {{ $pronounLabel }}
                                @endif
                            </div>
                            <div class="flashcard-portrait-form">{{ $pronounValue }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($conjugation)
        <div id="flashcard-back" class="flashcard-face flashcard-face-back">
            <div class="verb-card-large" style="--card-theme-color: {{ $groupThemeColor }}; max-width: 400px;">
                <div style="position: relative; z-index: 5; padding: 24px;">
                    <h3
                        style="font-family: var(--font-display); font-size: 18px; font-weight: 700; color: var(--color-text-primary); margin-bottom: 20px; text-align: center; text-transform: uppercase; letter-spacing: 0.1em;">
                        Présent de l'indicatif
                    </h3>

                    <div class="card-large-conjugations">
                        <div class="conjugation-row-large">
                            <span class="conjugation-pronoun-large" style="color: var(--card-theme-color)">je</span>
                            <span class="conjugation-form-large">{{ $verb->formatConjugation('je', $conjugation->je) }}</span>
                        </div>
                        <div class="conjugation-row-large">
                            <span class="conjugation-pronoun-large" style="color: var(--card-theme-color)">tu</span>
                            <span class="conjugation-form-large">{{ $verb->formatConjugation('tu', $conjugation->tu) }}</span>
                        </div>
                        <div class="conjugation-row-large">
                            <span class="conjugation-pronoun-large" style="color: var(--card-theme-color)">il/elle/on</span>
                            <span class="conjugation-form-large">{{ $verb->formatConjugation('il_elle_on', $conjugation->il_elle_on) }}</span>
                        </div>
                        <div class="conjugation-row-large">
                            <span class="conjugation-pronoun-large" style="color: var(--card-theme-color)">nous</span>
                            <span class="conjugation-form-large">{{ $verb->formatConjugation('nous', $conjugation->nous) }}</span>
                        </div>
                        <div class="conjugation-row-large">
                            <span class="conjugation-pronoun-large" style="color: var(--card-theme-color)">vous</span>
                            <span class="conjugation-form-large">{{ $verb->formatConjugation('vous', $conjugation->vous) }}</span>
                        </div>
                        <div class="conjugation-row-large">
                            <span class="conjugation-pronoun-large" style="color: var(--card-theme-color)">ils/elles</span>
                            <span class="conjugation-form-large">{{ $verb->formatConjugation('ils_elles', $conjugation->ils_elles) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Actions -->
<div class="flex-center gap-4 mt-8">
    <button class="btn btn-secondary btn-lg btn-audio-player" data-text="{{ $verb->infinitive }}">
        <i class="ph ph-speaker-high"></i>
        Prononcer
    </button>
    <a href="{{ route($printRouteName, $verb) }}" class="btn btn-primary btn-lg" target="_blank">
        <i class="ph ph-printer"></i>
        Imprimer la carte
    </a>
    <a href="{{ route($printBackRouteName, $verb) }}" class="btn btn-secondary btn-lg" target="_blank">
        <i class="ph ph-squares-four"></i>
        Imprimer le verso
    </a>
</div>
<div style="max-width: 620px; margin: 18px auto 0;">
    <label class="form-label" for="share-link">Lien interactif (cliquer pour copier)</label>
    <input id="share-link" class="form-input color-hex" type="text" readonly value="{{ route($showRouteName, $verb) }}">
</div>
</div>
@endsection
