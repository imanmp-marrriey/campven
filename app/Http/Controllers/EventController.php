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

        if ($is_logged_in) {
            $is_joined = Participant::where('event_id', $id)
                ->where('user_id', $user->id)
                ->exists();
        }

        return view('events.detail', compact(
            'event',
            'participants',
            'related_events',
            'is_logged_in',
            'user',
            'is_joined'
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

        // Cek sudah terdaftar
        $exists = Participant::where('event_id', $id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            return redirect()->route('event.detail', $id)->with('error', 'Anda sudah terdaftar!');
        }

        Participant::create([
            'event_id' => $id,
            'user_id' => Auth::id(),
            'status' => 'confirmed',
        ]);

        return redirect()->route('event.detail', $id)->with('success', 'Berhasil mendaftar ke event! Status Anda sudah terkonfirmasi.');
    }
}