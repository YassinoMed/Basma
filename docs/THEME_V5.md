# 📋 Thème V5 — Documentation

## Vue d'ensemble

Le thème **V5** est une version modernisée et premium basée sur la structure du thème V2, avec des améliorations visuelles significatives, une meilleure expérience utilisateur, des performances optimisées et une accessibilité renforcée.

> **Philosophie** : V5 conserve la stabilité et la logique de V2 tout en apportant un design glassmorphique, des micro-animations fluides, un mode sombre avancé et une personnalisation complète.

---

## 🆕 Nouveautés V5

### 1. Design Modernisé
- **Glassmorphisme** : Surfaces translucides avec `backdrop-filter` pour un effet de profondeur moderne
- **Palette Indigo/Cyan** : Couleurs contemporaines (`#4f46e5` primaire, `#06b6d4` accent)
- **Ombres raffinées** : Système d'ombres multi-couches (`shadow-sm`, `shadow-md`, `shadow-lg`, `shadow-xl`)
- **Bordures subtiles** : Bordures translucides pour un rendu léger
- **Rayons arrondis augmentés** : `20px` par défaut pour un look doux
- **Particules flottantes** : Fond animé avec des points lumineux indigo/cyan
- **Dégradés de titre** : Le titre du customizer utilise un gradient indigo→cyan

### 2. Typographie
- **Police Inter** : Police sans-serif moderne optimisée pour les interfaces (weight 800 ajouté)
- **JetBrains Mono** : Police mono-espace pour les valeurs techniques (hex, dimensions)
- **Hiérarchie claire** : Espacement des lettres et graisses ajustés par section

### 3. Animations & Micro-interactions
- **Zoom Interactif** : Cliquez sur n'importe quelle carte pour un zoom immersif (x1 à x5)
- **Apparition séquentielle** : Les cartes apparaissent en cascade avec délai progressif
- **Effet shimmer** : Brillance subtile sur le bouton de sauvegarde au survol
- **Pulse lumineux** : Animation douce dans l'en-tête du customizer
- **Sparkle magique** : L'icône du bouton Magique tourne et scale au survol
- **Spin réinitialisation** : L'icône du bouton Reset effectue une rotation de -360°
- **Transitions cubiques** : `cubic-bezier(0.22, 1, 0.36, 1)` pour des mouvements naturels
- **Hover amplifié** : Scale des démos de motifs au survol (×1.08)
- **Dark mode smooth** : Transition fluide de 0.4s lors du changement de thème

### 4. Mode Sombre Amélioré
- **Variables dédiées** : Palette sombre complète avec des valeurs adaptées
- **Surfaces profondes** : Arrière-plans sombres avec transparence
- **Particules adaptées** : Les particules flottantes s'ajustent en mode sombre
- **Accent lumineux** : Teintes primaires plus claires pour visibilité
- **Ombres adaptées** : Ombres plus prononcées en mode sombre
- **Transition fluide** : Changement de thème avec animation CSS de 0.4s

### 5. Nouvelles Options Graphiques (V5 Exclusif)
- **Styles d'écriture** : 4 presets intelligents (Classique, Moderne, Élégant, Minimaliste) qui ajustent police, graisse et espacement
- **Formes de carte** : 4 presets de forme (Arrondie, Carrée, Nette, Capsule)
- **Zoom contrôlable** : Slider pour définir le niveau de zoom par défaut (1x-5x)

### 6. Accessibilité (WCAG)
- **Focus visible** : Contours personnalisés avec la couleur primaire
- **Mouvement réduit** : Support `prefers-reduced-motion` pour désactiver les animations
- **Contraste élevé** : Support `prefers-contrast: high` pour les bordures et textes
- **Aria labels** : Éléments interactifs correctement annotés
- **Aria-live** : Zone de statut de sauvegarde avec `aria-live="polite"`
- **Zoom clavier** : Support de la touche Échap pour fermer le zoom

### 7. Responsive Design
- **Mobile-first** : Adaptation fluide sur tous les écrans
- **Breakpoints** : 640px (mobile), 1200px (tablette), au-delà (desktop)
- **Espacement adaptatif** : Padding et marges réduits sur petits écrans

### 8. Performances
- **CSS Variables** : Tokens de design centralisés pour la réutilisation
- **Fichier CSS séparé** : `admin-theme-v5.css` chargé uniquement pour V5
- **Versioning automatique** : Cache busting via timestamp du fichier
- **Minimal JavaScript** : Réutilise le JS existant de V2
- **Firefox fallback** : `@supports` pour remplacer backdrop-filter par opacité solide
- **GPU accelerated** : backdrop-filter et animations CSS optimisées

### 9. Navigation Améliorée
- **Menu admin complet** : V4 et V5 ajoutés au dropdown de navigation admin
- **V5 avec icône fusée** : Identification visuelle immédiate avec ✨
- **Pilules de version** : V1→V5 accessibles dans la barre de l'éditeur

