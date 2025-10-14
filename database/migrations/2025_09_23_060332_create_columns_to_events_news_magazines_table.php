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
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'website_preview')) {
                $table->boolean('website_preview')->default(true);
            }
        });

        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'website_preview')) {
                $table->boolean('website_preview')->default(true);
            }
        });

        Schema::table('magazines', function (Blueprint $table) {
            if (!Schema::hasColumn('magazines', 'website_preview')) {
                $table->boolean('website_preview')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('website_preview');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('website_preview');
        });

        Schema::table('magazines', function (Blueprint $table) {
            $table->dropColumn('website_preview');
        });
    }
};
