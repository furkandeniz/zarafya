<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $primaryKey = 'key';
    protected $keyType    = 'string';
    public    $incrementing = false;

    protected $fillable = ['key', 'value'];

    const DEFAULTS = [
        'commission_rate'        => '15',
        'blog_show_visit_count'  => '1',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $fallback = $default ?? self::DEFAULTS[$key] ?? null;

        try {
            return Cache::rememberForever('setting_' . $key, function () use ($key, $fallback) {
                $row = static::find($key);
                $val = $row?->value;
                return ($val !== null && $val !== '') ? $val : $fallback;
            });
        } catch (\Throwable) {
            return $fallback;
        }
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('setting_' . $key);
    }
}
