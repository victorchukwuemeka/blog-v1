<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->index();
            $table->string('url');
            $table->string('source');
            $table->string('language');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->json('technologies');
            $table->json('locations');
            $table->string('setting');
            $table->unsignedBigInteger('min_salary')->nullable();
            $table->unsignedBigInteger('max_salary')->nullable();
            $table->string('currency')->nullable();
            $table->boolean('equity')->default(false);
            $table->json('how_to_apply');
            $table->json('perks');
            $table->json('interview_process');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
