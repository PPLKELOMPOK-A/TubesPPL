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
        Schema::table('donasi_makanans', function (Blueprint $table) {
            // Menambahkan kolom user_id setelah kolom status
            $table->foreignId('user_id')->nullable()->after('status')->constrained('users')->onDelete('cascade');

            // Catatan Tambahan: Di model Anda ada 'validated_by', tapi di db belum ada. 
            // Jika mau sekalian ditambahkan:
            $table->foreignId('validated_by')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('donasi_makanans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
}
};
