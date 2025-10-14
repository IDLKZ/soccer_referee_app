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
        Schema::create('trip_migrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transport_type_id')->constrained('transport_types')->onDelete('cascade');
            $table->foreignId('departure_city_id')->constrained('cities')->onDelete('cascade');
            $table->foreignId('arrival_city_id')->constrained('cities')->onDelete('cascade');
            $table->dateTime('from_date');
            $table->dateTime('to_date');
            $table->text('info')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_migrations');
    }
};
