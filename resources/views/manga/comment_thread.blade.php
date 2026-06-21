<!-- Kode ini diletakkan di resources/views/manga/comment_thread.blade.php -->
@extends('layout')

@section('content')
<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-8">
        
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm mb-4">&larr; Kembali ke Komik</a>

        <!-- Komentar Utama -->
        <div class="card border-primary mb-4" style="background-color: #16181f;">
            <div class="card-header bg-primary text-white fw-bold border-0 d-flex justify-content-between align-items-center">
                <span>Komentar Utama oleh {{ $comment->user->name }}</span>
                <span class="badge bg-dark">{{ $comment->manga->title }}</span>
            </div>
            <div class="card-body p-4 text-white">
                <p class="mb-0 fs-5">{{ $comment->comment }}</p>
                <small class="text-muted d-block mt-3">{{ $comment->created_at->format('d M Y - H:i') }}</small>
            </div>
        </div>

        <hr class="border-secondary mb-4">

        <!-- Form Balasan -->
        <div class="card border-0 mb-5" style="background-color: #1e212b;">
            <div class="card-body">
                <form action="{{ route('comment.reply', $comment->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="comment" rows="3" class="form-control bg-dark text-white border-secondary" placeholder="Tulis balasanmu di sini..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success px-4">Kirim Balasan</button>
                </form>
            </div>
        </div>

        <!-- Daftar Balasan -->
        <h5 class="text-white fw-bold mb-3"><i class="bi bi-chat-dots"></i> {{ $comment->replies->count() }} Balasan</h5>
        <div class="list-group">
            @forelse($comment->replies as $reply)
                <div class="list-group-item bg-transparent border-secondary text-white py-3 px-4 mb-2 rounded" style="background-color: #16181f !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong class="{{ $reply->user->role == 'admin' ? 'text-warning' : 'text-info' }}">
                            {{ $reply->user->name }}
                        </strong>
                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-0">{{ $reply->comment }}</p>
                </div>
            @empty
                <div class="text-muted text-center py-4">Belum ada balasan. Jadilah yang pertama membalas!</div>
            @endforelse
        </div>

    </div>
</div>
@endsection