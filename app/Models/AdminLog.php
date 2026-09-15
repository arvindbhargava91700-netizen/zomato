<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Request;

class AdminLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'admin_id',
        'name',
        'email',
        'role',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'browser',
        'os',
        'device',
        'status',
        'logged_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
        ];
    }

    /**
     * The admin who performed the action.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Write an admin log entry. Pass any of the table columns as attributes.
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function log(array $attributes): self
    {
        $ua = $attributes['user_agent'] ?? Request::userAgent();
        $parsed = static::parseUserAgent($ua);

        $data = [
            'admin_id' => null,
            'name' => null,
            'email' => null,
            'role' => null,
            'action' => 'LOGIN',
            'description' => null,
            'ip_address' => Request::ip(),
            'user_agent' => $ua,
            'browser' => $parsed['browser'],
            'os' => $parsed['os'],
            'device' => $parsed['device'],
            'status' => 'success',
            'logged_at' => now(),
        ];

        $data = array_merge($data, $attributes);
        $data['logged_at'] = now();

        return static::create($data);
    }

    /**
     * Best-effort browser / OS / device detection from a user agent string.
     *
     * @return array{browser: string, os: string, device: string}
     */
    public static function parseUserAgent(?string $ua): array
    {
        $ua = (string) $ua;

        $os = 'Unknown';
        if (preg_match('/Windows/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/Android/i', $ua)) {
            $os = 'Android';
        } elseif (preg_match('/iPhone|iPad|iOS/i', $ua)) {
            $os = 'iOS';
        } elseif (preg_match('/Mac OS X|Macintosh/i', $ua)) {
            $os = 'macOS';
        } elseif (preg_match('/Linux/i', $ua)) {
            $os = 'Linux';
        }

        $browser = 'Unknown';
        if (preg_match('/Edg|Edge/i', $ua)) {
            $browser = 'Edge';
        } elseif (preg_match('/OPR|Opera/i', $ua)) {
            $browser = 'Opera';
        } elseif (preg_match('/Firefox/i', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $ua)) {
            $browser = 'Safari';
        }

        $device = 'Desktop';
        if (preg_match('/iPad|Tablet/i', $ua)) {
            $device = 'Tablet';
        } elseif (preg_match('/Mobile|Android|iPhone/i', $ua)) {
            $device = 'Mobile';
        }

        return compact('browser', 'os', 'device');
    }
}