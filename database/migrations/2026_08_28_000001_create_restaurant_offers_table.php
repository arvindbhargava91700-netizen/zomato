<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->string('title');
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('minimum_order_amount', 10, 2)->nullable();
            $table->decimal('maximum_discount_amount', 10, 2)->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('banner')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_offers');
    }
};
