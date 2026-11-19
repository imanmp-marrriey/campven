@extends('layouts.app')

@section('title', 'Campven - Temukan & Ikuti Event Organisasi Kampus')

@section('content')
@include('partials.public-navbar', ['activeNav' => 'home'])

<!-- ===== HERO DISCOVERY SECTION ===== -->
<section class="pt-10 pb-14 bg-gradient-to-b from-white via-slate-50 to-slate-100/70 border-b border-slate-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Floating Pill Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-slate-200/80 text-slate-700 text-xs font-bold mb-6 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Portal Resmi Event Organisasi &amp; Kegiatan Kampus</span>
        </div>

        <!-- Headline -->
        <h1 class="text-3xl sm:text-5xl md:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15] max-w-4xl mx-auto">
            Temukan, Ikuti, dan Kelola <span class="text-indigo-600">Event Kampus.</span>
        </h1>
        <p class="mt-4 text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
            Platform terpusat untuk mencari, mempublikasikan, dan mendaftar seminar, workshop, lomba, serta kegiatan UKM dan organisasi mahasiswa.
        </p>

        <!-- 4-Segment Floating Search Bar -->
        <div class="mt-8 max-w-4xl mx-auto">
            <form method="GET" action="{{ route('home') }}" class="bg-white p-2 sm:p-2.5 rounded-3xl shadow-xl shadow-slate-200/80 border border-slate-200 flex flex-col md:flex-row items-center gap-2">
                @if(!empty($kategori))
                    <input type="hidden" name="kategori" value="{{ $kategori }}">
                @endif
                @if(!empty($sort))
                    <input type="hidden" name="sort" value="{{ $sort }}">
                @endif

                <!-- Segment 1: Keyword Search -->
                <div class="flex items-center gap-3 px-4 py-2 w-full md:w-2/5">
                    <i class="fas fa-search text-slate-400 text-sm"></i>
                    <input type="text"
                           id="mainSearchInput"
                           name="search"
                           value="{{ $search ?? '' }}"
                           placeholder="Cari nama event, topik, lokasi..."
                           class="w-full bg-transparent text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none">
                    @if(!empty($search))
                    <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-600 p-1" title="Hapus pencarian">
                        <i class="fas fa-times-circle text-xs"></i>
                    </a>
                    @endif
                </div>

                <!-- Segment 2: Time Range Filter -->
                <div class="flex items-center gap-2 px-3 py-2 w-full md:w-1/4 border-t md:border-t-0 md:border-l border-slate-200 text-xs text-slate-600">
                    <i class="far fa-calendar-alt text-slate-400"></i>
                    <select name="rentang" class="bg-transparent w-full text-xs text-slate-700 font-semibold focus:outline-none cursor-pointer">
                        <option value="">Semua Waktu</option>
                        <option value="minggu_ini" {{ ($rentang ?? '') === 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan_ini" {{ ($rentang ?? '') === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="semester" {{ ($rentang ?? '') === 'semester' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </div>

                <!-- Segment 3: Location / Building Filter -->
                <div class="flex items-center gap-2 px-3 py-2 w-full md:w-1/4 border-t md:border-t-0 md:border-l border-slate-200 text-xs text-slate-600">
                    <i class="fas fa-map-marker-alt text-slate-400"></i>
                    <select name="lokasi_filter" class="bg-transparent w-full text-xs text-slate-700 font-semibold focus:outline-none cursor-pointer">
                        <option value="">Semua Lokasi</option>
                        <option value="Auditorium" {{ ($lokasi_filter ?? '') === 'Auditorium' ? 'selected' : '' }}>Auditorium / Aula</option>
                        <option value="GSC" {{ ($lokasi_filter ?? '') === 'GSC' ? 'selected' : '' }}>Gedung GSC</option>
                        <option value="Lab" {{ ($lokasi_filter ?? '') === 'Lab' ? 'selected' : '' }}>Lab Komputer / Studio</option>
                        <option value="Online" {{ ($lokasi_filter ?? '') === 'Online' ? 'selected' : '' }}>Online / Zoom</option>
                    </select>
                </div>

                <!-- Segment 4: Search Submit Button -->
                <div class="w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition-all btn-spring flex items-center justify-center gap-2 whitespace-nowrap">
                        <span>Cari Event</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Category Pills Bar -->
        <div id="categories" class="mt-6 flex items-center justify-start sm:justify-center gap-2 overflow-x-auto pb-2">
            <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all flex-shrink-0 {{ empty($kategori) ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
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

        <!-- Real Stats Ticker Bar -->
        <div class="mt-8 flex flex-wrap items-center justify-center gap-6 sm:gap-10 text-xs font-semibold text-slate-500 pt-6 border-t border-slate-200/80">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span><strong>{{ $total_events }}</strong> Event Terdaftar</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                <span><strong>{{ $total_participants }}</strong> Partisipasi Aktif</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                <span><strong>{{ $total_organizers }}</strong> Organisasi Penyelenggara</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span><strong>{{ $total_users }}</strong> Akun Terdaftar</span>
            </div>
        </div>
    </div>
</section>

<!-- ===== SOROTAN UNGGULAN KAMPUS (WIDE FEATURED CARDS) ===== -->
@if(count($featured_events) > 0 && empty($search) && empty($kategori) && empty($rentang) && empty($lokasi_filter))
<section class="py-12 bg-white border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 flex items-center gap-2">
                    <span class="w-1.5 h-6 rounded-full bg-indigo-600 inline-block"></span>
                    <span>Sorotan Unggulan Kampus</span>
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Rekomendasi agenda pilihan dengan informasi lengkap penyelenggara dan poin SKPI.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($featured_events->take(2) as $fe)
            @php
                $sisa = $fe->sisaKuota();
                $percent = $fe->persentaseTerisi();
                $price = $fe->formattedHarga();
                $isPastFe = \Carbon\Carbon::parse($fe->tanggal)->lt(\Carbon\Carbon::today());
                $skpiFe = (int) ($fe->skpi_poin ?? 0);
            @endphp
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 p-6 sm:p-7 flex flex-col justify-between relative overflow-hidden group">
                <div>
                    <div class="flex items-center gap-2 flex-wrap mb-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                            {{ $fe->kategori }}
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                            <i class="fas fa-check-circle text-[9px]"></i> Oleh {{ $fe->creator->nama ?? 'Penyelenggara Kampus' }}
                        </span>
                        @if($isPastFe)
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 flex items-center gap-1">
                                <i class="fas fa-history text-[9px]"></i> Selesai
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-1">
                                <i class="fas fa-hourglass-half text-[9px]"></i> Mendatang
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('event.detail', $fe->id) }}" class="block group-hover:text-indigo-600 transition-colors">
                        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-tight mb-2">
                            {{ $fe->nama_event }}
                        </h3>
                    </a>

                    <p class="text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed mb-6">
                        {{ $fe->deskripsi ?: 'Lihat detail jadwal, lokasi, dan informasi pendaftaran acara ini.' }}
                    </p>

                    <!-- Meta Badges 3 Columns -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs mb-6">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Waktu &amp; Tanggal</span>
                            <span class="font-extrabold text-slate-800 block truncate">{{ \Carbon\Carbon::parse($fe->tanggal)->locale('id')->isoFormat('D MMM Y') }}</span>
                            <span class="text-[10px] text-slate-500">{{ $fe->waktu_mulai ? \Carbon\Carbon::parse($fe->waktu_mulai)->format('H:i') . ' WIB' : 'Jadwal di detail' }}</span>
                        </div>
                        <div class="sm:border-l sm:border-slate-200 sm:pl-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Tempat</span>
                            <span class="font-extrabold text-slate-800 block truncate">{{ $fe->lokasi }}</span>
                            <span class="text-[10px] text-slate-500">Lokasi Acara</span>
                        </div>
                        <div class="sm:border-l sm:border-slate-200 sm:pl-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Nilai Kredit</span>
                            <span class="font-extrabold text-indigo-600 block">{{ $skpiFe > 0 ? '+' . $skpiFe . ' Poin SKPI' : 'Non-SKPI' }}</span>
                            <span class="text-[10px] text-slate-500">E-Sertifikat</span>
                        </div>
                    </div>

                    @if($fe->kuota)
                    <div class="mb-6">
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-slate-600">Sisa Kuota: <strong class="{{ $fe->isFull() ? 'text-rose-600' : 'text-indigo-700' }}">{{ $sisa }} Kursi</strong></span>
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
                    <a href="{{ route('event.detail', $fe->id) }}" class="px-6 py-2.5 rounded-2xl {{ $isPastFe || $fe->isFull() ? 'bg-slate-800 hover:bg-slate-900' : 'bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/20' }} text-white font-bold text-xs transition-all btn-spring flex items-center gap-2">
                        <span>{{ $isPastFe || $fe->isFull() ? 'Lihat Detail' : 'Daftar Sekarang' }}</span>
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
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Menampilkan {{ $events->count() }} dari {{ $events->total() }} kegiatan terdaftar.
                </p>
            </div>

            <form method="GET" action="{{ route('home') }}" class="flex items-center gap-2 self-start sm:self-center">
                @if(!empty($search)) <input type="hidden" name="search" value="{{ $search }}"> @endif
                @if(!empty($kategori)) <input type="hidden" name="kategori" value="{{ $kategori }}"> @endif
                @if(!empty($rentang)) <input type="hidden" name="rentang" value="{{ $rentang }}"> @endif
                @if(!empty($lokasi_filter)) <input type="hidden" name="lokasi_filter" value="{{ $lokasi_filter }}"> @endif

                <label for="sortEventsSelect" class="text-xs text-slate-400 font-semibold">Urutkan:</label>
                <select id="sortEventsSelect" name="sort" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 focus:outline-none cursor-pointer">
                    <option value="terbaru" {{ ($sort ?? 'terbaru') === 'terbaru' ? 'selected' : '' }}>Tanggal Terbaru</option>
                    <option value="terdekat" {{ ($sort ?? '') === 'terdekat' ? 'selected' : '' }}>Tanggal Terawal</option>
                    <option value="kuota" {{ ($sort ?? '') === 'kuota' ? 'selected' : '' }}>Kuota Terbanyak</option>
                </select>
            </form>
        </div>

        <!-- 2-Column Split (Left: 8 Cols, Right: 4 Cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Left Column: Event Grid -->
            <div class="lg:col-span-8 space-y-8">
                @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($events as $event)
                        @include('mahasiswa.partials.event-card', ['event' => $event])
                    @endforeach
                </div>

                <!-- Real Pagination -->
                @if($events->hasPages())
                <div class="pt-4">
                    {{ $events->links() }}
                </div>
                @endif
                @else
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Tidak Ada Event</h3>
                    <p class="text-xs text-slate-500 mb-6">Belum ada kegiatan yang cocok dengan kriteria pencarian atau filter Anda.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition btn-spring">
                        <i class="fas fa-undo"></i> Reset Filter
                    </a>
                </div>
                @endif
            </div>

            <!-- Right Column: Official Notices & Sidebars -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Pemberitahuan Resmi -->
                <div id="announcements" class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-bullhorn text-indigo-600"></i>
                            <span>Pemberitahuan Resmi</span>
                        </h3>
                        <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 font-extrabold text-[10px] uppercase">
                            Info Kampus
                        </span>
                    </div>

                    <div class="space-y-3.5 text-xs">
                        @forelse($announcements as $ann)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-200 transition">
                            <div class="flex items-center justify-between text-[10px] text-slate-400 mb-1">
                                <span class="font-bold text-indigo-600 uppercase tracking-wider">PENGUMUMAN RESMI</span>
                                <span>{{ \Carbon\Carbon::parse($ann->created_at)->locale('id')->isoFormat('D MMM Y') }}</span>
                            </div>
                            <h4 class="font-bold text-slate-900 mb-1 leading-snug">{{ $ann->title }}</h4>
                            <p class="text-slate-600 text-[11px] leading-relaxed">{{ $ann->content }}</p>
                        </div>
                        @empty
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center text-slate-400 text-xs">
                            <i class="fas fa-info-circle mb-1 block text-base text-indigo-400"></i>
                            Belum ada pengumuman kampus saat ini.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Portal Tiket / Organizer Callout -->
                @if(Auth::check() && Auth::user()->role === 'mahasiswa')
                <div class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-slate-900 rounded-3xl p-6 text-white shadow-xl shadow-indigo-600/20 space-y-4">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-lg">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold leading-snug">Tiket &amp; E-Sertifikat Anda</h3>
                        <p class="text-xs text-indigo-100 mt-1 leading-relaxed">
                            Lihat kode QR tiket acara yang telah Anda daftar dan cetak e-sertifikat setelah presensi terverifikasi.
                        </p>
                    </div>
                    <a href="{{ route('my.events') }}" class="block w-full py-2.5 text-center rounded-xl bg-white text-indigo-700 font-bold text-xs hover:bg-indigo-50 shadow-sm transition btn-spring">
                        Buka Tiket Saya →
                    </a>
                </div>
                @endif

                <!-- Organizer Callout Banner -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-sm font-extrabold text-slate-900">Punya Agenda UKM / Himpunan?</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Publikasikan acara organisasi kampus Anda, kelola pendaftaran peserta, dan catat presensi secara terpusat.
                        </p>
                        @if(Auth::check() && Auth::user()->role === 'penyelenggara')
                            <a href="{{ route('organizer.dashboard') }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:underline">
                                <span>Buka Portal Penyelenggara</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        @elseif(Auth::check() && Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:underline">
                                <span>Buka Dashboard Admin</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:underline">
                                <span>Masuk Sebagai Penyelenggara</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Helpdesk Link -->
                <div class="text-center pt-2">
                    <a href="mailto:support@campven.com" class="text-xs font-semibold text-slate-400 hover:text-slate-600 transition flex items-center justify-center gap-1.5">
                        <i class="fas fa-headset text-indigo-500"></i>
                        <span>Kendala Tiket / Akun? Hubungi Bantuan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')
@endsection
