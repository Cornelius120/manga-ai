@extends('layout')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold">Katalog Komik Terbaru</h2>
        <p class="text-muted">Temukan komik kesukaanmu atau gunakan fitur Semantic Search AI.</p>
    </div>
</div>

<div class="row">
    @forelse($mangas as $manga)
    <div class="col-md-3 mb-4">
        <div class="card h-100 shadow-sm manga-card">
            <img src="https://via.placeholder.com/300x400?text=Cover+Komik" class="card-img-top" alt="Cover Manga">
            <div class="card-body">
                <h5 class="card-title fw-bold">{{ $manga->title }}</h5>
                <p class="card-text text-muted small">
                    Author: {{ $manga->author }} <br>
                    Status: <span class="badge bg-{{ $manga->status == 'ongoing' ? 'success' : 'primary' }}">
                        {{ ucfirst($manga->status) }}
                    </span>
                </p>
                <div class="mb-2">
                    @foreach($manga->genres as $genre)
                        <span class="badge bg-secondary">{{ $genre->name }}</span>
                    @endforeach
                </div>
                <p class="card-text small text-truncate">{{ $manga->synopsis }}</p>
                <a href="#" class="btn btn-outline-dark btn-sm w-100">Baca Sekarang</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-md-12 text-center">
        <p>Belum ada komik yang tersedia.</p>
    </div>
    @endforelse
</div>
@endsection