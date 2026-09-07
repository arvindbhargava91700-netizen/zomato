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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('logo_lg')->nullable();
            $table->string('logo_sm')->nullable();
            $table->string('favicon')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('currency', 10)->default('INR');
            $table->string('timezone', 60)->default('Asia/Kolkata');
            $table->string('date_format', 30)->default('d/m/Y');
            $table->string('tax_gst')->nullable();
            $table->string('invoice_prefix', 20)->nullable();
            $table->string('receipt_prefix', 20)->nullable();
            $table->string('payment_qr')->nullable();
            $table->text('payment_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};