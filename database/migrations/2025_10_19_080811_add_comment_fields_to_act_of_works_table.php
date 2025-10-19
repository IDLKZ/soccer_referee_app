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
        Schema::table('act_of_works', function (Blueprint $table) {
            $table->text('judge_comment')->nullable()->after('judge_status');
            $table->text('control_comment')->nullable()->after('judge_comment');
            $table->text('first_financial_comment')->nullable()->after('control_comment');
            $table->text('last_financial_comment')->nullable()->after('first_financial_comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('act_of_works', function (Blueprint $table) {
            $table->dropColumn(['judge_comment', 'control_comment', 'first_financial_comment', 'last_financial_comment']);
        });
    }
};
