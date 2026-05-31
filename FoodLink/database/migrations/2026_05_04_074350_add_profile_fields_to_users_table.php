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
            // Menambahkan kolom-kolom profil baru setelah kolom 'email'
            // nullable() digunakan agar tidak error jika user lama belum mengisi data ini
            $table->string('nik')->nullable()->after('email');
            $table->string('telepon')->nullable()->after('nik');
            $table->string('lokasi')->nullable()->after('telepon');
            $table->text('alamat')->nullable()->after('lokasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn(['nik', 'telepon', 'lokasi', 'alamat']);
        });
    }
};