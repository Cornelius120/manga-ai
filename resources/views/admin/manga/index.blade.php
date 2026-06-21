<!-- Kode ini diletakkan di resources/views/admin/manga/index.blade.php -->
@extends('layout')

@section('content')
<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold">Panel Admin - Kelola Komik</h2>
        <a href="{{ route('admin.manga.create') }}" class="btn btn-dark">+ Tambah Komik Baru</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Cover</th>
                    <th>Judul</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mangas as $manga)
                <tr>
                    <td>
                        @if($manga->cover_image)
                            <!-- Menampilkan cover aslinya jika ada -->
                            <img src="{{ asset('storage/' . $manga->cover_image) }}" alt="Cover" width="50" class="rounded">
                        @else
                            <img src="https://via.placeholder.com/50" alt="No Cover" class="rounded">
                        @endif
                    </td>
                    <td class="fw-bold align-middle">{{ $manga->title }}</td>
                    <td class="align-middle">{{ $manga->author }}</td>
                    <td class="align-middle">
                        <span class="badge bg-{{ $manga->status == 'ongoing' ? 'success' : 'primary' }}">
                            {{ ucfirst($manga->status) }}
                        </span>
                    </td>
                    <td class="align-middle">
                        <!-- Tombol Tambah Chapter (Fase 5) -->
                        <a href="{{ route('admin.chapter.create', $manga->id) }}" class="btn btn-sm btn-success">+ Chapter</a>
                        
                        <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                        
                        <!-- Form Hapus Komik -->
                        <form action="{{ route('admin.manga.destroy', $manga->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus komik ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection