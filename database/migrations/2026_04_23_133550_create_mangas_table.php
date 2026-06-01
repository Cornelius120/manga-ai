<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('mangas', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Judul Komik
        $table->string('slug')->unique(); // Untuk URL (contoh: naruto-shippuden)
        $table->text('synopsis'); // Kolom krusial yang akan dianalisis AI
        $table->string('genre'); // Kategori genre
        $table->string('cover_image')->nullable(); // Link gambar sampul
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mangas');
    }
};
