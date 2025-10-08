<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('content');
            $table->dropColumn('modified_at');
        });
    }

    public function down() : void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->datetime('modified_at')->nullable();
        });
    }
};
