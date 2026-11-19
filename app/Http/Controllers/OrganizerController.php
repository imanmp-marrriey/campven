<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizerController extends Controller
{
    public function dashboard()
    {
        // Cek role - jika bukan penyelenggara, redirect
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $user = Auth::user();
        $events = Event::where('created_by', $user->id)
            ->with(['participants.user'])
            ->orderBy('tanggal', 'DESC')
            ->get();

        $total_events = $events->count();
        $total_participants = $events->sum(function ($event) {
            return $event->participants()->count();
        });
        $attended_participants = $events->sum(function ($event) {
            return $event->participants()->where('status', 'attended')->count();
        });
        $attendance_rate = $total_participants > 0 ? round(($attended_participants / $total_participants) * 100, 1) : 0;
        
        $total_quota = $events->sum('kuota');
        $quota_rate = $total_quota > 0 ? min(100, round(($total_participants / $total_quota) * 100, 1)) : 0;

        $active_event = $events->first();

        $recent_attendances = \App\Models\Participant::with(['user', 'event'])
            ->whereHas('event', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })
            ->where('status', 'attended')
            ->orderBy('updated_at', 'DESC')
            ->limit(5)
            ->get();

        $categories = Category::all();

        return view('organizer.dashboard', compact(
            'events',
            'total_events',
            'total_participants',
            'attended_participants',
            'attendance_rate',
            'quota_rate',
            'active_event',
            'recent_attendances',
            'user',
            'categories'
        ));
    }

    public function editEvent($id)
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $event = Event::where('created_by', Auth::id())->findOrFail($id);
        $categories = Category::all();

        return view('organizer.events.edit', compact('event', 'categories'));
    }

    public function storeEvent(Request $request)
    {
        // Cek role - jika bukan penyelenggara, redirect
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'nama_event' => 'required|string|max:200',
            'kategori' => 'required|string|max:50',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kuota' => 'nullable|integer|min:1',
            'harga' => 'nullable|integer|min:0',
            'skpi_poin' => 'nullable|integer|min:0',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'tanggal.after_or_equal' => 'Tanggal event tidak boleh di masa lalu.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $poster = null;
        if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
            try {
                $file = $request->file('poster');
                $fileName = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $path = $file->storeAs('posters', $fileName, 'public');
                $poster = $path;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal upload poster: '.$e->getMessage())->withInput();
            }
        }

        Event::create(array_merge($validated, [
            'poster' => $poster,
            'created_by' => Auth::id(),
        ]));

        return redirect()->route('organizer.dashboard')->with('success', 'Event berhasil ditambahkan!');
    }

    public function updateEvent(Request $request, $id)
    {
        // Cek role - jika bukan penyelenggara, redirect
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $event = Event::where('created_by', Auth::id())->findOrFail($id);

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
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
            // Hapus poster lama
            if ($event->poster && Storage::disk('public')->exists($event->poster)) {
                Storage::disk('public')->delete($event->poster);
            }

            try {
                $file = $request->file('poster');
                $fileName = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $path = $file->storeAs('posters', $fileName, 'public');
                $validated['poster'] = $path;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal upload poster: '.$e->getMessage())->withInput();
            }
        }

        $event->update($validated);

        return redirect()->route('organizer.dashboard')->with('success', 'Event berhasil diperbarui!');
    }

    public function deleteEvent($id)
    {
        // Cek role - jika bukan penyelenggara, redirect
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $event = Event::where('created_by', Auth::id())->findOrFail($id);

        if ($event->participants()->count() > 0) {
            return redirect()->route('organizer.dashboard')->with('error', 'Event tidak dapat dihapus karena memiliki peserta!');
        }

        if ($event->poster && Storage::disk('public')->exists($event->poster)) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('organizer.dashboard')->with('success', 'Event berhasil dihapus!');
    }

    public function confirmParticipant($id)
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $participant = \App\Models\Participant::whereHas('event', function ($query) {
            $query->where('created_by', Auth::id());
        })->findOrFail($id);

        $participant->update(['status' => 'confirmed']);

        return redirect()->route('organizer.dashboard')->with('success', 'Pendaftaran peserta berhasil dikonfirmasi!');
    }

    public function attendParticipant($id)
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $participant = \App\Models\Participant::whereHas('event', function ($query) {
            $query->where('created_by', Auth::id());
        })->findOrFail($id);

        $participant->update(['status' => 'attended']);

        return redirect()->route('organizer.dashboard')->with('success', 'Kehadiran peserta berhasil dikonfirmasi!');
    }

    public function quickCheckIn(Request $request)
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'query' => 'required|string',
            'event_id' => 'nullable|exists:events,id',
        ]);

        $query = trim($validated['query']);
        $myEventIds = Event::where('created_by', Auth::id())->pluck('id')->toArray();

        if (empty($myEventIds)) {
            return redirect()->route('organizer.dashboard')->with('error', "Anda belum memiliki event aktif untuk melakukan presensi.");
        }

        $participantQuery = \App\Models\Participant::with(['user', 'event'])
            ->whereIn('event_id', $myEventIds)
            ->where(function ($w) use ($query) {
                if (is_numeric($query)) {
                    $w->where('id', $query)->orWhere('user_id', $query);
                }
                $w->orWhereHas('user', function ($q) use ($query) {
                    $q->where('nama', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%")
                      ->orWhere('no_telp', 'LIKE', "%{$query}%");
                });
            });

        if (!empty($validated['event_id']) && in_array($validated['event_id'], $myEventIds)) {
            $participant = (clone $participantQuery)->where('event_id', $validated['event_id'])->first();
            if (!$participant) {
                $participant = $participantQuery->first();
            }
        } else {
            $participant = $participantQuery->first();
        }

        if (!$participant) {
            return redirect()->route('organizer.dashboard')->with('error', "Peserta dengan data '{$query}' tidak ditemukan pada event yang Anda kelola!");
        }

        if ($participant->status === 'attended') {
            return redirect()->route('organizer.dashboard')->with('success', "Peserta {$participant->user->nama} ({$participant->event->nama_event}) SUDAH HADIR sebelumnya pada " . $participant->updated_at->format('H:i') . " WIB.");
        }

        $participant->update(['status' => 'attended']);

        return redirect()->route('organizer.dashboard')->with('success', "Presensi Berhasil! {$participant->user->nama} resmi tercatat HADIR di {$participant->event->nama_event}.");
    }

    public function exportCsv(Request $request, $eventId = null)
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $myEventIds = Event::where('created_by', Auth::id())->pluck('id');

        $query = \App\Models\Participant::with(['user', 'event'])
            ->whereIn('event_id', $myEventIds);

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        $participants = $query->orderBy('created_at', 'DESC')->get();

        $fileName = 'log_presensi_campven_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($participants) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['ID Tiket', 'Nama Mahasiswa', 'Email', 'No Telepon', 'Nama Acara', 'Kategori', 'Tanggal Pelaksanaan', 'Status Kehadiran', 'Waktu Registrasi', 'Waktu Verifikasi Presensi']);

            foreach ($participants as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->user->nama ?? '-',
                    $p->user->email ?? '-',
                    $p->user->no_telp ?? '-',
                    $p->event->nama_event ?? '-',
                    $p->event->kategori ?? '-',
                    $p->event->tanggal ?? '-',
                    ucfirst($p->status),
                    $p->created_at ? $p->created_at->format('Y-m-d H:i:s') : '-',
                    $p->updated_at ? $p->updated_at->format('Y-m-d H:i:s') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