---

## 📊 Comparaison V2 vs V5

| Caractéristique | V2 | V5 |
|---|---|---|
| **Palette primaire** | `#1e3a5f` (bleu marine) | `#4f46e5` (indigo) |
| **Accent** | Rose/bleu | `#06b6d4` (cyan) |
| **Fond de carte** | `#faf8f5` (beige) | `#ffffff` (blanc pur) |
| **Bordure carte** | `2px solid #3a3a3a` | `1px solid rgba(0,0,0,0.08)` |
| **Rayon bordure** | `16px` | Variable (Arrondi/Carré/Capsule) |
| **Ombres** | Standard | Multi-couches raffinées |
| **Glassmorphisme** | Non | Oui |
| **Particules flottantes** | Non | Oui (animation 20s) |
| **Zoom Carte** | Non | **Oui (Interactif x3)** |
| **Styles d'écriture** | Manuel | **Presets Intelligents** |
| **Formes de carte** | Manuel | **Presets Rapides** |
| **Animations card** | Basique | Cascade séquentielle |
| **Shimmer bouton** | Non | Oui |
| **Sparkle magique** | Non | Oui |
| **Mode sombre** | Standard | Variables dédiées avancées |
| **Dark mode transition** | Immédiat | 0.4s smooth |
| **Accessibilité** | Basique | WCAG amélioré |
| **Scrollbar** | 10px standard | 6px minimaliste |
| **High contrast** | Non | Oui |
| **Print styles** | Non | Oui |
| **Firefox fallback** | N/A | `@supports` fallback |
| **Font mono** | Système | JetBrains Mono |
| **CSS séparé** | Non | Oui (`admin-theme-v5.css`) |
| **Nav admin** | V1-V3 | V1-V5 complet |

---

## 🛠 Installation et Utilisation

### Accès
1. Connectez-vous en tant qu'administrateur
2. Naviguez via **Admin (bouclier) → Thème (V5) ✨**
3. Ou cliquez sur l'onglet **V5** dans la barre de versions du customizer

### URL directe
```
https://votre-domaine/admin/theme-v5
```

### Route Laravel
```php
Route::get('/theme-v5', [ThemeController::class, 'editV5'])->name('admin.theme.editV5');
```

### Structure des fichiers

```
├── app/Http/Controllers/ThemeController.php    # editV5() + defaultThemeSettingsV5()
├── resources/views/admin/theme.blade.php       # Template partagé (V1-V5)
├── resources/views/layouts/app.blade.php       # Navigation admin avec V5
├── public/css/admin-theme-v5.css               # Styles exclusifs V5 (~1500 lignes)
├── public/css/admin-theme.fonts.css            # Polices (Inter 800 + JetBrains Mono)
├── routes/web.php                              # Route /admin/theme-v5
└── docs/THEME_V5.md                            # Cette documentation
```

### Stockage des paramètres
Les paramètres V5 sont stockés avec le préfixe `--rami-v5-` dans la table `theme_settings` :
```
--rami-v5-writing-style
--rami-v5-card-shape
--rami-v5-zoom-level
... et les héritages V2 (--rami-*)
```
Cela garantit une isolation complète par rapport aux thèmes V1-V4.

---

## 🎨 Tokens de Design V5

### Couleurs

| Token | Light | Dark |
|---|---|---|
| `--v5-primary` | `#4f46e5` | `#818cf8` |
| `--v5-primary-light` | `#818cf8` | `#a5b4fc` |
| `--v5-primary-dark` | `#3730a3` | `#6366f1` |
| `--v5-accent` | `#06b6d4` | `#22d3ee` |
| `--v5-accent-light` | `#67e8f9` | `#67e8f9` |
| `--v5-success` | `#059669` | `#34d399` |
| `--v5-warning` | `#f59e0b` | — |
| `--v5-danger` | `#ef4444` | — |
| `--v5-text-primary` | `#0f172a` | `#f1f5f9` |
| `--v5-text-secondary` | `#475569` | `#cbd5e1` |
| `--v5-text-muted` | `#94a3b8` | `#64748b` |

### Surfaces

| Token | Light | Dark |
|---|---|---|
| `--v5-bg-start` | `#f8fafc` | `#0c0f1a` |
| `--v5-bg-end` | `#eef2ff` | `#111827` |
| `--v5-surface` | `rgba(255,255,255,0.82)` | `rgba(15,23,42,0.78)` |
| `--v5-glass` | `rgba(255,255,255,0.48)` | `rgba(15,23,42,0.58)` |
| `--v5-glass-border` | `rgba(255,255,255,0.65)` | `rgba(255,255,255,0.08)` |

### Rayons

