<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'category',
        'description',
        'address',
        'location_district',
        'phone_whatsapp',
        'instagram',
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
     * Get the photo URL with category-based curated fallback images
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

        return match ($this->category) {
            'Kuliner & Olahan' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80',
            'Jasa Kreatif & Percetakan' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=800&q=80',
            'Jasa Teknik & Fabrikasi' => 'https://images.unsplash.com/photo-1504917599217-d4dc5ebe6122?auto=format&fit=crop&w=800&q=80',
            'Kerajinan & Kriya Khas' => 'https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?auto=format&fit=crop&w=800&q=80',
            'Fashion & Pakaian' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=800&q=80',
            'Toko & Retail' => 'https://images.unsplash.com/photo-1604719312566-8912e9227c6a?auto=format&fit=crop&w=800&q=80',
            'Jasa Profesional' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=800&q=80',
            default => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?auto=format&fit=crop&w=800&q=80',
        };
    }
}
