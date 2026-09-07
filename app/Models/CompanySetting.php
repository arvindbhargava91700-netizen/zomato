<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_name',
        'logo_lg',
        'logo_sm',
        'favicon',
        'email',
        'phone',
        'address',
        'website',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'instagram_url',
        'youtube_url',
        'currency',
        'timezone',
        'date_format',
        'tax_gst',
        'invoice_prefix',
        'receipt_prefix',
        'payment_qr',
        'payment_details',
        'delivery_partner_share',
        'delivery_partner_cod_limit',
        'platform_share',
        'dining_com_per',
        'dining_restaurant_share',
        'cover_charge_admin_share',
        'cover_charge_restaurant_share',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'delivery_partner_share' => 'decimal:2',
            'delivery_partner_cod_limit' => 'decimal:2',
            'platform_share' => 'decimal:2',
            'dining_com_per' => 'decimal:2',
            'dining_restaurant_share' => 'decimal:2',
            'cover_charge_admin_share' => 'decimal:2',
            'cover_charge_restaurant_share' => 'decimal:2',
        ];
    }

    /**
     * Extract the numeric tax percentage from the tax_gst value.
     * Handles values like "5", "5%", "5% GST", "GST 5%".
     */
    public function taxGstPercentage(): float
    {
        $value = trim((string) $this->tax_gst);
        if ($value === '') {
            return 0.0;
        }

        if (preg_match('/(\d+(?:\.\d+)?)/', $value, $matches)) {
            return (float) $matches[1];
        }

        return 0.0;
    }

    /**
     * Get the single company settings record (creates one if missing).
     */
    public static function firstSetting(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }

    /**
     * Resolve the currency symbol for the stored currency value.
     * Known ISO codes are mapped to their symbols; anything else is
     * returned unchanged (so a symbol can be saved directly).
     */
    public function currencySymbol(): string
    {
        $symbols = [
            'USD' => '$',
            'INR' => '₹',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CNY' => '¥',
            'AUD' => 'A$',
            'CAD' => 'C$',
            'SGD' => 'S$',
            'AED' => 'د.إ',
            'SAR' => '﷼',
            'PKR' => '₨',
            'NPR' => '₨',
            'BDT' => '৳',
            'LKR' => 'Rs',
            'MYR' => 'RM',
            'THB' => '฿',
            'PHP' => '₱',
            'IDR' => 'Rp',
            'VND' => '₫',
            'BRL' => 'R$',
            'MXN' => 'Mex$',
            'ZAR' => 'R',
            'CHF' => 'CHF ',
            'RUB' => '₽',
            'KRW' => '₩',
            'NGN' => '₦',
        ];

        $currency = trim((string) $this->currency);
        if ($currency === '') {
            return '$';
        }

        $code = strtoupper($currency);

        return $symbols[$code] ?? $currency;
    }

    /**
     * Get Admin / Platform commission percentage for dining (default: 10.00%).
     */
    public function diningAdminShare(): float
    {
        return $this->dining_com_per !== null ? (float) $this->dining_com_per : 10.00;
    }

    /**
     * Get Restaurant share percentage for dining (default: 90.00%).
     */
    public function diningRestaurantShare(): float
    {
        return $this->dining_restaurant_share !== null ? (float) $this->dining_restaurant_share : 90.00;
    }

    /**
     * Get Admin / Platform share percentage for cover charges (default: 20.00%).
     */
    public function coverChargeAdminShare(): float
    {
        return $this->cover_charge_admin_share !== null ? (float) $this->cover_charge_admin_share : 20.00;
    }

    /**
     * Get Restaurant share percentage for cover charges (default: 80.00%).
     */
    public function coverChargeRestaurantShare(): float
    {
        return $this->cover_charge_restaurant_share !== null ? (float) $this->cover_charge_restaurant_share : 80.00;
    }

    /**
     * Get Delivery Partner Cash On Delivery (COD) holding limit (default: 5000.00).
     */
    public function deliveryPartnerCodLimit(): float
    {
        return $this->delivery_partner_cod_limit !== null ? (float) $this->delivery_partner_cod_limit : 5000.00;
    }
}
