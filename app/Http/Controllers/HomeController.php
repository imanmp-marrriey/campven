<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $is_logged_in = Auth::check();
        $user = Auth::user();

        // Search
        if ($search) {
            $events = Event::with('creator')
                ->where('nama_event', 'LIKE', "%{$search}%")
                ->orWhere('kategori', 'LIKE', "%{$search}%")
                ->orWhere('lokasi', 'LIKE', "%{$search}%")
                ->orWhere('deskripsi', 'LIKE', "%{$search}%")
                ->orderBy('tanggal', 'DESC')
                ->get();
        } else {
            $events = Event::with('creator')
                ->orderBy('tanggal', 'DESC')
                ->limit(9)
                ->get();

            if ($kategori) {
                $events = Event::with('creator')
                    ->where('kategori', $kategori)
                    ->orderBy('tanggal', 'DESC')
                    ->get();
            }
        }

        $featured_events = Event::with('creator')
            ->where('tanggal', '>=', date('Y-m-d'))
            ->orderBy('tanggal', 'ASC')
            ->limit(6)
            ->get();

        $categories = Category::all();
        $announcements = Announcement::orderBy('created_at', 'DESC')->limit(3)->get();
        $total_events = Event::count();
        $total_users = \App\Models\User::count();
        $total_participants = Participant::count();

        return view('mahasiswa.index', compact(
            'events',
            'featured_events',
            'categories',
            'announcements',
            'total_events',
            'total_users',
            'total_participants',
            'is_logged_in',
            'user',
            'search',
            'kategori'
        ));
    }

    public function myEvents()
    {
        $user = Auth::user();
        
        // Jika user bukan mahasiswa, redirect ke dashboard sesuai role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'penyelenggara') {
            return redirect()->route('organizer.dashboard');
        }

        $my_events = Participant::with('event.creator')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $status_counts = [
            'registered' => Participant::where('user_id', $user->id)->where('status', 'registered')->count(),
            'confirmed' => Participant::where('user_id', $user->id)->where('status', 'confirmed')->count(),
            'attended' => Participant::where('user_id', $user->id)->where('status', 'attended')->count(),
        ];

        return view('mahasiswa.my-events', compact('my_events', 'status_counts'));
    }

    public function cancelEvent($id)
    {
        $participant = Participant::where('event_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($participant && $participant->status === 'registered') {
            $participant->delete();
            return redirect()->route('my.events')->with('success', 'Berhasil membatalkan pendaftaran!');
        }

        return redirect()->route('my.events')->with('error', 'Gagal membatalkan pendaftaran!');
    }
}