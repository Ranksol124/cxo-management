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
        Schema::table('members', function (Blueprint $table) {
            // Purana city column delete
            $table->dropColumn('city');

            // Naye columns add
            $table->unsignedBigInteger('country_id')->nullable()->after('contact');
            $table->unsignedBigInteger('state_id')->nullable()->after('country_id');
            $table->unsignedBigInteger('city_id')->nullable()->after('state_id');

        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Naye columns delete
            $table->dropForeign(['country_id']);
            $table->dropForeign(['state_id']);
            $table->dropForeign(['city_id']);

            $table->dropColumn(['country_id', 'state_id', 'city_id']);

            // Purana city column wapas laana
            $table->string('city')->nullable()->after('contact');
        });
    }
};
