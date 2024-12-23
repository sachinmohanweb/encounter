<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->string('course_name', 256);  // Column for the course name
            $table->integer('bible_id');  // Column for the Bible ID
            $table->string('course_creator', 256);  // Column for course creator
            $table->string('creator_designation', 256)->nullable();  // Column for creator designation (nullable)
            $table->string('creator_image', 256)->nullable();  // Column for creator image (nullable)
            $table->integer('no_of_days');  // Column for the number of days
            $table->text('description')->nullable();  // Column for the course description (nullable)
            $table->string('thumbnail', 256)->nullable();  // Column for course thumbnail (nullable)
            $table->longText('intro_commentary')->nullable();  // Column for intro commentary (nullable)
            $table->string('intro_video', 256)->nullable();  // Column for intro video URL (nullable)
            $table->string('intro_audio', 256)->nullable();  // Column for intro audio URL (nullable)
            $table->string('intro_video_thumb', 256)->default('storage/course_intro_thumb/video_thumb.png');  // Column for intro video thumbnail
            $table->integer('status')->default(1);  // Column for status (default value 1)
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
}