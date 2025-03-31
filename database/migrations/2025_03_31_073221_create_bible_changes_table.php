<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('bible_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bible_id');
            $table->unsignedBigInteger('statement_id');
            $table->bigInteger('sync_time');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('bible_changes');
    }
};
