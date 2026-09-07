<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->foreignId('dining_offer_id')->nullable()->constrained('dining_offers')->onDelete('set null');
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('customer_name');
            $table->string('phone');
            $table->date('book_date');
            $table->time('book_time');
            $table->unsignedInteger('guests')->default(1);
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['restaurant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
