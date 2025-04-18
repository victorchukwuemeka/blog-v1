<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->string('image_url')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->dateTime('modified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('posts');
    }
};
