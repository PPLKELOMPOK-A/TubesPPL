<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_kegiatan_donasis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kegiatan_donasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul_donasi');
            $table->string('kategori_penerima');
            $table->date('tanggal_kegiatan');
            $table->text('deskripsi');
            $table->string('alamat_penyaluran');
            $table->string('foto_kegiatan'); // Untuk menyimpan path foto
            
            // Opsional: Jika kamu butuh mencatat siapa Admin yang membuat (berelasi ke tabel users)
            // $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kegiatan_donasis');
    }
};