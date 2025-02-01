<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Illuminate\Support\Str;

class JournalEntry extends Model
{
    /** @use HasFactory<\Database\Factories\JournalEntryFactory> */
    use HasFactory;

    protected $attributes = [
        'is_private' => false, // Public by default
    ];

    protected $casts = [
        'date' => 'date',
        'is_private' => 'boolean',
        'blocks' => 'array',
        'tags' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'challenge_id',
        'date',
        'content',
        'blocks',
        'tags',
        'is_private',
        'shared_link',
        'title',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($entry) {
            // Always generate shared link for new entries
            if (!$entry->shared_link) {
                $entry->shared_link = (string) Str::uuid();
            }
        });
    }

    public function getShareUrl()
    {
        if ($this->is_private) {
            return null;
        }
        if ($this->shared_link) {
            return route('shared.journal', ['shareToken' => $this->shared_link]);
        }
        return null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function canBeAccessed(?User $user = null): bool
    {
        // Check if user is the owner
        if ($user && $user->id === $this->user_id) {
            return true;
        }
        
        // Check if entry is not private and has a valid shared link
        if (!$this->is_private && $this->shared_link) {
            return true;
        }
        
        return false;
    }

    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }
    
    public function challenge():BelongsTo {
        return $this->belongsTo(Challenge::class);
    }

    public function links():HasMany{
        return $this->hasMany(JournalLink::class);
    }
}
