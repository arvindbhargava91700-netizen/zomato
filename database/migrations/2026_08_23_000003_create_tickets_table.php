<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique();
            $table->string('user_type'); // customer, restaurant, delivery_partner
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('category'); // order, payment, delivery, account, other
            $table->string('subject');
            $table->text('description');
            $table->string('attachment')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('open'); // open, in_progress, resolved, closed, reopened
            $table->text('resolution_note')->nullable();
            $table->unsignedTinyInteger('rating')->nullable(); // 1-5
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
