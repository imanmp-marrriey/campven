@extends('layouts.app')

@section('title', 'Campven - Your Campus, Organized')

@section('content')
<!-- ===== NAVBAR ===== -->
<nav style="background:rgba(255,255,255,0.92);backdrop-filter:blur(12px);padding:0 2rem;height:72px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #E5E7EB;position:sticky;top:0;z-index:100;">
    <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#6C63FF,#8B83FF);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;font-weight:800;">C</div>
        <div><span style="font-size:22px;font-weight:800;color:#1A1A2E;">Camp<span style="color:#6C63FF;">ven</span></span></div>
    </a>
    <ul style="display:flex;align-items:center;gap:2rem;list-style:none;">
        <li><a href="#events" style="text-decoration:none;color:#6B7280;font-weight:500;font-size:0.9rem;">Event</a></li>
        <li><a href="#categories" style="text-decoration:none;color:#6B7280;font-weight:500;font-size:0.9rem;">Kategori</a></li>
        <li><a href="#about" style="text-decoration:none;color:#6B7280;font-weight:500;font-size:0.9rem;">Tentang</a></li>
        @if($is_logged_in && $user->role === 'admin')
        <li><a href="{{ route('admin.dashboard') }}" style="text-decoration:none;color:#6B7280;font-weight:500;font-size:0.9rem;">Dashboard</a></li>
        @elseif($is_logged_in && $user->role === 'penyelenggara')
        <li><a href="{{ route('organizer.dashboard') }}" style="text-decoration:none;color:#6B7280;font-weight:500;font-size:0.9rem;">Dashboard</a></li>
        @endif
    </ul>
    <div style="display:flex;align-items:center;gap:12px;">
        @if($is_logged_in)
            @if($user->role === 'mahasiswa')
            <a href="{{ route('my.events') }}" class="btn btn-outline btn-sm"><i class="fas fa-star"></i> My Events</a>
            @endif
            <a href="{{ route('profile') }}" style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6C63FF,#8B83FF);color:white;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:14px;text-decoration:none;transition:all 0.2s;">
                {{ strtoupper(substr($user->nama, 0, 1)) }}
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm" style="background:transparent;border:1.5px solid #D1D5DB;padding:0.3rem 0.8rem;border-radius:50px;font-weight:600;font-size:0.75rem;cursor:pointer;color:#6B7280;">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
        @endif
        <button onclick="toggleMobileMenu()" style="display:none;font-size:1.5rem;cursor:pointer;color:#6B7280;background:none;border:none;">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<!-- ===== HERO ===== -->
