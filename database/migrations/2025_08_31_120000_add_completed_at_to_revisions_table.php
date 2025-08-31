<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('revisions', function (Blueprint $table) {
            if (! Schema::hasColumn('revisions', 'completed_at')) {
                $table->dateTime('completed_at')->nullable()->after('data');
            }
        });
    }
};
