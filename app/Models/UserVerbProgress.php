<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVerbProgress extends Model
{
    protected $table = 'user_verb_progress';

    protected $fillable = [
        'user_id',
        'verb_id',
        'correct_count',
        'wrong_count',
        'streak',
        'last_practiced_at',
        'next_review_at',
        'mastered_at',
    ];

    protected $casts = [
        'correct_count' => 'integer',
        'wrong_count' => 'integer',
        'streak' => 'integer',
        'last_practiced_at' => 'datetime',
        'next_review_at' => 'datetime',
        'mastered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verb(): BelongsTo
    {
        return $this->belongsTo(Verb::class);
    }
}
