<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('url')->unique();
            $table->text('image_url')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('is_approved')->nullable();
            $table->timestamp('is_declined')->nullable();
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('links');
    }
};
