<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analytics extends Model
{
    /** @use HasFactory<\Database\Factories\AnalyticsFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_challenges',
        'total_journal_entries',
        'longest_streak',
        'last_updated',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
