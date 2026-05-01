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

@section('title', 'Carte pleine page A4 - Impression')

@section('content')
@php
$paperSize = $paperSize ?? 'a4';
$paperSizeLabel = $paperSize === 'letter' ? 'Letter' : 'A4';
$includeBack = !empty($includeBack) || (string) request()->query('include_back', '') === '1';
$backOnly = !empty($backOnly) || (string) request()->query('back_only', '') === '1';
$includeBack = $includeBack || $backOnly;
$backPattern = (string) request()->query('back_pattern', ($themeCardBackPattern ?? 'plain'));
$backColor = (string) request()->query('back_color', '1');
$allowedBackColors = ['1', '2', '3', 'premium', 'classic-red', 'classic-blue', 'classic-gold', 'classic-emerald', 'filigrane-red', 'filigrane-blue', 'ecusson-red', 'ecusson-blue', 'eventail-red', 'eventail-blue', 'indigo', 'bordeaux'];
if (! in_array($backColor, $allowedBackColors, true)) { $backColor = '1'; }
$backColor = $backColor === 'indigo' ? '1' : ($backColor === 'bordeaux' ? '2' : $backColor);
$backColorClass = match ($backColor) {
    '2' => 'rami-card-back--v2',
    '3' => 'rami-card-back--v3',
    'premium' => 'rami-card-back--premium',
    'classic-red' => 'rami-card-back--classic-red',
    'classic-blue' => 'rami-card-back--classic-blue',
    'classic-gold' => 'rami-card-back--classic-gold',
    'classic-emerald' => 'rami-card-back--classic-emerald',
    default => 'rami-card-back--v1',
};

$useConjugatedVerb = (($printUiVersion ?? 2) === 3);
$printFullRouteName = $useConjugatedVerb ? 'cards.print_full_v3' : 'cards.print_full';
$printFullBackRouteName = $useConjugatedVerb ? 'cards.print_full_back_v3' : 'cards.print_full_back';
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
if (($activeThemeUiVersion ?? 1) === 5) { $wrapperClass .= ' theme-customizer--v5'; }
$wrapperClass .= $backOnly ? ' print-preview' : '';

$baseParams = [
    'group' => count($selectedGroups) ? $selectedGroups : null,
    'q' => $searchQuery !== '' ? $searchQuery : null,
    'verb' => $selectedVerb !== '' ? $selectedVerb : null,
    'irregular' => $irregularOnly ? 1 : null,
    'paper' => $paperSize,
];

$printFullUrl = route($backOnly ? $printFullBackRouteName : $printFullRouteName, array_filter(array_merge($baseParams, [
    'include_back' => $includeBack ? 1 : null,
    'back_color' => $backColor,
    'back_pattern' => $includeBack ? $backPattern : null,
    'back_only' => $backOnly ? 1 : null,
])));

$printBackV1Url = route($printFullBackRouteName, array_filter(array_merge($baseParams, ['back_color' => '1', 'back_pattern' => $backPattern, 'back_only' => 1])));
$printBackV2Url = route($printFullBackRouteName, array_filter(array_merge($baseParams, ['back_color' => '2', 'back_pattern' => $backPattern, 'back_only' => 1])));
$printBackV3Url = route($printFullBackRouteName, array_filter(array_merge($baseParams, ['back_color' => '3', 'back_pattern' => $backPattern, 'back_only' => 1])));
$printBackPremiumUrl = route($printFullBackRouteName, array_filter(array_merge($baseParams, ['back_color' => 'premium', 'back_pattern' => $backPattern, 'back_only' => 1])));
$printBackClassicRedUrl = route($printFullBackRouteName, array_filter(array_merge($baseParams, ['back_color' => 'classic-red', 'back_pattern' => 'classic-red', 'back_only' => 1])));
$printBackClassicBlueUrl = route($printFullBackRouteName, array_filter(array_merge($baseParams, ['back_color' => 'classic-blue', 'back_pattern' => 'classic-blue', 'back_only' => 1])));
$printBackClassicGoldUrl = route($printFullBackRouteName, array_filter(array_merge($baseParams, ['back_color' => 'classic-gold', 'back_pattern' => 'classic-gold', 'back_only' => 1])));
$printBackClassicEmeraldUrl = route($printFullBackRouteName, array_filter(array_merge($baseParams, ['back_color' => 'classic-emerald', 'back_pattern' => 'classic-emerald', 'back_only' => 1])));

