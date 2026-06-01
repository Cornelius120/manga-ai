<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MangaController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $mangas = collect();

        if ($query) {
            // 1. Ambil semua data komik dari database
            $allMangas = Manga::all();
            $synopsisList = $allMangas->pluck('synopsis')->toArray();

            // 2. Kirim data ke API AI (Python)
            // Pastikan Python app.py kamu sedang berjalan di port 5000!
            try {
                $response = Http::timeout(10)->post('http://127.0.0.1:5000/api/search', [
                    'query' => $query,
                    'synopsis_list' => $synopsisList
                ]);

                if ($response->successful()) {
                    $aiResults = $response->json()['data'];

                    // 3. Urutkan data berdasarkan skor dari AI
                    foreach ($aiResults as $result) {
                        $manga = $allMangas[$result['index_komik']];
                        $manga->score = $result['skor_kemiripan'];
                        $mangas->push($manga);
                    }
                }
            } catch (\Exception $e) {
                // Jika AI mati, kembalikan pencarian biasa atau pesan error
                return back()->with('error', 'Koneksi ke Mesin AI terputus!');
            }
        } else {
            // Jika tidak ada pencarian, tampilkan semua komik biasa
            $mangas = Manga::all();
        }

        return view('manga.index', compact('mangas', 'query'));
    }
}