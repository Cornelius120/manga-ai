<?php

// Kode ini diletakkan di app/Models/Comment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Mengizinkan pengisian massal kecuali kolom id
    protected $guarded = ['id'];

    // Relasi balik ke tabel User (Siapa yang nulis komentar ini)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi balik ke tabel Chapter (Komentar ini ada di chapter mana)
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}