<section style="padding:4rem 2rem 3rem;background:linear-gradient(135deg,#6C63FF,#4A42B8);color:white;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30%;right:-10%;width:500px;height:500px;background:rgba(255,255,255,0.05);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-20%;left:-5%;width:300px;height:300px;background:rgba(255,255,255,0.03);border-radius:50%;"></div>
    <div style="max-width:1280px;margin:0 auto;position:relative;z-index:1;display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;">
        <div>
            <h1 style="font-size:3.2rem;font-weight:900;line-height:1.1;">Your Campus, <span style="color:#FFE66D;">Organized.</span></h1>
            <p style="font-size:1.1rem;opacity:0.85;margin:1rem 0 1.5rem;max-width:460px;line-height:1.7;">Temukan, ikuti, dan kelola event kampus dalam satu platform. Dari seminar hingga lomba, semua ada di Campven.</p>
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                @if($is_logged_in)
                    <a href="#events" class="btn" style="background:white;color:#6C63FF;padding:0.5rem 1.2rem;border-radius:50px;font-weight:600;text-decoration:none;"><i class="fas fa-calendar-alt"></i> Lihat Event</a>
                    @if($user->role === 'mahasiswa')
                    <a href="{{ route('my.events') }}" style="border-color:rgba(255,255,255,0.3);color:white;background:transparent;border:1.5px solid rgba(255,255,255,0.3);padding:0.5rem 1.2rem;border-radius:50px;font-weight:600;text-decoration:none;">
                        <i class="fas fa-star"></i> Event Saya
                    </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn" style="background:white;color:#6C63FF;padding:0.5rem 1.2rem;border-radius:50px;font-weight:600;text-decoration:none;"><i class="fas fa-user-plus"></i> Daftar Gratis</a>
                @endif
            </div>
            <div style="display:flex;gap:2.5rem;margin-top:2rem;">
                <div><div style="font-size:2rem;font-weight:800;">{{ $total_events }}</div><div style="font-size:0.85rem;opacity:0.7;">Total Event</div></div>
                <div><div style="font-size:2rem;font-weight:800;">{{ $total_users }}</div><div style="font-size:0.85rem;opacity:0.7;">Pengguna Aktif</div></div>
                <div><div style="font-size:2rem;font-weight:800;">{{ $total_participants }}</div><div style="font-size:0.85rem;opacity:0.7;">Peserta Terdaftar</div></div>
            </div>
        </div>
        <div>
            <div style="background:rgba(255,255,255,0.08);border-radius:16px;padding:2rem;text-align:center;backdrop-filter:blur(4px);border:1px solid rgba(255,255,255,0.1);">
                <i class="fas fa-calendar-check" style="font-size:4rem;color:#FFE66D;margin-bottom:1rem;display:block;"></i>
                <h3 style="font-size:1.2rem;margin-bottom:0.5rem;">Event Kampus Terpusat</h3>
                <p style="opacity:0.7;font-size:0.9rem;">Semua event kampus dalam satu platform yang mudah diakses</p>
                <div style="margin-top:1rem;display:flex;gap:0.5rem;justify-content:center;font-size:0.7rem;opacity:0.7;flex-wrap:wrap;">
                    <span><i class="fas fa-check-circle"></i> Seminar</span>
                    <span><i class="fas fa-check-circle"></i> Workshop</span>
                    <span><i class="fas fa-check-circle"></i> Lomba</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== SEARCH ===== -->
