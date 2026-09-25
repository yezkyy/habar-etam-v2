<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function verification(): HasOne
    {
        return $this->hasOne(Verification::class);
    }

    public function jobVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class);
    }

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    public function culinaryPlaces(): HasMany
    {
        return $this->hasMany(CulinaryPlace::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function communities(): HasMany
    {
        return $this->hasMany(Community::class);
    }

    public function quickSales(): HasMany
    {
        return $this->hasMany(QuickSale::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // Role and status checks
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function canSubmitUgc(): bool
    {
        return !$this->isSuspended();
    }

    public function canSubmitReport(): bool
    {
        return $this->isVerified() || $this->isAdmin();
    }
}
