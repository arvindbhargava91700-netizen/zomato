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
        Schema::table('dining_offers', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->text('admin_remarks')->nullable()->after('approval_status');
            $table->timestamp('approved_at')->nullable()->after('admin_remarks');
            $table->foreignId('approved_by')->nullable()->constrained('admins')->onDelete('set null')->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dining_offers', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approval_status', 'admin_remarks', 'approved_at', 'approved_by']);
        });
    }
};
