@extends('layouts.app')

@section('title', 'Campven - Portal Event Organisasi Kampus')

@section('content')
@include('partials.public-navbar', ['activeNav' => 'home', 'heroDark' => true])

@php
    $hour = (int) now()->format('H');
    $salamWaktu = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $mosaicSource = isset($hero_mosaic_events) && $hero_mosaic_events->count() > 0 ? $hero_mosaic_events : $events;
    $mosaicPosters = $mosaicSource->filter(fn ($e) => $e->poster && file_exists(public_path('storage/' . $e->poster)))->take(6)->values();
    $statusFilter = $status_filter ?? 'mendatang';
    $hasActiveFilter = !empty($search) || !empty($kategori) || !empty($rentang) || !empty($lokasi_filter) || $statusFilter !== 'mendatang';
@endphp

<!-- ===== FULL-BLEED CINEMATIC DARK EVENT HERO BANNER (INDIGO-SLATE HARMONIZED) ===== -->
<section class="relative w-full min-h-[430px] sm:min-h-[490px] bg-slate-950 overflow-hidden flex items-center">
    <!-- Full-Bleed Monochrome/Darkened Event Photo Mosaic Background -->
    @if($mosaicPosters->count() > 0)
    <div class="absolute inset-0 grid grid-cols-2 sm:grid-cols-3 gap-1 opacity-40 grayscale-[45%] scale-[1.02] pointer-events-none select-none">
        @foreach($mosaicPosters as $mp)
            <div class="relative w-full h-full overflow-hidden">
                <img src="{{ asset('storage/' . $mp->poster) }}" alt="" class="w-full h-full object-cover object-top">
            </div>
        @endforeach
    </div>
    @endif

    <!-- Harmonized Indigo/Slate Editorial Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-indigo-950/88 to-slate-950/70"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-indigo-950/20 to-slate-950/40"></div>

    <!-- Subtle Geometric Diagonal Accent Lines -->
    <svg class="absolute inset-0 w-full h-full opacity-15 pointer-events-none hidden sm:block" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <line x1="58%" y1="0" x2="78%" y2="100%" stroke="#A5B4FC" stroke-width="1"/>
        <line x1="72%" y1="0" x2="92%" y2="100%" stroke="#5EEAD4" stroke-width="1"/>
        <line x1="88%" y1="0" x2="66%" y2="100%" stroke="white" stroke-width="1"/>
    </svg>

    <!-- Hero Foreground Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 w-full">
        <div class="max-w-2xl space-y-4 sm:space-y-5">
            <!-- Sapaan -->
            <p class="text-xs sm:text-sm font-semibold tracking-wide text-indigo-200">
                @if(Auth::check())
                    {{ $salamWaktu }}, <span class="text-white font-bold">{{ Auth::user()->nama }}</span>
                @else
                    {{ $salamWaktu }}, Selamat Datang di Portal Event Kampus
                @endif
            </p>

            <!-- Brand / Main Headline -->
            <div class="space-y-1.5">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.08]">
                    Campven Event
                </h1>
                <p class="text-lg sm:text-2xl font-semibold text-teal-400 tracking-tight">
                    Platform Event &amp; Organisasi Kampus Indonesia
                </p>
            </div>

            <!-- Slogan -->
            <p class="text-sm sm:text-base text-slate-200 leading-relaxed max-w-xl font-normal">
                Satu Kampus, Semua Event dalam Genggaman.<br class="hidden sm:inline">
                Temukan Seminar, Workshop, Kompetisi, &amp; Raih Poin SKPI Terbaikmu!
            </p>

            <!-- Primary & Secondary CTAs -->
            <div class="flex flex-wrap items-center gap-3 pt-2">
                <a href="#events" class="btn-tactile active:scale-[0.98] inline-flex items-center gap-2.5 px-7 py-3 rounded-full bg-teal-500 hover:bg-teal-400 text-slate-950 font-extrabold text-xs sm:text-sm uppercase tracking-wider shadow-lg shadow-teal-500/20 transition-all duration-200">
                    <i class="fas fa-compass"></i>
                    <span>Jelajahi Acara</span>
                </a>

                @if(!Auth::check())
                    <a href="{{ route('register') }}" class="btn-tactile active:scale-[0.98] inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/25 font-bold text-xs sm:text-sm transition-all duration-200 backdrop-blur-sm">
                        <span>Daftar Mahasiswa</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                @elseif(Auth::user()->role === 'mahasiswa')
                    <a href="{{ route('my.events') }}" class="btn-tactile active:scale-[0.98] inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/25 font-bold text-xs sm:text-sm transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-ticket-alt text-teal-300"></i>
                        <span>Buka Tiket Saya</span>
                    </a>
                @elseif(Auth::user()->role === 'penyelenggara')
                    <a href="{{ route('organizer.dashboard') }}" class="btn-tactile active:scale-[0.98] inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/25 font-bold text-xs sm:text-sm transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-th-large text-teal-300"></i>
                        <span>Kelola Acara Saya</span>
                    </a>
                @elseif(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn-tactile active:scale-[0.98] inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/25 font-bold text-xs sm:text-sm transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-shield-alt text-teal-300"></i>
                        <span>Dashboard Admin</span>
                    </a>
                @endif
            </div>

            <!-- Real Stats Bar -->
            <div class="pt-5 mt-1 border-t border-white/15 flex flex-wrap items-center gap-6 sm:gap-8 text-xs text-slate-300">
                <div>
                    <span class="block text-xl font-extrabold text-white leading-none">{{ $upcoming_events_count ?? $total_events }}</span>
                    <span class="mt-1 block text-slate-300">Agenda Aktif</span>
                </div>
                <div class="h-7 w-px bg-white/15"></div>
                <div>
                    <span class="block text-xl font-extrabold text-white leading-none">{{ $total_organizers }}</span>
                    <span class="mt-1 block text-slate-300">Organisasi Aktif</span>
                </div>
                <div class="h-7 w-px bg-white/15"></div>
                <div>
                    <span class="block text-xl font-extrabold text-white leading-none">{{ $total_participants }}</span>
                    <span class="mt-1 block text-slate-300">Partisipasi Mahasiswa</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== SEARCH & CATEGORY FILTER BAR ===== -->
<section class="bg-white border-b border-slate-200 py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3.5">
        <form method="GET" action="{{ route('home') }}#events" class="bg-slate-50 p-2 rounded-xl border border-slate-200 flex flex-col md:flex-row items-stretch md:items-center gap-2">
            @if(!empty($kategori))
                <input type="hidden" name="kategori" value="{{ $kategori }}">
            @endif
            @if(!empty($statusFilter))
                <input type="hidden" name="status" value="{{ $statusFilter }}">
            @endif
            @if(!empty($sort))
                <input type="hidden" name="sort" value="{{ $sort }}">
            @endif

            <div class="flex items-center gap-2.5 px-3 py-2 bg-white rounded-lg border border-slate-200 flex-1">
                <i class="fas fa-search text-slate-400 text-xs"></i>
                <input type="text"
                       id="mainSearchInput"
                       name="search"
                       value="{{ $search ?? '' }}"
                       placeholder="Cari judul acara, topik, atau lokasi..."
                       class="w-full bg-transparent text-sm text-slate-800 placeholder-slate-400 focus:outline-none">
                @if(!empty($search))
                    <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-600" title="Hapus pencarian">
                        <i class="fas fa-times-circle text-xs"></i>
                    </a>
                @endif
            </div>

            <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-slate-200 md:w-44">
                <i class="far fa-calendar text-slate-400 text-xs"></i>
                <select name="rentang" class="bg-transparent w-full text-xs text-slate-700 font-medium focus:outline-none cursor-pointer">
                    <option value="">Semua Waktu</option>
                    <option value="minggu_ini" {{ ($rentang ?? '') === 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan_ini" {{ ($rentang ?? '') === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="semester" {{ ($rentang ?? '') === 'semester' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>

            <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-slate-200 md:w-44">
                <i class="fas fa-map-marker-alt text-slate-400 text-xs"></i>
                <select name="lokasi_filter" class="bg-transparent w-full text-xs text-slate-700 font-medium focus:outline-none cursor-pointer">
                    <option value="">Semua Lokasi</option>
                    <option value="Auditorium" {{ ($lokasi_filter ?? '') === 'Auditorium' ? 'selected' : '' }}>Auditorium / Aula</option>
                    <option value="GSC" {{ ($lokasi_filter ?? '') === 'GSC' ? 'selected' : '' }}>Gedung GSC</option>
                    <option value="Lab" {{ ($lokasi_filter ?? '') === 'Lab' ? 'selected' : '' }}>Lab Komputer</option>
                    <option value="Online" {{ ($lokasi_filter ?? '') === 'Online' ? 'selected' : '' }}>Online / Zoom</option>
                </select>
            </div>

            <button type="submit" class="btn-tactile active:scale-[0.98] px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition-all duration-200 whitespace-nowrap">
                Cari Acara
            </button>
        </form>

        <!-- Category Filter Chips -->
        <div id="categories" class="scroll-mt-24 flex items-center gap-2 overflow-x-auto pb-1">
            <a href="{{ route('home', ['status' => $statusFilter]) }}#events" class="btn-tactile active:scale-[0.98] px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200 flex-shrink-0 {{ empty($kategori) ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">
                Semua Kategori
            </a>
            @foreach($categories as $cat)
                @php $isActive = ($kategori === $cat->nama); @endphp
                <a href="{{ route('home', ['kategori' => $cat->nama, 'status' => $statusFilter]) }}#events" class="btn-tactile active:scale-[0.98] px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200 flex items-center gap-1.5 flex-shrink-0 {{ $isActive ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">
                    <i class="fas {{ $cat->icon ?? 'fa-tag' }} text-[11px] opacity-80"></i>
                    <span>{{ $cat->nama }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== FEATURED UPCOMING EVENTS (SAFE POSTER FRAMING, NO AWKWARD CROPPING) ===== -->
@if(count($featured_events) > 0 && !$hasActiveFilter)
<section class="py-7 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-500 ring-4 ring-teal-500/15"></span>
                <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Agenda Mendatang Pilihan</h2>
            </div>
            <a href="{{ route('home', ['status' => 'semua']) }}#events" class="btn-tactile group inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/70 text-indigo-700 text-xs font-bold shadow-2xs transition-all duration-200">
                <span>Lihat Daftar Lengkap</span>
                <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform duration-200"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($featured_events->take(2) as $feIdx => $fe)
            @php
                $hasFePoster = $fe->poster && file_exists(public_path('storage/' . $fe->poster));
                $price = $fe->formattedHarga();
                $skpiFe = (int) ($fe->skpi_poin ?? 0);
            @endphp
            <div class="reveal-on-scroll group bg-white rounded-xl border border-slate-200 overflow-hidden hover:-translate-y-1 hover:border-indigo-200 hover:shadow-md transition-all duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] flex flex-col" style="--reveal-delay: {{ $feIdx * 60 }}ms;">
                <!-- Proportional 16:9 Poster Frame (No Letterbox Bars, No Awkward Cropping) -->
                <a href="{{ route('event.detail', $fe->id) }}" class="relative aspect-[16/9] w-full flex-shrink-0 bg-slate-900 block overflow-hidden">
                    @if($hasFePoster)
                        <img src="{{ asset('storage/' . $fe->poster) }}" alt="{{ $fe->nama_event }}" class="w-full h-full object-cover object-center group-hover:scale-[1.03] transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)]">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-teal-400 bg-gradient-to-br from-slate-900 to-indigo-950">
                            <i class="far fa-calendar-alt text-3xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3 flex items-center gap-1.5">
                        <span class="px-2.5 py-1 rounded-md bg-white/95 text-slate-800 text-[11px] font-semibold shadow-sm">{{ $fe->kategori }}</span>
                        @if($skpiFe > 0)
                            <span class="px-2.5 py-1 rounded-md bg-teal-600 text-white text-[11px] font-semibold shadow-sm">+{{ $skpiFe }} SKPI</span>
                        @endif
                    </div>
                </a>
                <div class="p-4 sm:p-5 flex flex-col justify-between flex-grow gap-3">
                    <div class="space-y-2">
                        <a href="{{ route('event.detail', $fe->id) }}" class="block">
                            <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition leading-snug line-clamp-2">{{ $fe->nama_event }}</h3>
                        </a>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-600">
                            <span><i class="far fa-calendar text-slate-400 mr-1"></i>{{ \Carbon\Carbon::parse($fe->tanggal)->locale('id')->isoFormat('D MMMM Y') }}</span>
                            <span class="truncate"><i class="fas fa-map-marker-alt text-slate-400 mr-1"></i>{{ $fe->lokasi }}</span>
                            <span class="truncate text-slate-500"><i class="far fa-building text-slate-400 mr-1"></i>{{ $fe->creator->nama ?? 'Penyelenggara Kampus' }}</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-sm font-bold {{ $price === 'GRATIS' ? 'text-teal-600' : 'text-slate-900' }}">{{ $price }}</span>
                        <a href="{{ route('event.detail', $fe->id) }}" class="btn-tactile active:scale-[0.98] px-4 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition">
                            Lihat Acara
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===== MAIN EVENT CATALOG + SIDEBAR ===== -->
<section id="events" class="scroll-mt-20 py-8 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header, Status Filter & Sort Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs mb-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 text-sm">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight leading-snug">
                        @if(!empty($search))
                            Hasil Pencarian: "{{ $search }}"
                        @elseif(!empty($kategori))
                            Kategori: {{ $kategori }}
                        @elseif($statusFilter === 'selesai')
                            Arsip Acara Selesai
                        @elseif($statusFilter === 'semua')
                            Seluruh Katalog Acara
                        @else
                            Agenda Mendatang
                        @endif
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Menampilkan <strong class="text-slate-700">{{ min(count($events), 6) }}</strong> dari <strong class="text-slate-700">{{ count($events) }}</strong> acara{{ $statusFilter === 'mendatang' ? ' aktif terdekat' : '' }}.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Curated Status Filter Tabs -->
                <div class="inline-flex items-center p-1 rounded-xl bg-slate-100/90 border border-slate-200/80 text-xs font-bold">
                    <a href="{{ route('home', array_filter(['search' => $search, 'kategori' => $kategori, 'lokasi_filter' => $lokasi_filter, 'status' => 'mendatang'])) }}#events"
                       class="btn-tactile px-3 py-1.5 rounded-lg transition {{ $statusFilter === 'mendatang' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Mendatang <span class="opacity-80">({{ $upcoming_events_count ?? 0 }})</span>
                    </a>
                    <a href="{{ route('home', array_filter(['search' => $search, 'kategori' => $kategori, 'lokasi_filter' => $lokasi_filter, 'status' => 'semua'])) }}#events"
                       class="btn-tactile px-3 py-1.5 rounded-lg transition {{ $statusFilter === 'semua' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Semua <span class="opacity-80">({{ $total_events ?? 0 }})</span>
                    </a>
                    <a href="{{ route('home', array_filter(['search' => $search, 'kategori' => $kategori, 'lokasi_filter' => $lokasi_filter, 'status' => 'selesai'])) }}#events"
                       class="btn-tactile px-3 py-1.5 rounded-lg transition {{ $statusFilter === 'selesai' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Selesai <span class="opacity-80">({{ $past_events_count ?? 0 }})</span>
                    </a>
                </div>

                <!-- Sort -->
                <form method="GET" action="{{ route('home') }}#events" class="flex items-center gap-2">
                    @if(!empty($search)) <input type="hidden" name="search" value="{{ $search }}"> @endif
                    @if(!empty($kategori)) <input type="hidden" name="kategori" value="{{ $kategori }}"> @endif
                    @if(!empty($rentang)) <input type="hidden" name="rentang" value="{{ $rentang }}"> @endif
                    @if(!empty($lokasi_filter)) <input type="hidden" name="lokasi_filter" value="{{ $lokasi_filter }}"> @endif
                    <input type="hidden" name="status" value="{{ $statusFilter }}">

                    <div class="relative flex items-center">
                        <i class="fas fa-sort-amount-down text-slate-400 text-[11px] absolute left-3 pointer-events-none"></i>
                        <select id="sortEventsSelect" name="sort" onchange="this.form.submit()" class="pl-8 pr-3 py-2 rounded-xl bg-slate-50 hover:bg-white border border-slate-200 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500 cursor-pointer transition" aria-label="Urutkan acara">
                            <option value="terdekat" {{ ($sort ?? 'terdekat') === 'terdekat' ? 'selected' : '' }}>Tanggal Terdekat</option>
                            <option value="terbaru" {{ ($sort ?? '') === 'terbaru' ? 'selected' : '' }}>Tanggal Terbaru</option>
                            <option value="kuota" {{ ($sort ?? '') === 'kuota' ? 'selected' : '' }}>Kuota Terbanyak</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            <!-- Left: Event Grid -->
            <div class="lg:col-span-8 space-y-5">
                @if(count($events) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5" id="homeEventsGrid">
                    @foreach($events as $idx => $event)
                        <div class="{{ $idx >= 6 ? 'hidden extra-event-card' : 'reveal-on-scroll' }}" @if($idx < 6) style="--reveal-delay: {{ $idx * 50 }}ms;" @endif>
                            @include('mahasiswa.partials.event-card', ['event' => $event])
                        </div>
                    @endforeach
                </div>

                @if(count($events) > 6)
                <div class="bg-white rounded-2xl border border-slate-200/90 p-4 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-2xs" id="showMoreEventsWrapper">
                    <div class="text-xs text-slate-600 text-center sm:text-left">
                        Masih ada <strong class="text-slate-900">{{ count($events) - 6 }} acara lainnya</strong> di katalog ini.
                    </div>
                    <button type="button" onclick="showAllHomeEvents()" class="btn-tactile active:scale-[0.98] inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition-all duration-200">
                        <span>Tampilkan Semua Acara (+{{ count($events) - 6 }})</span>
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                </div>
                @elseif($statusFilter === 'mendatang' && ($past_events_count ?? 0) > 0)
                <div class="bg-white rounded-2xl border border-slate-200/90 p-4 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-2xs">
                    <div class="text-xs text-slate-600 text-center sm:text-left">
                        Ingin menelusuri arsip kegiatan sebelumnya? Total <strong class="text-slate-900">{{ $total_events }} acara</strong> tercatat di Campven.
                    </div>
                    <a href="{{ route('home', ['status' => 'semua']) }}#events" class="btn-tactile active:scale-[0.98] group inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white font-bold text-xs transition-all duration-200 whitespace-nowrap">
                        <span>Lihat Daftar Lengkap</span>
                        <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                    </a>
                </div>
                @endif
                @else
                <div class="bg-white rounded-xl border border-slate-200 p-8 sm:p-10 text-center space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-2xl">
                        <i class="far fa-calendar-times"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">
                        @if(!empty($kategori))
                            Belum Ada Acara pada Kategori "{{ $kategori }}"
                        @else
                            Tidak Ada Acara Ditemukan
                        @endif
                    </h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                        @if($statusFilter === 'mendatang')
                            Saat ini belum ada jadwal mendatang yang cocok dengan filter Anda. Anda dapat menelusuri seluruh katalog atau mengatur ulang filter.
                        @else
                            Coba ubah kata kunci pencarian atau pilih kategori kegiatan lainnya.
                        @endif
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-2.5 pt-2">
                        @if($statusFilter === 'mendatang')
                        <a href="{{ route('home', array_filter(['kategori' => $kategori, 'search' => $search, 'status' => 'semua'])) }}#events" class="btn-tactile active:scale-[0.98] inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition-all duration-200">
                            Lihat Semua Status
                        </a>
                        @endif
                        <a href="{{ route('home') }}#events" class="btn-tactile active:scale-[0.98] inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-indigo-600 text-white font-semibold text-xs hover:bg-indigo-700 transition-all duration-200">
                            Reset Filter
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right: Official Announcements & Quick Portal -->
            <div class="lg:col-span-4 space-y-5">
                <div id="announcements" class="scroll-mt-24 reveal-on-scroll bg-white rounded-xl p-5 border border-slate-200 space-y-4" style="--reveal-delay: 80ms;">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                            <span>Pengumuman Resmi</span>
                        </h3>
                        <span class="text-[11px] font-medium text-slate-400">Kampus</span>
                    </div>

                    <div class="space-y-3 text-xs">
                        @forelse($announcements as $ann)
                        <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200/70">
                            <div class="text-[10px] text-slate-400 mb-1">
                                {{ \Carbon\Carbon::parse($ann->created_at)->locale('id')->isoFormat('D MMMM Y') }}
                            </div>
                            <h4 class="font-bold text-slate-900 mb-1 leading-snug">{{ $ann->title }}</h4>
                            <p class="text-slate-600 leading-relaxed">{{ $ann->content }}</p>
                        </div>
                        @empty
                        <div class="p-4 rounded-lg bg-slate-50 text-center text-slate-400 text-xs">
                            Belum ada pengumuman kampus saat ini.
                        </div>
                        @endforelse
                    </div>
                </div>

                @if(Auth::check() && Auth::user()->role === 'mahasiswa')
                <div class="bg-white rounded-xl p-5 border border-slate-200 space-y-3">
                    <h3 class="text-sm font-bold text-slate-900">Tiket &amp; E-Sertifikat</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Akses kode QR tiket acara yang sudah Anda daftar dan cetak e-sertifikat setelah kehadiran diverifikasi.
                    </p>
                    <a href="{{ route('my.events') }}" class="btn-tactile active:scale-[0.98] block w-full py-2.5 text-center rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition">
                        Buka Tiket Saya
                    </a>
                </div>
                @endif

                <div class="bg-white rounded-xl p-5 border border-slate-200 space-y-2.5">
                    <h4 class="text-sm font-bold text-slate-900">Penyelenggara Organisasi?</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Kelola publikasi kegiatan BEM, himpunan, atau UKM serta pantau presensi peserta secara langsung.
                    </p>
                    @if(Auth::check() && Auth::user()->role === 'penyelenggara')
                        <a href="{{ route('organizer.dashboard') }}" class="inline-block text-xs font-semibold text-indigo-600 hover:underline">
                            Masuk ke Portal Penyelenggara →
                        </a>
                    @elseif(Auth::check() && Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="inline-block text-xs font-semibold text-indigo-600 hover:underline">
                            Masuk ke Dashboard Admin →
                        </a>
                    @elseif(Auth::check() && Auth::user()->role === 'mahasiswa')
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Anda sedang masuk sebagai <strong>Mahasiswa</strong>. Untuk mengakses Portal Penyelenggara, silakan keluar lalu masuk menggunakan akun Penyelenggara yang diberikan Admin.
                        </p>
                        <button type="button" onclick="openLogoutModal()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:underline">
                            <span>Keluar &amp; Ganti Akun →</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="inline-block text-xs font-semibold text-indigo-600 hover:underline">
                            Login Penyelenggara →
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')
@endsection

@push('scripts')
<script>
    function showAllHomeEvents() {
        const extraCards = document.querySelectorAll('.extra-event-card');
        extraCards.forEach((el, idx) => {
            el.classList.remove('hidden');
            el.style.animationDelay = Math.min(idx * 50, 300) + 'ms';
            el.classList.add('reveal-extra-card');

            // Fill any quota progress bars inside newly revealed cards
            el.querySelectorAll('.quota-progress-bar[data-target-width]').forEach((bar) => {
                requestAnimationFrame(() => {
                    bar.style.width = bar.getAttribute('data-target-width') + '%';
                });
            });
        });

        const wrapper = document.getElementById('showMoreEventsWrapper');
        if (wrapper) wrapper.remove();
    }
</script>
@endpush
