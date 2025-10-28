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
        Schema::create('prayer_contents', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->string('audio_file');
            $table->foreignId('prayer_id')->constrained('prayers')->onDelete('cascade'); // Deletes details if the parent story is deleted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_contents');
    }
};