$printSingleUrl = route($printSingleRouteName, array_filter($baseParams));
$printDeckUrl = route($printDeckRouteName, array_filter($baseParams));

$selectedGroupForIndex = count($selectedGroups) === 1 ? $selectedGroups[0] : 'all';
$indexUrl = route($indexRouteName, array_filter([
    'group' => $selectedGroupForIndex !== 'all' ? $selectedGroupForIndex : null,
    'q' => $searchQuery !== '' ? $searchQuery : null,
]));
@endphp

<div class="{{ $wrapperClass }}">
    @if(!empty($themeSettingsInlineCss))
    <style>
        {!! $themeSettingsInlineCss !!}
    </style>
    @endif
    <style>
        .print-main {
            --rami-card-width: 190px;
            --rami-card-height: 328px;
        }

        .print-full-sheets {
            display: grid;
            gap: 24px;
        }

        .print-full-sheet {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            padding: 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.10);
            min-height: 90vh; /* Visual preview size padding */
        }

        .print-full-header {
            text-align: center;
            width: 100%;
            padding-bottom: 12px;
            opacity: 0.5; /* Keeping it subtle so it does not distract from card */
        }

        .print-full-verb-name {
            font-family: var(--rami-font-family, 'Inter', sans-serif);
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--color-text-muted, #666);
            margin: 0;
        }

        .print-full-cell {
            width: var(--rami-card-width, 190px);
            height: var(--rami-card-height, 328px);
            position: relative;
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

        .print-rami-card[data-suit="diamond"] .rami-card-suit,
        .print-rami-card[data-suit="diamonds"] .rami-card-suit,
        .print-rami-card[data-suit="heart"] .rami-card-suit,
        .print-rami-card[data-suit="hearts"] .rami-card-suit {
            color: #d32f2f !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Adjust UI details for large scale view */
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
            margin-top: 5px;
        }

        .print-rami-card .rami-card-index-bottom {
            transform: rotate(180deg);
            transform-origin: center;
        }

        .print-rami-card .rami-card-badge-bottom {
            top: auto;
            right: auto;
            bottom: var(--rami-index-padding, 24px);
            left: var(--rami-index-padding, 24px);
            transform: rotate(180deg);
            transform-origin: center;
        }

        .print-rami-card .rami-card-verb:not(.rami-card-verb-bottom) {
            top: var(--rami-verb-bottom, 80px);
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
            --rami-card-border-width: 3px;
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
            .print-legend,
            .print-full-header {
                display: none;
            }

            @page {
                size: {{ $paperSizeLabel }} portrait;
                margin: 4mm;
            }

            .print-full-sheets {
                gap: 0;
            }

            .print-full-sheet {
                break-after: page;
                page-break-after: always;
                box-shadow: none;
                padding: 0;
                border-radius: 0;
                background: transparent;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                height: 100vh;
            }
        }
    </style>

    <form method="GET" action="{{ route($backOnly ? $printFullBackRouteName : $printFullRouteName) }}" class="print-filters">
        @if($searchQuery !== '')
        <input type="hidden" name="q" value="{{ $searchQuery }}">
        @endif
        @if($includeBack)
        <input type="hidden" name="include_back" value="1">
        @endif
        @if($backOnly)
        <input type="hidden" name="back_only" value="1">
        @endif
        <input type="hidden" name="back_color" value="{{ $backColor }}">
        @if($includeBack)
        <input type="hidden" name="back_pattern" value="{{ $backPattern }}">
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
        <a class="btn btn-secondary" href="{{ $printFullUrl }}">
            <i class="ph ph-x"></i>
            Réinitialiser
        </a>
    </form>

    <div class="print-full-sheets">
        @if(!count($fullPages))
        <div class="print-legend" style="padding: 18px;">
            Aucune carte à imprimer.
        </div>
        @endif

        @foreach($fullPages as $pageData)
        @php
        $verb = $pageData['verb'];
        $isAuxiliary = in_array(mb_strtolower($verb->infinitive), ['être', 'avoir'], true);
        $suitSymbol = $verb->suit_symbol;
        $badgeText = $isAuxiliary ? 'Aux' : $verb->group;
        $suitTitle = $verb->suit_title;
        @endphp
        <div class="print-full-sheet">
            <div class="print-full-header">
                <h3 class="print-full-verb-name">{{ mb_strtoupper($verb->infinitive) }} — {{ $pageData['pronoun_label'] }}</h3>
            </div>

            @if(!$backOnly)
            <div class="print-full-cell">
                <div class="print-rami-card rami-card{{ $isDosTheme ? ' print-dos-back' : '' }}" data-group="{{ $verb->group }}"
                    data-suit="{{ $verb->suit ?? 'spade' }}" data-pattern="{{ $verb->pattern ?? 'plain' }}">
                    
                    <div class="rami-card-index-top">
                        <div class="rami-card-index-pronoun">
                            @if($suitSymbol !== '')
                            <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
                            @endif
                            <span>{{ $pageData['pronoun_label'] }}</span>
                        </div>
                        <div class="rami-card-index-verb">{{ $pageData['conjugation_value'] }}</div>
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
                                    ($pageData['conjugation_value'] ?? $verb->infinitive)) }}</span>
                            </div>

                        </div>
                        @else
                        <div class="rami-card-verb-text">{{ mb_strtoupper((string) ($pageData['conjugation_value'] ??
                            $verb->infinitive)) }}</div>
                        @endif
                    </div>
                    <div class="rami-card-verb rami-card-verb-bottom" aria-hidden="true">
                        @if($useConjugatedVerb)
                        <div class="rami-card-verb-text rami-card-verb-text-v3">
                            <div class="rami-card-verb-main-row">
                                <span class="rami-card-verb-main">{{ mb_strtoupper((string)
                                    ($pageData['conjugation_value'] ?? $verb->infinitive)) }}</span>
                            </div>

                        </div>
                        @else
                        <div class="rami-card-verb-text">{{ mb_strtoupper((string) ($pageData['conjugation_value'] ??
                            $verb->infinitive)) }}</div>
                        @endif
                    </div>

                    <div class="rami-card-index-bottom">
                        <div class="rami-card-index-pronoun">
                            @if($suitSymbol !== '')
                            <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol }}</span>
                            @endif
                            <span>{{ $pageData['pronoun_label'] }}</span>
                        </div>
                        <div class="rami-card-index-verb">{{ $pageData['conjugation_value'] }}</div>
                    </div>
                </div>
            </div>
            @endif

            @if($includeBack)
            <div class="print-full-cell">
                <div class="print-rami-card rami-card {{ $backColorClass }}{{ $isDosTheme ? ' print-dos-back' : '' }}"
                    data-group="{{ $verb->group }}" data-suit="{{ $verb->suit ?? 'spade' }}"
                    data-pattern="{{ in_array($backColor, ['classic-red', 'classic-blue', 'classic-gold', 'classic-emerald', 'filigrane-red', 'filigrane-blue', 'ecusson-red', 'ecusson-blue', 'eventail-red', 'eventail-blue'], true) ? $backColor : $backPattern }}">
                </div>
            </div>
            @endif
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
        <a class="btn btn-secondary" href="{{ $printSingleUrl }}">
            <i class="ph ph-file"></i>
            1 verbe/A4
        </a>
        <a class="btn btn-secondary" href="{{ $printFullUrl }}" aria-current="page"
            style="background: var(--color-primary, #4f46e5); color: #fff; border-color: var(--color-primary, #4f46e5);">
            <i class="ph ph-arrows-out"></i>
            1 carte/A4
        </a>
        
        @if(!$includeBack)
        <a class="btn btn-secondary" href="{{ $printBackV1Url }}" target="_blank">
            <i class="ph ph-squares-four"></i>
            Dos 1
        </a>
        <a class="btn btn-secondary" href="{{ $printBackV2Url }}" target="_blank">
            <i class="ph ph-squares-four"></i>
            Dos 2
        </a>
        <a class="btn btn-secondary" href="{{ $printBackV3Url }}" target="_blank">
            <i class="ph ph-squares-four"></i>
            Dos 3
        </a>
        <a class="btn btn-secondary" href="{{ $printBackPremiumUrl }}" target="_blank"
            style="background: linear-gradient(135deg, #8B1E2F, #6B1525); color: #D4AF37; border-color: #D4AF37;">
            <i class="ph ph-star"></i>
            Premium ★
        </a>
        <a class="btn btn-secondary" href="{{ $printBackClassicRedUrl }}" target="_blank"
            style="background: #ffffff; color: #c41e3a; border-color: #c41e3a;">
            <i class="ph ph-diamond"></i>
            Classique <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span>
        </a>
        <a class="btn btn-secondary" href="{{ $printBackClassicBlueUrl }}" target="_blank"
            style="background: #ffffff; color: #1a3a6b; border-color: #1a3a6b;">
            <i class="ph ph-diamond"></i>
            Classique ♠
        </a>
        <a class="btn btn-secondary" href="{{ $printBackClassicGoldUrl }}" target="_blank"
            style="background: linear-gradient(135deg, #4a0e1e, #5c1428); color: #d4a832; border-color: #d4a832;">
            <i class="ph ph-star-four"></i>
            Or ✦
        </a>
        <a class="btn btn-secondary" href="{{ $printBackClassicEmeraldUrl }}" target="_blank"
            style="background: #f5f0e6; color: #0d5e3a; border-color: #0d5e3a;">
            <i class="ph ph-leaf"></i>
            Émeraude ♣
        </a>
        @else
        <a class="btn btn-secondary" href="{{ $printBackV1Url }}">
            <i class="ph ph-palette"></i>
            Dos 1
        </a>
        <a class="btn btn-secondary" href="{{ $printBackV2Url }}">
            <i class="ph ph-palette"></i>
            Dos 2
        </a>
        <a class="btn btn-secondary" href="{{ $printBackV3Url }}">
            <i class="ph ph-palette"></i>
            Dos 3
        </a>
        <a class="btn btn-secondary" href="{{ $printBackPremiumUrl }}"
            style="background: linear-gradient(135deg, #8B1E2F, #6B1525); color: #D4AF37; border-color: #D4AF37;">
            <i class="ph ph-star"></i>
            Premium ★
        </a>
        <a class="btn btn-secondary" href="{{ $printBackClassicRedUrl }}"
            style="background: #ffffff; color: #c41e3a; border-color: #c41e3a;">
            <i class="ph ph-diamond"></i>
            Rouge <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span>
        </a>
        <a class="btn btn-secondary" href="{{ $printBackClassicBlueUrl }}"
            style="background: #ffffff; color: #1a3a6b; border-color: #1a3a6b;">
            <i class="ph ph-diamond"></i>
            Bleu ♠
        </a>
        <a class="btn btn-secondary" href="{{ $printBackClassicGoldUrl }}"
            style="background: linear-gradient(135deg, #4a0e1e, #5c1428); color: #d4a832; border-color: #d4a832;">
            <i class="ph ph-star-four"></i>
            Or ✦
        </a>
        <a class="btn btn-secondary" href="{{ $printBackClassicEmeraldUrl }}"
            style="background: #f5f0e6; color: #0d5e3a; border-color: #0d5e3a;">
            <i class="ph ph-leaf"></i>
            Émeraude ♣
        </a>
        @endif
        
        <button class="btn btn-primary" onclick="window.print()">
            <i class="ph ph-printer"></i>
            Imprimer
        </button>
    </div>

    <div class="print-legend" style="margin-top: 10px;">
        {{ count($fullPages) }} page(s) · 1 carte en pleine page A4 par page · Couleurs : ♠ Pique · <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span> Carreaux · ♣ Trèfle · ♥ Cœur
    </div>
</div>
@endsection
