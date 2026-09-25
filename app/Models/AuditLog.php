<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an action in the system securely without sensitive NIK/passwords
     */
    public static function record(string $action, ?string $targetType = null, ?int $targetId = null, array $metadata = []): self
    {
        // Filter out sensitive keys from metadata
        $safeMetadata = array_diff_key($metadata, array_flip(['password', 'nik', 'nik_encrypted', 'token', '_token']));

        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $safeMetadata,
            'created_at' => now(),
        ]);
    }
}
