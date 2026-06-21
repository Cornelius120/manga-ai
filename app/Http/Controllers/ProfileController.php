<?php

// Kode ini diletakkan di app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    // Mengubah Nama Tampilan
    public function updateInfo(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return back()->with('success', 'Nama tampilan berhasil diperbarui!');
    }

    // Mengubah Password dan Paksa Logout
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Cek apakah password lama sesuai
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah!']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        Auth::logout(); // Paksa pengguna untuk login ulang
        return redirect('/login')->with('success', 'Password berhasil diubah. Silakan login kembali dengan password baru.');
    }

    // Mengunggah Foto Profil Menggunakan AJAX
    public function uploadAvatar(Request $request)
    {
        // Validasi: Harus berupa gambar, max 5MB (5120 KB)
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', 
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Hapus foto lama dari penyimpanan lokal jika sudah punya
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan foto baru ke folder storage/app/public/avatars
            $imagePath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $imagePath;
            $user->save();

            // Mengembalikan respons JSON untuk ditangkap oleh AJAX
            return response()->json([
                'success' => true,
                'avatar_url' => asset('storage/' . $imagePath)
            ]);
        }

        return response()->json(['success' => false], 400);
    }
}