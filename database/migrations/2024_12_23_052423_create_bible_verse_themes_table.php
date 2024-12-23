<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBibleVerseThemesTable extends Migration
{
    public function up()
    {
        Schema::create('bible_verse_themes', function (Blueprint $table) {
            $table->id();  // Automatically creates an auto-incrementing primary key column
            $table->string('name', 256);  // Column for the theme name, varchar(256)
            $table->integer('status')->default(1);  // Column for status, default value 1
            $table->timestamps();  // Creates 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('bible_verse_themes');
    }
}
