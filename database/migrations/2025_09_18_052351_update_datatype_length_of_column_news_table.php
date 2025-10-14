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
        Schema::table('news', function (Blueprint $table) {
            // title ko 1000 length ka string banado
            $table->string('title', 1000)->change();

            // description ko text bana do (length nahi hoti text ki)
            $table->text('description')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // wapas purani state me le ao
            $table->string('title', 255)->change();
            $table->text('description')->nullable(false)->change(); 
            // ya agar pehle string tha to:
            // $table->string('description', 1000)->nullable()->change();
        });
    }
};
