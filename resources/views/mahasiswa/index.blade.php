@extends('layouts.app')

@section('title', 'Campven - Your Campus, Organized')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<style>
    /* ===== NAVBAR ===== */
    .navbar {
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(12px);
        padding: 0 2rem;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--gray-200);
        position: sticky;
        top: 0;
        z-index: 100;
    }
    .navbar-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }
    .navbar-logo {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        font-weight: 800;
    }
    .navbar-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--gray-900);
    }
    .nav-links {
        display: flex;
        align-items: center;
        gap: 2rem;
        list-style: none;
    }
    .nav-link {
        text-decoration: none;
        color: var(--gray-500);
        font-weight: 500;
        font-size: 0.9rem;
        transition: color 0.2s;
    }
    .nav-link:hover { color: var(--primary); }
    .nav-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .avatar:hover { transform: scale(1.05); }
    .logout-btn {
        background: transparent;
        border: 1.5px solid var(--gray-300);
        padding: 0.3rem 0.8rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        cursor: pointer;
        color: var(--gray-500);
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
    }
    .logout-btn:hover { border-color: var(--danger); color: var(--danger); }
    .menu-toggle {
        display: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--gray-500);
        background: none;
        border: none;
    }

    /* ===== HERO ===== */
    .hero {
        padding: 4rem 2rem 3rem;
        background: linear-gradient(135deg, var(--primary), #4A42B8);
        color: white;
        position: relative;
        overflow: hidden;
    }
    .hero::before,
    .hero::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,0.05);
    }
    .hero::before { top: -30%; right: -10%; width: 500px; height: 500px; }
    .hero::after { bottom: -20%; left: -5%; width: 300px; height: 300px; background: rgba(255,255,255,0.03); }
    .hero-grid {
        max-width: 1280px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: center;
    }
    .hero-title { font-size: 3.2rem; font-weight: 900; line-height: 1.1; }
    .hero-title span { color: #FFE66D; }
    .hero-subtitle {
        font-size: 1.1rem;
        opacity: 0.85;
        margin: 1rem 0 1.5rem;
        max-width: 460px;
        line-height: 1.7;
    }
    .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }
    .btn-white {
        background: white;
        color: var(--primary);
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
    }
    .btn-ghost {
        border: 1.5px solid rgba(255,255,255,0.3);
        color: white;
        background: transparent;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
    }
    .hero-stats { display: flex; gap: 2.5rem; margin-top: 2rem; }
    .hero-stat-value { font-size: 2rem; font-weight: 800; }
    .hero-stat-label { font-size: 0.85rem; opacity: 0.7; }
    .hero-card {
        background: rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .hero-card-icon { font-size: 4rem; color: #FFE66D; margin-bottom: 1rem; display: block; }
    .hero-card h3 { font-size: 1.2rem; margin-bottom: 0.5rem; }
    .hero-card p { opacity: 0.7; font-size: 0.9rem; }
    .hero-card-tags {
        margin-top: 1rem;
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        font-size: 0.7rem;
        opacity: 0.7;
        flex-wrap: wrap;
    }

    /* ===== SEARCH BAR ===== */
    .search-wrapper {
        max-width: 1280px;
        margin: -1.5rem auto 0;
        padding: 0 2rem;
        position: relative;
        z-index: 2;
    }
    .search-form {
        background: white;
        border-radius: 16px;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(4px);
    }
    .search-icon { padding: 0 0.5rem 0 1rem; color: var(--gray-400); }
    .search-input {
        flex: 1;
        border: none;
        padding: 0.8rem 0.5rem;
        font-size: 1rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        color: var(--gray-800);
        background: transparent;
    }
    .search-clear {
        padding: 0.5rem 1rem;
        background: transparent;
        color: var(--gray-400);
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        text-decoration: none;
    }
    .search-submit {
        padding: 0.7rem 1.8rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        white-space: nowrap;
        transition: background 0.2s;
    }
    .search-submit:hover { background: var(--primary-dark); }

    /* ===== SECTIONS ===== */
    .section { padding: 3rem 0; }
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 10px;
    }
    .section-title { font-size: 1.8rem; font-weight: 800; color: var(--gray-800); }
    .section-title i { color: var(--primary); margin-right: 8px; }
    .section-link { color: var(--primary); text-decoration: none; font-weight: 500; font-size: 0.9rem; }

    /* ===== EVENT CARDS ===== */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .event-card {
        text-decoration: none;
        color: inherit;
    }
    .card-fallback {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    .card-fallback i { font-size: 3rem; }
    .card-fallback span { font-size: 0.8rem; opacity: 0.8; }
    .card-badge {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        color: white;
    }
    .card-body { padding: 1rem 1.2rem; }
    .card-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .card-meta {
        display: flex;
        flex-direction: column;
        gap: 3px;
        font-size: 0.8rem;
        color: var(--gray-500);
    }
    .card-meta i { width: 16px; color: var(--primary); }
    .card-footer {
        padding: 0.8rem 1.2rem;
        border-top: 1px solid var(--gray-100);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: var(--gray-500);
    }
    .card-footer i { color: var(--primary); }

    /* ===== CATEGORY CARDS ===== */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 1rem;
    }
    .category-card {
        background: #FFFFFF;
        border: 1px solid var(--gray-200);
        border-radius: 16px;
        padding: 1.2rem 0.8rem;
        text-align: center;
        transition: all 0.2s;
        text-decoration: none;
        color: var(--gray-700);
    }
    .category-card i { font-size: 1.8rem; margin-bottom: 0.3rem; display: block; }
    .category-card-name { font-weight: 600; font-size: 0.85rem; }
    .category-card-count { font-size: 0.7rem; color: var(--gray-400); }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #FFFFFF;
        border-radius: 16px;
        border: 1px solid var(--gray-200);
    }
    .empty-state i { font-size: 3rem; color: var(--gray-300); margin-bottom: 1rem; }
    .empty-state h3 { font-size: 1.2rem; color: var(--gray-500); }
    .empty-state p { color: var(--gray-400); }
    .empty-state a { color: var(--primary); text-decoration: none; font-weight: 500; }

    /* ===== ANNOUNCEMENTS ===== */
    .announcement-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.2rem;
    }
    .announcement-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--gray-200);
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
    }
    .announcement-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(108,99,255,0.1);
        border-color: #C7D2FE;
    }
    .announcement-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 0.8rem;
    }
    .announcement-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .announcement-title { font-weight: 700; font-size: 1.05rem; line-height: 1.3; }
    .announcement-excerpt { color: var(--gray-500); font-size: 0.9rem; line-height: 1.7; flex: 1; }
    .announcement-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        padding-top: 0.8rem;
        border-top: 1px solid var(--gray-100);
    }
    .announcement-date { font-size: 0.75rem; color: var(--gray-400); }
    .announcement-more { color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.8rem; }
    .announcement-full {
        display: none;
        color: var(--gray-500);
        font-size: 0.9rem;
        line-height: 1.7;
        margin-top: 0.8rem;
        padding-top: 0.8rem;
        border-top: 1px solid var(--gray-100);
    }

    /* ===== ABOUT ===== */
    .about-box {
        background: var(--gray-50);
        border-radius: 16px;
        padding: 2.5rem;
        text-align: center;
    }
    .about-box h2 { font-size: 1.8rem; font-weight: 800; }
    .about-box h2 span { color: var(--primary); }
    .about-desc {
        color: var(--gray-500);
        font-size: 1.05rem;
        line-height: 1.8;
        max-width: 700px;
        margin: 0.5rem auto 0;
    }
    .about-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
        text-align: left;
    }
    .about-card {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        border: 1px solid var(--gray-200);
    }
    .about-card i { color: var(--primary); font-size: 1.5rem; }
    .about-card h4 { margin: 0.5rem 0 0.2rem; }
    .about-card p { font-size: 0.85rem; color: var(--gray-500); }

    /* ===== FOOTER ===== */
    .footer {
        background: var(--gray-900);
        color: white;
        padding: 3rem 2rem 1.5rem;
        margin-top: 2rem;
    }
    .footer-grid {
        max-width: 1280px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 2rem;
    }
    .footer h4 { font-size: 1rem; font-weight: 700; margin-bottom: 0.8rem; }
    .footer p { opacity: 0.7; font-size: 0.9rem; line-height: 1.7; }
    .footer ul { list-style: none; }
    .footer li { margin-bottom: 0.4rem; }
    .footer a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem; transition: color 0.2s; }
    .footer a:hover { color: white; }
    .footer-bottom {
        max-width: 1280px;
        margin: 2rem auto 0;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255,255,255,0.1);
        text-align: center;
        font-size: 0.85rem;
        opacity: 0.6;
    }

    /* ===== GLOBAL LINK ===== */
    a { text-decoration: none; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .nav-links {
            display: none;
            flex-direction: column;
            position: absolute;
            top: 72px;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--gray-200);
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .nav-links.open { display: flex; }
        .menu-toggle { display: block; }
        .hero { padding: 3rem 1rem 2.5rem; }
        .hero-grid { grid-template-columns: 1fr; gap: 2rem; }
        .hero-title { font-size: 2.2rem; }
        .hero-stats { gap: 1.5rem; }
        .search-wrapper { padding: 0 1rem; }
        .footer-grid { grid-template-columns: 1fr; gap: 1.5rem; }
    }
