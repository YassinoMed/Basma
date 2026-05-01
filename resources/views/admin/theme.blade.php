@extends('layouts.app')

@section('title', 'Personnalisation du thème - FrenchVerbs')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/preview-modern-style.css') }}">
@endpush

@section('content')
<div
    class="theme-customizer {{ in_array(($themeUiVersion ?? 1), [2, 3, 4, 5, 6, 7], true) ? 'theme-customizer--v2' : '' }} {{ in_array(($themeUiVersion ?? 1), [3, 4], true) ? 'theme-customizer--v3' : '' }} {{ (($themeUiVersion ?? 1) === 4) ? 'theme-customizer--v4' : '' }} {{ (($themeUiVersion ?? 1) === 5) ? 'theme-customizer--v5' : '' }} {{ (($themeUiVersion ?? 1) === 6) ? 'theme-customizer--v6' : '' }} {{ (($themeUiVersion ?? 1) === 7) ? 'theme-customizer--v7' : '' }}">
    <!-- Header avec tabs -->
    <div class="customizer-header">
        <div class="section-badge">
            <i class="ph ph-palette"></i>
            <span>Administration</span>
        </div>
        <h1 class="section-title">Personnalisation de la carte éducative</h1>
        <p class="section-description">
            Modifiez le design de la carte avec un aperçu en temps réel
        </p>
        <div class="customizer-version-switch" role="tablist" aria-label="Version de l'éditeur">
            <a href="{{ route('admin.theme.edit') }}"
                class="customizer-version-pill {{ (($themeUiVersion ?? 1) === 1) ? 'is-active' : '' }}"
                aria-current="{{ (($themeUiVersion ?? 1) === 1) ? 'page' : 'false' }}">V1</a>
            <a href="{{ route('admin.theme.editV2') }}"
                class="customizer-version-pill {{ (($themeUiVersion ?? 1) === 2) ? 'is-active' : '' }}"
                aria-current="{{ (($themeUiVersion ?? 1) === 2) ? 'page' : 'false' }}">V2</a>
            <a href="{{ route('admin.theme.editV3') }}"
                class="customizer-version-pill {{ (($themeUiVersion ?? 1) === 3) ? 'is-active' : '' }}"
                aria-current="{{ (($themeUiVersion ?? 1) === 3) ? 'page' : 'false' }}">V3</a>
            <a href="{{ route('admin.theme.editV4') }}"
                class="customizer-version-pill {{ (($themeUiVersion ?? 1) === 4) ? 'is-active' : '' }}"
                aria-current="{{ (($themeUiVersion ?? 1) === 4) ? 'page' : 'false' }}">V4</a>
            <a href="{{ route('admin.theme.editV5') }}"
                class="customizer-version-pill {{ (($themeUiVersion ?? 1) === 5) ? 'is-active' : '' }}"
                aria-current="{{ (($themeUiVersion ?? 1) === 5) ? 'page' : 'false' }}">V5</a>
            <a href="{{ route('admin.theme.editV6') }}"
                class="customizer-version-pill {{ (($themeUiVersion ?? 1) === 6) ? 'is-active' : '' }}"
                aria-current="{{ (($themeUiVersion ?? 1) === 6) ? 'page' : 'false' }}">V6</a>
            <a href="{{ route('admin.theme.editV7') }}"
                class="customizer-version-pill {{ (($themeUiVersion ?? 1) === 7) ? 'is-active' : '' }}"
                aria-current="{{ (($themeUiVersion ?? 1) === 7) ? 'page' : 'false' }}">V7</a>
        </div>
    </div>
    <div id="theme-message" class="mb-8 is-hidden"></div>
    <div id="rami-shadow-presets" class="is-hidden" data-presets='@json($ramiShadowPresets ?? [])'></div>
    <div id="rami-center-presets" class="is-hidden" data-presets='@json($ramiCenterPresets ?? [])'></div>
    <div id="theme-defaults" class="is-hidden" data-defaults='@json($themeDefaults ?? [])'></div>
    <div id="theme-settings" class="is-hidden" data-settings='@json($themeSettings ?? [])'></div>

    <!-- Navigation par onglets -->
    <div class="customizer-tabs">
        <button class="tab-btn active" data-tab="card">
            <i class="ph ph-cards"></i>
            <span>Carte</span>
        </button>
    </div>

    <div class="customizer-layout">
        <!-- Panel de gauche - Contrôles -->
        <div class="customizer-controls">
            <form id="theme-form">
                @csrf
                <input type="hidden" id="theme-ui-version" value="{{ (int) ($themeUiVersion ?? 1) }}">

                <div class="customizer-tools">
                    <div class="customizer-tools-row">
                        <div class="customizer-search">
                            <i class="ph ph-magnifying-glass"></i>
                            <input type="text" id="customizer-search" placeholder="Rechercher un paramètre…"
                                autocomplete="off">
                            <button type="button" class="customizer-search-clear" id="customizer-search-clear"
                                aria-label="Effacer la recherche">
                                <i class="ph ph-x"></i>
                            </button>
                        </div>
                        <div class="customizer-tools-actions">
                            <button type="button" class="btn btn-secondary customizer-tool-btn"
                                id="customizer-expand-all">
                                <i class="ph ph-plus-square"></i>
                                Ouvrir
                            </button>
                            <button type="button" class="btn btn-secondary customizer-tool-btn"
                                id="customizer-collapse-all">
                                <i class="ph ph-minus-square"></i>
                                Réduire
                            </button>
                        </div>
                    </div>
                    <div class="customizer-tools-row">
                        <div class="customizer-jump">
                            <i class="ph ph-list-bullets"></i>
                            <select id="customizer-jump">
                                <option value="">Aller à…</option>
                            </select>
                        </div>
                    </div>
                    <div class="customizer-tools-hint">Ctrl+S enregistrer · Ctrl+K rechercher</div>
                </div>

                <div class="tab-content active" data-tab-content="card">
                    <div class="control-section">
                        <h3>Assistant IA</h3>
                        <livewire:admin.theme-ai-assistant />
                    </div>

                    @if(in_array(($themeUiVersion ?? 1), [5, 6, 7], true))
                    {{-- V5 EXCLUSIVE: Writing Style --}}
                    <div class="control-section v5-exclusive-section">
                        <h3><i class="ph ph-text-aa"></i> Style d'écriture</h3>
                        <input type="hidden" data-css-var="--rami-v5-writing-style"
                            value="{{ $themeSettings['--rami-v5-writing-style'] ?? 'modern' }}">
                        <div class="v5-writing-styles">
                            <button type="button" class="v5-style-option" data-v5-writing="classic">
                                <div class="v5-style-preview" style="font-family: 'Lora', serif;">
                                    <span class="v5-style-letter">Aa</span>
                                    <span class="v5-style-sample">Manger</span>
                                </div>
                                <span class="v5-style-label">Classique</span>
                                <span class="v5-style-desc">Serif élégant</span>
                            </button>
                            <button type="button" class="v5-style-option" data-v5-writing="modern">
                                <div class="v5-style-preview" style="font-family: 'Inter', sans-serif;">
                                    <span class="v5-style-letter">Aa</span>
                                    <span class="v5-style-sample">Manger</span>
                                </div>
                                <span class="v5-style-label">Moderne</span>
                                <span class="v5-style-desc">Sans-serif net</span>
                            </button>
                            <button type="button" class="v5-style-option" data-v5-writing="elegant">
                                <div class="v5-style-preview" style="font-family: 'Outfit', sans-serif;">
                                    <span class="v5-style-letter">Aa</span>
                                    <span class="v5-style-sample">Manger</span>
                                </div>
                                <span class="v5-style-label">Élégant</span>
                                <span class="v5-style-desc">Raffiné doux</span>
                            </button>
                            <button type="button" class="v5-style-option" data-v5-writing="minimalist">
                                <div class="v5-style-preview" style="font-family: 'Space Grotesk', sans-serif;">
                                    <span class="v5-style-letter">Aa</span>
                                    <span class="v5-style-sample">Manger</span>
                                </div>
                                <span class="v5-style-label">Minimaliste</span>
                                <span class="v5-style-desc">Géométrique</span>
                            </button>
                            <button type="button" class="v5-style-option" data-v5-writing="handwritten">
                                <div class="v5-style-preview" style="font-family: 'Patrick Hand', cursive;">
                                    <span class="v5-style-letter">Aa</span>
                                    <span class="v5-style-sample">Manger</span>
                                </div>
                                <span class="v5-style-label">Scolaire</span>
                                <span class="v5-style-desc">Écriture manuscrite</span>
                            </button>
                            <button type="button" class="v5-style-option" data-v5-writing="mono">
                                <div class="v5-style-preview" style="font-family: 'JetBrains Mono', monospace;">
                                    <span class="v5-style-letter">Aa</span>
                                    <span class="v5-style-sample">Manger</span>
                                </div>
                                <span class="v5-style-label">Mono</span>
                                <span class="v5-style-desc">Lisible & régulier</span>
                            </button>
                            <button type="button" class="v5-style-option" data-v5-writing="compact">
                                <div class="v5-style-preview" style="font-family: 'Inter', sans-serif;">
                                    <span class="v5-style-letter">Aa</span>
                                    <span class="v5-style-sample">Manger</span>
                                </div>
                                <span class="v5-style-label">Compact</span>
                                <span class="v5-style-desc">Lettres plus serrées</span>
                            </button>
                        </div>
                    </div>

                    {{-- V5 EXCLUSIVE: Card Shape --}}
                    <div class="control-section v5-exclusive-section">
                        <h3><i class="ph ph-shapes"></i> Forme de la carte</h3>
                        <input type="hidden" data-css-var="--rami-v5-card-shape"
                            value="{{ $themeSettings['--rami-v5-card-shape'] ?? 'rounded' }}">
                        <div class="v5-shape-options">
                            <button type="button" class="v5-shape-option" data-v5-shape="rounded">
                                <div class="v5-shape-preview v5-shape-rounded"></div>
                                <span>Arrondie</span>
                            </button>
                            <button type="button" class="v5-shape-option" data-v5-shape="square">
                                <div class="v5-shape-preview v5-shape-square"></div>
                                <span>Carrée</span>
                            </button>
                            <button type="button" class="v5-shape-option" data-v5-shape="sharp">
                                <div class="v5-shape-preview v5-shape-sharp"></div>
                                <span>Nette</span>
                            </button>
                            <button type="button" class="v5-shape-option" data-v5-shape="pill">
                                <div class="v5-shape-preview v5-shape-pill"></div>
                                <span>Capsule</span>
                            </button>
                        </div>
                    </div>

                    {{-- V5 EXCLUSIVE: Zoom Control --}}
                    <div class="control-section v5-exclusive-section">
                        <h3><i class="ph ph-magnifying-glass-plus"></i> Zoom carte</h3>
                        <div class="slider-group">
                            <label>Niveau de zoom</label>
                            <div class="slider-wrapper">
                                <input type="range" min="1" max="5" step="0.5"
                                    value="{{ $themeSettings['--rami-v5-zoom-level'] ?? '3' }}" id="v5-zoom-slider"
                                    data-css-var="--rami-v5-zoom-level" class="slider">
                                <span class="slider-value" id="v5-zoom-value">{{ $themeSettings['--rami-v5-zoom-level']
                                    ?? '3' }}×</span>
                            </div>
                        </div>
                        <div class="customizer-help">
                            Cliquez sur une carte dans l'aperçu pour activer le zoom interactif.
                            Utilisez la molette ou le curseur pour ajuster.
                        </div>
                    </div>
                    @endif

                    @if(($themeUiVersion ?? 1) === 6)
                    {{-- V6 EXCLUSIVE: Card Glow --}}
                    <div class="control-section v6-exclusive-section">
                        <h3><i class="ph ph-sun-dim"></i> Effet Glow</h3>
                        <input type="hidden" data-css-var="--rami-v6-card-glow"
                            value="{{ $themeSettings['--rami-v6-card-glow'] ?? 'subtle' }}">
                        <div class="v6-glow-options">
                            <button type="button" class="v6-glow-option" data-v6-glow="none">
                                <div class="v6-glow-preview v6-glow-preview--none"></div>
                                <span>Aucun</span>
                            </button>
                            <button type="button" class="v6-glow-option" data-v6-glow="subtle">
                                <div class="v6-glow-preview v6-glow-preview--subtle"></div>
                                <span>Subtil</span>
                            </button>
                            <button type="button" class="v6-glow-option" data-v6-glow="intense">
                                <div class="v6-glow-preview v6-glow-preview--intense"></div>
                                <span>Intense</span>
                            </button>
                        </div>
                    </div>

                    {{-- V6 EXCLUSIVE: Typography Weight --}}
                    <div class="control-section v6-exclusive-section">
                        <h3><i class="ph ph-text-b"></i> Poids typographique</h3>
                        <input type="hidden" data-css-var="--rami-v6-typography-weight"
                            value="{{ $themeSettings['--rami-v6-typography-weight'] ?? 'medium' }}">
                        <div class="v6-weight-options">
                            <button type="button" class="v6-weight-option" data-v6-weight="light">
                                <span class="v6-weight-sample" style="font-weight:400">Aa</span>
                                <span>Léger</span>
                            </button>
                            <button type="button" class="v6-weight-option" data-v6-weight="medium">
                                <span class="v6-weight-sample" style="font-weight:600">Aa</span>
                                <span>Moyen</span>
                            </button>
                            <button type="button" class="v6-weight-option" data-v6-weight="bold">
                                <span class="v6-weight-sample" style="font-weight:800">Aa</span>
                                <span>Gras</span>
                            </button>
                        </div>
                    </div>
                    @endif

                    @if(($themeUiVersion ?? 1) === 7)
                    {{-- V7 EXCLUSIVE: Ornament Style --}}
                    <div class="control-section v7-exclusive-section">
                        <h3><i class="ph ph-flower-lotus"></i> Style d'ornements</h3>
                        <input type="hidden" data-css-var="--rami-v7-ornament-style"
                            value="{{ $themeSettings['--rami-v7-ornament-style'] ?? 'fleuron' }}">
                        <div class="v7-ornament-options">
                            <button type="button" class="v7-ornament-option" data-v7-ornament="minimal">
                                <span class="v7-ornament-preview">·</span>
                                <span>Minimal</span>
                            </button>
                            <button type="button" class="v7-ornament-option" data-v7-ornament="fleuron">
                                <span class="v7-ornament-preview">❧</span>
                                <span>Fleuron</span>
                            </button>
                            <button type="button" class="v7-ornament-option" data-v7-ornament="royal">
                                <span class="v7-ornament-preview">♛</span>
                                <span>Royal</span>
                            </button>
                        </div>
                    </div>

                    {{-- V7 EXCLUSIVE: Gold Intensity --}}
                    <div class="control-section v7-exclusive-section">
                        <h3><i class="ph ph-sparkle"></i> Intensité de l'or</h3>
                        <input type="hidden" data-css-var="--rami-v7-gold-intensity"
                            value="{{ $themeSettings['--rami-v7-gold-intensity'] ?? 'classic' }}">
                        <div class="v7-gold-options">
                            <button type="button" class="v7-gold-option" data-v7-gold="muted">
                                <div class="v7-gold-swatch v7-gold-swatch--muted"></div>
                                <span>Discret</span>
                            </button>
                            <button type="button" class="v7-gold-option" data-v7-gold="classic">
                                <div class="v7-gold-swatch v7-gold-swatch--classic"></div>
                                <span>Classique</span>
                            </button>
                            <button type="button" class="v7-gold-option" data-v7-gold="rich">
                                <div class="v7-gold-swatch v7-gold-swatch--rich"></div>
                                <span>Riche</span>
                            </button>
                        </div>
                    </div>
                    @endif

                    <div class="control-section">
                        <h3>Carte</h3>
                        <div class="color-grid">
                            <div class="color-input-group">
                                <label>Fond</label>
                                <div class="color-input-wrapper">
                                    <input type="color" data-css-var="--rami-card-bg"
                                        value="{{ $themeSettings['--rami-card-bg'] ?? '#faf8f5' }}">
                                    <input type="text" class="color-hex"
                                        value="{{ $themeSettings['--rami-card-bg'] ?? '#faf8f5' }}" readonly>
                                </div>
                            </div>
                            <div class="color-input-group">
                                <label>Bordure</label>
                                <div class="color-input-wrapper">
                                    <input type="color" data-css-var="--rami-card-border-color"
                                        value="{{ $themeSettings['--rami-card-border-color'] ?? '#3a3a3a' }}">
                                    <input type="text" class="color-hex"
                                        value="{{ $themeSettings['--rami-card-border-color'] ?? '#3a3a3a' }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Épaisseur bordure</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="6"
                                    value="{{ (int) ($themeSettings['--rami-card-border-width'] ?? '2') }}"
                                    data-css-var="--rami-card-border-width" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-card-border-width'] ?? '2px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Largeur carte</label>
                            <div class="slider-wrapper">
                                <input type="range" min="160" max="320"
                                    value="{{ (int) ($themeSettings['--rami-card-width'] ?? '200') }}"
                                    data-css-var="--rami-card-width" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-card-width'] ?? '200px' }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Hauteur carte</label>
                            <div class="slider-wrapper">
                                <input type="range" min="220" max="420"
                                    value="{{ (int) ($themeSettings['--rami-card-height'] ?? '280') }}"
                                    data-css-var="--rami-card-height" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-card-height'] ?? '280px' }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Largeur carte (détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="260" max="520"
                                    value="{{ (int) ($themeSettings['--rami-card-width-large'] ?? '320') }}"
                                    data-css-var="--rami-card-width-large" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-card-width-large'] ?? '320px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Hauteur carte (détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="360" max="720"
                                    value="{{ (int) ($themeSettings['--rami-card-height-large'] ?? '448') }}"
                                    data-css-var="--rami-card-height-large" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-card-height-large'] ?? '448px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Style bordure</label>
                            <div class="color-input-wrapper">
                                <select class="color-hex" data-css-var="--rami-card-border-style">
                                    <option value="solid" @selected(($themeSettings['--rami-card-border-style']
                                        ?? 'solid' )==='solid' )>Solide</option>
                                    <option value="dashed" @selected(($themeSettings['--rami-card-border-style'] ?? ''
                                        )==='dashed' )>Tirets</option>
                                    <option value="dotted" @selected(($themeSettings['--rami-card-border-style'] ?? ''
                                        )==='dotted' )>Pointillé</option>
                                    <option value="double" @selected(($themeSettings['--rami-card-border-style'] ?? ''
                                        )==='double' )>Double</option>
                                </select>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Rayon</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="30"
                                    value="{{ (int) ($themeSettings['--rami-card-radius'] ?? '12') }}"
                                    data-css-var="--rami-card-radius" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-card-radius'] ?? '12px' }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Rayon haut gauche</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="40"
                                    value="{{ (int) ($themeSettings['--rami-card-radius-tl'] ?? '12') }}"
                                    data-css-var="--rami-card-radius-tl" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-card-radius-tl'] ?? '12px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Rayon haut droit</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="40"
                                    value="{{ (int) ($themeSettings['--rami-card-radius-tr'] ?? '12') }}"
                                    data-css-var="--rami-card-radius-tr" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-card-radius-tr'] ?? '12px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Rayon bas droit</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="40"
                                    value="{{ (int) ($themeSettings['--rami-card-radius-br'] ?? '12') }}"
                                    data-css-var="--rami-card-radius-br" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-card-radius-br'] ?? '12px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Rayon bas gauche</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="40"
                                    value="{{ (int) ($themeSettings['--rami-card-radius-bl'] ?? '12') }}"
                                    data-css-var="--rami-card-radius-bl" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-card-radius-bl'] ?? '12px'
                                    }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-section">
                        <h3>Illustration</h3>
                        <div class="color-grid">
                            <div class="color-input-group">
                                <label>Dégradé (début)</label>
                                <div class="color-input-wrapper">
                                    <input type="color" data-css-var="--rami-illustration-bg-start"
                                        value="{{ $themeSettings['--rami-illustration-bg-start'] ?? '#f0efed' }}">
                                    <input type="text" class="color-hex"
                                        value="{{ $themeSettings['--rami-illustration-bg-start'] ?? '#f0efed' }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="color-input-group">
                                <label>Dégradé (fin)</label>
                                <div class="color-input-wrapper">
                                    <input type="color" data-css-var="--rami-illustration-bg-end"
                                        value="{{ $themeSettings['--rami-illustration-bg-end'] ?? '#e8e6e2' }}">
                                    <input type="text" class="color-hex"
                                        value="{{ $themeSettings['--rami-illustration-bg-end'] ?? '#e8e6e2' }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="color-input-group">
                                <label>Bordure illustration</label>
                                <div class="color-input-wrapper">
                                    <input type="text" class="color-hex" data-css-var="--rami-illustration-border-color"
                                        value="{{ $themeSettings['--rami-illustration-border-color'] ?? 'rgba(30, 58, 95, 0.1)' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="control-section">
                        <h3>Centre</h3>

                        <input type="hidden" id="rami_illustration_shadow" data-css-var="--rami-illustration-shadow"
                            value="{{ $themeSettings['--rami-illustration-shadow'] ?? ($ramiCenterPresets['circle']['illustrationShadow'] ?? '') }}">

                        <div class="slider-group">
                            <label>Position verticale (petit)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="35" max="65"
                                    value="{{ (float) str_replace('%', '', ($themeSettings['--rami-center-top'] ?? '48%')) }}"
                                    data-css-var="--rami-center-top" data-unit="%" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-center-top'] ?? '48%' }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Marge autour du centre</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="24"
                                    value="{{ (int) ($themeSettings['--rami-center-padding'] ?? '10') }}"
                                    data-css-var="--rami-center-padding" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-center-padding'] ?? '10px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="shadow-selector">
                            <label><i class="ph ph-hand"></i> Design</label>
                            <div class="shadow-options">
                                <button type="button" class="shadow-option" data-rami-center-preset="circle">
                                    <div class="center-demo center-circle"></div>
                                    <span>Cercle</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="rounded">
                                    <div class="center-demo center-rounded"></div>
                                    <span>Arrondi</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="square">
                                    <div class="center-demo center-square"></div>
                                    <span>Compact</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="neumo">
                                    <div class="center-demo center-neumo"></div>
                                    <span>Neumo</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="glass">
                                    <div class="center-demo center-glass"></div>
                                    <span>Verre</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="halo">
                                    <div class="center-demo center-halo"></div>
                                    <span>Halo</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="frame">
                                    <div class="center-demo center-frame"></div>
                                    <span>Cadre</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="minimal">
                                    <div class="center-demo center-minimal"></div>
                                    <span>Minimal</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="pill">
                                    <div class="center-demo center-pill"></div>
                                    <span>Pilule</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="squircle">
                                    <div class="center-demo center-squircle"></div>
                                    <span>Squircle</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="sharp">
                                    <div class="center-demo center-sharp"></div>
                                    <span>Carré</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="outline">
                                    <div class="center-demo center-outline"></div>
                                    <span>Outline</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="glow">
                                    <div class="center-demo center-glow"></div>
                                    <span>Glow</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="frost">
                                    <div class="center-demo center-frost"></div>
                                    <span>Poudre</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="hex">
                                    <div class="center-demo center-hex"></div>
                                    <span>Hex</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="diamond">
                                    <div class="center-demo center-diamond"></div>
                                    <span>Losange</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="star">
                                    <div class="center-demo center-star"></div>
                                    <span>Étoile</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-center-preset="blob">
                                    <div class="center-demo center-blob"></div>
                                    <span>Blob</span>
                                </button>
                            </div>
                        </div>

                        <div class="design-selector">
                            <label><i class="ph ph-palette"></i> Styles</label>
                            <input type="hidden" id="rami_illustration_design" data-css-var="--rami-illustration-design"
                                value="{{ $themeSettings['--rami-illustration-design'] ?? 'none' }}">
                            <div class="design-toolbar">
                                <select id="design-category-filter" class="design-toolbar-select"
                                    aria-label="Catégorie de style">
                                    <option value="all">Tous</option>
                                    <option value="gradient">Gradients</option>
                                    <option value="pattern">Patterns</option>
                                    <option value="texture">Textures</option>
                                    <option value="glass">Glass</option>
                                    <option value="neon">Néon & Glow</option>
                                    <option value="metal">Métallique</option>
                                    <option value="nature">Nature</option>
                                    <option value="geo">Géométrique</option>
                                    <option value="art">Artistique</option>
                                    <option value="future">Futuriste</option>
                                    <option value="retro">Rétro</option>
                                </select>
                                <input id="design-search-filter" class="design-toolbar-search" type="text"
                                    placeholder="Rechercher…" autocomplete="off" />
                                <button type="button" id="design-filter-clear"
                                    class="design-toolbar-clear">Effacer</button>
                            </div>
                            <div class="design-current">
                                <span class="design-current-label">Sélection :</span>
                                <span id="design-current-value" class="design-current-value"></span>
                            </div>
                            <div class="design-options">
                                <button type="button" class="design-option" data-rami-illustration-design="none">
                                    <div class="design-demo"></div>
                                    <span>Défaut</span>
                                </button>

                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-sunset">
                                    <div class="design-demo design-gradient-sunset"></div>
                                    <span>Sunset</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-ocean">
                                    <div class="design-demo design-gradient-ocean"></div>
                                    <span>Ocean</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-forest">
                                    <div class="design-demo design-gradient-forest"></div>
                                    <span>Forest</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-lavender">
                                    <div class="design-demo design-gradient-lavender"></div>
                                    <span>Lavender</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-fire">
                                    <div class="design-demo design-gradient-fire"></div>
                                    <span>Fire</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-ice">
                                    <div class="design-demo design-gradient-ice"></div>
                                    <span>Ice</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-emerald">
                                    <div class="design-demo design-gradient-emerald"></div>
                                    <span>Emerald</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-ruby">
                                    <div class="design-demo design-gradient-ruby"></div>
                                    <span>Ruby</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-amber">
                                    <div class="design-demo design-gradient-amber"></div>
                                    <span>Amber</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-sapphire">
                                    <div class="design-demo design-gradient-sapphire"></div>
                                    <span>Sapphire</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-aurora">
                                    <div class="design-demo design-gradient-aurora"></div>
                                    <span>Aurora</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-cosmic">
                                    <div class="design-demo design-gradient-cosmic"></div>
                                    <span>Cosmic</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-peacock">
                                    <div class="design-demo design-gradient-peacock"></div>
                                    <span>Peacock</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-desert">
                                    <div class="design-demo design-gradient-desert"></div>
                                    <span>Desert</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-tropical">
                                    <div class="design-demo design-gradient-tropical"></div>
                                    <span>Tropical</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-volcanic">
                                    <div class="design-demo design-gradient-volcanic"></div>
                                    <span>Volcanic</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-arctic">
                                    <div class="design-demo design-gradient-arctic"></div>
                                    <span>Arctic</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-midnight">
                                    <div class="design-demo design-gradient-midnight"></div>
                                    <span>Midnight</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-cherry">
                                    <div class="design-demo design-gradient-cherry"></div>
                                    <span>Cherry</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="gradient-mint">
                                    <div class="design-demo design-gradient-mint"></div>
                                    <span>Mint</span>
                                </button>

                                <button type="button" class="design-option"
                                    data-rami-illustration-design="pattern-dots">
                                    <div class="design-demo design-pattern-dots"></div>
                                    <span>Dots</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="pattern-grid">
                                    <div class="design-demo design-pattern-grid"></div>
                                    <span>Grid</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="pattern-stripes">
                                    <div class="design-demo design-pattern-stripes"></div>
                                    <span>Stripes</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="pattern-waves">
                                    <div class="design-demo design-pattern-waves"></div>
                                    <span>Waves</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="pattern-hexagons">
                                    <div class="design-demo design-pattern-hexagons"></div>
                                    <span>Hexagons</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="pattern-honeycomb">
                                    <div class="design-demo design-pattern-honeycomb"></div>
                                    <span>Honeycomb</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="pattern-circuit">
                                    <div class="design-demo design-pattern-circuit"></div>
                                    <span>Circuit</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="pattern-blueprint">
                                    <div class="design-demo design-pattern-blueprint"></div>
                                    <span>Blueprint</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="pattern-moroccan">
                                    <div class="design-demo design-pattern-moroccan"></div>
                                    <span>Moroccan</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="pattern-carbon">
                                    <div class="design-demo design-pattern-carbon"></div>
                                    <span>Carbon</span>
                                </button>

                                <button type="button" class="design-option"
                                    data-rami-illustration-design="texture-paper">
                                    <div class="design-demo design-texture-paper"></div>
                                    <span>Paper</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="texture-canvas">
                                    <div class="design-demo design-texture-canvas"></div>
                                    <span>Canvas</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="texture-linen">
                                    <div class="design-demo design-texture-linen"></div>
                                    <span>Linen</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="texture-concrete">
                                    <div class="design-demo design-texture-concrete"></div>
                                    <span>Concrete</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="texture-marble">
                                    <div class="design-demo design-texture-marble"></div>
                                    <span>Marble</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="texture-velvet">
                                    <div class="design-demo design-texture-velvet"></div>
                                    <span>Velvet</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="texture-silk">
                                    <div class="design-demo design-texture-silk"></div>
                                    <span>Silk</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="texture-denim">
                                    <div class="design-demo design-texture-denim"></div>
                                    <span>Denim</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="texture-leather">
                                    <div class="design-demo design-texture-leather"></div>
                                    <span>Leather</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="texture-cork">
                                    <div class="design-demo design-texture-cork"></div>
                                    <span>Cork</span>
                                </button>

                                <button type="button" class="design-option" data-rami-illustration-design="glass-frost">
                                    <div class="design-demo design-glass-frost"></div>
                                    <span>Frost</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="glass-blur">
                                    <div class="design-demo design-glass-blur"></div>
                                    <span>Blur</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="glass-crystal">
                                    <div class="design-demo design-glass-crystal"></div>
                                    <span>Crystal</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="glass-smoke">
                                    <div class="design-demo design-glass-smoke"></div>
                                    <span>Smoke</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="glass-aurora">
                                    <div class="design-demo design-glass-aurora"></div>
                                    <span>Aurora</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="glass-tinted">
                                    <div class="design-demo design-glass-tinted"></div>
                                    <span>Tinted</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="glass-mirror">
                                    <div class="design-demo design-glass-mirror"></div>
                                    <span>Mirror</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="glass-opal">
                                    <div class="design-demo design-glass-opal"></div>
                                    <span>Opal</span>
                                </button>

                                <button type="button" class="design-option" data-rami-illustration-design="neon-blue">
                                    <div class="design-demo design-neon-blue"></div>
                                    <span>Neon Blue</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="neon-pink">
                                    <div class="design-demo design-neon-pink"></div>
                                    <span>Neon Pink</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="neon-green">
                                    <div class="design-demo design-neon-green"></div>
                                    <span>Neon Green</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="glow-soft">
                                    <div class="design-demo design-glow-soft"></div>
                                    <span>Soft Glow</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="glow-rainbow">
                                    <div class="design-demo design-glow-rainbow"></div>
                                    <span>Rainbow</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="neon-cyan">
                                    <div class="design-demo design-neon-cyan"></div>
                                    <span>Neon Cyan</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="neon-yellow">
                                    <div class="design-demo design-neon-yellow"></div>
                                    <span>Neon Yellow</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="neon-orange">
                                    <div class="design-demo design-neon-orange"></div>
                                    <span>Neon Orange</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="glow-plasma">
                                    <div class="design-demo design-glow-plasma"></div>
                                    <span>Plasma</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="glow-ethereal">
                                    <div class="design-demo design-glow-ethereal"></div>
                                    <span>Ethereal</span>
                                </button>

                                <button type="button" class="design-option" data-rami-illustration-design="metal-gold">
                                    <div class="design-demo design-metal-gold"></div>
                                    <span>Gold</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="metal-silver">
                                    <div class="design-demo design-metal-silver"></div>
                                    <span>Silver</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="metal-bronze">
                                    <div class="design-demo design-metal-bronze"></div>
                                    <span>Bronze</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="metal-copper">
                                    <div class="design-demo design-metal-copper"></div>
                                    <span>Copper</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="metal-platinum">
                                    <div class="design-demo design-metal-platinum"></div>
                                    <span>Platinum</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="metal-titanium">
                                    <div class="design-demo design-metal-titanium"></div>
                                    <span>Titanium</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="metal-rose">
                                    <div class="design-demo design-metal-rose"></div>
                                    <span>Rose Gold</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="metal-chrome">
                                    <div class="design-demo design-metal-chrome"></div>
                                    <span>Chrome</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="metal-mercury">
                                    <div class="design-demo design-metal-mercury"></div>
                                    <span>Mercury</span>
                                </button>

                                <button type="button" class="design-option" data-rami-illustration-design="nature-wood">
                                    <div class="design-demo design-nature-wood"></div>
                                    <span>Wood</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="nature-leaf">
                                    <div class="design-demo design-nature-leaf"></div>
                                    <span>Leaf</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="nature-stone">
                                    <div class="design-demo design-nature-stone"></div>
                                    <span>Stone</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="nature-sand">
                                    <div class="design-demo design-nature-sand"></div>
                                    <span>Sand</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="nature-water">
                                    <div class="design-demo design-nature-water"></div>
                                    <span>Water</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="nature-bamboo">
                                    <div class="design-demo design-nature-bamboo"></div>
                                    <span>Bamboo</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="nature-coral">
                                    <div class="design-demo design-nature-coral"></div>
                                    <span>Coral</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="nature-moss">
                                    <div class="design-demo design-nature-moss"></div>
                                    <span>Moss</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="nature-sky">
                                    <div class="design-demo design-nature-sky"></div>
                                    <span>Sky</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="nature-earth">
                                    <div class="design-demo design-nature-earth"></div>
                                    <span>Earth</span>
                                </button>

                                <button type="button" class="design-option"
                                    data-rami-illustration-design="geo-triangles">
                                    <div class="design-demo design-geo-triangles"></div>
                                    <span>Triangles</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="geo-hexagon">
                                    <div class="design-demo design-geo-hexagon"></div>
                                    <span>Hexagon</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="geo-diamond">
                                    <div class="design-demo design-geo-diamond"></div>
                                    <span>Diamond</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="geo-octagon">
                                    <div class="design-demo design-geo-octagon"></div>
                                    <span>Octagon</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="geo-star">
                                    <div class="design-demo design-geo-star"></div>
                                    <span>Star</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="geo-pentagon">
                                    <div class="design-demo design-geo-pentagon"></div>
                                    <span>Pentagon</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="geo-chevron">
                                    <div class="design-demo design-geo-chevron"></div>
                                    <span>Chevron</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="geo-cross">
                                    <div class="design-demo design-geo-cross"></div>
                                    <span>Cross</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="geo-arrow">
                                    <div class="design-demo design-geo-arrow"></div>
                                    <span>Arrow</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="geo-wave">
                                    <div class="design-demo design-geo-wave"></div>
                                    <span>Wave</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="art-watercolor">
                                    <div class="design-demo design-art-watercolor"></div>
                                    <span>Watercolor</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="art-oil">
                                    <div class="design-demo design-art-oil"></div>
                                    <span>Oil</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="art-mosaic">
                                    <div class="design-demo design-art-mosaic"></div>
                                    <span>Mosaic</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="art-stained">
                                    <div class="design-demo design-art-stained"></div>
                                    <span>Stained</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="art-pixel">
                                    <div class="design-demo design-art-pixel"></div>
                                    <span>Pixel</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="future-hologram">
                                    <div class="design-demo design-future-hologram"></div>
                                    <span>Hologram</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="future-matrix">
                                    <div class="design-demo design-future-matrix"></div>
                                    <span>Matrix</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="future-cyber">
                                    <div class="design-demo design-future-cyber"></div>
                                    <span>Cyber</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="future-quantum">
                                    <div class="design-demo design-future-quantum"></div>
                                    <span>Quantum</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="future-grid">
                                    <div class="design-demo design-future-grid"></div>
                                    <span>Grid</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="retro-80s">
                                    <div class="design-demo design-retro-80s"></div>
                                    <span>80s</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="retro-vaporwave">
                                    <div class="design-demo design-retro-vaporwave"></div>
                                    <span>Vaporwave</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="retro-sepia">
                                    <div class="design-demo design-retro-sepia"></div>
                                    <span>Sepia</span>
                                </button>
                                <button type="button" class="design-option"
                                    data-rami-illustration-design="retro-vintage">
                                    <div class="design-demo design-retro-vintage"></div>
                                    <span>Vintage</span>
                                </button>
                                <button type="button" class="design-option" data-rami-illustration-design="retro-film">
                                    <div class="design-demo design-retro-film"></div>
                                    <span>Film Noir</span>
                                </button>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Taille</label>
                            <div class="slider-wrapper">
                                <input type="range" min="60" max="200"
                                    value="{{ (int) ($themeSettings['--rami-illustration-size'] ?? '120') }}"
                                    data-css-var="--rami-illustration-size" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-illustration-size'] ?? '120px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Épaisseur bordure</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="6"
                                    value="{{ (int) ($themeSettings['--rami-illustration-border-width'] ?? '2') }}"
                                    data-css-var="--rami-illustration-border-width" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-illustration-border-width'] ??
                                    '2px' }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Inset intérieur</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="48"
                                    value="{{ (int) ($themeSettings['--rami-illustration-inner-inset'] ?? '0') }}"
                                    data-css-var="--rami-illustration-inner-inset" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-illustration-inner-inset'] ?? '0px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="color-grid">
                            <div class="color-input-group">
                                <label>Fond intérieur (début)</label>
                                <div class="color-input-wrapper">
                                    <input type="text" class="color-hex"
                                        data-css-var="--rami-illustration-inner-bg-start"
                                        value="{{ $themeSettings['--rami-illustration-inner-bg-start'] ?? 'rgba(255, 255, 255, 0)' }}">
                                </div>
                            </div>
                            <div class="color-input-group">
                                <label>Fond intérieur (fin)</label>
                                <div class="color-input-wrapper">
                                    <input type="text" class="color-hex" data-css-var="--rami-illustration-inner-bg-end"
                                        value="{{ $themeSettings['--rami-illustration-inner-bg-end'] ?? 'rgba(255, 255, 255, 0)' }}">
                                </div>
                            </div>
                            <!-- Hidden Clip Path Input -->
                            <input type="hidden" data-css-var="--rami-illustration-clip-path"
                                value="{{ $themeSettings['--rami-illustration-clip-path'] ?? 'none' }}">
                        </div>

                    </div>


                    <div class="control-section">
                        <h3>Texte & groupes</h3>
                        <div class="color-grid">
                            @if(in_array(($themeUiVersion ?? 1), [5, 6, 7], true))
                            @php
                            $accentPickerFallback = $themeSettings['--rami-group-1-color'] ?? '#4f46e5';
                            $mutedPickerFallback = $themeSettings['--rami-text-muted-color'] ?? '#94a3b8';
                            @endphp
                            <div class="color-input-group">
                                <label>Verbe</label>
                                <div class="color-input-wrapper">
                                    @php
                                    $verbColor = $themeSettings['--rami-verb-color'] ?? 'var(--rami-accent-color)';
                                    $verbColorPicker = preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $verbColor)
                                    === 1 ? $verbColor : $accentPickerFallback;
                                    @endphp
                                    <input type="color" data-css-var="--rami-verb-color" value="{{ $verbColorPicker }}">
                                    <input type="text" class="color-hex" data-css-var="--rami-verb-color"
                                        value="{{ $verbColor }}">
                                </div>
                            </div>
                            <div class="color-input-group">
                                <label>Petit verbe</label>
                                <div class="color-input-wrapper">
                                    @php
                                    $indexVerbColor = $themeSettings['--rami-index-verb-color'] ??
                                    'var(--rami-text-muted-color)';
                                    $indexVerbColorPicker = preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/',
                                    $indexVerbColor) === 1 ? $indexVerbColor : $mutedPickerFallback;
                                    @endphp
                                    <input type="color" data-css-var="--rami-index-verb-color"
                                        value="{{ $indexVerbColorPicker }}">
                                    <input type="text" class="color-hex" data-css-var="--rami-index-verb-color"
                                        value="{{ $indexVerbColor }}">
                                </div>
                            </div>
                            <div class="color-input-group">
                                <label>Pronom</label>
                                <div class="color-input-wrapper">
                                    @php
                                    $pronounColor = $themeSettings['--rami-pronoun-color'] ??
                                    'var(--rami-accent-color)';
                                    $pronounColorPicker = preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/',
                                    $pronounColor) === 1 ? $pronounColor : $accentPickerFallback;
                                    @endphp
                                    <input type="color" data-css-var="--rami-pronoun-color"
                                        value="{{ $pronounColorPicker }}">
                                    <input type="text" class="color-hex" data-css-var="--rami-pronoun-color"
                                        value="{{ $pronounColor }}">
                                </div>
                            </div>
                            <div class="color-input-group">
                                <label>Sous-texte</label>
                                <div class="color-input-wrapper">
                                    @php
                                    $verbSubColor = $themeSettings['--rami-verb-sub-color'] ??
                                    'var(--rami-text-muted-color)';
                                    $verbSubColorPicker = preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/',
                                    $verbSubColor) === 1 ? $verbSubColor : $mutedPickerFallback;
                                    @endphp
                                    <input type="color" data-css-var="--rami-verb-sub-color"
                                        value="{{ $verbSubColorPicker }}">
                                    <input type="text" class="color-hex" data-css-var="--rami-verb-sub-color"
                                        value="{{ $verbSubColor }}">
                                </div>
                            </div>
                            <div class="color-input-group">
                                <label>Infinitif (grand)</label>
                                <div class="color-input-wrapper">
                                    @php
                                    $infinitiveColor = $themeSettings['--rami-infinitive-color'] ??
                                    'var(--rami-text-muted-color)';
                                    $infinitiveColorPicker = preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/',
                                    $infinitiveColor) === 1 ? $infinitiveColor : $mutedPickerFallback;
                                    @endphp
                                    <input type="color" data-css-var="--rami-infinitive-color"
                                        value="{{ $infinitiveColorPicker }}">
                                    <input type="text" class="color-hex" data-css-var="--rami-infinitive-color"
                                        value="{{ $infinitiveColor }}">
                                </div>
                            </div>
                            @endif
                            <div class="color-input-group">
                                <label>Groupe 1</label>
                                <div class="color-input-wrapper">
                                    <input type="color" data-css-var="--rami-group-1-color"
                                        value="{{ $themeSettings['--rami-group-1-color'] ?? '#1e3a5f' }}">
                                    <input type="text" class="color-hex"
                                        value="{{ $themeSettings['--rami-group-1-color'] ?? '#1e3a5f' }}" readonly>
                                </div>
                            </div>
                            <div class="color-input-group">
                                <label>Groupe 2</label>
                                <div class="color-input-wrapper">
                                    <input type="color" data-css-var="--rami-group-2-color"
                                        value="{{ $themeSettings['--rami-group-2-color'] ?? '#2d5a3d' }}">
                                    <input type="text" class="color-hex"
                                        value="{{ $themeSettings['--rami-group-2-color'] ?? '#2d5a3d' }}" readonly>
                                </div>
                            </div>
                            <div class="color-input-group">
                                <label>Groupe 3</label>
                                <div class="color-input-wrapper">
                                    <input type="color" data-css-var="--rami-group-3-color"
                                        value="{{ $themeSettings['--rami-group-3-color'] ?? '#5a2d5a' }}">
                                    <input type="text" class="color-hex"
                                        value="{{ $themeSettings['--rami-group-3-color'] ?? '#5a2d5a' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="control-section">
                        <h3>Ombres</h3>
                        <input type="hidden" id="rami_card_shadow" data-css-var="--rami-card-shadow"
                            value="{{ $themeSettings['--rami-card-shadow'] ?? ($ramiShadowPresets['default']['card'] ?? '') }}">
                        <input type="hidden" id="rami_card_shadow_hover" data-css-var="--rami-card-shadow-hover"
                            value="{{ $themeSettings['--rami-card-shadow-hover'] ?? ($ramiShadowPresets['default']['cardHover'] ?? '') }}">

                        <div class="shadow-selector">
                            <label>Preset</label>
                            <div class="shadow-options">
                                <button type="button" class="shadow-option" data-rami-shadow-preset="soft">
                                    <div class="shadow-demo shadow-soft"></div>
                                    <span>Douce</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-shadow-preset="default">
                                    <div class="shadow-demo shadow-default"></div>
                                    <span>Normale</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-shadow-preset="strong">
                                    <div class="shadow-demo shadow-strong"></div>
                                    <span>Forte</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-shadow-preset="flat">
                                    <div class="shadow-demo shadow-flat"></div>
                                    <span>Rétro</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-shadow-preset="inner">
                                    <div class="shadow-demo shadow-inner"></div>
                                    <span>Interne</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="control-section">
                        <h3>Texture</h3>
                        <div class="slider-group">
                            <label>Opacité papier</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="0.1" step="0.005"
                                    value="{{ $themeSettings['--rami-noise-opacity'] ?? '0.03' }}"
                                    data-css-var="--rami-noise-opacity" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-noise-opacity'] ?? '0.03' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-section">
                        <h3>Motif</h3>
                        <div class="shadow-selector">
                            <label>Arrière-plan</label>
                            <input type="hidden" data-css-var="--rami-selected-pattern"
                                value="{{ $themeSettings['--rami-selected-pattern'] ?? 'plain' }}">
                            <div class="shadow-options">
                                <button type="button" class="shadow-option" data-rami-bg-preset="plain">
                                    <div class="bg-demo bg-plain"></div>
                                    <span>Uni</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="diamonds">
                                    <div class="bg-demo bg-diamonds"></div>
                                    <span>Losanges</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="geometry">
                                    <div class="bg-demo bg-geometry"></div>
                                    <span>Géométrie</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="mixed">
                                    <div class="bg-demo bg-mixed"></div>
                                    <span>Mixte</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="checker">
                                    <div class="bg-demo bg-checker"></div>
                                    <span>Carreaux</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="stripes">
                                    <div class="bg-demo bg-stripes"></div>
                                    <span>Rayures</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="dots">
                                    <div class="bg-demo bg-dots"></div>
                                    <span>Points</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="hexagons">
                                    <div class="bg-demo bg-hexagons"></div>
                                    <span>Hexagones</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="triangles">
                                    <div class="bg-demo bg-triangles"></div>
                                    <span>Triangles</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="chevron">
                                    <div class="bg-demo bg-chevron"></div>
                                    <span>Chevron</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="waves">
                                    <div class="bg-demo bg-waves"></div>
                                    <span>Vagues</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="scales">
                                    <div class="bg-demo bg-scales"></div>
                                    <span>Écailles</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="halfcircles">
                                    <div class="bg-demo bg-halfcircles"></div>
                                    <span>Demi-cercles</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="grid">
                                    <div class="bg-demo bg-grid"></div>
                                    <span>Grille</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="graph">
                                    <div class="bg-demo bg-graph"></div>
                                    <span>Millimétré</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="zigzag">
                                    <div class="bg-demo bg-zigzag"></div>
                                    <span>Zigzag</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="crosses">
                                    <div class="bg-demo bg-crosses"></div>
                                    <span>Croix</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="crosshatch">
                                    <div class="bg-demo bg-crosshatch"></div>
                                    <span>Hachures</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="bricks">
                                    <div class="bg-demo bg-bricks"></div>
                                    <span>Briques</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="stars">
                                    <div class="bg-demo bg-stars"></div>
                                    <span>Étoiles</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="mosaic">
                                    <div class="bg-demo bg-mosaic"></div>
                                    <span>Mosaïque</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="circles">
                                    <div class="bg-demo bg-circles"></div>
                                    <span>Cercles</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="minimal">
                                    <div class="bg-demo bg-minimal"></div>
                                    <span>Minimal</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="baroque">
                                    <div class="bg-demo bg-baroque"></div>
                                    <span>Baroque</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="ornate">
                                    <div class="bg-demo bg-ornate"></div>
                                    <span>Orné</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="weave">
                                    <div class="bg-demo bg-weave"></div>
                                    <span>Tressage</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="artdeco">
                                    <div class="bg-demo bg-artdeco"></div>
                                    <span>Art déco</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="herringbone">
                                    <div class="bg-demo bg-herringbone"></div>
                                    <span>Parquet</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="lace">
                                    <div class="bg-demo bg-lace"></div>
                                    <span>Dentelle</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="origami">
                                    <div class="bg-demo bg-origami"></div>
                                    <span>Origami</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="circuit">
                                    <div class="bg-demo bg-circuit"></div>
                                    <span>Circuit</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="constellation">
                                    <div class="bg-demo bg-constellation"></div>
                                    <span>Constellation</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="stainedglass">
                                    <div class="bg-demo bg-stainedglass"></div>
                                    <span>Vitrail</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="foliage">
                                    <div class="bg-demo bg-foliage"></div>
                                    <span>Feuillage</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="arabesque">
                                    <div class="bg-demo bg-arabesque"></div>
                                    <span>Arabesque</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="damask">
                                    <div class="bg-demo bg-damask"></div>
                                    <span>Damas</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="paisley">
                                    <div class="bg-demo bg-paisley"></div>
                                    <span>Paisley</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="celtic">
                                    <div class="bg-demo bg-celtic"></div>
                                    <span>Celtique</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="japanese-wave">
                                    <div class="bg-demo bg-japanese-wave"></div>
                                    <span>Vagues japonaises</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="moroccan">
                                    <div class="bg-demo bg-moroccan"></div>
                                    <span>Zellige</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="artnouveau">
                                    <div class="bg-demo bg-artnouveau"></div>
                                    <span>Art nouveau</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="nordic">
                                    <div class="bg-demo bg-nordic"></div>
                                    <span>Nordique</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="quilted">
                                    <div class="bg-demo bg-quilted"></div>
                                    <span>Matelassé</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="byzantine">
                                    <div class="bg-demo bg-byzantine"></div>
                                    <span>Byzantin</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="bamboo">
                                    <div class="bg-demo bg-bamboo"></div>
                                    <span>Bambou</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="feather">
                                    <div class="bg-demo bg-feather"></div>
                                    <span>Plumes</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="crystalline">
                                    <div class="bg-demo bg-crystalline"></div>
                                    <span>Cristal</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="terrazzo">
                                    <div class="bg-demo bg-terrazzo"></div>
                                    <span>Terrazzo</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="labyrinth">
                                    <div class="bg-demo bg-labyrinth"></div>
                                    <span>Labyrinthe</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="tribal">
                                    <div class="bg-demo bg-tribal"></div>
                                    <span>Tribal</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="nautical">
                                    <div class="bg-demo bg-nautical"></div>
                                    <span>Nautique</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="mandala">
                                    <div class="bg-demo bg-mandala"></div>
                                    <span>Mandala</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="topographic">
                                    <div class="bg-demo bg-topographic"></div>
                                    <span>Topographique</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="starmap">
                                    <div class="bg-demo bg-starmap"></div>
                                    <span>Carte céleste</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="renaissance">
                                    <div class="bg-demo bg-renaissance"></div>
                                    <span>Renaissance</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="diagonal-checker">
                                    <div class="bg-demo bg-diagonal-checker"></div>
                                    <span>Damier diagonal</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="ticks">
                                    <div class="bg-demo bg-ticks"></div>
                                    <span>Repères</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="fleurdelys">
                                    <div class="bg-demo bg-fleurdelys"></div>
                                    <span>Fleur de lys</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="ikat">
                                    <div class="bg-demo bg-ikat"></div>
                                    <span>Ikat</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="marble">
                                    <div class="bg-demo bg-marble"></div>
                                    <span>Marbre</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="houndstooth">
                                    <div class="bg-demo bg-houndstooth"></div>
                                    <span>Pied-de-poule</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="toiledejouy">
                                    <div class="bg-demo bg-toiledejouy"></div>
                                    <span>Toile de Jouy</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="seigaiha">
                                    <div class="bg-demo bg-seigaiha"></div>
                                    <span>Seigaiha</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="broderie">
                                    <div class="bg-demo bg-broderie"></div>
                                    <span>Broderie</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="guilloche">
                                    <div class="bg-demo bg-guilloche"></div>
                                    <span>Guilloché</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="tapisserie">
                                    <div class="bg-demo bg-tapisserie"></div>
                                    <span>Tapisserie</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="liberty">
                                    <div class="bg-demo bg-liberty"></div>
                                    <span>Liberty</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="chevronfr">
                                    <div class="bg-demo bg-chevronfr"></div>
                                    <span>Chevron français</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="pointhongrie">
                                    <div class="bg-demo bg-pointhongrie"></div>
                                    <span>Point de Hongrie</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="versailles">
                                    <div class="bg-demo bg-versailles"></div>
                                    <span>Versailles</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="brocart">
                                    <div class="bg-demo bg-brocart"></div>
                                    <span>Brocart</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="mosaicfr">
                                    <div class="bg-demo bg-mosaicfr"></div>
                                    <span>Mosaïque française</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="ecailles">
                                    <div class="bg-demo bg-ecailles"></div>
                                    <span>Écailles (FR)</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-bg-preset="coquille">
                                    <div class="bg-demo bg-coquille"></div>
                                    <span>Coquilles</span>
                                </button>
                            </div>
                        </div>

                        <div class="shadow-selector">
                            <label>Dos de carte</label>
                            <input type="hidden" data-css-var="--rami-card-back-pattern"
                                value="{{ $themeSettings['--rami-card-back-pattern'] ?? 'diamonds' }}">
                            <div class="shadow-options">
                                <button type="button" class="shadow-option shadow-option--premium"
                                    data-rami-card-back-preset="premium">
                                    <div class="bg-demo bg-premium"></div>
                                    <span>Premium ★</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="royal">
                                    <div class="bg-demo bg-royal"></div>
                                    <span>Royal</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="classic-red">
                                    <div class="bg-demo bg-classic-red"></div>
                                    <span>Classique <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span></span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="classic-blue">
                                    <div class="bg-demo bg-classic-blue"></div>
                                    <span>Classique ♠</span>
                                </button>
                                <button type="button" class="shadow-option shadow-option--premium"
                                    data-rami-card-back-preset="classic-gold">
                                    <div class="bg-demo bg-classic-gold"></div>
                                    <span>Classique Or ✦</span>
                                </button>
                                <button type="button" class="shadow-option"
                                    data-rami-card-back-preset="classic-emerald">
                                    <div class="bg-demo bg-classic-emerald"></div>
                                    <span>Classique Émeraude ♣</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="filigrane-red">
                                    <div class="bg-demo bg-filigrane-red"></div>
                                    <span>Filigrane <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span></span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="filigrane-blue">
                                    <div class="bg-demo bg-filigrane-blue"></div>
                                    <span>Filigrane ♠</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="ecusson-red">
                                    <div class="bg-demo bg-ecusson-red"></div>
                                    <span>Écusson <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span></span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="ecusson-blue">
                                    <div class="bg-demo bg-ecusson-blue"></div>
                                    <span>Écusson ♠</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="eventail-red">
                                    <div class="bg-demo bg-eventail-red"></div>
                                    <span>Éventail <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span></span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="eventail-blue">
                                    <div class="bg-demo bg-eventail-blue"></div>
                                    <span>Éventail ♠</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="plain">
                                    <div class="bg-demo bg-plain"></div>
                                    <span>Uni</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="diamonds">
                                    <div class="bg-demo bg-diamonds"></div>
                                    <span>Losanges</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="geometry">
                                    <div class="bg-demo bg-geometry"></div>
                                    <span>Géométrie</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="mixed">
                                    <div class="bg-demo bg-mixed"></div>
                                    <span>Mixte</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="checker">
                                    <div class="bg-demo bg-checker"></div>
                                    <span>Carreaux</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="stripes">
                                    <div class="bg-demo bg-stripes"></div>
                                    <span>Rayures</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="dots">
                                    <div class="bg-demo bg-dots"></div>
                                    <span>Points</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="hexagons">
                                    <div class="bg-demo bg-hexagons"></div>
                                    <span>Hexagones</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="triangles">
                                    <div class="bg-demo bg-triangles"></div>
                                    <span>Triangles</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="chevron">
                                    <div class="bg-demo bg-chevron"></div>
                                    <span>Chevron</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="waves">
                                    <div class="bg-demo bg-waves"></div>
                                    <span>Vagues</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="scales">
                                    <div class="bg-demo bg-scales"></div>
                                    <span>Écailles</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="halfcircles">
                                    <div class="bg-demo bg-halfcircles"></div>
                                    <span>Demi-cercles</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="grid">
                                    <div class="bg-demo bg-grid"></div>
                                    <span>Grille</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="graph">
                                    <div class="bg-demo bg-graph"></div>
                                    <span>Millimétré</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="zigzag">
                                    <div class="bg-demo bg-zigzag"></div>
                                    <span>Zigzag</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="crosses">
                                    <div class="bg-demo bg-crosses"></div>
                                    <span>Croix</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="crosshatch">
                                    <div class="bg-demo bg-crosshatch"></div>
                                    <span>Hachures</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="bricks">
                                    <div class="bg-demo bg-bricks"></div>
                                    <span>Briques</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="stars">
                                    <div class="bg-demo bg-stars"></div>
                                    <span>Étoiles</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="mosaic">
                                    <div class="bg-demo bg-mosaic"></div>
                                    <span>Mosaïque</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="circles">
                                    <div class="bg-demo bg-circles"></div>
                                    <span>Cercles</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="minimal">
                                    <div class="bg-demo bg-minimal"></div>
                                    <span>Minimal</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="baroque">
                                    <div class="bg-demo bg-baroque"></div>
                                    <span>Baroque</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="ornate">
                                    <div class="bg-demo bg-ornate"></div>
                                    <span>Orné</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="weave">
                                    <div class="bg-demo bg-weave"></div>
                                    <span>Tressage</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="artdeco">
                                    <div class="bg-demo bg-artdeco"></div>
                                    <span>Art déco</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="herringbone">
                                    <div class="bg-demo bg-herringbone"></div>
                                    <span>Parquet</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="lace">
                                    <div class="bg-demo bg-lace"></div>
                                    <span>Dentelle</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="origami">
                                    <div class="bg-demo bg-origami"></div>
                                    <span>Origami</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="circuit">
                                    <div class="bg-demo bg-circuit"></div>
                                    <span>Circuit</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="constellation">
                                    <div class="bg-demo bg-constellation"></div>
                                    <span>Constellation</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="stainedglass">
                                    <div class="bg-demo bg-stainedglass"></div>
                                    <span>Vitrail</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="foliage">
                                    <div class="bg-demo bg-foliage"></div>
                                    <span>Feuillage</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="arabesque">
                                    <div class="bg-demo bg-arabesque"></div>
                                    <span>Arabesques</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="damask">
                                    <div class="bg-demo bg-damask"></div>
                                    <span>Damas</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="toiledejouy">
                                    <div class="bg-demo bg-toiledejouy"></div>
                                    <span>Toile de Jouy</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="tartanfr">
                                    <div class="bg-demo bg-tartanfr"></div>
                                    <span>Tartan</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="smock">
                                    <div class="bg-demo bg-smock"></div>
                                    <span>Smocks</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="hatchfr">
                                    <div class="bg-demo bg-hatchfr"></div>
                                    <span>Hachures fines</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="graphpaper">
                                    <div class="bg-demo bg-graphpaper"></div>
                                    <span>Papier quadrillé</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="rose">
                                    <div class="bg-demo bg-rose"></div>
                                    <span>Rosace</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="fleurdelis">
                                    <div class="bg-demo bg-fleurdelis"></div>
                                    <span>Fleur-de-lis</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="chevronfr">
                                    <div class="bg-demo bg-chevronfr"></div>
                                    <span>Chevron français</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="pointhongrie">
                                    <div class="bg-demo bg-pointhongrie"></div>
                                    <span>Point de Hongrie</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="versailles">
                                    <div class="bg-demo bg-versailles"></div>
                                    <span>Versailles</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="brocart">
                                    <div class="bg-demo bg-brocart"></div>
                                    <span>Brocart</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="mosaicfr">
                                    <div class="bg-demo bg-mosaicfr"></div>
                                    <span>Mosaïque française</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="briare">
                                    <div class="bg-demo bg-briare"></div>
                                    <span>Briare</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="artdeco-paris">
                                    <div class="bg-demo bg-artdeco-paris"></div>
                                    <span>Art déco Paris</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="provence">
                                    <div class="bg-demo bg-provence"></div>
                                    <span>Provence</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="cloisonne">
                                    <div class="bg-demo bg-cloisonne"></div>
                                    <span>Cloisonné</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="parametric">
                                    <div class="bg-demo bg-parametric"></div>
                                    <span>Paramétrique</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="ecailles">
                                    <div class="bg-demo bg-ecailles"></div>
                                    <span>Écailles (FR)</span>
                                </button>
                                <button type="button" class="shadow-option" data-rami-card-back-preset="coquille">
                                    <div class="bg-demo bg-coquille"></div>
                                    <span>Coquilles</span>
                                </button>
                                <button type="button" class="shadow-option"
                                    data-rami-card-back-preset="mandala-noir-or">
                                    <div class="bg-demo bg-mandala-noir-or"></div>
                                    <span>Mandala noir or</span>
                                </button>
                                <button type="button" class="shadow-option"
                                    data-rami-card-back-preset="mandala-nuit-or">
                                    <div class="bg-demo bg-mandala-nuit-or"></div>
                                    <span>Mandala nuit or</span>
                                </button>
                                <button type="button" class="shadow-option"
                                    data-rami-card-back-preset="mandala-bordeaux-or">
                                    <div class="bg-demo bg-mandala-bordeaux-or"></div>
                                    <span>Mandala bordeaux or</span>
                                </button>
                                <button type="button" class="shadow-option"
                                    data-rami-card-back-preset="mandala-noir-coeurs-or">
                                    <div class="bg-demo bg-mandala-noir-coeurs-or"></div>
                                    <span>Mandala noir cœurs</span>
                                </button>
                            </div>
                        </div>

                        <div class="color-grid">
                            <div class="color-input-group color-input-group--full">
                                <label>Couleur motif (dos)</label>
                                <div class="color-input-wrapper">
                                    <input type="text" class="color-hex" data-css-var="--rami-card-back-pattern-color"
                                        value="{{ $themeSettings['--rami-card-back-pattern-color'] ?? 'rgba(30, 58, 95, 0.03)' }}">
                                </div>
                            </div>
                        </div>

                        <div class="color-grid">
                            <div class="color-input-group color-input-group--full">
                                <label>Couleur motif (losanges)</label>
                                <div class="color-input-wrapper">
                                    <input type="text" class="color-hex" data-css-var="--rami-pattern-color"
                                        value="{{ $themeSettings['--rami-pattern-color'] ?? 'rgba(30, 58, 95, 0.03)' }}">
                                </div>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Marge motif (petite carte)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="24"
                                    value="{{ (int) ($themeSettings['--rami-pattern-inset'] ?? '8') }}"
                                    data-css-var="--rami-pattern-inset" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-pattern-inset'] ?? '8px' }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Marge motif (carte détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="36"
                                    value="{{ (int) ($themeSettings['--rami-pattern-inset-large'] ?? '12') }}"
                                    data-css-var="--rami-pattern-inset-large" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-pattern-inset-large'] ?? '12px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Force cercles (0–30%)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="30"
                                    value="{{ (float) str_replace('%', '', ($themeSettings['--rami-bg-circles-strength'] ?? '12%')) }}"
                                    data-css-var="--rami-bg-circles-strength" data-unit="%" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-bg-circles-strength'] ?? '12%'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Force rectangles (0–30%)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="30"
                                    value="{{ (float) str_replace('%', '', ($themeSettings['--rami-bg-rectangles-strength'] ?? '9%')) }}"
                                    data-css-var="--rami-bg-rectangles-strength" data-unit="%" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-bg-rectangles-strength'] ?? '9%'
                                    }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-section">
                        <h3>Interaction</h3>
                        <div class="slider-group">
                            <label>Lift au survol</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="16"
                                    value="{{ (int) ($themeSettings['--rami-card-hover-lift'] ?? '8') }}"
                                    data-css-var="--rami-card-hover-lift" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-card-hover-lift'] ?? '8px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Tilt au survol</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="12" step="0.5"
                                    value="{{ (float) str_replace('deg', '', ($themeSettings['--rami-card-hover-tilt'] ?? '5deg')) }}"
                                    data-css-var="--rami-card-hover-tilt" data-unit="deg" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-card-hover-tilt'] ?? '5deg'
                                    }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-section">
                        <h3>Typographie</h3>
                        <div class="slider-group">
                            <label>Police</label>
                            <div class="color-input-wrapper">
                                <select class="color-hex select-full" data-css-var="--rami-font-family">
                                    <option value="'Inter', sans-serif" @selected(($themeSettings['--rami-font-family']
                                        ?? '' )==="'Inter', sans-serif" )>Inter (Moderne)</option>
                                    <option value="'Outfit', sans-serif" @selected(($themeSettings['--rami-font-family']
                                        ?? '' )==="'Outfit', sans-serif" )>Outfit (Friendly)</option>
                                    <option value="'Space Grotesk', sans-serif"
                                        @selected(($themeSettings['--rami-font-family'] ?? ''
                                        )==="'Space Grotesk', sans-serif" )>Space Grotesk (Tech)</option>
                                    <option value="'JetBrains Mono', monospace"
                                        @selected(($themeSettings['--rami-font-family'] ?? ''
                                        )==="'JetBrains Mono', monospace" )>JetBrains Mono (Mono)</option>
                                    <option value="'Patrick Hand', cursive"
                                        @selected(($themeSettings['--rami-font-family'] ?? ''
                                        )==="'Patrick Hand', cursive" )>Patrick Hand (Scolaire)</option>
                                    <option value="'Lora', serif" @selected(($themeSettings['--rami-font-family'] ?? ''
                                        )==="'Lora', serif" )>Lora (Élégant)</option>
                                </select>
                            </div>
                        </div>
                        <div class="slider-group">
                            <label>Choix rapide</label>
                            <div class="font-options" id="rami-font-options">
                                <button type="button" class="font-option" data-font-value="'Inter', sans-serif"
                                    style="font-family: 'Inter', sans-serif;">Inter</button>
                                <button type="button" class="font-option" data-font-value="'Outfit', sans-serif"
                                    style="font-family: 'Outfit', sans-serif;">Outfit</button>
                                <button type="button" class="font-option" data-font-value="'Space Grotesk', sans-serif"
                                    style="font-family: 'Space Grotesk', sans-serif;">Space</button>
                                <button type="button" class="font-option" data-font-value="'JetBrains Mono', monospace"
                                    style="font-family: 'JetBrains Mono', monospace;">Mono</button>
                                <button type="button" class="font-option" data-font-value="'Patrick Hand', cursive"
                                    style="font-family: 'Patrick Hand', cursive;">Patrick</button>
                                <button type="button" class="font-option" data-font-value="'Lora', serif"
                                    style="font-family: 'Lora', serif;">Lora</button>
                            </div>
                        </div>
                        <div class="slider-group">
                            <label>Styles de bouton</label>
                            <div class="button-style-options">
                                <button type="button" class="button-style-demo btn-filled">Rempli</button>
                                <button type="button" class="button-style-demo btn-outlined">Contour</button>
                                <button type="button" class="button-style-demo btn-ghost">Ghost</button>
                            </div>
                        </div>
                        <div class="color-grid">
                            <div class="color-input-group color-input-group--full">
                                <label>Texte secondaire</label>
                                <div class="color-input-wrapper">
                                    <input type="text" class="color-hex" data-css-var="--rami-text-muted-color"
                                        value="{{ $themeSettings['--rami-text-muted-color'] ?? '#7a7a7a' }}">
                                </div>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Padding index (petit)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="6" max="20"
                                    value="{{ (int) ($themeSettings['--rami-index-padding'] ?? '12') }}"
                                    data-css-var="--rami-index-padding" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-index-padding'] ?? '12px' }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Taille pronom index (petit)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="18" max="44"
                                    value="{{ (int) ($themeSettings['--rami-index-pronoun-size'] ?? '28') }}"
                                    data-css-var="--rami-index-pronoun-size" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-index-pronoun-size'] ?? '28px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Taille verbe index (petit)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="8" max="26"
                                    value="{{ (int) ($themeSettings['--rami-index-verb-size'] ?? '10') }}"
                                    data-css-var="--rami-index-verb-size" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-index-verb-size'] ?? '10px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Taille verbe (petit)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="16" max="36"
                                    value="{{ (int) ($themeSettings['--rami-verb-size'] ?? '22') }}"
                                    data-css-var="--rami-verb-size" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-verb-size'] ?? '22px' }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Espacement lettres verbe</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="0.12" step="0.005"
                                    value="{{ (float) str_replace('em', '', ($themeSettings['--rami-verb-letter-spacing'] ?? '0.02em')) }}"
                                    data-css-var="--rami-verb-letter-spacing" data-unit="em" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-verb-letter-spacing'] ?? '0.02em'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Position verbe (petit)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="20" max="90"
                                    value="{{ (int) ($themeSettings['--rami-verb-bottom'] ?? '45') }}"
                                    data-css-var="--rami-verb-bottom" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-verb-bottom'] ?? '45px' }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Padding index (détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="10" max="28"
                                    value="{{ (int) ($themeSettings['--rami-index-padding-large'] ?? '16') }}"
                                    data-css-var="--rami-index-padding-large" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-index-padding-large'] ?? '16px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Taille pronom index (détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="28" max="60"
                                    value="{{ (int) ($themeSettings['--rami-index-pronoun-size-large'] ?? '42') }}"
                                    data-css-var="--rami-index-pronoun-size-large" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-index-pronoun-size-large'] ??
                                    '42px' }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Taille verbe index (détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="10" max="18"
                                    value="{{ (int) ($themeSettings['--rami-index-verb-size-large'] ?? '12') }}"
                                    data-css-var="--rami-index-verb-size-large" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-index-verb-size-large'] ?? '12px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Taille verbe (détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="22" max="56"
                                    value="{{ (int) ($themeSettings['--rami-verb-size-large'] ?? '32') }}"
                                    data-css-var="--rami-verb-size-large" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-verb-size-large'] ?? '32px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Position verbe (détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="40" max="120"
                                    value="{{ (int) ($themeSettings['--rami-verb-bottom-large'] ?? '60') }}"
                                    data-css-var="--rami-verb-bottom-large" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-verb-bottom-large'] ?? '60px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Espacement lettres infinitif (détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0.05" max="0.4" step="0.01"
                                    value="{{ (float) str_replace('em', '', ($themeSettings['--rami-infinitive-letter-spacing'] ?? '0.2em')) }}"
                                    data-css-var="--rami-infinitive-letter-spacing" data-unit="em" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-infinitive-letter-spacing'] ??
                                    '0.2em' }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Taille symbole (♠<span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span>♣♥)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0.9" max="1.9" step="0.05"
                                    value="{{ (float) str_replace('em', '', ($themeSettings['--rami-suit-size'] ?? '1.35em')) }}"
                                    data-css-var="--rami-suit-size" data-unit="em" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-suit-size'] ?? '1.35em' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-section">
                        <h3>Badge</h3>
                        <div class="color-grid">
                            <div class="color-input-group">
                                <label>Fond badge</label>
                                <div class="color-input-wrapper">
                                    <input type="text" class="color-hex" data-css-var="--rami-badge-bg-color"
                                        value="{{ $themeSettings['--rami-badge-bg-color'] ?? 'var(--rami-accent-color)' }}">
                                </div>
                            </div>
                            <div class="color-input-group">
                                <label>Texte badge</label>
                                <div class="color-input-wrapper">
                                    <input type="color" data-css-var="--rami-badge-text-color"
                                        value="{{ $themeSettings['--rami-badge-text-color'] ?? '#ffffff' }}">
                                    <input type="text" class="color-hex"
                                        value="{{ $themeSettings['--rami-badge-text-color'] ?? '#ffffff' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="slider-group">
                            <label>Taille texte (petit)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="6" max="14"
                                    value="{{ (int) ($themeSettings['--rami-badge-font-size'] ?? '8') }}"
                                    data-css-var="--rami-badge-font-size" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-badge-font-size'] ?? '8px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Padding X (petit)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="4" max="18"
                                    value="{{ (int) ($themeSettings['--rami-badge-padding-x'] ?? '8') }}"
                                    data-css-var="--rami-badge-padding-x" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-badge-padding-x'] ?? '8px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Padding Y (petit)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="1" max="10"
                                    value="{{ (int) ($themeSettings['--rami-badge-padding-y'] ?? '3') }}"
                                    data-css-var="--rami-badge-padding-y" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-badge-padding-y'] ?? '3px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Rayon (petit)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="20"
                                    value="{{ (int) ($themeSettings['--rami-badge-radius'] ?? '10') }}"
                                    data-css-var="--rami-badge-radius" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-badge-radius'] ?? '10px' }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Taille texte (détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="8" max="18"
                                    value="{{ (int) ($themeSettings['--rami-badge-font-size-large'] ?? '10') }}"
                                    data-css-var="--rami-badge-font-size-large" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-badge-font-size-large'] ?? '10px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Padding X (détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="6" max="22"
                                    value="{{ (int) ($themeSettings['--rami-badge-padding-x-large'] ?? '10') }}"
                                    data-css-var="--rami-badge-padding-x-large" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-badge-padding-x-large'] ?? '10px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Padding Y (détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="2" max="14"
                                    value="{{ (int) ($themeSettings['--rami-badge-padding-y-large'] ?? '4') }}"
                                    data-css-var="--rami-badge-padding-y-large" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-badge-padding-y-large'] ?? '4px'
                                    }}</span>
                            </div>
                        </div>

                        <div class="slider-group">
                            <label>Rayon (détail)</label>
                            <div class="slider-wrapper">
                                <input type="range" min="0" max="26"
                                    value="{{ (int) ($themeSettings['--rami-badge-radius-large'] ?? '12') }}"
                                    data-css-var="--rami-badge-radius-large" data-unit="px" class="slider">
                                <span class="slider-value">{{ $themeSettings['--rami-badge-radius-large'] ?? '12px'
                                    }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div><!-- Fin de .customizer-controls -->

        <!-- Panel de droite - Aperçu en temps réel -->
        <div class="customizer-preview">
            <div class="preview-sticky">
                <div class="preview-header">
                    <h3>Aperçu en direct</h3>
                    <div class="preview-actions">
                        <div class="preview-mode-toggle" title="Taille de l'aperçu">
                            <button type="button" class="preview-mode-btn active" data-preview-width="100%">
                                <i class="ph ph-monitor"></i>
                            </button>
                            <button type="button" class="preview-mode-btn" data-preview-width="768px">
                                <i class="ph ph-device-tablet"></i>
                            </button>
                            <button type="button" class="preview-mode-btn" data-preview-width="375px">
                                <i class="ph ph-device-mobile"></i>
                            </button>
                        </div>

                        <button type="button" class="btn-icon-mini" id="preview-theme-toggle"
                            title="Mode Sombre/Clair (Aperçu)">
                            <i class="ph ph-moon"></i>
                        </button>

                        <div class="preview-mode-toggle">
                            <button type="button" class="preview-mode-btn active"
                                data-preview-mode="card">Carte</button>
                            <button type="button" class="preview-mode-btn" data-preview-mode="all">Tous</button>
                            <button type="button" class="preview-mode-btn" data-preview-mode="large">Détail</button>
                            <button type="button" class="preview-mode-btn" data-preview-mode="back">Dos</button>
                        </div>
                        <button type="button" class="btn-icon-mini" id="preview-fullscreen" title="Plein écran">
                            <i class="ph ph-arrows-out"></i>
                        </button>
                        @if(in_array(($themeUiVersion ?? 1), [2, 5, 6, 7]))
                        <button type="button" class="btn-icon-mini" id="preview-print" title="Imprimer la carte">
                            <i class="ph ph-printer"></i>
                        </button>
                        @endif
                    </div>
                </div>
                <div class="preview-legend">
                    Couleurs : ♠ Pique · <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span> Carreaux · ♣ Trèfle · ♥ Cœur
                </div>

                <div class="preview-container preview-container--single">
                    <div class="preview-mode active" data-preview="card">
                        <div class="preview-cards-grid preview-single-card">
                            <!-- Carte unique : MANGER (1er) -->
                            <article class="rami-card" data-group="1er">
                                <div class="rami-card-index-top">
                                    <div class="rami-card-index-pronoun"><span
                                            class="rami-card-suit">♠</span><span>JE</span></div>
                                    <div class="rami-card-index-verb">mange</div>
                                </div>
                                <span class="rami-card-badge"><span
                                        class="rami-card-suit">♠</span><span>1er</span></span>
                                @if(in_array(($themeUiVersion ?? 1), [2, 5, 6, 7]))
                                <span class="rami-card-badge rami-card-badge-bottom" aria-hidden="true"><span
                                        class="rami-card-suit">♠</span><span>1er</span></span>
                                @endif
                                <div class="rami-card-center">
                                    <div class="rami-card-illustration">
                                    </div>
                                </div>
                                <div class="rami-card-verb">
                                    @if(in_array(($themeUiVersion ?? 1), [3, 4, 5, 6, 7], true))
                                    <div class="rami-card-verb-text rami-card-verb-text-v3">
                                        <div class="rami-card-verb-main-row">
                                            <span class="rami-card-verb-main">MANGE</span>
                                        </div>
                                        <div class="rami-card-verb-sub">MANGER</div>
                                    </div>
                                    @else
                                    <div class="rami-card-verb-text">MANGER</div>
                                    @endif
                                </div>
                                @if(in_array(($themeUiVersion ?? 1), [2, 3, 5, 6, 7], true))
                                <div class="rami-card-verb rami-card-verb-bottom rami-rotate-180" aria-hidden="true">
                                    @if(in_array(($themeUiVersion ?? 1), [3, 4, 5, 6, 7], true))
                                    <div class="rami-card-verb-text rami-card-verb-text-v3">
                                        <div class="rami-card-verb-main-row">
                                            <span class="rami-card-verb-main">MANGE</span>
                                        </div>
                                        <div class="rami-card-verb-sub">MANGER</div>
                                    </div>
                                    @else
                                    <div class="rami-card-verb-text">MANGER</div>
                                    @endif
                                </div>
                                @endif
                                <div class="rami-card-index-bottom">
                                    <div class="rami-card-index-pronoun"><span
                                            class="rami-card-suit">♠</span><span>JE</span></div>
                                    <div class="rami-card-index-verb">mange</div>
                                </div>
                            </article>
                        </div>
                    </div>
                    <div class="preview-mode" data-preview="all">
                        @php
                        $previewPronouns = [
                        ['label' => 'JE', 'value' => 'mange'],
                        ['label' => 'TU', 'value' => 'manges'],
                        ['label' => 'IL', 'value' => 'mange'],
                        ['label' => 'NOUS', 'value' => 'mangeons'],
                        ['label' => 'VOUS', 'value' => 'mangez'],
                        ['label' => 'ILS', 'value' => 'mangent'],
                        ];
                        @endphp
                        <div class="preview-cards-grid">
                            @php
                            $previewGroups = ['1er', '2ème', '3ème'];
                            @endphp
                            @foreach($previewPronouns as $previewPronoun)
                            @php
                            $previewGroup = $previewGroups[$loop->index % count($previewGroups)];
                            @endphp
                            <article class="rami-card" data-group="{{ $previewGroup }}">
                                <div class="rami-card-index-top">
                                    <div class="rami-card-index-pronoun">
                                        <span class="rami-card-suit">♠</span>
                                        <span>{{ $previewPronoun['label'] }}</span>
                                    </div>
                                    <div class="rami-card-index-verb">{{ $previewPronoun['value'] }}</div>
                                </div>
                                <span class="rami-card-badge"><span class="rami-card-suit">♠</span><span>{{
                                        $previewGroup }}</span></span>
                                @if(in_array(($themeUiVersion ?? 1), [2, 5, 6, 7]))
                                <span class="rami-card-badge rami-card-badge-bottom" aria-hidden="true"><span
                                        class="rami-card-suit">♠</span><span>{{ $previewGroup }}</span></span>
                                @endif
                                <div class="rami-card-center">
                                    <div class="rami-card-illustration">
                                    </div>
                                </div>
                                <div class="rami-card-verb">
                                    @if(in_array(($themeUiVersion ?? 1), [3, 4, 5, 6, 7], true))
                                    <div class="rami-card-verb-text rami-card-verb-text-v3">
                                        <div class="rami-card-verb-main-row">
                                            <span class="rami-card-verb-main">{{ mb_strtoupper($previewPronoun['value'])
                                                }}</span>
                                        </div>
                                        <div class="rami-card-verb-sub">MANGER</div>
                                    </div>
                                    @else
                                    <div class="rami-card-verb-text">MANGER</div>
                                    @endif
                                </div>
                                @if(in_array(($themeUiVersion ?? 1), [2, 3, 5, 6, 7], true))
                                <div class="rami-card-verb rami-card-verb-bottom rami-rotate-180" aria-hidden="true">
                                    @if(in_array(($themeUiVersion ?? 1), [3, 4, 5, 6, 7], true))
                                    <div class="rami-card-verb-text rami-card-verb-text-v3">
                                        <div class="rami-card-verb-main-row">
                                            <span class="rami-card-verb-main">{{ mb_strtoupper($previewPronoun['value'])
                                                }}</span>
                                        </div>
                                        <div class="rami-card-verb-sub">MANGER</div>
                                    </div>
                                    @else
                                    <div class="rami-card-verb-text">MANGER</div>
                                    @endif
                                </div>
                                @endif
                                <div class="rami-card-index-bottom">
                                    <div class="rami-card-index-pronoun">
                                        <span class="rami-card-suit">♠</span>
                                        <span>{{ $previewPronoun['label'] }}</span>
                                    </div>
                                    <div class="rami-card-index-verb">{{ $previewPronoun['value'] }}</div>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    </div>

                    <div class="preview-mode" data-preview="large">
                        <div class="rami-card-large" data-group="1er">
                            <div class="rami-card-large-content">
                                <div class="rami-large-index-top">
                                    <div class="rami-large-index-pronoun">
                                        <span class="rami-card-suit" aria-hidden="true">♠</span>
                                        <span>JE</span>
                                    </div>
                                    <div class="rami-large-index-verb">mange</div>
                                </div>
                                <div class="rami-large-index-bottom">
                                    <div class="rami-large-index-pronoun">
                                        <span class="rami-card-suit" aria-hidden="true">♠</span>
                                        <span>JE</span>
                                    </div>
                                    <div class="rami-large-index-verb">mange</div>
                                </div>
                                <span class="rami-large-badge">
                                    <span class="rami-card-suit" aria-hidden="true">♠</span>
                                    <span>1er groupe</span>
                                </span>
                                @if(in_array(($themeUiVersion ?? 1), [2, 5, 6, 7]))
                                <span class="rami-large-badge rami-large-badge-bottom" aria-hidden="true">
                                    <span class="rami-card-suit" aria-hidden="true">♠</span>
                                    <span>1er groupe</span>
                                </span>
                                @endif
                                <div class="rami-large-illustration">
                                    <div class="rami-large-illustration-frame">
                                    </div>
                                </div>
                                <div class="rami-large-verb-section">
                                    @if(in_array(($themeUiVersion ?? 1), [3, 4, 5, 6, 7], true))
                                    <div class="rami-large-infinitive">MANGER</div>
                                    <div class="rami-large-verb-conjugated">MANGE</div>
                                    @elseif(in_array(($themeUiVersion ?? 1), [2], true))
                                    <div class="rami-large-infinitive">MANGE</div>
                                    @else
                                    <div class="rami-large-infinitive">MANGER</div>
                                    <div class="rami-large-verb-conjugated">mange</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="preview-mode" data-preview="back">
                        <div class="preview-cards-grid preview-single-card">
                            <article class="rami-card rami-card-back" data-group="1er"
                                data-pattern="{{ $themeSettings['--rami-card-back-pattern'] ?? 'diamonds' }}">
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fin de .customizer-preview -->
    </div><!-- Fin de .customizer-layout -->

    <!-- Actions flottantes -->
    <div class="customizer-footer">
        <button type="button" class="btn btn-secondary" id="theme-reset">
            <i class="ph ph-arrow-counter-clockwise"></i>
            Réinitialiser
        </button>
        <button type="button" class="btn btn-secondary btn-ml-10" id="theme-magic">
            <i class="ph ph-sparkle"></i>
            Magique
        </button>
        <div class="footer-spacer"></div>
        <div class="customizer-save-status" id="customizer-save-status" aria-live="polite">Aucune modification</div>
        <button type="button" class="btn btn-primary" id="theme-save" disabled>
            <i class="ph ph-check"></i>
            Enregistrer les modifications
        </button>
    </div>
</div>

@push('styles')
@php
$adminThemeFontsVersion = file_exists(public_path('css/admin-theme.fonts.css')) ?
filemtime(public_path('css/admin-theme.fonts.css')) : time();
$adminThemeCssVersion = file_exists(public_path('css/admin-theme.css')) ? filemtime(public_path('css/admin-theme.css'))
: time();
$adminThemeDesignCssVersion = file_exists(public_path('css/admin-theme.design-styles.css')) ?
filemtime(public_path('css/admin-theme.design-styles.css')) : time();
@endphp
<link rel="stylesheet" href="{{ asset('css/admin-theme.fonts.css') }}?v={{ $adminThemeFontsVersion }}">
<link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}?v={{ $adminThemeCssVersion }}">
<link rel="stylesheet" href="{{ asset('css/admin-theme.design-styles.css') }}?v={{ $adminThemeDesignCssVersion }}">
@if(in_array(($themeUiVersion ?? 1), [2, 5, 6, 7]))
<style id="theme-settings-inline-v2-preview">
    .theme-customizer--v2 .rami-card,
    .theme-customizer--v2 .rami-card-large,
    .theme-customizer--v2 .print-rami-card {
        @foreach(($themeSettings ?? []) as $key => $value) @if(is_string($key) && is_string($value) && str_starts_with ($key, '--rami-') && preg_match('/[;{}]/', $key.$value) !==1) {
            ! ! $key ! !
        }

        : {
            ! ! $value ! !
        }

        ;
        @endif @endforeach
    }
</style>
@endif
@if(in_array(($themeUiVersion ?? 1), [2, 3, 5, 6, 7], true))
<style>
    .theme-customizer--v2 {
        padding: 0;
        max-width: 1920px;
    }

    .theme-customizer--v2 .customizer-header {
        text-align: left;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        padding: 18px 20px;
        margin-bottom: 18px;
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(91, 155, 213, 0.16), rgba(240, 172, 184, 0.12));
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    [data-theme="dark"] .theme-customizer--v2 .customizer-header {
        background: linear-gradient(135deg, rgba(96, 165, 250, 0.18), rgba(167, 139, 250, 0.14));
        border-color: rgba(255, 255, 255, 0.1);
    }

    .theme-customizer--v2 .customizer-header .section-title {
        margin-bottom: 6px;
    }

    .theme-customizer--v2 .customizer-version-switch {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(0, 0, 0, 0.08);
        backdrop-filter: blur(14px) saturate(140%);
    }

    [data-theme="dark"] .theme-customizer--v2 .customizer-version-switch {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(255, 255, 255, 0.1);
    }

    .theme-customizer--v2 .customizer-version-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        min-width: 48px;
        padding: 0 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.2px;
        text-decoration: none;
        color: var(--color-text-secondary);
        border: 1px solid transparent;
        transition: background 0.2s ease, color 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .theme-customizer--v2 .customizer-version-pill:hover {
        transform: translateY(-1px);
        background: rgba(0, 0, 0, 0.05);
    }

    [data-theme="dark"] .theme-customizer--v2 .customizer-version-pill:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .theme-customizer--v2 .customizer-version-pill.is-active {
        color: var(--color-text-primary);
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(0, 0, 0, 0.08);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
    }

    [data-theme="dark"] .theme-customizer--v2 .customizer-version-pill.is-active {
        background: rgba(255, 255, 255, 0.14);
        border-color: rgba(255, 255, 255, 0.12);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.35);
    }

    .theme-customizer--v2 .customizer-layout {
        grid-template-columns: minmax(460px, 680px) minmax(0, 1fr);
        gap: 28px;
    }

    @media (max-width: 1100px) {
        .theme-customizer--v2 .customizer-layout {
            grid-template-columns: 1fr;
        }
    }

    .theme-customizer--v2 .customizer-controls {
        border-radius: 18px;
    }

    .theme-customizer--v2 .customizer-preview {
        border-radius: 18px;
        overflow: hidden;
    }

    @media (max-width: 700px) {
        .theme-customizer--v2 .customizer-header {
            flex-direction: column;
            align-items: stretch;
            padding: 16px 16px;
        }

        .theme-customizer--v2 .customizer-version-switch {
            align-self: flex-start;
        }
    }
</style>
@endif
@if(($themeUiVersion ?? 1) === 5)
<link rel="stylesheet"
    href="{{ asset('css/admin-theme-v5.css') }}?v={{ file_exists(public_path('css/admin-theme-v5.css')) ? filemtime(public_path('css/admin-theme-v5.css')) : time() }}">
@endif
@if(($themeUiVersion ?? 1) === 6)
<link rel="stylesheet"
    href="{{ asset('css/admin-theme-v6.css') }}?v={{ file_exists(public_path('css/admin-theme-v6.css')) ? filemtime(public_path('css/admin-theme-v6.css')) : time() }}">
@endif
@if(($themeUiVersion ?? 1) === 7)
<link rel="stylesheet"
    href="{{ asset('css/admin-theme-v7.css') }}?v={{ file_exists(public_path('css/admin-theme-v7.css')) ? filemtime(public_path('css/admin-theme-v7.css')) : time() }}">
@endif
@endpush

@if(in_array(($themeUiVersion ?? 1), [5, 6, 7], true))
{{-- V5 Zoom Overlay --}}
<div class="v5-zoom-overlay" id="v5-zoom-overlay" aria-hidden="true">
    <div class="v5-zoom-backdrop" id="v5-zoom-backdrop"></div>
    <div class="v5-zoom-container" id="v5-zoom-container">
        <div class="v5-zoom-card-wrapper" id="v5-zoom-card-wrapper">
            {{-- Card clone is placed here via JS --}}
        </div>
        <div class="v5-zoom-controls">
            <button type="button" class="v5-zoom-btn" id="v5-zoom-out" title="Réduire">
                <i class="ph ph-minus-circle"></i>
            </button>
            <span class="v5-zoom-level" id="v5-zoom-level-display">3×</span>
            <button type="button" class="v5-zoom-btn" id="v5-zoom-in" title="Agrandir">
                <i class="ph ph-plus-circle"></i>
            </button>
            <button type="button" class="v5-zoom-btn v5-zoom-close" id="v5-zoom-close" title="Fermer (Échap)">
                <i class="ph ph-x"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        'use strict';

        // --- V5 Zoom Feature ---
        const overlay = document.getElementById('v5-zoom-overlay');
        const backdrop = document.getElementById('v5-zoom-backdrop');
        const wrapper = document.getElementById('v5-zoom-card-wrapper');
        const zoomDisplay = document.getElementById('v5-zoom-level-display');
        const zoomSlider = document.getElementById('v5-zoom-slider');
        const zoomValue = document.getElementById('v5-zoom-value');
        let currentZoom = parseFloat(zoomSlider?.value || 3);
        let isZoomed = false;

        function openZoom(cardEl) {
            const clone = cardEl.cloneNode(true);
            clone.classList.add('v5-zoomed-card');
            wrapper.innerHTML = '';
            wrapper.appendChild(clone);
            wrapper.style.transform = 'scale(' + currentZoom + ')';
            overlay.classList.add('is-active');
            overlay.setAttribute('aria-hidden', 'false');
            isZoomed = true;
            updateZoomDisplay();
        }

        function closeZoom() {
            overlay.classList.remove('is-active');
            overlay.setAttribute('aria-hidden', 'true');
            isZoomed = false;
            setTimeout(function () { wrapper.innerHTML = ''; }, 350);
        }

        function setZoom(level) {
            currentZoom = Math.max(1, Math.min(5, level));
            wrapper.style.transform = 'scale(' + currentZoom + ')';
            updateZoomDisplay();
            if (zoomSlider) zoomSlider.value = currentZoom;
        }

        function updateZoomDisplay() {
            if (zoomDisplay) zoomDisplay.textContent = currentZoom.toFixed(1) + '×';
            if (zoomValue) zoomValue.textContent = currentZoom.toFixed(1) + '×';
        }

        // Click on card to zoom
        document.querySelectorAll('.theme-customizer--v5 .preview-container .rami-card, .theme-customizer--v5 .preview-container .rami-card-large, .theme-customizer--v6 .preview-container .rami-card, .theme-customizer--v6 .preview-container .rami-card-large, .theme-customizer--v7 .preview-container .rami-card, .theme-customizer--v7 .preview-container .rami-card-large').forEach(function (card) {
            card.style.cursor = 'zoom-in';
            card.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openZoom(this);
            });
        });

        // Close
        if (backdrop) backdrop.addEventListener('click', closeZoom);
        var closeBtn = document.getElementById('v5-zoom-close');
        if (closeBtn) closeBtn.addEventListener('click', closeZoom);

        // Zoom controls
        var zoomInBtn = document.getElementById('v5-zoom-in');
        var zoomOutBtn = document.getElementById('v5-zoom-out');
        if (zoomInBtn) zoomInBtn.addEventListener('click', function () { setZoom(currentZoom + 0.5); });
        if (zoomOutBtn) zoomOutBtn.addEventListener('click', function () { setZoom(currentZoom - 0.5); });

        // Mouse wheel zoom
        if (overlay) overlay.addEventListener('wheel', function (e) {
            if (!isZoomed) return;
            e.preventDefault();
            setZoom(currentZoom + (e.deltaY < 0 ? 0.25 : -0.25));
        }, { passive: false });

        // Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && isZoomed) closeZoom();
        });

        // Zoom slider sync
        if (zoomSlider) zoomSlider.addEventListener('input', function () {
            currentZoom = parseFloat(this.value);
            if (isZoomed) wrapper.style.transform = 'scale(' + currentZoom + ')';
            updateZoomDisplay();
        });

        // --- V5 Writing Style Selector ---
        var writingStyles = {
            classic: { font: "'Lora', serif", spacing: '0.02em', weight: '600' },
            modern: { font: "'Inter', sans-serif", spacing: '0.04em', weight: '700' },
            elegant: { font: "'Outfit', sans-serif", spacing: '0.06em', weight: '500' },
            minimalist: { font: "'Space Grotesk', sans-serif", spacing: '0.08em', weight: '700' },
            handwritten: { font: "'Patrick Hand', cursive", spacing: '0.02em', weight: '400' },
            mono: { font: "'JetBrains Mono', monospace", spacing: '0.01em', weight: '500' },
            compact: { font: "'Inter', sans-serif", spacing: '0em', weight: '700' }
        };

        document.querySelectorAll('.v5-style-option').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var style = this.dataset.v5Writing;
                var config = writingStyles[style];
                if (!config) return;

                // Update hidden input
                var input = document.querySelector('[data-css-var="--rami-v5-writing-style"]');
                if (input) input.value = style;

                // Update font family select
                var fontSelect = document.querySelector('[data-css-var="--rami-font-family"]');
                if (fontSelect) {
                    fontSelect.value = config.font;
                    fontSelect.dispatchEvent(new Event('change', { bubbles: true }));
                }

                // Update letter spacing
                var spacingSlider = document.querySelector('[data-css-var="--rami-verb-letter-spacing"]');
                if (spacingSlider) {
                    spacingSlider.value = parseFloat(config.spacing);
                    spacingSlider.dispatchEvent(new Event('input', { bubbles: true }));
                }

                // Visual active state
                document.querySelectorAll('.v5-style-option').forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');

                // Mark form dirty
                var saveBtn = document.getElementById('theme-save');
                if (saveBtn) saveBtn.disabled = false;
                var status = document.getElementById('customizer-save-status');
                if (status) status.textContent = 'Modifications non enregistrées';
            });
        });

        // Init writing style active state
        (function () {
            var current = document.querySelector('[data-css-var="--rami-v5-writing-style"]');
            if (current) {
                var btn = document.querySelector('[data-v5-writing="' + current.value + '"]');
                if (btn) btn.classList.add('active');
            }
        })();

        // --- V5 Card Shape Selector ---
        var shapePresets = {
            rounded: { radius: '20px', border: '1px', style: 'solid' },
            square: { radius: '4px', border: '2px', style: 'solid' },
            sharp: { radius: '0px', border: '2px', style: 'solid' },
            pill: { radius: '32px', border: '1px', style: 'solid' }
        };

        document.querySelectorAll('.v5-shape-option').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var shape = this.dataset.v5Shape;
                var preset = shapePresets[shape];
                if (!preset) return;

                var input = document.querySelector('[data-css-var="--rami-v5-card-shape"]');
                if (input) input.value = shape;

                // Apply radius to all corners
                ['', '-tl', '-tr', '-br', '-bl'].forEach(function (corner) {
                    var el = document.querySelector('[data-css-var="--rami-card-radius' + corner + '"]');
                    if (el) {
                        el.value = parseInt(preset.radius);
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                });

                // Apply border width
                var borderW = document.querySelector('[data-css-var="--rami-card-border-width"]');
                if (borderW) {
                    borderW.value = parseInt(preset.border);
                    borderW.dispatchEvent(new Event('input', { bubbles: true }));
                }

                // Visual active state
                document.querySelectorAll('.v5-shape-option').forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');

                var saveBtn = document.getElementById('theme-save');
                if (saveBtn) saveBtn.disabled = false;
                var status = document.getElementById('customizer-save-status');
                if (status) status.textContent = 'Modifications non enregistrées';
            });
        });

        // Init shape active state
        (function () {
            var current = document.querySelector('[data-css-var="--rami-v5-card-shape"]');
            if (current) {
                var btn = document.querySelector('[data-v5-shape="' + current.value + '"]');
                if (btn) btn.classList.add('active');
            }
        })();

        // --- V6 Card Glow Selector ---
        document.querySelectorAll('.v6-glow-option').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var glow = this.dataset.v6Glow;
                var input = document.querySelector('[data-css-var="--rami-v6-card-glow"]');
                if (input) input.value = glow;

                document.querySelectorAll('.v6-glow-option').forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');

                var saveBtn = document.getElementById('theme-save');
                if (saveBtn) saveBtn.disabled = false;
                var status = document.getElementById('customizer-save-status');
                if (status) status.textContent = 'Modifications non enregistrées';
            });
        });

        // Init glow active state
        (function () {
            var current = document.querySelector('[data-css-var="--rami-v6-card-glow"]');
            if (current) {
                var btn = document.querySelector('[data-v6-glow="' + current.value + '"]');
                if (btn) btn.classList.add('active');
            }
        })();

        // --- V6 Typography Weight Selector ---
        document.querySelectorAll('.v6-weight-option').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var weight = this.dataset.v6Weight;
                var input = document.querySelector('[data-css-var="--rami-v6-typography-weight"]');
                if (input) input.value = weight;

                document.querySelectorAll('.v6-weight-option').forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');

                var saveBtn = document.getElementById('theme-save');
                if (saveBtn) saveBtn.disabled = false;
                var status = document.getElementById('customizer-save-status');
                if (status) status.textContent = 'Modifications non enregistrées';
            });
        });

        // Init weight active state
        (function () {
            var current = document.querySelector('[data-css-var="--rami-v6-typography-weight"]');
            if (current) {
                var btn = document.querySelector('[data-v6-weight="' + current.value + '"]');
                if (btn) btn.classList.add('active');
            }
        })();

        // --- V7 Ornament Style Selector ---
        document.querySelectorAll('.v7-ornament-option').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var ornament = this.dataset.v7Ornament;
                var input = document.querySelector('[data-css-var="--rami-v7-ornament-style"]');
                if (input) input.value = ornament;

                document.querySelectorAll('.v7-ornament-option').forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');

                var saveBtn = document.getElementById('theme-save');
                if (saveBtn) saveBtn.disabled = false;
                var status = document.getElementById('customizer-save-status');
                if (status) status.textContent = 'Modifications non enregistrées';
            });
        });

        // Init ornament active state
        (function () {
            var current = document.querySelector('[data-css-var="--rami-v7-ornament-style"]');
            if (current) {
                var btn = document.querySelector('[data-v7-ornament="' + current.value + '"]');
                if (btn) btn.classList.add('active');
            }
        })();

        // --- V7 Gold Intensity Selector ---
        document.querySelectorAll('.v7-gold-option').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var gold = this.dataset.v7Gold;
                var input = document.querySelector('[data-css-var="--rami-v7-gold-intensity"]');
                if (input) input.value = gold;

                document.querySelectorAll('.v7-gold-option').forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');

                var saveBtn = document.getElementById('theme-save');
                if (saveBtn) saveBtn.disabled = false;
                var status = document.getElementById('customizer-save-status');
                if (status) status.textContent = 'Modifications non enregistrées';
            });
        });

        // Init gold active state
        (function () {
            var current = document.querySelector('[data-css-var="--rami-v7-gold-intensity"]');
            if (current) {
                var btn = document.querySelector('[data-v7-gold="' + current.value + '"]');
                if (btn) btn.classList.add('active');
            }
        })();

    })();
</script>
@endpush
@endif

@endsection