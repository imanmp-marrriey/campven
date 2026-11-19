<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function detail($id)
    {
        $event = Event::with('creator')->findOrFail($id);
        $participants = Participant::with('user')
            ->where('event_id', $id)
            ->whereIn('status', ['registered', 'confirmed', 'attended'])
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();

        $related_events = Event::with('creator')
            ->where('kategori', $event->kategori)
            ->where('id', '!=', $id)
            ->orderBy('tanggal', 'DESC')
            ->limit(3)
            ->get();

        $is_logged_in = Auth::check();
        $user = Auth::user();
        $is_joined = false;
        $participant_status = null;
        $isPast = Carbon::parse($event->tanggal)->lt(Carbon::today());

        if ($is_logged_in) {
            $user_participant = Participant::where('event_id', $id)
                ->where('user_id', $user->id)
                ->first();
            if ($user_participant && in_array($user_participant->status, ['registered', 'confirmed', 'attended'])) {
                $is_joined = true;
                $participant_status = $user_participant->status;
            }
        }

        return view('events.detail', compact(
            'event',
            'participants',
            'related_events',
            'is_logged_in',
            'user',
            'is_joined',
            'participant_status',
            'isPast'
        ));
    }

    public function register(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('event.detail', $id)
                ->with('error', 'Hanya akun mahasiswa yang dapat mendaftar sebagai peserta acara.');
        }

        $event = Event::findOrFail($id);

        if (Carbon::parse($event->tanggal)->lt(Carbon::today())) {
            return redirect()->route('event.detail', $id)
                ->with('error', 'Maaf, pendaftaran sudah ditutup karena acara telah selesai.');
        }

        if ($event->isFull()) {
            return redirect()->route('event.detail', $id)
                ->with('error', 'Maaf, kuota event ini sudah penuh!');
        }

        $existing = Participant::where('event_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            if (in_array($existing->status, ['registered', 'confirmed', 'attended'])) {
                return redirect()->route('event.detail', $id)
                    ->with('error', 'Anda sudah terdaftar di event ini!');
            }
            $existing->update(['status' => 'registered']);
            return redirect()->route('event.detail', $id)
                ->with('success', 'Pendaftaran Anda berhasil diperbarui! Menunggu konfirmasi panitia.');
        }

        Participant::create([
            'event_id' => $id,
            'user_id' => Auth::id(),
            'status' => 'registered',
        ]);

        return redirect()->route('event.detail', $id)
            ->with('success', 'Berhasil mendaftar ke event! Menunggu konfirmasi panitia.');
    }
}
