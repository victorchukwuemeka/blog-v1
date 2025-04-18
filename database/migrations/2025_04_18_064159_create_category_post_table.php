<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('category_post', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->index();
            $table->foreignId('post_id')->index();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('category_post');
    }
};
