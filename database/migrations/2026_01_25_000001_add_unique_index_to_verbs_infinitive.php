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

        $duplicates = DB::table('verbs')
            ->selectRaw('LOWER(infinitive) as infinitive_key, COUNT(*) as count')
            ->groupByRaw('LOWER(infinitive)')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $row) {
            $infinitiveKey = (string) ($row->infinitive_key ?? '');
            if ($infinitiveKey === '') {
                continue;
            }

            $ids = DB::table('verbs')
                ->whereRaw('LOWER(infinitive) = ?', [$infinitiveKey])
                ->orderBy('id')
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all();

            if (count($ids) < 2) {
                continue;
            }

            $keepId = array_shift($ids);
            $existingTenses = DB::table('conjugations')
                ->where('verb_id', $keepId)
                ->pluck('tense')
                ->map(fn ($t) => (string) $t)
                ->all();

            foreach ($ids as $duplicateId) {
                $conjugations = DB::table('conjugations')
                    ->where('verb_id', $duplicateId)
                    ->select(['id', 'tense'])
                    ->get();

                foreach ($conjugations as $conjugation) {
                    $tense = (string) ($conjugation->tense ?? '');
                    $conjugationId = (int) ($conjugation->id ?? 0);

                    if ($conjugationId <= 0 || $tense === '') {
                        continue;
                    }

                    if (in_array($tense, $existingTenses, true)) {
                        DB::table('conjugations')->where('id', $conjugationId)->delete();

                        continue;
                    }

                    DB::table('conjugations')->where('id', $conjugationId)->update(['verb_id' => $keepId]);
                    $existingTenses[] = $tense;
                }

                DB::table('verbs')->where('id', $duplicateId)->delete();
            }
        }

        if ($needsLowerUniqueIndex) {
            DB::statement('CREATE UNIQUE INDEX verbs_infinitive_unique_lower ON verbs (LOWER(infinitive))');

            return;
        }

        Schema::table('verbs', function (Blueprint $table) {
            $table->unique('infinitive');
        });
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        $needsLowerUniqueIndex = in_array($driver, ['sqlite', 'pgsql'], true);

        if ($needsLowerUniqueIndex) {
            DB::statement('DROP INDEX IF EXISTS verbs_infinitive_unique_lower');

            return;
        }

        Schema::table('verbs', function (Blueprint $table) {
            $table->dropUnique(['infinitive']);
        });
    }
};
