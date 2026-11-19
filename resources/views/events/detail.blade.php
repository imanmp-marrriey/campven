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
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-6 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-slate-700 transition">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <a href="{{ route('home', ['kategori' => $event->kategori]) }}" class="hover:text-slate-700 transition">{{ $event->kategori }}</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-slate-900 font-bold truncate max-w-sm">{{ $event->nama_event }}</span>
    </nav>

    <!-- Poster Hero Banner -->
    <div class="relative w-full h-72 sm:h-[400px] rounded-3xl overflow-hidden bg-slate-950 shadow-2xl mb-6 group">
        @if($event->poster && file_exists(public_path('storage/' . $event->poster)))
            <img src="{{ asset('storage/' . $event->poster) }}"
                 alt="{{ $event->nama_event }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out {{ $isPast ? 'grayscale-[30%]' : '' }}">
        @else
            <div class="w-full h-full bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-900 flex items-center justify-center p-8 relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
                <div class="text-center text-white/90">
                    <i class="fas fa-calendar-alt text-6xl text-indigo-400 mb-4 opacity-80"></i>
                    <p class="text-xs uppercase font-extrabold tracking-widest text-indigo-300">{{ $event->kategori }}</p>
                </div>
            </div>
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/35 to-black/25"></div>

        <!-- Top Floating Dynamic Badges -->
        <div class="absolute top-5 left-5 right-5 flex items-center justify-between gap-3 flex-wrap">
            @if($isPast)
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-800/80 backdrop-blur-md text-slate-200 border border-white/20 flex items-center gap-1.5">
                    <i class="fas fa-history text-slate-300"></i>
                    Acara Telah Selesai
                </span>
            @elseif($event->isFull())
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-600/90 backdrop-blur-md text-white border border-rose-400/40 flex items-center gap-1.5">
                    <i class="fas fa-ban"></i>
                    Kuota Peserta Penuh
                </span>
            @else
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/15 backdrop-blur-md text-white border border-white/20 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Pendaftaran Dibuka
                </span>
            @endif

            @if($skpi > 0)
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-600/90 backdrop-blur-md text-white border border-indigo-400/40 flex items-center gap-1.5">
                <i class="fas fa-certificate text-amber-300"></i>
                +{{ $skpi }} Poin SKPI
            </span>
            @endif
        </div>

        <!-- Bottom Title -->
        <div class="absolute bottom-6 left-6 right-6 max-w-3xl">
            <span class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-300 block mb-2">
                {{ $event->kategori }} • {{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('D MMMM Y') }}
            </span>
            <h1 class="text-2xl sm:text-4xl font-black text-white leading-tight drop-shadow-lg">
                {{ $event->nama_event }}
            </h1>
        </div>
    </div>

    <!-- Organizer Bar & Social Proof -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-10">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-700 text-lg font-bold flex-shrink-0">
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

        <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-600">
                <div class="w-7 h-7 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs font-bold">
                    <i class="fas fa-users"></i>
                </div>
                <span><strong>{{ $event->participantCount() }}</strong> peserta terdaftar</span>
            </div>

            @if($event->creator && $event->creator->email)
            <a href="mailto:{{ $event->creator->email }}" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition btn-spring">
                Hubungi Panitia
            </a>
            @endif
        </div>
    </div>

    <!-- Main 2-Column Content (Only Real Database Fields) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

        <!-- Left Content Column -->
        <div class="lg:col-span-8 space-y-8">

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
                        <div class="h-full bg-amber-500 rounded-full" style="width: {{ $percent }}%;"></div>
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
                    <button type="button" onclick="copyDetailLink()" class="py-2 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 font-bold text-slate-700 flex items-center justify-center gap-1.5 transition">
                        <i class="fas fa-share-alt text-slate-400"></i>
                        <span id="detailShareText">Bagikan</span>
                    </button>
                    <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text={{ urlencode($event->nama_event) }}&dates={{ date('Ymd', strtotime($event->tanggal)) }}/{{ date('Ymd', strtotime($event->tanggal)) }}&details={{ urlencode($event->deskripsi ?? '') }}&location={{ urlencode($event->lokasi) }}" target="_blank" rel="noopener noreferrer" class="py-2 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 font-bold text-slate-700 flex items-center justify-center gap-1.5 transition">
                        <i class="far fa-calendar-plus text-slate-400"></i>
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
