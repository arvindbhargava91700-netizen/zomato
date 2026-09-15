<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Withdrawal extends Model
{
    use HasFactory;

    protected $table = 'withdrawals';

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'withdrawal_number',
        'user_id',
        'wallet_id',
        'restaurant_id',
        'amount',
        'fee',
        'net_amount',
        'payout_method',
        'account_details',
        'status',
        'notes',
        'admin_remarks',
        'admin_transaction_id',
        'admin_id',
        'requested_at',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'account_details' => 'array',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Generate unique withdrawal reference code.
     */
    public static function generateWithdrawalNumber(string $prefix = 'WD'): string
    {
        do {
            $number = strtoupper($prefix) . '-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('withdrawal_number', $number)->exists());

        return $number;
    }
}
