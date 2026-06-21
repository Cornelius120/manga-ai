<?php

// Kode ini diletakkan di app/Http/Controllers/CommentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $chapter_id)
    {
        // Validasi komentar (maksimal 1000 karakter)
        $request->validate([
            'comment_text' => 'required|string|max:1000',
        ]);

        // Simpan ke database
        Comment::create([
            'user_id' => Auth::id(),
            'chapter_id' => $chapter_id,
            'comment_text' => $request->comment_text,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    // Menampilkan halaman khusus satu utas komentar beserta balasannya
    public function showThread($id)
    {
        // Tarik komentar utama beserta relasi balasannya
        $comment = \App\Models\Comment::with(['user', 'manga', 'replies.user'])->findOrFail($id);
        return view('manga.comment_thread', compact('comment'));
    }

    // Menyimpan balasan
    public function reply(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string']);
        $parentComment = \App\Models\Comment::findOrFail($id);

        \App\Models\Comment::create([
            'user_id' => Auth::id(),
            'manga_id' => $parentComment->manga_id,
            'chapter_id' => $parentComment->chapter_id,
            'parent_id' => $parentComment->id, // Ini yang menjadikannya sebuah balasan
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }
}