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
        Schema::create('act_of_works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('protocol_id')->nullable()->constrained('protocols')->onDelete('set null');
            $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');
            $table->foreignId('judge_id')->constrained('users')->onDelete('cascade');
            $table->text('customer_info');
            $table->boolean('judge_status')->nullable();
            $table->boolean('first_status')->nullable();
            $table->boolean('control_status')->nullable();
            $table->boolean('first_financial_status')->nullable();
            $table->boolean('last_financial_status')->nullable();
            $table->boolean('final_status')->nullable();
            $table->string('dogovor_number');
            $table->string('dogovor_date');
            $table->string('act_number');
            $table->date('act_date');
            $table->boolean('is_ready')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('act_of_works');
    }
};
