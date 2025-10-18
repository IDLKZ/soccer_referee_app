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
        Schema::table('trips', function (Blueprint $table) {
            // Add unique constraint for match_id and judge_id combination
            $table->unique(['match_id', 'judge_id'], 'trips_match_judge_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique('trips_match_judge_unique');
        });
    }
};
