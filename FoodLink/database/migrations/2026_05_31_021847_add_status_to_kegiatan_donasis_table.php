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
    Schema::table('kegiatan_donasis', function (Blueprint $table) {
        // Menambahkan kolom status dengan nilai default 'pending' atau 'menunggu'
        $table->string('status')->default('pending')->after('alamat_penyaluran');
    });
}

    public function down()
    {
        Schema::table('kegiatan_donasis', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
