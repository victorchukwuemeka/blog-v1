<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->string('post_slug')->index();
            $table->foreignId('parent_id')->nullable()->index();
            $table->text('content');
            $table->datetime('modified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('comments');
    }
};
