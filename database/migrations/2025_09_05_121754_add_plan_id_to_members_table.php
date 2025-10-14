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
            Schema::table('members', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable()->after('id'); // after id, adjust as needed
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');

            // old column ko hata bhi sakte ho agar zaroorat nahi:
            $table->dropColumn('membership_type');
    });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropForeign(['plan_id']);
                $table->dropColumn('plan_id');

                // rollback me membership_type dobara banani ho to:
                $table->string('membership_type')->nullable();
            });
        });
    }
};
