<!-- Kode ini diletakkan di resources/views/welcome.blade.php -->
@extends('layout')

@section('content')
<div class="row mt-4 mb-5">
    
    <!-- Judul Halaman -->
    <div class="col-md-12 mb-4">
        <h4 class="text-white fw-bold border-bottom border-secondary pb-2">Latest Update</h4>
    </div>

    <!-- Grid Katalog Komik -->
    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
        @forelse($mangas as $manga)
            @php
                // Logika Cerdas: Menentukan bendera berdasarkan Genre
                $flag = '🇯🇵'; // Default: Jepang (Manga)
                
                if($manga->genres) {
                    // Mengambil semua nama genre milik komik ini dan mengubahnya ke huruf kecil
                    $genreNames = $manga->genres->pluck('name')->map(fn($name) => strtolower($name))->toArray();
                    
                    if (in_array('manhwa', $genreNames)) {
                        $flag = '🇰🇷'; // Jika ada genre Manhwa, set Korea Selatan
                    } elseif (in_array('manhua', $genreNames)) {
                        $flag = '🇨🇳'; // Jika ada genre Manhua, set China
                    }
                }
            @endphp

            <div class="col">
                <div class="card bg-dark border-secondary h-100 manga-card position-relative overflow-hidden shadow-sm" style="border-radius: 10px;">
                    
                    <!-- Ikon Bendera Negara di Pojok Kanan Atas -->
                    <div class="position-absolute top-0 end-0 p-1 m-2 rounded text-center lh-1 shadow" style="background-color: rgba(0,0,0,0.7); z-index: 10; font-size: 1.2rem;">
                        {{ $flag }}
                    </div>

                    <!-- Gambar Cover -->
                    <img src="{{ asset('storage/' . $manga->cover_image) }}" class="card-img-top" alt="Cover" style="height: 240px; object-fit: cover;">

                    <div class="card-body p-2 d-flex flex-column" style="background-color: #1e1e1e;">
                        <!-- Judul Komik (Maksimal 2 Baris menggunakan CSS Line Clamp) -->
                        <h6 class="text-white fw-bold mb-auto mt-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.9rem; min-height: 2.7em;">
                            {{ $manga->title }}
                        </h6>
                        
                        <!-- Nama Author sengaja DIHAPUS sesuai permintaan -->
                        
                        <a href="{{ route('manga.show', $manga->slug) }}" class="btn btn-outline-primary btn-sm w-100 mt-3 rounded-pill fw-bold">Baca</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5 w-100">
                <h5>Belum ada komik terbaru.</h5>
            </div>
        @endforelse
    </div>

    <!-- Tombol Navigasi Pagination (Panah) -->
    <div class="col-md-12 d-flex justify-content-center mt-5 pagination-dark">
        {{ $mangas->links() }}
    </div>

</div>

<!-- CSS Kustom untuk membuat Pagination menyesuaikan dengan Dark Mode -->
<style>
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