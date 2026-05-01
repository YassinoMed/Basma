@extends('layouts.app')

@section('title', $pageTitle ?? 'Créer une carte - FrenchVerbs')

@section('content')
@php
$initial = is_array($initial ?? null) ? $initial : [];
$isEdit = !empty($editSource);
$formAction = $formAction ?? route('cards.store');
$cancelUrl = $cancelUrl ?? route('cards.index');
$submitLabel = $submitLabel ?? 'Créer la carte';
@endphp
<div class="section-header">
    <div class="section-badge">
        <i class="ph {{ $isEdit ? 'ph-pencil' : 'ph-plus-circle' }}"></i>
        <span>{{ $isEdit ? 'Édition' : 'Nouvelle carte' }}</span>
    </div>
    <h1 class="section-title">{{ $isEdit ? 'Modifier une carte de verbe' : 'Créer une carte de verbe' }}</h1>
    <p class="section-description">{{ $isEdit ? 'Modifiez un verbe existant et sa conjugaison au présent.' : 'Ajoutez un nouveau verbe avec sa conjugaison au présent de l\'indicatif.' }}</p>
    @if(!empty($duplicateSource))
        <p class="section-description" style="margin-top: 8px;">
            Duplication depuis : <strong>{{ $duplicateSource->infinitive }}</strong>
        </p>
    @endif
    @if($isEdit)
        <p class="section-description" style="margin-top: 8px;">
            Modification de : <strong>{{ $editSource->infinitive }}</strong>
        </p>
    @endif
</div>

