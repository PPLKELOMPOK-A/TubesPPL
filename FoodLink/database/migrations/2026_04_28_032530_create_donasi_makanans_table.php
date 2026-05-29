<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_donasi_makanans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donasi_makanans', function (Blueprint $table) {
            $table->id();
            // Data Donatur
            $table->string('nama_donatur');
            $table->string('no_telp');
            $table->string('email');
            
            // Detail Donasi
            $table->string('kategori_penerima');
            $table->string('kategori_wilayah');
            $table->string('lokasi_dropbox');
            $table->string('kategori_makanan');
            $table->string('waktu_layak');
            $table->text('deskripsi')->nullable();
            
            // Path Foto Makanan
            $table->string('foto_makanan');
            
            // Status Donasi (Pending, Diterima, Ditolak - berguna untuk Admin nanti)
            $table->string('status')->default('pending'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donasi_makanans');
    }
};