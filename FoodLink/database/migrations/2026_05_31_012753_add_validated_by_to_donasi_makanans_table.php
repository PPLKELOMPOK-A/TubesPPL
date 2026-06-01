<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kegiatan_donasis')) {
            return;
        }

        if (!Schema::hasColumn('kegiatan_donasis', 'status')) {
            Schema::table('kegiatan_donasis', function (Blueprint $table) {
                $table->string('status')
                    ->default('pending')
                    ->after('alamat_penyaluran');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('kegiatan_donasis')) {
            return;
        }

        if (Schema::hasColumn('kegiatan_donasis', 'status')) {
            Schema::table('kegiatan_donasis', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};