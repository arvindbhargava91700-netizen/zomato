<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    public const TYPE_TABLE_BOOKING = 'table_booking';
    public const TYPE_ORDER = 'order';

    public const STATUS_SUCCESS = 'success';
    public const STATUS_PENDING = 'pending';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';

    public const PAYMENT_STATUS_PAID = 'paid';
    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_FAILED = 'failed';
    public const PAYMENT_STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'transaction_number',
        'type',
        'booking_id',
        'order_id',
        'restaurant_id',
        'customer_id',
        'customer_name',
        'phone',
        'book_date',
        'book_time',
        'guests',
        'total_amount',
        'admin_share_percent',
        'admin_amount',
        'restaurant_share_percent',
        'restaurant_amount',
        'payment_method',
        'payment_gateway',
        'payment_id',
        'order_reference_id',
        'signature',
        'payment_status',
        'status',
        'note',
        'meta_data',
        'paid_at',
    ];

    protected $casts = [
        'book_date' => 'date',
        'guests' => 'integer',
        'total_amount' => 'decimal:2',
        'admin_share_percent' => 'decimal:2',
        'admin_amount' => 'decimal:2',
        'restaurant_share_percent' => 'decimal:2',
        'restaurant_amount' => 'decimal:2',
        'meta_data' => 'array',
        'paid_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Generate a unique transaction reference number.
     */
    public static function generateTransactionNumber(string $prefix = 'TBL'): string
    {
        do {
            $number = 'TXN-' . strtoupper($prefix) . '-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('transaction_number', $number)->exists());

        return $number;
    }
}
