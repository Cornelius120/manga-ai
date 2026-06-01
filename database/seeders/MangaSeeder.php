<?php

namespace Database\Seeders;

use App\Models\Manga;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MangaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'One Piece',
                'synopsis' => 'Kisah petualangan seru seorang remaja berkekuatan karet yang mengumpulkan kru untuk mencari harta karun legendaris dan menjadi raja bajak laut.',
                'genre' => 'Adventure, Action'
            ],
            [
                'title' => 'Naruto',
                'synopsis' => 'Seorang ninja muda yang dijauhi desanya berjuang untuk mendapatkan pengakuan dan menjadi pemimpin desa bernama Hokage.',
                'genre' => 'Action, Fantasy'
            ],
            [
                'title' => 'Death Note',
                'synopsis' => 'Siswa SMA jenius yang menemukan buku catatan kematian milik dewa pencabut nyawa dan berencana menghapus kejahatan di dunia.',
                'genre' => 'Mystery, Supernatural'
            ],
            [
                'title' => 'Jujutsu Kaisen',
                'synopsis' => 'Seorang siswa SMA yang memiliki kekuatan fisik luar biasa secara tidak sengaja memakan kutipan jari iblis dan masuk ke sekolah penyihir jujutsu.',
                'genre' => 'Dark Fantasy, Action'
            ]
        ];

        foreach ($data as $item) {
            Manga::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'synopsis' => $item['synopsis'],
                'genre' => $item['genre'],
                // cover_image dikosongkan dulu untuk tes
            ]);
        }
    }
}