<?php

// Kode ini diletakkan di app/Http/Controllers/MangaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Chapter; // Pastikan Model Chapter di-import

class MangaController extends Controller
{
    // Method Index yang sudah kita buat sebelumnya
    public function index()
    {
        $mangas = Manga::with('genres')->latest()->get();
        return view('manga.index', compact('mangas'));
    }

    // Menampilkan Halaman Detail Komik
    public function show($id)
    {
        // Mencari komik berdasarkan ID beserta relasi genre dan chapternya
        $manga = Manga::with(['genres', 'chapters' => function($query) {
            // Mengurutkan chapter dari yang terbaru
            $query->orderBy('created_at', 'desc'); 
        }])->findOrFail($id);

        return view('manga.show', compact('manga'));
    }

    // Menampilkan Halaman Baca Chapter
    public function read($id)
    {
        // Mencari chapter berdasarkan ID beserta relasi halaman dan komiknya
        $chapter = Chapter::with(['pages', 'manga'])->findOrFail($id);
        
        return view('manga.read', compact('chapter'));
    }
}