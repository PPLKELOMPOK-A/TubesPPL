<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                if (!Schema::hasColumn('messages', 'edited_at')) {
                    $table->timestamp('edited_at')->nullable()->after('is_read');
                }

                if (!Schema::hasColumn('messages', 'is_deleted')) {
                    $table->boolean('is_deleted')->default(false)->after('edited_at');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                if (Schema::hasColumn('messages', 'edited_at')) {
                    $table->dropColumn('edited_at');
                }

                if (Schema::hasColumn('messages', 'is_deleted')) {
                    $table->dropColumn('is_deleted');
                }
            });
        }
    }
};