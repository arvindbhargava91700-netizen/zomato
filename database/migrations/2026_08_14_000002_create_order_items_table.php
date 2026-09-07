<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('food_id')->nullable();
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('qty')->default(1);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
