<?php

// Kode ini diletakkan di app/Http/Controllers/SearchController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Manga;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $results = [];

        if ($query) {
            // 1. Ambil semua data komik dari database
            $allMangas = Manga::all();
            
            // Jika belum ada komik di database, hentikan proses
            if ($allMangas->isEmpty()) {
                return view('manga.search', compact('results', 'query'))
                    ->with('error', 'Belum ada data komik di database untuk dicari.');
            }

            $synopsisList = [];
            $mangaIdMap = [];

            // 2. Susun daftar sinopsis untuk dikirim ke Python AI
            foreach ($allMangas as $index => $manga) {
                $synopsisList[] = $manga->synopsis;
                $mangaIdMap[$index] = $manga->id; // Ingat urutan index ini milik ID komik yang mana
            }

            try {
                // 3. Tembak API Python dengan alamat dan struktur data yang tepat
                $response = Http::post('http://127.0.0.1:5000/api/search', [
                    'query' => $query,
                    'synopsis_list' => $synopsisList
                ]);

                if ($response->successful()) {
                    $responseData = $response->json();
                    
                    // 4. Jika Python berhasil merespons dengan status success
                    if (isset($responseData['status']) && $responseData['status'] === 'success') {
                        $mangaIds = [];
                        
                        // Membaca balasan AI (mengurutkan komik berdasarkan kemiripan)
                        foreach ($responseData['data'] as $aiResult) {
                            // Opsional: Kita filter agar hanya menampilkan komik dengan skor kemiripan di atas 0.1
                            // agar hasil pencarian tidak terlalu ngawur
                            if ($aiResult['skor_kemiripan'] > 0.1) {
                                // Menerjemahkan index dari Python kembali menjadi ID komik MySQL
                                $mangaIds[] = $mangaIdMap[$aiResult['index_komik']];
                            }
                        }
                        
                        // 5. Tarik data komik final dari MySQL sesuai urutan rekomendasi AI
                        if (!empty($mangaIds)) {
                            $idString = implode(',', $mangaIds);
                            $results = Manga::whereIn('id', $mangaIds)
                                ->orderByRaw("FIELD(id, $idString)") 
                                ->get();
                        }
                    }
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal terhubung ke layanan AI. Pastikan file app.py sedang berjalan.');
            }
        }

        return view('manga.search', compact('results', 'query'));
    }
}