<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'wallets';

    protected $fillable = [
        'user_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id')->latest();
    }

    /**
     * Get existing or create new wallet for given user.
     */
    public static function getOrCreateForUser(User|int $user): self
    {
        $userId = $user instanceof User ? $user->id : $user;

        return static::firstOrCreate(
            ['user_id' => $userId],
            ['balance' => 0.00]
        );
    }

    /**
     * Credit amount to wallet and record transaction.
     */
    public function credit(
        float|int $amount,
        string $source = 'manual',
        ?int $referenceId = null,
        ?string $description = null,
        ?array $meta = null
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Credit amount must be greater than zero.");
        }

        return DB::transaction(function () use ($amount, $source, $referenceId, $description, $meta) {
            // Lock wallet for update
            $wallet = static::where('id', $this->id)->lockForUpdate()->first();
            
            $balanceBefore = (float) $wallet->balance;
            $balanceAfter = round($balanceBefore + (float) $amount, 2);

            $wallet->update([
                'balance' => $balanceAfter,
            ]);

            $this->balance = $balanceAfter;

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'transaction_number' => WalletTransaction::generateTransactionNumber('CR'),
                'type' => WalletTransaction::TYPE_CREDIT,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'source' => $source,
                'reference_id' => $referenceId,
                'description' => $description,
                'meta_data' => $meta,
            ]);
        });
    }

    /**
     * Debit amount from wallet and record transaction.
     */
    public function debit(
        float|int $amount,
        string $source = 'manual',
        ?int $referenceId = null,
        ?string $description = null,
        ?array $meta = null,
        bool $allowNegative = false
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Debit amount must be greater than zero.");
        }

        return DB::transaction(function () use ($amount, $source, $referenceId, $description, $meta, $allowNegative) {
            // Lock wallet for update
            $wallet = static::where('id', $this->id)->lockForUpdate()->first();

            $balanceBefore = (float) $wallet->balance;

            if (!$allowNegative && $balanceBefore < (float) $amount) {
                throw new RuntimeException("Insufficient wallet balance.");
            }

            $balanceAfter = round($balanceBefore - (float) $amount, 2);

            $wallet->update([
                'balance' => $balanceAfter,
            ]);

            $this->balance = $balanceAfter;

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'transaction_number' => WalletTransaction::generateTransactionNumber('DR'),
                'type' => WalletTransaction::TYPE_DEBIT,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'source' => $source,
                'reference_id' => $referenceId,
                'description' => $description,
                'meta_data' => $meta,
            ]);
        });
    }
}
