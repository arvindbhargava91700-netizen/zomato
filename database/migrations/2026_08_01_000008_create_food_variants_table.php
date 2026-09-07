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
        Schema::create('food_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_id')->constrained('foods')->onDelete('cascade');
            $table->string('variant_name');
            $table->string('sku')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('weight_unit')->nullable();
            $table->string('serving_size')->nullable();
            $table->integer('preparation_time')->nullable()->comment('Preparation time in minutes');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
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
        Schema::dropIfExists('food_variants');
    }
};
