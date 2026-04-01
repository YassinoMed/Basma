<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conjugation extends Model
{
    protected $fillable = [
        'verb_id',
        'tense',
        'je',
        'tu',
        'il_elle_on',
        'nous',
        'vous',
        'ils_elles',
    ];

    public function verb(): BelongsTo
    {
        return $this->belongsTo(Verb::class);
    }

    public function getFormattedConjugations(): array
    {
        return [
            ['pronoun' => 'je', 'conjugation' => $this->je],
            ['pronoun' => 'tu', 'conjugation' => $this->tu],
            ['pronoun' => 'il/elle/on', 'conjugation' => $this->il_elle_on],
            ['pronoun' => 'nous', 'conjugation' => $this->nous],
            ['pronoun' => 'vous', 'conjugation' => $this->vous],
            ['pronoun' => 'ils/elles', 'conjugation' => $this->ils_elles],
        ];
    }
}
