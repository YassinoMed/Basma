@extends('layouts.app')

@section('title', 'Quiz - FrenchVerbs')

@section('content')
@php
$selectedGroup = $selectedGroup ?? 'all';
@endphp

<div class="section-header">
    <div class="section-badge">
        <i class="ph ph-question"></i>
        <span>Apprentissage</span>
    </div>
    <h1 class="section-title">Quiz</h1>
    <p class="section-description">Testez vos conjugaisons au présent.</p>
</div>

<div id="quiz-root" class="form-card form-card-wide">
    <div class="form-row" style="align-items: flex-end;">
        <div class="form-group" style="flex: 1;">
            <label class="form-label" for="quiz-mode">Mode</label>
            <select class="form-input" id="quiz-mode">
                <option value="standard" selected>Standard</option>
                <option value="timed">Chronométré</option>
                <option value="daily">Aléatoire quotidien</option>
                <option value="review">Révision (SRS)</option>
            </select>
        </div>
        <div class="form-group" style="flex: 1;">
            <label class="form-label" for="quiz-group">Groupe</label>
            <select class="form-input" id="quiz-group">
                <option value="" {{ $selectedGroup === 'all' ? 'selected' : '' }}>Tous</option>
                <option value="1er" {{ $selectedGroup === '1er' ? 'selected' : '' }}>1er groupe</option>
                <option value="2ème" {{ $selectedGroup === '2ème' ? 'selected' : '' }}>2ème groupe</option>
                <option value="3ème" {{ $selectedGroup === '3ème' ? 'selected' : '' }}>3ème groupe</option>
            </select>
        </div>
        <div class="form-group" style="flex: 0.7;">
            <label class="form-label" for="quiz-total">Questions</label>
            <input class="form-input" id="quiz-total" type="number" min="5" max="50" step="1" value="10">
        </div>
        <div class="form-group" style="flex: 0.8;">
            <label class="form-label" for="quiz-timer">Timer (s)</label>
            <input class="form-input" id="quiz-timer" type="number" min="15" max="300" step="5" value="60">
        </div>
        <div class="form-group" style="display: flex; gap: 10px; justify-content: flex-end;">
            <label class="form-label" style="display: flex; align-items: center; gap: 10px; margin: 0;">
                <input type="checkbox" id="quiz-reveal-all">
                <span>Révéler toutes les formes</span>
            </label>
            <button type="button" class="btn btn-primary" id="quiz-start">
                <i class="ph ph-play"></i>
                Démarrer
            </button>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; gap: 12px; margin-top: 14px; color: var(--color-text-muted); flex-wrap: wrap;">
        <div>
            Question: <strong id="quiz-questions">1 / 10</strong>
        </div>
        <div>
            Score: <strong id="quiz-score">0 / 0</strong>
        </div>
        <div id="quiz-timer-wrap" style="display: none;">
            Temps: <strong id="quiz-timer-left">60s</strong>
        </div>
        <div>
            Meilleur: <strong id="quiz-best">—</strong>
        </div>
    </div>

    <div style="margin-top: 18px;">
        <div class="form-label">À conjuguer</div>
        <div id="quiz-prompt" style="font-weight: 800; font-size: 1.1rem;"></div>
    </div>

    <div class="form-row" style="margin-top: 12px; align-items: flex-end;">
        <div class="form-group" style="flex: 1;">
            <label class="form-label" for="quiz-answer">Votre réponse</label>
            <input type="text" class="form-input" id="quiz-answer" placeholder="Ex: je mange">
        </div>
        <div class="form-group" style="display: flex; gap: 10px;">
            <button type="button" class="btn btn-primary" id="quiz-submit">
                Valider
            </button>
            <button type="button" class="btn btn-secondary" id="quiz-next" style="display: none;">
                Suivant
                <i class="ph ph-arrow-right"></i>
            </button>
        </div>
    </div>

    <div id="quiz-feedback" style="margin-top: 10px; font-weight: 700;"></div>
    <div id="quiz-reveal" style="margin-top: 6px; color: var(--color-text-muted); display: none;"></div>
    <div id="quiz-reveal-all-wrap" style="margin-top: 10px; display: none;"></div>
</div>
@endsection
