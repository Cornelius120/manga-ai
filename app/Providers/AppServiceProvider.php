<?php

// Kode ini diletakkan di app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // Tambahkan baris ini di atas

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Menginstruksikan Laravel untuk menggunakan gaya Bootstrap 5 untuk Pagination
        Paginator::useBootstrapFive();
    }
}