<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDescriptionInCourseContentLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_content_links', function (Blueprint $table) {
            $table->text('description')
                //->charset('utf8mb4')
                //->collation('utf8mb4_unicode_ci')
                ->nullable()
                ->default(null)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_content_links', function (Blueprint $table) {
            // Revert back to the previous column definition if needed
            $table->string('description')->change(); // Adjust based on the original type
        });
    }
}
