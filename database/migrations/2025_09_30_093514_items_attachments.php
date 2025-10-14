<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feed_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('members_feed_id'); 
            $table->string('attachment_path'); 
            $table->timestamps();
            $table->foreign('members_feed_id')
                ->references('id')
                ->on('members_feed')
                ->onDelete('cascade');
        }); 


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
