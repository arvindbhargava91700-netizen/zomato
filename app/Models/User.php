<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;


     protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'phone',
        'role_id',
        'notify_offer',
        'notify_order',
        'notify_new',
        'city',
        'country_id',
        'state_id',
        'city_id',
        'vehicle_type',
        'age',
        'aadhar_front',
        'aadhar_back',
        'passport_photo',
        'rc_image',
        'vehicle_number',
        'aadhar_card',
        'pan_card',
        'bank_account',
        'ifsc_code',
        'bank_name',
        'kyc_status',
        'kyc_rejected_reason',
        'kyc_remark',
        'kyc_reviewed_at',
        'status',
        'live_lat',
        'live_lng',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'city' => 'string',
            'country_id' => 'integer',
            'state_id' => 'integer',
            'city_id' => 'integer',
            'vehicle_type' => 'string',
            'age' => 'string',
            'kyc_status' => 'string',
            'kyc_rejected_reason' => 'string',
            'kyc_remark' => 'string',
            'kyc_reviewed_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to only delivery partners (role slug = delivery_partner).
     */
    public function scopeDeliveryPartners($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('slug', 'delivery_partner');
        });
    }

    /**
     * Scope a query to only users with a given KYC status.
     */
    public function scopeKycStatus($query, string $status)
    {
        return $query->where('kyc_status', $status);
    }

    /**
     * Determine whether the partner has submitted KYC and is awaiting review.
     */
    public function hasPendingKyc(): bool
    {
        return $this->kyc_status === 'pending';
    }

    /**
     * Relationship to Role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship to Country.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * Relationship to State.
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    /**
     * Relationship to Addresses.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Relationship to Cards.
     */
    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    /**
     * Orders placed by this user (customer).
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Deliveries assigned to this user (delivery partner).
     */
    public function deliveries()
    {
        return $this->hasMany(Order::class, 'delivery_partner_id');
    }

    /**
     * User Wallet (Customer, Restaurant Owner, Delivery Partner).
     */
    public function wallet(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * User Wallet Transactions.
     */
    public function walletTransactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get or create wallet instance for user.
     */
    public function getOrCreateWallet(): Wallet
    {
        return Wallet::getOrCreateForUser($this);
    }

    /**
     * Get current wallet balance.
     */
    public function getWalletBalanceAttribute(): float
    {
        return (float) ($this->wallet?->balance ?? 0.00);
    }
}
