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
            $table->enum('kyc_status', ['not_submitted', 'pending', 'approved', 'rejected'])
                ->default('not_submitted')
                ->after('status');
            $table->text('kyc_rejected_reason')->nullable()->after('kyc_status');
            $table->timestamp('kyc_reviewed_at')->nullable()->after('kyc_rejected_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kyc_status', 'kyc_rejected_reason', 'kyc_reviewed_at']);
        });
    }
};
