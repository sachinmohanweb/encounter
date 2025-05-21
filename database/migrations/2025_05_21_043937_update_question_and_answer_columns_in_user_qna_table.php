<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuestionAndAnswerColumnsInUserQnaTable extends Migration
{
    public function up()
    {
        Schema::table('user_qna', function (Blueprint $table) {
            $table->longText('question')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false)->change();
            $table->longText('answer')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('user_qna', function (Blueprint $table) {
            $table->longText('question')->nullable()->change();
            $table->longText('answer')->nullable()->change();
        });
    }
};

