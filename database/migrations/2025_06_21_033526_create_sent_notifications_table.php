<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('sent_notifications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('type_id');
            $table->string('type');
            $table->date('date_sent');
            $table->timestamps();

            $table->unique(['user_id', 'batch_id', 'course_id', 'type_id', 'date_sent'], 'unique_notification_entry');

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            // $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sent_notifications');
    }
}
