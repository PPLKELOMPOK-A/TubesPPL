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
        Schema::create('penugasans', function (Blueprint $table) {
            $table->id();

            $table->string('id_penugasan')->unique(); // contoh: P001
            $table->string('id_donasi')->nullable();
            $table->string('nama_donatur');
            $table->string('relawan');
            $table->string('lokasi_pengambilan');
            $table->string('lokasi_pengantaran');
            $table->date('tanggal_penugasan');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasans');
    }
};