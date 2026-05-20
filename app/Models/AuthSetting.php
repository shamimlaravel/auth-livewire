<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected function casts(): array
    {
        return [
            'value' => 'encrypted',
        ];
    }

    /**
     * Get a setting value with a default fallback.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return static::query()
            ->where('key', $key)
            ->value('value')
            ?? $default;
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? var_export($value, true) : (string) $value],
        );
    }

    /**
     * Check whether the given key is enabled (truthy).
     */
    public static function isEnabled(string $key): bool
    {
        $raw = static::get($key, 'false');

        return match (strtolower((string) $raw)) {
            'true', '1', 'yes', 'on' => true,
            default => false,
        };
    }

    /**
     * Return all settings as an associative array.
     */
    public static function allSettings(): array
    {
        return static::query()->pluck('value', 'key')->all();
    }
}
