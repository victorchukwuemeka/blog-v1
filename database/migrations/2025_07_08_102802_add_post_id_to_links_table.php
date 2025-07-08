<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->foreignId('post_id')->nullable()->unique()->after('user_id');
        });
    }

    public function down() : void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn('post_id');
        });
    }
};
