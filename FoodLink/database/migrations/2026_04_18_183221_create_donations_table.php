<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori');
            $table->date('tanggal'); // Menyimpan tanggal asli (YYYY-MM-DD)
            $table->string('foto')->nullable(); // Menyimpan path/nama file foto
            $table->text('deskripsi');
            $table->string('alamat');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('donations');
    }
};