</style>
@endpush

@section('content')
<!-- ===== NAVBAR ===== -->
<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-brand">
        <div class="navbar-logo">C</div>
        <div><span class="navbar-title">Camp<span style="color:var(--primary);">ven</span></span></div>
    </a>
    <ul class="nav-links" id="navLinks">
        <li><a href="#events" class="nav-link">Event</a></li>
        <li><a href="#categories" class="nav-link">Kategori</a></li>
        <li><a href="#about" class="nav-link">Tentang</a></li>
        @if($is_logged_in && $user->role === 'admin')
        <li><a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a></li>
        @elseif($is_logged_in && $user->role === 'penyelenggara')
        <li><a href="{{ route('organizer.dashboard') }}" class="nav-link">Dashboard</a></li>
        @endif
    </ul>
    <div class="nav-actions">
        @if($is_logged_in)
            @if($user->role === 'mahasiswa')
            <a href="{{ route('my.events') }}" class="btn btn-outline btn-sm"><i class="fas fa-star"></i> My Events</a>
            @endif
            <a href="{{ route('profile') }}" class="avatar">{{ strtoupper(substr($user->nama, 0, 1)) }}</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
        @endif
        <button class="menu-toggle" onclick="toggleMobileMenu()" aria-label="Menu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="hero-grid">
        <div>
            <h1 class="hero-title">Your Campus, <span>Organized.</span></h1>
            <p class="hero-subtitle">Temukan, ikuti, dan kelola event kampus dalam satu platform. Dari seminar hingga lomba, semua ada di Campven.</p>
            <div class="hero-actions">
                @if($is_logged_in)
                    <a href="#events" class="btn-white"><i class="fas fa-calendar-alt"></i> Lihat Event</a>
                    @if($user->role === 'mahasiswa')
                    <a href="{{ route('my.events') }}" class="btn-ghost"><i class="fas fa-star"></i> Event Saya</a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-white"><i class="fas fa-user-plus"></i> Daftar Gratis</a>
                @endif
            </div>
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-value counter" data-counter="{{ $total_events }}">0</div>
                    <div class="hero-stat-label">Total Event</div>
                </div>
                <div>
                    <div class="hero-stat-value counter" data-counter="{{ $total_users }}">0</div>
                    <div class="hero-stat-label">Pengguna Aktif</div>
                </div>
                <div>
                    <div class="hero-stat-value counter" data-counter="{{ $total_participants }}">0</div>
                    <div class="hero-stat-label">Peserta Terdaftar</div>
                </div>
            </div>
        </div>
        <div>
            <div class="hero-card">
                <i class="fas fa-calendar-check hero-card-icon"></i>
                <h3>Event Kampus Terpusat</h3>
                <p>Semua event kampus dalam satu platform yang mudah diakses</p>
                <div class="hero-card-tags">
                    <span><i class="fas fa-check-circle"></i> Seminar</span>
                    <span><i class="fas fa-check-circle"></i> Workshop</span>
                    <span><i class="fas fa-check-circle"></i> Lomba</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== SEARCH ===== -->
