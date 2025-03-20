<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->longText('value');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('metrics');
    }
};
