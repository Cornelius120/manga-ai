<?php

// Kode ini diletakkan di app/Http/Controllers/LibraryController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookmark;
use App\Models\ReadingHistory;

class LibraryController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        
        // Mengambil data bookmark beserta info manganya
        $bookmarks = Bookmark::with('manga')->where('user_id', $user_id)->latest()->get();
        
        // Mengambil riwayat baca terakhir beserta info manga dan chapter
        $histories = ReadingHistory::with(['manga', 'chapter'])->where('user_id', $user_id)->orderBy('updated_at', 'desc')->get();
        
        return view('library.index', compact('bookmarks', 'histories'));
    }

    public function toggleBookmark($manga_id)
    {
        $bookmark = Bookmark::where('user_id', Auth::id())->where('manga_id', $manga_id)->first();
        
        if ($bookmark) {
            $bookmark->delete();
            return back()->with('success', 'Dihapus dari Bookmark.');
        } else {
            Bookmark::create(['user_id' => Auth::id(), 'manga_id' => $manga_id]);
            return back()->with('success', 'Ditambahkan ke Bookmark!');
        }
    }
}