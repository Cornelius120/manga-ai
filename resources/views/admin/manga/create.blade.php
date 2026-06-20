<!-- Kode ini diletakkan di resources/views/admin/manga/create.blade.php -->
@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-dark text-white fw-bold">Tambah Komik Baru</div>
            <div class="card-body p-4">
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.manga.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="fw-bold">Judul Komik</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Penulis / Author</label>
                        <input type="text" name="author" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Gambar Cover</label>
                        <input type="file" name="cover_image" class="form-control" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG. Maksimal ukuran: 2MB.</small>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold d-block">Pilih Genre</label>
                        @foreach($genres as $genre)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="genres[]" value="{{ $genre->id }}" id="genre{{ $genre->id }}">
                                <label class="form-check-label" for="genre{{ $genre->id }}">{{ $genre->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Status Komik</label>
                            <select name="status" class="form-select" required>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold">Sinopsis</label>
                        <textarea name="synopsis" class="form-control" rows="5" required></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.manga.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-dark px-4">Simpan Komik</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection