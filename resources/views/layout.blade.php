<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manga Online AI</title>
    <!-- Menggunakan Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* === TEMA DARK MODE === */
        body { 
            background-color: #121212; 
            color: #e0e0e0;
        }
        
        .navbar { background-color: #000000 !important; border-bottom: 1px solid #333; }
        .card { background-color: #1e1e1e; border: 1px solid #2a2a2a; }
        .manga-card { transition: transform 0.2s; }
        .manga-card:hover { transform: scale(1.05); border-color: #0d6efd; }
        
        .btn-outline-dark { color: #ffffff; border-color: #555555; background-color: #1a1a1a; }
        .btn-outline-dark:hover { background-color: #0d6efd; border-color: #0d6efd; color: #fff; }
        .btn-dark { background-color: #2b2b2b; border-color: #444; }
        .btn-dark:hover { background-color: #444; }

        .list-group-item { background-color: #1e1e1e; border-color: #333; color: #e0e0e0; }
        .list-group-item:hover { background-color: #2a2a2a; color: #fff; }

        /* === KUSTOMISASI NAVBAR BARU === */
        .navbar .container { position: relative; }
        
        /* Memastikan Logo selalu tepat di tengah layar */
        .navbar-brand.absolute-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            margin: 0;
        }

        /* Desain Ikon Profil Bundar */
        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #1e1e1e;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d6efd;
            font-weight: bold;
            font-size: 1.2rem;
            text-decoration: none;
            border: 2px solid #0d6efd;
            transition: all 0.2s;
        }
        .profile-icon:hover, .profile-icon:focus {
            background-color: #0d6efd;
            color: #ffffff;
            outline: none;
        }
    </style>
</head>
<body>
    <!-- Navbar Utama -->
    <nav class="navbar navbar-dark mb-5 sticky-top shadow-sm py-3">
        <div class="container d-flex justify-content-between align-items-center">
            
            <!-- KIRI: Tombol Hamburger untuk Offcanvas -->
            <button class="navbar-toggler border-0 px-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuSamping" aria-controls="menuSamping" style="box-shadow: none;">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- TENGAH: Logo Website -->
            <a class="navbar-brand absolute-center fw-bold fs-4" href="/">
                <span class="text-primary">Manga</span>-AI
            </a>
            
            <!-- KANAN: Ikon Profil & Dropdown -->
            <div class="dropdown">
                <a href="#" class="profile-icon shadow-sm" id="dropdownProfil" data-bs-toggle="dropdown" aria-expanded="false">
                    @auth
    @if(Auth::user()->avatar)
        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="rounded-circle w-100 h-100" style="object-fit: cover;" alt="Avatar">
    @else
        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
    @endif
                    @else
                        <!-- Menampilkan Ikon User generik jika belum login -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                        </svg>
                    @endauth
                </a>
                
                <!-- Isi Menu Profil -->
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow border-secondary border-1 mt-2" aria-labelledby="dropdownProfil">
                    @auth
                        <li class="px-3 py-2 border-bottom border-secondary mb-1">
                            <span class="d-block small text-muted">Masuk sebagai</span>
                            <strong class="text-white">{{ Auth::user()->name }}</strong>
                        </li>
                        @if(Auth::user()->role == 'admin')
                            <li><a class="dropdown-item fw-bold text-warning mt-2" href="{{ route('admin.manga.index') }}">Panel Admin</a></li>
                        @endif
                        <li><a class="dropdown-item mt-1" href="{{ route('profile') }}">Profil Saya</a></li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="px-3 py-2 border-bottom border-secondary mb-1">
                            <span class="d-block small text-muted">Selamat datang!</span>
                        </li>
                        <li><a class="dropdown-item mt-2" href="/login">Masuk (Login)</a></li>
                        <li><a class="dropdown-item text-primary" href="/register">Daftar Akun Baru</a></li>
                    @endauth
                </ul>
            </div>

        </div>
    </nav>

    <!-- Menu Samping Kiri (Offcanvas / Laci Menu) -->
    <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="menuSamping" aria-labelledby="menuSampingLabel">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title fw-bold" id="menuSampingLabel">
                <span class="text-primary">Menu</span> Navigasi
            </h5>
            <!-- Tombol Tutup (X) -->
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav fs-5 mt-3">
                <li class="nav-item mb-4">
                    <a class="nav-link px-3 py-2 rounded" href="/">🏠 Beranda Katalog</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="nav-link px-3 py-2 rounded bg-primary text-white shadow-sm" href="{{ route('search') }}">🤖 Pencarian AI Semantik</a>
                </li>
                <li class="nav-item mb-4">
    <a class="nav-link px-3 py-2 rounded text-white" href="{{ route('library') }}">📚 Library Saya</a>
</li>
            </ul>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="container min-vh-100">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="text-center mt-5 py-4 border-top border-secondary">
        <small class="text-muted">&copy; 2026 Manga Online by Miyamura. Universitas Bina Sarana Informatika.</small>
    </footer>

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>