<?php

// Kode ini diletakkan di app/Http/Controllers/MangaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Genre;

class MangaController extends Controller
{
    public function index()
    {
        // Mengambil semua data komik beserta relasi genre-nya
        $mangas = Manga::with('genres')->latest()->get();
        
        // Menampilkan halaman index dan mengirimkan variabel $mangas
        return view('manga.index', compact('mangas'));
    }
}