<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    /** @use HasFactory<\Database\Factories\ReminderFactory> */
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'reminder_time',
        'message',
    ];

    public function challenge():BelongsTo{
        return $this->belongsTo(Challenge::class);
    }
} 
