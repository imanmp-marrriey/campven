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

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $rentang = $request->input('rentang');
        $lokasi_filter = $request->input('lokasi_filter');
        $sort = $request->input('sort', 'terbaru');
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
                $query->whereBetween('tanggal', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()]);
            } elseif ($rentang === 'bulan_ini') {
                $query->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'));
            } elseif ($rentang === 'semester') {
                $query->whereYear('tanggal', date('Y'));
            }
        }

        if ($sort === 'terdekat') {
            $query->orderBy('tanggal', 'ASC');
        } elseif ($sort === 'kuota') {
            $query->orderByRaw('COALESCE(kuota, 0) DESC');
        } else {
            $query->orderBy('tanggal', 'DESC');
        }

        $events = $query->paginate(6)->withQueryString();

        $featured_events = Event::with('creator')
            ->where('tanggal', '>=', date('Y-m-d'))
            ->orderBy('tanggal', 'ASC')
            ->limit(4)
            ->get();

        if ($featured_events->isEmpty()) {
            $featured_events = Event::with('creator')
                ->orderBy('tanggal', 'DESC')
                ->limit(4)
                ->get();
        }

        $categories = Category::all();
        $announcements = Announcement::orderBy('created_at', 'DESC')->limit(3)->get();
        $total_events = Event::count();
        $total_users = User::count();
        $total_organizers = User::where('role', 'penyelenggara')->count();
        $total_participants = Participant::whereIn('status', ['registered', 'confirmed', 'attended'])->count();

        return view('mahasiswa.index', compact(
            'events',
            'featured_events',
            'categories',
            'announcements',
            'total_events',
            'total_users',
            'total_organizers',
            'total_participants',
            'is_logged_in',
            'user',
            'search',
            'kategori',
            'rentang',
            'lokasi_filter',
            'sort'
        ));
    }

    public function myEvents()
    {
        $user = Auth::user();

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
            'all' => $my_events->count(),
            'registered' => $my_events->where('status', 'registered')->count(),
            'confirmed' => $my_events->where('status', 'confirmed')->count(),
            'attended' => $my_events->where('status', 'attended')->count(),
            'canceled' => $my_events->where('status', 'canceled')->count(),
        ];

        return view('mahasiswa.my-events', compact('my_events', 'status_counts'));
    }

    public function cancelEvent(Request $request, $id)
    {
        if (Auth::user()->role !== 'mahasiswa') {
            abort(403, 'Akses ditolak.');
        }

        $participant = Participant::where('event_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($participant && in_array($participant->status, ['registered', 'confirmed'])) {
            $participant->update(['status' => 'canceled']);
            return redirect()->route('my.events')->with('success', 'Berhasil membatalkan pendaftaran event!');
        }

        return redirect()->route('my.events')->with('error', 'Pendaftaran tidak dapat dibatalkan atau sudah diproses.');
    }

    public function certificate($id)
    {
        if (Auth::user()->role !== 'mahasiswa') {
            abort(403, 'Sertifikat hanya tersedia untuk akun mahasiswa.');
        }

        $participant = Participant::with(['event.creator', 'user'])
            ->where('event_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($participant->status !== 'attended') {
            abort(403, 'Sertifikat hanya dapat dicetak setelah kehadiran Anda terverifikasi.');
        }

        $event = $participant->event;
        $user = $participant->user;
        $cert_number = sprintf('CERT/CV/%d/%d/%s', $event->id, $participant->id, Carbon::parse($event->tanggal)->format('Y'));

        return view('mahasiswa.certificate', compact('participant', 'event', 'user', 'cert_number'));
    }
}
