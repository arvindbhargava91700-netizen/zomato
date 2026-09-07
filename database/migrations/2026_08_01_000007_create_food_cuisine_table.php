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
        Schema::create('food_cuisine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_id')->constrained('foods')->onDelete('cascade');
            $table->foreignId('cuisine_id')->constrained('cuisines')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['food_id', 'cuisine_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_cuisine');
    }
};
