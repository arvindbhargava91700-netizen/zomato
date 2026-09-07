<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_name');
            $table->string('code')->unique();
            $table->string('code_type')->nullable()->comment('welcome, referral, festival, custom');
            $table->enum('discount_type', ['percentage', 'flat'])->default('percentage');
            $table->decimal('discount_value', 10, 2);
            $table->decimal('minimum_order_amount', 10, 2)->default(0);
            $table->decimal('maximum_discount_amount', 10, 2)->nullable();
            $table->integer('per_user_limit')->default(1);
            $table->unsignedBigInteger('assigned_user_id')->nullable();
            $table->enum('usage_status', ['unused', 'used'])->default('unused');
            $table->timestamp('used_at')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['campaign_name']);
            $table->index(['usage_status']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
