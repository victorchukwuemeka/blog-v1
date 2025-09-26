<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->index();
            $table->string('url');
            $table->string('source');
            $table->string('language');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->text('description');
            $table->json('technologies');
            $table->string('location')->nullable();
            $table->string('setting');
            $table->unsignedBigInteger('min_salary')->nullable();
            $table->unsignedBigInteger('max_salary')->nullable();
            $table->string('currency')->nullable();
            $table->json('how_to_apply');
            $table->timestamp('published_at');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('listings');
    }
};
