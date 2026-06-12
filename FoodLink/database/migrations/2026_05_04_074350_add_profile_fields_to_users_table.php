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
            // Cek dan tambah kolom NIK
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik')->nullable()->after('email');
            }
            
            // Cek dan tambah kolom Telepon
            if (!Schema::hasColumn('users', 'telepon')) {
                $table->string('telepon')->nullable()->after('nik');
            }
            
            // Cek dan tambah kolom Lokasi
            if (!Schema::hasColumn('users', 'lokasi')) {
                $table->string('lokasi')->nullable()->after('telepon');
            }
            
            // Cek dan tambah kolom Alamat
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable()->after('lokasi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nik')) { $table->dropColumn('nik'); }
            if (Schema::hasColumn('users', 'telepon')) { $table->dropColumn('telepon'); }
            if (Schema::hasColumn('users', 'lokasi')) { $table->dropColumn('lokasi'); }
            if (Schema::hasColumn('users', 'alamat')) { $table->dropColumn('alamat'); }
        });
    }
};