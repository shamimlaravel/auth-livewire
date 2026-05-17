<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceFingerprint extends Model
{
    protected $fillable = [
        'user_id', 'fingerprint_hash', 'device_name',
        'platform', 'browser', 'last_used_at', 'is_trusted',
    ];

    protected function casts(): array
    {
        return [
            'last_used_at' => 'datetime',
            'is_trusted' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
