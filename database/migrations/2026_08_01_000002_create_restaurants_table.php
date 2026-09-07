<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->string('restaurant_name');
            $table->string('restaurant_slug')->unique();
            $table->string('owner_name');
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->text('description')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('fssai_number')->nullable();
            $table->text('address');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->decimal('minimum_order_amount', 8, 2)->default(0.00);
            $table->decimal('delivery_radius', 8, 2)->default(5.00);
            $table->integer('estimated_delivery_time')->default(30);
            $table->decimal('commission_percentage', 5, 2)->default(10.00);
            $table->boolean('is_pure_veg')->default(false);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
