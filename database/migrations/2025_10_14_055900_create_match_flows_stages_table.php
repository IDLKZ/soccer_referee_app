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
        Schema::create('match_flows_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('category_operations')->onDelete('cascade');
            $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('flow_id')->constrained('match_flows')->onDelete('cascade');
            $table->foreignId('responsible_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('is_passed');
            $table->text('comment');
            $table->integer('result')->default(0)->comment('-1 - rejected, 0 - waiting, 1 - passed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_flows_stages');
    }
};
