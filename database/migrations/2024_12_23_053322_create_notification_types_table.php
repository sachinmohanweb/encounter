<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTypesTable extends Migration
{
    public function up()
    {
        Schema::create('notification_types', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->string('type_name', 256);  // Type name for the notification
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_types');
    }
}

