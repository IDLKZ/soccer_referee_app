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
        Schema::create('category_operations', function (Blueprint $table) {
            $table->id();
            $table->string('title_ru', 255);
            $table->string('title_kk', 255)->nullable();
            $table->string('title_en', 255)->nullable();
            $table->string('value', 280)->unique();
            $table->integer('level')->default(1);
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
        Schema::dropIfExists('category_operations');
    }
};
