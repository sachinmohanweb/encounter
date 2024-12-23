<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseContentsTable extends Migration
{
    public function up()
    {
        Schema::create('course_contents', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->integer('course_id');  // Column for course ID
            $table->integer('day');  // Column for the day of the course
            $table->text('text_description')->nullable();  // Column for the text description (nullable)
            $table->string('audio_file', 256)->nullable();  // Column for the audio file URL (nullable)
            $table->string('website_link', 256)->nullable();  // Column for the website link (nullable)
            $table->string('image', 256)->nullable();  // Column for the image URL (nullable)
            $table->string('documents', 256)->nullable();  // Column for the documents (nullable)
            $table->integer('status')->default(1);  // Column for status, default value 1
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_contents');
    }
}

