<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGQCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('g_q_categories', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->string('name', 256);  // Category name column
            $table->integer('status')->default(1);  // Status column with default value of 1
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('g_q_categories');
    }
}

