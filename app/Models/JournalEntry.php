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

    protected $casts = [
        'date' => 'date',
        'is_public' => 'boolean',
        'blocks' => 'array',
        'tags' => 'array',
    ];

    protected $fillable = [
        'challenge_id',
        'date',
        'content',
        'blocks',
        'tags',
        'is_public',
        'shared_link',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($entry) {
            if (!$entry->shared_link) {
                $entry->shared_link = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function getShareUrl()
    {
        if ($this->shared_link) {
            return route('shared.journal', ['shareToken' => $this->shared_link]);
        }
        return null;
    }
    
    public function challenge():BelongsTo {
        return $this->belongsTo(Challenge::class);
    }

    public function links():HasMany{
        return $this->hasMany(JournalLink::class);
    }
}
