<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseDayVersesTable extends Migration
{
    public function up()
    {
        Schema::create('course_day_verses', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->integer('course_content_id');  // Column for course content ID
            $table->integer('testament');  // Column for testament (integer, required)
            $table->integer('book');  // Column for book (integer, required)
            $table->integer('chapter');  // Column for chapter (integer, required)
            $table->integer('verse_from');  // Column for the starting verse (integer, required)
            $table->integer('verse_to');  // Column for the ending verse (integer, required)
            $table->integer('status')->default(1);  // Column for status, default value 1
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_day_verses');
    }
}
