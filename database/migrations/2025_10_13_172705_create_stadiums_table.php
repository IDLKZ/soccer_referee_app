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
        Schema::create('stadiums', function (Blueprint $table) {
            $table->id();
            $table->text('image_url')->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->string('title_ru', 255);
            $table->string('title_kk', 255)->nullable();
            $table->string('title_en', 255)->nullable();
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->text('address_ru')->nullable();
            $table->text('address_kk')->nullable();
            $table->text('address_en')->nullable();
            $table->text('lat')->nullable();
            $table->text('lon')->nullable();
            $table->date('built_date')->nullable();
            $table->text('phone')->nullable();
            $table->text('website')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stadiums');
    }
};