<div class="search-wrapper">
    <form method="GET" action="{{ route('home') }}" class="search-form">
        <span class="search-icon"><i class="fas fa-search"></i></span>
        <input type="text" name="search" placeholder="Cari event, kategori, atau lokasi..." value="{{ $search ?? '' }}" class="search-input">
        @if(!empty($search))
        <a href="{{ route('home') }}" class="search-clear" aria-label="Hapus pencarian">
            <i class="fas fa-times"></i>
        </a>
        @endif
        <button type="submit" class="search-submit"><i class="fas fa-search"></i> Cari</button>
    </form>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="container">

    @if(!empty($search) || !empty($kategori))
        <!-- ===== SEARCH/FILTER RESULTS ===== -->
        <div class="section">
            <div class="section-header" style="justify-content:flex-start;">
                <h2 class="section-title" style="font-size:1.5rem;">
                    @if(!empty($search))
                        <i class="fas fa-search"></i> Hasil Pencarian
                    @else
                        <i class="fas fa-tags"></i> Kategori: {{ $kategori }}
                    @endif
                </h2>
                <span style="background:var(--gray-100);padding:0.2rem 0.8rem;border-radius:50px;font-size:0.85rem;color:var(--gray-500);">{{ count($events) }} hasil ditemukan</span>
                <a href="{{ route('home') }}" class="section-link">kembali</a>
            </div>

            @if(count($events) > 0)
            <div class="card-grid">
                @foreach($events as $event)
                    @include('mahasiswa.partials.event-card', ['event' => $event, 'animate' => true, 'delay' => $loop->index * 50])
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <i class="fas {{ !empty($search) ? 'fa-search' : 'fa-calendar-times' }}"></i>
                <h3>
                    @if(!empty($search))
                        Tidak ada event ditemukan
                    @else
                        Belum ada event di kategori ini
                    @endif
                </h3>
                <p>Coba cari kategori lain atau <a href="{{ route('home') }}">kembali ke beranda</a></p>
            </div>
            @endif
        </div>
    @else
        <!-- ===== CATEGORIES ===== -->
        <div class="section" id="categories">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-tags"></i> Kategori Event</h2>
                <a href="#events" class="section-link">Lihat Semua →</a>
            </div>
            <div class="category-grid">
                @foreach($categories as $cat)
                @php
                    $count = \App\Models\Event::where('kategori', $cat->nama)->count();
                @endphp
                <a href="{{ route('home', ['kategori' => $cat->nama]) }}" class="category-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <i class="fas {{ $cat->icon }}" style="color:{{ $cat->color }};"></i>
                    <div class="category-card-name">{{ $cat->nama }}</div>
                    <div class="category-card-count">{{ $count }} event</div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- ===== FEATURED EVENTS ===== -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-calendar-alt"></i> Event Unggulan</h2>
                <a href="#events" class="section-link">Lihat Semua →</a>
            </div>
            @if(count($featured_events) > 0)
            <div class="swiper featured-swiper" data-aos="fade-up">
                <div class="swiper-wrapper">
                    @foreach($featured_events as $event)
                    <div class="swiper-slide">
                        @include('mahasiswa.partials.event-card', ['event' => $event, 'animate' => false, 'delay' => 0])
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
            @else
            <div class="empty-state" style="padding:2rem;background:var(--gray-50);">
                <p style="color:var(--gray-500);">Belum ada event unggulan saat ini.</p>
            </div>
            @endif
        </div>

        <!-- ===== ALL EVENTS ===== -->
        <div class="section" id="events">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-list"></i> Semua Event</h2>
            </div>
            @if(count($events) > 0)
            <div class="card-grid">
                @foreach($events as $event)
                    @include('mahasiswa.partials.event-card', ['event' => $event, 'animate' => true, 'delay' => $loop->index * 50])
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>Belum ada event</h3>
                <p>Mulai tambahkan event kampus pertamamu!</p>
            </div>
            @endif
        </div>
    @endif

    <!-- ===== ANNOUNCEMENTS ===== -->
    @if(count($announcements) > 0)
    <div class="section">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-bullhorn"></i> Pengumuman</h2>
        </div>
        <div class="announcement-grid">
            @foreach($announcements as $ann)
            <div class="announcement-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="announcement-header">
                    <div class="announcement-icon"><i class="fas fa-bullhorn"></i></div>
                    <div class="announcement-title">{{ $ann->title }}</div>
                </div>
                <div class="announcement-excerpt">{{ Str::limit($ann->content, 200) }}</div>
                <div class="announcement-footer">
                    <div class="announcement-date"><i class="far fa-clock"></i> {{ date('d M Y', strtotime($ann->created_at)) }}</div>
                    @if(strlen($ann->content) > 200)
                    <a href="#" class="announcement-more" onclick="event.preventDefault(); this.closest('.announcement-card').querySelector('.announcement-full').style.display = 'block'; this.style.display = 'none';">Baca Selengkapnya →</a>
                    @endif
                </div>
                @if(strlen($ann->content) > 200)
                <div class="announcement-full">{{ $ann->content }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- ===== ABOUT ===== -->
<div class="container" id="about">
    <div class="section">
        <div class="about-box">
            <h2>Tentang <span>Campven</span></h2>
            <p class="about-desc">Campven adalah platform event kampus yang menghubungkan mahasiswa dengan berbagai kegiatan kampus. Kami percaya bahwa setiap mahasiswa berhak untuk menemukan dan mengikuti event yang sesuai dengan minat dan bakat mereka.</p>
            <div class="about-grid">
                <div class="about-card">
                    <i class="fas fa-bullseye"></i>
                    <h4>Misi Kami</h4>
                    <p>Menyediakan platform terpusat untuk event kampus.</p>
                </div>
                <div class="about-card">
                    <i class="fas fa-users" style="color:var(--success);"></i>
                    <h4>Komunitas</h4>
                    <p>Bergabung dengan ribuan mahasiswa aktif.</p>
                </div>
                <div class="about-card">
                    <i class="fas fa-certificate" style="color:var(--warning);"></i>
                    <h4>Sertifikat</h4>
                    <p>Dapatkan sertifikat dari event yang diikuti.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== FOOTER ===== -->
<footer class="footer">
    <div class="footer-grid">
        <div>
            <h4>Campven</h4>
            <p>Your Campus, Organized. Platform event kampus terpusat untuk mahasiswa Indonesia.</p>
        </div>
        <div>
            <h4>Menu</h4>
            <ul>
                <li><a href="#events">Event</a></li>
                <li><a href="#categories">Kategori</a></li>
                <li><a href="#about">Tentang</a></li>
            </ul>
        </div>
        <div>
            <h4>Untuk Kamu</h4>
            <ul>
                <li><a href="{{ route('register') }}">Daftar</a></li>
                <li><a href="{{ route('login') }}">Masuk</a></li>
                @if($is_logged_in && $user->role === 'mahasiswa')
                <li><a href="{{ route('my.events') }}">My Events</a></li>
                @endif
                @if($is_logged_in)
                <li><a href="{{ route('profile') }}">Profil</a></li>
                @endif
            </ul>
        </div>
        <div>
            <h4>Kontak</h4>
            <ul>
                <li><a href="#">support@campven.com</a></li>
                <li><a href="#">@campven</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; {{ date('Y') }} Campven. All rights reserved.
    </div>
</footer>

<script>
    function toggleMobileMenu() {
        document.getElementById('navLinks').classList.toggle('open');
    }
</script>
@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== AOS INIT =====
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 80
        });

        // ===== SWIPER INIT =====
        const featuredSwiper = new Swiper('.featured-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.featured-swiper .swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.featured-swiper .swiper-button-next',
                prevEl: '.featured-swiper .swiper-button-prev',
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });

        // ===== COUNTER ANIMATION =====
        const counters = document.querySelectorAll('.counter');
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseInt(el.getAttribute('data-counter'), 10) || 0;
                    const duration = 2000;
                    const start = performance.now();

                    function updateCounter(now) {
                        const progress = Math.min((now - start) / duration, 1);
                        const eased = 1 - Math.pow(1 - progress, 3);
                        el.textContent = Math.floor(eased * target).toLocaleString('id-ID');
                        if (progress < 1) {
                            requestAnimationFrame(updateCounter);
                        } else {
                            el.textContent = target.toLocaleString('id-ID');
                        }
                    }
                    requestAnimationFrame(updateCounter);
                    counterObserver.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => counterObserver.observe(counter));
    });
</script>
@endpush