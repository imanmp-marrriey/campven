@extends('layouts.app')

@section('title', $event->nama_event . ' - Campven')

@section('content')
<!-- ===== TOP MAIN NAVBAR ===== -->
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
                    <span class="block text-[10px] font-semibold text-slate-400 tracking-wider -mt-1">Pusat Kegiatan & Tiket Kampus</span>
                </div>
            </a>

            <!-- Search Quick Trigger (⌘K) -->
            <div class="hidden lg:flex items-center gap-2 bg-slate-100/80 border border-slate-200/80 rounded-full px-4 py-2 w-72 text-slate-400 text-xs">
                <i class="fas fa-search text-slate-400"></i>
                <input type="text" placeholder="Cari acara kampus..." class="bg-transparent text-slate-700 w-full focus:outline-none placeholder-slate-400 text-xs" onfocus="window.location='{{ route('home') }}'">
                <kbd class="px-1.5 py-0.5 rounded bg-white border border-slate-200 text-[10px] font-bold text-slate-500 shadow-2xs">⌘K</kbd>
            </div>

            <!-- Desktop Nav Links -->
            <div class="hidden md:flex items-center gap-1.5 bg-slate-100/70 p-1 rounded-full border border-slate-200/60">
                <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-full text-xs font-bold bg-indigo-600 text-white shadow-sm transition">
                    Jelajahi Acara
                </a>
                <a href="{{ route('home') }}#categories" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                    Kategori
                </a>
                <a href="{{ route('home') }}#announcements" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                    Pengumuman
                </a>
                @if(Auth::check() && Auth::user()->role === 'mahasiswa')
                <a href="{{ route('my.events') }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                    Tiket Saya
                </a>
                @endif
                <a href="{{ Auth::check() && Auth::user()->role === 'penyelenggara' ? route('organizer.dashboard') : (Auth::check() && Auth::user()->role === 'admin' ? route('admin.dashboard') : route('login')) }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
                    Portal Penyelenggara
                </a>
            </div>

            <!-- Right Profile / Action -->
            <div class="flex items-center gap-3">
                @if(Auth::check())
                    <a href="{{ route('profile') }}" class="flex items-center gap-2.5 p-1 rounded-full border border-slate-200 bg-white hover:bg-slate-50 transition shadow-2xs">
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-slate-800 text-white flex items-center justify-center font-bold text-xs">
                            @if(Auth::user()->foto && Storage::disk('public')->exists(Auth::user()->foto))
                                <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                            @endif
                        </div>
                        <div class="hidden sm:block text-left pr-2">
                            <span class="block text-xs font-bold text-slate-900 leading-none truncate max-w-[100px]">{{ Auth::user()->nama }}</span>
                            <span class="text-[10px] font-medium text-slate-400 capitalize">{{ Auth::user()->role }}</span>
                        </div>
                    </a>
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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-slate-700 transition">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <a href="{{ route('home', ['kategori' => $event->kategori]) }}" class="hover:text-slate-700 transition">{{ $event->kategori }}</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-slate-900 font-bold truncate max-w-sm">{{ $event->nama_event }}</span>
    </nav>

    <!-- Cinematic Poster Hero Banner (Matching Image 2) -->
    <div class="relative w-full h-80 sm:h-[420px] rounded-3xl overflow-hidden bg-slate-950 shadow-2xl mb-6 group">
        @if($event->poster && file_exists(public_path('storage/' . $event->poster)))
            <img src="{{ asset('storage/' . $event->poster) }}" 
                 alt="{{ $event->nama_event }}" 
                 class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700 ease-out">
        @else
            <!-- Fallback High-Fidelity Banner -->
            <div class="w-full h-full bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-900 flex items-center justify-center p-8 relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
                <div class="text-center text-white/90">
                    <i class="fas fa-calendar-alt text-6xl text-indigo-400 mb-4 opacity-80"></i>
                    <p class="text-xs uppercase font-extrabold tracking-widest text-indigo-300">{{ $event->kategori }}</p>
                </div>
            </div>
        @endif

        <!-- Dark Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-black/30"></div>

        <!-- Top Floating Badges -->
        <div class="absolute top-6 left-6 right-6 flex items-center justify-between gap-4">
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/10 backdrop-blur-md text-white border border-white/20 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                Pendaftaran Online Resmi Dibuka
            </span>

            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-600/90 backdrop-blur-md text-white border border-indigo-400/40 flex items-center gap-1.5">
                <i class="fas fa-certificate text-amber-300"></i>
                SKPI Terakreditasi +10 Poin
            </span>
        </div>

        <!-- Bottom Title Inside Hero Banner -->
        <div class="absolute bottom-6 left-6 right-6 max-w-3xl">
            <span class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-300 block mb-2">
                EVENT TERKONFIRMASI • SEMESTER GENAP
            </span>
            <h1 class="text-2xl sm:text-4xl font-black text-white leading-tight drop-shadow-lg">
                {{ $event->nama_event }}
            </h1>
        </div>
    </div>

    <!-- Organizer Bar & Social Proof Below Hero (Matching Image 2) -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-10">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-700 text-lg font-bold flex-shrink-0">
                <i class="fas fa-university"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-extrabold text-slate-900">{{ $event->creator->nama ?? 'Penyelenggara Kampus' }}</h3>
                    <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-bold text-[10px]">Penyelenggara Resmi</span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">{{ $event->lokasi }}</p>
            </div>
        </div>

        <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-600">
                <div class="flex -space-x-1.5 overflow-hidden">
                    <span class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-slate-800 text-white text-[9px] font-bold flex items-center justify-center">A</span>
                    <span class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-indigo-600 text-white text-[9px] font-bold flex items-center justify-center">B</span>
                    <span class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-emerald-600 text-white text-[9px] font-bold flex items-center justify-center">+{{ $event->participantCount() }}</span>
                </div>
                <span>Telah bergabung <strong>{{ $event->participantCount() }}</strong> mahasiswa</span>
            </div>

            <a href="mailto:{{ $event->creator->email ?? 'info@campven.com' }}" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition btn-spring">
                Hubungi Panitia
            </a>
        </div>
    </div>

    <!-- Main Content 2-Column Split (Left: 65%, Right: 35%) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
        
        <!-- Left Content Column (8 Cols) -->
        <div class="lg:col-span-8 space-y-10">
            
            <!-- Tentang Acara -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-4">
                <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-indigo-600 inline-block"></span>
                    <span>Tentang Acara</span>
                </h2>
                <div class="text-sm text-slate-600 leading-relaxed space-y-3 prose max-w-none">
                    {!! nl2br(e($event->deskripsi)) !!}
                </div>
            </div>

            <!-- Benefit Eksklusif Peserta (4 Grid Cards) -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-indigo-600 inline-block"></span>
                    <span>Benefit Eksklusif Peserta</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center flex-shrink-0 text-base shadow-sm">
                            <i class="fas fa-award"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Sertifikat Berbobot SKPI</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Pengakuan resmi rektorat bernilai 10 Poin SKPI kelulusan.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-100 flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 text-base shadow-sm">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Snack & Buffet Lunch</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Coffee break saat sesi dan makan siang gratis di venue.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center flex-shrink-0 text-base shadow-sm">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Tech Networking Session</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Sesi koneksi dengan mentor industri, alumni, dan pimpinan himpunan.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-purple-50/60 border border-purple-100 flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0 text-base shadow-sm">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Repository Materi & Dataset</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Akses penuh ke e-book materi dan source code demo pembicara.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pembicara / Pemateri Utama -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-indigo-600 inline-block"></span>
                        <span>Narasumber & Pemateri Acara</span>
                    </h3>
                    <span class="text-xs font-bold text-indigo-600">Instruktur / Keynote</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl border border-slate-200 flex items-center gap-3.5 hover:border-indigo-300 transition">
                        <div class="w-12 h-12 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-base flex-shrink-0">
                            {{ strtoupper(substr($event->creator->nama ?? 'P', 0, 1)) }}
                        </div>
                        <div>
                            <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-extrabold text-[9px] uppercase tracking-wider">Pemateri Utama</span>
                            <h4 class="text-xs font-extrabold text-slate-900 mt-1">{{ $event->creator->nama ?? 'Narasumber Ahli' }}</h4>
                            <p class="text-[11px] text-slate-500">{{ $event->lokasi }}</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl border border-slate-200 flex items-center gap-3.5 hover:border-indigo-300 transition">
                        <div class="w-12 h-12 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-base flex-shrink-0">
                            <i class="fas fa-certificate text-indigo-400"></i>
                        </div>
                        <div>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-extrabold text-[9px] uppercase tracking-wider">Akreditasi</span>
                            <h4 class="text-xs font-extrabold text-slate-900 mt-1">Sertifikat Resmi Kampus</h4>
                            <p class="text-[11px] text-slate-500">Terdaftar di Buku Panduan Akademik</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rundown Acara (Vertical Timeline) -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-indigo-600 inline-block"></span>
                        <span>Rundown Acara</span>
                    </h3>
                    <span class="text-xs text-slate-400 font-semibold">Zona Waktu: WIB (UTC+7)</span>
                </div>

                <div class="relative border-l-2 border-indigo-100 ml-4 space-y-6 text-xs">
                    <div class="relative pl-6">
                        <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white border-4 border-indigo-600"></span>
                        <div class="font-bold text-slate-400">08:30 - 09:00 WIB</div>
                        <h4 class="font-extrabold text-slate-900 text-sm mt-0.5">Registrasi & Welcome Coffee</h4>
                        <p class="text-slate-500 text-[11px] mt-0.5">Check-in barcode peserta di meja presensi gerbang masuk.</p>
                    </div>

                    <div class="relative pl-6">
                        <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white border-4 border-indigo-600"></span>
                        <div class="flex items-center gap-2 font-bold text-indigo-600">
                            <span>09:00 - 10:30 WIB</span>
                            <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[9px] font-bold">Live Demo</span>
                        </div>
                        <h4 class="font-extrabold text-slate-900 text-sm mt-0.5">Keynote Session: Modern AI & Scalable Web Architecture</h4>
                        <p class="text-slate-500 text-[11px] mt-0.5">Pemaparan materi utama, perancangan sistem agentic, dan arsitektur microservices.</p>
                    </div>

                    <div class="relative pl-6">
                        <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white border-4 border-indigo-600"></span>
                        <div class="font-bold text-slate-400">10:45 - 12:15 WIB</div>
                        <h4 class="font-extrabold text-slate-900 text-sm mt-0.5">Hands-on Case Study & Live Q&A</h4>
                        <p class="text-slate-500 text-[11px] mt-0.5">Sesi interaktif tanya jawab serta bedah arsitektur studi kasus riil.</p>
                    </div>

                    <div class="relative pl-6">
                        <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white border-4 border-emerald-500"></span>
                        <div class="font-bold text-emerald-600">13:00 - 14:30 WIB</div>
                        <h4 class="font-extrabold text-slate-900 text-sm mt-0.5">Panel Discussion & Penyerahan SKPI</h4>
                        <p class="text-slate-500 text-[11px] mt-0.5">Penutupan acara, foto bersama, dan penerbitan bukti kehadiran SKPI.</p>
                    </div>
                </div>
            </div>

            <!-- Lokasi & Akses Kampus -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-4">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-indigo-600 inline-block"></span>
                    <span>Lokasi & Akses Kampus</span>
                </h3>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-indigo-600 uppercase">TEMPAT ACARA</span>
                        <h4 class="text-sm font-extrabold text-slate-900 mt-0.5">{{ $event->lokasi }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Gedung Kampus Utama • Akses lift tersedia di lobi utama.</p>
                        <div class="flex items-center gap-2 mt-3 text-[11px]">
                            <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 font-semibold"><i class="fas fa-chair mr-1"></i> Kursi Ergonomis</span>
                            <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 font-semibold"><i class="fas fa-parking mr-1"></i> Parkir Gratis</span>
                        </div>
                    </div>

                    <a href="https://maps.google.com/?q={{ urlencode($event->lokasi) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold text-xs transition btn-spring flex items-center gap-1.5 flex-shrink-0">
                        <i class="fas fa-external-link-alt text-indigo-600"></i>
                        <span>Buka di Maps</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Sticky Action Column (4 Cols - Matching Image 2) -->
        <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xl shadow-indigo-600/5 space-y-6">
                <!-- Category / Pricing Switcher -->
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">KATEGORI TIKET MASUK</span>
                    <div class="grid grid-cols-2 gap-2 p-1 rounded-2xl bg-slate-100 text-xs font-bold text-center">
                        <button type="button" class="py-2 rounded-xl bg-white text-indigo-700 shadow-xs">
                            Internal Kampus
                        </button>
                        <button type="button" class="py-2 rounded-xl text-slate-500 hover:text-slate-800">
                            Umum / Luar
                        </button>
                    </div>
                </div>

                <!-- Price Display -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="block text-[10px] font-bold uppercase text-slate-400">Total Biaya Registrasi</span>
                        <div class="text-2xl font-black text-slate-900 mt-0.5">
                            {{ $event->formattedHarga() }}
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-[10px]">
                        Subsidi Kampus
                    </span>
                </div>

                <!-- Schedule & Venue Details -->
                <div class="space-y-3 text-xs text-slate-600 pt-2 border-t border-slate-100">
                    <div class="flex items-start gap-3">
                        <i class="far fa-calendar-alt text-indigo-600 mt-0.5"></i>
                        <div>
                            <span class="font-extrabold text-slate-900 block">{{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                            <span class="text-slate-500 text-[11px]">{{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') ?? '09:00' }} - {{ \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i') ?? '15:00' }} WIB</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-rose-500 mt-0.5"></i>
                        <div>
                            <span class="font-extrabold text-slate-900 block">{{ $event->lokasi }}</span>
                            <span class="text-slate-500 text-[11px]">Kampus Terpadu</span>
                        </div>
                    </div>
                </div>

                <!-- Sisa Kuota Meter -->
                @if($event->kuota)
                @php
                    $sisa = $event->sisaKuota();
                    $percent = $event->persentaseTerisi();
                    $isFull = $event->isFull();
                @endphp
                <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/80 space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-amber-800"><i class="fas fa-fire mr-1 text-amber-600"></i> Sisa Kuota Terbatas!</span>
                        <span class="text-amber-900">{{ $sisa }} Kursi Tersedia</span>
                    </div>
                    <div class="w-full h-2 bg-amber-200/70 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: {{ $percent }}%;"></div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-amber-700">
                        <span>{{ $percent }}% Terisi</span>
                        <span>Total {{ $event->kuota }} Kuota</span>
                    </div>
                </div>
                @endif

                <!-- Primary Action CTA -->
                @if(Auth::check() && $is_joined)
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-center space-y-2">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-lg font-bold">
                        <i class="fas fa-check"></i>
                    </div>
                    <h4 class="text-xs font-bold text-emerald-900">Tiket Terdaftar!</h4>
                    <p class="text-[11px] text-emerald-700">Status Anda saat ini: <strong class="capitalize">{{ $participant_status ?? 'Registered' }}</strong></p>
                    <a href="{{ route('my.events') }}" class="block w-full py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition">
                        Buka Tiket Saya
                    </a>
                </div>
                @elseif(Auth::check() && $event->isFull())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-center text-xs font-bold text-rose-700">
                    <i class="fas fa-ban mr-1"></i> Kuota Penuh (Pendaftaran Ditutup)
                </div>
                @elseif(Auth::check() && !$is_joined)
                <form method="POST" action="{{ route('event.register', $event->id) }}" class="space-y-3">
                    @csrf
                    <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-indigo-600/30 transition-all btn-spring flex items-center justify-center gap-2">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Daftar Event Ini Sekarang</span>
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="block w-full py-3.5 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs sm:text-sm text-center shadow-lg shadow-indigo-600/30 transition btn-spring">
                    Masuk untuk Mendaftar
                </a>
                @endif

                <!-- Action Button Row: Share & Calendar -->
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" onclick="copyDetailLink()" class="py-2 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 font-bold text-slate-700 flex items-center justify-center gap-1.5 transition">
                        <i class="fas fa-share-alt text-slate-400"></i>
                        <span id="detailShareText">Bagikan</span>
                    </button>
                    <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text={{ urlencode($event->nama_event) }}&dates={{ date('Ymd', strtotime($event->tanggal)) }}/{{ date('Ymd', strtotime($event->tanggal)) }}&details={{ urlencode($event->deskripsi) }}&location={{ urlencode($event->lokasi) }}" target="_blank" class="py-2 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 font-bold text-slate-700 flex items-center justify-center gap-1.5 transition">
                        <i class="far fa-calendar-plus text-slate-400"></i>
                        <span>e-Kalender</span>
                    </a>
                </div>

                <!-- Feature Verification Checklist -->
                <div class="pt-4 border-t border-slate-100 space-y-2 text-[11px] text-slate-600">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                        <span>Sertifikat otomatis terbit di akun setelah check-in</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                        <span>Tiket QR langsung dikirim via Email & Akun</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                        <span>Garansi reservasi terhubung ke Dashboard SKPI</span>
                    </div>
                </div>

                <div class="text-center pt-2">
                    <a href="mailto:support@campven.com" class="text-[11px] font-semibold text-slate-400 hover:text-indigo-600 transition">
                        Ada kendala verifikasi tiket? <strong>Pusat Bantuan Mahasiswa</strong>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Serupa yang Mungkin Kamu Sukai (Bottom Row) -->
    @if(count($related_events) > 0)
    <div class="mt-16 pt-12 border-t border-slate-200">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900">Event Serupa yang Mungkin Kamu Sukai</h3>
            <a href="{{ route('home') }}" class="text-xs font-bold text-indigo-600 hover:underline">Lihat Semua Jadwal →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($related_events as $rel)
                @include('mahasiswa.partials.event-card', ['event' => $rel, 'animate' => false])
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- 4-Column Footer -->
<footer class="bg-white text-slate-600 py-16 border-t border-slate-200 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-slate-100">
            <div class="space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <span class="text-lg font-extrabold text-slate-900 tracking-tight">Camp<span class="text-indigo-600">ven</span></span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs">
                    Platform resmi manajemen tiket kegiatan, akreditasi SKPI, dan ekosistem aktivitas mahasiswa terpadu.
                </p>
            </div>

            <div class="space-y-2.5 text-xs">
                <h4 class="font-extrabold text-slate-900 text-xs tracking-wider uppercase">Ekosistem Kampus</h4>
                <ul class="space-y-2 text-slate-500">
                    <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Simposium Akademik</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Festival & Seni</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Kegiatan UKM & Himpunan</a></li>
                </ul>
            </div>

            <div class="space-y-2.5 text-xs">
                <h4 class="font-extrabold text-slate-900 text-xs tracking-wider uppercase">Bantuan & Layanan</h4>
                <ul class="space-y-2 text-slate-500">
                    <li><a href="mailto:support@campven.com" class="hover:text-indigo-600 transition">Pusat Bantuan Mahasiswa</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Verifikasi Tiket QR</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-indigo-600 transition">Panduan Penyelenggara</a></li>
                </ul>
            </div>

            <div class="space-y-2.5 text-xs">
                <h4 class="font-extrabold text-slate-900 text-xs tracking-wider uppercase">Akreditasi Resmi</h4>
                <p class="text-slate-500 leading-relaxed text-[11px]">
                    Sesuai Standar Operasional Kemahasiswaan & Terhubung Langsung dengan Sistem Poin SKPI Direktorat Kemahasiswaan 2026.
                </p>
            </div>
        </div>

        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <div>© {{ date('Y') }} Campven. Seluruh hak cipta dilindungi undang-undang.</div>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-slate-600 transition">Keamanan Kampus</a>
                <a href="#" class="hover:text-slate-600 transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-slate-600 transition">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>
@endsection

@push('scripts')
<script>
    function copyDetailLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const btn = document.getElementById('detailShareText');
            btn.innerText = 'Link Disalin!';
            setTimeout(() => {
                btn.innerText = 'Bagikan';
            }, 2500);
        });
    }
</script>
@endpush
