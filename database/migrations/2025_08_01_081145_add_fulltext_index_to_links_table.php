<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('links', function (Blueprint $table) {
            // In order to keep using SQLite in CI, I need to skip this migration.
            if (! app()->runningUnitTests()) {
                $table->fullText('url');
                $table->fullText('title');
                $table->fullText('description');
            }
        });
    }

    public function down() : void
    {
        Schema::table('links', function (Blueprint $table) {
            // In order to keep using SQLite in CI, I need to skip this migration.
            if (! app()->runningUnitTests()) {
                $table->dropFullText([
                    'url',
                    'title',
                    'description',
                ]);
            }
        });
    }
};
