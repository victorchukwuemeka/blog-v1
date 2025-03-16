<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('comment_id');
            $table->string('emoji');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('reactions');
    }
};
