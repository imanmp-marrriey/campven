<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $my_events_count = Event::where('created_by', $user->id)->count();
        $joined_events_count = Participant::where('user_id', $user->id)->count();

        // Tentukan halaman kembali berdasarkan role
        if ($user->role === 'admin') {
            $back_url = route('admin.dashboard');
        } elseif ($user->role === 'penyelenggara') {
            $back_url = route('organizer.dashboard');
        } else {
            $back_url = route('home');
        }

        return view('profile.index', compact('user', 'my_events_count', 'joined_events_count', 'back_url'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'bio' => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'foto.image' => 'File foto profil harus berupa gambar yang valid.',
            'foto.mimes' => 'Format foto profil yang didukung: JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'bio' => $validated['bio'] ?? null,
            'no_telp' => $validated['no_telp'] ?? null,
        ];

        // Upload / ganti foto profil
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $oldPhoto = $user->foto;

            try {
                $file = $request->file('foto');
                $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                $fileName = 'avatar_' . $user->id . '_' . time() . '.' . $ext;
                $path = $file->storeAs('avatars', $fileName, 'public');
                $data['foto'] = $path;

                // Hapus foto lama setelah upload baru berhasil
                if ($oldPhoto && $oldPhoto !== $path && Storage::disk('public')->exists($oldPhoto)) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal upload foto: ' . $e->getMessage())->withInput();
            }
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}
