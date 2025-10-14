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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id')->constrained('leagues')->onDelete('cascade');
            $table->foreignId('season_id')->constrained('seasons')->onDelete('cascade');
            $table->foreignId('stadium_id')->constrained('stadiums')->onDelete('cascade');
            $table->foreignId('current_operation_id')->constrained('operations')->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->foreignId('owner_club_id')->constrained('clubs')->onDelete('cascade');
            $table->foreignId('guest_club_id')->constrained('clubs')->onDelete('cascade');
            $table->foreignId('winner_id')->nullable()->constrained('clubs')->onDelete('set null');
            $table->integer('owner_point')->nullable();
            $table->integer('guest_point')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_finished')->default(false);
            $table->boolean('is_canceled')->default(false);
            $table->text('cancel_reason')->nullable();
            $table->json('info')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
