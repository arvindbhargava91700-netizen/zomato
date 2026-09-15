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
            if (!Schema::hasColumn('users', 'holder_name')) {
                $table->string('holder_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'bank_account')) {
                $table->string('bank_account')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('users', 'ifsc_code')) {
                $table->string('ifsc_code')->nullable()->after('bank_account');
            }
            if (!Schema::hasColumn('users', 'branch_name')) {
                $table->string('branch_name')->nullable()->after('ifsc_code');
            }
            if (!Schema::hasColumn('users', 'upi_id')) {
                $table->string('upi_id')->nullable()->after('branch_name');
            }
        });

        Schema::table('restaurants', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurants', 'holder_name')) {
                $table->string('holder_name')->nullable()->after('owner_name');
            }
            if (!Schema::hasColumn('restaurants', 'branch_name')) {
                $table->string('branch_name')->nullable()->after('ifsc_code');
            }
            if (!Schema::hasColumn('restaurants', 'upi_id')) {
                $table->string('upi_id')->nullable()->after('qr_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('users', 'holder_name')) $cols[] = 'holder_name';
            if (Schema::hasColumn('users', 'branch_name')) $cols[] = 'branch_name';
            if (Schema::hasColumn('users', 'upi_id')) $cols[] = 'upi_id';
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('restaurants', 'holder_name')) $cols[] = 'holder_name';
            if (Schema::hasColumn('restaurants', 'branch_name')) $cols[] = 'branch_name';
            if (Schema::hasColumn('restaurants', 'upi_id')) $cols[] = 'upi_id';
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
