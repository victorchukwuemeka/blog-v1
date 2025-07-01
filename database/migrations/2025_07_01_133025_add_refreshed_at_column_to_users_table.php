<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->datetime('refreshed_at')->nullable();
        });

        User::query()->cursor()->each(function (User $user) {
            $user->update(['refreshed_at' => $user->created_at]);
        });
    }

    public function down() : void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('refreshed_at');
        });
    }
};
