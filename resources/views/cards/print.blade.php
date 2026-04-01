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

@section('title', $verb->infinitive . ' - Carte Rami à imprimer')

@section('content')
@php
$conjugation = $verb->getPresentConjugation();
$isAuxiliary = in_array(mb_strtolower($verb->infinitive), ['être', 'avoir'], true);
$suitSymbol = $verb->suit_symbol;
$badgeText = $isAuxiliary ? 'Aux' : $verb->group;
$suitTitle = $verb->suit_title;
$paperSize = strtolower((string) request()->query('paper', 'a4'));
$paperSize = $paperSize === 'letter' ? 'letter' : 'a4';
$paperSizeLabel = $paperSize === 'letter' ? 'Letter' : 'A4';
$themeWritingStyle = $themeWritingStyle ?? null;
$isDosTheme = (($activeThemeUiVersion ?? 1) === 5) && ($themeWritingStyle === 'mono');
$wrapperClass = 'print-theme-wrapper';
if (($activeThemeUiVersion ?? 1) === 5) {
$wrapperClass .= ' theme-customizer--v5';
}
$wrapperClass .= $backOnly ? ' print-preview' : '';
$includeBack = !empty($includeBack) || (string) request()->query('include_back', '') === '1';
$backOnly = !empty($backOnly) || (string) request()->query('back_only', '') === '1';
$includeBack = $includeBack || $backOnly;
$backPattern = (string) request()->query('back_pattern', ($themeCardBackPattern ?? 'plain'));
$backColor = (string) request()->query('back_color', '1');
$allowedBackColors = ['1', '2', '3', 'premium', 'classic-red', 'classic-blue', 'classic-gold', 'classic-emerald',
'filigrane-red', 'filigrane-blue', 'ecusson-red', 'ecusson-blue', 'eventail-red', 'eventail-blue',
'indigo', 'bordeaux'];
if (! in_array($backColor, $allowedBackColors, true)) {
$backColor = '1';
}
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
$printRouteName = $useConjugatedVerb ? 'cards.print_v3' : 'cards.print';
$printBackRouteName = $useConjugatedVerb ? 'cards.print_back_v3' : 'cards.print_back';
$printBackV1Url = route($printBackRouteName, $verb).'?'.http_build_query(['back_color' => '1',
'back_pattern' => $backPattern, 'paper' => $paperSize]);
$printBackV2Url = route($printBackRouteName, $verb).'?'.http_build_query(['back_color' => '2',
'back_pattern' => $backPattern, 'paper' => $paperSize]);
$printBackV3Url = route($printBackRouteName, $verb).'?'.http_build_query(['back_color' => '3',
'back_pattern' => $backPattern, 'paper' => $paperSize]);
$printBackPremiumUrl = route($printBackRouteName, $verb).'?'.http_build_query(['back_color' =>
'premium', 'back_pattern' => $backPattern, 'paper' => $paperSize]);
$printBackClassicRedUrl = route($printBackRouteName, $verb).'?'.http_build_query(['back_color' =>
'classic-red', 'back_pattern' => 'classic-red', 'paper' => $paperSize]);
$printBackClassicBlueUrl = route($printBackRouteName, $verb).'?'.http_build_query(['back_color' =>
'classic-blue', 'back_pattern' => 'classic-blue', 'paper' => $paperSize]);
$printBackClassicGoldUrl = route($printBackRouteName, $verb).'?'.http_build_query(['back_color' =>
'classic-gold', 'back_pattern' => 'classic-gold', 'paper' => $paperSize]);
$printBackClassicEmeraldUrl = route($printBackRouteName, $verb).'?'.http_build_query(['back_color' =>
'classic-emerald', 'back_pattern' => 'classic-emerald', 'paper' => $paperSize]);
$printPaperParams = array_filter([
'include_back' => $includeBack ? 1 : null,
'back_color' => $includeBack ? $backColor : null,
'back_pattern' => $includeBack ? $backPattern : null,
'back_only' => $backOnly ? 1 : null,
], static fn ($value) => $value !== null && $value !== '');
$printPaperA4Url = route($printRouteName, $verb).'?'.http_build_query(array_merge($printPaperParams, ['paper' =>
'a4']));
$printPaperLetterUrl = route($printRouteName, $verb).'?'.http_build_query(array_merge($printPaperParams, ['paper' =>
'letter']));
@endphp

