<?php

// Kode ini diletakkan di app/Http/Controllers/SearchController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Manga;

class SearchController extends Controller
{
    // Mengembalikan nama method menjadi index sesuai rute aslimu
    public function index(Request $request)
    {
        // Menangkap input dari desain search.blade.php yang baru (menggunakan name="keyword")
        $keyword = $request->input('keyword');
        
        // Jika tidak ada pencarian, tampilkan semua komik terbaru beserta genrenya
        if (!$keyword) {
            $mangas = Manga::with('genres')->latest()->paginate(12);
            return view('manga.search', compact('mangas', 'keyword'));
        }

        // 1. Ambil semua data komik dari database
        $allMangas = Manga::all();
        
        // Jika belum ada komik di database, hentikan proses
        if ($allMangas->isEmpty()) {
            $mangas = Manga::where('id', 0)->paginate(12); // Paginator kosong
            return view('manga.search', compact('mangas', 'keyword'))
                ->with('error', 'Belum ada data komik di database untuk dicari.');
        }

        $synopsisList = [];
        $mangaIdMap = [];

        // 2. Susun daftar sinopsis untuk dikirim ke Python AI
        foreach ($allMangas as $index => $manga) {
            $synopsisList[] = $manga->synopsis;
            $mangaIdMap[$index] = $manga->id; 
        }

        try {
            // 3. Tembak API Python dengan alamat dan struktur data yang tepat
            $response = Http::post('http://127.0.0.1:5000/api/search', [
                'query' => $keyword, // Menggunakan keyword dari tampilan baru
                'synopsis_list' => $synopsisList
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // 4. Jika Python berhasil merespons dengan status success
                if (isset($responseData['status']) && $responseData['status'] === 'success') {
                    $mangaIds = [];
                    
                    // Membaca balasan AI
                    foreach ($responseData['data'] as $aiResult) {
                        if ($aiResult['skor_kemiripan'] > 0.1) {
                            $mangaIds[] = $mangaIdMap[$aiResult['index_komik']];
                        }
                    }
                    
                    // 5. Tarik data komik final dari MySQL sesuai urutan rekomendasi AI
                    if (!empty($mangaIds)) {
                        $idString = implode(',', $mangaIds);
                        
                        // Menarik data komik dengan relasi genre (untuk bendera) dan di-paginate!
                        $mangas = Manga::with('genres')
                            ->whereIn('id', $mangaIds)
                            ->orderByRaw("FIELD(id, $idString)") 
                            ->paginate(12);
                            
                        return view('manga.search', compact('mangas', 'keyword'));
                    }
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke layanan AI. Pastikan file app.py sedang berjalan.');
        }

        // Jika pencarian AI tidak menemukan hasil yang cocok
        $mangas = Manga::where('id', 0)->paginate(12); 
        return view('manga.search', compact('mangas', 'keyword'));
    }
}