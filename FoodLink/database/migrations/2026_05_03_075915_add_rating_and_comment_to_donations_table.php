<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (! Schema::hasColumn('donations', 'rating')) {
                $table->integer('rating')->nullable()->after('status');
            }

            if (! Schema::hasColumn('donations', 'komentar')) {
                $table->text('komentar')->nullable()->after('rating');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['rating', 'komentar']);
        });
    }
};