@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <!-- Area Gambar Cover -->
        @if($manga->cover_image)
            <img src="{{ asset('storage/' . $manga->cover_image) }}" class="img-fluid rounded shadow-sm w-100" alt="Cover {{ $manga->title }}">
        @else
            <img src="https://via.placeholder.com/400x550?text=Cover+Komik" class="img-fluid rounded shadow-sm w-100" alt="Cover {{ $manga->title }}">
        @endif

        <!-- LETAKKAN TOMBOL BOOKMARK DI SINI (Di bawah Cover) -->
        @auth
            <form action="{{ route('bookmark.toggle', $manga->id) }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-outline-warning w-100 fw-bold">🔖 Simpan ke Bookmark</button>
            </form>
        @endauth
    </div>

    <div class="col-md-8 mb-4">
        <h2 class="fw-bold mb-1">{{ $manga->title }}</h2>
        <p class="text-muted mb-3">Oleh: <span class="fw-semibold">{{ $manga->author }}</span></p>

        <div class="mb-3">
            <span class="badge bg-{{ $manga->status == 'ongoing' ? 'success' : 'primary' }} me-2">
                {{ ucfirst($manga->status) }}
            </span>
            @foreach($manga->genres as $genre)
                <span class="badge bg-secondary">{{ $genre->name }}</span>
            @endforeach
        </div>

        <h5 class="fw-bold mt-4">Sinopsis</h5>
        <p style="line-height: 1.7; text-align: justify;">{{ $manga->synopsis }}</p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <h4 class="fw-bold mb-3 border-bottom pb-2">Daftar Chapter</h4>
        
        @if($manga->chapters->count() > 0)
            <div class="list-group shadow-sm">
                @foreach($manga->chapters as $chapter)
                    <a href="{{ route('chapter.read', ['slug' => $manga->slug, 'chapter_number' => $chapter->chapter_number]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
    <div>
        <span class="fw-bold">Chapter {{ $chapter->chapter_number }}</span>
        @if($chapter->title)
            <span class="text-muted ms-2">- {{ $chapter->title }}</span>
        @endif
    </div>
    <span class="badge bg-dark rounded-pill">Baca</span>
</a>
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center">
                Belum ada chapter yang tersedia untuk komik ini.
            </div>
        @endif
    </div>
</div>

<!-- KODE KOMENTAR DETAIL KOMIK -->
<div class="row mt-5 mb-5">
    <div class="col-md-12">
        @php 
            // Menarik komentar khusus untuk komik ini saja
            $mangaComments = App\Models\Comment::where('manga_id', $manga->id)->whereNull('chapter_id')->latest()->get(); 
        @endphp
        
        <h4 class="fw-bold border-bottom border-secondary pb-2">Komentar Komik ({{ $mangaComments->count() }})</h4>
        
        @if(session('success'))
            <div class="alert alert-success mt-2">{{ session('success') }}</div>
        @endif

        @auth
        <form action="{{ route('manga.comment.store', $manga->id) }}" method="POST" class="mb-4 mt-3">
            @csrf
            <div class="mb-2">
                <textarea name="comment_text" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Bagaimana pendapatmu tentang komik ini secara keseluruhan?" required></textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">Kirim Komentar</button>
            </div>
        </form>
        @else
        <div class="alert alert-secondary mt-3 bg-dark text-white border-secondary">
            Silakan <a href="/login" class="fw-bold text-primary">Login</a> terlebih dahulu untuk memberikan komentar.
        </div>
        @endauth

        <div class="list-group list-group-flush mt-3">
            @forelse($mangaComments as $comment)
            <!-- ID untuk Deep Linking -->
            <div class="list-group-item px-4 py-3 bg-transparent text-white border-secondary mb-2 rounded shadow-sm" style="background-color: #1a1a1a !important;" id="comment-{{ $comment->id }}">
                <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=random' }}" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;">
                        
                        <!-- Tautan ke Profil Publik -->
                        <a href="{{ route('profile.public', $comment->user->id) }}" class="mb-0 fw-bold text-decoration-none {{ $comment->user->role == 'admin' ? 'text-warning' : 'text-white' }}">
                            {{ $comment->user->name }}
                            @if($comment->user->role == 'admin') <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Admin</span> @endif
                        </a>
                    </div>
                    <div>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
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