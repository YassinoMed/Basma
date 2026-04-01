@extends('layouts.app')

@section('title', 'Règles & mini-jeux - FrenchVerbs')

@section('content')
@php
$backPattern = $themeCardBackPattern ?? 'plain';
@endphp

<div class="section-header">
    <div class="section-badge">
        <i class="ph ph-book-open"></i>
        <span>Règles & mini-jeux</span>
    </div>
    <h1 class="section-title">Jouer avec les cartes</h1>
    <p class="section-description">
        Des règles simples pour apprendre le présent de l’indicatif avec un vrai feeling “jeu de cartes”.
    </p>
</div>

<div class="form-card" style="max-width: 980px; margin-left: auto; margin-right: auto;">
    <h2 style="margin: 0 0 10px; font-family: var(--font-display); font-size: 18px; font-weight: 800;">
        Rami conjugaison
    </h2>
    <div style="display: grid; gap: 8px; color: var(--color-text-secondary);">
        <div><strong>Objectif</strong> : former des combinaisons.</div>
        <div><strong>Série</strong> : verbes du même groupe (♠ 1er, ♦ 2ème, ♣ 3ème, ♥ auxiliaires).</div>
        <div><strong>Suite</strong> : mêmes terminaisons / structures proches au présent.</div>
        <div><strong>Bonus</strong> : une combinaison avec un auxiliaire (♥) vaut +1 point.</div>
    </div>
</div>

<div class="form-card" style="max-width: 980px; margin: 18px auto 0;">
    <h2 style="margin: 0 0 10px; font-family: var(--font-display); font-size: 18px; font-weight: 800;">
        Bataille conjugaison
    </h2>
    <div style="display: grid; gap: 8px; color: var(--color-text-secondary);">
        <div><strong>Pioche</strong> : chaque joueur tire une carte.</div>
        <div><strong>Pronom</strong> : on tire un pronom (JE, TU, IL, NOUS, VOUS, ILS).</div>
        <div><strong>Gagne</strong> : celui qui donne la bonne forme au pronom.</div>
    </div>
</div>

<div class="form-card" style="max-width: 980px; margin: 18px auto 0;">
    <h2 style="margin: 0 0 10px; font-family: var(--font-display); font-size: 18px; font-weight: 800;">
        Go Fish verbes
    </h2>
    <div style="display: grid; gap: 8px; color: var(--color-text-secondary);">
        <div><strong>But</strong> : collectionner des familles (par groupe ou par pronom).</div>
        <div><strong>Demander</strong> : “As-tu un verbe du 2ème groupe ?” ou “As-tu la carte NOUS de … ?”.</div>
        <div><strong>Si non</strong> : “Pioche !”.</div>
    </div>
</div>

<div class="form-card" id="memory-root" data-back-pattern="{{ $backPattern }}" style="max-width: 980px; margin: 18px auto 0;">
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
        <h2 style="margin: 0; font-family: var(--font-display); font-size: 18px; font-weight: 800;">
            Mini-memory (infinitif ↔ forme conjuguée)
        </h2>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <label style="display: flex; gap: 8px; align-items: center; color: var(--color-text-muted);">
                Groupe
                <select id="memory-group" class="form-input" style="padding: 8px 10px; width: 180px;">
                    <option value="">Tous</option>
                    <option value="1er">1er</option>
                    <option value="2ème">2ème</option>
                    <option value="3ème">3ème</option>
                </select>
            </label>
            <label style="display: flex; gap: 8px; align-items: center; color: var(--color-text-muted);">
                Paires
                <select id="memory-pairs" class="form-input" style="padding: 8px 10px; width: 120px;">
                    <option value="6" selected>6</option>
                    <option value="8">8</option>
                    <option value="10">10</option>
                </select>
            </label>
            <button type="button" class="btn btn-primary" id="memory-start">
                <i class="ph ph-play"></i>
                Démarrer
            </button>
            <button type="button" class="btn btn-secondary" id="memory-reset" style="display: none;">
                <i class="ph ph-arrow-counter-clockwise"></i>
                Recommencer
            </button>
        </div>
    </div>
    <div class="memory-grid" id="memory-grid" style="margin-top: 14px;"></div>
</div>

<div class="form-card" id="deal-root" data-back-pattern="{{ $backPattern }}" style="max-width: 980px; margin: 18px auto 0;">
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
        <h2 style="margin: 0; font-family: var(--font-display); font-size: 18px; font-weight: 800;">
            Mélange & distribution
        </h2>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <label style="display: flex; gap: 8px; align-items: center; color: var(--color-text-muted);">
                Cartes
                <select id="deal-count" class="form-input" style="padding: 8px 10px; width: 120px;">
                    <option value="12">12</option>
                    <option value="18" selected>18</option>
                    <option value="24">24</option>
                </select>
            </label>
            <label style="display: flex; gap: 8px; align-items: center; color: var(--color-text-muted);">
                Distribuer
                <select id="deal-hand" class="form-input" style="padding: 8px 10px; width: 120px;">
                    <option value="5">5</option>
                    <option value="7" selected>7</option>
                    <option value="10">10</option>
                </select>
            </label>
            <button type="button" class="btn btn-secondary" id="deal-shuffle">
                <i class="ph ph-shuffle"></i>
                Mélanger
            </button>
            <button type="button" class="btn btn-primary" id="deal-deal">
                <i class="ph ph-hand"></i>
                Distribuer
            </button>
            <button type="button" class="btn btn-secondary" id="deal-reset">
                <i class="ph ph-arrow-counter-clockwise"></i>
                Réinitialiser
            </button>
        </div>
    </div>
    <div class="deal-stage">
        <div class="deal-stack" id="deal-stack" aria-label="Tas"></div>
        <div class="deal-hand" id="deal-hand-area" aria-label="Main"></div>
    </div>
</div>
@endsection

