<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        $needsLowerUniqueIndex = in_array($driver, ['sqlite', 'pgsql'], true);

        if (! Schema::hasColumn('verbs', 'suit')) {
            Schema::table('verbs', function (Blueprint $table) {
                $table->enum('suit', ['heart', 'spade', 'diamond', 'club'])->default('spade')->after('group');
            });
        }

        if ($needsLowerUniqueIndex) {
            DB::statement('DROP INDEX IF EXISTS verbs_infinitive_unique_lower');
            DB::statement('DROP INDEX IF EXISTS verbs_infinitive_suit_unique_lower');
            DB::statement('CREATE UNIQUE INDEX verbs_infinitive_suit_unique_lower ON verbs (LOWER(infinitive), suit)');

            return;
        }

        $databaseName = (string) (DB::selectOne('select database() as db')->db ?? '');
        if ($databaseName === '') {
            return;
        }

        try {
            DB::statement('ALTER TABLE verbs DROP INDEX verbs_infinitive_unique');
        } catch (\Throwable $e) {
            if (! str_contains($e->getMessage(), 'check that column/key exists')) {
                throw $e;
            }
        }

        try {
            DB::statement('ALTER TABLE verbs ADD UNIQUE INDEX verbs_infinitive_suit_unique (infinitive, suit)');
        } catch (\Throwable $e) {
            if (! str_contains($e->getMessage(), 'Duplicate key name')) {
                throw $e;
            }
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        $needsLowerUniqueIndex = in_array($driver, ['sqlite', 'pgsql'], true);

        if ($needsLowerUniqueIndex) {
            DB::statement('DROP INDEX IF EXISTS verbs_infinitive_suit_unique_lower');
            DB::statement('DROP INDEX IF EXISTS verbs_infinitive_unique_lower');
            DB::statement('CREATE UNIQUE INDEX verbs_infinitive_unique_lower ON verbs (LOWER(infinitive))');

            return;
        }

        $databaseName = (string) (DB::selectOne('select database() as db')->db ?? '');
        if ($databaseName === '') {
            return;
        }

        try {
            DB::statement('ALTER TABLE verbs DROP INDEX verbs_infinitive_suit_unique');
        } catch (\Throwable $e) {
            if (! str_contains($e->getMessage(), 'check that column/key exists')) {
                throw $e;
            }
        }

        try {
            DB::statement('ALTER TABLE verbs ADD UNIQUE INDEX verbs_infinitive_unique (infinitive)');
        } catch (\Throwable $e) {
            if (! str_contains($e->getMessage(), 'Duplicate key name')) {
                throw $e;
            }
        }
    }
};
