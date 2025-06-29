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
            $table->string('image_path')->nullable();
            $table->string('image_disk')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->dateTime('modified_at')->nullable();
            $table->json('recommendations')->nullable();
            $table->unsignedBigInteger('sessions_count')->default(0);
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('posts');
    }
};
