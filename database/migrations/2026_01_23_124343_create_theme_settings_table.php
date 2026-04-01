<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->timestamps();
        });

        // Insérer les valeurs par défaut
        DB::table('theme_settings')->insert([
            ['key' => '--color-primary', 'value' => '#1e3a5f'],
            ['key' => '--color-primary-light', 'value' => '#2d5480'],
            ['key' => '--color-accent', 'value' => '#5b9bd5'],
            ['key' => '--color-bg-primary', 'value' => '#faf9f7'],
            ['key' => '--color-group-1', 'value' => '#1e3a5f'],
            ['key' => '--color-group-2', 'value' => '#2d5a3d'],
            ['key' => '--color-group-3', 'value' => '#5a2d5a'],
            ['key' => '--radius-xl', 'value' => '16px'], // Rayon des cartes
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};
