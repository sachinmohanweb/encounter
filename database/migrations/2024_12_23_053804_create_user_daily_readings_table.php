<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDailyReadingsTable extends Migration
{
    public function up()
    {
        Schema::create('user_daily_readings', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->integer('user_lms_id');  // Foreign key for LMS user
            $table->integer('day');  // Integer column for day
            $table->date('date_of_reading');  // Date column for the reading date
            $table->integer('status')->default(1);  // Status column with a default value of 1
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_daily_readings');
    }
}
