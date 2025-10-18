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
        Schema::table('trip_migrations', function (Blueprint $table) {
            $table->foreignId('trip_id')->after('id')->constrained('trips')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_migrations', function (Blueprint $table) {
            $table->dropForeign(['trip_id']);
            $table->dropColumn('trip_id');
        });
    }
};
