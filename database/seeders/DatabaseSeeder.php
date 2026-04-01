<?php

namespace Database\Seeders;

use App\Models\ThemeSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed des verbes français avec conjugaisons
        $this->call([
            VerbSeeder::class,
        ]);

        ThemeSetting::query()->updateOrCreate(
            ['key' => 'theme_ui_version'],
            ['value' => '5']
        );

        foreach ([
            '--rami-v5-pronoun-color' => '#191970',
            '--rami-v5-index-verb-color' => '#64B4E6',
            '--rami-v5-verb-color' => '#0033A0',
            '--rami-v5-badge-bg-color' => '#191970',
            '--rami-v5-badge-text-color' => '#FFFFFF',
        ] as $key => $value) {
            ThemeSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('theme_settings_inline_css');
        Cache::forget('theme_icon_setting');
        Cache::forget('theme_card_back_pattern');

        if (app()->environment('local')) {
            User::query()->updateOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Admin',
                    'password' => Hash::make('password'),
                    'is_admin' => true,
                ]
            );
        }

        User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );
    }
}
