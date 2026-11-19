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
            ->with('participants.user')
            ->orderBy('tanggal', 'DESC')
            ->get();

        $total_events = $events->count();
        $total_participants = $events->sum(function ($event) {
            return $event->participants()->count();
        });

        $categories = Category::all();

        return view('organizer.dashboard', compact(
            'events',
            'total_events',
            'total_participants',
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
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kuota' => 'nullable|integer|min:1',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'waktu_selesai' => 'nullable|date_format:H:i',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kuota' => 'nullable|integer|min:1',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
}
