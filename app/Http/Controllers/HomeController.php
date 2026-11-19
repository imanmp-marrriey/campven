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
        $rentang = $request->input('rentang');
        $lokasi_filter = $request->input('lokasi_filter');
        $is_logged_in = Auth::check();
        $user = Auth::user();

        $query = Event::with('creator');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_event', 'LIKE', "%{$search}%")
                  ->orWhere('kategori', 'LIKE', "%{$search}%")
                  ->orWhere('lokasi', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($kategori)) {
            $query->where('kategori', $kategori);
        }

        if (!empty($lokasi_filter)) {
            $query->where('lokasi', 'LIKE', "%{$lokasi_filter}%");
        }

        if (!empty($rentang)) {
            if ($rentang === 'minggu_ini') {
                $query->whereBetween('tanggal', [\Carbon\Carbon::now()->startOfWeek()->toDateString(), \Carbon\Carbon::now()->endOfWeek()->toDateString()]);
            } elseif ($rentang === 'bulan_ini') {
                $query->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'));
            } elseif ($rentang === 'semester') {
                $query->whereYear('tanggal', date('Y'));
            }
        }

        $events = $query->orderBy('tanggal', 'DESC')->get();

        $featured_events = Event::with('creator')
            ->where('tanggal', '>=', date('Y-m-d'))
            ->orderBy('tanggal', 'ASC')
            ->limit(6)
            ->get();

        if ($featured_events->isEmpty()) {
            $featured_events = Event::with('creator')
                ->orderBy('tanggal', 'DESC')
                ->limit(6)
                ->get();
        }

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
            'kategori',
            'rentang',
            'lokasi_filter'
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
            'all' => Participant::where('user_id', $user->id)->count(),
            'registered' => Participant::where('user_id', $user->id)->where('status', 'registered')->count(),
            'confirmed' => Participant::where('user_id', $user->id)->where('status', 'confirmed')->count(),
            'attended' => Participant::where('user_id', $user->id)->where('status', 'attended')->count(),
            'canceled' => Participant::where('user_id', $user->id)->where('status', 'canceled')->count(),
        ];

        return view('mahasiswa.my-events', compact('my_events', 'status_counts'));
    }

    public function cancelEvent(Request $request, $id)
    {
        $participant = Participant::where('event_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($participant && in_array($participant->status, ['registered', 'confirmed'])) {
            $participant->update(['status' => 'canceled']);
            return redirect()->route('my.events')->with('success', 'Berhasil membatalkan pendaftaran event!');
        }

        return redirect()->route('my.events')->with('error', 'Pendaftaran tidak dapat dibatalkan atau sudah diproses.');
    }
}