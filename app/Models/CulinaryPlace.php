<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CulinaryPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'culinary_type',
        'price_range',
        'description',
        'address',
        'location_district',
        'latitude',
        'longitude',
        'phone_whatsapp',
        'photo',
        'operating_hours',
        'status',
        'rejection_reason',
        'moderated_by',
        'moderated_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'moderated_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Get the photo URL with culinary-type-based curated fallback images
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            if (Str::startsWith($this->photo, ['http://', 'https://'])) {
                return $this->photo;
            }
            if (Storage::disk('public')->exists($this->photo)) {
                return asset('storage/' . $this->photo);
            }
        }

        $name = Str::lower($this->name . ' ' . $this->description);

        if (Str::contains($name, ['gence', 'ruan', 'gabus', 'patin', 'bakar', 'ikan'])) {
            return 'https://images.unsplash.com/photo-1534939561126-855b8675edd7?auto=format&fit=crop&w=800&q=80';
        }
        if (Str::contains($name, ['nasi kuning', 'sarapan', 'jajanan', 'kue', 'habang', 'pisang gapit'])) {
            return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80';
        }
        if (Str::contains($name, ['kopi', 'cafe', 'kafe', 'latte', 'espresso', 'santai'])) {
            return 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=800&q=80';
        }
        if (Str::contains($name, ['sambal', 'raja', 'sayur asam', 'kutai', 'tradisional'])) {
            return 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=800&q=80';
        }

        return match ($this->culinary_type) {
            'Kuliner Tradisional Kutai' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=800&q=80',
            'Sarapan Pagi & Jajanan' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
            'Cafe & Santai Sore' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=800&q=80',
            'Rumah Makan & Seafood' => 'https://images.unsplash.com/photo-1534939561126-855b8675edd7?auto=format&fit=crop&w=800&q=80',
            default => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80',
        };
    }
}
