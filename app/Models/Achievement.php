<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    /** @use HasFactory<\Database\Factories\AchievementFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'challenge_id',
        'name',
        'date_awarded',
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function challenge() : BelongsTo {
        return $this->belongsTo(Challenge::class);
    }
}
