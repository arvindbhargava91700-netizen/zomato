<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->string('table_number'); // e.g. T1, T2, Table-1, Booth-A
            $table->unsignedInteger('capacity')->default(2); // seating capacity
            $table->enum('status', ['available', 'inactive', 'maintenance'])->default('available');
            $table->timestamps();

            $table->unique(['restaurant_id', 'table_number']);
            $table->index(['restaurant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};
