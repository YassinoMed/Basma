@extends('layouts.app', ['hideChrome' => true, 'mainClass' => 'print-main'])

@push('styles')
@if(($activeThemeUiVersion ?? 1) === 5)
<link rel="stylesheet" href="{{ asset('css/admin-theme-v5.css') }}">
@endif
@if(($activeThemeUiVersion ?? 1) === 6)
<link rel="stylesheet" href="{{ asset('css/admin-theme-v6.css') }}">
@endif
@if(($activeThemeUiVersion ?? 1) === 7)
<link rel="stylesheet" href="{{ asset('css/admin-theme-v7.css') }}">
@endif
@endpush

@section('title', '1 Verbe par A4 - Impression')

@section('content')
@php
$paperSize = $paperSize ?? 'a4';
$paperSizeLabel = $paperSize === 'letter' ? 'Letter' : 'A4';
$useConjugatedVerb = (($printUiVersion ?? 2) === 3);
$printSingleRouteName = $useConjugatedVerb ? 'cards.print_single_v3' : 'cards.print_single';
$printDeckRouteName = $useConjugatedVerb ? 'cards.print_deck_v3' : 'cards.print_deck';
$indexRouteName = $useConjugatedVerb ? 'cards.index_v3' : 'cards.index';
$selectedGroups = $selectedGroups ?? [];
$searchQuery = $searchQuery ?? '';
$selectedVerb = $selectedVerb ?? '';
$irregularOnly = $irregularOnly ?? false;
$themeWritingStyle = $themeWritingStyle ?? null;
$isDosTheme = (($activeThemeUiVersion ?? 1) === 5) && ($themeWritingStyle === 'mono');
$wrapperClass = 'print-theme-wrapper';
if (($activeThemeUiVersion ?? 1) === 5) {
    $wrapperClass .= ' theme-customizer--v5';
}
$printFullRouteName = $useConjugatedVerb ? 'cards.print_full_v3' : 'cards.print_full';
$printSingleUrl = route($printSingleRouteName, array_filter([
    'group' => count($selectedGroups) ? $selectedGroups : null,
    'q' => $searchQuery !== '' ? $searchQuery : null,
    'verb' => $selectedVerb !== '' ? $selectedVerb : null,
    'irregular' => $irregularOnly ? 1 : null,
    'paper' => $paperSize,
]));
$printFullUrl = route($printFullRouteName, array_filter([
    'group' => count($selectedGroups) ? $selectedGroups : null,
    'q' => $searchQuery !== '' ? $searchQuery : null,
    'verb' => $selectedVerb !== '' ? $selectedVerb : null,
    'irregular' => $irregularOnly ? 1 : null,
    'paper' => $paperSize,
]));
$printDeckUrl = route($printDeckRouteName, array_filter([
    'group' => count($selectedGroups) ? $selectedGroups : null,
    'q' => $searchQuery !== '' ? $searchQuery : null,
    'verb' => $selectedVerb !== '' ? $selectedVerb : null,
    'irregular' => $irregularOnly ? 1 : null,
    'paper' => $paperSize,
]));
$selectedGroupForIndex = count($selectedGroups) === 1 ? $selectedGroups[0] : 'all';
$indexUrl = route($indexRouteName, array_filter([
    'group' => $selectedGroupForIndex !== 'all' ? $selectedGroupForIndex : null,
    'q' => $searchQuery !== '' ? $searchQuery : null,
]));
@endphp

