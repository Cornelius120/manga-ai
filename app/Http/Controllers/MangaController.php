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
    public function show($slug)
    {
        // Mencari komik berdasarkan slug (bukan ID)
        $manga = Manga::with(['genres', 'chapters' => function($query) {
            $query->orderBy('created_at', 'desc'); 
        }])->where('slug', $slug)->firstOrFail();

        return view('manga.show', compact('manga'));
    }

    // Menampilkan Halaman Baca Chapter
    public function read($slug, $chapter_number)
    {
        $manga = Manga::where('slug', $slug)->firstOrFail();
        
        // Tambahkan 'comments.user' di dalam array with()
        $chapter = Chapter::with(['pages', 'manga', 'comments.user'])
            ->where('manga_id', $manga->id)
            ->where('chapter_number', $chapter_number)
            ->firstOrFail();
        
        return view('manga.read', compact('chapter'));
    }
}