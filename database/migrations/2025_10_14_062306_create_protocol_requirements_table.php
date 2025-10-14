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
        Schema::create('protocol_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id')->constrained('leagues')->onDelete('cascade');
            $table->foreignId('match_id')->nullable()->constrained('matches')->onDelete('cascade');
            $table->foreignId('judge_type_id')->constrained('judge_types')->onDelete('cascade');
            $table->json('example_file_url')->nullable();
            $table->string('title_ru', 255);
            $table->string('title_kk', 255)->nullable();
            $table->string('title_en', 255)->nullable();
            $table->text('info_ru');
            $table->text('info_kk')->nullable();
            $table->text('info_en')->nullable();
            $table->boolean('is_required')->default(true);
            $table->json('extensions');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protocol_requirements');
    }
};
