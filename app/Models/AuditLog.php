<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audit_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_name',
        'module',
        'action',
        'old_data',
        'new_data',
        'ip_address',
        'browser',
        'platform',
        'city',
        'state',
        'country',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'old_data' => 'array',
            'new_data' => 'array',
        ];
    }

    /**
     * Record a single audit log entry with the current request context.
     */
    public static function log(
        string $module,
        string $action,
        ?array $oldData = null,
        ?array $newData = null,
        ?string $description = null,
        ?int $userId = null,
        ?string $userName = null
    ): void {
        $userId = $userId ?? auth('admin')->id() ?? auth()->id();

        if (empty($userName)) {
            $userName = auth('admin')->user()->name ?? auth()->user()->name ?? null;
        }

        static::create([
            'user_id' => $userId,
            'user_name' => $userName,
            'module' => $module,
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => request()->ip(),
            'browser' => static::detectBrowser(request()->userAgent()),
            'platform' => static::detectPlatform(request()->userAgent()),
            'description' => $description,
        ]);
    }

    /**
     * Extract the browser name from a user agent string.
     */
    protected static function detectBrowser(?string $userAgent): ?string
    {
        if (empty($userAgent)) {
            return null;
        }

        $browsers = [
            'Edg' => 'Microsoft Edge',
            'OPR' => 'Opera',
            'Chrome' => 'Chrome',
            'Firefox' => 'Firefox',
            'Safari' => 'Safari',
            'MSIE' => 'Internet Explorer',
        ];

        foreach ($browsers as $key => $name) {
            if (str_contains($userAgent, $key)) {
                return $name;
            }
        }

        return 'Unknown';
    }

    /**
     * Extract the platform (OS) name from a user agent string.
     */
    protected static function detectPlatform(?string $userAgent): ?string
    {
        if (empty($userAgent)) {
            return null;
        }

        $platforms = [
            'Windows' => 'Windows',
            'Android' => 'Android',
            'iPhone' => 'iOS',
            'iPad' => 'iOS',
            'Linux' => 'Linux',
            'Mac OS' => 'macOS',
        ];

        foreach ($platforms as $key => $name) {
            if (str_contains($userAgent, $key)) {
                return $name;
            }
        }

        return 'Unknown';
    }
}