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
        Schema::table('match_judges', function (Blueprint $table) {
            $table->foreignId('operation_id')->nullable()->after('final_status')->constrained('operations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_judges', function (Blueprint $table) {
            $table->dropForeign(['operation_id']);
            $table->dropColumn('operation_id');
        });
    }
};
