<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ModerationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'moderatable_type',
        'moderatable_id',
        'from_status',
        'to_status',
        'note',
    ];

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function moderatable(): MorphTo
    {
        return $this->morphTo();
    }
}
