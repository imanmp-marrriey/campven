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
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $rentang = $request->input('rentang');
        $lokasi_filter = $request->input('lokasi_filter');
        $status_filter = $request->input('status', 'mendatang');
        if (!in_array($status_filter, ['mendatang', 'semua', 'selesai'], true)) {
            $status_filter = 'mendatang';
        }
        $sort = $request->input('sort', $status_filter === 'mendatang' ? 'terdekat' : 'terbaru');
        $is_logged_in = Auth::check();
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

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
        } elseif ($status_filter === 'mendatang') {
            $query->where('tanggal', '>=', $today);
        } elseif ($status_filter === 'selesai') {
            $query->where('tanggal', '<', $today);
        }

        if ($sort === 'terdekat') {
            $query->orderBy('tanggal', 'ASC');
        } elseif ($sort === 'kuota') {
            $query->orderByRaw('COALESCE(kuota, 0) DESC');
        } else {
            $query->orderBy('tanggal', 'DESC');
        }

        $events = $query->get();

        $featured_events = Event::with('creator')
            ->where('tanggal', '>=', $today)
            ->orderBy('tanggal', 'ASC')
            ->limit(2)
            ->get();

        if ($featured_events->isEmpty()) {
            $featured_events = Event::with('creator')
                ->orderBy('tanggal', 'DESC')
                ->limit(2)
                ->get();
        }

        $hero_mosaic_events = Event::whereNotNull('poster')
            ->orderBy('tanggal', 'DESC')
            ->limit(6)
            ->get();

        $categories = Category::orderBy('nama', 'ASC')->get();
        $announcements = Announcement::orderBy('created_at', 'DESC')->limit(3)->get();
        $total_events = Event::count();
        $upcoming_events_count = Event::where('tanggal', '>=', $today)->count();
        $past_events_count = Event::where('tanggal', '<', $today)->count();
        $total_users = User::count();
        $total_organizers = User::where('role', 'penyelenggara')->count();
        $total_participants = Participant::whereIn('status', ['registered', 'confirmed', 'attended'])->count();

        return view('mahasiswa.index', compact(
            'events',
            'featured_events',
            'hero_mosaic_events',
            'categories',
            'announcements',
            'total_events',
            'upcoming_events_count',
            'past_events_count',
            'total_users',
            'total_organizers',
            'total_participants',
            'is_logged_in',
            'user',
            'search',
            'kategori',
            'rentang',
            'lokasi_filter',
            'status_filter',
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

    public function uploadPaymentProof(Request $request, $id)
    {
        if (Auth::user()->role !== 'mahasiswa') {
            abort(403, 'Akses ditolak.');
        }

        $participant = Participant::with('event')
            ->where('event_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$participant->event || ((int) ($participant->event->harga ?? 0)) <= 0) {
            return redirect()->route('my.events')->with('error', 'Acara gratis tidak memerlukan unggah bukti pembayaran.');
        }

        if ($participant->status !== 'registered') {
            return redirect()->route('my.events')->with('error', 'Bukti pembayaran hanya dapat diunggah saat status masih Menunggu Konfirmasi.');
        }

        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'bukti_bayar.required' => 'Pilih file gambar bukti pembayaran terlebih dahulu.',
            'bukti_bayar.image' => 'Bukti pembayaran harus berupa file gambar yang valid.',
            'bukti_bayar.mimes' => 'Format yang didukung: JPG, JPEG, PNG, atau WEBP.',
            'bukti_bayar.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        $oldProof = $participant->bukti_bayar;
        $file = $request->file('bukti_bayar');
        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $fileName = 'proof_' . $participant->event_id . '_' . $participant->id . '_' . time() . '.' . $ext;
        $path = $file->storeAs('payment_proofs', $fileName, 'public');

        $participant->update(['bukti_bayar' => $path]);

        if ($oldProof && $oldProof !== $path && Storage::disk('public')->exists($oldProof)) {
            Storage::disk('public')->delete($oldProof);
        }

        return redirect()->route('my.events')->with('success', 'Bukti pembayaran berhasil diunggah! Panitia akan segera memverifikasi pendaftaran Anda.');
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
