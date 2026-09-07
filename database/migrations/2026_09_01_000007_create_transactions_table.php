<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_number', 50)->unique();
                $table->string('type', 50)->default('table_booking');
                $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
                $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
                $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->cascadeOnDelete();
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('customer_name')->nullable();
                $table->string('phone', 30)->nullable();
                $table->date('book_date')->nullable();
                $table->string('book_time', 50)->nullable();
                $table->integer('guests')->nullable();
                $table->decimal('total_amount', 10, 2)->default(0.00);
                $table->decimal('admin_share_percent', 5, 2)->default(20.00);
                $table->decimal('admin_amount', 10, 2)->default(0.00);
                $table->decimal('restaurant_share_percent', 5, 2)->default(80.00);
                $table->decimal('restaurant_amount', 10, 2)->default(0.00);
                $table->string('payment_method', 50)->default('razorpay');
                $table->string('payment_gateway', 50)->nullable()->default('razorpay');
                $table->string('payment_id', 100)->nullable();
                $table->string('order_reference_id', 100)->nullable();
                $table->string('signature', 255)->nullable();
                $table->string('payment_status', 30)->default('paid');
                $table->string('status', 30)->default('success');
                $table->text('note')->nullable();
                $table->json('meta_data')->nullable();
                $table->dateTime('paid_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