<div class="{{ $wrapperClass }}">
    <style>
        .print-main {
            --rami-card-width: 42mm;
            --rami-card-height: 80mm;
        }

        .print-preview .print-sheet {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.16);
            padding: 12px;
        }

        .print-preview .print-page {
            transform: scale(0.95);
            transform-origin: top center;
        }

        .print-preview .print-sheets {
            align-items: flex-start;
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

        .print-rami-card.print-dos-back .rami-card-badge {
            border: 1px solid var(--rami-dos-fg);
        }

        .print-rami-card.print-dos-back .rami-card-suit {
            color: var(--rami-dos-fg);
        }

        .print-rami-card.print-dos-back::before {
            opacity: 0;
        }

        .print-rami-card.print-dos-back::after {
            background-image:
                linear-gradient(to right, transparent 0, transparent 9px, var(--rami-pattern-color) 9px, var(--rami-pattern-color) 10px),
                linear-gradient(to bottom, transparent 0, transparent 9px, var(--rami-pattern-color) 9px, var(--rami-pattern-color) 10px);
            background-size: 10px 10px;
        }

        .print-sheets {
            display: grid;
            gap: 18px;
        }

        .print-sheet {
            display: grid;
            gap: 10px;
        }

        .print-page {
            display: grid;
            grid-template-columns: repeat(4, var(--rami-card-width, 200px));
            grid-template-rows: repeat(2, var(--rami-card-height, 280px));
            gap: 2mm;
            justify-content: center;
            align-content: start;
        }

        .print-card-cell {
            width: var(--rami-card-width, 200px);
            height: var(--rami-card-height, 280px);
            position: relative;
            border-radius: 0;
            overflow: visible;
        }

        .print-card-cell::after {
            content: '';
            position: absolute;
            inset: 0;
            display: none;
        }

        .print-rami-card {
            width: 100%;
            height: 100%;
            border-radius: 0;
            overflow: visible;
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

        .print-legend {
            text-align: center;
            font-size: 12px;
            color: var(--color-text-muted, #666);
        }

        .print-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .theme-customizer--v5 {
            --rami-card-back-bg: var(--rami-card-bg, #ffffff);
            --rami-card-back-border-color: var(--rami-card-border-color, rgba(0, 0, 0, 0.08));
            --rami-card-back-pattern-color: var(--rami-pattern-color, rgba(30, 58, 95, 0.03));
            --rami-card-back-title-color: var(--rami-text-muted-color, #94a3b8);
            --rami-card-back-noise-opacity: var(--rami-noise-opacity, 0.01);
        }

        .print-rami-card.rami-card-back--v1 {
            --rami-card-bg: #1b1b1b;
            --rami-card-border-color: #1b1b1b;
            --rami-pattern-color: rgba(240, 240, 240, 0.12);
            --rami-back-title-color: #f4f4f4;
            --rami-noise-opacity: 0.06;
        }

        .print-rami-card.rami-card-back--v2 {
            --rami-card-bg: #242424;
            --rami-card-border-color: #242424;
            --rami-pattern-color: rgba(235, 235, 235, 0.12);
            --rami-back-title-color: #f0f0f0;
            --rami-noise-opacity: 0.06;
        }

        .print-rami-card.rami-card-back--v3 {
            --rami-card-bg: #2e2e2e;
            --rami-card-border-color: #2e2e2e;
            --rami-pattern-color: rgba(230, 230, 230, 0.12);
            --rami-back-title-color: #e6e6e6;
            --rami-noise-opacity: 0.06;
        }

        .print-rami-card.rami-card-back--premium {
            --rami-card-bg: #8B1E2F;
            --rami-card-border-color: #6B1525;
            --rami-pattern-color: rgba(212, 175, 55, 0.08);
            --rami-back-title-color: #D4AF37;
            --rami-noise-opacity: 0.01;
        }

        .print-rami-card.rami-card-back--classic-red {
            --rami-card-bg: #ffffff;
            --rami-card-border-color: #d4d0cb;
            --rami-pattern-color: transparent;
            --rami-back-title-color: #c41e3a;
            --rami-noise-opacity: 0;
        }

        .print-rami-card.rami-card-back--classic-blue {
            --rami-card-bg: #ffffff;
            --rami-card-border-color: #d4d0cb;
            --rami-pattern-color: transparent;
            --rami-back-title-color: #1a3a6b;
            --rami-noise-opacity: 0;
        }

        .print-rami-card.rami-card-back--classic-gold {
            --rami-card-bg: #4a0e1e;
            --rami-card-border-color: #6b1525;
            --rami-pattern-color: transparent;
            --rami-back-title-color: #d4a832;
            --rami-noise-opacity: 0;
        }

        .print-rami-card.rami-card-back--classic-emerald {
            --rami-card-bg: #f5f0e6;
            --rami-card-border-color: #c5bfb2;
            --rami-pattern-color: transparent;
            --rami-back-title-color: #0d5e3a;
            --rami-noise-opacity: 0;
        }

        .theme-customizer--v5 .print-rami-card.rami-card-back--v1,
        .theme-customizer--v5 .print-rami-card.rami-card-back--v2,
        .theme-customizer--v5 .print-rami-card.rami-card-back--v3,
        .theme-customizer--v5 .print-rami-card.rami-card-back--premium {
            --rami-card-bg: var(--rami-card-back-bg);
            --rami-card-border-color: var(--rami-card-back-border-color);
            --rami-pattern-color: var(--rami-card-back-pattern-color);
            --rami-back-title-color: var(--rami-card-back-title-color);
            --rami-noise-opacity: var(--rami-card-back-noise-opacity);
        }

        .print-rami-card,
        .print-rami-card.rami-card-back,
        .print-rami-card-back {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .rami-card-back-center {
            display: grid;
            gap: 4px;
            place-items: center;
            text-align: center;
            height: 100%;
        }

        .print-rami-card .rami-card-back-title {
            color: var(--rami-back-title-color, rgba(255, 255, 255, 0.9));
            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
        }

        .print-rami-card .rami-card-back-subtitle {
            color: var(--rami-back-title-color, rgba(255, 255, 255, 0.75));
            font-size: 0.7rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        @media print {
            .print-actions {
                display: none;
            }

            @page {
                size: {
                        {
                        $paperSizeLabel
                    }
                }

                portrait;
                margin: 8mm;
            }

            .print-sheets {
                gap: 0;
            }

            .print-sheet {
                break-after: page;
                page-break-after: always;
                gap: 0;
            }

            .print-legend {
                display: none;
            }

            .print-preview .print-sheet {
                box-shadow: none;
                padding: 0;
                border-radius: 0;
                background: transparent;
            }

            .print-preview .print-page {
                transform: none;
            }
        }
    </style>

    <div class="print-sheets">
        @php
        $pronouns = [
        'je',
        'tu',
        'il',
        'elle',
        'nous',
        'vous',
        'ils',
        'elles',
        ];
        $cols = 4;
        $rows = 2;
        $cellsPerPage = $cols * $rows;
        $pronounPages = array_chunk($pronouns, $cellsPerPage);
        @endphp

        @foreach($pronounPages as $pagePronouns)
        <div class="print-sheet">
            @if(! $backOnly)
            <div class="print-page">
                @foreach($pagePronouns as $key)
                @php
                $conjugationKey = in_array($key, ['il', 'elle'], true) ? 'il_elle_on' : (in_array($key, ['ils',
                'elles'], true) ? 'ils_elles' : $key);
                $label = $key === 'je' ? $verb->pronounLabel('je') : mb_strtoupper($key);
                @endphp
                <div class="print-card-cell">
                    <div class="print-rami-card rami-card" data-group="{{ $verb->group }}"
                        data-pattern="{{ $verb->pattern ?? 'plain' }}">
                        <div class="rami-card-index-top">
                            <div class="rami-card-index-pronoun">
                                @if($suitSymbol !== '')
                                <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol
                                    }}</span>
                                @endif
                                <span>{{ $label }}</span>
                            </div>
                            <div class="rami-card-index-verb">{{ $conjugation ?
                                $verb->formatConjugation($conjugationKey, (string) ($conjugation->$conjugationKey ??
                                '')) : '' }}</div>
                        </div>

                        <span class="rami-card-badge">
                            @if($suitSymbol !== '')
                            <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol
                                }}</span>
                            @endif
                            <span>{{ $badgeText }}</span>
                        </span>
                        @if((($printUiVersion ?? 2) === 2))
                        <span class="rami-card-badge rami-card-badge-bottom" aria-hidden="true">
                            @if($suitSymbol !== '')
                            <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol
                                }}</span>
                            @endif
                            <span>{{ $badgeText }}</span>
                        </span>
                        @endif

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
                                    <span class="rami-card-verb-main">{{ mb_strtoupper($conjugation ?
                                        $verb->formatConjugation($conjugationKey, (string)
                                        ($conjugation->$conjugationKey ?? '')) : $verb->infinitive) }}</span>
                                </div>
                                <div class="rami-card-verb-sub">{{ mb_strtoupper($verb->infinitive) }}</div>
                            </div>
                            @else
                            <div class="rami-card-verb-text">{{ mb_strtoupper($verb->infinitive) }}</div>
                            @endif
                        </div>
                        <div class="rami-card-verb rami-card-verb-bottom" aria-hidden="true">
                            @if($useConjugatedVerb)
                            <div class="rami-card-verb-text rami-card-verb-text-v3">
                                <div class="rami-card-verb-main-row">
                                    <span class="rami-card-verb-main">{{ mb_strtoupper($conjugation ?
                                        $verb->formatConjugation($conjugationKey, (string)
                                        ($conjugation->$conjugationKey ?? '')) : $verb->infinitive) }}</span>
                                </div>
                                <div class="rami-card-verb-sub">{{ mb_strtoupper($verb->infinitive) }}</div>
                            </div>
                            @else
                            <div class="rami-card-verb-text">{{ mb_strtoupper($verb->infinitive) }}</div>
                            @endif
                        </div>

                        <div class="rami-card-index-bottom">
                            <div class="rami-card-index-pronoun">
                                @if($suitSymbol !== '')
                                <span class="rami-card-suit" aria-hidden="true" title="{{ $suitTitle }}">{{ $suitSymbol
                                    }}</span>
                                @endif
                                <span>{{ $label }}</span>
                            </div>
                            <div class="rami-card-index-verb">{{ $conjugation ?
                                $verb->formatConjugation($conjugationKey, (string) ($conjugation->$conjugationKey ??
                                '')) : '' }}</div>
                        </div>
                    </div>
                </div>
                @endforeach

                @for($i = 0; $i < max(0, $cellsPerPage - count($pagePronouns)); $i++) <div class="print-card-cell">
            </div>
            @endfor
        </div>
        @endif

        @if($includeBack)
        @php
        $frontCells = array_values($pagePronouns);
        $backCells = array_pad($frontCells, $cellsPerPage, null);
        $mirrored = [];
        for ($row = 0; $row < $rows; $row++) { $slice=array_slice($backCells, $row * $cols, $cols);
            $slice=array_reverse($slice); array_push($mirrored, ...$slice); } @endphp <div class="print-page">
            @foreach($mirrored as $cell)
            <div class="print-card-cell">
                @if($cell !== null)
                <div class="print-rami-card rami-card {{ $backColorClass }}{{ $isDosTheme ? ' print-dos-back' : '' }}"
                    data-group="{{ $verb->group }}"
                    data-pattern="{{ in_array($backColor, ['classic-red', 'classic-blue', 'classic-gold', 'classic-emerald', 'filigrane-red', 'filigrane-blue', 'ecusson-red', 'ecusson-blue', 'eventail-red', 'eventail-blue'], true) ? $backColor : $backPattern }}">
                    @if($backOnly)
                    <div class="rami-card-back-center">
                        <div class="rami-card-back-title">Dos</div>
                        <div class="rami-card-back-subtitle">{{ mb_strtoupper($verb->infinitive) }}</div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
    </div>
    @endif
</div>
@endforeach

<div class="print-actions">
    <button class="btn btn-back" onclick="window.history.back()">
        <i class="ph ph-arrow-left"></i>
        Retour
    </button>
    <a class="btn btn-secondary" href="{{ $printPaperA4Url }}" {{ $paperSize==='a4' ? 'aria-current=page' : '' }}>
        A4
    </a>
    <a class="btn btn-secondary" href="{{ $printPaperLetterUrl }}" {{ $paperSize==='letter' ? 'aria-current=page' : ''
        }}>
        Letter
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
        Classique ♦
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
        Classique ♦
    </a>
    <a class="btn btn-secondary" href="{{ $printBackClassicBlueUrl }}"
        style="background: #ffffff; color: #1a3a6b; border-color: #1a3a6b;">
        <i class="ph ph-diamond"></i>
        Classique ♠
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
    <button class="btn btn-print" onclick="window.print()">
        <i class="ph ph-printer"></i>
        Imprimer
    </button>
</div>

<div class="print-legend">
    Couleurs : ♠ Pique · ♦ Carreaux · ♣ Trèfle · ♥ Cœur
</div>
</div>
</div>
@endsection