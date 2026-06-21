<?php

// Kode ini diletakkan di app/Http/Controllers/AdminChapterController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;

class AdminChapterController extends Controller
{
    // Proteksi Admin
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Akses Ditolak.');
            }
            return $next($request);
        });
    }

    // Menampilkan form tambah chapter untuk komik tertentu
    public function create($manga_id)
    {
        $manga = Manga::findOrFail($manga_id);
        return view('admin.chapter.create', compact('manga'));
    }

    // Menyimpan chapter dan banyak halaman gambar sekaligus
    public function store(Request $request, $manga_id)
    {
        $request->validate([
            'chapter_number' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            // Validasi file 'pages' harus berupa array gambar
            'pages' => 'required|array',
            'pages.*' => 'image|mimes:jpeg,png,jpg,webp|max:3072' // Maks 3MB per gambar
        ]);

        $manga = Manga::findOrFail($manga_id);

        // 1. Simpan data Chapter
        $chapter = Chapter::create([
            'manga_id' => $manga->id,
            'chapter_number' => $request->chapter_number,
            'title' => $request->title,
        ]);

        // 2. Proses Multi-Upload Gambar
        if ($request->hasFile('pages')) {
            $pageNumber = 1;
            foreach ($request->file('pages') as $file) {
                // Simpan gambar ke storage/app/public/pages
                $imagePath = $file->store('pages', 'public');

                // Simpan data halaman ke database
                Page::create([
                    'chapter_id' => $chapter->id,
                    'page_number' => $pageNumber,
                    'image_path' => asset('storage/' . $imagePath), // Langsung buat jadi URL utuh
                ]);

                $pageNumber++;
            }
        }

        return redirect()->route('admin.manga.index')->with('success', 'Chapter ' . $chapter->chapter_number . ' beserta ' . ($pageNumber - 1) . ' halaman berhasil diunggah!');
    }
}