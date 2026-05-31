<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Memeriksa apakah kolom user_id belum ada di tabel donations
        if (!Schema::hasColumn('donations', 'user_id')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('donations', 'user_id')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};