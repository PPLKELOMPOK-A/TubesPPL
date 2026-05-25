<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('donations', function (Blueprint $table) {
        $table->foreignId('courier_id')
              ->nullable()
              ->constrained('couriers')
              ->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('kegiatan_donasis', function (Blueprint $table) {
        $table->dropForeign(['courier_id']);
        $table->dropColumn('courier_id');
    });
}
};