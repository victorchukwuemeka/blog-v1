<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id');
            $table->json('data');
            $table->timestamps();
        });
    }
};
