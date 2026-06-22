<!-- Kode ini diletakkan di resources/views/manga/read.blade.php -->
@extends('layout')

@section('content')

<!-- 1. HEADER INFO CHAPTER -->
<div class="row mt-3 mb-4 justify-content-center">
    <div class="col-md-10 d-flex flex-column flex-md-row justify-content-between align-items-center border-bottom border-secondary pb-3">
        <div class="text-center text-md-start mb-3 mb-md-0">
            <h3 class="fw-bold text-white mb-1">{{ $chapter->manga->title }}</h3>
            <h6 class="text-secondary mb-0">
                Chapter {{ $chapter->chapter_number }} 
                @if($chapter->title) <span class="text-muted"> - {{ $chapter->title }}</span> @endif
            </h6>
        </div>
        <div>
            <a href="{{ route('manga.show', $chapter->manga->slug) }}" class="btn btn-outline-secondary btn-sm px-4 rounded-pill fw-bold shadow-sm">
                &larr; Detail Komik
            </a>
        </div>
    </div>
</div>

<!-- 2. AREA BACA KOMIK (SEAMLESS) -->
<div class="row justify-content-center mb-5">
    <div class="col-md-8 col-lg-7 p-0 shadow-lg manga-reader-container" style="background-color: #000000; border-radius: 8px; overflow: hidden;">
        @forelse($chapter->pages as $page)
            <!-- Class d-block dan m-0 memastikan tidak ada jarak putih antar gambar -->
            <img src="{{ $page->image_path }}" class="img-fluid w-100 d-block m-0 p-0 reader-img" alt="Halaman {{ $page->page_number }}" loading="lazy">
        @empty
            <div class="p-5 text-center text-muted" style="background-color: #1a1a1a;">
                <h5 class="mb-0">Belum ada gambar yang diunggah untuk chapter ini.</h5>
            </div>
        @endforelse
    </div>
</div>

<!-- 3. TOMBOL NAVIGASI NEXT / PREV -->
<div class="row justify-content-center mb-5">
    <div class="col-md-8 col-lg-7 d-flex justify-content-between align-items-center bg-dark p-3 rounded shadow-sm border border-secondary border-opacity-50">
        
        @if($prevChapter)
            <a href="{{ route('chapter.read', ['slug' => $chapter->manga->slug, 'chapter_number' => $prevChapter->chapter_number]) }}" class="btn btn-dark px-4 rounded-pill border-secondary fw-bold hover-primary">
                &larr; Prev
            </a>
        @else
            <button class="btn btn-dark px-4 rounded-pill border-secondary text-muted" disabled>&larr; Prev</button>
        @endif

        <span class="text-muted small fw-bold">CH {{ $chapter->chapter_number }}</span>

        @if($nextChapter)
            <a href="{{ route('chapter.read', ['slug' => $chapter->manga->slug, 'chapter_number' => $nextChapter->chapter_number]) }}" class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm">
                Next &rarr;
            </a>
        @else
            <button class="btn btn-secondary px-4 rounded-pill fw-bold" disabled>Next &rarr;</button>
        @endif

    </div>
</div>

<!-- 4. AREA KOMENTAR CHAPTER -->
<div class="row justify-content-center mb-5">
    <div class="col-md-10 col-lg-8">
        <h4 class="fw-bold border-bottom border-secondary pb-2 text-white">Komentar Chapter ({{ $chapter->comments->count() }})</h4>
        
        @if(session('success'))
            <div class="alert alert-success mt-3 bg-success text-white border-0">{{ session('success') }}</div>
        @endif

        @auth
        <form action="{{ route('comment.store', $chapter->id) }}" method="POST" class="mb-4 mt-4 p-3 rounded" style="background-color: #1e1e1e;">
            @csrf
            <div class="mb-3">
                <textarea name="comment_text" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Tulis komentarmu untuk chapter ini..." required></textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill">Kirim Komentar</button>
            </div>
        </form>
        @else
        <div class="alert mt-3 text-white border-secondary" style="background-color: #1e1e1e;">
            Silakan <a href="/login" class="fw-bold text-primary text-decoration-none">Login</a> terlebih dahulu untuk memberikan komentar.
        </div>
        @endauth

        <div class="list-group mt-4">
            @forelse($chapter->comments->sortByDesc('created_at') as $comment)
            <!-- ID Deep Linking -->
            <div class="list-group-item px-4 py-3 bg-transparent text-white border-secondary mb-3 rounded shadow-sm" style="background-color: #16181f !important;" id="comment-{{ $comment->id }}">
                <div class="d-flex w-100 justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=random' }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                        
                        <!-- Link Profil Publik -->
                        <a href="{{ route('profile.public', $comment->user->id) }}" class="mb-0 fw-bold text-decoration-none {{ $comment->user->role == 'admin' ? 'text-warning' : 'text-white' }}">
                            {{ $comment->user->name }}
                            @if($comment->user->role == 'admin') <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Admin</span> @endif
                        </a>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block mb-1">{{ $comment->created_at->diffForHumans() }}</small>
                        <a href="{{ url()->current() }}#comment-{{ $comment->id }}" class="text-secondary small text-decoration-none me-2" title="Bagikan Komentar ini">🔗 Link</a>
                        <a href="{{ route('comment.thread', $comment->id) }}" class="text-decoration-none text-info small fw-bold">Balasan &rarr;</a>
                    </div>
                </div>
                <p class="mb-1 small text-light" style="text-align: justify; line-height: 1.6;">{{ $comment->comment_text }}</p>
            </div>
            @empty
            <p class="text-muted small text-center mt-4">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
            @endforelse
        </div>
    </div>
</div>

<!-- CSS Kustom untuk Efek Seamless -->
<style>
    /* Mencegah adanya garis/spasi putih samar di antara gambar komik */
    .manga-reader-container .reader-img {
        margin-bottom: -1px; 
    }
    
    /* Efek tombol prev */
    .hover-primary:hover {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: white !important;
    }
</style>
@endsection