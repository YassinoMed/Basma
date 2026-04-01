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
        Schema::create('conjugations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verb_id')->constrained()->onDelete('cascade');
            $table->string('tense'); // présent, imparfait, passé composé, futur, etc.
            $table->string('je');
            $table->string('tu');
            $table->string('il_elle_on');
            $table->string('nous');
            $table->string('vous');
            $table->string('ils_elles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conjugations');
    }
};
