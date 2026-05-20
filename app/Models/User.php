<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name', 'email', 'password', 'username', 'avatar', 'phone',
    'whatsapp_number', 'telegram_chat_id',
    'two_factor_enabled', 'two_factor_channel',
    'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at',
    'last_login_ip', 'last_login_at', 'status', 'auth_provider', 'auth_provider_id',
    'deactivated_at',
])]
#[Hidden([
    'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'deactivated_at' => 'datetime',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function loginAttempts(): HasMany
    {
        return $this->hasMany(LoginAttempt::class);
    }

    public function deviceFingerprints(): HasMany
    {
        return $this->hasMany(DeviceFingerprint::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled
            || ! is_null($this->two_factor_confirmed_at);
    }

    /**
     * Return the identifier that should receive a 2FA OTP for
     * the user's currently configured 2FA channel.
     */
    public function getTwoFactorTarget(): ?string
    {
        return match ($this->two_factor_channel) {
            'sms' => $this->phone,
            'whatsapp' => $this->whatsapp_number,
            'telegram' => $this->telegram_chat_id,
            default => $this->email,
        };
    }

    /**
     * Return all contact channels this user has registered.
     *
     * @return array<string, string|null> keys: sms, whatsapp, telegram, email
     */
    public function getAvailableChannels(): array
    {
        return [
            'sms' => $this->phone,
            'whatsapp' => $this->whatsapp_number,
            'telegram' => $this->telegram_chat_id,
            'email' => $this->email,
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
