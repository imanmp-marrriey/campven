@extends('layouts.app')

@section('title', $event->nama_event . ' - Campven')

@section('content')
<style>
    .poster-hero {
        margin-bottom: 1.5rem;
        border-radius: 16px;
        overflow: hidden;
        max-height: 400px;
        background: #E5E7EB;
        position: relative;
    }
    .poster-hero img {
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: cover;
        /* object-fit: contain; */
    }
    .poster-hero .poster-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #334155, #475569);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        gap: 0.5rem;
    }
    .poster-hero .poster-placeholder span {
        font-size: 1rem;
        opacity: 0.8;
    }
    .event-detail {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
    .event-main {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid #E5E7EB;
    }
    .event-main .event-header h1 { font-size: 1.8rem; font-weight: 800; }
    .event-main .event-header .category-badge {
        display: inline-block;
        background: #334155;
        color: white;
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.3rem;
    }
    .event-main .event-meta { display: flex; flex-direction: column; gap: 8px; margin: 1rem 0; }
    .event-main .event-meta .meta-item { display: flex; align-items: center; gap: 10px; color: #6B7280; font-size: 0.95rem; }
    .event-main .event-meta .meta-item i { width: 20px; color: #334155; }
    .event-main .event-description { margin: 1.5rem 0; line-height: 1.8; color: #6B7280; }
    .event-main .event-description h3 { color: #1F2937; margin-bottom: 0.3rem; }

    .event-sidebar { display: flex; flex-direction: column; gap: 1rem; }
    .sidebar-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #E5E7EB;
    }
    .sidebar-card h4 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sidebar-card h4 i { color: #334155; }

    .stat-list { display: flex; flex-direction: column; gap: 0; }
    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    .stat-item:last-child { border-bottom: none; }
    .stat-item .stat-label { color: #6B7280; font-size: 0.85rem; font-weight: 500; }
    .stat-item .stat-value { color: #1F2937; font-size: 0.85rem; font-weight: 600; text-align: right; max-width: 55%; word-break: break-word; }
    .stat-item .stat-value .highlight { color: #334155; }

    .registration-form { margin-top: 1rem; }
    .registration-form .form-group { margin-bottom: 0.8rem; }
    .registration-form .form-group label { display: block; font-weight: 600; font-size: 0.8rem; color: #374151; margin-bottom: 0.2rem; }
    .registration-form .form-group input, .registration-form .form-group textarea {
        width: 100%;
        padding: 0.6rem 0.8rem;
        border: 1.5px solid #E5E7EB;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .registration-form .form-group input:focus, .registration-form .form-group textarea:focus {
        outline: none;
        border-color: #334155;
        box-shadow: 0 0 0 3px rgba(51,65,85,0.1);
    }
    .registration-form .form-group textarea { min-height: 60px; resize: vertical; }

    .participant-list .participant-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.4rem 0;
        border-bottom: 1px solid #F3F4F6;
        font-size: 0.85rem;
    }
    .participant-list .participant-item .avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #334155;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
    }

    .related-events { margin-top: 2rem; }
    .related-events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 0.5rem;
    }
    .related-event-card {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        border: 1px solid #E5E7EB;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s;
    }
    .related-event-card:hover { border-color: #334155; transform: translateY(-2px); }
    .related-event-card h5 { font-size: 0.9rem; font-weight: 600; }
    .related-event-card p { font-size: 0.8rem; color: #6B7280; }

    .joined-badge {
        display: inline-block;
        background: #10B981;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .event-detail { grid-template-columns: 1fr; }
        .container { padding: 1rem; }
        .navbar { padding: 0 1rem; height: 56px; }
        .event-main { padding: 1.2rem; }
        .stat-item .stat-value { max-width: 45%; font-size: 0.8rem; }
        .poster-hero { max-height: 200px; }
        .poster-hero img { max-height: 200px; }
    }
    @media (max-width: 480px) {
        .stat-item { flex-direction: row; flex-wrap: wrap; gap: 2px; }
        .stat-item .stat-value { max-width: 50%; }
    }
</style>

<!-- ===== NAVBAR ===== -->
<nav style="background:rgba(255,255,255,0.92);backdrop-filter:blur(12px);padding:0 2rem;height:64px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #E5E7EB;position:sticky;top:0;z-index:100;">
    <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
        <div style="width:36px;height:36px;background:linear-gradient(135deg,#334155,#475569);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;font-weight:800;">C</div>
        <div><span style="font-size:20px;font-weight:800;color:#1F2937;">Camp<span style="color:#334155;">ven</span></span></div>
    </a>
    <div style="display:flex;align-items:center;gap:12px;">
        @if(Auth::check())
            <a href="{{ route('profile') }}" style="width:36px;height:36px;border-radius:50%;overflow:hidden;text-decoration:none;transition:all 0.2s;display:block;">
                @if(Auth::user()->foto && Storage::disk('public')->exists(Auth::user()->foto))
                    <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto Profil" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                @else
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#334155,#475569);color:white;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:14px;">{{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}</div>
                @endif
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm" style="padding:0.3rem 0.8rem;border-radius:50px;border:1.5px solid #D1D5DB;background:transparent;color:#6B7280;font-weight:600;font-size:0.75rem;cursor:pointer;font-family:'Inter',sans-serif;">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
        @endif
    </div>
</nav>

<div class="container" style="padding:1.5rem 2rem;max-width:1200px;margin:0 auto;">
    <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:6px;color:#6B7280;text-decoration:none;font-size:0.9rem;margin-bottom:1rem;">
        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
    </a>

    <!-- ===== POSTER HERO ===== -->
    <div class="poster-hero">
        @if($event->poster && file_exists(public_path('storage/' . $event->poster)))
            <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->nama_event }}">
        @else
            <div class="poster-placeholder">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ $event->kategori }}</span>
            </div>
        @endif
    </div>

    <div class="event-detail">
        <!-- ===== MAIN ===== -->
        <div class="event-main">
            <div class="event-header">
                <span class="category-badge">{{ $event->kategori }}</span>
                <h1>{{ $event->nama_event }}</h1>
            </div>

            <div class="event-meta">
                <div class="meta-item"><i class="fas fa-calendar-day"></i> {{ date('l, d M Y', strtotime($event->tanggal)) }}</div>
                @if($event->waktu_mulai)
                <div class="meta-item"><i class="fas fa-clock"></i> {{ date('H:i', strtotime($event->waktu_mulai)) }} - {{ date('H:i', strtotime($event->waktu_selesai)) }} WIB</div>
                @endif
                <div class="meta-item"><i class="fas fa-map-marker-alt"></i> {{ $event->lokasi }}</div>
                <div class="meta-item"><i class="fas fa-user-tie"></i> Penyelenggara: {{ $event->creator->nama ?? 'Tidak diketahui' }}</div>
                <div class="meta-item"><i class="fas fa-users"></i> {{ $event->participantCount() }} peserta terdaftar</div>
            </div>

            <div class="event-description">
                <h3>Tentang Event</h3>
                <p>{{ nl2br($event->deskripsi) }}</p>
            </div>

            @if(Auth::check() && $is_joined)
            <div style="margin-top:1rem;padding:1rem;background:#D1FAE5;border-radius:10px;text-align:center;">
                <i class="fas fa-check-circle" style="color:#10B981;font-size:1.2rem;"></i>
                <span style="font-weight:600;color:#065F46;">Anda sudah terdaftar di event ini!</span>
            </div>
            @endif
        </div>

        <!-- ===== SIDEBAR ===== -->
        <div class="event-sidebar">

            <!-- Registration Card -->
            <div class="sidebar-card">
                <h4><i class="fas fa-user-plus"></i> Daftar Sekarang</h4>

                @if(session('success'))
                <div style="padding:0.8rem 1rem;border-radius:10px;margin-bottom:1rem;display:flex;align-items:center;gap:10px;font-weight:500;font-size:0.9rem;background:#D1FAE5;color:#065F46;border-left:4px solid #10B981;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div style="padding:0.8rem 1rem;border-radius:10px;margin-bottom:1rem;display:flex;align-items:center;gap:10px;font-weight:500;font-size:0.9rem;background:#FEE2E2;color:#991B1B;border-left:4px solid #EF4444;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
                @endif

                @if($event->kuota)
                <div style="padding:0.6rem 1rem;border-radius:10px;margin-bottom:1rem;background:{{ $event->isFull() ? '#FEE2E2' : '#EFF6FF' }};color:{{ $event->isFull() ? '#991B1B' : '#1E40AF' }};font-size:0.85rem;font-weight:500;display:flex;align-items:center;gap:8px;">
                    <i class="fas {{ $event->isFull() ? 'fa-exclamation-circle' : 'fa-users' }}"></i>
                    {{ $event->isFull() ? 'Kuota sudah penuh!' : 'Sisa kuota: ' . $event->sisaKuota() . ' dari ' . $event->kuota }}
                </div>
                @endif

                @if(Auth::check() && !$is_joined && !$event->isFull())
                <form method="POST" action="{{ route('event.register', $event->id) }}" class="registration-form">
                    @csrf
                    <div class="form-group">
                        <label>Nama Lengkap <span style="color:#EF4444;">*</span></label>
                        <input type="text" value="{{ Auth::user()->nama }}" readonly style="background:#F9FAFB;color:#6B7280;cursor:not-allowed;">
                    </div>
                    <div class="form-group">
                        <label>Email <span style="color:#EF4444;">*</span></label>
                        <input type="email" value="{{ Auth::user()->email }}" readonly style="background:#F9FAFB;color:#6B7280;cursor:not-allowed;">
                    </div>
                    <button type="submit" name="register" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.6rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#334155;color:white;">
                        <i class="fas fa-check"></i> Daftar Event
                    </button>
                </form>
                @elseif(Auth::check() && $is_joined)
                <div style="text-align:center;padding:0.5rem 0;">
                    <span class="joined-badge"><i class="fas fa-check-circle"></i> Terdaftar</span>
                    <p style="font-size:0.8rem;color:#6B7280;margin-top:0.5rem;">Anda sudah terdaftar di event ini</p>
                </div>
                @elseif(Auth::check() && $event->isFull())
                <div style="text-align:center;padding:0.5rem 0;">
                    <span style="display:inline-block;background:#EF4444;color:white;padding:0.5rem 1.5rem;border-radius:50px;font-weight:600;font-size:0.9rem;">
                        <i class="fas fa-times-circle"></i> Kuota Penuh
                    </span>
                    <p style="font-size:0.8rem;color:#6B7280;margin-top:0.5rem;">Maaf, event ini sudah mencapai batas peserta</p>
                </div>
                @else
                <div style="text-align:center;padding:0.5rem 0;">
                    <p style="color:#6B7280;margin-bottom:1rem;">Silakan login terlebih dahulu untuk mendaftar</p>
                    <a href="{{ route('login') }}" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.6rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#334155;color:white;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </div>
                @endif
            </div>

            <!-- ===== STATISTIK CARD ===== -->
            <div class="sidebar-card">
                <h4><i class="fas fa-chart-simple"></i> Statistik Event</h4>
                <div class="stat-list">
                    <div class="stat-item">
                        <span class="stat-label">Total Peserta</span>
                        <span class="stat-value"><span class="highlight">{{ $event->participantCount() }}</span> orang{{ $event->kuota ? ' / ' . $event->kuota : '' }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Kategori</span>
                        <span class="stat-value">{{ $event->kategori }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Tanggal</span>
                        <span class="stat-value">{{ date('d M Y', strtotime($event->tanggal)) }}</span>
                    </div>
                    @if($event->waktu_mulai)
                    <div class="stat-item">
                        <span class="stat-label">Waktu</span>
                        <span class="stat-value">{{ date('H:i', strtotime($event->waktu_mulai)) }} - {{ date('H:i', strtotime($event->waktu_selesai)) }}</span>
                    </div>
                    @endif
                    <div class="stat-item">
                        <span class="stat-label">Lokasi</span>
                        <span class="stat-value">{{ $event->lokasi }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Penyelenggara</span>
                        <span class="stat-value">{{ $event->creator->nama ?? 'Tidak diketahui' }}</span>
                    </div>
                    <div class="stat-item" style="border-bottom: none; padding-bottom: 0;">
                        <span class="stat-label">Status Event</span>
                        <span class="stat-value">
                            @if(strtotime($event->tanggal) > time())
                                <span style="color:#10B981;">Akan Datang</span>
                            @elseif(strtotime($event->tanggal) == date('Y-m-d'))
                                <span style="color:#F59E0B;">Hari Ini</span>
                            @else
                                <span style="color:#6B7280;">Selesai</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- ===== PARTICIPANTS CARD ===== -->
            @if(count($participants) > 0)
            <div class="sidebar-card">
                <h4><i class="fas fa-users"></i> Peserta Terbaru</h4>
                <div class="participant-list">
                    @foreach($participants as $p)
                    <div class="participant-item">
                        <div class="avatar">{{ strtoupper(substr($p->user->nama, 0, 1)) }}</div>
                        <div>
                            <div style="font-weight:600;font-size:0.85rem;">{{ $p->user->nama }}</div>
                            <div style="font-size:0.7rem;color:#9CA3AF;">{{ $p->user->no_telp ?? '' }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- ===== RELATED EVENTS ===== -->
    @if(count($related_events) > 0)
    <div class="related-events">
        <h4 style="font-size:1.1rem;font-weight:700;"><i class="fas fa-link" style="color:#334155;"></i> Event Serupa</h4>
        <div class="related-events-grid">
            @foreach($related_events as $re)
            <a href="{{ route('event.detail', $re->id) }}" class="related-event-card">
                <h5>{{ $re->nama_event }}</h5>
                <p><i class="fas fa-calendar-day"></i> {{ date('d M Y', strtotime($re->tanggal)) }}</p>
                <p style="font-size:0.7rem;color:#9CA3AF;"><i class="fas fa-map-marker-alt"></i> {{ $re->lokasi }}</p>
            </a>
            @endforeach
        </div>
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