<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Cek role - jika bukan admin, redirect
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $events = Event::with('creator')->orderBy('tanggal', 'DESC')->get();
        $categories = Category::all();
        $total_events = Event::count();
        $total_users = User::count();
        $total_participants = Participant::count();

        return view('admin.dashboard', compact(
            'events',
            'categories',
            'total_events',
            'total_users',
            'total_participants'
        ));
    }

    public function users()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $users = User::orderBy('role')->get();
        $total_users = User::count();
        $total_admin = User::where('role', 'admin')->count();
        $total_penyelenggara = User::where('role', 'penyelenggara')->count();
        $total_mahasiswa = User::where('role', 'mahasiswa')->count();

        return view('admin.users', compact(
            'users',
            'total_users',
            'total_admin',
            'total_penyelenggara',
            'total_mahasiswa'
        ));
    }

    public function editEvent($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $event = Event::findOrFail($id);
        $categories = Category::all();
        $users = User::all();

        return view('admin.events.edit', compact('event', 'categories', 'users'));
    }

    public function storeEvent(Request $request)
    {
        // Cek role - jika bukan admin, redirect
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'nama_event' => 'required|string|max:200',
            'kategori' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kuota' => 'nullable|integer|min:1',
            'harga' => 'nullable|integer|min:0',
            'skpi_poin' => 'nullable|integer|min:0',
            'created_by' => 'nullable|exists:users,id',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $validated['created_by'] = $validated['created_by'] ?: Auth::id();

        // Handle poster upload
        $poster = null;
        if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
            try {
                $file = $request->file('poster');
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $path = $file->storeAs('posters', $fileName, 'public');
                $poster = $path;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal upload poster: ' . $e->getMessage())->withInput();
            }
        }

        Event::create(array_merge($validated, ['poster' => $poster]));

        return redirect()->route('admin.dashboard')->with('success', 'Event berhasil ditambahkan!');
    }

    public function updateEvent(Request $request, $id)
    {
        // Cek role - jika bukan admin, redirect
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'nama_event' => 'required|string|max:200',
            'kategori' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kuota' => 'nullable|integer|min:1',
            'harga' => 'nullable|integer|min:0',
            'skpi_poin' => 'nullable|integer|min:0',
            'created_by' => 'nullable|exists:users,id',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

    // Handle poster upload
    if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
        // Hapus poster lama
        if ($event->poster && Storage::disk('public')->exists($event->poster)) {
            Storage::disk('public')->delete($event->poster);
        }
        
        try {
            $file = $request->file('poster');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('posters', $fileName, 'public');
            $validated['poster'] = $path;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal upload poster: ' . $e->getMessage())->withInput();
        }
    }

    $event->update($validated);

    return redirect()->route('admin.dashboard')->with('success', 'Event berhasil diperbarui!');
}

    public function deleteEvent($id)
    {
        // Cek role - jika bukan admin, redirect
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $event = Event::findOrFail($id);

        if ($event->participants()->count() > 0) {
            return redirect()->route('admin.dashboard')->with('error', 'Event tidak dapat dihapus karena memiliki peserta!');
        }

        // Hapus poster
        if ($event->poster && Storage::disk('public')->exists($event->poster)) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Event berhasil dihapus!');
    }

    public function storeUser(Request $request)
    {
        // Cek role - jika bukan admin, redirect
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,penyelenggara,mahasiswa',
            'no_telp' => 'nullable|string|max:20',
        ]);

        User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'no_telp' => $validated['no_telp'] ?? null,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id)
    {
        // Cek role - jika bukan admin, redirect
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,penyelenggara,mahasiswa',
            'no_telp' => 'nullable|string|max:20',
        ]);

        $data = $validated;
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil diperbarui!');
    }

    public function deleteUser($id)
    {
        // Cek role - jika bukan admin, redirect
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Tidak dapat menghapus admin!');
        }
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User berhasil dihapus!');
    }
}