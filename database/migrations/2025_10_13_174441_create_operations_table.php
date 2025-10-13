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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('category_operations')->onDelete('cascade');
            $table->text('title_ru');
            $table->text('title_kk');
            $table->text('title_en');
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->string('value', 255)->unique();
            $table->json('responsible_roles')->nullable();
            $table->boolean('is_first')->default(false);
            $table->boolean('is_last')->default(false);
            $table->boolean('can_reject')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('result')->default(0);
            $table->foreignId('previous_id')->nullable()->constrained('operations')->onDelete('set null');
            $table->foreignId('next_id')->nullable()->constrained('operations')->onDelete('set null');
            $table->foreignId('on_reject_id')->nullable()->constrained('operations')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
