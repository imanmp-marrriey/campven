<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function detail($id)
    {
        $event = Event::with('creator')->findOrFail($id);
        $participants = Participant::with('user')
            ->where('event_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();

        $related_events = Event::where('kategori', $event->kategori)
            ->where('id', '!=', $id)
            ->orderBy('tanggal', 'DESC')
            ->limit(3)
            ->get();

        $is_logged_in = Auth::check();
        $user = Auth::user();
        $is_joined = false;
        $participant_status = null;

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
            'participant_status'
        ));
    }

    public function register(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $event = Event::findOrFail($id);

        // Cek kuota penuh
        if ($event->isFull()) {
            return redirect()->route('event.detail', $id)->with('error', 'Maaf, kuota event ini sudah penuh!');
        }

        // Cek status pendaftaran sebelumnya
        $existing = Participant::where('event_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            if (in_array($existing->status, ['registered', 'confirmed', 'attended'])) {
                return redirect()->route('event.detail', $id)->with('error', 'Anda sudah terdaftar di event ini!');
            }
            // Jika sebelumnya pernah membatalkan, perbarui status menjadi registered
            $existing->update(['status' => 'registered']);
            return redirect()->route('event.detail', $id)->with('success', 'Pendaftaran Anda berhasil diperbarui! Menunggu konfirmasi panitia.');
        }

        Participant::create([
            'event_id' => $id,
            'user_id' => Auth::id(),
            'status' => 'registered',
        ]);

        return redirect()->route('event.detail', $id)->with('success', 'Berhasil mendaftar ke event! Menunggu konfirmasi panitia.');
    }
}