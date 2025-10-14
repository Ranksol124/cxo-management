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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->string('currency')->default('USD');
            $table->integer('trial_days')->default(0);
            $table->integer('max_users')->default(0);
            $table->integer('max_projects')->default(0);
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('interval')->default('month');
            $table->integer('interval_count')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
