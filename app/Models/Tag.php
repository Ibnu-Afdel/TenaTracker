<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function challenges():BelongsToMany {
        return $this->belongsToMany(Challenge::class, 'challenge_tag');
    }

    public function user():BelongsTo {
        return $this->belongsTo(User::class);
    }
}
