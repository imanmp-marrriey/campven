@extends('layouts.app')

@section('title', 'Campven - Platform Kegiatan Kampus Terpadu')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endpush

@section('content')
<!-- ===== TOP OFFICIAL SUB-HEADER ===== -->
<div class="bg-slate-900 text-white text-[11px] font-semibold py-2 px-4 border-b border-slate-800">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 font-bold text-[10px] uppercase tracking-wider border border-indigo-500/30">
                <i class="fas fa-university text-[9px]"></i> PORTAL RESMI KEMAHASISWAAN
            </span>
            <span class="text-slate-300 hidden sm:inline">• Semester Genap 2025/2026</span>
            <span class="text-slate-300 hidden md:inline">• <i class="fas fa-certificate text-emerald-400 mr-1"></i> SKPI Terintegrasi</span>
        </div>
        <div class="flex items-center gap-4 text-slate-300 text-[10px] sm:text-[11px]">
            <span><i class="fas fa-clock text-indigo-400 mr-1"></i> Sinkronisasi: Baru saja</span>
            <span class="text-slate-600">|</span>
            <a href="#announcements" class="text-indigo-300 hover:text-white transition">Pemberitahuan Resmi</a>
        </div>
    </div>
</div>

<!-- ===== MAIN NAVBAR ===== -->
<nav class="sticky top-0 z-50 glass-nav transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-xl shadow-md shadow-indigo-600/20 group-hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-cubes text-sm"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold text-slate-900 tracking-tight">Camp<span class="text-indigo-600">ven</span></span>
                    <span class="block text-[10px] font-semibold text-slate-400 tracking-wider -mt-1">Platform Kegiatan Kampus</span>
                </div>
            </a>

            <!-- Search Quick Trigger (⌘K) -->
            <div class="hidden lg:flex items-center gap-2 bg-slate-100/80 border border-slate-200/80 rounded-full px-4 py-2 w-72 text-slate-400 text-xs">
                <i class="fas fa-search text-slate-400"></i>
                <input type="text" placeholder="Cari kegiatan kampus..." class="bg-transparent text-slate-700 w-full focus:outline-none placeholder-slate-400 text-xs" onfocus="document.getElementById('mainSearchInput').scrollIntoView({behavior: 'smooth'})">
                <kbd class="px-1.5 py-0.5 rounded bg-white border border-slate-200 text-[10px] font-bold text-slate-500 shadow-2xs">⌘K</kbd>
            </div>

            <!-- Desktop Nav Links -->
            <div class="hidden md:flex items-center gap-1.5 bg-slate-100/70 p-1 rounded-full border border-slate-200/60">
                <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-full text-xs font-bold bg-indigo-600 text-white shadow-sm transition">
                    Jelajahi Event
                </a>
                <a href="#categories" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                    Kategori
                </a>
                <a href="#announcements" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                    Pengumuman
                </a>
                @if($is_logged_in && $user->role === 'mahasiswa')
                <a href="{{ route('my.events') }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                    Tiket Saya
                </a>
                @endif
                <a href="{{ $is_logged_in && $user->role === 'penyelenggara' ? route('organizer.dashboard') : ($is_logged_in && $user->role === 'admin' ? route('admin.dashboard') : route('login')) }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
                    Portal Penyelenggara
                </a>
            </div>

            <!-- Right Profile / Action -->
            <div class="flex items-center gap-3">
                <button type="button" class="relative w-9 h-9 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center text-sm transition" title="Notifikasi">
                    <i class="far fa-bell"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                </button>

                @if($is_logged_in)
                    <div class="relative group">
                        <a href="{{ route('profile') }}" class="flex items-center gap-2.5 p-1 rounded-full border border-slate-200 bg-white hover:bg-slate-50 transition shadow-2xs">
                            <div class="w-8 h-8 rounded-full overflow-hidden bg-slate-800 text-white flex items-center justify-center font-bold text-xs">
                                @if($user->foto && Storage::disk('public')->exists($user->foto))
                                    <img src="{{ Storage::url($user->foto) }}" alt="Foto" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                @endif
                            </div>
                            <div class="hidden sm:block text-left pr-2">
                                <span class="block text-xs font-bold text-slate-900 leading-none truncate max-w-[100px]">{{ $user->nama }}</span>
                                <span class="text-[10px] font-medium text-slate-400 capitalize">{{ $user->role }}</span>
                            </div>
                        </a>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 hover:text-slate-900 px-3 py-2 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2.5 rounded-full shadow-md shadow-indigo-600/20 transition btn-spring">
                        Daftar Akun
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>

