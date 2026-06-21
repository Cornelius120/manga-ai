<!-- Kode ini diletakkan di resources/views/manga/search.blade.php -->
@extends('layout')

@section('content')
<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-8 text-center">
        <h2 class="fw-bold mb-4">Pencarian Komik Berbasis AI</h2>
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('search') }}" method="GET" class="mb-5">
            <div class="input-group input-group-lg shadow-sm">
                <input type="text" name="q" class="form-control border-primary" placeholder="Cari alur cerita... (contoh: anak laki-laki topi jerami)" value="{{ $query ?? '' }}" required>
                <button class="btn btn-primary px-4" type="submit">Cari</button>
            </div>
        </form>

        <!-- Area Hasil Pencarian -->
        @if(isset($query))
            <h5 class="text-start mb-4 border-bottom pb-2">Hasil rekomendasi AI untuk: <span class="fst-italic">"{{ $query }}"</span></h5>
            
            <div class="list-group text-start">
                @forelse($results as $manga)
                    <a href="{{ route('manga.show', $manga->slug) }}" class="list-group-item list-group-item-action p-4 mb-3 shadow-sm rounded border-start border-primary border-4">
                        <div class="d-flex w-100 justify-content-between">
                            <h4 class="fw-bold text-primary mb-2">{{ $manga->title }}</h4>
                            <span class="badge bg-{{ $manga->status == 'ongoing' ? 'success' : 'dark' }} align-self-start">{{ ucfirst($manga->status) }}</span>
                        </div>
                        <p class="mb-1 text-muted">{{ Str::limit($manga->synopsis, 200) }}</p>
                    </a>
                @empty
                    <div class="alert alert-warning">AI tidak dapat menemukan komik yang cocok dengan deskripsi tersebut. Coba deskripsi lain!</div>
                @endforelse
            </div>
        @endif

    </div>
</div>
@endsection