@extends('layouts.app')

@section('title', 'Tiket & Event Saya - Campven')

@section('content')
@include('partials.public-navbar', ['activeNav' => 'my-events'])

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Header Hero Banner -->
    <div class="bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl mb-7 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/10 backdrop-blur-md text-indigo-300 mb-3 border border-white/10">
                    <i class="fas fa-ticket-alt"></i> Portal Tiket Mahasiswa
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">Event &amp; Tiket Saya</h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-2 max-w-xl leading-relaxed">
                    Pantau status pendaftaran, tunjukkan kode QR tiket saat presensi di lokasi acara, dan cetak e-sertifikat setelah hadir.
                </p>
            </div>

            <!-- Stats KPI Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 text-center">
                <div class="p-2">
                    <div class="text-xl sm:text-2xl font-extrabold text-white">{{ count($my_events) }}</div>
                    <div class="text-[10px] sm:text-xs text-slate-300 uppercase font-semibold mt-0.5">Total Tiket</div>
                </div>
                <div class="p-2 border-l border-white/10">
                    <div class="text-xl sm:text-2xl font-extrabold text-amber-400">{{ $status_counts['registered'] ?? 0 }}</div>
                    <div class="text-[10px] sm:text-xs text-slate-300 uppercase font-semibold mt-0.5">Menunggu</div>
                </div>
                <div class="p-2 sm:border-l border-white/10">
                    <div class="text-xl sm:text-2xl font-extrabold text-emerald-400">{{ $status_counts['confirmed'] ?? 0 }}</div>
                    <div class="text-[10px] sm:text-xs text-slate-300 uppercase font-semibold mt-0.5">Dikonfirmasi</div>
                </div>
                <div class="p-2 border-l border-white/10">
                    <div class="text-xl sm:text-2xl font-extrabold text-indigo-300">{{ $status_counts['attended'] ?? 0 }}</div>
                    <div class="text-[10px] sm:text-xs text-slate-300 uppercase font-semibold mt-0.5">Hadir</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    @php
        $currStatus = request('status');
    @endphp
    <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6">
        <a href="{{ route('my.events') }}"
           class="btn-tactile active:scale-[0.98] px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center gap-2 flex-shrink-0 {{ empty($currStatus) ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">
            <i class="fas fa-layer-group"></i>
            <span>Semua Tiket</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ empty($currStatus) ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ count($my_events) }}</span>
        </a>

        <a href="{{ route('my.events', ['status' => 'registered']) }}"
           class="btn-tactile active:scale-[0.98] px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center gap-2 flex-shrink-0 {{ $currStatus === 'registered' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">
            <i class="fas fa-clock text-amber-500"></i>
            <span>Menunggu Konfirmasi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $currStatus === 'registered' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $status_counts['registered'] ?? 0 }}</span>
        </a>

        <a href="{{ route('my.events', ['status' => 'confirmed']) }}"
           class="btn-tactile active:scale-[0.98] px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center gap-2 flex-shrink-0 {{ $currStatus === 'confirmed' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">
            <i class="fas fa-check-circle text-emerald-500"></i>
            <span>Terkonfirmasi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $currStatus === 'confirmed' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $status_counts['confirmed'] ?? 0 }}</span>
        </a>

        <a href="{{ route('my.events', ['status' => 'attended']) }}"
           class="btn-tactile active:scale-[0.98] px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center gap-2 flex-shrink-0 {{ $currStatus === 'attended' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">
            <i class="fas fa-check-double text-blue-500"></i>
            <span>Sudah Hadir</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $currStatus === 'attended' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $status_counts['attended'] ?? 0 }}</span>
        </a>

        <a href="{{ route('my.events', ['status' => 'canceled']) }}"
           class="btn-tactile active:scale-[0.98] px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center gap-2 flex-shrink-0 {{ $currStatus === 'canceled' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">
            <i class="fas fa-ban text-rose-500"></i>
            <span>Dibatalkan</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $currStatus === 'canceled' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $status_counts['canceled'] ?? 0 }}</span>
        </a>
    </div>

    <!-- Event Ticket Cards Grid -->
    @php
        $filteredEvents = $my_events;
        if (!empty($currStatus)) {
            $filteredEvents = $my_events->where('status', $currStatus);
        }
    @endphp

    @if(count($filteredEvents) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-start">
        @foreach($filteredEvents as $participant)
        @php
            $event = $participant->event;
            if (!$event) continue;
            $status = $participant->status;
            $ticketCode = $participant->ticketCode();
            $statusStyles = [
                'registered' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'dot' => 'bg-amber-500', 'label' => 'Menunggu Konfirmasi'],
                'confirmed'  => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Terkonfirmasi'],
                'attended'   => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'dot' => 'bg-blue-500', 'label' => 'Sudah Hadir'],
                'canceled'   => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'dot' => 'bg-rose-500', 'label' => 'Dibatalkan'],
            ];
            $st = $statusStyles[$status] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'dot' => 'bg-slate-400', 'label' => ucfirst($status)];
        @endphp

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden relative group">
            <!-- Top Card Body -->
            <div class="p-6 pb-4 space-y-4">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $event->kategori }}
                    </span>

                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $st['bg'] }} {{ $st['text'] }} border {{ $st['border'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span>
                        {{ $st['label'] }}
                    </span>
                </div>

                <a href="{{ route('event.detail', $event->id) }}" class="block group-hover:text-indigo-600 transition-colors">
                    <h3 class="text-base font-extrabold text-slate-900 leading-snug line-clamp-2">
                        {{ $event->nama_event }}
                    </h3>
                </a>

                <!-- Meta Details -->
                <div class="space-y-1.5 text-xs text-slate-600">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calendar-day text-slate-400 w-4"></i>
                        <span>{{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    @if($event->waktu_mulai)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock text-slate-400 w-4"></i>
                        <span>{{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') }}{{ $event->waktu_selesai ? ' - ' . \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i') : '' }} WIB</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-rose-500 w-4"></i>
                        <span class="truncate">{{ $event->lokasi }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-user-tie text-slate-400 w-4"></i>
                        <span>{{ $event->creator->nama ?? 'Panitia Kampus' }}</span>
                    </div>
                </div>

                <!-- QR Code Visual Tiket (Only for Active Tickets) -->
                @if(in_array($status, ['registered', 'confirmed', 'attended']))
                @php
                    $verifyUrl = route('ticket.verify', $ticketCode);
                @endphp
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-dashed border-slate-300 flex items-center gap-3.5">
                    <button type="button"
                            onclick="openQrZoomModal(this)"
                            data-code="{{ $ticketCode }}"
                            data-event="{{ $event->nama_event }}"
                            data-url="{{ $verifyUrl }}"
                            class="p-1.5 bg-white rounded-xl border border-slate-200 shadow-sm hover:scale-105 transition flex-shrink-0 cursor-pointer"
                            title="Klik untuk memperbesar QR">
                        {!! \App\Support\QrCodeSvg::generate($verifyUrl, 76) !!}
                    </button>
                    <div class="min-w-0">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Kode Tiket Presensi</span>
                        <a href="{{ $verifyUrl }}" class="block font-mono font-extrabold text-sm text-indigo-700 hover:underline tracking-wide">{{ $ticketCode }}</a>
                        <span class="block text-[10px] text-slate-500 mt-0.5">Pindai QR atau sebutkan kode ke panitia</span>
                    </div>
                </div>
                @endif

                <!-- Compact Payment Action Strip (Opens Payment Modal so Cards Stay Uniform) -->
                @if($status === 'registered' && ((int) ($event->harga ?? 0)) > 0)
                @php
                    $hasProof = $participant->bukti_bayar && \Illuminate\Support\Facades\Storage::disk('public')->exists($participant->bukti_bayar);
                    $orgRawPhone = preg_replace('/[^0-9]/', '', (string) ($event->creator->no_telp ?? ''));
                    $orgWaPhone = str_starts_with($orgRawPhone, '0') ? '62' . substr($orgRawPhone, 1) : $orgRawPhone;
                    $orgWaText = "Halo panitia {$event->creator->nama}, saya " . Auth::user()->nama . " (Kode Tiket: {$ticketCode}) ingin konfirmasi pembayaran acara \"{$event->nama_event}\" sebesar {$event->formattedHarga()}.";
                    $contactHref = !empty($orgWaPhone)
                        ? 'https://wa.me/' . $orgWaPhone . '?text=' . urlencode($orgWaText)
                        : (($event->creator && $event->creator->email) ? 'mailto:' . $event->creator->email . '?subject=' . rawurlencode('Konfirmasi Pembayaran ' . $ticketCode) : '');
                    $contactLabel = !empty($orgWaPhone) ? 'Chat WhatsApp Panitia' : 'Email Panitia';
                @endphp
                <div class="px-3.5 py-2.5 rounded-2xl {{ $hasProof ? 'bg-teal-50/80 border border-teal-200' : 'bg-amber-50/80 border border-amber-200' }} flex items-center justify-between gap-2 text-xs">
                    <div class="min-w-0">
                        <span class="block text-[10px] font-bold uppercase tracking-wider {{ $hasProof ? 'text-teal-700' : 'text-amber-700' }}">
                            {{ $hasProof ? 'Bukti Terkirim' : 'Perlu Pembayaran' }}
                        </span>
                        <span class="block font-extrabold text-slate-900 truncate">{{ $event->formattedHarga() }}</span>
                    </div>
                    <button type="button"
                            onclick="openPaymentModal(this)"
                            data-action="{{ route('my.events.paymentProof', $event->id) }}"
                            data-event="{{ $event->nama_event }}"
                            data-organizer="{{ $event->creator->nama ?? 'Panitia Kampus' }}"
                            data-price="{{ $event->formattedHarga() }}"
                            data-code="{{ $ticketCode }}"
                            data-contact-url="{{ $contactHref }}"
                            data-contact-label="{{ $contactLabel }}"
                            data-proof-url="{{ $hasProof ? asset('storage/' . $participant->bukti_bayar) : '' }}"
                            class="btn-tactile px-3 py-1.5 rounded-xl font-bold text-[11px] flex-shrink-0 transition {{ $hasProof ? 'bg-white border border-teal-200 text-teal-700 hover:bg-teal-100' : 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs' }}">
                        <i class="fas {{ $hasProof ? 'fa-receipt' : 'fa-upload' }} mr-1"></i>
                        <span>{{ $hasProof ? 'Lihat / Ganti' : 'Bayar & Unggah' }}</span>
                    </button>
                </div>
                @endif
            </div>

            <!-- Card Bottom Actions -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-2 flex-wrap">
                <a href="{{ route('event.detail', $event->id) }}" class="text-xs font-bold text-slate-700 hover:text-indigo-600 transition flex items-center gap-1">
                    <span>Detail Acara</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>

                <div class="flex items-center gap-2">
                    @if($status === 'attended')
                    <a href="{{ route('my.events.certificate', $event->id) }}"
                       class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition btn-spring flex items-center gap-1.5">
                        <i class="fas fa-certificate"></i>
                        <span>Cetak E-Sertifikat</span>
                    </a>
                    @elseif(in_array($status, ['registered', 'confirmed']))
                    <button type="button"
                            onclick="openCancelModal('{{ route('my.events.cancel', $event->id) }}', '{{ addslashes($event->nama_event) }}')"
                            class="px-3.5 py-1.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs transition btn-spring flex items-center gap-1.5">
                        <i class="fas fa-times-circle"></i>
                        <span>Batalkan</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-md mx-auto shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Tiket</h3>
        <p class="text-xs text-slate-500 mb-6 leading-relaxed">
            Belum ada tiket kegiatan yang sesuai dengan filter ini.
        </p>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition btn-spring">
            <i class="fas fa-search"></i> Jelajahi Event Kampus
        </a>
    </div>
    @endif
</div>

@include('partials.footer')

<!-- ===== MODAL ZOOM QR TIKET ===== -->
<div id="qrZoomModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="qrZoomBackdrop" onclick="closeQrZoomModal()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>
    <div id="qrZoomCard" class="relative bg-white w-full max-w-sm rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 text-center transform scale-95 opacity-0 transition-all duration-300">
        <button type="button" onclick="closeQrZoomModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-xs hover:bg-slate-200">
            <i class="fas fa-times"></i>
        </button>
        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">E-Tiket Presensi Campven</span>
        <h3 id="qrZoomEventName" class="text-base font-extrabold text-slate-900 mt-1 mb-4"></h3>
        <div id="qrZoomSvgContainer" class="flex justify-center mb-4 p-3 bg-white rounded-2xl border border-slate-200 shadow-inner"></div>
        <div id="qrZoomCodeDisplay" class="font-mono text-xl font-black text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-2xl py-2.5 px-4 tracking-wider mb-3"></div>
        <a id="qrZoomVerifyLink" href="#" class="block w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition mb-2">
            Buka Halaman Verifikasi Tiket
        </a>
        <p class="text-[11px] text-slate-500 leading-relaxed">Tunjukkan QR di atas ke panitia atau ketik kode tiket pada form presensi.</p>
    </div>
</div>

<!-- ===== MODAL CONFIRM CANCEL EVENT ===== -->
<div id="cancelModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="cancelModalBackdrop" onclick="closeCancelModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>

    <div id="cancelModalCard" class="relative bg-white w-full max-w-sm rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 text-center transform scale-95 opacity-0 transition-all duration-300">
        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="fas fa-exclamation-triangle"></i>
        </div>

        <h3 class="text-lg font-extrabold text-slate-900 mb-1">Batalkan Pendaftaran?</h3>
        <p class="text-xs text-slate-500 mb-6 leading-relaxed">
            Apakah Anda yakin ingin membatalkan keikutsertaan pada event <span id="cancelEventTitle" class="font-bold text-slate-900"></span>? Kuota Anda akan dilepas ke mahasiswa lain.
        </p>

        <form id="cancelEventForm" method="POST" action="" class="flex items-center gap-3">
            @csrf
            <button type="button" onclick="closeCancelModal()" class="flex-1 py-3 px-4 rounded-xl border border-slate-200 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition btn-spring">
                Jangan Batalkan
            </button>
            <button type="submit" class="flex-1 py-3 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-lg shadow-rose-600/20 transition btn-spring">
                Ya, Batalkan
            </button>
        </form>
    </div>
</div>

<!-- ===== MODAL PEMBAYARAN & UNGGAH BUKTI BAYAR ===== -->
<div id="paymentProofModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="paymentProofBackdrop" onclick="closePaymentModal()" class="fixed inset-0 bg-slate-900/45 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>
    <div id="paymentProofCard" class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200/90 p-6 transform scale-95 opacity-0 transition-all duration-200 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm flex-shrink-0">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm font-extrabold text-slate-900">Instruksi Pembayaran</h3>
                    <p id="payModalEventName" class="text-[11px] text-slate-500 truncate"></p>
                </div>
            </div>
            <button type="button" onclick="closePaymentModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs transition" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
            <div class="flex items-center justify-between">
                <span class="text-slate-500">Nominal Biaya</span>
                <span id="payModalPrice" class="text-base font-black text-slate-900"></span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-500">Penyelenggara</span>
                <span id="payModalOrganizer" class="font-bold text-slate-800"></span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-500">Berita Transfer (Kode Tiket)</span>
                <span id="payModalCode" class="font-mono font-extrabold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md"></span>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 text-xs">
            <a id="payModalContactLink" href="#" target="_blank" rel="noopener noreferrer" class="hidden btn-tactile flex-1 py-2 px-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-center transition">
                <i class="fab fa-whatsapp mr-1"></i>
                <span id="payModalContactLabel">Hubungi Panitia</span>
            </a>
            <a id="payModalProofLink" href="#" target="_blank" rel="noopener noreferrer" class="hidden btn-tactile flex-1 py-2 px-3 rounded-xl bg-white border border-teal-200 text-teal-700 hover:bg-teal-50 font-bold text-center transition">
                <i class="fas fa-receipt mr-1"></i>
                <span>Lihat Bukti Terkirim</span>
            </a>
        </div>

        <form id="paymentProofForm" method="POST" action="" enctype="multipart/form-data" class="space-y-3 pt-2 border-t border-slate-100">
            @csrf
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Unggah Foto Bukti Pembayaran <span class="text-rose-500">*</span></label>
                <input type="file" name="bukti_bayar" accept="image/jpeg,image/png,image/webp" required class="block w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:cursor-pointer">
                <span class="text-[11px] text-slate-400 block">Format JPG, PNG, atau WEBP (maks. 2MB).</span>
            </div>
            <button type="submit" id="payModalSubmitBtn" class="btn-tactile w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition">
                <i class="fas fa-upload mr-1.5"></i>
                <span>Kirim Bukti Pembayaran</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const cancelModal = document.getElementById('cancelModal');
    const cancelBackdrop = document.getElementById('cancelModalBackdrop');
    const cancelCard = document.getElementById('cancelModalCard');
    const cancelForm = document.getElementById('cancelEventForm');
    const cancelTitle = document.getElementById('cancelEventTitle');

    function openCancelModal(actionUrl, eventName) {
        cancelForm.action = actionUrl;
        cancelTitle.innerText = '"' + eventName + '"';
        cancelModal.classList.remove('hidden');
        cancelModal.classList.add('flex');
        setTimeout(() => {
            cancelBackdrop.classList.remove('opacity-0');
            cancelCard.classList.remove('opacity-0', 'scale-95');
            cancelCard.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeCancelModal() {
        cancelBackdrop.classList.add('opacity-0');
        cancelCard.classList.add('opacity-0', 'scale-95');
        cancelCard.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => {
            cancelModal.classList.add('hidden');
            cancelModal.classList.remove('flex');
        }, 300);
    }

    function openQrZoomModal(btn) {
        const modal = document.getElementById('qrZoomModal');
        const backdrop = document.getElementById('qrZoomBackdrop');
        const card = document.getElementById('qrZoomCard');
        const code = btn.getAttribute('data-code');
        const eventName = btn.getAttribute('data-event');
        const url = btn.getAttribute('data-url');
        const svgClone = btn.querySelector('svg').cloneNode(true);
        svgClone.setAttribute('width', '196');
        svgClone.setAttribute('height', '196');

        const svgContainer = document.getElementById('qrZoomSvgContainer');
        svgContainer.innerHTML = '';
        svgContainer.appendChild(svgClone);

        document.getElementById('qrZoomEventName').innerText = eventName;
        document.getElementById('qrZoomCodeDisplay').innerText = code;
        document.getElementById('qrZoomVerifyLink').href = url;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            card.classList.remove('opacity-0', 'scale-95');
            card.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeQrZoomModal() {
        const modal = document.getElementById('qrZoomModal');
        const backdrop = document.getElementById('qrZoomBackdrop');
        const card = document.getElementById('qrZoomCard');
        backdrop.classList.add('opacity-0');
        card.classList.add('opacity-0', 'scale-95');
        card.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function openPaymentModal(btn) {
        const modal = document.getElementById('paymentProofModal');
        const backdrop = document.getElementById('paymentProofBackdrop');
        const card = document.getElementById('paymentProofCard');

        document.getElementById('paymentProofForm').action = btn.getAttribute('data-action');
        document.getElementById('payModalEventName').innerText = btn.getAttribute('data-event');
        document.getElementById('payModalOrganizer').innerText = btn.getAttribute('data-organizer');
        document.getElementById('payModalPrice').innerText = btn.getAttribute('data-price');
        document.getElementById('payModalCode').innerText = btn.getAttribute('data-code');

        const contactUrl = btn.getAttribute('data-contact-url');
        const contactLink = document.getElementById('payModalContactLink');
        if (contactUrl) {
            contactLink.href = contactUrl;
            document.getElementById('payModalContactLabel').innerText = btn.getAttribute('data-contact-label') || 'Hubungi Panitia';
            contactLink.classList.remove('hidden');
        } else {
            contactLink.classList.add('hidden');
        }

        const proofUrl = btn.getAttribute('data-proof-url');
        const proofLink = document.getElementById('payModalProofLink');
        const submitBtn = document.getElementById('payModalSubmitBtn');
        if (proofUrl) {
            proofLink.href = proofUrl;
            proofLink.classList.remove('hidden');
            submitBtn.innerHTML = '<i class="fas fa-upload mr-1.5"></i><span>Ganti Bukti Pembayaran</span>';
        } else {
            proofLink.classList.add('hidden');
            submitBtn.innerHTML = '<i class="fas fa-upload mr-1.5"></i><span>Kirim Bukti Pembayaran</span>';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            card.classList.remove('opacity-0', 'scale-95');
            card.classList.add('opacity-100', 'scale-100');
        });
    }

    function closePaymentModal() {
        const modal = document.getElementById('paymentProofModal');
        const backdrop = document.getElementById('paymentProofBackdrop');
        const card = document.getElementById('paymentProofCard');
        backdrop.classList.add('opacity-0');
        card.classList.add('opacity-0', 'scale-95');
        card.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
</script>
@endpush
