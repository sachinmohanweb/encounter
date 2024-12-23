<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGotQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('got_questions', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->text('question');  // Question column with text data type
            $table->integer('category_id');  // Category ID column (foreign key)
            $table->integer('sub_category_id');  // Sub-category ID column (foreign key)
            $table->text('answer');  // Answer column with text data type
            $table->integer('status')->default(1);  // Status column with default value of 1
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('got_questions');
    }
}