@if ($errors->any())
<div class="alert alert-danger" role="alert">
    <i class="ph ph-warning-circle"></i>
    <div>
        <div style="font-weight: 600;">Veuillez corriger les champs en erreur.</div>
        <ul style="margin: 8px 0 0 18px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ $formAction }}" method="POST" class="form-card form-card-wide">
    @csrf
    @if(!empty($formMethod))
        @method($formMethod)
    @endif
    <div class="create-layout">
        <div class="create-form">
            <div class="form-row" style="align-items: end;">
                <div class="form-group" style="flex: 2;">
                    <label class="form-label" for="builder-template">Modèles</label>
                    <select class="form-input form-select" id="builder-template">
                        <option value="">Aucun</option>
                        <option value="classic">Classique</option>
                        <option value="ocean">Océan</option>
                        <option value="forest">Forêt</option>
                        <option value="lavender">Lavande</option>
                        <option value="contrast">Contraste</option>
                    </select>
                </div>
                <div class="form-group" style="display: flex; gap: 10px; justify-content: flex-end; flex: 3;">
                    <button type="button" class="btn btn-secondary" id="builder-undo" disabled>
                        <i class="ph ph-arrow-counter-clockwise"></i>
                        Annuler
                    </button>
                    <button type="button" class="btn btn-secondary" id="builder-redo" disabled>
                        <i class="ph ph-arrow-clockwise"></i>
                        Rétablir
                    </button>
                    <button type="button" class="btn btn-secondary" id="builder-tutorial">
                        <i class="ph ph-question"></i>
                        Tutoriel
                    </button>
                    <button type="button" class="btn btn-secondary" id="builder-clear">
                        <i class="ph ph-eraser"></i>
                        Réinitialiser
                    </button>
                </div>
            </div>

            <h3 class="form-section-title">
                <i class="ph ph-text-aa"></i>
                Informations du verbe
            </h3>

            <div class="form-row">
                <div class="form-group" style="flex: 2;">
                    <label class="form-label" for="infinitive">Infinitif *</label>
                    <div class="input-with-action">
                        <input type="text" class="form-input" id="infinitive" name="infinitive" placeholder="Ex: manger"
                            value="{{ old('infinitive', $initial['infinitive'] ?? '') }}" autocapitalize="none" autocorrect="off" spellcheck="false"
                            required>
                        <button type="button" class="btn btn-secondary btn-icon-only" id="magic-conjugate"
                            title="Conjugaison Magique">
                            <i class="ph-fill ph-sparkle" style="color: var(--color-accent);"></i>
                        </button>
                    </div>
                    @error('infinitive')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="flex: 1.5;">
                    <label class="form-label" for="infinitive_translation">Traduction (anglais)</label>
                    <input type="text" class="form-input" id="infinitive_translation" name="infinitive_translation"
                        placeholder="Ex: to eat" value="{{ old('infinitive_translation', $initial['infinitive_translation'] ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="example_sentence">Phrase d’exemple (optionnel)</label>
                <input type="text" class="form-input" id="example_sentence" name="example_sentence"
                    placeholder="Ex: Je mange une pomme." value="{{ old('example_sentence', $initial['example_sentence'] ?? '') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Groupe verbal *</label>
                <div class="group-selector">
                    <label class="group-option">
                        <input type="radio" name="group" value="1er" {{ old('group', $initial['group'] ?? '')=='1er' ? 'checked' : '' }}
                            required>
                        <div class="group-card">
                            <span class="group-icon">I</span>
                            <span class="group-name">1er Groupe</span>
                            <span class="group-desc">-ER (sauf aller)</span>
                        </div>
                    </label>
                    <label class="group-option">
                        <input type="radio" name="group" value="2ème" {{ old('group', $initial['group'] ?? '')=='2ème' ? 'checked' : '' }}>
                        <div class="group-card">
                            <span class="group-icon">II</span>
                            <span class="group-name">2ème Groupe</span>
                            <span class="group-desc">-IR (finissons)</span>
                        </div>
                    </label>
                    <label class="group-option">
                        <input type="radio" name="group" value="3ème" {{ old('group', $initial['group'] ?? '')=='3ème' ? 'checked' : '' }}>
                        <div class="group-card">
                            <span class="group-icon">III</span>
                            <span class="group-name">3ème Groupe</span>
                            <span class="group-desc">Irréguliers</span>
                        </div>
                    </label>
                </div>
                <!-- Hidden select for compatibility if needed, or just rely on radios. js might need update -->
                <input type="hidden" id="group-value" value="{{ old('group', ($initial['group'] ?? '1er') ?: '1er') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Couleur de la carte *</label>
                <input type="hidden" name="suit" id="suit" value="{{ old('suit', ($initial['suit'] ?? 'spade') ?: 'spade') }}">
                <div class="group-selector suit-selector">
                    <label class="group-option">
                        <input type="radio" name="suit_display" value="spade" {{ old('suit', ($initial['suit'] ?? 'spade') ?: 'spade') === 'spade' ? 'checked' : '' }} disabled>
                        <div class="group-card suit-card">
                            <span class="group-icon suit-icon" style="color: black;">♠</span>
                            <span class="group-name">Pique</span>
                        </div>
                    </label>
                    <label class="group-option">
                        <input type="radio" name="suit_display" value="diamond" {{ old('suit', $initial['suit'] ?? '') === 'diamond' ? 'checked' : '' }} disabled>
                        <div class="group-card suit-card">
                            <span class="group-icon suit-icon" style="color: #d32f2f;">♦</span>
                            <span class="group-name">Carreau</span>
                        </div>
                    </label>
                    <label class="group-option">
                        <input type="radio" name="suit_display" value="club" {{ old('suit', $initial['suit'] ?? '') === 'club' ? 'checked' : '' }} disabled>
                        <div class="group-card suit-card">
                            <span class="group-icon suit-icon" style="color: black;">♣</span>
                            <span class="group-name">Trèfle</span>
                        </div>
                    </label>
                    <label class="group-option">
                        <input type="radio" name="suit_display" value="heart" {{ old('suit', $initial['suit'] ?? '') === 'heart' ? 'checked' : '' }} disabled>
                        <div class="group-card suit-card">
                            <span class="group-icon suit-icon" style="color: #d32f2f;">♥</span>
                            <span class="group-name">Cœur</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="theme_color">Couleur du thème</label>
                    <div class="color-picker-wrapper">
                        <input type="color" class="form-input-color" id="theme_color" name="theme_color"
                            value="{{ old('theme_color', ($initial['theme_color'] ?? '') ?: '#1e3a5f') }}">
                        <span class="color-value">#1e3a5f</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="accent_color">Couleur d’accent</label>
                    <div class="color-picker-wrapper">
                        <input type="color" class="form-input-color" id="accent_color" name="accent_color"
                            value="{{ old('accent_color', ($initial['accent_color'] ?? '') ?: '#5b9bd5') }}">
                        <span class="color-value">#5b9bd5</span>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="pattern">Motif de fond (Texture)</label>
                    <select class="form-input form-select" id="pattern" name="pattern">
                        <option value="" {{ old('pattern', $initial['pattern'] ?? null) === null ? 'selected' : '' }}>Par défaut du thème</option>
                        <option value="plain" {{ old('pattern', $initial['pattern'] ?? '')=='plain' ? 'selected' : '' }}>Uni (Classique)</option>
                        <option value="diamonds" {{ old('pattern', $initial['pattern'] ?? '')=='diamonds' ? 'selected' : '' }}>Losanges ♦</option>
                        <option value="geometry" {{ old('pattern', $initial['pattern'] ?? '')=='geometry' ? 'selected' : '' }}>Géométrique</option>
                        <option value="mixed" {{ old('pattern', $initial['pattern'] ?? '')=='mixed' ? 'selected' : '' }}>Mixte</option>
                        <option value="checker" {{ old('pattern', $initial['pattern'] ?? '')=='checker' ? 'selected' : '' }}>Damier</option>
                        <option value="stripes" {{ old('pattern', $initial['pattern'] ?? '')=='stripes' ? 'selected' : '' }}>Rayures</option>
                        <option value="dots" {{ old('pattern', $initial['pattern'] ?? '')=='dots' ? 'selected' : '' }}>Points</option>
                        <option value="hexagons" {{ old('pattern', $initial['pattern'] ?? '')=='hexagons' ? 'selected' : '' }}>Hexagones</option>
                        <option value="triangles" {{ old('pattern', $initial['pattern'] ?? '')=='triangles' ? 'selected' : '' }}>Triangles</option>
                        <option value="chevron" {{ old('pattern', $initial['pattern'] ?? '')=='chevron' ? 'selected' : '' }}>Chevrons</option>
                        <option value="waves" {{ old('pattern', $initial['pattern'] ?? '')=='waves' ? 'selected' : '' }}>Vagues</option>
                        <option value="circles" {{ old('pattern', $initial['pattern'] ?? '')=='circles' ? 'selected' : '' }}>Cercles</option>
                    </select>
                </div>
            </div>

            <div class="form-divider"></div>

            <h3 class="form-section-title">
                <i class="ph ph-list-numbers"></i>
                Conjugaison au présent
            </h3>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="je">je *</label>
                    <input type="text" class="form-input" id="je" name="je" placeholder="Ex: mange"
                        value="{{ old('je', $initial['je'] ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="tu">tu *</label>
                    <input type="text" class="form-input" id="tu" name="tu" placeholder="Ex: manges"
                        value="{{ old('tu', $initial['tu'] ?? '') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="il_elle_on">il/elle/on *</label>
                    <input type="text" class="form-input" id="il_elle_on" name="il_elle_on" placeholder="Ex: mange"
                        value="{{ old('il_elle_on', $initial['il_elle_on'] ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nous">nous *</label>
                    <input type="text" class="form-input" id="nous" name="nous" placeholder="Ex: mangeons"
                        value="{{ old('nous', $initial['nous'] ?? '') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="vous">vous *</label>
                    <input type="text" class="form-input" id="vous" name="vous" placeholder="Ex: mangez"
                        value="{{ old('vous', $initial['vous'] ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="ils_elles">ils/elles *</label>
                    <input type="text" class="form-input" id="ils_elles" name="ils_elles" placeholder="Ex: mangent"
                        value="{{ old('ils_elles', $initial['ils_elles'] ?? '') }}" required>
                </div>
            </div>

            <div class="form-divider"></div>

            <div class="form-actions">
                <a href="{{ $cancelUrl }}" class="btn btn-secondary">
                    <i class="ph ph-x"></i>
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check"></i>
                    {{ $submitLabel }}
                </button>
            </div>
        </div>

        <aside class="create-preview">
            <div class="create-preview-sticky">
                <h3 class="form-section-title">
                    <i class="ph ph-eye"></i>
                    Aperçu
                </h3>
                <div style="margin: 6px 0 14px; color: var(--color-text-muted); font-size: 0.95rem;">
                    Couleurs : ♠ Pique · <span style="color: #d32f2f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">♦</span> Carreaux · ♣ Trèfle · ♥ Cœur
                </div>

                <article class="rami-card create-preview-card" id="create-preview-card"
                    data-group="{{ old('group', ($initial['group'] ?? '1er') ?: '1er') }}" data-suit="{{ old('suit', ($initial['suit'] ?? 'spade') ?: 'spade') }}" data-pattern="{{ old('pattern', ($initial['pattern'] ?? 'plain') ?: 'plain') }}"
                    style="--rami-accent-color: {{ old('accent_color', ($initial['accent_color'] ?? '') ?: '#5b9bd5') }}; --rami-card-border-color: {{ old('theme_color', ($initial['theme_color'] ?? '') ?: '#1e3a5f') }}; cursor: default;">
                    <div class="rami-card-index-top">
                        <div class="rami-card-index-pronoun">
                            <span class="rami-card-suit" id="create-preview-suit" aria-hidden="true"></span>
                            <span>JE</span>
                        </div>
                        <div class="rami-card-index-verb" id="create-preview-je-index-top">{{ old('je', $initial['je'] ?? '') ?: '...' }}
                        </div>
                    </div>

                    <span class="rami-card-badge" id="create-preview-group">
                        <span class="rami-card-suit" id="create-preview-suit-badge" aria-hidden="true"></span>
                        <span>{{ old('group', ($initial['group'] ?? '1er') ?: '1er') }} groupe</span>
                    </span>

                    <div class="rami-card-center">
                        <div class="rami-card-illustration">
                        </div>
                    </div>

                    <div class="rami-card-verb">
                        <div class="rami-card-verb-text" id="create-preview-infinitive">{{
                            mb_strtoupper(old('infinitive', $initial['infinitive'] ?? '') ?: '...') }}</div>
                    </div>

                    <div class="rami-card-index-bottom">
                        <div class="rami-card-index-pronoun">
                            <span class="rami-card-suit" id="create-preview-suit-bottom" aria-hidden="true"></span>
                            <span>JE</span>
                        </div>
                        <div class="rami-card-index-verb" id="create-preview-je-index-bottom">{{ old('je', $initial['je'] ?? '') ?: '...' }}
                        </div>
                    </div>
                </article>

                <div class="create-preview-translation" id="create-preview-translation"
                    style="{{ old('infinitive_translation', $initial['infinitive_translation'] ?? '') ? '' : 'display: none;' }}">
                    {{ old('infinitive_translation', $initial['infinitive_translation'] ?? '') }}
                </div>

                <div class="card-conjugations-preview create-preview-conjugations">
                    <div class="conjugation-row">
                        <span class="conjugation-pronoun">je</span>
                        <span class="conjugation-form" id="create-preview-je">{{ old('je', $initial['je'] ?? '') ?: '...' }}</span>
                    </div>
                    <div class="conjugation-row">
                        <span class="conjugation-pronoun">tu</span>
                        <span class="conjugation-form" id="create-preview-tu">{{ old('tu', $initial['tu'] ?? '') ?: '...' }}</span>
                    </div>
                    <div class="conjugation-row">
                        <span class="conjugation-pronoun">il/elle/on</span>
                        <span class="conjugation-form" id="create-preview-il">{{ old('il_elle_on', $initial['il_elle_on'] ?? '') ?: '...' }}</span>
                    </div>
                    <div class="conjugation-row">
                        <span class="conjugation-pronoun">nous</span>
                        <span class="conjugation-form" id="create-preview-nous">{{ old('nous', $initial['nous'] ?? '') ?: '...' }}</span>
                    </div>
                    <div class="conjugation-row">
                        <span class="conjugation-pronoun">vous</span>
                        <span class="conjugation-form" id="create-preview-vous">{{ old('vous', $initial['vous'] ?? '') ?: '...' }}</span>
                    </div>
                    <div class="conjugation-row">
                        <span class="conjugation-pronoun">ils/elles</span>
                        <span class="conjugation-form" id="create-preview-ils">{{ old('ils_elles', $initial['ils_elles'] ?? '') ?: '...' }}</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</form>
@endsection
