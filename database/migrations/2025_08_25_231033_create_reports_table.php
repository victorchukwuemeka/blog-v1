<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id');
            $table->longText('content');
            $table->timestamps();
        });
    }
};
