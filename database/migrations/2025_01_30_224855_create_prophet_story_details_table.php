<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProphetStoryDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('prophet_story_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prophet_story_id')->constrained('prophet_stories')->onDelete('cascade'); // Deletes details if the parent story is deleted
            $table->string('title');
            $table->text('content');
            $table->string('image_path')->nullable();
            $table->string('audio_path');
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('prophet_story_details');
    }
}

