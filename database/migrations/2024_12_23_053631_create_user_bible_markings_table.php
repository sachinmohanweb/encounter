<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBibleMarkingsTable extends Migration
{
    public function up()
    {
        Schema::create('user_bible_markings', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // User ID with foreign key
            $table->integer('type')->default(1);  // Type column (note, bookmark, color) with default value of 1
            $table->integer('statement_id');  // Foreign key to statements
            $table->longText('data');  // Data column for storing long text
            $table->integer('status')->default(1);  // Status column with a default value of 1
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_bible_markings');
    }
}

