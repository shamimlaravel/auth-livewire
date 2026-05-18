<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpToken extends Model
{
    protected $fillable = [
        'email', 'token', 'expires_at', 'used_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function isValid(): bool
    {
        return is_null($this->used_at) && $this->expires_at->isFuture();
    }

    public function scopeValid($query)
    {
        return $query->whereNull('used_at')->where('expires_at', '>', now());
    }
}
