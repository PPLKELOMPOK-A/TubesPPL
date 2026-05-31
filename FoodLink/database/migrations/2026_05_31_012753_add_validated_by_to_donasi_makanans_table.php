<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('donasi_makanans', function (Blueprint $table) {
            // Menambahkan kolom validated_by setelah kolom status (atau user_id jika sudah ada)
            $table->unsignedBigInteger('validated_by')->nullable()->after('status');

            // Menjadikannya Foreign Key yang merujuk ke id di tabel users (Opsional tapi disarankan)
            $table->foreign('validated_by')
                  ->references('id')->on('users')
                  ->onDelete('set null'); 
                  // 'set null' berarti jika user admin/validator dihapus, 
                  // data donasi tetap ada, hanya saja divalidasi oleh "null"
        });
    }

    public function down()
    {
        Schema::table('donasi_makanans', function (Blueprint $table) {
            // Menghapus foreign key dan kolom jika di-rollback
            $table->dropForeign(['validated_by']); 
            $table->dropColumn('validated_by');
        });
    }
};
