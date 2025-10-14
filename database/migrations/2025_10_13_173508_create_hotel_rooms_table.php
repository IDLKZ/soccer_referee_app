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
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->nullable()->constrained('hotels')->onDelete('set null');
            $table->json('image_url')->nullable();
            $table->text('title_ru');
            $table->text('title_kk')->nullable();
            $table->text('title_en')->nullable();
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->integer('bed_quantity');
            $table->decimal('room_size', 8, 2);
            $table->boolean('air_conditioning')->default(false);
            $table->boolean('private_bathroom')->default(false);
            $table->boolean('tv')->default(false);
            $table->boolean('wifi')->default(false);
            $table->boolean('smoking_allowed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_rooms');
    }
};
