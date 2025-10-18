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
        Schema::table('trip_hotels', function (Blueprint $table) {
            // Drop the incorrect foreign key constraint
            $table->dropForeign(['room_id']);

            // Add correct foreign key constraint to hotel_rooms table
            $table->foreign('room_id')->references('id')->on('hotel_rooms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_hotels', function (Blueprint $table) {
            // Revert to the original incorrect constraint
            $table->dropForeign(['room_id']);
            $table->foreign('room_id')->references('id')->on('hotels')->onDelete('set null');
        });
    }
};
