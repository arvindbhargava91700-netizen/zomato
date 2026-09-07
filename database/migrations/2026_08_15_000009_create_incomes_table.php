<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Income table: records every income entry generated when a COD
     * settlement is approved by the admin. Each row belongs to one
     * recipient (platform / restaurant / delivery partner) and carries
     * the income type, base amount, percentage and the paid amount.
     */
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cod_settlement_id')->nullable()->index();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->unsignedBigInteger('restaurant_id')->nullable()->index();
            $table->string('income_type')->index();
            $table->string('recipient_type')->index();
            $table->unsignedBigInteger('recipient_id')->nullable()->index();
            $table->decimal('base_amount', 10, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};