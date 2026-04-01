<?php

namespace App\Http\Controllers;

use App\Models\ThemeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThemeController extends Controller
{
    private const RAMI_SHADOW_PRESETS = [
        'soft' => [
            'card' => '0 3px 6px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.05), inset 0 0 0 1px rgba(255, 255, 255, 0.5)',
            'cardHover' => '0 14px 28px rgba(0, 0, 0, 0.12), 0 6px 12px rgba(0, 0, 0, 0.08), inset 0 0 0 1px rgba(255, 255, 255, 0.6)',
        ],
        'default' => [
            'card' => '0 4px 8px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06), inset 0 0 0 1px rgba(255, 255, 255, 0.5)',
            'cardHover' => '0 20px 40px rgba(0, 0, 0, 0.15), 0 8px 16px rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.6)',
        ],
        'strong' => [
            'card' => '0 6px 12px rgba(0, 0, 0, 0.12), 0 3px 6px rgba(0, 0, 0, 0.08), inset 0 0 0 1px rgba(255, 255, 255, 0.5)',
            'cardHover' => '0 26px 52px rgba(0, 0, 0, 0.18), 0 10px 20px rgba(0, 0, 0, 0.12), inset 0 0 0 1px rgba(255, 255, 255, 0.6)',
        ],
        'flat' => [
            'card' => '4px 4px 0px rgba(30, 58, 95, 0.2), inset 0 0 0 2px rgba(30, 58, 95, 0.1)',
            'cardHover' => '8px 8px 0px rgba(30, 58, 95, 0.3), inset 0 0 0 2px rgba(30, 58, 95, 0.2)',
        ],
        'inner' => [
            'card' => 'inset 2px 2px 6px rgba(0, 0, 0, 0.1), inset -2px -2px 6px rgba(255, 255, 255, 0.8)',
            'cardHover' => 'inset 4px 4px 12px rgba(0, 0, 0, 0.15), inset -4px -4px 12px rgba(255, 255, 255, 0.9)',
        ],
    ];

    private const RAMI_CENTER_PRESETS = [
        'circle' => [
            'illustrationSize' => '108px',
            'illustrationRadius' => '50%',
            'illustrationBorderWidth' => '1px',
            'illustrationShadow' => 'inset 0 1px 4px rgba(0, 0, 0, 0.03)',
        ],
        'rounded' => [
            'illustrationSize' => '120px',
            'illustrationRadius' => '18px',
            'illustrationBorderWidth' => '2px',
            'illustrationShadow' => '0 6px 14px rgba(0, 0, 0, 0.10), inset 0 0 0 1px rgba(255, 255, 255, 0.35)',
        ],
        'square' => [
            'illustrationSize' => '120px',
            'illustrationRadius' => '10px',
            'illustrationBorderWidth' => '2px',
            'illustrationShadow' => '0 4px 10px rgba(0, 0, 0, 0.10), inset 0 2px 6px rgba(0, 0, 0, 0.04)',
        ],
        'neumo' => [
            'illustrationSize' => '120px',
            'illustrationRadius' => '50%',
            'illustrationBorderWidth' => '0px',
            'illustrationShadow' => 'inset 6px 6px 12px rgba(0, 0, 0, 0.10), inset -6px -6px 12px rgba(255, 255, 255, 0.65)',
            'illustrationInnerInset' => '10px',
            'illustrationInnerBgStart' => 'rgba(255, 255, 255, 0.35)',
            'illustrationInnerBgEnd' => 'rgba(255, 255, 255, 0.05)',
        ],
        'glass' => [
            'illustrationSize' => '120px',
            'illustrationRadius' => '22px',
            'illustrationBorderWidth' => '1px',
            'illustrationShadow' => '0 14px 28px rgba(0, 0, 0, 0.12), inset 0 0 0 1px rgba(255, 255, 255, 0.45)',
            'illustrationInnerInset' => '8px',
            'illustrationInnerBgStart' => 'rgba(255, 255, 255, 0.35)',
            'illustrationInnerBgEnd' => 'rgba(255, 255, 255, 0.00)',
        ],
        'halo' => [
            'illustrationSize' => '120px',
            'illustrationRadius' => '50%',
            'illustrationBorderWidth' => '3px',
            'illustrationShadow' => '0 10px 24px rgba(0, 0, 0, 0.14), 0 0 0 6px rgba(255, 255, 255, 0.35)',
            'illustrationInnerInset' => '14px',
            'illustrationInnerBgStart' => 'rgba(255, 255, 255, 0.18)',
            'illustrationInnerBgEnd' => 'rgba(255, 255, 255, 0.00)',
        ],
        'frame' => [
            'illustrationSize' => '120px',
            'illustrationRadius' => '12px',
            'illustrationBorderWidth' => '4px',
            'illustrationShadow' => 'inset 0 0 0 2px rgba(30, 58, 95, 0.18), 0 10px 22px rgba(0, 0, 0, 0.10)',
            'illustrationInnerInset' => '8px',
            'illustrationInnerBgStart' => 'rgba(255, 255, 255, 0.20)',
            'illustrationInnerBgEnd' => 'rgba(255, 255, 255, 0.00)',
        ],
        'minimal' => [
            'illustrationSize' => '110px',
            'illustrationRadius' => '0px',
            'illustrationBorderWidth' => '1px',
            'illustrationShadow' => 'none',
            'illustrationInnerInset' => '0px',
            'illustrationInnerBgStart' => 'rgba(255, 255, 255, 0)',
            'illustrationInnerBgEnd' => 'rgba(255, 255, 255, 0)',
        ],
        'pill' => [
            'illustrationSize' => '120px',
            'illustrationRadius' => '0px',
            'illustrationBorderWidth' => '0px',
            'illustrationShadow' => '0 4px 12px rgba(0, 0, 0, 0.1)',
            'illustrationClipPath' => 'inset(25% 0 25% 0 round 100px)',
            'illustrationInnerBgStart' => '#f0efed',
            'illustrationInnerBgEnd' => '#e8e6e2',
        ],
        'squircle' => [
            'illustrationSize' => '120px',
            'illustrationRadius' => '22%',
            'illustrationBorderWidth' => '0px',
            'illustrationShadow' => '0 4px 12px rgba(0, 0, 0, 0.08), inset 0 2px 4px rgba(255, 255, 255, 0.5)',
            'illustrationClipPath' => 'none',
        ],
        'sharp' => [
            'illustrationSize' => '110px',
            'illustrationRadius' => '0px',
            'illustrationBorderWidth' => '2px',
            'illustrationShadow' => '0 4px 0 rgba(30, 58, 95, 0.1)',
            'illustrationClipPath' => 'none',
        ],
        'outline' => [
            'illustrationSize' => '120px',
            'illustrationRadius' => '50%',
            'illustrationBorderWidth' => '2px',
            'illustrationShadow' => 'none',
            'illustrationInnerBgStart' => 'transparent',
            'illustrationInnerBgEnd' => 'transparent',
            'illustrationClipPath' => 'none',
        ],
        'glow' => [
            'illustrationSize' => '120px',
            'illustrationRadius' => '50%',
            'illustrationBorderWidth' => '0px',
            'illustrationShadow' => '0 0 20px rgba(91, 155, 213, 0.4), inset 0 0 10px rgba(255, 255, 255, 0.8)',
            'illustrationInnerBgStart' => '#ffffff',
            'illustrationInnerBgEnd' => '#f0f8ff',
            'illustrationClipPath' => 'none',
        ],
        'frost' => [
            'illustrationSize' => '120px',
            'illustrationRadius' => '24px',
            'illustrationBorderWidth' => '1px',
            'illustrationShadow' => '0 8px 32px rgba(31, 38, 135, 0.07)',
            'illustrationInnerBgStart' => 'rgba(255, 255, 255, 0.6)',
            'illustrationInnerBgEnd' => 'rgba(255, 255, 255, 0.2)',
            'illustrationClipPath' => 'none',
            'illustrationBorderColor' => 'rgba(255, 255, 255, 0.4)',
        ],
        'hex' => [
            'illustrationSize' => '130px',
            'illustrationRadius' => '0px',
            'illustrationBorderWidth' => '0px',
            'illustrationShadow' => 'none', // Shadow doesn't work well with clip-path on container
            'illustrationClipPath' => 'polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%)',
        ],
        'diamond' => [
            'illustrationSize' => '130px',
            'illustrationRadius' => '0px',
            'illustrationBorderWidth' => '0px',
            'illustrationShadow' => 'none',
            'illustrationClipPath' => 'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)',
        ],
        'star' => [
            'illustrationSize' => '130px',
            'illustrationRadius' => '0px',
            'illustrationBorderWidth' => '0px',
            'illustrationShadow' => 'none',
            'illustrationClipPath' => 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)',
        ],
        'blob' => [
            'illustrationSize' => '130px',
            'illustrationRadius' => '60% 40% 30% 70% / 60% 30% 70% 40%',
            'illustrationBorderWidth' => '2px',
            'illustrationShadow' => '0 8px 16px rgba(0,0,0,0.06)',
            'illustrationClipPath' => 'none',
        ],
    ];

    private const RAMI_ICONS = [
        'ph-user-circle-gear',
        'ph-graduation-cap',
        'ph-book-open-text',
        'ph-translate',
        'ph-student',
        'ph-chalkboard-teacher',
        'ph-brain',
        'ph-lightning',
        'ph-lightbulb',
        'ph-star',
        'ph-crown',
        'ph-rocket',
        'ph-target',
        'ph-pencil-circle',
        'ph-chat-circle-text',
        // Nouveaux ajouts
        'ph-heart',
        'ph-sun',
        'ph-moon',
        'ph-cloud',
        'ph-snowflake',
        'ph-fire',
        'ph-drop',
        'ph-tree',
        'ph-flower',
        'ph-paw-print',
        'ph-bird',
        'ph-cat',
        'ph-dog',
        'ph-fish',
        'ph-ghost',
        'ph-alien',
        'ph-robot',
        'ph-soccer-ball',
        'ph-basketball',
        'ph-football',
        'ph-game-controller',
        'ph-music-notes',
        'ph-paint-brush',
        'ph-camera',
        'ph-video-camera',
        'ph-microphone',
        'ph-palette',
        'ph-magic-wand',
        'ph-medal',
        'ph-trophy',
        'ph-flag',
        'ph-map-pin',
        'ph-compass',
        'ph-globe',
        'ph-desktop',
        'ph-laptop',
        'ph-mobile',
        'ph-bookmark',
        'ph-tag',
        'ph-gift',
    ];

    public function edit()
    {
        $defaults = $this->defaultThemeSettings();
        $saved = ThemeSetting::query()
            ->whereIn('key', array_keys($defaults))
            ->pluck('value', 'key')
            ->all();

        $settings = array_merge($defaults, $saved);

        return view('admin.theme', [
            'themeSettings' => $settings,
            'themeDefaults' => $defaults,
            'ramiShadowPresets' => self::RAMI_SHADOW_PRESETS,
            'ramiCenterPresets' => self::RAMI_CENTER_PRESETS,
            'ramiIcons' => self::RAMI_ICONS,
        ]);
    }

    public function editV2()
    {
        $defaults = $this->defaultThemeSettingsV2();
        $saved = ThemeSetting::query()
            ->whereIn('key', $this->toVersionedKeys(2, array_keys($defaults)))
            ->pluck('value', 'key')
            ->all();

        $settings = array_merge($defaults, $this->mapFromVersionedSettings(2, $saved));

        return view('admin.theme', [
            'themeSettings' => $settings,
            'themeDefaults' => $defaults,
            'ramiShadowPresets' => self::RAMI_SHADOW_PRESETS,
            'ramiCenterPresets' => self::RAMI_CENTER_PRESETS,
            'ramiIcons' => self::RAMI_ICONS,
            'themeUiVersion' => 2,
            'mainClass' => 'main-content--wide',
        ]);
    }

    public function editV3()
    {
        $defaults = $this->defaultThemeSettings();
        $saved = ThemeSetting::query()
            ->whereIn('key', array_keys($defaults))
            ->pluck('value', 'key')
            ->all();

        $settings = array_merge($defaults, $saved);

        return view('admin.theme', [
            'themeSettings' => $settings,
            'themeDefaults' => $defaults,
            'ramiShadowPresets' => self::RAMI_SHADOW_PRESETS,
            'ramiCenterPresets' => self::RAMI_CENTER_PRESETS,
            'ramiIcons' => self::RAMI_ICONS,
            'themeUiVersion' => 3,
        ]);
    }

    public function editV4()
    {
        $defaults = $this->defaultThemeSettings();
        $saved = ThemeSetting::query()
            ->whereIn('key', array_keys($defaults))
            ->pluck('value', 'key')
            ->all();

        $settings = array_merge($defaults, $saved);

        return view('admin.theme', [
            'themeSettings' => $settings,
            'themeDefaults' => $defaults,
            'ramiShadowPresets' => self::RAMI_SHADOW_PRESETS,
            'ramiCenterPresets' => self::RAMI_CENTER_PRESETS,
            'ramiIcons' => self::RAMI_ICONS,
            'themeUiVersion' => 4,
            'mainClass' => 'main-content--wide',
        ]);
    }

    public function editV5()
    {
        $defaults = $this->defaultThemeSettingsV5();
        $saved = ThemeSetting::query()
            ->whereIn('key', $this->toVersionedKeys(5, array_keys($defaults)))
            ->pluck('value', 'key')
            ->all();

        $settings = array_merge($defaults, $this->mapFromVersionedSettings(5, $saved));

        return view('admin.theme', [
            'themeSettings' => $settings,
            'themeDefaults' => $defaults,
            'ramiShadowPresets' => self::RAMI_SHADOW_PRESETS,
            'ramiCenterPresets' => self::RAMI_CENTER_PRESETS,
            'ramiIcons' => self::RAMI_ICONS,
            'themeUiVersion' => 5,
            'mainClass' => 'main-content--wide',
        ]);
    }

    public function editV6()
    {
        $defaults = $this->defaultThemeSettingsV6();
        $saved = ThemeSetting::query()
            ->whereIn('key', $this->toVersionedKeys(6, array_keys($defaults)))
            ->pluck('value', 'key')
            ->all();

        $settings = array_merge($defaults, $this->mapFromVersionedSettings(6, $saved));

        return view('admin.theme', [
            'themeSettings' => $settings,
            'themeDefaults' => $defaults,
            'ramiShadowPresets' => self::RAMI_SHADOW_PRESETS,
            'ramiCenterPresets' => self::RAMI_CENTER_PRESETS,
            'ramiIcons' => self::RAMI_ICONS,
            'themeUiVersion' => 6,
            'mainClass' => 'main-content--wide',
        ]);
    }

    public function editV7()
    {
        $defaults = $this->defaultThemeSettingsV7();
        $saved = ThemeSetting::query()
            ->whereIn('key', $this->toVersionedKeys(7, array_keys($defaults)))
            ->pluck('value', 'key')
            ->all();

        $settings = array_merge($defaults, $this->mapFromVersionedSettings(7, $saved));

        return view('admin.theme', [
            'themeSettings' => $settings,
            'themeDefaults' => $defaults,
            'ramiShadowPresets' => self::RAMI_SHADOW_PRESETS,
            'ramiCenterPresets' => self::RAMI_CENTER_PRESETS,
            'ramiIcons' => self::RAMI_ICONS,
            'themeUiVersion' => 7,
            'mainClass' => 'main-content--wide',
        ]);
    }

    /**
     * Update theme settings.
     */
    public function update(Request $request)
    {
        $settings = $request->input('settings');
        $uiVersion = (int) $request->input('uiVersion', 1);

        if (! $settings || ! is_array($settings)) {
            return response()->json(['error' => 'Format invalide'], 400);
        }

        $defaults = match ($uiVersion) {
            7 => $this->defaultThemeSettingsV7(),
            6 => $this->defaultThemeSettingsV6(),
            5 => $this->defaultThemeSettingsV5(),
            2 => $this->defaultThemeSettingsV2(),
            default => $this->defaultThemeSettings(),
        };
        $allowedKeys = array_keys($defaults);

        foreach ($settings as $key => $value) {
            if (! is_string($key) || ! in_array($key, $allowedKeys, true)) {
                continue;
            }

            $value = is_string($value) ? trim($value) : '';

            if ($value === '' || ! $this->isAllowedCssValue($key, $value)) {
                continue;
            }

            ThemeSetting::query()->updateOrCreate(
                ['key' => $this->toVersionedKey($uiVersion, $key)],
                ['value' => $value]
            );
        }

        // Persist the active theme version so card pages can read it
        ThemeSetting::query()->updateOrCreate(
            ['key' => 'theme_ui_version'],
            ['value' => (string) $uiVersion]
        );

        Cache::forget('theme_settings_inline_css');
        Cache::forget('theme_icon_setting');
        Cache::forget('theme_card_back_pattern');

        return response()->json(['success' => true, 'message' => 'Thème mis à jour avec succès']);
    }

    /**
     * Reset theme to default.
     */
    public function reset()
    {
        $uiVersion = (int) request()->input('uiVersion', 1);
        $defaults = match ($uiVersion) {
            7 => $this->defaultThemeSettingsV7(),
            6 => $this->defaultThemeSettingsV6(),
            5 => $this->defaultThemeSettingsV5(),
            2 => $this->defaultThemeSettingsV2(),
            default => $this->defaultThemeSettings(),
        };

        foreach ($defaults as $key => $value) {
            ThemeSetting::query()->updateOrCreate(
                ['key' => $this->toVersionedKey($uiVersion, $key)],
                ['value' => $value]
            );
        }

        Cache::forget('theme_settings_inline_css');
        Cache::forget('theme_icon_setting');
        Cache::forget('theme_card_back_pattern');

        return response()->json(['success' => true]);
    }

    private function defaultThemeSettings(): array
    {
        return [
            '--rami-card-bg' => '#faf8f5',
            '--rami-bg-circles-strength' => '12%',
            '--rami-bg-rectangles-strength' => '9%',
            '--rami-card-border-color' => '#3a3a3a',
            '--rami-card-border-width' => '3px',
            '--rami-card-border-style' => 'solid',
            '--rami-card-radius' => '16px',
            '--rami-card-radius-tl' => '16px',
            '--rami-card-radius-tr' => '16px',
            '--rami-card-radius-br' => '16px',
            '--rami-card-radius-bl' => '16px',
            '--rami-card-width' => '200px',
            '--rami-card-height' => '280px',
            '--rami-card-width-large' => '320px',
            '--rami-card-height-large' => '448px',
            '--rami-card-shadow' => self::RAMI_SHADOW_PRESETS['default']['card'],
            '--rami-card-shadow-hover' => self::RAMI_SHADOW_PRESETS['default']['cardHover'],
            '--rami-card-hover-lift' => '8px',
            '--rami-card-hover-tilt' => '5deg',
            '--rami-noise-opacity' => '0.03',
            '--rami-pattern-color' => 'rgba(30, 58, 95, 0.03)',
            '--rami-pattern-inset' => '8px',
            '--rami-pattern-inset-large' => '12px',
            '--rami-illustration-bg-start' => '#f0efed',
            '--rami-illustration-bg-end' => '#e8e6e2',
            '--rami-illustration-border-color' => 'rgba(30, 58, 95, 0.07)',
            '--rami-illustration-design' => 'none',
            '--rami-illustration-size' => self::RAMI_CENTER_PRESETS['circle']['illustrationSize'],
            '--rami-illustration-radius' => self::RAMI_CENTER_PRESETS['circle']['illustrationRadius'],
            '--rami-illustration-border-width' => self::RAMI_CENTER_PRESETS['circle']['illustrationBorderWidth'],
            '--rami-illustration-shadow' => self::RAMI_CENTER_PRESETS['circle']['illustrationShadow'],
            '--rami-center-top' => '48%',
            '--rami-center-padding' => '10px',
            '--rami-illustration-inner-inset' => '0px',
            '--rami-illustration-inner-bg-start' => 'rgba(255, 255, 255, 0)',
            '--rami-illustration-inner-bg-end' => 'rgba(255, 255, 255, 0)',
            '--rami-illustration-clip-path' => 'none',
            '--rami-text-muted-color' => '#7a7a7a',
            '--rami-pronoun-color' => 'var(--rami-accent-color)',
            '--rami-verb-color' => 'var(--rami-accent-color)',
            '--rami-index-verb-color' => 'var(--rami-text-muted-color)',
            '--rami-verb-sub-color' => 'var(--rami-text-muted-color)',
            '--rami-infinitive-color' => 'var(--rami-text-muted-color)',
            '--rami-index-padding' => '12px',
            '--rami-index-padding-large' => '16px',
            '--rami-index-pronoun-size' => '30px',
            '--rami-index-pronoun-size-large' => '46px',
            '--rami-index-verb-size' => '10px',
            '--rami-index-verb-size-large' => '12px',
            '--rami-verb-size' => '22px',
            '--rami-verb-size-large' => '32px',
            '--rami-verb-letter-spacing' => '0.02em',
            '--rami-infinitive-letter-spacing' => '0.2em',
            '--rami-verb-bottom' => '34px',
            '--rami-verb-bottom-large' => '54px',
            '--rami-badge-font-size' => '8px',
            '--rami-badge-font-size-large' => '10px',
            '--rami-badge-radius' => '10px',
            '--rami-badge-radius-large' => '12px',
            '--rami-badge-padding-x' => '8px',
            '--rami-badge-padding-x-large' => '10px',
            '--rami-badge-padding-y' => '3px',
            '--rami-badge-padding-y-large' => '4px',
            '--rami-badge-bg-color' => 'var(--rami-accent-color)',
            '--rami-badge-text-color' => '#ffffff',
            '--rami-suit-size' => '1.35em',
            '--rami-group-1-color' => '#1e3a5f',
            '--rami-group-2-color' => '#2d5a3d',
            '--rami-group-3-color' => '#5a2d5a',
            '--rami-font-family' => 'Inter, sans-serif',
            '--rami-selected-pattern' => 'plain',
            '--rami-card-back-pattern' => 'diamonds',
        ];
    }

    private function defaultThemeSettingsV2(): array
    {
        $defaults = $this->defaultThemeSettings();

        $defaults['--rami-card-border-width'] = '2px';
        $defaults['--rami-index-pronoun-size'] = '28px';
        $defaults['--rami-index-pronoun-size-large'] = '42px';
        $defaults['--rami-verb-bottom'] = '45px';
        $defaults['--rami-verb-bottom-large'] = '60px';

        return $defaults;
    }

    private function defaultThemeSettingsV5(): array
    {
        $defaults = $this->defaultThemeSettingsV2();

        // Modern V5 overrides - more refined, glassmorphic feel
        $defaults['--rami-card-bg'] = '#ffffff';
        $defaults['--rami-card-border-color'] = 'rgba(0, 0, 0, 0.08)';
        $defaults['--rami-card-border-width'] = '1px';
        $defaults['--rami-card-border-style'] = 'solid';
        $defaults['--rami-card-radius'] = '20px';
        $defaults['--rami-card-radius-tl'] = '20px';
        $defaults['--rami-card-radius-tr'] = '20px';
        $defaults['--rami-card-radius-br'] = '20px';
        $defaults['--rami-card-radius-bl'] = '20px';
        $defaults['--rami-card-shadow'] = self::RAMI_SHADOW_PRESETS['soft']['card'];
        $defaults['--rami-card-shadow-hover'] = self::RAMI_SHADOW_PRESETS['soft']['cardHover'];
        $defaults['--rami-card-hover-lift'] = '12px';
        $defaults['--rami-card-hover-tilt'] = '3deg';
        $defaults['--rami-noise-opacity'] = '0.01';
        $defaults['--rami-illustration-bg-start'] = '#f8f9ff';
        $defaults['--rami-illustration-bg-end'] = '#eef2ff';
        $defaults['--rami-illustration-border-color'] = 'rgba(99, 102, 241, 0.08)';
        $defaults['--rami-font-family'] = 'Inter, sans-serif';
        $defaults['--rami-group-1-color'] = '#4f46e5';
        $defaults['--rami-group-2-color'] = '#059669';
        $defaults['--rami-group-3-color'] = '#7c3aed';
        $defaults['--rami-badge-bg-color'] = 'var(--rami-accent-color)';
        $defaults['--rami-text-muted-color'] = '#94a3b8';
        $defaults['--rami-verb-letter-spacing'] = '0.04em';

        // V5-exclusive: Writing style
        $defaults['--rami-v5-writing-style'] = 'modern';

        // V5-exclusive: Card shape preset
        $defaults['--rami-v5-card-shape'] = 'rounded';

        // V5-exclusive: Zoom level for card preview
        $defaults['--rami-v5-zoom-level'] = '3';

        return $defaults;
    }

    private function defaultThemeSettingsV6(): array
    {
        $defaults = $this->defaultThemeSettingsV5();

        // V6 Aurora — refined, deeper palette with perfect card spacing
        $defaults['--rami-card-bg'] = '#ffffff';
        $defaults['--rami-card-border-color'] = 'rgba(0, 0, 0, 0.06)';
        $defaults['--rami-card-border-width'] = '1px';
        $defaults['--rami-card-radius'] = '24px';
        $defaults['--rami-card-radius-tl'] = '24px';
        $defaults['--rami-card-radius-tr'] = '24px';
        $defaults['--rami-card-radius-br'] = '24px';
        $defaults['--rami-card-radius-bl'] = '24px';
        $defaults['--rami-card-shadow'] = self::RAMI_SHADOW_PRESETS['soft']['card'];
        $defaults['--rami-card-shadow-hover'] = self::RAMI_SHADOW_PRESETS['soft']['cardHover'];
        $defaults['--rami-card-hover-lift'] = '14px';
        $defaults['--rami-card-hover-tilt'] = '2deg';
        $defaults['--rami-noise-opacity'] = '0.005';
        $defaults['--rami-illustration-bg-start'] = '#f0f4ff';
        $defaults['--rami-illustration-bg-end'] = '#e8ecff';
        $defaults['--rami-illustration-border-color'] = 'rgba(99, 102, 241, 0.06)';
        $defaults['--rami-font-family'] = 'Inter, sans-serif';
        $defaults['--rami-group-1-color'] = '#6366f1';
        $defaults['--rami-group-2-color'] = '#10b981';
        $defaults['--rami-group-3-color'] = '#8b5cf6';
        $defaults['--rami-text-muted-color'] = '#94a3b8';
        $defaults['--rami-verb-letter-spacing'] = '0.05em';
        $defaults['--rami-center-top'] = '44%'; // Fix: move illustration up to prevent overlap

        // V6-exclusive settings
        $defaults['--rami-v6-card-glow'] = 'subtle';
        $defaults['--rami-v6-typography-weight'] = 'medium';
        $defaults['--rami-v6-animation-intensity'] = 'normal';

        // Keep V5 exclusive settings for backward compatibility
        $defaults['--rami-v5-writing-style'] = 'modern';
        $defaults['--rami-v5-card-shape'] = 'rounded';
        $defaults['--rami-v5-zoom-level'] = '3';

        return $defaults;
    }

    private function defaultThemeSettingsV7(): array
    {
        $defaults = $this->defaultThemeSettingsV2();

        // V7 Premium Vintage — cream, navy, gold
        $defaults['--rami-card-bg'] = '#F9F7F0';
        $defaults['--rami-card-border-color'] = '#bfa05f';
        $defaults['--rami-card-border-width'] = '2px';
        $defaults['--rami-card-border-style'] = 'solid';
        $defaults['--rami-card-radius'] = '15px';
        $defaults['--rami-card-radius-tl'] = '15px';
        $defaults['--rami-card-radius-tr'] = '15px';
        $defaults['--rami-card-radius-br'] = '15px';
        $defaults['--rami-card-radius-bl'] = '15px';
        $defaults['--rami-card-shadow'] = self::RAMI_SHADOW_PRESETS['strong']['card'];
        $defaults['--rami-card-shadow-hover'] = self::RAMI_SHADOW_PRESETS['strong']['cardHover'];
        $defaults['--rami-card-hover-lift'] = '10px';
        $defaults['--rami-card-hover-tilt'] = '3deg';
        $defaults['--rami-noise-opacity'] = '0.04';
        $defaults['--rami-illustration-bg-start'] = '#f0ead6';
        $defaults['--rami-illustration-bg-end'] = '#e8dfc8';
        $defaults['--rami-illustration-border-color'] = '#bfa05f';
        $defaults['--rami-font-family'] = "'Playfair Display', serif";
        $defaults['--rami-group-1-color'] = '#0f3057';
        $defaults['--rami-group-2-color'] = '#2d5a3d';
        $defaults['--rami-group-3-color'] = '#5a2d5a';
        $defaults['--rami-badge-bg-color'] = '#0f3057';
        $defaults['--rami-text-muted-color'] = '#8a7e6b';
        $defaults['--rami-verb-letter-spacing'] = '0.08em';
        $defaults['--rami-center-top'] = '46%';

        // V7-exclusive
        $defaults['--rami-v7-ornament-style'] = 'fleuron';
        $defaults['--rami-v7-gold-intensity'] = 'classic';
        $defaults['--rami-v5-writing-style'] = 'classic';
        $defaults['--rami-v5-card-shape'] = 'rounded';
        $defaults['--rami-v5-zoom-level'] = '3';

        return $defaults;
    }

    private function toVersionedKey(int $uiVersion, string $key): string
    {
        if (! in_array($uiVersion, [2, 5, 6, 7], true)) {
            return $key;
        }

        if (! str_starts_with($key, '--rami-')) {
            return $key;
        }

        // If the key already carries the correct version prefix, return as-is
        // e.g. --rami-v5-writing-style should NOT become --rami-v5-v5-writing-style
        $versionPrefix = '--rami-v'.$uiVersion.'-';
        if (str_starts_with($key, $versionPrefix)) {
            return $key;
        }

        // Also skip keys that carry ANY version prefix (e.g. --rami-v5-* when saving for v6)
        if (preg_match('/^--rami-v\d+-/', $key) === 1) {
            return $key;
        }

        return '--rami-v'.$uiVersion.'-'.substr($key, 7);
    }

    private function toVersionedKeys(int $uiVersion, array $keys): array
    {
        $out = [];
        foreach ($keys as $key) {
            if (! is_string($key)) {
                continue;
            }
            $out[] = $this->toVersionedKey($uiVersion, $key);
        }

        return $out;
    }

    private function mapFromVersionedSettings(int $uiVersion, array $saved): array
    {
        if (! in_array($uiVersion, [2, 5, 6, 7], true)) {
            return $saved;
        }

        $prefix = '--rami-v'.$uiVersion.'-';
        $prefixLen = strlen($prefix);

        // Gather the canonical default keys so we know which keys are
        // version-exclusive (e.g. --rami-v5-writing-style) vs normal
        // (e.g. --rami-card-bg stored as --rami-v5-card-bg).
        $defaults = match ($uiVersion) {
            7 => $this->defaultThemeSettingsV7(),
            6 => $this->defaultThemeSettingsV6(),
            5 => $this->defaultThemeSettingsV5(),
            2 => $this->defaultThemeSettingsV2(),
            default => $this->defaultThemeSettings(),
        };

        $out = [];
        foreach ($saved as $key => $value) {
            if (! is_string($key) || ! is_string($value)) {
                continue;
            }

            if (! str_starts_with($key, $prefix)) {
                continue;
            }

            // Attempt the normal un-prefix: --rami-v5-card-bg → --rami-card-bg
            $unversioned = '--rami-'.substr($key, $prefixLen);

            // If the un-versioned key exists in defaults, use it
            if (array_key_exists($unversioned, $defaults)) {
                $out[$unversioned] = $value;

                continue;
            }

            // Otherwise, the DB key itself may be a version-exclusive canonical key
            // e.g. --rami-v5-writing-style (stored as-is, maps back to itself)
            if (array_key_exists($key, $defaults)) {
                $out[$key] = $value;

                continue;
            }

            // Fallback: legacy double-prefixed keys (--rami-v5-v5-writing-style)
            // Try stripping the double prefix
            $doublePrefix = '--rami-v'.$uiVersion.'-v'.$uiVersion.'-';
            if (str_starts_with($key, $doublePrefix)) {
                $canonicalKey = '--rami-v'.$uiVersion.'-'.substr($key, strlen($doublePrefix));
                if (array_key_exists($canonicalKey, $defaults)) {
                    $out[$canonicalKey] = $value;

                    continue;
                }
            }

            // Default: use the un-versioned form
            $out[$unversioned] = $value;
        }

        return $out;
    }

    private function isAllowedCssValue(string $key, string $value): bool
    {
        if (preg_match('/[;{}]/', $value) === 1) {
            return false;
        }

        if (strlen($value) > 200) {
            return false;
        }

        if (in_array($key, [
            '--rami-pronoun-color',
            '--rami-verb-color',
            '--rami-index-verb-color',
            '--rami-verb-sub-color',
            '--rami-infinitive-color',
        ], true)) {
            if (in_array($value, ['var(--rami-accent-color)', 'var(--rami-text-muted-color)'], true)) {
                return true;
            }

            return $this->isColorValue($value);
        }

        if (in_array($key, [
            '--rami-card-bg',
            '--rami-card-border-color',
            '--rami-pattern-color',
            '--rami-illustration-bg-start',
            '--rami-illustration-bg-end',
            '--rami-illustration-border-color',
            '--rami-illustration-design',
            '--rami-illustration-inner-bg-start',
            '--rami-illustration-inner-bg-end',
            '--rami-illustration-clip-path',
            '--rami-text-muted-color',
            '--rami-badge-text-color',
            '--rami-group-1-color',
            '--rami-group-2-color',
            '--rami-group-3-color',
            '--rami-font-family', // Ajouté ici
            '--rami-selected-pattern',
            '--rami-card-back-pattern',
        ], true)) {
            return true; // On accepte les strings safe pour ces valeurs (couleurs ou font names)
        }

        if ($key === '--rami-card-border-style') {
            return in_array($value, ['solid', 'dashed', 'dotted', 'double'], true);
        }

        if ($key === '--rami-badge-bg-color') {
            if ($value === 'var(--rami-accent-color)') {
                return true;
            }

            return $this->isColorValue($value);
        }

        if (in_array($key, [
            '--rami-card-radius',
            '--rami-card-border-width',
            '--rami-card-hover-lift',
            '--rami-card-width',
            '--rami-card-height',
            '--rami-card-width-large',
            '--rami-card-height-large',
            '--rami-pattern-inset',
            '--rami-pattern-inset-large',
            '--rami-index-padding',
            '--rami-index-padding-large',
            '--rami-index-pronoun-size',
            '--rami-index-pronoun-size-large',
            '--rami-index-verb-size',
            '--rami-index-verb-size-large',
            '--rami-verb-size',
            '--rami-verb-size-large',
            '--rami-verb-letter-spacing',
            '--rami-infinitive-letter-spacing',
            '--rami-verb-bottom',
            '--rami-verb-bottom-large',
            '--rami-badge-font-size',
            '--rami-badge-font-size-large',
            '--rami-badge-radius',
            '--rami-badge-radius-large',
            '--rami-badge-padding-x',
            '--rami-badge-padding-x-large',
            '--rami-badge-padding-y',
            '--rami-badge-padding-y-large',
            '--rami-center-padding',
            '--rami-center-top',
            '--rami-suit-size',
        ], true)) {
            return (bool) preg_match('/^\d+(\.\d+)?(px|rem|em|%)$/', $value);
        }

        if (in_array($key, ['--rami-card-hover-tilt'], true)) {
            return (bool) preg_match('/^\d+(\.\d+)?deg$/', $value);
        }

        if (in_array($key, ['--rami-illustration-size'], true)) {
            return (bool) preg_match('/^\d+(\.\d+)?(px|rem|em)$/', $value);
        }

        if (in_array($key, ['--rami-illustration-radius'], true)) {
            return (bool) preg_match('/^\d+(\.\d+)?(px|rem|em|%)$/', $value);
        }

        if (in_array($key, ['--rami-illustration-border-width'], true)) {
            return (bool) preg_match('/^\d+(\.\d+)?px$/', $value);
        }

        if (in_array($key, ['--rami-illustration-inner-inset'], true)) {
            return (bool) preg_match('/^\d+(\.\d+)?px$/', $value);
        }

        if (in_array($key, ['--rami-bg-circles-strength', '--rami-bg-rectangles-strength'], true)) {
            return (bool) preg_match('/^\d+(\.\d+)?%$/', $value);
        }

        if (in_array($key, [
            '--rami-card-radius-tl',
            '--rami-card-radius-tr',
            '--rami-card-radius-br',
            '--rami-card-radius-bl',
        ], true)) {
            return (bool) preg_match('/^\d+(\.\d+)?px$/', $value);
        }

        if (in_array($key, ['--rami-noise-opacity'], true)) {
            return (bool) preg_match('/^(0(\.\d+)?|1(\.0+)?)$/', $value);
        }

        if (in_array($key, ['--rami-card-shadow', '--rami-card-shadow-hover'], true)) {
            $allowed = [];
            foreach (self::RAMI_SHADOW_PRESETS as $preset) {
                $allowed[] = $preset['card'];
                $allowed[] = $preset['cardHover'];
            }

            return in_array($value, $allowed, true);
        }

        if (in_array($key, ['--rami-illustration-shadow'], true)) {
            $allowed = [];
            foreach (self::RAMI_CENTER_PRESETS as $preset) {
                $allowed[] = $preset['illustrationShadow'];
            }

            return in_array($value, $allowed, true);
        }

        // V5-exclusive settings
        if ($key === '--rami-v5-writing-style') {
            return in_array($value, ['classic', 'modern', 'elegant', 'minimalist', 'handwritten', 'mono', 'compact'], true);
        }

        if ($key === '--rami-v5-card-shape') {
            return in_array($value, ['rounded', 'square', 'sharp', 'pill'], true);
        }

        if ($key === '--rami-v5-zoom-level') {
            return (bool) preg_match('/^\d+(\.\d+)?$/', $value);
        }

        // V6-exclusive settings
        if ($key === '--rami-v6-card-glow') {
            return in_array($value, ['none', 'subtle', 'intense'], true);
        }

        if ($key === '--rami-v6-typography-weight') {
            return in_array($value, ['light', 'medium', 'bold'], true);
        }

        if ($key === '--rami-v6-animation-intensity') {
            return in_array($value, ['none', 'subtle', 'normal', 'high'], true);
        }

        // V7-exclusive settings
        if ($key === '--rami-v7-ornament-style') {
            return in_array($value, ['minimal', 'fleuron', 'royal'], true);
        }

        if ($key === '--rami-v7-gold-intensity') {
            return in_array($value, ['muted', 'classic', 'rich'], true);
        }

        return false;
    }

    private function isColorValue(string $value): bool
    {
        if (preg_match('/^#[0-9a-fA-F]{3}([0-9a-fA-F]{3})?([0-9a-fA-F]{2})?$/', $value) === 1) {
            return true;
        }

        if (preg_match('/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}(\s*,\s*(0(\.\d+)?|1(\.0+)?)\s*)?\)$/', $value) === 1) {
            return true;
        }

        if (preg_match('/^hsla?\(\s*\d{1,3}\s*,\s*\d{1,3}%\s*,\s*\d{1,3}%(\s*,\s*(0(\.\d+)?|1(\.0+)?)\s*)?\)$/', $value) === 1) {
            return true;
        }

        if (in_array($value, ['none', 'transparent'], true)) {
            return true;
        }

        // Allow clip-path basic shapes (unsafe regex but limited context)
        if (str_starts_with($value, 'polygon(') || str_starts_with($value, 'inset(') || str_starts_with($value, 'circle(')) {
            return true;
        }

        return false;
    }
}
