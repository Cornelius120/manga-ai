@extends('layout')

@section('content')
<div class="row justify-content-center mb-4">
    <div class="col-md-8 text-center">
    <h3 class="fw-bold">{{ $chapter->manga->title }}</h3>
    <h5 class="text-muted">Chapter {{ $chapter->chapter_number }} @if($chapter->title) - {{ $chapter->title }} @endif</h5>

    <a href="{{ route('manga.show', $chapter->manga->slug) }}" class="btn btn-outline-secondary btn-sm mt-2">
        &larr; Kembali ke Detail Komik
    </a>
</div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 text-center p-0 shadow-lg" style="background-color: #1a1a1a;">
        @forelse($chapter->pages as $page)
            <img src="{{ $page->image_path }}" class="img-fluid w-100 d-block" alt="Halaman {{ $page->page_number }}">
        @empty
            <div class="p-5 text-white">Belum ada gambar yang diunggah untuk chapter ini.</div>
        @endforelse
    </div>
</div>

<div class="row justify-content-center mt-4 mb-5">
    <div class="col-md-8 d-flex justify-content-between">
        
        @if($prevChapter)
            <a href="{{ route('chapter.read', ['slug' => $chapter->manga->slug, 'chapter_number' => $prevChapter->chapter_number]) }}" class="btn btn-dark">
                &larr; Chapter Sebelumnya
            </a>
        @else
            <button class="btn btn-secondary" disabled>&larr; Chapter Sebelumnya</button>
        @endif

        @if($nextChapter)
            <a href="{{ route('chapter.read', ['slug' => $chapter->manga->slug, 'chapter_number' => $nextChapter->chapter_number]) }}" class="btn btn-dark">
                Chapter Selanjutnya &rarr;
            </a>
        @else
            <button class="btn btn-secondary" disabled>Chapter Selanjutnya &rarr;</button>
        @endif

    </div>
</div>

<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-8">
        <h4 class="fw-bold border-bottom pb-2">Komentar ({{ $chapter->comments->count() }})</h4>
        
        @if(session('success'))
            <div class="alert alert-success mt-2">{{ session('success') }}</div>
        @endif

        @auth
        <form action="{{ route('comment.store', $chapter->id) }}" method="POST" class="mb-4 mt-3">
            @csrf
            <div class="mb-2">
                <textarea name="comment_text" class="form-control" rows="3" placeholder="Tulis komentarmu untuk chapter ini..." required></textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-dark btn-sm px-4">Kirim</button>
            </div>
        </form>
        @else
        <div class="alert alert-secondary mt-3">
            Silakan <a href="/login" class="fw-bold text-dark">Login</a> terlebih dahulu untuk memberikan komentar.
        </div>
        @endauth

        <div class="list-group list-group-flush mt-3">
            @forelse($chapter->comments->sortByDesc('created_at') as $comment)
            <!-- Tambahkan id="comment-{{ $comment->id }}" pada elemen pembungkus -->
<div class="list-group-item px-0 py-3" id="comment-{{ $comment->id }}">
    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
        
        <div class="d-flex align-items-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=random&size=32" class="rounded-circle me-2">
            <!-- Username nanti akan kita buat menjadi link ke Profil Publik -->
            <a href="#" class="mb-0 fw-bold text-white text-decoration-none">{{ $comment->user->name }}</a>
        </div>
        
        <div>
            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            
            <!-- Tautan Share/Deep Link Komentar -->
            <a href="{{ url()->current() }}#comment-{{ $comment->id }}" class="text-secondary small ms-2 text-decoration-none" title="Bagikan Komentar ini">🔗 Link</a>
            
            <a href="{{ route('comment.thread', $comment->id) }}" class="text-decoration-none text-info small ms-2 fw-bold">Lihat Balasan &rarr;</a>
        </div>
        
    </div>
    <p class="mb-1 ms-5 small" style="text-align: justify;">{{ $comment->comment_text }}</p>
</div>
            @empty
            <p class="text-muted small text-center mt-4">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection