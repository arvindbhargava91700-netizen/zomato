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
        if (!Schema::hasTable('withdrawals')) {
            Schema::create('withdrawals', function (Blueprint $table) {
                $table->id();
                $table->string('withdrawal_number')->unique();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('wallet_id')->nullable()->constrained('wallets')->nullOnDelete();
                $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
                $table->decimal('amount', 10, 2);
                $table->decimal('fee', 10, 2)->default(0.00);
                $table->decimal('net_amount', 10, 2);
                $table->string('payout_method')->default('bank_transfer'); // bank_transfer, upi, cash, other
                $table->json('account_details')->nullable(); // bank_name, account_number, ifsc_code, upi_id, holder_name
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('notes')->nullable();
                $table->text('admin_remarks')->nullable();
                $table->string('admin_transaction_id')->nullable(); // UTR / payment reference
                $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
                $table->timestamp('requested_at')->useCurrent();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
