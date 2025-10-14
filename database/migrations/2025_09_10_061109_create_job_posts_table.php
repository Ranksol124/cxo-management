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
        Schema::create('job_posts', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description',1000)->nullable();
            $table->string('company')->nullable();
            $table->string('designation')->nullable();
            $table->string('job_type')->nullable();
            $table->string('salary')->nullable();
            $table->date('due_date')->nullable();
            $table->string('link')->nullable();
            $table->string('job_image')->nullable();
            $table->string('address')->nullable();
            $table->boolean('job_status')->default(true);
            $table->boolean('website_preview')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
