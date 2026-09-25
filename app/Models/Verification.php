<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class Verification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nik_encrypted',
        'nik_hash',
        'date_of_birth',
        'nik_region_valid',
        'birth_date_valid',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'nik_region_valid' => 'boolean',
            'birth_date_valid' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get masked NIK for safe display (e.g. 640206******0001)
     */
    public function getMaskedNikAttribute(): string
    {
        try {
            $decrypted = Crypt::decryptString($this->nik_encrypted);
            if (strlen($decrypted) === 16) {
                return substr($decrypted, 0, 6) . '******' . substr($decrypted, -4);
            }
            return '****************';
        } catch (\Exception $e) {
            return '****************';
        }
    }

    /**
     * Internal decryption for verified admin workflows only
     */
    public function getDecryptedNik(): ?string
    {
        try {
            return Crypt::decryptString($this->nik_encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }
}
