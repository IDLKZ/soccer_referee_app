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
        Schema::create('act_of_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('act_id')->constrained('act_of_works')->onDelete('cascade');
            $table->json('file_urls');
            $table->foreignId('checked_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('act_of_payments');
    }
};
