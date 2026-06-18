@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <img src="https://via.placeholder.com/400x550?text=Cover+Komik" class="img-fluid rounded shadow-sm w-100" alt="Cover {{ $manga->title }}">
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
                    <a href="{{ route('chapter.read', $chapter->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div>
                            <span class="fw-bold">{{ $chapter->chapter_number }}</span>
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
@endsection