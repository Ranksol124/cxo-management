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
        Schema::table('gold_and_silver_tables', function (Blueprint $table) {
            Schema::table('gold_member_details', function (Blueprint $table) {
                $table->string('organization_business')->nullable()->change();
            });

            Schema::table('silver_member_details', function (Blueprint $table) {
                $table->string('organization_business')->nullable()->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gold_and_silver_tables', function (Blueprint $table) {
            Schema::table('gold_member_details', function (Blueprint $table) {
                $table->string('organization_business')->nullable(false)->change();
            });

            Schema::table('silver_member_details', function (Blueprint $table) {
                $table->string('organization_business')->nullable(false)->change();
            });
        });
    }
};
