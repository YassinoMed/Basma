<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
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
            DB::statement('CREATE UNIQUE INDEX verbs_infinitive_suit_unique_lower ON verbs (LOWER(infinitive), suit)');

            return;
        }

        $databaseName = (string) (DB::selectOne('select database() as db')->db ?? '');
        $hasInfinitiveUnique = false;
        $hasInfinitiveSuitUnique = false;

        if ($databaseName !== '') {
            $indexRows = DB::select(
                'select index_name from information_schema.statistics where table_schema = ? and table_name = ? group by index_name',
                [$databaseName, 'verbs']
            );

            foreach ($indexRows as $row) {
                $name = (string) ($row->index_name ?? '');
                if ($name === 'verbs_infinitive_unique') {
                    $hasInfinitiveUnique = true;
                }
                if ($name === 'verbs_infinitive_suit_unique') {
                    $hasInfinitiveSuitUnique = true;
                }
            }
        }

        Schema::table('verbs', function (Blueprint $table) use ($hasInfinitiveUnique, $hasInfinitiveSuitUnique) {
            if ($hasInfinitiveUnique) {
                $table->dropUnique('verbs_infinitive_unique');
            }
            if (! $hasInfinitiveSuitUnique) {
                $table->unique(['infinitive', 'suit'], 'verbs_infinitive_suit_unique');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        $needsLowerUniqueIndex = in_array($driver, ['sqlite', 'pgsql'], true);
        $hasSuitColumn = Schema::hasColumn('verbs', 'suit');

        if ($needsLowerUniqueIndex) {
            DB::statement('DROP INDEX IF EXISTS verbs_infinitive_suit_unique_lower');
            DB::statement('DROP INDEX IF EXISTS verbs_infinitive_unique_lower');

            Schema::table('verbs', function (Blueprint $table) use ($hasSuitColumn) {
                if ($hasSuitColumn) {
                    $table->dropColumn('suit');
                }
            });

            DB::statement('CREATE UNIQUE INDEX verbs_infinitive_unique_lower ON verbs (LOWER(infinitive))');

            return;
        }

        Schema::table('verbs', function (Blueprint $table) use ($hasSuitColumn) {
            $table->dropUnique('verbs_infinitive_suit_unique');
            if ($hasSuitColumn) {
                $table->dropColumn('suit');
            }
        });

        $duplicates = DB::table('verbs')
            ->selectRaw('LOWER(infinitive) as infinitive_key, COUNT(*) as count')
            ->groupByRaw('LOWER(infinitive)')
            ->having('count', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            Schema::table('verbs', function (Blueprint $table) {
                $table->unique('infinitive');
            });
        }
    }
};
