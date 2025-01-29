<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Challenge extends Model
{
    /** @use HasFactory<\Database\Factories\ChallengeFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description', 'user_id', 'start_date', 'end_date', 'status', 'is_favorite'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany{
        return $this->belongsToMany(Tag::class, 'challenge_tag');
    }

    // public function journalEntries(): HasMany {
    //     return $this->hasMany(JournalEntry::class);
    // }

    // public function remiders():HasMany {
    //     return $this->hasMany(Reminder::class);
    // }

}
