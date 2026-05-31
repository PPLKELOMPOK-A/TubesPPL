<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up(): void
{
    Schema::table('donations', function (Blueprint $table) {

        if (!Schema::hasColumn('donations', 'latitude')) {
            $table->decimal('latitude', 10, 8)->nullable();
        }

        if (!Schema::hasColumn('donations', 'longitude')) {
            $table->decimal('longitude', 11, 8)->nullable();
        }

    });
}

    public function down(): void
    {
        if (Schema::hasColumn('donations', 'quantity')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->dropColumn('quantity');
            });
        }
    }
};
