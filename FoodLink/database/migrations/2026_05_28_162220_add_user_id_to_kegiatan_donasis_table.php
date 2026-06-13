<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahin if ini aja bro biar dia ngecek dulu
        if (!Schema::hasColumn('kegiatan_donasis', 'user_id')) {
            Schema::table('kegiatan_donasis', function (Blueprint $table) {
                $table->foreignId('user_id')
                      ->nullable()
                      ->constrained()
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kegiatan_donasis', 'user_id')) {
            Schema::table('kegiatan_donasis', function (Blueprint $table) {
                $table->dropForeign(['user_id']); 
                $table->dropColumn('user_id');
            });
        }
    }
};