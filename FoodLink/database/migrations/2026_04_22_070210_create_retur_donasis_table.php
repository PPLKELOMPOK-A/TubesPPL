<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('retur_donasis', function (Blueprint $table) {
            $table->id();
            $table->string('id_donasi');
            $table->string('nama_makanan');
            $table->integer('jumlah');
            $table->string('kategori');
            $table->string('alasan');
            $table->date('tanggal_pengajuan');
            $table->text('deskripsi')->nullable();
            $table->string('bukti')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('retur_donasis');
    }
};