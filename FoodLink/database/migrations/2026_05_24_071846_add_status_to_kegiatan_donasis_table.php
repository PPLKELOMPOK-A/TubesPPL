<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah kolom 'status' BELUM ada sebelum menambahkannya
        if (!Schema::hasColumn('kegiatan_donasis', 'status')) {
            Schema::table('kegiatan_donasis', function (Blueprint $table) {
                $table->string('status')->default('dalam_perjalanan');
            });
        }
    }

    public function down(): void
    {
        // Cek apakah kolom 'status' SUDAH ada sebelum menghapusnya
        if (Schema::hasColumn('kegiatan_donasis', 'status')) {
            Schema::table('kegiatan_donasis', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};