<!-- ===== HERO DISCOVERY SECTION ===== -->
<section class="pt-12 pb-16 bg-gradient-to-b from-white via-slate-50 to-slate-100/70 border-b border-slate-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Floating Pill Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-slate-200/80 text-slate-700 text-xs font-bold mb-6 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Portal Resmi Kegiatan Mahasiswa Berbobot SKPI</span>
        </div>

        <!-- Headline -->
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15] max-w-4xl mx-auto">
            Temukan, Ikuti, dan Rasakan <span class="text-indigo-600">Kehidupan Kampus.</span>
        </h1>
        <p class="mt-4 text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
            Platform resmi manajemen dan registrasi kegiatan kampus, seminar nasional, kompetisi, dan workshop mahasiswa dengan validasi sertifikat instan.
        </p>
            Platform resmi manajemen dan registrasi kegiatan kampus, seminar nasional, kompetisi, dan workshop mahasiswa dengan validasi sertifikat instan.
        </p>

        <!-- 4-Segment Floating Search Bar -->
        <div class="mt-8 max-w-4xl mx-auto">
            <form method="GET" action="{{ route('home') }}" class="bg-white p-2 sm:p-2.5 rounded-3xl shadow-xl shadow-slate-200/80 border border-slate-200 flex flex-col md:flex-row items-center gap-2">
                <!-- Segment 1: Keyword Search -->
                <div class="flex items-center gap-3 px-4 py-2 w-full md:w-2/5">
                    <i class="fas fa-search text-slate-400 text-sm"></i>
                    <input type="text" 
                           id="mainSearchInput"
                           name="search" 
                           value="{{ $search ?? '' }}" 
                           placeholder="Cari nama event, pembicara, topik..." 
                           class="w-full bg-transparent text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none">
                    @if(!empty($search))
                    <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-600 p-1" title="Hapus">
                        <i class="fas fa-times-circle text-xs"></i>
                    </a>
                    @endif
                </div>

                <!-- Segment 2: Time Range Filter -->
                <div class="hidden sm:flex items-center gap-2 px-3 py-2 w-full md:w-1/4 border-t md:border-t-0 md:border-l border-slate-200 text-xs text-slate-600">
                    <i class="far fa-calendar-alt text-slate-400"></i>
                    <select name="rentang" class="bg-transparent w-full text-xs text-slate-700 font-semibold focus:outline-none cursor-pointer">
                        <option value="">Pilih Rentang Waktu</option>
                        <option value="minggu_ini" {{ ($rentang ?? '') === 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan_ini" {{ ($rentang ?? '') === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="semester" {{ ($rentang ?? '') === 'semester' ? 'selected' : '' }}>Semester Ini</option>
                    </select>
                </div>

                <!-- Segment 3: Location / Building Filter -->
                <div class="hidden sm:flex items-center gap-2 px-3 py-2 w-full md:w-1/4 border-t md:border-t-0 md:border-l border-slate-200 text-xs text-slate-600">
                    <i class="fas fa-map-marker-alt text-slate-400"></i>
                    <select name="lokasi_filter" class="bg-transparent w-full text-xs text-slate-700 font-semibold focus:outline-none cursor-pointer">
                        <option value="">Lokasi & Gedung</option>
                        <option value="Auditorium" {{ ($lokasi_filter ?? '') === 'Auditorium' ? 'selected' : '' }}>Auditorium Utama</option>
                        <option value="GSC" {{ ($lokasi_filter ?? '') === 'GSC' ? 'selected' : '' }}>Gedung GSC Polindra</option>
                        <option value="Lab" {{ ($lokasi_filter ?? '') === 'Lab' ? 'selected' : '' }}>Lab Komputer / Multimedia</option>
                        <option value="Aula" {{ ($lokasi_filter ?? '') === 'Aula' ? 'selected' : '' }}>Aula Utama</option>
                        <option value="Online" {{ ($lokasi_filter ?? '') === 'Online' ? 'selected' : '' }}>Online / Zoom</option>
                    </select>
                </div>

                <!-- Segment 4: Search Submit Button -->
                <div class="w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition-all btn-spring flex items-center justify-center gap-2">
                        <span>Cari Event</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Category Pills Bar -->
        <div class="mt-6 flex items-center justify-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all {{ empty($kategori) ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                Semua
            </a>
            @foreach($categories as $cat)
            @php $isActive = ($kategori === $cat->nama); @endphp
            <a href="{{ route('home', ['kategori' => $cat->nama]) }}" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 flex-shrink-0 {{ $isActive ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                <i class="fas {{ $cat->icon ?? 'fa-tag' }} text-[11px]"></i>
                <span>{{ $cat->nama }}</span>
            </a>
            @endforeach
        </div>

        <!-- Quick Info Ticker Bar -->
        <div class="mt-8 flex flex-wrap items-center justify-center gap-6 sm:gap-10 text-xs font-semibold text-slate-500 pt-6 border-t border-slate-200/80">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span><strong>{{ $total_events }}+ Events Terdaftar</strong> (Bulan ini)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                <span><strong>{{ $total_users }}+ Peserta</strong> (Aktif terverifikasi)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                <span><strong>32 UKM & Himpunan</strong> (Terakreditasi)</span>
            </div>
            <div class="flex items-center gap-2 hidden md:flex">
                <i class="fas fa-sync-alt text-slate-400 text-[10px]"></i>
                <span>Sistem sinkronisasi jadwal kampus terbaik</span>
            </div>
        </div>
    </div>
</section>

<!-- ===== SOROTAN UNGGULAN KAMPUS (WIDE FEATURED CARDS) ===== -->
@if(count($featured_events) > 0 && empty($search) && empty($kategori))
<section class="py-12 bg-white border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 flex items-center gap-2">
                    <span class="w-1.5 h-6 rounded-full bg-indigo-600 inline-block"></span>
                    <span>Sorotan Unggulan Kampus</span>
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Kegiatan bergengsi dengan kuota terbatas dan poin SKPI maksimal.</p>
            </div>
        </div>

        <!-- 2 Wide Featured Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($featured_events->take(2) as $fe)
            @php
                $sisa = $fe->sisaKuota();
                $percent = $fe->persentaseTerisi();
                $price = $fe->formattedHarga();
            @endphp
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 p-6 sm:p-7 flex flex-col justify-between relative overflow-hidden group">
                <!-- Badges Header -->
                <div>
                    <div class="flex items-center gap-2 flex-wrap mb-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                            {{ $fe->kategori }}
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                            <i class="fas fa-check-circle text-[9px]"></i> Verified by {{ $fe->creator->nama ?? 'Penyelenggara Resmi' }}
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-1">
                            <i class="fas fa-hourglass-half text-[9px]"></i> Mendatang
                        </span>
                    </div>

                    <a href="{{ route('event.detail', $fe->id) }}" class="block group-hover:text-indigo-600 transition-colors">
                        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-tight mb-2">
                            {{ $fe->nama_event }}
                        </h3>
                    </a>

                    <p class="text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed mb-6">
                        {{ $fe->deskripsi }}
                    </p>

                    <!-- Meta Badges 3 Columns -->
                    <div class="grid grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs mb-6">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Waktu & Tanggal</span>
                            <span class="font-extrabold text-slate-800 block truncate">{{ \Carbon\Carbon::parse($fe->tanggal)->format('d M Y') }}</span>
                            <span class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($fe->waktu_mulai)->format('H:i') ?? '09:00' }} WIB</span>
                        </div>
                        <div class="border-l border-slate-200 pl-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Tempat</span>
                            <span class="font-extrabold text-slate-800 block truncate">{{ $fe->lokasi }}</span>
                            <span class="text-[10px] text-slate-500">Kampus Utama</span>
                        </div>
                        <div class="border-l border-slate-200 pl-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Nilai Kredit</span>
                            <span class="font-extrabold text-indigo-600 block">+10 SKPI Poin</span>
                            <span class="text-[10px] text-slate-500">Sertifikat Digital</span>
                        </div>
                    </div>

                    <!-- Quota Progress -->
                    @if($fe->kuota)
                    <div class="mb-6">
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-slate-600">Sisa Kuota: <strong class="text-rose-600">{{ $sisa }} Kursi</strong></span>
                            <span class="text-indigo-600 font-extrabold">{{ $percent }}% Terisi</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-700 rounded-full" style="width: {{ $percent }}%;"></div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Price and CTA -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <div class="text-lg font-black {{ $price === 'GRATIS' ? 'text-emerald-600' : 'text-slate-900' }}">
                        {{ $price }}
                    </div>
                    <a href="{{ route('event.detail', $fe->id) }}" class="px-6 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition-all btn-spring flex items-center gap-2">
                        <span>Daftar Sekarang</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===== 2-COLUMN MAIN EVENT DISCOVERY SECTION ===== -->
<section id="events" class="py-14 bg-slate-50/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Title & Sorter -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    @if(!empty($search))
                        Hasil Pencarian: "{{ $search }}"
                    @elseif(!empty($kategori))
                        Agenda Kampus: {{ $kategori }}
                    @else
                        Jelajahi Agenda Kampus
                    @endif
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Menampilkan kegiatan terverifikasi dan terakreditasi kampus.</p>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-center">
                <span class="text-xs text-slate-400 font-semibold">Urutkan:</span>
                <select class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 focus:outline-none cursor-pointer">
                    <option>Paling Mendekati</option>
                    <option>Kuota Terbanyak</option>
                    <option>Paling Populer</option>
                </select>
            </div>
        </div>

        <!-- 2-Column Split (Left: 68%, Right: 32%) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Event Grid (8 Cols) -->
            <div class="lg:col-span-8 space-y-8">
                @if(count($events) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($events as $event)
                        @include('mahasiswa.partials.event-card', ['event' => $event, 'animate' => false])
                    @endforeach
                </div>

                <!-- Load More Button -->
                <div class="text-center pt-4">
                    <button type="button" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-sm transition btn-spring">
                        <i class="fas fa-redo-alt text-xs text-slate-400"></i>
                        <span>Muat Lebih Banyak Kegiatan Kampus</span>
                    </button>
                </div>
                @else
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Tidak Ada Event</h3>
                    <p class="text-xs text-slate-500 mb-6">Belum ada kegiatan yang cocok dengan kriteria pencarian Anda.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition btn-spring">
                        <i class="fas fa-undo"></i> Reset Filter
                    </a>
                </div>
                @endif
            </div>

            <!-- Right Column: Official Notices & Sidebars (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Pemberitahuan Resmi (Penting) -->
                <div id="announcements" class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-bullhorn text-indigo-600"></i>
                            <span>Pemberitahuan Resmi</span>
                        </h3>
                        <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 font-extrabold text-[10px] uppercase">
                            Penting
                        </span>
                    </div>

                    <div class="space-y-3.5 text-xs">
                        @forelse($announcements as $ann)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-200 transition">
                            <div class="flex items-center justify-between text-[10px] text-slate-400 mb-1">
                                <span class="font-bold text-indigo-600 uppercase tracking-wider">PENGUMUMAN RESMI</span>
                                <span>{{ \Carbon\Carbon::parse($ann->created_at)->format('d M Y') }}</span>
                            </div>
                            <h4 class="font-bold text-slate-900 mb-1 leading-snug">{{ $ann->judul }}</h4>
                            <p class="text-slate-500 text-[11px] leading-relaxed">{{ $ann->isi }}</p>
                        </div>
                        @empty
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center text-slate-400 text-xs">
                            <i class="fas fa-info-circle mb-1 block text-base text-indigo-400"></i>
                            Belum ada pengumuman kampus saat ini.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Purple Calendar Reminder Promo Card -->
                <div class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-slate-900 rounded-3xl p-6 text-white shadow-xl shadow-indigo-600/20 space-y-4">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-lg">
                        <i class="far fa-bell"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold leading-snug">Jangan Ketinggalan Tiket</h3>
                        <p class="text-xs text-indigo-100 mt-1 leading-relaxed">
                            Dapatkan notifikasi pembukaan tiket event gratis 1 jam sebelum kuota habis melalui broadcast resmi Campven.
                        </p>
                    </div>

                    <form onsubmit="event.preventDefault(); const val = document.getElementById('reminderWaInput').value; if(val){ alert('Pengingat Berhasil Diaktifkan! Kami akan mengirim notifikasi pendaftaran ke nomor ' + val); document.getElementById('reminderWaInput').value=''; } else { alert('Mohon masukkan nomor WhatsApp Anda terlebih dahulu.'); }" class="space-y-2">
                        <input type="text" id="reminderWaInput" placeholder="Ketik Nomor WhatsApp..." class="w-full px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-xs text-white placeholder-indigo-200 focus:outline-none focus:bg-white/20">
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-white text-indigo-700 font-bold text-xs hover:bg-indigo-50 shadow-sm transition btn-spring">
                            Aktifkan Pengingat Kalender
                        </button>
                    </form>
                </div>

                <!-- Organizer Callout Banner -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Punya Agenda UKM / Himpunan?</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                            Buat pendaftaran, verifikasi tiket QR code, dan unduh rekapan peserta secara otomatis.
                        </p>
                        <a href="{{ route('login') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition inline-flex items-center gap-1 mt-2">
                            <span>Masuk Portal Penyelenggara</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Helpdesk 24/7 Footer Link -->
                <div class="text-center pt-2">
                    <a href="mailto:support@campven.com" class="text-xs font-semibold text-slate-400 hover:text-slate-600 transition flex items-center justify-center gap-1.5">
                        <i class="fas fa-headset text-indigo-500"></i>
                        <span>Kendala Tiket / SKPI? Layanan Bantuan 24/7</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== 4-COLUMN MODERN FOOTER (INDONESIAN) ===== -->
<footer class="bg-white text-slate-600 py-16 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-slate-100">
            <!-- Col 1: Brand Info -->
            <div class="space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <span class="text-lg font-extrabold text-slate-900 tracking-tight">Camp<span class="text-indigo-600">ven</span></span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs">
                    Ekosistem terpadu manajemen kegiatan, pendaftaran tiket, dan sertifikasi kemahasiswaan kampus.
                </p>
            </div>

            <!-- Col 2: Ekosistem -->
            <div class="space-y-2.5 text-xs">
                <h4 class="font-extrabold text-slate-900 text-xs tracking-wider uppercase">Ekosistem Kampus</h4>
                <ul class="space-y-2 text-slate-500">
                    <li><a href="#" class="hover:text-indigo-600 transition">Simposium Akademik</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Festival Kampus</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Orientasi UKM & Organisasi</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Kompetisi & Olahraga</a></li>
                </ul>
            </div>

            <!-- Col 3: Bantuan & Layanan -->
            <div class="space-y-2.5 text-xs">
                <h4 class="font-extrabold text-slate-900 text-xs tracking-wider uppercase">Bantuan & Layanan</h4>
                <ul class="space-y-2 text-slate-500">
                    <li><a href="#" class="hover:text-indigo-600 transition">Pusat Bantuan Mahasiswa</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Verifikasi Tiket & Barcode</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Panduan Penyelenggara</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Peminjaman Sarana Prasarana</a></li>
                </ul>
            </div>

            <!-- Col 4: Akreditasi -->
            <div class="space-y-2.5 text-xs">
                <h4 class="font-extrabold text-slate-900 text-xs tracking-wider uppercase">Akreditasi & Regulasi</h4>
                <p class="text-slate-500 leading-relaxed text-[11px]">
                    Sesuai dengan Regulasi Dewan Tata Kelola & Direktorat Kemahasiswaan Kampus Tahun 2026.
                </p>
            </div>
        </div>

        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <div>© {{ date('Y') }} Platform Manajemen Campven. Seluruh hak cipta dilindungi.</div>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-slate-600 transition">Keamanan Kampus</a>
                <a href="#" class="hover:text-slate-600 transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-slate-600 transition">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>
@endsection
