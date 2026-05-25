<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('donations', function (Blueprint $table) {
        $table->decimal('latitude', 10, 8)->nullable(); // 10 digit dengan 8 angka setelah koma
        $table->decimal('longitude', 11, 8)->nullable(); // 11 digit dengan 8 angka setelah koma
    });
}

public function down()
{
    Schema::table('kegiatan_donasis', function (Blueprint $table) {
        $table->dropColumn(['latitude', 'longitude']);
    });
}
};
