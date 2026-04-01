@extends('layouts.app')

@section('title', 'FrenchVerbs - Cartes de Conjugaison Premium')

@section('content')
<div class="landing">
    <section class="landing-hero">
        <div class="landing-hero-inner">
            <div class="landing-hero-copy">
                <div class="section-badge">
                    <i class="ph ph-sparkle"></i>
                    <span>Cartes éducatives premium</span>
                </div>

                <h1 class="landing-title">Maîtrisez la conjugaison française avec des cartes style rami</h1>
                <p class="landing-subtitle">
                    Un design clair, des verbes classés, des favoris, un quiz, et l’impression en un clic.
                    Apprenez efficacement, sans friction.
                </p>

                <div class="landing-actions">
                    @auth
                        <a href="{{ route('cards.index') }}" class="btn btn-primary btn-lg">
                            <i class="ph ph-cards"></i>
                            Accéder aux cartes
                        </a>
                        <a href="{{ route('quiz.index') }}" class="btn btn-secondary btn-lg">
                            <i class="ph ph-question"></i>
                            Lancer un quiz
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            <i class="ph ph-rocket-launch"></i>
                            Commencer gratuitement
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">
                            <i class="ph ph-sign-in"></i>
                            Connexion
                        </a>
                    @endauth
                </div>

                <div class="landing-highlights" role="list">
                    <div class="landing-highlight" role="listitem">
                        <i class="ph ph-magnifying-glass"></i>
                        <span>Recherche instantanée</span>
                    </div>
                    <div class="landing-highlight" role="listitem">
                        <i class="ph ph-star"></i>
                        <span>Favoris & progression</span>
                    </div>
                    <div class="landing-highlight" role="listitem">
                        <i class="ph ph-printer"></i>
                        <span>Impression A4</span>
                    </div>
                </div>
            </div>

            <div class="landing-hero-preview" aria-hidden="true">
                <div class="landing-preview-card">
                    <div class="landing-preview-top">
                        <div class="landing-preview-pill">
                            <i class="ph ph-lightning"></i>
                            <span>Exemple</span>
                        </div>
                        <div class="landing-preview-pill is-soft">
                            <i class="ph ph-sparkle"></i>
                            <span>Style rami</span>
                        </div>
                    </div>
                    <div class="landing-preview-image-wrap">
                        <img src="{{ asset('images/cards/je_mange.png') }}" alt="" class="landing-preview-image">
                    </div>
                    <div class="landing-preview-bottom">
                        <div class="landing-preview-meta">
                            <div class="landing-preview-meta-title">manger</div>
                            <div class="landing-preview-meta-subtitle">to eat</div>
                        </div>
                        <div class="landing-preview-badge">1er groupe</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="landing-section">
        <div class="section-header">
            <div class="section-badge">
                <i class="ph ph-compass-tool"></i>
                <span>Fonctionnalités</span>
            </div>
            <h2 class="section-title">Un parcours simple, du premier verbe au réflexe</h2>
            <p class="section-description">Chaque écran est pensé pour rester lisible, rapide et agréable.</p>
        </div>

        <div class="landing-feature-grid">
            <article class="landing-feature">
                <div class="landing-feature-icon">
                    <i class="ph ph-cards"></i>
                </div>
                <h3 class="landing-feature-title">Cartes élégantes</h3>
                <p class="landing-feature-text">Une mise en page claire pour mémoriser plus vite.</p>
            </article>

            <article class="landing-feature">
                <div class="landing-feature-icon">
                    <i class="ph ph-star"></i>
                </div>
                <h3 class="landing-feature-title">Favoris & suivi</h3>
                <p class="landing-feature-text">Gardez vos verbes clés sous la main et révisez mieux.</p>
            </article>

            <article class="landing-feature">
                <div class="landing-feature-icon">
                    <i class="ph ph-question"></i>
                </div>
                <h3 class="landing-feature-title">Quiz</h3>
                <p class="landing-feature-text">Testez vos acquis et consolidez vos automatismes.</p>
            </article>

            <article class="landing-feature">
                <div class="landing-feature-icon">
                    <i class="ph ph-printer"></i>
                </div>
                <h3 class="landing-feature-title">Impression</h3>
                <p class="landing-feature-text">Imprimez un deck A4 (9 cartes) en quelques secondes.</p>
            </article>
        </div>
    </section>

    <section class="landing-cta">
        <div class="landing-cta-inner">
            <div class="landing-cta-copy">
                <div class="landing-cta-title">Prêt à réviser ?</div>
                <div class="landing-cta-text">Créez un compte et commencez avec vos verbes du quotidien.</div>
            </div>
            <div class="landing-cta-actions">
                @auth
                    <a href="{{ route('cards.index') }}" class="btn btn-primary btn-lg">
                        <i class="ph ph-play"></i>
                        Ouvrir les cartes
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="ph ph-rocket-launch"></i>
                        Créer un compte
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">
                        <i class="ph ph-sign-in"></i>
                        Se connecter
                    </a>
                @endauth
            </div>
        </div>
    </section>
</div>
@endsection

