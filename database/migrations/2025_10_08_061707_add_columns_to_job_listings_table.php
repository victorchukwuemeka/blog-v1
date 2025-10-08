<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->longText('html')->after('url');
        });
    }

    public function down() : void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropColumn('html');
        });
    }
};
