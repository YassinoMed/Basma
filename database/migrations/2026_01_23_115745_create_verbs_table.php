<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('verbs', function (Blueprint $table) {
            $table->id();
            $table->string('infinitive');
            $table->string('infinitive_translation')->nullable();
            $table->string('group')->default('1er'); // 1er, 2ème, 3ème groupe
            $table->string('illustration_path')->nullable();
            $table->string('illustration_description')->nullable();
            $table->string('theme_color')->default('#1e3a5f'); // Bleu marine par défaut
            $table->string('accent_color')->default('#5b9bd5'); // Bleu ciel
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verbs');
    }
};
