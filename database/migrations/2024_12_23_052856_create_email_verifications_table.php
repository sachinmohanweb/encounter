<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailVerificationsTable extends Migration
{
    public function up()
    {
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->string('email', 256);  // Email column with varchar length of 256
            $table->string('otp', 4);  // OTP column with varchar length of 4
            $table->timestamp('otp_expiry');  // OTP expiry timestamp
            $table->integer('otp_used')->default(0);  // OTP used flag, default to 0
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_verifications');
    }
}

