@extends('layouts.app')

@section('title', 'My Events - Campven')

@section('content')
<!-- ===== NAVBAR ===== -->
<nav style="background:rgba(255,255,255,0.92);backdrop-filter:blur(12px);padding:0 2rem;height:64px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #E5E7EB;position:sticky;top:0;z-index:100;">
    <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
        <div style="width:36px;height:36px;background:linear-gradient(135deg,#6C63FF,#8B83FF);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;font-weight:800;">C</div>
        <div><span style="font-size:20px;font-weight:800;color:#1F2937;">Camp<span style="color:#6C63FF;">ven</span></span></div>
    </a>
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="display:flex;align-items:center;gap:10px;font-size:0.85rem;color:#6B7280;">
            <span style="font-weight:500;">{{ Auth::user()->nama }}</span>
            <span style="padding:0.15rem 0.6rem;border-radius:50px;font-size:0.65rem;font-weight:600;background:#DBEAFE;color:#1E40AF;">Mahasiswa</span>
        </div>
        <a href="{{ route('profile') }}" style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6C63FF,#8B83FF);color:white;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:14px;text-decoration:none;transition:all 0.2s;">
            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
        </a>
        <a href="{{ route('home') }}" class="btn btn-outline btn-sm" style="padding:0.2rem 0.6rem;font-size:0.7rem;border:1.5px solid #D1D5DB;border-radius:50px;text-decoration:none;color:#6B7280;background:transparent;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="padding:0.4rem 1rem;border-radius:50px;border:1px solid #E5E7EB;background:transparent;color:#6B7280;font-weight:500;cursor:pointer;font-family:'Inter',sans-serif;font-size:0.8rem;">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</nav>

