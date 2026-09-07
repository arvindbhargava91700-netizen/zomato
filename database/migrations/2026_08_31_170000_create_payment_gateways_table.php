<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_key', 50)->unique();
            $table->string('name', 100);
            $table->string('display_name', 150)->nullable();
            $table->string('badge_color', 50)->default('#072654');
            $table->string('icon', 100)->nullable()->default('ri-flashlight-fill');
            $table->enum('mode', ['sandbox', 'live'])->default('sandbox');
            $table->boolean('is_active')->default(true);
            $table->text('key_id')->nullable();
            $table->text('key_secret')->nullable();
            $table->text('webhook_secret')->nullable();
            $table->string('merchant_id', 100)->nullable();
            $table->string('currency', 10)->default('INR');
            $table->string('theme_color', 50)->default('#072654');
            $table->text('description')->nullable();
            $table->json('additional_settings')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed initial gateways with user-provided Razorpay credentials
        DB::table('payment_gateways')->insert([
            [
                'gateway_key' => 'razorpay',
                'name' => 'Razorpay',
                'display_name' => 'Razorpay (UPI, GPay, PhonePe, Cards & NetBanking)',
                'badge_color' => '#072654',
                'icon' => 'ri-flashlight-fill',
                'mode' => 'sandbox',
                'is_active' => true,
                'key_id' => 'rzp_test_TWIAoYszpcVthe',
                'key_secret' => 'EyaGehL4Ok8UttyrPDipNTcO',
                'webhook_secret' => null,
                'merchant_id' => null,
                'currency' => 'INR',
                'theme_color' => '#072654',
                'description' => 'Accept payments via UPI, Google Pay, PhonePe, Credit/Debit Cards, and NetBanking across India.',
                'additional_settings' => json_encode(['auto_capture' => true]),
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gateway_key' => 'phonepe',
                'name' => 'PhonePe',
                'display_name' => 'PhonePe Payment Gateway',
                'badge_color' => '#5f259f',
                'icon' => 'ri-smartphone-line',
                'mode' => 'sandbox',
                'is_active' => false,
                'key_id' => null,
                'key_secret' => null,
                'webhook_secret' => null,
                'merchant_id' => null,
                'currency' => 'INR',
                'theme_color' => '#5f259f',
                'description' => 'Direct UPI intent and QR payments via PhonePe gateway.',
                'additional_settings' => null,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gateway_key' => 'paytm',
                'name' => 'Paytm',
                'display_name' => 'Paytm Payment Gateway',
                'badge_color' => '#00b9f5',
                'icon' => 'ri-wallet-3-line',
                'mode' => 'sandbox',
                'is_active' => false,
                'key_id' => null,
                'key_secret' => null,
                'webhook_secret' => null,
                'merchant_id' => null,
                'currency' => 'INR',
                'theme_color' => '#00b9f5',
                'description' => 'Paytm Wallet, UPI, and Postpaid payments.',
                'additional_settings' => null,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gateway_key' => 'payu',
                'name' => 'PayU Money',
                'display_name' => 'PayU Money Payment Gateway',
                'badge_color' => '#a4c639',
                'icon' => 'ri-bank-card-line',
                'mode' => 'sandbox',
                'is_active' => false,
                'key_id' => null,
                'key_secret' => null,
                'webhook_secret' => null,
                'merchant_id' => null,
                'currency' => 'INR',
                'theme_color' => '#a4c639',
                'description' => 'PayU payment gateway processing.',
                'additional_settings' => null,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gateway_key' => 'paypal',
                'name' => 'PayPal',
                'display_name' => 'PayPal Global Payments',
                'badge_color' => '#003087',
                'icon' => 'ri-paypal-fill',
                'mode' => 'sandbox',
                'is_active' => false,
                'key_id' => null,
                'key_secret' => null,
                'webhook_secret' => null,
                'merchant_id' => null,
                'currency' => 'USD',
                'theme_color' => '#003087',
                'description' => 'International card and wallet payments via PayPal.',
                'additional_settings' => null,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
