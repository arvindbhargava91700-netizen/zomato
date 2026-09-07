<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'wallet_transactions';

    public const TYPE_CREDIT = 'credit';
    public const TYPE_DEBIT = 'debit';

    protected $fillable = [
        'wallet_id',
        'user_id',
        'transaction_number',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'source',
        'reference_id',
        'description',
        'meta_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'meta_data' => 'array',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique transaction reference code.
     */
    public static function generateTransactionNumber(string $prefix = 'WTXN'): string
    {
        do {
            $number = strtoupper($prefix) . '-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('transaction_number', $number)->exists());

        return $number;
    }
}
