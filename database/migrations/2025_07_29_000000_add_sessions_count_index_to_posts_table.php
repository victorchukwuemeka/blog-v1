<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->index('sessions_count');
        });
    }

    public function down() : void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['sessions_count']);
        });
    }
};
