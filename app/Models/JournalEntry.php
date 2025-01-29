<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{
    /** @use HasFactory<\Database\Factories\JournalEntryFactory> */
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'date',
        'content',
        'image',
        'code_snippet',
        'is_public',
        'shared_link'
    ];

    public function challenge():BelongsTo {
        return $this->belongsTo(Challenge::class);
    }

    public function links():HasMany{
        return $this->hasMany(JournalLink::class);
    }
}
