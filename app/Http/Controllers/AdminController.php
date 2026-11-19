<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $events = Event::with('creator')->orderBy('tanggal', 'DESC')->get();
        $categories = Category::all();
        $organizers = User::whereIn('role', ['penyelenggara', 'admin'])->orderBy('nama')->get();
        $total_events = Event::count();
        $total_users = User::count();
        $total_participants = Participant::whereIn('status', ['registered', 'confirmed', 'attended'])->count();
        $total_announcements = Announcement::count();

        return view('admin.dashboard', compact(
            'events',
            'categories',
            'organizers',
            'total_events',
            'total_users',
            'total_participants',
            'total_announcements'
        ));
    }

    public function users()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $users = User::orderBy('role')->orderBy('nama')->get();
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
        $users = User::whereIn('role', ['penyelenggara', 'admin'])->orderBy('nama')->get();

        return view('admin.events.edit', compact('event', 'categories', 'users'));
    }

    public function eventParticipants(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $event = Event::with('creator')->findOrFail($id);
        $allParticipants = $event->participants()->with('user')->orderBy('created_at', 'DESC')->get();

        $counts = [
            'all' => $allParticipants->count(),
            'registered' => $allParticipants->where('status', 'registered')->count(),
            'confirmed' => $allParticipants->where('status', 'confirmed')->count(),
            'attended' => $allParticipants->where('status', 'attended')->count(),
            'canceled' => $allParticipants->where('status', 'canceled')->count(),
        ];

        $statusFilter = $request->input('status');
        $participants = $statusFilter
            ? $allParticipants->where('status', $statusFilter)->values()
            : $allParticipants;

        return view('admin.events.participants', compact('event', 'participants', 'counts', 'statusFilter'));
    }

    public function exportEventCsv($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $event = Event::findOrFail($id);
        $participants = $event->participants()->with('user')->orderBy('created_at', 'DESC')->get();
        $fileName = 'peserta_event_' . $event->id . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($participants, $event) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fwrite($file, "sep=;\r\n");
            fputcsv($file, ['Kode Tiket', 'Nama Mahasiswa', 'Email', 'No Telepon', 'Nama Acara', 'Status', 'Waktu Registrasi', 'Waktu Update'], ';');

            foreach ($participants as $p) {
                fputcsv($file, [
                    $p->ticketCode(),
                    $p->user->nama ?? '-',
                    $p->user->email ?? '-',
                    $p->user->no_telp ?? '-',
                    $event->nama_event,
                    ucfirst($p->status),
                    $p->created_at ? $p->created_at->format('Y-m-d H:i:s') : '-',
                    $p->updated_at ? $p->updated_at->format('Y-m-d H:i:s') : '-',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function storeEvent(Request $request)
    {
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
            'skpi_poin' => 'nullable|integer|min:0|max:100',
            'created_by' => 'nullable|exists:users,id',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $validated['created_by'] = $validated['created_by'] ?: Auth::id();
        $validated['harga'] = $validated['harga'] ?? 0;
        $validated['skpi_poin'] = $validated['skpi_poin'] ?? 10;

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
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $event = Event::findOrFail($id);

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
            'skpi_poin' => 'nullable|integer|min:0|max:100',
            'created_by' => 'nullable|exists:users,id',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
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

        $validated['harga'] = $validated['harga'] ?? 0;
        $validated['skpi_poin'] = $validated['skpi_poin'] ?? 10;

        $event->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Event berhasil diperbarui!');
    }

    public function deleteEvent($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $event = Event::findOrFail($id);

        if ($event->activeParticipants()->count() > 0) {
            return redirect()->route('admin.dashboard')->with('error', 'Event tidak dapat dihapus karena masih memiliki peserta aktif!');
        }

        if ($event->poster && Storage::disk('public')->exists($event->poster)) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Event berhasil dihapus!');
    }

    public function storeUser(Request $request)
    {
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

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,penyelenggara,mahasiswa',
            'no_telp' => 'nullable|string|max:20',
            'password' => 'nullable|min:6',
        ]);

        if ((int) $user->id === (int) Auth::id() && $validated['role'] !== 'admin') {
            return redirect()->route('admin.users')
                ->with('error', 'Anda tidak dapat menurunkan role akun administrator Anda sendiri!');
        }

        $data = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'no_telp' => $validated['no_telp'] ?? null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function deleteUser($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $user = User::findOrFail($id);

        if ((int) $user->id === (int) Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'Tidak dapat menghapus akun yang sedang digunakan!');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.users')->with('error', 'Akun administrator tidak dapat dihapus!');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil dihapus!');
    }

    public function announcements()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $announcements = Announcement::with('creator')->orderBy('created_at', 'DESC')->get();
        return view('admin.announcements', compact('announcements'));
    }

    public function storeAnnouncement(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.announcements')->with('success', 'Pengumuman berhasil dipublikasikan!');
    }

    public function updateAnnouncement(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
        ]);

        $announcement->update($validated);

        return redirect()->route('admin.announcements')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function deleteAnnouncement($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('admin.announcements')->with('success', 'Pengumuman berhasil dihapus!');
    }

    public function exportEventReport($id, string $format)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        if (!in_array($format, ['excel', 'word'], true)) {
            abort(404);
        }

        $event = Event::with('creator')->findOrFail($id);
        $participants = Participant::with(['user', 'event.creator'])
            ->where('event_id', $event->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $reportTitle = 'Laporan Peserta Acara (Admin)';
        $exportedBy = Auth::user()->nama . ' (Administrator)';
        $html = view('exports.participants-report', compact('participants', 'event', 'reportTitle', 'exportedBy', 'format'))->render();

        if ($format === 'excel') {
            $fileName = "laporan_peserta_event_{$event->id}_" . date('Ymd_His') . '.xls';
            $contentType = 'application/vnd.ms-excel; charset=utf-8';
        } else {
            $fileName = "laporan_peserta_event_{$event->id}_" . date('Ymd_His') . '.doc';
            $contentType = 'application/msword; charset=utf-8';
        }

        return response("\xEF\xBB\xBF" . $html, 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }
}
