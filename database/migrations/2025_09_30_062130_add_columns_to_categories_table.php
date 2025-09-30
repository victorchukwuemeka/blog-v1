<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->datetime('modified_at')->nullable()->after('content');
        });
    }

    public function down() : void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('modified_at');
        });
    }
};
