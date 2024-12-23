<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCustomNotesTable extends Migration
{
    public function up()
    {
        Schema::create('user_custom_notes', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // User ID with foreign key
            $table->longText('note_text');  // Text for storing custom notes
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');  // Foreign key for tags
            $table->integer('status')->default(1);  // Status column with a default value of 1
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_custom_notes');
    }
}

