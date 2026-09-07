<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $table = 'payment_gateways';

    protected $fillable = [
        'gateway_key',
        'name',
        'display_name',
        'badge_color',
        'icon',
        'mode',
        'is_active',
        'key_id',
        'key_secret',
        'webhook_secret',
        'merchant_id',
        'currency',
        'theme_color',
        'description',
        'additional_settings',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'additional_settings' => 'array',
        'sort_order' => 'integer',
    ];

    public const GATEWAY_RAZORPAY = 'razorpay';
    public const GATEWAY_PHONEPE = 'phonepe';
    public const GATEWAY_PAYTM = 'paytm';
    public const GATEWAY_PAYU = 'payu';
    public const GATEWAY_PAYPAL = 'paypal';

    /**
     * Get a specific gateway by its key.
     */
    public static function getGateway(string $key): ?self
    {
        return self::where('gateway_key', $key)->first();
    }

    /**
     * Get active Razorpay Key ID (database first, then config/env fallback).
     */
    public static function getRazorpayKey(): string
    {
        $gateway = self::getGateway(self::GATEWAY_RAZORPAY);
        if ($gateway && !empty($gateway->key_id)) {
            return trim($gateway->key_id);
        }
        return config('services.razorpay.key', 'rzp_test_TWIAoYszpcVthe');
    }

    /**
     * Get active Razorpay Secret Key (database first, then config/env fallback).
     */
    public static function getRazorpaySecret(): string
    {
        $gateway = self::getGateway(self::GATEWAY_RAZORPAY);
        if ($gateway && !empty($gateway->key_secret)) {
            return trim($gateway->key_secret);
        }
        return config('services.razorpay.secret', 'EyaGehL4Ok8UttyrPDipNTcO');
    }

    /**
     * Check if Razorpay is enabled in database.
     */
    public static function isRazorpayActive(): bool
    {
        $gateway = self::getGateway(self::GATEWAY_RAZORPAY);
        return $gateway ? (bool) $gateway->is_active : true;
    }
}
