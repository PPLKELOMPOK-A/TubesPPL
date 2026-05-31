<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            // DATA DONASI (yang sudah ada)
            $table->string('judul');
            $table->string('kategori');
            $table->date('tanggal');
            $table->string('foto')->nullable();
            $table->text('deskripsi');
            $table->string('alamat');

            // ===============================
            // TAMBAHAN UNTUK RELASI USER
            // ===============================
            // Baris ini yang sebelumnya kelupaan:
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            // ===============================
            // TAMBAHAN UNTUK VALIDASI PROSES DONASI
            // ===============================
            $table->string('nama_makanan')->nullable();
            $table->string('donatur')->nullable();
            $table->integer('porsi')->nullable();

            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])
                  ->default('menunggu');

            $table->foreignId('validated_by')->nullable(); // siapa yang validasi

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('donations');
    }
};