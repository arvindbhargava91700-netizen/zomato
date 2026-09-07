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
        Schema::create('audit_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')->nullable();

            $table->string('user_name')->nullable();

            $table->string('module');

            $table->string('action');

            $table->json('old_data')->nullable();

            $table->json('new_data')->nullable();

            $table->string('ip_address')->nullable();

            $table->string('browser')->nullable();

            $table->string('platform')->nullable();

            $table->string('city')->nullable();

            $table->string('state')->nullable();

            $table->string('country')->nullable();

            $table->text('description')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
