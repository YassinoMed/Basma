<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cartes éducatives premium pour l'apprentissage de la conjugaison française">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FrenchVerbs - Cartes de Conjugaison Premium')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&family=Lora:ital,wght@0,400;0,600;1,400&family=Montserrat:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;700&family=Patrick+Hand&family=Space+Grotesk:wght@400;500;700&display=swap"
        rel="stylesheet">

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @livewireStyles

    @if(!empty($themeSettingsInlineCss))
    <style id="theme-settings-inline">
        {!! $themeSettingsInlineCss !!}
    </style>
    @endif

    @stack('styles')
</head>

<body class="{{ $bodyClass ?? '' }}">
    <div id="notification-container" class="notification-container" aria-live="polite" aria-atomic="true"></div>
    @if(empty($hideChrome))
    @php
    $isCardsV3 = request()->routeIs('cards.index_v3') || request()->routeIs('cards.show_v3');
    $isFavoritesV3 = request()->routeIs('favorites.index_v3');
    $useV3Nav = $isCardsV3 || $isFavoritesV3;
    $cardsIndexRouteName = $useV3Nav ? 'cards.index_v3' : 'cards.index';
    $favoritesIndexRouteName = $useV3Nav ? 'favorites.index_v3' : 'favorites.index';
    @endphp
    <nav class="navbar navbar-modern">
        <div class="navbar-backdrop"></div>
        <div class="navbar-container">
            <a href="{{ auth()->check() ? route($cardsIndexRouteName) : route('home') }}"
                class="navbar-brand navbar-brand-modern">
                <div class="brand-icon brand-icon-modern">
                    <i class="ph-duotone ph-book-open-text"></i>
                    <div class="brand-icon-glow"></div>
                </div>
                <span class="brand-text brand-text-modern">
                    French<span class="brand-accent">Verbs</span>
                </span>
            </a>

            <div class="navbar-main" id="navbar-main">
                <div class="navbar-menu navbar-menu-modern">
                    @auth
                    <a href="{{ route($cardsIndexRouteName) }}"
                        class="nav-link nav-link-modern {{ (request()->routeIs('cards.index') || request()->routeIs('cards.index_v3') || request()->routeIs('cards.show') || request()->routeIs('cards.show_v3')) ? 'active' : '' }}">
                        <i class="ph ph-cards"></i>
                        <span>Cartes</span>
                        <div class="nav-link-indicator"></div>
                    </a>
                    <a href="{{ route($favoritesIndexRouteName) }}"
                        class="nav-link nav-link-modern {{ (request()->routeIs('favorites.index') || request()->routeIs('favorites.index_v3')) ? 'active' : '' }}">
                        <i class="ph ph-star"></i>
                        <span>Favoris</span>
                        <div class="nav-link-indicator"></div>
                    </a>
                    <a href="{{ route('quiz.index') }}"
                        class="nav-link nav-link-modern {{ request()->routeIs('quiz.index') ? 'active' : '' }}">
                        <i class="ph ph-question"></i>
                        <span>Quiz</span>
                        <div class="nav-link-indicator"></div>
                    </a>
                    <a href="{{ route('cards.rules') }}"
                        class="nav-link nav-link-modern {{ request()->routeIs('cards.rules') ? 'active' : '' }}">
                        <i class="ph ph-book-open"></i>
                        <span>Règles</span>
                        <div class="nav-link-indicator"></div>
                    </a>
                    @else
                    <a href="{{ route('home') }}"
                        class="nav-link nav-link-modern {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="ph ph-house"></i>
                        <span>Accueil</span>
                        <div class="nav-link-indicator"></div>
                    </a>
                    @endauth
                </div>

                <div class="navbar-actions">
                    @auth
                    <a href="{{ route('builder') }}"
                        class="btn btn-primary btn-modern navbar-create {{ request()->routeIs('builder') || request()->routeIs('cards.create') ? 'is-active' : '' }}"
                        title="Créer une carte">
                        <i class="ph ph-plus-circle"></i>
                        <span>Créer</span>
                        <div class="btn-shine"></div>
                    </a>
                    @else
                    <a href="{{ route('register') }}"
                        class="btn btn-primary navbar-create {{ request()->routeIs('register') ? 'is-active' : '' }}"
                        title="Créer un compte">
                        <i class="ph ph-rocket-launch"></i>
                        <span>Commencer</span>
                    </a>
                    @endauth
                </div>
            </div>

            <div class="navbar-quick-actions">
                <button class="btn-icon btn-icon-modern" id="theme-toggle" title="Changer le thème" type="button">
                    <i class="ph ph-moon"></i>
                    <div class="btn-icon-ripple"></div>
                </button>

                @if(!empty($adminThemeLinkEnabled))
                <div class="dropdown dropdown-modern" data-dropdown>
                    <button class="btn-icon btn-icon-modern dropdown-toggle" type="button" title="Administration"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="ph ph-shield-star"></i>
                        <div class="btn-icon-ripple"></div>
                    </button>
                    <div class="dropdown-menu dropdown-menu-modern dropdown-menu-right" role="menu">
                        <a class="dropdown-item dropdown-item-modern" href="{{ route('admin.system.index') }}">
                            <i class="ph ph-gear"></i>
                            <span>Système</span>
                        </a>
                        <a class="dropdown-item dropdown-item-modern" href="{{ route('admin.verbs.index') }}">
                            <i class="ph ph-cards"></i>
                            <span>Verbes</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item dropdown-item-modern" href="{{ route('admin.theme.edit') }}">
                            <i class="ph ph-palette"></i>
                            <span>Thème</span>
                        </a>
                        <a class="dropdown-item dropdown-item-modern" href="{{ route('admin.theme.editV2') }}">
                            <i class="ph ph-sparkle"></i>
                            <span>Thème (V2)</span>
                        </a>
                        <a class="dropdown-item dropdown-item-modern" href="{{ route('admin.theme.editV3') }}">
                            <i class="ph ph-magic-wand"></i>
                            <span>Thème (V3)</span>
                        </a>
                        <a class="dropdown-item dropdown-item-modern" href="{{ route('admin.theme.editV4') }}">
                            <i class="ph ph-paint-brush"></i>
                            <span>Thème (V4)</span>
                        </a>
                        <a class="dropdown-item dropdown-item-modern" href="{{ route('admin.theme.editV5') }}">
                            <i class="ph ph-rocket-launch"></i>
                            <span>Thème (V5) ✨</span>
                        </a>
                        <a class="dropdown-item dropdown-item-modern" href="{{ route('admin.theme.editV6') }}">
                            <i class="ph ph-shooting-star"></i>
                            <span>Thème (V6) ✦</span>
                        </a>
                        <a class="dropdown-item dropdown-item-modern" href="{{ route('admin.theme.editV7') }}">
                            <i class="ph ph-crown"></i>
                            <span>Thème (V7) ❧</span>
                        </a>
                    </div>
                </div>
                @endif

                @auth
                <div class="dropdown dropdown-modern" data-dropdown>
                    <button class="btn-icon btn-icon-modern dropdown-toggle" type="button"
                        title="{{ auth()->user()->email ?? 'Compte' }}" aria-haspopup="true" aria-expanded="false">
                        <i class="ph ph-user-circle"></i>
                        <div class="btn-icon-ripple"></div>
                    </button>
                    <div class="dropdown-menu dropdown-menu-modern dropdown-menu-right" role="menu">
                        <div class="dropdown-header">
                            <div class="dropdown-title">{{ auth()->user()->name ?? 'Compte' }}</div>
                            @if(!empty(auth()->user()->email))
                            <div class="dropdown-subtitle">{{ auth()->user()->email }}</div>
                            @endif
                        </div>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" class="dropdown-form">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item-modern">
                                <i class="ph ph-sign-out"></i>
                                <span>Se déconnecter</span>
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="dropdown dropdown-modern" data-dropdown>
                    <button class="btn-icon btn-icon-modern dropdown-toggle" type="button" title="Compte"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="ph ph-user"></i>
                        <div class="btn-icon-ripple"></div>
                    </button>
                    <div class="dropdown-menu dropdown-menu-modern dropdown-menu-right" role="menu">
                        <a class="dropdown-item dropdown-item-modern" href="{{ route('login') }}">
                            <i class="ph ph-sign-in"></i>
                            <span>Connexion</span>
                        </a>
                        <a class="dropdown-item dropdown-item-modern" href="{{ route('register') }}">
                            <i class="ph ph-user-plus"></i>
                            <span>Créer un compte</span>
                        </a>
                    </div>
                </div>
                @endauth
            </div>

            <button class="btn-icon btn-icon-modern navbar-toggle" id="navbar-toggle" type="button"
                aria-controls="navbar-main" aria-expanded="false" aria-label="Ouvrir le menu" title="Menu">
                <i class="ph ph-list"></i>
                <div class="btn-icon-ripple"></div>
            </button>
        </div>
    </nav>
    @endif

    <!-- Contenu principal -->
    <main class="main-content {{ $mainClass ?? '' }}">
        @if(session('success'))
        <div class="alert alert-success">
            <i class="ph ph-check-circle"></i>
            {{ session('success') }}
            <button class="alert-close" onclick="this.parentElement.remove()">
                <i class="ph ph-x"></i>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            <i class="ph ph-warning-circle"></i>
            {{ session('error') }}
            <button class="alert-close" onclick="this.parentElement.remove()">
                <i class="ph ph-x"></i>
            </button>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer élégant -->
    @if(empty($hideChrome))
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-brand">
                <i class="ph-duotone ph-graduation-cap"></i>
                <span>FrenchVerbs</span>
            </div>
            <p class="footer-copyright">© {{ date('Y') }} - Application éducative premium</p>
        </div>
    </footer>
    @endif

    @livewireScripts
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
