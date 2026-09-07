<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantTable extends Model
{
    use HasFactory;

    protected $table = 'restaurant_tables';

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_MAINTENANCE = 'maintenance';

    protected $fillable = [
        'restaurant_id',
        'table_number',
        'capacity',
        'status',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'restaurant_table_id');
    }

    public function isAvailableForSlot(string $date, string $timeSlot): bool
    {
        if ($this->status !== self::STATUS_AVAILABLE) {
            return false;
        }

        return !Booking::where('restaurant_table_id', $this->id)
            ->where('book_date', $date)
            ->where('book_time', $timeSlot)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_ACCEPTED])
            ->exists();
    }
}
