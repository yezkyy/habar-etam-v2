<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'phone',
        'whatsapp',
        'address',
        'location_district',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        if (!empty($this->image)) {
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            if (file_exists(public_path($this->image))) {
                return asset($this->image);
            }
            return asset('storage/' . $this->image);
        }

        return match ($this->category) {
            'rumah_sakit', 'ambulans' => asset('assets/emergency/rsud.jpg'),
            'damkar' => asset('assets/emergency/damkar.jpg'),
            'polisi' => asset('assets/emergency/polres.jpg'),
            'sar_bpbd', 'posko_bencana' => asset('assets/emergency/bpbd.jpg'),
            'puskesmas' => asset('assets/emergency/puskesmas.jpg'),
            'pdam' => asset('assets/emergency/pdam.jpg'),
            'pln' => asset('assets/emergency/pln.jpg'),
            default => asset('assets/emergency/rsud.jpg'),
        };
    }
}