<div style="max-width:1280px;margin:-1.5rem auto 0;padding:0 2rem;position:relative;z-index:2;">
    <form method="GET" action="{{ route('home') }}" style="background:white;border-radius:16px;padding:0.5rem;display:flex;align-items:center;gap:0.5rem;box-shadow:0 10px 40px rgba(0,0,0,0.1);border:1px solid rgba(255,255,255,0.2);backdrop-filter:blur(4px);">
        <span style="padding:0 0.5rem 0 1rem;color:#9CA3AF;"><i class="fas fa-search"></i></span>
        <input type="text" name="search" placeholder="Cari event, kategori, atau lokasi..." value="{{ $search ?? '' }}" style="flex:1;border:none;padding:0.8rem 0.5rem;font-size:1rem;font-family:'Inter',sans-serif;outline:none;color:#1F2937;background:transparent;">
        @if(!empty($search))
        <a href="{{ route('home') }}" style="padding:0.5rem 1rem;background:transparent;color:#9CA3AF;border:none;cursor:pointer;font-size:1.1rem;text-decoration:none;">
            <i class="fas fa-times"></i>
        </a>
        @endif
        <button type="submit" style="padding:0.7rem 1.8rem;background:#6C63FF;color:white;border:none;border-radius:10px;font-weight:600;font-size:0.9rem;cursor:pointer;font-family:'Inter',sans-serif;white-space:nowrap;">
            <i class="fas fa-search"></i> Cari
        </button>
    </form>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="container">

    @if(!empty($search) || !empty($kategori))
        <!-- ===== SEARCH/FILTER RESULTS ===== -->
        <div style="padding:3rem 0;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:1.5rem;flex-wrap:wrap;">
                <h2 style="font-size:1.5rem;font-weight:700;">
                    @if(!empty($search))
                        <i class="fas fa-search" style="color:#6C63FF;"></i> Hasil Pencarian
                    @else
                        <i class="fas fa-tags" style="color:#6C63FF;"></i> Kategori: {{ $kategori }}
                    @endif
                </h2>
                <span style="background:#F3F4F6;padding:0.2rem 0.8rem;border-radius:50px;font-size:0.85rem;color:#6B7280;">{{ count($events) }} hasil ditemukan</span>
                <a href="{{ route('home') }}" style="color:#6C63FF;text-decoration:none;font-weight:500;font-size:0.9rem;">kembali</a>
            </div>

            @if(count($events) > 0)
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;">
                @foreach($events as $event)
                <a href="{{ route('event.detail', $event->id) }}" style="background:#FFFFFF;border-radius:16px;overflow:hidden;border:1px solid #E5E7EB;transition:all 0.3s;text-decoration:none;color:inherit;">
                    <div style="height:180px;background:linear-gradient(135deg, {{ $event->kategori == 'Seminar' ? '#6C63FF' : ($event->kategori == 'Workshop' ? '#10B981' : ($event->kategori == 'Lomba' ? '#F59E0B' : '#3B82F6')) }}, {{ $event->kategori == 'Seminar' ? '#8B83FF' : ($event->kategori == 'Workshop' ? '#34D399' : ($event->kategori == 'Lomba' ? '#FBBF24' : '#60A5FA')) }});display:flex;align-items:center;justify-content:center;color:white;font-size:3rem;position:relative;overflow:hidden;">
                        @if($event->poster && file_exists(public_path('storage/' . $event->poster)))
                            <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->nama_event }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="display:flex;flex-direction:column;align-items:center;gap:8px;text-shadow:0 2px 4px rgba(0,0,0,0.3);">
                                <i class="fas {{ $event->kategori == 'Seminar' ? 'fa-graduation-cap' : ($event->kategori == 'Workshop' ? 'fa-tools' : ($event->kategori == 'Lomba' ? 'fa-trophy' : 'fa-calendar')) }}"></i>
                                <span style="font-size:0.8rem;opacity:0.8;">{{ $event->kategori }}</span>
                            </div>
                        @endif
                        <span style="position:absolute;bottom:12px;right:12px;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);padding:0.2rem 0.8rem;border-radius:50px;font-size:0.65rem;font-weight:600;text-transform:uppercase;color:white;">{{ $event->kategori }}</span>
                    </div>
                    <div style="padding:1rem 1.2rem;">
                        <h3 style="font-size:1rem;font-weight:700;margin-bottom:0.3rem;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;">{{ $event->nama_event }}</h3>
                        <div style="display:flex;flex-direction:column;gap:3px;font-size:0.8rem;color:#6B7280;">
                            <span><i class="fas fa-calendar-day" style="width:16px;color:#6C63FF;"></i> {{ date('d M Y', strtotime($event->tanggal)) }}</span>
                            <span><i class="fas fa-map-marker-alt" style="width:16px;color:#6C63FF;"></i> {{ $event->lokasi }}</span>
                        </div>
                    </div>
                    <div style="padding:0.8rem 1.2rem;border-top:1px solid #F3F4F6;display:flex;justify-content:space-between;align-items:center;font-size:0.8rem;color:#6B7280;">
                        <span><i class="fas fa-users" style="color:#6C63FF;"></i> {{ $event->participantCount() }} peserta</span>
                        <span>{{ $event->creator->nama ?? 'Tidak diketahui' }}</span>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div style="text-align:center;padding:4rem 2rem;background:#FFFFFF;border-radius:16px;border:1px solid #E5E7EB;">
                <i class="fas {{ !empty($search) ? 'fa-search' : 'fa-calendar-times' }}" style="font-size:3rem;color:#D1D5DB;margin-bottom:1rem;"></i>
                <h3 style="font-size:1.2rem;color:#6B7280;">
                    @if(!empty($search))
                        Tidak ada event ditemukan
                    @else
                        Belum ada event di kategori ini
                    @endif
                </h3>
                <p style="color:#9CA3AF;">Coba cari kategori lain atau <a href="{{ route('home') }}" style="color:#6C63FF;text-decoration:none;font-weight:500;">kembali ke beranda</a></p>
            </div>
            @endif
        </div>
    @else
        <!-- ===== CATEGORIES ===== -->
        <div style="padding:3rem 0;" id="categories">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:10px;">
                <h2 style="font-size:1.8rem;font-weight:800;color:#1F2937;"><i class="fas fa-tags" style="color:#6C63FF;margin-right:8px;"></i> Kategori Event</h2>
                <a href="#events" style="color:#6C63FF;text-decoration:none;font-weight:500;font-size:0.9rem;">Lihat Semua →</a>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:1rem;">
                @foreach($categories as $cat)
                @php
                    $count = \App\Models\Event::where('kategori', $cat->nama)->count();
                @endphp
                <a href="{{ route('home', ['kategori' => $cat->nama]) }}" style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:16px;padding:1.2rem 0.8rem;text-align:center;transition:all 0.2s;text-decoration:none;color:#374151;">
                    <i class="fas {{ $cat->icon }}" style="font-size:1.8rem;margin-bottom:0.3rem;display:block;color:{{ $cat->color }};"></i>
                    <div style="font-weight:600;font-size:0.85rem;">{{ $cat->nama }}</div>
                    <div style="font-size:0.7rem;color:#9CA3AF;">{{ $count }} event</div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- ===== FEATURED EVENTS ===== -->
        <div style="padding:3rem 0;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:10px;">
                <h2 style="font-size:1.8rem;font-weight:800;color:#1F2937;"><i class="fas fa-calendar-alt" style="color:#6C63FF;margin-right:8px;"></i> Event Unggulan</h2>
                <a href="#events" style="color:#6C63FF;text-decoration:none;font-weight:500;font-size:0.9rem;">Lihat Semua →</a>
            </div>
            @if(count($featured_events) > 0)
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;">
                @foreach($featured_events as $event)
                <a href="{{ route('event.detail', $event->id) }}" style="background:#FFFFFF;border-radius:16px;overflow:hidden;border:1px solid #E5E7EB;transition:all 0.3s;text-decoration:none;color:inherit;">
                    <div style="height:180px;background:linear-gradient(135deg, {{ $event->kategori == 'Seminar' ? '#6C63FF' : ($event->kategori == 'Workshop' ? '#10B981' : ($event->kategori == 'Lomba' ? '#F59E0B' : '#3B82F6')) }}, {{ $event->kategori == 'Seminar' ? '#8B83FF' : ($event->kategori == 'Workshop' ? '#34D399' : ($event->kategori == 'Lomba' ? '#FBBF24' : '#60A5FA')) }});display:flex;align-items:center;justify-content:center;color:white;font-size:3rem;position:relative;overflow:hidden;">
                        @if($event->poster && file_exists(public_path('storage/' . $event->poster)))
                            <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->nama_event }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="display:flex;flex-direction:column;align-items:center;gap:8px;text-shadow:0 2px 4px rgba(0,0,0,0.3);">
                                <i class="fas {{ $event->kategori == 'Seminar' ? 'fa-graduation-cap' : ($event->kategori == 'Workshop' ? 'fa-tools' : ($event->kategori == 'Lomba' ? 'fa-trophy' : 'fa-calendar')) }}"></i>
                                <span style="font-size:0.8rem;opacity:0.8;">{{ $event->kategori }}</span>
                            </div>
                        @endif
                        <span style="position:absolute;bottom:12px;right:12px;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);padding:0.2rem 0.8rem;border-radius:50px;font-size:0.65rem;font-weight:600;text-transform:uppercase;color:white;">{{ $event->kategori }}</span>
                    </div>
                    <div style="padding:1rem 1.2rem;">
                        <h3 style="font-size:1rem;font-weight:700;margin-bottom:0.3rem;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;">{{ $event->nama_event }}</h3>
                        <div style="display:flex;flex-direction:column;gap:3px;font-size:0.8rem;color:#6B7280;">
                            <span><i class="fas fa-calendar-day" style="width:16px;color:#6C63FF;"></i> {{ date('d M Y', strtotime($event->tanggal)) }}</span>
                            <span><i class="fas fa-map-marker-alt" style="width:16px;color:#6C63FF;"></i> {{ $event->lokasi }}</span>
                        </div>
                    </div>
                    <div style="padding:0.8rem 1.2rem;border-top:1px solid #F3F4F6;display:flex;justify-content:space-between;align-items:center;font-size:0.8rem;color:#6B7280;">
                        <span><i class="fas fa-users" style="color:#6C63FF;"></i> {{ $event->participantCount() }} peserta</span>
                        <span>{{ $event->creator->nama ?? 'Tidak diketahui' }}</span>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div style="text-align:center;padding:2rem;background:#F9FAFB;border-radius:16px;">
                <p style="color:#6B7280;">Belum ada event unggulan saat ini.</p>
            </div>
            @endif
        </div>

        <!-- ===== ALL EVENTS ===== -->
        <div style="padding:3rem 0;" id="events">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:10px;">
                <h2 style="font-size:1.8rem;font-weight:800;color:#1F2937;"><i class="fas fa-list" style="color:#6C63FF;margin-right:8px;"></i> Semua Event</h2>
            </div>
            @if(count($events) > 0)
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;">
                @foreach($events as $event)
                <a href="{{ route('event.detail', $event->id) }}" style="background:#FFFFFF;border-radius:16px;overflow:hidden;border:1px solid #E5E7EB;transition:all 0.3s;text-decoration:none;color:inherit;">
                    <div style="height:180px;background:linear-gradient(135deg, {{ $event->kategori == 'Seminar' ? '#6C63FF' : ($event->kategori == 'Workshop' ? '#10B981' : ($event->kategori == 'Lomba' ? '#F59E0B' : '#3B82F6')) }}, {{ $event->kategori == 'Seminar' ? '#8B83FF' : ($event->kategori == 'Workshop' ? '#34D399' : ($event->kategori == 'Lomba' ? '#FBBF24' : '#60A5FA')) }});display:flex;align-items:center;justify-content:center;color:white;font-size:3rem;position:relative;overflow:hidden;">
                        @if($event->poster && file_exists(public_path('storage/' . $event->poster)))
                            <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->nama_event }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="display:flex;flex-direction:column;align-items:center;gap:8px;text-shadow:0 2px 4px rgba(0,0,0,0.3);">
                                <i class="fas {{ $event->kategori == 'Seminar' ? 'fa-graduation-cap' : ($event->kategori == 'Workshop' ? 'fa-tools' : ($event->kategori == 'Lomba' ? 'fa-trophy' : 'fa-calendar')) }}"></i>
                                <span style="font-size:0.8rem;opacity:0.8;">{{ $event->kategori }}</span>
                            </div>
                        @endif
                        <span style="position:absolute;bottom:12px;right:12px;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);padding:0.2rem 0.8rem;border-radius:50px;font-size:0.65rem;font-weight:600;text-transform:uppercase;color:white;">{{ $event->kategori }}</span>
                    </div>
                    <div style="padding:1rem 1.2rem;">
                        <h3 style="font-size:1rem;font-weight:700;margin-bottom:0.3rem;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;">{{ $event->nama_event }}</h3>
                        <div style="display:flex;flex-direction:column;gap:3px;font-size:0.8rem;color:#6B7280;">
                            <span><i class="fas fa-calendar-day" style="width:16px;color:#6C63FF;"></i> {{ date('d M Y', strtotime($event->tanggal)) }}</span>
                            <span><i class="fas fa-map-marker-alt" style="width:16px;color:#6C63FF;"></i> {{ $event->lokasi }}</span>
                        </div>
                    </div>
                    <div style="padding:0.8rem 1.2rem;border-top:1px solid #F3F4F6;display:flex;justify-content:space-between;align-items:center;font-size:0.8rem;color:#6B7280;">
                        <span><i class="fas fa-users" style="color:#6C63FF;"></i> {{ $event->participantCount() }} peserta</span>
                        <span>{{ $event->creator->nama ?? 'Tidak diketahui' }}</span>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div style="text-align:center;padding:4rem 2rem;background:#FFFFFF;border-radius:16px;border:1px solid #E5E7EB;">
                <i class="fas fa-calendar-times" style="font-size:3rem;color:#D1D5DB;margin-bottom:1rem;"></i>
                <h3 style="font-size:1.2rem;color:#6B7280;">Belum ada event</h3>
                <p style="color:#9CA3AF;">Mulai tambahkan event kampus pertamamu!</p>
            </div>
            @endif
        </div>
    @endif
