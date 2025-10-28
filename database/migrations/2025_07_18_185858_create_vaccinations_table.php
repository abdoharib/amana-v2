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
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->date('vaccination_date');
            $table->time('vaccination_time');
            $table->foreignId('guardian_id')->constrained('users')->constrained()->onDelete('cascade');
            $table->enum('status', ['قادمة', 'منجزة', 'فائتة'])->default('قادمة');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccinations');
    }
};
