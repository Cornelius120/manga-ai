<?php

// Kode ini diletakkan di app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Proteksi: Hanya Admin yang boleh mengakses controller ini
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Akses Ditolak: Anda bukan Admin.');
            }
            return $next($request);
        });
    }

    // Menampilkan daftar komik di panel admin
    public function index()
    {
        $mangas = Manga::latest()->get();
        return view('admin.manga.index', compact('mangas'));
    }

    // Menampilkan formulir tambah komik
    public function create()
    {
        $genres = Genre::all(); // Mengambil pilihan genre dari database
        return view('admin.manga.create', compact('genres'));
    }

    // Menyimpan data komik baru dan mengunggah gambar
    public function store(Request $request)
    {
        // Validasi input data
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'status' => 'required|in:ongoing,completed',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal gambar 2MB
            'genres' => 'required|array'
        ]);

        // Proses unggah (upload) gambar ke folder storage/app/public/covers
        $imagePath = $request->file('cover_image')->store('covers', 'public');

        // Menyimpan data komik ke database
        $manga = Manga::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'author' => $request->author,
            'synopsis' => $request->synopsis,
            'status' => $request->status,
            'cover_image' => $imagePath,
        ]);

        // Menyimpan relasi genre ke komik tersebut
        $manga->genres()->attach($request->genres);

        return redirect()->route('admin.manga.index')->with('success', 'Komik baru berhasil ditambahkan!');
    }

    // Menghapus komik beserta gambarnya
    public function destroy($id)
    {
        $manga = Manga::findOrFail($id);
        
        // Hapus file gambar cover dari penyimpanan server agar tidak menumpuk
        if ($manga->cover_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($manga->cover_image);
        }
        
        // Hapus data dari database
        $manga->delete();
        
        return redirect()->route('admin.manga.index')->with('success', 'Komik berhasil dihapus secara permanen!');
    }
}