<div class="container" style="padding:1.5rem 2rem;max-width:1280px;margin:0 auto;">

    <!-- ===== HERO ===== -->
    <div style="background:linear-gradient(135deg,#6C63FF,#4A42B8);border-radius:16px;padding:2rem 2.5rem;color:white;margin-bottom:2rem;">
        <h1 style="font-size:1.8rem;font-weight:800;"><i class="fas fa-calendar-check" style="margin-right:10px;"></i> My <span style="color:#FFE66D;">Events</span></h1>
        <div style="display:flex;gap:2.5rem;margin-top:1rem;flex-wrap:wrap;">
            <div><div style="font-size:1.3rem;font-weight:700;">{{ count($my_events) }}</div><div style="font-size:0.8rem;opacity:0.75;">Total Diikuti</div></div>
            <div><div style="font-size:1.3rem;font-weight:700;">{{ $status_counts['registered'] ?? 0 }}</div><div style="font-size:0.8rem;opacity:0.75;">Terdaftar</div></div>
            <div><div style="font-size:1.3rem;font-weight:700;">{{ $status_counts['confirmed'] ?? 0 }}</div><div style="font-size:0.8rem;opacity:0.75;">Dikonfirmasi</div></div>
            <div><div style="font-size:1.3rem;font-weight:700;">{{ $status_counts['attended'] ?? 0 }}</div><div style="font-size:0.8rem;opacity:0.75;">Sudah Hadir</div></div>
        </div>
    </div>

    @if(session('success'))
    <div class="notification success" style="padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;display:flex;align-items:center;gap:10px;font-weight:500;font-size:0.9rem;background:#D1FAE5;color:#065F46;border-left:4px solid #10B981;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button onclick="this.parentElement.remove()" style="margin-left:auto;cursor:pointer;background:none;border:none;font-size:16px;opacity:0.5;">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="notification error" style="padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;display:flex;align-items:center;gap:10px;font-weight:500;font-size:0.9rem;background:#FEE2E2;color:#991B1B;border-left:4px solid #EF4444;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button onclick="this.parentElement.remove()" style="margin-left:auto;cursor:pointer;background:none;border:none;font-size:16px;opacity:0.5;">&times;</button>
    </div>
    @endif

    <!-- ===== FILTER ===== -->
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:1.5rem;">
        <a href="{{ route('my.events') }}" style="padding:0.4rem 1rem;border-radius:50px;border:1.5px solid #E5E7EB;background:#FFFFFF;color:#6B7280;font-weight:500;font-size:0.75rem;text-decoration:none;display:inline-flex;align-items:center;gap:6px;{{ !request('status') ? 'background:#6C63FF;color:white;border-color:#6C63FF;' : '' }}">
            <i class="fas fa-list"></i> Semua
            <span style="background:{{ !request('status') ? 'rgba(255,255,255,0.2)' : '#F3F4F6' }};padding:0.05rem 0.4rem;border-radius:50px;font-size:0.6rem;margin-left:4px;">{{ count($my_events) }}</span>
        </a>
        <a href="{{ route('my.events', ['status' => 'registered']) }}" style="padding:0.4rem 1rem;border-radius:50px;border:1.5px solid #E5E7EB;background:#FFFFFF;color:#6B7280;font-weight:500;font-size:0.75rem;text-decoration:none;display:inline-flex;align-items:center;gap:6px;{{ request('status') == 'registered' ? 'background:#6C63FF;color:white;border-color:#6C63FF;' : '' }}">
            <i class="fas fa-clock"></i> Terdaftar
            <span style="background:{{ request('status') == 'registered' ? 'rgba(255,255,255,0.2)' : '#F3F4F6' }};padding:0.05rem 0.4rem;border-radius:50px;font-size:0.6rem;margin-left:4px;">{{ $status_counts['registered'] ?? 0 }}</span>
        </a>
        <a href="{{ route('my.events', ['status' => 'confirmed']) }}" style="padding:0.4rem 1rem;border-radius:50px;border:1.5px solid #E5E7EB;background:#FFFFFF;color:#6B7280;font-weight:500;font-size:0.75rem;text-decoration:none;display:inline-flex;align-items:center;gap:6px;{{ request('status') == 'confirmed' ? 'background:#6C63FF;color:white;border-color:#6C63FF;' : '' }}">
            <i class="fas fa-check-circle"></i> Dikonfirmasi
            <span style="background:{{ request('status') == 'confirmed' ? 'rgba(255,255,255,0.2)' : '#F3F4F6' }};padding:0.05rem 0.4rem;border-radius:50px;font-size:0.6rem;margin-left:4px;">{{ $status_counts['confirmed'] ?? 0 }}</span>
        </a>
        <a href="{{ route('my.events', ['status' => 'attended']) }}" style="padding:0.4rem 1rem;border-radius:50px;border:1.5px solid #E5E7EB;background:#FFFFFF;color:#6B7280;font-weight:500;font-size:0.75rem;text-decoration:none;display:inline-flex;align-items:center;gap:6px;{{ request('status') == 'attended' ? 'background:#6C63FF;color:white;border-color:#6C63FF;' : '' }}">
            <i class="fas fa-check-double"></i> Sudah Hadir
            <span style="background:{{ request('status') == 'attended' ? 'rgba(255,255,255,0.2)' : '#F3F4F6' }};padding:0.05rem 0.4rem;border-radius:50px;font-size:0.6rem;margin-left:4px;">{{ $status_counts['attended'] ?? 0 }}</span>
        </a>
    </div>

    <!-- ===== EVENT GRID ===== -->
    @if(count($my_events) > 0)
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1.2rem;">
        @foreach($my_events as $participant)
        @php $event = $participant->event; @endphp
        <div style="background:#FFFFFF;border-radius:16px;overflow:hidden;border:1px solid #E5E7EB;transition:all 0.2s;">
            <div style="padding:1rem 1.2rem;background:linear-gradient(135deg,#6C63FF,#8B83FF);color:white;">
                <span style="display:inline-block;background:rgba(255,255,255,0.2);padding:0.1rem 0.6rem;border-radius:50px;font-size:0.6rem;font-weight:600;text-transform:uppercase;letter-spacing:0.3px;">{{ $event->kategori }}</span>
                <h3 style="font-size:1rem;font-weight:700;margin-top:0.3rem;">{{ $event->nama_event }}</h3>
            </div>
            <div style="padding:1rem 1.2rem;">
                <div style="display:flex;flex-direction:column;gap:4px;margin-bottom:0.5rem;">
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.8rem;color:#6B7280;"><i class="fas fa-calendar-day" style="width:16px;font-size:0.75rem;color:#6C63FF;"></i> {{ date('d M Y', strtotime($event->tanggal)) }}</div>
                    @if($event->waktu_mulai)
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.8rem;color:#6B7280;"><i class="fas fa-clock" style="width:16px;font-size:0.75rem;color:#6C63FF;"></i> {{ date('H:i', strtotime($event->waktu_mulai)) }} - {{ date('H:i', strtotime($event->waktu_selesai)) }}</div>
                    @endif
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.8rem;color:#6B7280;"><i class="fas fa-map-marker-alt" style="width:16px;font-size:0.75rem;color:#6C63FF;"></i> {{ $event->lokasi }}</div>
                    @if($event->creator)
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.8rem;color:#6B7280;"><i class="fas fa-user-tie" style="width:16px;font-size:0.75rem;color:#6C63FF;"></i> {{ $event->creator->nama }}</div>
                    @endif
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.8rem;color:#6B7280;"><i class="fas fa-clock" style="width:16px;font-size:0.75rem;color:#6C63FF;"></i> Daftar: {{ date('d M Y H:i', strtotime($participant->created_at)) }}</div>
                </div>
                <div style="font-size:0.85rem;color:#6B7280;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $event->deskripsi }}</div>
            </div>
            <div style="padding:0.8rem 1.2rem;border-top:1px solid #E5E7EB;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:6px;">
                <span style="font-size:0.75rem;color:#6B7280;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-circle" style="color:{{ $participant->status == 'registered' ? '#F59E0B' : ($participant->status == 'confirmed' ? '#10B981' : '#3B82F6') }};font-size:8px;"></i>
                    <span style="display:inline-block;padding:0.1rem 0.6rem;border-radius:50px;font-size:0.65rem;font-weight:600;{{ $participant->status == 'registered' ? 'background:#FEF3C7;color:#92400E;' : ($participant->status == 'confirmed' ? 'background:#D1FAE5;color:#065F46;' : 'background:#DBEAFE;color:#1E40AF;') }}">{{ ucfirst($participant->status) }}</span>
                </span>
                @if($participant->status === 'registered')
                <a href="{{ route('my.events.cancel', $event->id) }}" onclick="return confirm('Yakin ingin membatalkan pendaftaran event ini?')" style="padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:600;background:#EF4444;color:white;text-decoration:none;border:none;cursor:pointer;">
                    <i class="fas fa-times"></i> Batalkan
                </a>
                @elseif($participant->status === 'confirmed')
                <span style="padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:600;background:#10B981;color:white;opacity:0.8;cursor:default;">
                    <i class="fas fa-check"></i> Terkonfirmasi
                </span>
                @else
                <span style="padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:600;background:#3B82F6;color:white;opacity:0.8;cursor:default;">
                    <i class="fas fa-check-double"></i> Selesai
                </span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align:center;padding:3rem 2rem;background:#FFFFFF;border-radius:16px;border:1px solid #E5E7EB;">
        <i class="fas fa-calendar-times" style="font-size:2.5rem;color:#D1D5DB;margin-bottom:0.5rem;"></i>
        <h3 style="font-size:1rem;color:#6B7280;">Belum mengikuti event apapun</h3>
        <p style="color:#9CA3AF;font-size:0.85rem;">Yuk ikuti event kampus menarik di halaman utama!</p>
        <br>
        <a href="{{ route('home') }}" class="btn btn-primary btn-sm" style="padding:0.3rem 0.8rem;font-size:0.75rem;background:#6C63FF;color:white;border-radius:50px;text-decoration:none;"><i class="fas fa-calendar-alt"></i> Cari Event</a>
    </div>
    @endif
</div>

<script>
    setTimeout(function() {
        const notif = document.querySelector('.notification');
        if (notif) {
            notif.style.transition = 'opacity 0.5s';
            notif.style.opacity = '0';
            setTimeout(() => notif.remove(), 500);
        }
    }, 5000);
</script>
@endsection