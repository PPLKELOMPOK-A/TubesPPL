<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('drop_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('lokasi');
            $table->string('mitra');
            $table->string('kapasitas')->default('0/20');
            $table->string('status')->default('tersedia');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            // Kita ubah nama kolom 'update' menjadi 'keterangan_status' karena 'update' tidak boleh dipakai di database
            $table->string('keterangan_status')->default('Baru saja ditambahkan'); 
            $table->json('history')->nullable(); // Disimpan sebagai JSON Array
            $table->json('active_task')->nullable(); // Disimpan sebagai JSON Array untuk animasi peta
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drop_boxes');
    }
};