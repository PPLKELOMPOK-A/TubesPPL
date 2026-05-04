public function up(): void
{
    Schema::table('donations', function (Blueprint $table) {
        $table->integer('rating')->nullable()->after('status'); // Menambah kolom rating setelah kolom status
        $table->text('komentar')->nullable()->after('rating');  // Menambah kolom komentar setelah rating
    });
}

public function down(): void
{
    Schema::table('donations', function (Blueprint $table) {
        $table->dropColumn(['rating', 'komentar']);
    });
}