<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'market_name',
        'commodity_name',
        'category',
        'price',
        'previous_price',
        'unit',
        'recorded_date',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'previous_price' => 'decimal:2',
            'recorded_date' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPriceDifferenceAttribute(): float
    {
        if (!$this->previous_price || $this->previous_price == 0) {
            return 0;
        }
        return (float) ($this->price - $this->previous_price);
    }
}