</div>

<!-- ===== ANNOUNCEMENTS ===== -->
<div class="container">
    <div style="padding:3rem 0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:10px;">
            <h2 style="font-size:1.8rem;font-weight:800;color:#1F2937;"><i class="fas fa-bullhorn" style="color:#6C63FF;margin-right:8px;"></i> Pengumuman</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.2rem;">
            @foreach($announcements as $ann)
            <div style="background:#FFFFFF;border-radius:16px;padding:1.5rem;border:1px solid #E5E7EB;transition:all 0.2s;">
                <div style="font-weight:700;font-size:1.05rem;">{{ $ann->title }}</div>
                <div style="color:#6B7280;font-size:0.9rem;margin-top:0.3rem;">{{ Str::limit($ann->content, 120) }}...</div>
                <div style="font-size:0.75rem;color:#9CA3AF;margin-top:0.5rem;"><i class="far fa-clock"></i> {{ date('d M Y', strtotime($ann->created_at)) }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- ===== ABOUT ===== -->
<div class="container" id="about">
    <div style="padding:3rem 0;">
        <div style="background:#F9FAFB;border-radius:16px;padding:2.5rem;text-align:center;">
            <h2 style="font-size:1.8rem;font-weight:800;">Tentang <span style="color:#6C63FF;">Campven</span></h2>
            <p style="color:#6B7280;font-size:1.05rem;line-height:1.8;max-width:700px;margin:0.5rem auto 0;">Campven adalah platform event kampus yang menghubungkan mahasiswa dengan berbagai kegiatan kampus. Kami percaya bahwa setiap mahasiswa berhak untuk menemukan dan mengikuti event yang sesuai dengan minat dan bakat mereka.</p>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1.5rem;margin-top:2rem;text-align:left;">
                <div style="background:white;padding:1.5rem;border-radius:16px;border:1px solid #E5E7EB;">
                    <i class="fas fa-bullseye" style="color:#6C63FF;font-size:1.5rem;"></i>
                    <h4 style="margin:0.5rem 0 0.2rem;">Misi Kami</h4>
                    <p style="font-size:0.85rem;color:#6B7280;">Menyediakan platform terpusat untuk event kampus.</p>
                </div>
                <div style="background:white;padding:1.5rem;border-radius:16px;border:1px solid #E5E7EB;">
                    <i class="fas fa-users" style="color:#10B981;font-size:1.5rem;"></i>
                    <h4 style="margin:0.5rem 0 0.2rem;">Komunitas</h4>
                    <p style="font-size:0.85rem;color:#6B7280;">Bergabung dengan ribuan mahasiswa aktif.</p>
                </div>
                <div style="background:white;padding:1.5rem;border-radius:16px;border:1px solid #E5E7EB;">
                    <i class="fas fa-certificate" style="color:#F59E0B;font-size:1.5rem;"></i>
                    <h4 style="margin:0.5rem 0 0.2rem;">Sertifikat</h4>
                    <p style="font-size:0.85rem;color:#6B7280;">Dapatkan sertifikat dari event yang diikuti.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== FOOTER ===== -->
<footer style="background:#111827;color:white;padding:3rem 2rem 1.5rem;margin-top:2rem;">
    <div style="max-width:1280px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:2rem;">
        <div>
            <h4 style="font-size:1rem;font-weight:700;margin-bottom:0.8rem;">Campven</h4>
            <p style="opacity:0.7;font-size:0.9rem;line-height:1.7;">Your Campus, Organized. Platform event kampus terpusat untuk mahasiswa Indonesia.</p>
        </div>
        <div>
            <h4 style="font-size:1rem;font-weight:700;margin-bottom:0.8rem;">Menu</h4>
            <ul style="list-style:none;">
                <li style="margin-bottom:0.4rem;"><a href="#events" style="color:rgba(255,255,255,0.7);text-decoration:none;font-size:0.9rem;">Event</a></li>
                <li style="margin-bottom:0.4rem;"><a href="#categories" style="color:rgba(255,255,255,0.7);text-decoration:none;font-size:0.9rem;">Kategori</a></li>
                <li style="margin-bottom:0.4rem;"><a href="#about" style="color:rgba(255,255,255,0.7);text-decoration:none;font-size:0.9rem;">Tentang</a></li>
            </ul>
        </div>
        <div>
            <h4 style="font-size:1rem;font-weight:700;margin-bottom:0.8rem;">Untuk Kamu</h4>
            <ul style="list-style:none;">
                <li style="margin-bottom:0.4rem;"><a href="{{ route('register') }}" style="color:rgba(255,255,255,0.7);text-decoration:none;font-size:0.9rem;">Daftar</a></li>
                <li style="margin-bottom:0.4rem;"><a href="{{ route('login') }}" style="color:rgba(255,255,255,0.7);text-decoration:none;font-size:0.9rem;">Masuk</a></li>
                @if($is_logged_in && $user->role === 'mahasiswa')
                <li style="margin-bottom:0.4rem;"><a href="{{ route('my.events') }}" style="color:rgba(255,255,255,0.7);text-decoration:none;font-size:0.9rem;">My Events</a></li>
                @endif
                @if($is_logged_in)
                <li style="margin-bottom:0.4rem;"><a href="{{ route('profile') }}" style="color:rgba(255,255,255,0.7);text-decoration:none;font-size:0.9rem;">Profil</a></li>
                @endif
            </ul>
        </div>
        <div>
            <h4 style="font-size:1rem;font-weight:700;margin-bottom:0.8rem;">Kontak</h4>
            <ul style="list-style:none;">
                <li style="margin-bottom:0.4rem;"><a href="#" style="color:rgba(255,255,255,0.7);text-decoration:none;font-size:0.9rem;">support@campven.com</a></li>
                <li style="margin-bottom:0.4rem;"><a href="#" style="color:rgba(255,255,255,0.7);text-decoration:none;font-size:0.9rem;">@campven</a></li>
            </ul>
        </div>
    </div>
    <div style="max-width:1280px;margin:2rem auto 0;padding-top:1.5rem;border-top:1px solid rgba(255,255,255,0.1);text-align:center;font-size:0.85rem;opacity:0.6;">
        &copy; {{ date('Y') }} Campven. All rights reserved.
    </div>
</footer>

<script>
    function toggleMobileMenu() {
        const links = document.querySelector('.nav-links');
        if (links.style.display === 'flex') {
            links.style.display = 'none';
        } else {
            links.style.display = 'flex';
            links.style.flexDirection = 'column';
            links.style.position = 'absolute';
            links.style.top = '72px';
            links.style.left = '0';
            links.style.right = '0';
            links.style.background = 'white';
            links.style.padding = '1rem 2rem';
            links.style.borderBottom = '1px solid #E5E7EB';
            links.style.boxShadow = '0 4px 20px rgba(0,0,0,0.06)';
        }
    }

    @if(!empty($search))
    document.addEventListener('DOMContentLoaded', function() {
        const searchSection = document.querySelector('.search-section');
        if (searchSection) {
            setTimeout(function() {
                searchSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 300);
        }
    });
    @endif
</script>

@if(session('success'))
<script>
    setTimeout(function() {
        const notif = document.querySelector('.notification.success');
        if (notif) {
            notif.style.transition = 'opacity 0.5s';
            notif.style.opacity = '0';
            setTimeout(() => notif.remove(), 500);
        }
    }, 5000);
</script>
@endif
@endsection