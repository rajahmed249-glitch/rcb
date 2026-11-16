<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        try {
            $value = self::query()->where('key', $key)->value('value');
        } catch (\Throwable) {
            return $default;
        }

        return ($value !== null && $value !== '') ? $value : $default;
    }

    public static function set(string $key, ?string $value): void
    {
        if ($value === null || $value === '') {
            self::query()->where('key', $key)->delete();

            return;
        }

        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
