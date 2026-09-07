<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'description',
    ];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        $row = static::where('key', $key)->first();
        return $row ? $row->value : $default;
    }

    /**
     * Set (insert or update) a setting value by key.
     */
    public static function set(string $key, $value, ?string $group = null): void
    {
        $group = $group ?? static::groupFor($key);

        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value === null ? null : (string) $value,
                'group' => $group,
            ]
        );
    }

    /**
     * Derive the settings group from the key name.
     */
    protected static function groupFor(string $key): string
    {
        if (str_starts_with($key, 'mail_')) {
            return 'email';
        }
        if (str_starts_with($key, 'sms_')) {
            return 'sms';
        }
        if ($key === 'welcome_promo_source') {
            return 'registration';
        }
        return 'general';
    }

    /**
     * Get all settings for a group keyed by `key`.
     */
    public static function group(string $group): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('group', $group)->get();
    }
}
