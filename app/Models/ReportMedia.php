<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportMedia extends Model
{
    use HasFactory;

    protected $table = 'report_media';

    protected $fillable = [
        'report_id',
        'path',
        'media_type',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
