<!-- Kode ini diletakkan di resources/views/library/index.blade.php -->
@extends('layout')

@section('content')
<div class="row mt-4 mb-5 justify-content-center">
    <div class="col-md-10">
        <h3 class="fw-bold text-white mb-4"><i class="bi bi-collection-play me-2"></i>Library Saya</h3>
        
        <div class="card border-0 shadow-sm" style="background-color: #16181f; border-radius: 12px;">
            <!-- Header Tab Navigasi -->
            <div class="card-header border-bottom border-secondary p-0" style="background-color: #1e212b; border-radius: 12px 12px 0 0;">
                <ul class="nav nav-pills nav-fill" id="libraryPills" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-0 fw-bold w-100" id="history-tab" data-bs-toggle="pill" data-bs-target="#history" type="button" role="tab" style="border-radius: 12px 0 0 0 !important;">🕒 Riwayat Baca</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-0 fw-bold w-100 text-muted" id="bookmark-tab" data-bs-toggle="pill" data-bs-target="#bookmark" type="button" role="tab" style="border-radius: 0 12px 0 0 !important;" onclick="this.classList.remove('text-muted'); document.getElementById('history-tab').classList.add('text-muted');">🔖 Bookmark</button>
                    </li>
                </ul>
            </div>

            <!-- Isi Konten -->
            <div class="card-body p-4">
                <div class="tab-content">
                    
                    <!-- TAB RIWAYAT -->
                    <div class="tab-pane fade show active" id="history" role="tabpanel">
                        <div class="list-group list-group-flush">
                            @forelse($histories as $history)
                                <a href="{{ route('chapter.read', ['slug' => $history->manga->slug, 'chapter_number' => $history->chapter->chapter_number]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-transparent text-white border-secondary py-3 px-0">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $history->manga->cover_image) }}" alt="Cover" class="rounded me-3" style="width: 50px; height: 70px; object-fit: cover;">
                                        <div>
                                            <h6 class="fw-bold mb-1">{{ $history->manga->title }}</h6>
                                            <small class="text-primary">Melanjutkan Chapter {{ $history->chapter->chapter_number }}</small>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $history->updated_at->diffForHumans() }}</small>
                                </a>
                            @empty
                                <div class="text-center py-5 text-muted">Belum ada riwayat bacaan.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- TAB BOOKMARK -->
                    <div class="tab-pane fade" id="bookmark" role="tabpanel">
                        <div class="row row-cols-2 row-cols-md-4 g-3 mt-2">
                            @forelse($bookmarks as $bookmark)
                                <div class="col">
                                    <div class="card bg-dark border-secondary h-100 manga-card">
                                        <img src="{{ asset('storage/' . $bookmark->manga->cover_image) }}" class="card-img-top" alt="Cover" style="height: 250px; object-fit: cover;">
                                        <div class="card-body p-2 text-center">
                                            <h6 class="text-white small fw-bold text-truncate mb-2">{{ $bookmark->manga->title }}</h6>
                                            <a href="{{ route('manga.show', $bookmark->manga->slug) }}" class="btn btn-outline-primary btn-sm w-100">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5 text-muted w-100">Belum ada komik yang di-bookmark.</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('bookmark-tab').addEventListener('click', function() {
        this.classList.remove('text-muted');
        document.getElementById('history-tab').classList.add('text-muted');
    });
    document.getElementById('history-tab').addEventListener('click', function() {
        this.classList.remove('text-muted');
        document.getElementById('bookmark-tab').classList.add('text-muted');
    });
</script>
@endsection