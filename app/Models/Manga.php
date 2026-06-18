<?php

// Kode ini diletakkan di app/Models/Manga.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Mengizinkan semua kolom diisi kecuali ID

    // Relasi ke tabel Chapter (1 Komik punya banyak Chapter)
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    // Relasi ke tabel Genre (Many to Many)
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'manga_genres');
    }
}