<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// This removes the crap I've done in the previous
// migrations and builds new indexes properly.
return new class extends Migration
{
    public function up() : void
    {
        Schema::table('posts', function (Blueprint $table) {
            // In order to keep using SQLite in CI, I need to skip this migration.
            if (! app()->runningUnitTests()) {
                $table->fullText(
                    ['title', 'slug', 'content', 'description'],
                    'posts_fulltext_all'
                );
            }
        });

        Schema::table('links', function (Blueprint $table) {
            // In order to keep using SQLite in CI, I need to skip this migration.
            if (! app()->runningUnitTests()) {
                $table->fullText(
                    ['url', 'title', 'description'],
                    'links_fulltext_all'
                );
            }
        });
    }

    public function down() : void
    {
        Schema::table('posts', function (Blueprint $table) {
            // In order to keep using SQLite in CI, I need to skip this migration.
            if (! app()->runningUnitTests()) {
                $table->dropFullText('posts_fulltext_all');
            }
        });

        Schema::table('links', function (Blueprint $table) {
            // In order to keep using SQLite in CI, I need to skip this migration.
            if (! app()->runningUnitTests()) {
                $table->dropFullText('links_fulltext_all');
            }
        });
    }
};