| Token | Valeur |
|---|---|
| `--v5-radius-sm` | `10px` |
| `--v5-radius-md` | `16px` |
| `--v5-radius-lg` | `22px` |
| `--v5-radius-xl` | `28px` |

### Ombres

| Token | Usage |
|---|---|
| `--v5-shadow-sm` | Éléments inline (badges, valeurs) |
| `--v5-shadow-md` | Cartes au repos, boutons |
| `--v5-shadow-lg` | Panneaux principaux |
| `--v5-shadow-xl` | Barre d'actions flottante |

### Transitions

| Token | Durée | Usage |
|---|---|---|
| `--v5-transition-fast` | `0.15s` | Hover immédiat |
| `--v5-transition-normal` | `0.25s` | Transitions standard |
| `--v5-transition-smooth` | `0.35s` | Animations fluides |

### Blur

| Token | Valeur |
|---|---|
| `--v5-blur-md` | `blur(16px) saturate(180%)` |
| `--v5-blur-lg` | `blur(24px) saturate(200%)` |

---

## 🔧 Personnalisation

Le thème V5 hérite de toutes les options de personnalisation de V2 :
- Couleurs (fond, bordure, illustration, groupes)
- Typographie (5 polices, tailles, espacement)
- Ombres (5 presets prédéfinis)
- Formes (18 formes de centre illustration)
- Motifs de fond (60+ patterns d'arrière-plan)
- Motifs de dos de carte (40+ patterns)
- Designs d'illustration (80+ styles avec filtrage)
- Icônes (56 options Phosphor Icons)
- Badges (taille, padding, rayon, couleurs)
- Interaction (lift, tilt au survol)

Les modifications sont sauvegardées indépendamment du V2.

---

## ⚡ Performance

- **Chargement conditionnel** : Le CSS V5 n'est chargé que sur `/admin/theme-v5`
- **Zero JS additionnel** : Réutilise le moteur JS existant + léger script vanilla pour le zoom
- **Cache busting** : CSS versionné par timestamp
- **backdrop-filter** : Accéléré GPU avec fallback Firefox
- **Animations CSS only** : Aucune dépendance JS pour les animations
- **`@supports` queries** : Dégradation gracieuse pour navigateurs sans backdrop-filter

---

## 🌐 Compatibilité

| Navigateur | Supporté | Notes |
|---|---|---|
| Chrome 90+ | ✅ | Complet |
| Firefox 90+ | ✅ | Fallback opaque si < 103 |
| Safari 15+ | ✅ | Complet |
| Edge 90+ | ✅ | Complet |
| Mobile Safari | ✅ | Responsive |
| Chrome Mobile | ✅ | Responsive |

> Note : `backdrop-filter` peut ne pas être supporté sur Firefox < 103. Un fallback opaque via `@supports` est appliqué automatiquement.

---

## 📝 Changelog

### V5.2 (2026-02-11) - Mise à jour Graphique & Zoom
- ✨ **Zoom Interactif** : Clic sur carte = zoom immersif avec backdrop flou
- ✨ **Styles d'écriture** : Sélecteur visuel avec 4 presets (Classique, Moderne, Élégant, Minimaliste)
- ✨ **Formes de carte** : Sélecteur visuel avec 4 presets (Arrondie, Carrée, Nette, Capsule)
- 🔧 **Contrôles Zoom** : Slider dédié, boutons +/-, molette souris, touche Échap
- 🎨 **UI Exclusif** : Sections V5 avec fond bleu/cyan subtil pour les distinguer

### V5.1 (2026-02-11)
- ✨ Ajout particules flottantes animées en fond
- ✨ Animation sparkle sur le bouton Magique
- ✨ Animation spin sur le bouton Réinitialiser
- ✨ Feature tag automatique dans la description header
- ✨ Dégradé animé sur la pilule de version active
- 🎨 Styles premium pour select/dropdown
- 🎨 Styles améliorés pour les démos de motifs
- 🎨 Preview header avec gradient text
- 🎨 Keyboard shortcut hints stylisés
- 🎨 Design current value indicator amélioré
- ♿ Help text avec bordure gauche indigo
- 🔧 Firefox `@supports` fallback pour backdrop-filter
- 🔧 Transitions dark mode fluides (0.4s)
- 🔧 JetBrains Mono + Inter 800 ajoutés aux polices
- 📌 V4 et V5 ajoutés au dropdown de navigation admin

### V5.0 (2026-02-11)
- 🚀 Version initiale basée sur V2
- 🎨 Glassmorphisme complet
- 🎨 Palette indigo/cyan
- 🎨 Animations d'apparition en cascade
- 🌙 Mode sombre avec variables dédiées
- ♿ Support WCAG (focus, reduced-motion, high-contrast)
- 📱 Responsive design 3 breakpoints
- 🖨 Styles d'impression
