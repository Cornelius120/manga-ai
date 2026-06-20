@extends('layout')

@section('content')
<div class="row justify-content-center mt-4 mb-5">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center p-5">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&size=128" class="rounded-circle mb-3 shadow-sm" alt="Avatar">
                
                <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                
                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'dark' }} mb-4 px-3 py-2">
                    Role: {{ strtoupper($user->role) }}
                </span>
                
                <hr>
                <p class="small text-muted mb-0">Bergabung sejak: {{ $user->created_at->format('d F Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection