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
        Schema::table('verbs', function (Blueprint $table) {
            $table->string('pattern')->nullable()->after('accent_color')->default('plain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verbs', function (Blueprint $table) {
            $table->dropColumn('pattern');
        });
    }
};
