<!-- Kode ini diletakkan di resources/views/manga/search.blade.php -->
@extends('layout')

@section('content')
<div class="row mt-4 mb-5 justify-content-center">
    
    <!-- 1. BARIS PENCARIAN BESAR -->
    <div class="col-md-8 mb-5">
        <form action="{{ route('search') }}" method="GET" class="d-flex shadow-sm">
            <!-- Menampilkan kembali kata kunci yang diketik user sebelumnya menggunakan value="{{ request('keyword') }}" -->
            <input type="text" name="keyword" class="form-control form-control-lg bg-dark text-white border-secondary" placeholder="Ketik judul komik yang ingin kamu baca..." value="{{ request('keyword') }}" style="border-radius: 50px 0 0 50px;" required>
            <button type="submit" class="btn btn-primary px-4 px-md-5 fw-bold" style="border-radius: 0 50px 50px 0;">Cari</button>
        </form>
    </div>

    <!-- 2. JUDUL HASIL PENCARIAN -->
    <div class="col-md-12 mb-4">
        <h4 class="text-white fw-bold border-bottom border-secondary pb-2">
            @if(request('keyword'))
                Hasil Pencarian: <span class="text-primary">"{{ request('keyword') }}"</span>
            @else
                Mencari Semua Komik
            @endif
        </h4>
    </div>

    <!-- 3. GRID KATALOG KOMIK ALA MANGADEX -->
    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3 w-100 m-0">
        @forelse($mangas as $manga)
            @php
                // Logika Bendera ala MangaDex (Teks Singkatan Negara)
                $flag = 'JP'; // Default: Jepang (Manga)
                
                if($manga->genres) {
                    $genreNames = $manga->genres->pluck('name')->map(fn($name) => strtolower($name))->toArray();
                    
                    if (in_array('manhwa', $genreNames)) {
                        $flag = 'KR'; // Korea
                    } elseif (in_array('manhua', $genreNames)) {
                        $flag = 'CN'; // China
                    }
                }
            @endphp

            <div class="col px-2">
                <a href="{{ route('manga.show', $manga->slug) }}" class="text-decoration-none">
                    <div class="card bg-transparent border-0 h-100 manga-card position-relative overflow-hidden">
                        
                        <!-- Wadah Cover dengan Rasio Tetap (Aspect Ratio 2:3) -->
                        <div class="position-relative rounded shadow-sm" style="overflow: hidden; aspect-ratio: 2/3;">
                            
                            <!-- Ikon Negara -->
                            <div class="position-absolute top-0 end-0 p-1 m-1 rounded fw-bold text-white shadow-sm" style="background-color: rgba(0,0,0,0.6); z-index: 10; font-size: 0.75rem; letter-spacing: 1px; backdrop-filter: blur(2px);">
                                {{ $flag }}
                            </div>

                            <!-- Gambar Cover -->
                            <img src="{{ asset('storage/' . $manga->cover_image) }}" class="w-100 h-100 cover-img" alt="Cover" style="object-fit: cover; transition: transform 0.3s ease;">
                            
                            <!-- Efek Gelap saat di-hover -->
                            <div class="overlay position-absolute top-0 start-0 w-100 h-100 bg-dark" style="opacity: 0; transition: opacity 0.3s ease;"></div>
                        </div>

                        <!-- Detail Judul -->
                        <div class="pt-2">
                            <h6 class="text-white fw-bold mb-0 title-clamp">
                                {{ $manga->title }}
                            </h6>
                        </div>

                    </div>
                </a>
            </div>
        @empty
            <!-- Tampilan jika komik tidak ditemukan -->
            <div class="col-12 text-center text-muted py-5 w-100">
                <h1 class="display-1 mb-3">🔍</h1>
                <h5>Waduh, tidak ada komik yang cocok dengan kata kunci tersebut.</h5>
                <p>Coba gunakan kata kunci lain atau periksa ejaan judulnya.</p>
            </div>
        @endforelse
    </div>

    <!-- 4. TOMBOL NAVIGASI PAGINATION -->
    <div class="col-md-12 d-flex justify-content-center mt-5 pagination-dark">
        <!-- withQueryString() memastikan keyword tetap menempel saat pindah halaman -->
        {{ $mangas->withQueryString()->links() }}
    </div>

</div>

<!-- CSS Kustom untuk Efek MangaDex -->
<style>
    .manga-card:hover .cover-img { transform: scale(1.05); }
    .manga-card:hover .overlay { opacity: 0.4 !important; }
    .manga-card:hover .title-clamp { color: #0d6efd !important; }

    .title-clamp {
        display: -webkit-box; 
        -webkit-line-clamp: 2; 
        -webkit-box-orient: vertical; 
        overflow: hidden; 
        text-overflow: ellipsis;
        font-size: 0.9rem; 
        line-height: 1.4; 
        max-height: 2.8em; 
    }
    
    .pagination-dark .pagination {
        --bs-pagination-bg: #1e1e1e;
        --bs-pagination-border-color: #333;
        --bs-pagination-color: #e0e0e0;
        --bs-pagination-hover-bg: #2a2a2a;
        --bs-pagination-hover-color: #fff;
        --bs-pagination-hover-border-color: #0d6efd;
        --bs-pagination-active-bg: #0d6efd;
        --bs-pagination-active-border-color: #0d6efd;
    }
</style>
@endsection