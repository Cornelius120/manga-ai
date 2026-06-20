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
}