<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verbs', function (Blueprint $table) {
            $table->enum('suit', ['heart', 'spade', 'diamond', 'club'])->default('spade');
        });
    }

    public function down(): void
    {
        Schema::table('verbs', function (Blueprint $table) {
            $table->dropColumn('suit');
        });
    }
};
