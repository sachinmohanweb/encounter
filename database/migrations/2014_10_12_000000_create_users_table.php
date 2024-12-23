<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 256);
            $table->string('last_name', 256)->nullable();
            $table->string('gender', 100)->nullable();
            $table->integer('age')->nullable();
            $table->string('location', 256)->nullable();
            $table->string('image', 256)->nullable();
            $table->string('device_type', 256)->nullable();
            $table->string('ip', 256)->nullable();
            $table->string('device_id', 256)->nullable();
            $table->string('refresh_token', 256)->nullable();
            $table->string('app_usage', 256)->nullable();
            $table->string('browser', 256)->nullable();
            $table->timestamp('last_accessed')->useCurrent();
            $table->string('email', 256)->unique();
            $table->string('password', 512)->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('phone', 25)->nullable();
            $table->integer('status')->default(1);
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}