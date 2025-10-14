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
        Schema::create('act_of_work_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('act_of_work_id')->constrained('act_of_works')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('common_services')->onDelete('cascade');
            $table->string('price_per');
            $table->decimal('qty', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->date('executed_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('act_of_work_services');
    }
};
