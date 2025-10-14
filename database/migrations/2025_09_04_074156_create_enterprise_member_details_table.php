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
        Schema::create('enterprise_member_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('organization')->nullable();
            $table->string('organization_business')->nullable();
            $table->string('organization_contact')->nullable();
            $table->string('second_member_name')->nullable();
            $table->string('second_member_contact')->nullable();
            $table->string('second_member_designation')->nullable();
            $table->string('second_member_email')->nullable();
            $table->string('organization_status')->nullable();
            $table->integer('number_of_employees')->default(1);
            $table->boolean('payment_term')->default(false);
            $table->string('mailing_address')->nullable();
            $table->text('expectation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprise_member_details');
    }
};
