<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyBibleVersesTable extends Migration
{
    public function up()
    {
        Schema::create('daily_bible_verses', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->integer('bible_id');  // Column for bible ID
            $table->integer('testament_id');  // Column for testament ID
            $table->integer('book_id');  // Column for book ID
            $table->integer('chapter_id');  // Column for chapter ID
            $table->integer('verse_id');  // Column for verse ID
            $table->date('date')->nullable();  // Column for date, nullable
            $table->integer('theme_id')->default(1);  // Column for theme ID, default value 1
            $table->integer('status')->default(1);  // Column for status, default value 1
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_bible_verses');
    }
}

