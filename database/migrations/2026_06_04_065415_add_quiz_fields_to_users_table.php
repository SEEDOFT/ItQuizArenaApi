<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('name');
            $table->integer('xp')->default(0)->after('password');
            $table->integer('level')->default(1)->after('xp');
            $table->integer('total_quizzes')->default(0)->after('level');
            $table->integer('highest_score')->default(0)->after('total_quizzes');
            $table->integer('best_streak')->default(0)->after('highest_score');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'xp', 'level', 'total_quizzes', 'highest_score', 'best_streak']);
        });
    }
};
