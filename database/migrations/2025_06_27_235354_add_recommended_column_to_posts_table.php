<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->json('recommendations')->nullable()->after('modified_at');
        });
    }

    public function down() : void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('recommendations');
        });
    }
};
