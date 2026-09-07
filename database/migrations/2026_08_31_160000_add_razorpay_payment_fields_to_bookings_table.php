<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'cover_charge')) {
                $table->decimal('cover_charge', 10, 2)->default(0.00)->after('guests');
            }
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->default('razorpay')->after('cover_charge');
            }
            if (!Schema::hasColumn('bookings', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending')->after('payment_method');
            }
            if (!Schema::hasColumn('bookings', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id', 100)->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('bookings', 'razorpay_order_id')) {
                $table->string('razorpay_order_id', 100)->nullable()->after('razorpay_payment_id');
            }
            if (!Schema::hasColumn('bookings', 'razorpay_signature')) {
                $table->string('razorpay_signature', 255)->nullable()->after('razorpay_order_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'cover_charge',
                'payment_method',
                'payment_status',
                'razorpay_payment_id',
                'razorpay_order_id',
                'razorpay_signature',
            ]);
        });
    }
};
