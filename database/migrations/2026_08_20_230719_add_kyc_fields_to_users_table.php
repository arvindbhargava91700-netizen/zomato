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
        Schema::table('users', function (Blueprint $table) {
            $table->string('age')->nullable()->after('name');
            $table->string('aadhar_front')->nullable()->after('age');
            $table->string('aadhar_back')->nullable()->after('aadhar_front');
            $table->string('passport_photo')->nullable()->after('aadhar_back');
            $table->string('rc_image')->nullable()->after('passport_photo');
            $table->string('vehicle_number')->nullable()->after('rc_image');
            $table->string('aadhar_card')->nullable()->after('city_id');
            $table->string('pan_card')->nullable()->after('aadhar_card');
            $table->string('bank_account')->nullable()->after('pan_card');
            $table->string('ifsc_code')->nullable()->after('bank_account');
            $table->string('bank_name')->nullable()->after('ifsc_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'age',
                'aadhar_front',
                'aadhar_back',
                'passport_photo',
                'rc_image',
                'vehicle_number',
                'aadhar_card',
                'pan_card',
                'bank_account',
                'ifsc_code',
                'bank_name',
            ]);
        });
    }
};