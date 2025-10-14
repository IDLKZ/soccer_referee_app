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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->text('image_url')->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->text('title_ru');
            $table->text('title_kk')->nullable();
            $table->text('title_en')->nullable();
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->integer('star')->default(0);
            $table->text('email')->nullable();
            $table->text('address_ru')->nullable();
            $table->text('address_kk')->nullable();
            $table->text('address_en')->nullable();
            $table->text('website_ru')->nullable();
            $table->text('website_kk')->nullable();
            $table->text('website_en')->nullable();
            $table->text('lat')->nullable();
            $table->text('lon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_partner')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
