<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseOrderToCoursesTable extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedInteger('course_order')->nullable()->after('no_of_days');
            $table->index('course_order'); // Optional: Index for better query performance
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['course_order']); // Drop the index if it exists
            $table->dropColumn('course_order'); // Drop the course_order column
        });
    }
}