<div class="{{ $wrapperClass }}">
    <style>
        .print-main {
            --rami-card-width: 170px;
            --rami-card-height: 250px;
        }

        .print-single-sheets {
            display: grid;
            gap: 24px;
        }

        .print-single-sheet {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.10);
        }

        .print-single-header {
            text-align: center;
            width: 100%;
            border-bottom: 2px solid rgba(0,0,0,0.06);
            padding-bottom: 12px;
        }

        .print-single-verb-name {
            font-family: var(--rami-font-family, 'Inter', sans-serif);
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--rami-verb-color, #0033A0);
            margin: 0;
        }

        .print-single-verb-group {
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-top: 4px;
            color: var(--rami-text-muted-color, #94a3b8);
        }

        .print-single-verb-group .rami-card-suit {
            font-size: 1.2em;
        }

        .print-single-verb-translation {
            font-size: 0.85rem;
            font-style: italic;
            color: var(--rami-text-muted-color, #94a3b8);
            margin-top: 2px;
        }

        .print-single-grid {
            display: grid;
            grid-template-columns: repeat(4, var(--rami-card-width, 170px));
            grid-template-rows: repeat(2, var(--rami-card-height, 250px));
            gap: 8px;
            justify-content: center;
            align-content: center;
        }

        .print-single-cell {
            width: var(--rami-card-width, 170px);
            height: var(--rami-card-height, 250px);
        }

        .print-rami-card {
            width: 100%;
            height: 100%;
            overflow: visible;
        }

        .print-rami-card,
        .print-rami-card.rami-card-back,
        .print-rami-card-back {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .print-rami-card .rami-card-index-top,
        .print-rami-card .rami-card-index-bottom {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }

        .print-rami-card .rami-card-index-pronoun {
            justify-content: flex-start;
        }

        .print-rami-card .rami-card-index-verb {
            text-align: left;
        }

        .print-rami-card .rami-card-index-bottom {
            transform: rotate(180deg);
            transform-origin: center;
        }

        .print-rami-card .rami-card-badge-bottom {
            top: auto;
            right: auto;
            bottom: var(--rami-index-padding, 12px);
            left: var(--rami-index-padding, 12px);
            transform: rotate(180deg);
            transform-origin: center;
        }

        .print-rami-card .rami-card-verb:not(.rami-card-verb-bottom) {
            top: var(--rami-verb-bottom, 45px);
            bottom: auto;
        }

        .print-rami-card .rami-card-verb-bottom {
            transform: rotate(180deg);
            transform-origin: center;
        }

        .print-rami-card.print-dos-back {
            --rami-dos-bg: #050a06;
            --rami-dos-fg: #25ff7d;
            --rami-dos-muted: rgba(37, 255, 125, 0.65);
            --rami-card-bg: var(--rami-dos-bg);
            --rami-card-border-color: var(--rami-dos-fg);
            --rami-card-border-width: 2px;
            --rami-card-shadow: none;
            --rami-pattern-color: rgba(37, 255, 125, 0.2);
            --rami-noise-opacity: 0;
            --rami-text-muted-color: var(--rami-dos-muted);
            --rami-index-verb-color: var(--rami-dos-muted);
            --rami-verb-color: var(--rami-dos-fg);
            --rami-verb-sub-color: var(--rami-dos-muted);
            --rami-pronoun-color: var(--rami-dos-fg);
            --rami-illustration-border-color: var(--rami-dos-fg);
            --rami-illustration-bg-start: #050a06;
            --rami-illustration-bg-end: #050a06;
            --rami-illustration-shadow: none;
            --rami-group-1-color: var(--rami-dos-fg);
            --rami-group-2-color: var(--rami-dos-fg);
            --rami-group-3-color: var(--rami-dos-fg);
            --rami-badge-bg-color: transparent;
            --rami-badge-text-color: var(--rami-dos-fg);
        }

        .print-legend {
            text-align: center;
            font-size: 12px;
            color: var(--color-text-muted, #666);
        }

        .print-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .print-filters {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .print-filters .print-filter-field {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .print-filters .print-filter-field label {
            font-size: 0.9rem;
            color: var(--color-text-muted, #666);
        }

        .print-filters input[type="number"] {
            width: 110px;
        }

        @media print {
            .print-actions,
            .print-filters,
            .print-legend {
                display: none;
            }

            @page {
                size: {{ $paperSizeLabel }} portrait;
                margin: 8mm;
            }

            .print-single-sheets {
                gap: 0;
            }

            .print-single-sheet {
                break-after: page;
                page-break-after: always;
                box-shadow: none;
                padding: 0;
                border-radius: 0;
                background: transparent;
                justify-content: center;
                min-height: 100vh;
            }

            .print-single-header {
                border-bottom-color: rgba(0,0,0,0.10);
            }
        }
    </style>

    <form method="GET" action="{{ route($printSingleRouteName) }}" class="print-filters">
        @if($searchQuery !== '')
        <input type="hidden" name="q" value="{{ $searchQuery }}">
        @endif

        <div class="print-filter-field">
            <label for="print-verb">Verbe</label>
            <input class="form-input" type="text" id="print-verb" name="verb" value="{{ $selectedVerb }}"
                placeholder="manger">
        </div>

        <div class="print-filter-field">
            <label>Groupes</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <label style="display: inline-flex; gap: 6px; align-items: center;">
                    <input type="checkbox" name="group[]" value="1er" {{ in_array('1er', $selectedGroups, true)
                        ? 'checked' : '' }}>
                    <span>1er</span>
                </label>
                <label style="display: inline-flex; gap: 6px; align-items: center;">
                    <input type="checkbox" name="group[]" value="2ème" {{ in_array('2ème', $selectedGroups, true)
                        ? 'checked' : '' }}>
                    <span>2ème</span>
                </label>
                <label style="display: inline-flex; gap: 6px; align-items: center;">
                    <input type="checkbox" name="group[]" value="3ème" {{ in_array('3ème', $selectedGroups, true)
                        ? 'checked' : '' }}>
                    <span>3ème</span>
                </label>
            </div>
        </div>

        <div class="print-filter-field">
            <label style="display: inline-flex; gap: 6px; align-items: center;">
                <input type="checkbox" name="irregular" value="1" {{ $irregularOnly ? 'checked' : '' }}>
                <span>Irréguliers</span>
            </label>
        </div>

        <div class="print-filter-field">
            <label for="print-paper">Format</label>
            <select class="form-input" id="print-paper" name="paper">
                <option value="a4" {{ $paperSize==='a4' ? 'selected' : '' }}>A4</option>
                <option value="letter" {{ $paperSize==='letter' ? 'selected' : '' }}>Letter</option>
            </select>
        </div>

        <button type="submit" class="btn btn-secondary">
            <i class="ph ph-funnel"></i>
            Filtrer
        </button>
        <a class="btn btn-secondary" href="{{ $printSingleUrl }}">
            <i class="ph ph-x"></i>
            Réinitialiser
        </a>
    </form>

    <div class="print-single-sheets">
        @if(!count($verbPages))
        <div class="print-legend" style="padding: 18px;">
            Aucun verbe à imprimer.
        </div>
        @endif

        @foreach($verbPages as $page)
        @php
        $verb = $page['verb'];
        $pageCards = $page['cards'];
        $isAuxiliary = in_array(mb_strtolower($verb->infinitive), ['être', 'avoir'], true);
        $suitSymbol = $verb->suit_symbol;
        $badgeText = $isAuxiliary ? 'Aux' : $verb->group;
        $suitTitle = $verb->suit_title;
        @endphp
        <div class="print-single-sheet">
            <div class="print-single-header">
                <div class="print-single-verb-name">
                    @if($suitSymbol !== '')
                    <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
                    @endif
                    {{ mb_strtoupper($verb->infinitive) }}
                </div>
                <div class="print-single-verb-group">
                    {{ $badgeText }}
                    @if($verb->infinitive_translation)
                    <span class="print-single-verb-translation">— {{ $verb->infinitive_translation }}</span>
                    @endif
                </div>
            </div>

            <div class="print-single-grid">
                @foreach($pageCards as $card)
                <div class="print-single-cell">
                    <div class="print-rami-card rami-card{{ $isDosTheme ? ' print-dos-back' : '' }}" data-group="{{ $verb->group }}"
                        data-suit="{{ $verb->suit ?? 'spade' }}" data-pattern="{{ $verb->pattern ?? 'plain' }}">
                        <div class="rami-card-index-top">
                            <div class="rami-card-index-pronoun">
                                @if($suitSymbol !== '')
                                <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
                                @endif
                                <span>{{ $card['pronoun_label'] }}</span>
                            </div>
                            <div class="rami-card-index-verb">{{ $card['conjugation_value'] }}</div>
                        </div>

                        <span class="rami-card-badge">
                            @if($suitSymbol !== '')
                            <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
                            @endif
                            <span>{{ $badgeText }}</span>
                        </span>
                        <span class="rami-card-badge rami-card-badge-bottom" aria-hidden="true">
                            @if($suitSymbol !== '')
                            <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
                            @endif
                            <span>{{ $badgeText }}</span>
                        </span>

                        <div class="rami-card-center">
                            <div class="rami-card-illustration">
                                @if($verb->illustration_path)
                                <img src="{{ asset($verb->illustration_path) }}"
                                    alt="Illustration du verbe {{ $verb->infinitive }}">
                                @endif
                            </div>
                        </div>

                        <div class="rami-card-verb">
                            @if($useConjugatedVerb)
                            <div class="rami-card-verb-text rami-card-verb-text-v3">
                                <div class="rami-card-verb-main-row">
                                    <span class="rami-card-verb-main">{{ mb_strtoupper((string)
                                        ($card['conjugation_value'] ?? $verb->infinitive)) }}</span>
                                </div>
                                <div class="rami-card-verb-sub">{{ mb_strtoupper($verb->infinitive) }}</div>
                            </div>
                            @else
                            <div class="rami-card-verb-text">{{ mb_strtoupper((string) ($card['conjugation_value'] ??
                                $verb->infinitive)) }}</div>
                            @endif
                        </div>
                        <div class="rami-card-verb rami-card-verb-bottom" aria-hidden="true">
                            @if($useConjugatedVerb)
                            <div class="rami-card-verb-text rami-card-verb-text-v3">
                                <div class="rami-card-verb-main-row">
                                    <span class="rami-card-verb-main">{{ mb_strtoupper((string)
                                        ($card['conjugation_value'] ?? $verb->infinitive)) }}</span>
                                </div>
                                <div class="rami-card-verb-sub">{{ mb_strtoupper($verb->infinitive) }}</div>
                            </div>
                            @else
                            <div class="rami-card-verb-text">{{ mb_strtoupper((string) ($card['conjugation_value'] ??
                                $verb->infinitive)) }}</div>
                            @endif
                        </div>

                        <div class="rami-card-index-bottom">
                            <div class="rami-card-index-pronoun">
                                @if($suitSymbol !== '')
                                <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
                                @endif
                                <span>{{ $card['pronoun_label'] }}</span>
                            </div>
                            <div class="rami-card-index-verb">{{ $card['conjugation_value'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <div class="print-actions" style="margin-top: 16px;">
        <a class="btn btn-secondary" href="{{ $indexUrl }}">
            <i class="ph ph-arrow-left"></i>
            Retour
        </a>
        <a class="btn btn-secondary" href="{{ $printDeckUrl }}">
            <i class="ph ph-squares-four"></i>
            8 cartes/A4
        </a>
        <a class="btn btn-secondary" href="{{ $printSingleUrl }}" aria-current="page"
            style="background: var(--color-primary, #4f46e5); color: #fff; border-color: var(--color-primary, #4f46e5);">
            <i class="ph ph-file"></i>
            1 verbe/A4
        </a>
        <a class="btn btn-secondary" href="{{ $printFullUrl }}">
            <i class="ph ph-arrows-out"></i>
            1 carte/A4
        </a>
        <button class="btn btn-primary" onclick="window.print()">
            <i class="ph ph-printer"></i>
            Imprimer
        </button>
    </div>

    <div class="print-legend" style="margin-top: 10px;">
        {{ count($verbPages) }} verbe(s) · 1 verbe par page {{ $paperSizeLabel }} · Couleurs : ♠ Pique · ♦ Carreaux · ♣ Trèfle · ♥ Cœur
    </div>
</div>
@endsection
