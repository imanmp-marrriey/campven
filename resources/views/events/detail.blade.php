@extends('layouts.app')

@section('title', $event->nama_event . ' - Campven')

@section('content')
@include('partials.public-navbar', ['activeNav' => 'home'])

@php
    $skpi = (int) ($event->skpi_poin ?? 0);
    $statusLabels = [
        'registered' => 'Menunggu Konfirmasi',
        'confirmed'  => 'Terkonfirmasi',
        'attended'   => 'Sudah Hadir',
        'canceled'   => 'Dibatalkan',
    ];
    $userStatusLabel = $participant_status ? ($statusLabels[$participant_status] ?? ucfirst($participant_status)) : '';
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-5 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-slate-700 transition">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <a href="{{ route('home', ['kategori' => $event->kategori]) }}" class="hover:text-slate-700 transition">{{ $event->kategori }}</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-slate-900 font-bold truncate max-w-sm">{{ $event->nama_event }}</span>
    </nav>

    <!-- Main 2-Column Content (Proportional 16:9 Poster + Sticky Ticket Card) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

        <!-- Left Content Column -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Proportional 16:9 Event Poster Card -->
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-sm">
                <div class="relative w-full aspect-[16/9] bg-slate-900 overflow-hidden group">
                    @if($event->poster && file_exists(public_path('storage/' . $event->poster)))
                        <img src="{{ asset('storage/' . $event->poster) }}"
                             alt="{{ $event->nama_event }}"
                             class="w-full h-full object-cover object-center group-hover:scale-[1.02] transition-transform duration-500 ease-out {{ $isPast ? 'grayscale-[30%]' : '' }}">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 flex items-center justify-center p-8">
                            <div class="text-center text-white/90">
                                <i class="fas fa-calendar-alt text-5xl text-teal-400 mb-3 opacity-85"></i>
                                <p class="text-xs uppercase font-extrabold tracking-widest text-indigo-300">{{ $event->kategori }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Top Floating Dynamic Badges -->
                    <div class="absolute top-4 left-4 right-4 z-10 flex items-center justify-between gap-2 flex-wrap">
                        @if($isPast)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-900/85 backdrop-blur-md text-slate-200 border border-white/15 flex items-center gap-1.5">
                                <i class="fas fa-history text-slate-300"></i>
                                Acara Telah Selesai
                            </span>
                        @elseif($event->isFull())
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-600/90 backdrop-blur-md text-white flex items-center gap-1.5">
                                <i class="fas fa-ban"></i>
                                Kuota Penuh
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-900/75 backdrop-blur-md text-white border border-white/15 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-teal-400"></span>
                                Pendaftaran Dibuka
                            </span>
                        @endif

                        @if($skpi > 0)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-teal-600/95 backdrop-blur-md text-white shadow-sm flex items-center gap-1.5">
                            <i class="fas fa-award text-amber-300"></i>
                            +{{ $skpi }} Poin SKPI
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Event Title & Organizer Summary Strip -->
                <div class="p-5 sm:p-6 space-y-4">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600 block mb-1">
                            {{ $event->kategori }} • {{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('D MMMM Y') }}
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                            {{ $event->nama_event }}
                        </h1>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-700 text-base font-bold flex-shrink-0">
                                <i class="fas fa-university"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-extrabold text-slate-900">{{ $event->creator->nama ?? 'Penyelenggara Kampus' }}</h3>
                                    <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-bold text-[10px]">Penyelenggara</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $event->lokasi }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-600">
                                <div class="w-7 h-7 rounded-full bg-teal-50 text-teal-700 flex items-center justify-center text-xs font-bold">
                                    <i class="fas fa-users"></i>
                                </div>
                                <span><strong>{{ $event->participantCount() }}</strong> peserta</span>
                            </div>

                            @if($event->creator)
                            <button type="button" onclick="openContactModal()" class="btn-tactile px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 hover:border-indigo-200 text-slate-700 font-bold text-xs transition inline-flex items-center gap-1.5">
                                <i class="fas fa-headset text-indigo-600 text-[11px]"></i>
                                <span>Hubungi Panitia</span>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tentang Acara -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-4">
                <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-indigo-600 inline-block"></span>
                    <span>Tentang Acara</span>
                </h2>
                <div class="text-sm text-slate-600 leading-relaxed space-y-3">
                    @if(!empty($event->deskripsi))
                        {!! nl2br(e($event->deskripsi)) !!}
                    @else
                        <p class="text-slate-400 italic">Penyelenggara belum menambahkan deskripsi rinci untuk acara ini.</p>
                    @endif
                </div>
            </div>

            <!-- Ringkasan Informasi Pelaksanaan (Data Nyata) -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-indigo-600 inline-block"></span>
                    <span>Informasi Pelaksanaan &amp; Lokasi</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center flex-shrink-0">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold uppercase text-slate-400">Tanggal Pelaksanaan</span>
                            <span class="font-extrabold text-slate-900 block mt-0.5">{{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                            @if($event->waktu_mulai)
                                <span class="text-slate-500 text-[11px]">
                                    {{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') }}
                                    @if($event->waktu_selesai) - {{ \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i') }} @endif
                                    WIB
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-award"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold uppercase text-slate-400">Kategori &amp; Poin SKPI</span>
                            <span class="font-extrabold text-slate-900 block mt-0.5">{{ $event->kategori }}</span>
                            <span class="text-slate-500 text-[11px]">{{ $skpi > 0 ? $skpi . ' Poin SKPI (E-Sertifikat saat hadir)' : 'Tanpa poin SKPI' }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-indigo-600 uppercase">TEMPAT ACARA</span>
                        <h4 class="text-sm font-extrabold text-slate-900 mt-0.5">{{ $event->lokasi }}</h4>
                    </div>

                    <a href="https://maps.google.com/?q={{ urlencode($event->lokasi) }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold text-xs transition btn-spring flex items-center gap-1.5 flex-shrink-0">
                        <i class="fas fa-external-link-alt text-indigo-600"></i>
                        <span>Buka di Maps</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Sticky Action Column -->
        <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xl shadow-indigo-600/5 space-y-6">
                <!-- Price Display -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="block text-[10px] font-bold uppercase text-slate-400">Biaya Pendaftaran</span>
                        <div class="text-2xl font-black {{ $event->formattedHarga() === 'GRATIS' ? 'text-emerald-600' : 'text-slate-900' }} mt-0.5">
                            {{ $event->formattedHarga() }}
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 font-extrabold text-[10px]">
                        {{ $event->kategori }}
                    </span>
                </div>

                <!-- Schedule & Venue Details -->
                <div class="space-y-3 text-xs text-slate-600 pt-2 border-t border-slate-100">
                    <div class="flex items-start gap-3">
                        <i class="far fa-calendar-alt text-indigo-600 mt-0.5"></i>
                        <div>
                            <span class="font-extrabold text-slate-900 block">{{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                            @if($event->waktu_mulai)
                                <span class="text-slate-500 text-[11px]">
                                    {{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') }}
                                    @if($event->waktu_selesai) - {{ \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i') }} @endif
                                    WIB
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-rose-500 mt-0.5"></i>
                        <div>
                            <span class="font-extrabold text-slate-900 block">{{ $event->lokasi }}</span>
                            <span class="text-slate-500 text-[11px]">Oleh {{ $event->creator->nama ?? 'Penyelenggara Kampus' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Sisa Kuota Meter -->
                @if($event->kuota)
                @php
                    $sisa = $event->sisaKuota();
                    $percent = $event->persentaseTerisi();
                @endphp
                <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/80 space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-amber-800"><i class="fas fa-users mr-1 text-amber-600"></i> Kuota Peserta</span>
                        <span class="text-amber-900">{{ $sisa }} Kursi Tersedia</span>
                    </div>
                    <div class="w-full h-2 bg-amber-200/70 rounded-full overflow-hidden">
                        <div class="quota-progress-bar h-full bg-amber-500 rounded-full" style="width: 0%;" data-target-width="{{ $percent }}"></div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-amber-700">
                        <span>{{ $percent }}% Terisi</span>
                        <span>Total {{ $event->kuota }} Kuota</span>
                    </div>
                </div>
                @endif

                <!-- Primary Action CTA (Role-aware & Date-aware) -->
                @if(Auth::check() && Auth::user()->role !== 'mahasiswa')
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-2.5">
                        <p class="text-xs font-bold text-slate-700">
                            Anda masuk sebagai <span class="capitalize text-indigo-600">{{ Auth::user()->role }}</span>
                        </p>
                        <p class="text-[11px] text-slate-500">Hanya akun Mahasiswa yang dapat mendaftar sebagai peserta acara.</p>
                        @if(Auth::user()->role === 'penyelenggara' && (int) $event->created_by === (int) Auth::id())
                            <a href="{{ route('organizer.event.participants', $event->id) }}" class="block w-full py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition">
                                Kelola Peserta Acara Ini
                            </a>
                        @elseif(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.event.participants', $event->id) }}" class="block w-full py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition">
                                Lihat Daftar Peserta
                            </a>
                        @endif
                    </div>
                @elseif(Auth::check() && $is_joined)
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-center space-y-2.5">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-lg font-bold">
                            <i class="fas fa-check"></i>
                        </div>
                        <h4 class="text-xs font-bold text-emerald-900">Tiket Terdaftar!</h4>
                        <p class="text-[11px] text-emerald-700">Status Anda saat ini: <strong>{{ $userStatusLabel }}</strong></p>
                        <div class="space-y-2">
                            <a href="{{ route('my.events') }}" class="block w-full py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition">
                                Buka Tiket &amp; QR Saya
                            </a>
                            @if($participant_status === 'attended')
                            <a href="{{ route('my.events.certificate', $event->id) }}" class="block w-full py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition">
                                <i class="fas fa-certificate mr-1"></i> Cetak E-Sertifikat
                            </a>
                            @endif
                        </div>
                    </div>
                @elseif($isPast)
                    <div class="p-4 rounded-2xl bg-slate-100 border border-slate-200 text-center text-xs font-bold text-slate-600">
                        <i class="fas fa-history mr-1"></i> Acara Telah Selesai (Pendaftaran Ditutup)
                    </div>
                @elseif($event->isFull())
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-center text-xs font-bold text-rose-700">
                        <i class="fas fa-ban mr-1"></i> Kuota Penuh (Pendaftaran Ditutup)
                    </div>
                @elseif(Auth::check())
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

                <!-- Share & Calendar -->
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" id="detailShareBtn" onclick="openShareModal()" class="btn-tactile py-2.5 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 hover:border-indigo-200 font-bold text-slate-700 flex items-center justify-center gap-1.5 transition">
                        <i id="detailShareIcon" class="fas fa-share-alt text-indigo-600"></i>
                        <span id="detailShareText">Bagikan</span>
                    </button>
                    <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text={{ urlencode($event->nama_event) }}&dates={{ date('Ymd', strtotime($event->tanggal)) }}/{{ date('Ymd', strtotime($event->tanggal)) }}&details={{ urlencode($event->deskripsi ?? '') }}&location={{ urlencode($event->lokasi) }}" target="_blank" rel="noopener noreferrer" class="btn-tactile py-2.5 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 hover:border-indigo-200 font-bold text-slate-700 flex items-center justify-center gap-1.5 transition">
                        <i class="far fa-calendar-plus text-teal-600"></i>
                        <span>e-Kalender</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Serupa -->
    @if(count($related_events) > 0)
    <div class="mt-16 pt-12 border-t border-slate-200">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900">Event Serupa yang Mungkin Kamu Sukai</h3>
            <a href="{{ route('home') }}" class="text-xs font-bold text-indigo-600 hover:underline">Lihat Semua Jadwal →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($related_events as $rel)
                @include('mahasiswa.partials.event-card', ['event' => $rel])
            @endforeach
        </div>
    </div>
    @endif
</div>

@include('partials.footer')

@php
    $shareUrl = route('event.detail', $event->id);
    $shareMessage = "Yuk ikuti acara \"{$event->nama_event}\" di Campven!\nTanggal: " . \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('D MMMM Y') . "\nLokasi: {$event->lokasi}\nDaftar di sini: {$shareUrl}";
    $rawPhone = preg_replace('/[^0-9]/', '', (string) ($event->creator->no_telp ?? ''));
    if (str_starts_with($rawPhone, '0')) {
        $waPhone = '62' . substr($rawPhone, 1);
    } elseif (str_starts_with($rawPhone, '62')) {
        $waPhone = $rawPhone;
    } else {
        $waPhone = $rawPhone;
    }
    $waContactText = "Halo panitia {$event->creator->nama}, saya ingin bertanya mengenai acara \"{$event->nama_event}\" di Campven.";
@endphp

<!-- ===== MODAL BAGIKAN ACARA ===== -->
<div id="shareEventModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="shareEventBackdrop" onclick="closeShareModal()" class="fixed inset-0 bg-slate-900/45 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>
    <div id="shareEventCard" class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200/90 p-6 transform scale-95 opacity-0 transition-all duration-200 space-y-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                    <i class="fas fa-share-alt"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Bagikan Acara</h3>
                    <p class="text-[11px] text-slate-500 truncate max-w-[230px]">{{ $event->nama_event }}</p>
                </div>
            </div>
            <button type="button" onclick="closeShareModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs transition" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Copy Link Box -->
        <div class="space-y-1.5">
            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Tautan Halaman Acara</label>
            <div class="flex items-center gap-2">
                <input type="text" id="shareEventUrlInput" value="{{ $shareUrl }}" readonly class="flex-1 px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 font-mono focus:outline-none">
                <button type="button" id="copyShareUrlBtn" onclick="copyTextWithFeedback('shareEventUrlInput', 'copyShareUrlBtn', 'Salin')" class="btn-tactile px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition whitespace-nowrap">
                    Salin
                </button>
            </div>
        </div>

        <!-- Direct Share Channels -->
        <div class="grid grid-cols-2 gap-2.5 text-xs">
            <a href="https://wa.me/?text={{ urlencode($shareMessage) }}" target="_blank" rel="noopener noreferrer" class="btn-tactile py-2.5 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold flex items-center justify-center gap-2 transition">
                <i class="fab fa-whatsapp text-sm"></i>
                <span>WhatsApp</span>
            </a>
            <a href="https://t.me/share/url?url={{ urlencode($shareUrl) }}&text={{ urlencode('Yuk ikuti acara ' . $event->nama_event . ' di Campven!') }}" target="_blank" rel="noopener noreferrer" class="btn-tactile py-2.5 px-3 rounded-xl bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 font-bold flex items-center justify-center gap-2 transition">
                <i class="fab fa-telegram-plane text-sm"></i>
                <span>Telegram</span>
            </a>
        </div>
    </div>
</div>

<!-- ===== MODAL HUBUNGI PANITIA ===== -->
@if($event->creator)
<div id="contactOrganizerModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="contactOrganizerBackdrop" onclick="closeContactModal()" class="fixed inset-0 bg-slate-900/45 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>
    <div id="contactOrganizerCard" class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200/90 p-6 transform scale-95 opacity-0 transition-all duration-200 space-y-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Hubungi Panitia</h3>
                    <p class="text-[11px] text-slate-500">Kontak resmi penyelenggara acara</p>
                </div>
            </div>
            <button type="button" onclick="closeContactModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs transition" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Organizer Contact Details -->
        <div class="space-y-3 text-xs">
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-extrabold text-sm flex-shrink-0">
                    {{ strtoupper(substr($event->creator->nama, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Organisasi Penyelenggara</span>
                    <span class="block text-sm font-extrabold text-slate-900 truncate">{{ $event->creator->nama }}</span>
                </div>
            </div>

            @if($event->creator->email)
            <div class="p-3 rounded-xl bg-white border border-slate-200 flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <span class="block text-[10px] font-bold uppercase text-slate-400">Email Panitia</span>
                    <input type="text" id="organizerEmailInput" value="{{ $event->creator->email }}" readonly class="w-full bg-transparent text-xs font-semibold text-slate-800 focus:outline-none">
                </div>
                <button type="button" id="copyEmailBtn" onclick="copyTextWithFeedback('organizerEmailInput', 'copyEmailBtn', 'Salin')" class="btn-tactile px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition flex-shrink-0">
                    Salin
                </button>
            </div>
            @endif

            @if(!empty($event->creator->no_telp))
            <div class="p-3 rounded-xl bg-white border border-slate-200 flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <span class="block text-[10px] font-bold uppercase text-slate-400">Nomor Telepon / WhatsApp</span>
                    <input type="text" id="organizerPhoneInput" value="{{ $event->creator->no_telp }}" readonly class="w-full bg-transparent text-xs font-semibold text-slate-800 focus:outline-none">
                </div>
                <button type="button" id="copyPhoneBtn" onclick="copyTextWithFeedback('organizerPhoneInput', 'copyPhoneBtn', 'Salin')" class="btn-tactile px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition flex-shrink-0">
                    Salin
                </button>
            </div>
            @endif
        </div>

        <!-- Direct Contact Action Buttons -->
        <div class="grid {{ !empty($waPhone) && $event->creator->email ? 'grid-cols-2' : 'grid-cols-1' }} gap-2.5 text-xs pt-1">
            @if(!empty($waPhone))
            <a href="https://wa.me/{{ $waPhone }}?text={{ urlencode($waContactText) }}" target="_blank" rel="noopener noreferrer" class="btn-tactile py-2.5 px-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold flex items-center justify-center gap-2 transition">
                <i class="fab fa-whatsapp text-sm"></i>
                <span>Chat WhatsApp</span>
            </a>
            @endif

            @if($event->creator->email)
            <a href="mailto:{{ $event->creator->email }}?subject={{ rawurlencode('Pertanyaan Acara: ' . $event->nama_event) }}" class="btn-tactile py-2.5 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold flex items-center justify-center gap-2 transition">
                <i class="far fa-envelope text-sm"></i>
                <span>Kirim Email</span>
            </a>
            @endif
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    function toggleDetailModal(modalId, backdropId, cardId, show) {
        const modal = document.getElementById(modalId);
        const backdrop = document.getElementById(backdropId);
        const card = document.getElementById(cardId);
        if (!modal || !backdrop || !card) return;

        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0');
                card.classList.remove('opacity-0', 'scale-95');
                card.classList.add('opacity-100', 'scale-100');
            });
        } else {
            backdrop.classList.add('opacity-0');
            card.classList.add('opacity-0', 'scale-95');
            card.classList.remove('opacity-100', 'scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }
    }

    window.openShareModal = function() {
        toggleDetailModal('shareEventModal', 'shareEventBackdrop', 'shareEventCard', true);
    };
    window.closeShareModal = function() {
        toggleDetailModal('shareEventModal', 'shareEventBackdrop', 'shareEventCard', false);
    };

    window.openContactModal = function() {
        toggleDetailModal('contactOrganizerModal', 'contactOrganizerBackdrop', 'contactOrganizerCard', true);
    };
    window.closeContactModal = function() {
        toggleDetailModal('contactOrganizerModal', 'contactOrganizerBackdrop', 'contactOrganizerCard', false);
    };

    function fallbackCopyText(text) {
        const tempInput = document.createElement('textarea');
        tempInput.value = text;
        tempInput.setAttribute('readonly', '');
        tempInput.style.position = 'fixed';
        tempInput.style.top = '-9999px';
        tempInput.style.left = '-9999px';
        document.body.appendChild(tempInput);
        tempInput.select();
        tempInput.setSelectionRange(0, text.length);
        let ok = false;
        try {
            ok = document.execCommand('copy');
        } catch (e) {
            ok = false;
        }
        document.body.removeChild(tempInput);
        return ok;
    }

    window.copyTextWithFeedback = function(inputId, btnId, defaultLabel) {
        const input = document.getElementById(inputId);
        const btn = document.getElementById(btnId);
        if (!input || !btn) return;
        const text = input.value;

        const markCopied = () => {
            btn.innerText = '✓ Tersalin!';
            btn.classList.add('bg-teal-600', 'text-white');
            btn.classList.remove('bg-indigo-600', 'bg-slate-100', 'text-slate-700');
            setTimeout(() => {
                btn.innerText = defaultLabel;
                btn.classList.remove('bg-teal-600', 'text-white');
                if (btnId === 'copyShareUrlBtn') {
                    btn.classList.add('bg-indigo-600', 'text-white');
                } else {
                    btn.classList.add('bg-slate-100', 'text-slate-700');
                }
            }, 2200);
        };

        if (window.isSecureContext && navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(markCopied).catch(() => {
                if (fallbackCopyText(text)) markCopied();
            });
        } else if (fallbackCopyText(text)) {
            markCopied();
        } else {
            input.select();
        }
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closeShareModal();
            window.closeContactModal();
        }
    });
</script>
@endpush
