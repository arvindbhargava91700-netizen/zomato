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
        if (!Schema::hasTable('wallet_transactions')) {
            Schema::create('wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('transaction_number', 50)->unique();
                $table->enum('type', ['credit', 'debit']);
                $table->decimal('amount', 10, 2);
                $table->decimal('balance_before', 10, 2)->default(0.00);
                $table->decimal('balance_after', 10, 2)->default(0.00);
                $table->string('source', 60)->default('manual');
                $table->unsignedBigInteger('reference_id')->nullable()->index();
                $table->string('description')->nullable();
                $table->json('meta_data')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
