<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Verb extends Model
{
    private const H_ASPIRE_EXCEPTIONS = [
        'haïr',
        'hacher',
        'haleter',
        'harceler',
        'hausser',
        'hennir',
        'hisser',
        'hoqueter',
        'huer',
        'hurler',
    ];

    protected static function booted(): void
    {
        static::saving(function (Verb $verb): void {
            $infinitive = mb_strtolower(trim((string) ($verb->attributes['infinitive'] ?? '')));
            if ($infinitive === 'aller') {
                $verb->attributes['group'] = '3ème';
            }

            $verb->attributes['suit'] = $verb->resolveSuitFromGroup(
                $verb->attributes['group'] ?? null,
                $verb->attributes['infinitive'] ?? null,
                $verb->attributes['suit'] ?? null
            );
        });
    }

    protected $fillable = [
        'infinitive',
        'infinitive_translation',
        'example_sentence',
        'group',
        'suit',
        'illustration_path',
        'illustration_description',
        'theme_color',
        'accent_color',
        'pattern',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getSuitAttribute(mixed $value): string
    {
        return $this->resolveSuitFromGroup(
            $this->attributes['group'] ?? null,
            $this->attributes['infinitive'] ?? null,
            is_string($value) ? $value : null
        );
    }

    public function getSuitSymbolAttribute(): string
    {
        return match ((string) $this->suit) {
            'heart' => '♥',
            'diamond' => '♦',
            'club' => '♣',
            default => '♠',
        };
    }

    public function getSuitTitleAttribute(): string
    {
        return match ((string) $this->suit) {
            'heart' => 'Cœur (♥)',
            'diamond' => 'Carreaux (♦)',
            'club' => 'Trèfle (♣)',
            default => 'Pique (♠)',
        };
    }

    private function resolveSuitFromGroup(?string $group, ?string $infinitive, ?string $fallback): string
    {
        $group = is_string($group) ? trim($group) : '';
        $infinitive = is_string($infinitive) ? mb_strtolower(trim($infinitive)) : '';

        if (in_array($infinitive, ['avoir', 'être', 'etre'], true)) {
            return 'spade';
        }

        return match ($group) {
            '1er' => 'heart',
            '2ème' => 'diamond',
            '3ème' => 'club',
            default => in_array((string) $fallback, ['heart', 'spade', 'diamond', 'club'], true) ? (string) $fallback : 'spade',
        };
    }

    public function conjugations(): HasMany
    {
        return $this->hasMany(Conjugation::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function getPresentConjugation(): ?Conjugation
    {
        if ($this->relationLoaded('conjugations')) {
            return $this->conjugations->firstWhere('tense', 'présent');
        }

        return $this->conjugations()->where('tense', 'présent')->first();
    }

    public function shouldElideJe(): bool
    {
        if ($this->isPronominal()) {
            return false;
        }

        $conjugation = $this->getPresentConjugation();
        $word = $conjugation ? mb_strtolower(trim((string) ($conjugation->je ?? ''))) : '';

        if ($word === '') {
            $word = mb_strtolower(trim((string) $this->infinitive));
        }

        if ($word === '') {
            return false;
        }

        $firstChar = mb_substr($word, 0, 1);
        if ($firstChar === '') {
            return false;
        }

        $vowelStarters = [
            'a', 'à', 'â', 'ä',
            'e', 'é', 'è', 'ê', 'ë',
            'i', 'î', 'ï',
            'o', 'ô', 'ö',
            'u', 'ù', 'û', 'ü',
            'y',
            'œ',
        ];

        if (in_array($firstChar, $vowelStarters, true)) {
            return true;
        }

        if ($firstChar === 'h') {
            $infinitive = mb_strtolower(trim((string) $this->infinitive));
            if ($infinitive !== '' && in_array($infinitive, self::H_ASPIRE_EXCEPTIONS, true)) {
                return false;
            }

            return true;
        }

        return false;
    }

    public function pronounLabel(string $pronounKey): string
    {
        $pronounKey = trim($pronounKey);

        $map = [
            'je' => 'JE',
            'tu' => 'TU',
            'il_elle_on' => 'IL',
            'nous' => 'NOUS',
            'vous' => 'VOUS',
            'ils_elles' => 'ILS',
        ];

        if ($pronounKey === 'je') {
            return $this->shouldElideJe() ? 'J’' : 'JE';
        }

        return $map[$pronounKey] ?? mb_strtoupper($pronounKey);
    }

    public function isPronominal(): bool
    {
        $infinitive = mb_strtolower(trim((string) $this->infinitive));
        if ($infinitive === '') {
            return false;
        }

        return str_starts_with($infinitive, 'se ') || str_starts_with($infinitive, "s'");
    }

    public function baseInfinitive(): string
    {
        $infinitive = trim((string) $this->infinitive);
        $lower = mb_strtolower($infinitive);

        if (str_starts_with($lower, 'se ')) {
            return trim(mb_substr($infinitive, 3));
        }

        if (str_starts_with($lower, "s'")) {
            return trim(mb_substr($infinitive, 2));
        }

        return $infinitive;
    }

    private function shouldElideBeforeWord(string $word): bool
    {
        $word = mb_strtolower(trim($word));
        if ($word === '') {
            return false;
        }

        $firstChar = mb_substr($word, 0, 1);
        if ($firstChar === '') {
            return false;
        }

        $vowelStarters = [
            'a', 'à', 'â', 'ä',
            'e', 'é', 'è', 'ê', 'ë',
            'i', 'î', 'ï',
            'o', 'ô', 'ö',
            'u', 'ù', 'û', 'ü',
            'y',
            'œ',
        ];

        if (in_array($firstChar, $vowelStarters, true)) {
            return true;
        }

        if ($firstChar === 'h') {
            $base = mb_strtolower(trim($this->baseInfinitive()));
            if ($base !== '' && in_array($base, self::H_ASPIRE_EXCEPTIONS, true)) {
                return false;
            }

            return true;
        }

        return false;
    }

    public function formatConjugation(string $pronounKey, ?string $raw): string
    {
        $value = trim((string) ($raw ?? ''));
        if ($value === '') {
            return '';
        }

        if (! $this->isPronominal()) {
            return $value;
        }

        $pronounKey = trim($pronounKey);
        $reflexiveMap = [
            'je' => 'me',
            'tu' => 'te',
            'il_elle_on' => 'se',
            'nous' => 'nous',
            'vous' => 'vous',
            'ils_elles' => 'se',
        ];

        $reflexive = $reflexiveMap[$pronounKey] ?? null;
        if (! $reflexive) {
            return $value;
        }

        $value = preg_replace("/^(j['’]|je|tu|il|elle|on|nous|vous|ils|elles)\\s+/iu", '', $value) ?? $value;
        $value = trim($value);

        $value = preg_replace("/^(m['’]|me|t['’]|te|s['’]|se|nous|vous)\\s*/iu", '', $value) ?? $value;
        $verbPart = trim($value);

        if ($reflexive === 'nous' || $reflexive === 'vous') {
            return $reflexive.' '.$verbPart;
        }

        $needsElision = $this->shouldElideBeforeWord($verbPart);
        if (! $needsElision) {
            return $reflexive.' '.$verbPart;
        }

        $firstLetter = mb_substr($reflexive, 0, 1);

        return $firstLetter.'’'.$verbPart;
    }

    public static function editVerb(int $verbId, array $newData): string
    {
        return DB::transaction(function () use ($verbId, $newData): string {
            $verb = self::query()->with('conjugations')->findOrFail($verbId);

            $verbUpdates = [];

            $stringOrNull = static function (mixed $value): ?string {
                if (! is_string($value)) {
                    return null;
                }
                $value = trim($value);

                return $value !== '' ? $value : null;
            };

            if (array_key_exists('infinitive', $newData)) {
                $infinitive = is_string($newData['infinitive']) ? mb_strtolower(trim($newData['infinitive'])) : '';
                if ($infinitive !== '') {
                    $verbUpdates['infinitive'] = $infinitive;
                }
            }

            foreach ([
                'infinitive_translation',
                'example_sentence',
                'group',
                'suit',
                'illustration_path',
                'illustration_description',
                'theme_color',
                'accent_color',
                'pattern',
            ] as $key) {
                if (! array_key_exists($key, $newData)) {
                    continue;
                }

                if (in_array($key, ['infinitive_translation', 'example_sentence', 'illustration_path', 'illustration_description', 'pattern'], true)) {
                    $verbUpdates[$key] = $stringOrNull($newData[$key]);

                    continue;
                }

                if (is_string($newData[$key])) {
                    $value = trim($newData[$key]);
                    if ($value !== '') {
                        $verbUpdates[$key] = $value;
                    }
                }
            }

            if (array_key_exists('is_active', $newData)) {
                $verbUpdates['is_active'] = filter_var($newData['is_active'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
            }

            $verb->fill(array_filter($verbUpdates, static fn ($v) => $v !== null));
            $verb->save();

            $conjugationKeys = ['je', 'tu', 'il_elle_on', 'nous', 'vous', 'ils_elles'];
            $shouldUpdateConjugation = false;
            foreach ($conjugationKeys as $key) {
                if (array_key_exists($key, $newData)) {
                    $shouldUpdateConjugation = true;
                    break;
                }
            }

            if ($shouldUpdateConjugation) {
                $present = $verb->getPresentConjugation() ?? new Conjugation(['tense' => 'présent']);
                $present->verb_id = $verb->id;
                $present->tense = 'présent';

                foreach ($conjugationKeys as $key) {
                    if (! array_key_exists($key, $newData)) {
                        continue;
                    }

                    if (! is_string($newData[$key])) {
                        continue;
                    }

                    $value = trim($newData[$key]);
                    if ($value === '') {
                        continue;
                    }

                    $present->{$key} = $value;
                }

                $present->save();
            }

            return 'Verbe mis à jour avec succès.';
        });
    }
}
