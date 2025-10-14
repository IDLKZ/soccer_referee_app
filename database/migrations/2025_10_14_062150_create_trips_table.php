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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('departure_city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->foreignId('arrival_city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->string('name', 255)->nullable();
            $table->date('departure_date')->nullable();
            $table->date('return_date')->nullable();
            $table->foreignId('transport_type_id')->constrained('transport_types')->onDelete('cascade');
            $table->foreignId('judge_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('logist_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('judge_status')->default(0)->comment('0 - waiting, -1 - reject, 1 - ok');
            $table->integer('first_status')->default(0)->comment('0 - waiting, -1 - reject, 1 - ok');
            $table->integer('final_status')->default(0)->comment('0 - waiting, -1 - reject, 1 - ok');
            $table->text('info')->nullable();
            $table->text('judge_comment')->nullable();
            $table->text('first_comment')->nullable();
            $table->text('final_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
