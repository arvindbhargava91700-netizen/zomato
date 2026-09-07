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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->foreignId('food_category_id')->constrained('food_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('sku')->nullable();
            $table->enum('food_type', ['veg', 'non_veg', 'egg'])->default('veg');
            $table->boolean('is_veg')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_recommended')->default(false);
            $table->boolean('is_spicy')->default(false);
            $table->integer('preparation_time')->nullable()->comment('Preparation time in minutes');
            $table->decimal('base_price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->decimal('tax_percentage', 5, 2)->default(0.00);
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['restaurant_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
