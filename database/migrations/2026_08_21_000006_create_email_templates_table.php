<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->comment('machine name used in code, e.g. order_confirmation');
            $table->string('subject');
            $table->text('body')->comment('HTML body with {{ variable }} placeholders');
            $table->string('category')->nullable()->comment('order, auth, marketing, etc.');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
