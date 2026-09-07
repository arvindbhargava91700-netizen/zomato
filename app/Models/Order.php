<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PREPARING = 'preparing';
    public const STATUS_READY = 'ready';
    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_PICKED_UP = 'picked_up';
    public const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_ACCEPTED,
        self::STATUS_REJECTED,
        self::STATUS_PREPARING,
        self::STATUS_READY,
        self::STATUS_ASSIGNED,
        self::STATUS_PICKED_UP,
        self::STATUS_OUT_FOR_DELIVERY,
        self::STATUS_DELIVERED,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'user_id',
        'address_id',
        'restaurant_id',
        'delivery_partner_id',
        'delivery_option',
        'delivery_charge',
        'payment_method',
        'payment_status',
        'subtotal',
        'discount',
        'tax',
        'total',
        'promo_code_id',
        'promo_code',
        'promo_discount',
        'status',
        'accepted_at',
        'rejected_at',
        'preparing_at',
        'ready_at',
        'assigned_at',
        'picked_up_at',
        'out_for_delivery_at',
        'delivered_at',
        'completed_at',
        'cancelled_at',
        'cancel_reason',
    ];

    protected $casts = [
        'delivery_charge' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'promo_discount' => 'decimal:2',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'preparing_at' => 'datetime',
        'ready_at' => 'datetime',
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'out_for_delivery_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function deliveryPartner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_partner_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function deliveryRequests(): HasMany
    {
        return $this->hasMany(DeliveryRequest::class);
    }

    public function codSettlements(): HasMany
    {
        return $this->hasMany(CodSettlement::class);
    }

    public function latestCodSettlement(): HasOne
    {
        return $this->hasOne(CodSettlement::class)->latestOfMany();
    }

    public function isCodOrder(): bool
    {
        return $this->payment_method === 'cash_on_delivery';
    }

    /**
     * Send a delivery request to delivery partners that are currently free.
     * Partners already tried for this order are skipped, so repeated calls
     * naturally "try the next partner".
     */
    public function sendDeliveryRequests(?int $limit = null): int
    {
        $tried = $this->deliveryRequests()->pluck('delivery_partner_id')->all();

        $busy = Order::whereIn('status', [
            self::STATUS_ASSIGNED,
            self::STATUS_PICKED_UP,
            self::STATUS_OUT_FOR_DELIVERY,
        ])->whereNotNull('delivery_partner_id')->pluck('delivery_partner_id')->all();

        $partners = User::whereHas('role', function ($q) {
            $q->where('slug', 'delivery_partner');
        })
            ->whereNotIn('id', array_merge($tried, $busy))
            ->when($limit, fn ($q) => $q->limit($limit))
            ->get();

        $sent = 0;
        foreach ($partners as $partner) {
            $this->deliveryRequests()->create([
                'delivery_partner_id' => $partner->id,
                'status' => DeliveryRequest::STATUS_PENDING,
                'expires_at' => now()->addMinutes(2),
            ]);
            $sent++;
        }

        return $sent;
    }

    /**
     * Expire stale pending requests. Returns ids of orders whose last
     * pending request was just expired (callers can resend to next partner).
     */
    public static function expireStaleRequests(): array
    {
        $stale = DeliveryRequest::where('status', DeliveryRequest::STATUS_PENDING)
            ->where('expires_at', '<', now())
            ->get();

        $orderIds = [];
        foreach ($stale as $request) {
            $orderIds[] = $request->order_id;
            $request->update([
                'status' => DeliveryRequest::STATUS_EXPIRED,
                'responded_at' => now(),
            ]);
        }

        return array_values(array_unique($orderIds));
    }

    public function isActive(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_ACCEPTED,
            self::STATUS_PREPARING,
            self::STATUS_READY,
            self::STATUS_ASSIGNED,
            self::STATUS_PICKED_UP,
            self::STATUS_OUT_FOR_DELIVERY,
        ]);
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_PREPARING => 'Preparing',
            self::STATUS_READY => 'Ready',
            self::STATUS_ASSIGNED => 'Delivery Assigned',
            self::STATUS_PICKED_UP => 'Picked Up',
            self::STATUS_OUT_FOR_DELIVERY => 'Out for Delivery',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            default => ucfirst($status),
        };
    }

    public static function statusBadge(string $status): string
    {
        $color = match ($status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_ACCEPTED, self::STATUS_PREPARING, self::STATUS_READY => 'info',
            self::STATUS_ASSIGNED, self::STATUS_PICKED_UP, self::STATUS_OUT_FOR_DELIVERY => 'primary',
            self::STATUS_DELIVERED, self::STATUS_COMPLETED => 'success',
            self::STATUS_REJECTED, self::STATUS_CANCELLED => 'danger',
            default => 'secondary',
        };

        return "badge bg-soft-{$color} text-{$color}";
    }

    /**
     * Ordered timeline steps (delivery flow) for customer tracking.
     */
    public static function timelineSteps(): array
    {
        return [
            self::STATUS_PENDING => 'Order Placed',
            self::STATUS_ACCEPTED => 'Order Accepted',
            self::STATUS_PREPARING => 'Preparing Food',
            self::STATUS_READY => 'Food is Ready',
            self::STATUS_PICKED_UP => 'Picked Up',
            self::STATUS_OUT_FOR_DELIVERY => 'Out for Delivery',
            self::STATUS_DELIVERED => 'Delivered',
        ];
    }

    /**
     * Distribute financials, wallet balances, and incomes upon order delivery:
     * - If COD: Debit delivery partner's wallet for the collected total cash.
     * - Delivery Partner: Credit delivery partner's wallet with their share of delivery charge.
     * - Restaurant: Credit restaurant owner's wallet with food total minus restaurant commission.
     * - Platform/Admin: Record restaurant commission, delivery platform share, and tax/gst incomes.
     */
    public function distributeDeliveryFinancials(): array
    {
        return DB::transaction(function () {
            // Reload order with relationships
            $this->loadMissing(['restaurant', 'deliveryPartner']);

            // Idempotency check: prevent duplicate payouts for the same order
            if (WalletTransaction::where('reference_id', $this->id)
                ->whereIn('source', ['order_restaurant_payout', 'delivery_partner_payout', 'cod_cash_collected'])
                ->exists()) {
                return [];
            }

            $setting = CompanySetting::firstSetting();

            $foodTotal = round((float) $this->subtotal - (float) $this->discount, 2);
            $deliveryCharge = (float) $this->delivery_charge;
            $tax = (float) $this->tax;

            $commissionPct = (float) ($this->restaurant?->commission_percentage ?: 10);
            $partnerSharePct = (float) ($setting->delivery_partner_share ?: 85);
            $platformSharePct = (float) ($setting->platform_share ?: 15);

            $commission = round($foodTotal * $commissionPct / 100, 2);
            $restaurantPayout = round($foodTotal - $commission, 2);
            $deliveryPartnerPayout = round($deliveryCharge * $partnerSharePct / 100, 2);
            $platformDeliveryShare = round($deliveryCharge * $platformSharePct / 100, 2);
            $platformPayout = round($commission + $platformDeliveryShare + $tax, 2);

            // 1. If COD, debit delivery partner wallet for the cash collected from customer (allows negative balance)
            if ($this->isCodOrder() && $this->delivery_partner_id && (float) $this->total > 0) {
                $deliveryPartner = User::find($this->delivery_partner_id);
                if ($deliveryPartner) {
                    $deliveryPartner->getOrCreateWallet()->debit(
                        (float) $this->total,
                        'cod_cash_collected',
                        $this->id,
                        "COD cash collected from customer for Order #{$this->id}",
                        [
                            'order_id' => $this->id,
                            'total' => (float) $this->total,
                            'payment_method' => $this->payment_method,
                        ],
                        true // allow negative balance for COD cash holding
                    );
                }
            }

            // 2. Credit Delivery Partner Wallet with their delivery charge share
            if ($this->delivery_partner_id && $deliveryPartnerPayout > 0) {
                $deliveryPartner = User::find($this->delivery_partner_id);
                if ($deliveryPartner) {
                    $deliveryPartner->getOrCreateWallet()->credit(
                        $deliveryPartnerPayout,
                        'delivery_partner_payout',
                        $this->id,
                        "Delivery earnings payout for Order #{$this->id} ({$partnerSharePct}% of delivery charge)",
                        [
                            'order_id' => $this->id,
                            'delivery_charge' => $deliveryCharge,
                            'partner_share_pct' => $partnerSharePct,
                        ]
                    );
                }
            }

            // 3. Credit Restaurant Owner Wallet with food payout (food total minus admin commission)
            if ($this->restaurant && $this->restaurant->user_id && $restaurantPayout > 0) {
                $restaurantOwner = User::find($this->restaurant->user_id);
                if ($restaurantOwner) {
                    $restaurantOwner->getOrCreateWallet()->credit(
                        $restaurantPayout,
                        'order_restaurant_payout',
                        $this->id,
                        "Food earnings payout for Order #{$this->id} at {$this->restaurant->restaurant_name} (after {$commissionPct}% admin commission)",
                        [
                            'order_id' => $this->id,
                            'food_total' => $foodTotal,
                            'commission_pct' => $commissionPct,
                            'commission' => $commission,
                            'restaurant_payout' => $restaurantPayout,
                        ]
                    );
                }
            }

            // 4. Record Incomes for Platform / Restaurant / Partner accounting
            Income::where('order_id', $this->id)->delete();

            $taxPct = (float) $setting->taxGstPercentage();

            $entries = [
                [
                    'income_type' => Income::TYPE_RESTAURANT_COMMISSION,
                    'recipient_type' => Income::RECIPIENT_PLATFORM,
                    'recipient_id' => null,
                    'user_id' => null,
                    'restaurant_id' => $this->restaurant_id,
                    'base_amount' => $foodTotal,
                    'percentage' => $commissionPct,
                    'amount' => $commission,
                ],
                [
                    'income_type' => Income::TYPE_TAX,
                    'recipient_type' => Income::RECIPIENT_PLATFORM,
                    'recipient_id' => null,
                    'user_id' => null,
                    'restaurant_id' => $this->restaurant_id,
                    'base_amount' => $foodTotal,
                    'percentage' => $taxPct,
                    'amount' => $tax,
                ],
                [
                    'income_type' => Income::TYPE_PLATFORM_SHARE,
                    'recipient_type' => Income::RECIPIENT_PLATFORM,
                    'recipient_id' => null,
                    'user_id' => null,
                    'restaurant_id' => $this->restaurant_id,
                    'base_amount' => $deliveryCharge,
                    'percentage' => $platformSharePct,
                    'amount' => $platformDeliveryShare,
                ],
                [
                    'income_type' => Income::TYPE_RESTAURANT_PAYOUT,
                    'recipient_type' => Income::RECIPIENT_RESTAURANT,
                    'recipient_id' => $this->restaurant?->user_id,
                    'user_id' => $this->restaurant?->user_id,
                    'restaurant_id' => $this->restaurant_id,
                    'base_amount' => $foodTotal,
                    'percentage' => $commissionPct,
                    'amount' => $restaurantPayout,
                ],
                [
                    'income_type' => Income::TYPE_DELIVERY_PARTNER_PAYOUT,
                    'recipient_type' => Income::RECIPIENT_DELIVERY_PARTNER,
                    'recipient_id' => $this->delivery_partner_id,
                    'user_id' => $this->delivery_partner_id,
                    'restaurant_id' => $this->restaurant_id,
                    'base_amount' => $deliveryCharge,
                    'percentage' => $partnerSharePct,
                    'amount' => $deliveryPartnerPayout,
                ],
            ];

            foreach ($entries as $entry) {
                Income::create($entry + [
                    'order_id' => $this->id,
                    'paid_at' => now(),
                ]);
            }

            return [
                'food_total' => $foodTotal,
                'delivery_charge' => $deliveryCharge,
                'tax' => $tax,
                'commission_pct' => $commissionPct,
                'commission' => $commission,
                'restaurant_payout' => $restaurantPayout,
                'partner_share_pct' => $partnerSharePct,
                'platform_share_pct' => $platformSharePct,
                'delivery_partner_payout' => $deliveryPartnerPayout,
                'platform_delivery_share' => $platformDeliveryShare,
                'platform_payout' => $platformPayout,
            ];
        });
    }
}
