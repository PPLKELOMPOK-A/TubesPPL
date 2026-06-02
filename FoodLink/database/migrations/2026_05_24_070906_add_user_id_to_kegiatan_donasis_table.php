<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah kolom 'user_id' BELUM ada sebelum menambahkannya
        if (!Schema::hasColumn('kegiatan_donasis', 'user_id')) {
            Schema::table('kegiatan_donasis', function (Blueprint $table) {
                $table->foreignId('user_id')
                      ->after('id')
                      ->constrained()
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        // Cek apakah kolom 'user_id' SUDAH ada sebelum menghapusnya
        if (Schema::hasColumn('kegiatan_donasis', 'user_id')) {
            Schema::table('kegiatan_donasis', function (Blueprint $table) {
                $table->dropForeign(['user_id']); // Hapus foreign key constraint terlebih dahulu
                $table->dropColumn('user_id');    // Baru kemudian hapus kolomnya
            });
        }
    }
};