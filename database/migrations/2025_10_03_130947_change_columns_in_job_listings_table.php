<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->json('technologies')->nullable()->change();
            $table->json('locations')->nullable()->change();
            $table->json('how_to_apply')->nullable()->change();
            $table->json('perks')->nullable()->change();
            $table->json('interview_process')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->json('technologies')->nullable(false)->change();
            $table->json('locations')->nullable(false)->change();
            $table->json('how_to_apply')->nullable(false)->change();
            $table->json('perks')->nullable(false)->change();
            $table->json('interview_process')->nullable(false)->change();
        });
    }
};
