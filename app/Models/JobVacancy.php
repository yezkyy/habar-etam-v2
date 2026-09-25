<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'company',
        'photo',
        'employment_type',
        'location',
        'salary_range',
        'description',
        'requirements',
        'deadline',
        'contact_person',
        'contact_phone',
        'contact_email',
        'status',
        'rejection_reason',
        'moderated_by',
        'moderated_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
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
     * Get the photo URL with high-definition curated fallback images
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

        $title = Str::lower($this->title . ' ' . $this->company);

        if (Str::contains($title, ['barista', 'kopi', 'cafe', 'kafe', 'kitchen', 'cook', 'waiter', 'pelayan'])) {
            return 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=800&q=80';
        }
        if (Str::contains($title, ['mekanik', 'teknisi', 'bengkel', 'motor', 'mobil', 'las', 'listrik'])) {
            return 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?auto=format&fit=crop&w=800&q=80';
        }
        if (Str::contains($title, ['admin', 'administrasi', 'sekretaris', 'akuntan', 'finance', 'kantor'])) {
            return 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=800&q=80';
        }
        if (Str::contains($title, ['kasir', 'toko', 'retail', 'pramuniaga', 'sales', 'marketing', 'spg'])) {
            return 'https://images.unsplash.com/photo-1556742049-0a67e557b4f5?auto=format&fit=crop&w=800&q=80';
        }
        if (Str::contains($title, ['driver', 'supir', 'kurir', 'pengemudi', 'delivery'])) {
            return 'https://images.unsplash.com/photo-1526367790999-0150786686a2?auto=format&fit=crop&w=800&q=80';
        }
        if (Str::contains($title, ['desain', 'grafis', 'it', 'programmer', 'web', 'media', 'fotografer'])) {
            return 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80';
        }
        if (Str::contains($title, ['guru', 'pengajar', 'tutor', 'dosen'])) {
            return 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&q=80';
        }

        return 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80';
    }
}
