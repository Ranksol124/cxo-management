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
        Schema::create('silver_member_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('organization')->nullable();
            $table->string('organization_status')->nullable();
            $table->integer('number_of_employees')->nullable();
            $table->string('gender')->nullable();
            $table->string('qualification')->nullable();
            $table->decimal('annual_membership_fee', 10, 2)->default(0.00);
            $table->boolean('registration_agreement')->default(true);
            $table->string('payment_timeline')->nullable();
            $table->string('organization_business');
            $table->text('expectation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('silver_member_details');
    }
};
