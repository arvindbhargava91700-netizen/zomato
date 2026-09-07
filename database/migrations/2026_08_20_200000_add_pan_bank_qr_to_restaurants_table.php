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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('pan_number')->nullable()->after('fssai_number');
            $table->string('bank_name')->nullable()->after('pan_number');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('ifsc_code')->nullable()->after('account_number');
            $table->string('qr_code')->nullable()->after('ifsc_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['qr_code', 'ifsc_code', 'account_number', 'bank_name', 'pan_number']);
        });
    }
};