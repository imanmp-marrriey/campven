<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizerController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $user = Auth::user();
        $events = Event::where('created_by', $user->id)
            ->with(['participants.user'])
            ->orderBy('tanggal', 'DESC')
            ->get();

        $total_events = $events->count();
        $upcoming_events_count = $events->filter(function ($event) {
            return Carbon::parse($event->tanggal)->gte(Carbon::today());
        })->count();

        $total_participants = $events->sum(function ($event) {
            return $event->activeParticipants()->count();
        });
        $attended_participants = $events->sum(function ($event) {
            return $event->participants()->where('status', 'attended')->count();
        });
        $attendance_rate = $total_participants > 0 ? round(($attended_participants / $total_participants) * 100, 1) : 0;

        $eventsWithQuota = $events->filter(fn ($e) => $e->kuota && $e->kuota > 0);
        $total_quota = $eventsWithQuota->sum('kuota');
        $filled_quota = $eventsWithQuota->sum(fn ($e) => $e->activeParticipants()->count());
        $quota_rate = $total_quota > 0 ? min(100, round(($filled_quota / $total_quota) * 100, 1)) : 0;

        $active_event = $events->first(function ($event) {
            return Carbon::parse($event->tanggal)->gte(Carbon::today());
        }) ?? $events->first();

        $recent_attendances = Participant::with(['user', 'event'])
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
            'upcoming_events_count',
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

    public function participants(Request $request, $id)
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $event = Event::where('created_by', Auth::id())->findOrFail($id);
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

        return view('organizer.events.participants', compact('event', 'participants', 'counts', 'statusFilter'));
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
            'skpi_poin' => 'nullable|integer|min:0|max:100',
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
            'harga' => $validated['harga'] ?? 0,
            'skpi_poin' => $validated['skpi_poin'] ?? 10,
            'poster' => $poster,
            'created_by' => Auth::id(),
        ]));

        return redirect()->route('organizer.dashboard')->with('success', 'Event berhasil ditambahkan!');
    }

    public function updateEvent(Request $request, $id)
    {
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
            'skpi_poin' => 'nullable|integer|min:0|max:100',
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
                $fileName = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $path = $file->storeAs('posters', $fileName, 'public');
                $validated['poster'] = $path;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal upload poster: '.$e->getMessage())->withInput();
            }
        }

        $validated['harga'] = $validated['harga'] ?? 0;
        $validated['skpi_poin'] = $validated['skpi_poin'] ?? 10;

        $event->update($validated);

        return redirect()->route('organizer.dashboard')->with('success', 'Event berhasil diperbarui!');
    }

    public function deleteEvent($id)
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $event = Event::where('created_by', Auth::id())->findOrFail($id);

        if ($event->activeParticipants()->count() > 0) {
            return redirect()->route('organizer.dashboard')->with('error', 'Event tidak dapat dihapus karena masih memiliki peserta aktif!');
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

        $participant = Participant::with('user')->whereHas('event', function ($query) {
            $query->where('created_by', Auth::id());
        })->findOrFail($id);

        if ($participant->status === 'canceled') {
            return redirect()->back()->with('error', "Pendaftaran {$participant->user->nama} sudah dibatalkan dan tidak dapat dikonfirmasi.");
        }

        if ($participant->status === 'attended') {
            return redirect()->back()->with('success', "Peserta {$participant->user->nama} sudah berstatus hadir.");
        }

        $participant->update(['status' => 'confirmed']);

        return redirect()->back()->with('success', "Pendaftaran {$participant->user->nama} berhasil dikonfirmasi!");
    }

    public function attendParticipant($id)
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $participant = Participant::with('user')->whereHas('event', function ($query) {
            $query->where('created_by', Auth::id());
        })->findOrFail($id);

        if ($participant->status === 'canceled') {
            return redirect()->back()->with('error', "Pendaftaran {$participant->user->nama} sudah dibatalkan dan tidak dapat dipresensi.");
        }

        $participant->update(['status' => 'attended']);

        return redirect()->back()->with('success', "Kehadiran {$participant->user->nama} berhasil diverifikasi!");
    }

    public function cancelParticipant($id)
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $participant = Participant::with('user')->whereHas('event', function ($query) {
            $query->where('created_by', Auth::id());
        })->findOrFail($id);

        if ($participant->status === 'attended') {
            return redirect()->back()->with('error', 'Peserta yang sudah hadir tidak dapat dibatalkan.');
        }

        $participant->update(['status' => 'canceled']);

        return redirect()->back()->with('success', "Pendaftaran {$participant->user->nama} berhasil ditolak/dibatalkan.");
    }

    public function quickCheckIn(Request $request)
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'query' => 'required|string|max:150',
            'event_id' => 'required|integer',
        ]);

        $event = Event::where('created_by', Auth::id())->findOrFail($validated['event_id']);
        $query = trim($validated['query']);
        $participant = null;

        if (preg_match('/(?:^|\/ticket\/)CV-(\d+)-(\d+)$/i', $query, $matches)) {
            if ((int) $matches[1] !== (int) $event->id) {
                return redirect()->back()->with('error', "Kode tiket tersebut bukan untuk acara {$event->nama_event}.");
            }
            $participant = Participant::with('user')
                ->where('event_id', $event->id)
                ->where('id', (int) $matches[2])
                ->first();
        } elseif (ctype_digit($query)) {
            $participant = Participant::with('user')
                ->where('event_id', $event->id)
                ->where('id', (int) $query)
                ->first();
        } else {
            $participant = Participant::with('user')
                ->where('event_id', $event->id)
                ->whereHas('user', function ($q) use ($query) {
                    $q->whereRaw('LOWER(email) = ?', [strtolower($query)]);
                })
                ->first();
        }

        if (!$participant) {
            return redirect()->back()->with('error', "Tiket atau email peserta '{$query}' tidak ditemukan pada acara {$event->nama_event}.");
        }

        if ($participant->status === 'canceled') {
            return redirect()->back()->with('error', "Pendaftaran peserta {$participant->user->nama} sudah dibatalkan dan tidak dapat dipresensi.");
        }

        if ($participant->status === 'attended') {
            return redirect()->back()->with('success', "Peserta {$participant->user->nama} sudah tercatat HADIR sebelumnya pada " . $participant->updated_at->format('H:i') . ' WIB.');
        }

        $participant->update(['status' => 'attended']);

        return redirect()->back()->with('success', "Presensi Berhasil! {$participant->user->nama} ({$participant->ticketCode()}) tercatat HADIR di {$event->nama_event}.");
    }

    public function exportCsv(Request $request, $eventId = null)
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        $query = Participant::with(['user', 'event']);

        if ($eventId !== null) {
            $event = Event::where('created_by', Auth::id())->findOrFail($eventId);
            $query->where('event_id', $event->id);
        } else {
            $myEventIds = Event::where('created_by', Auth::id())->pluck('id');
            $query->whereIn('event_id', $myEventIds);
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
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fwrite($file, "sep=;\r\n");

            fputcsv($file, ['Kode Tiket', 'Nama Mahasiswa', 'Email', 'No Telepon', 'Nama Acara', 'Kategori', 'Tanggal Pelaksanaan', 'Status Kehadiran', 'Waktu Registrasi', 'Waktu Verifikasi Presensi'], ';');

            foreach ($participants as $p) {
                fputcsv($file, [
                    $p->ticketCode(),
                    $p->user->nama ?? '-',
                    $p->user->email ?? '-',
                    $p->user->no_telp ?? '-',
                    $p->event->nama_event ?? '-',
                    $p->event->kategori ?? '-',
                    $p->event->tanggal ? Carbon::parse($p->event->tanggal)->format('Y-m-d') : '-',
                    ucfirst($p->status),
                    $p->created_at ? $p->created_at->format('Y-m-d H:i:s') : '-',
                    $p->updated_at ? $p->updated_at->format('Y-m-d H:i:s') : '-',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportReport(string $format, $eventId = null)
    {
        if (Auth::user()->role !== 'penyelenggara') {
            abort(403, 'Unauthorized access.');
        }

        if (!in_array($format, ['excel', 'word'], true)) {
            abort(404);
        }

        $event = null;
        $query = Participant::with(['user', 'event.creator']);

        if ($eventId !== null) {
            $event = Event::with('creator')->where('created_by', Auth::id())->findOrFail($eventId);
            $query->where('event_id', $event->id);
            $reportTitle = 'Laporan Peserta Acara';
            $slug = 'acara_' . $event->id;
        } else {
            $myEventIds = Event::where('created_by', Auth::id())->pluck('id');
            $query->whereIn('event_id', $myEventIds);
            $reportTitle = 'Rekapitulasi Seluruh Peserta';
            $slug = 'semua_acara';
        }

        $participants = $query->orderBy('created_at', 'DESC')->get();
        $exportedBy = Auth::user()->nama;

        $html = view('exports.participants-report', compact('participants', 'event', 'reportTitle', 'exportedBy', 'format'))->render();

        if ($format === 'excel') {
            $fileName = "laporan_peserta_{$slug}_" . date('Ymd_His') . '.xls';
            $contentType = 'application/vnd.ms-excel; charset=utf-8';
        } else {
            $fileName = "laporan_peserta_{$slug}_" . date('Ymd_His') . '.doc';
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
