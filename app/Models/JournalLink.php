<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalLink extends Model
{
    /** @use HasFactory<\Database\Factories\JournalLinkFactory> */
    use HasFactory;
    protected $fillable = [
        'journal_entry_id',
        'url',
        'caption',
    ];

    public function journalEntry():BelongsTo{
        return $this->belongsTo(JournalEntry::class);
    }
}
