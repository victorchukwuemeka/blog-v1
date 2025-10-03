<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->text('extra_attributes')->nullable();
        });
    }

    public function down() : void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('extra_attributes');
        });
    }
};
