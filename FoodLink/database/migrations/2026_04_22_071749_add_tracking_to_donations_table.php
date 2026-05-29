<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('donations', 'status')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->enum('status', ['menunggu', 'dalam_perjalanan', 'terkirim'])
                      ->default('menunggu')
                      ->after('alamat');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('donations', 'status')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};