<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->string('delivery_option')->nullable();
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
