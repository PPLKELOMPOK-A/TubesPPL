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
        // MODIFIKASI: Hanya tambah user_id jika kolom belum ada di tabel kegiatan_donasis
        if (!Schema::hasColumn('kegiatan_donasis', 'user_id')) {
            Schema::table('kegiatan_donasis', function (Blueprint $table) {
                $table->bigInteger('user_id')->unsigned()->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('kegiatan_donasis', 'user_id')) {
            Schema::table('kegiatan_donasis', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};