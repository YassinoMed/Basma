<?php

namespace App\Providers;

use App\Models\ThemeSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $data = $view->getData();

            static $activeThemeUiVersion = null;
            if ($activeThemeUiVersion === null) {
                $raw = ThemeSetting::query()->where('key', 'theme_ui_version')->value('value');
                $raw = is_string($raw) ? trim($raw) : '';
                $activeThemeUiVersion = (preg_match('/^\d+$/', $raw) === 1) ? (int) $raw : 1;
            }
            $view->with('activeThemeUiVersion', $activeThemeUiVersion);

            $inlineCss = Cache::rememberForever('theme_settings_inline_css', function () {
                $settings = ThemeSetting::query()
                    ->select(['key', 'value'])
                    ->get()
                    ->pluck('value', 'key')
                    ->all();

                $activeVersion = 1;
                $activeVersionRaw = $settings['theme_ui_version'] ?? null;
                if (is_string($activeVersionRaw) && preg_match('/^\d+$/', $activeVersionRaw) === 1) {
                    $activeVersion = (int) $activeVersionRaw;
                }

                $useVersioned = in_array($activeVersion, [2, 5, 6, 7], true);
                $versionPrefix = $useVersioned ? ('--rami-v'.$activeVersion.'-') : '';
                $versionPrefixLen = $useVersioned ? strlen($versionPrefix) : 0;

                $vars = [];

                foreach ($settings as $key => $value) {
                    if (! is_string($key) || ! is_string($value)) {
                        continue;
                    }

                    $key = trim($key);
                    $value = trim($value);

                    if (! str_starts_with($key, '--')) {
                        continue;
                    }

                    if (! str_starts_with($key, '--rami-')) {
                        continue;
                    }

                    if ($useVersioned) {
                        if (preg_match('/^--rami-v\d+-/', $key) !== 1) {
                            continue;
                        }

                        if (! str_starts_with($key, $versionPrefix)) {
                            continue;
                        }

                        $mappedKey = '--rami-'.substr($key, $versionPrefixLen);

                        if ($mappedKey === '--' || $value === '') {
                            continue;
                        }

                        if (strlen($mappedKey) > 64 || strlen($value) > 128) {
                            continue;
                        }

                        if (preg_match('/[;{}]/', $mappedKey.$value) === 1) {
                            continue;
                        }

                        $vars[$mappedKey] = $value;

                        continue;
                    }

                    if ($key === '--' || $value === '') {
                        continue;
                    }

                    if (strlen($key) > 64 || strlen($value) > 128) {
                        continue;
                    }

                    if (preg_match('/[;{}]/', $key.$value) === 1) {
                        continue;
                    }

                    $vars[$key] = $value;
                }

                if (! $vars) {
                    return '';
                }

                $pairs = [];
                foreach ($vars as $key => $value) {
                    $pairs[] = $key.': '.$value;
                }

                return '.rami-card,.rami-card-large,.print-rami-card,.print-main{'.implode(';', $pairs).';}';
            });

            $themeCardBackPattern = Cache::rememberForever('theme_card_back_pattern', function () {
                $activeVersionSetting = ThemeSetting::query()->where('key', 'theme_ui_version')->first();
                $activeVersionRaw = $activeVersionSetting ? trim((string) $activeVersionSetting->value) : '';
                $activeVersion = (preg_match('/^\d+$/', $activeVersionRaw) === 1) ? (int) $activeVersionRaw : 1;
                $useVersioned = in_array($activeVersion, [2, 5, 6, 7], true);

                $patternKey = '--rami-card-back-pattern';
                if ($useVersioned) {
                    $patternKey = '--rami-v'.$activeVersion.'-card-back-pattern';
                }

                $setting = ThemeSetting::query()->where('key', $patternKey)->first();
                if (! $setting && $useVersioned) {
                    $setting = ThemeSetting::query()->where('key', '--rami-card-back-pattern')->first();
                }
                $value = $setting ? trim((string) $setting->value) : 'plain';
                if ($value === '' || preg_match('/^[a-z0-9-]{1,32}$/', $value) !== 1) {
                    return 'plain';
                }

                return $value;
            });

            if (! array_key_exists('themeSettingsInlineCss', $data) || $data['themeSettingsInlineCss'] === '' || $data['themeSettingsInlineCss'] === null) {
                $view->with('themeSettingsInlineCss', $inlineCss);
            }

            if (! array_key_exists('themeCardBackPattern', $data) || $data['themeCardBackPattern'] === '' || $data['themeCardBackPattern'] === null) {
                $view->with('themeCardBackPattern', $themeCardBackPattern);
            }

            $view->with('adminThemeLinkEnabled', $this->isAdminThemeLinkEnabled());
        });
    }

    private function isAdminThemeLinkEnabled(): bool
    {
        $user = auth()->user();

        return (bool) ($user?->is_admin);
    }
}
