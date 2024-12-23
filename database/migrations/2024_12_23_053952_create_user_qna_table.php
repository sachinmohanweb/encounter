<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserQnaTable extends Migration
{
    public function up()
    {
        Schema::create('user_qna', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Foreign key for user
            $table->text('question');  // Text column for the question
            $table->text('answer')->nullable();  // Nullable text column for the answer
            $table->integer('status')->default(1);  // Status column with default value of 1
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_qna');
    }
}

