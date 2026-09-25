<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuickSaleMedia extends Model
{
    use HasFactory;

    protected $table = 'quick_sale_media';

    protected $fillable = [
        'quick_sale_id',
        'path',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function quickSale(): BelongsTo
    {
        return $this->belongsTo(QuickSale::class);
    }
}
