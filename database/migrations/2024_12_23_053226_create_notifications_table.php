<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->string('title', 256);  // Title of the notification
            $table->text('description')->nullable();  // Description of the notification (nullable)
            $table->string('redirection', 256)->nullable();  // URL redirection (nullable)
            $table->integer('type');  // Type of the notification
            $table->longText('data')->nullable();  // Data related to the notification (nullable)
            $table->integer('status')->default(1);  // Status column with a default value of 1
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}


