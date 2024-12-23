<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLMSTable extends Migration
{
    public function up()
    {
        Schema::create('user_l_m_s', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Foreign key for user
            $table->foreignId('course_id')->constrained()->onDelete('cascade');  // Foreign key for course
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');  // Foreign key for batch
            $table->date('start_date');  // Start date for the course
            $table->date('end_date')->nullable();  // Nullable end date for the course
            $table->integer('progress')->default(0);  // Progress column with default value of 0
            $table->integer('completed_status')->default(1);  // Completed status with default value of 1 (Not started)
            $table->integer('status')->default(1);  // Status column with default value of 1
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_l_m_s');
    }
}

