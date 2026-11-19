@extends('layouts.app')

@section('title', 'Tiket & Event Saya - Campven')

@section('content')
<!-- ===== TOP MODERN STICKY NAVBAR ===== -->
<nav class="sticky top-0 z-50 glass-nav transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-slate-900 to-slate-700 flex items-center justify-center text-white font-extrabold text-xl shadow-md shadow-slate-900/10 group-hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-cubes text-sm"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold text-slate-900 tracking-tight">Camp<span class="text-indigo-600">ven</span></span>
                    <span class="block text-[10px] font-semibold text-slate-400 tracking-wider uppercase -mt-1">Campus Events</span>
                </div>
            </a>

            <!-- Right Nav Actions -->
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-bold px-4 py-2.5 rounded-full border border-slate-200 text-slate-700 hover:bg-slate-50 transition btn-spring">
                    <i class="fas fa-arrow-left text-slate-400"></i>
                    <span>Cari Event Lain</span>
                </a>

                <a href="{{ route('profile') }}" class="w-9 h-9 rounded-full overflow-hidden bg-slate-800 text-white flex items-center justify-center font-bold text-sm shadow-sm ring-2 ring-slate-200">
                    @if(Auth::user()->foto && Storage::disk('public')->exists(Auth::user()->foto))
                        <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    @endif
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Header Hero Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 rounded-3xl p-6 sm:p-10 text-white shadow-xl mb-10 relative overflow-hidden">
        <!-- Abstract Shapes -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/10 backdrop-blur-md text-indigo-300 mb-3 border border-white/10">
                    <i class="fas fa-ticket-alt"></i> Portal Tiket Mahasiswa
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">Event & Kegiatan Saya</h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-2 max-w-xl leading-relaxed">
                    Pantau status verifikasi pendaftaran, jadwal pelaksanaan acara, dan tiket kegiatan kampus yang Anda ikuti.
                </p>
            </div>

            <!-- Stats KPI Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 text-center">
                <div class="p-2">
                    <div class="text-xl sm:text-2xl font-extrabold text-white">{{ count($my_events) }}</div>
                    <div class="text-[10px] sm:text-xs text-slate-400 uppercase font-semibold mt-0.5">Total Diikuti</div>
                </div>
                <div class="p-2 border-l border-white/10">
                    <div class="text-xl sm:text-2xl font-extrabold text-amber-400">{{ $status_counts['registered'] ?? 0 }}</div>
                    <div class="text-[10px] sm:text-xs text-slate-400 uppercase font-semibold mt-0.5">Terdaftar</div>
                </div>
                <div class="p-2 border-l border-white/10">
                    <div class="text-xl sm:text-2xl font-extrabold text-emerald-400">{{ $status_counts['confirmed'] ?? 0 }}</div>
                    <div class="text-[10px] sm:text-xs text-slate-400 uppercase font-semibold mt-0.5">Dikonfirmasi</div>
                </div>
                <div class="p-2 border-l border-white/10">
                    <div class="text-xl sm:text-2xl font-extrabold text-blue-400">{{ $status_counts['attended'] ?? 0 }}</div>
                    <div class="text-[10px] sm:text-xs text-slate-400 uppercase font-semibold mt-0.5">Sudah Hadir</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    @php
        $currStatus = request('status');
    @endphp
    <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 scrollbar-none">
        <a href="{{ route('my.events') }}" 
           class="px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 flex-shrink-0 {{ empty($currStatus) ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <i class="fas fa-layer-group"></i>
            <span>Semua Tiket</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ empty($currStatus) ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ count($my_events) }}</span>
        </a>

        <a href="{{ route('my.events', ['status' => 'registered']) }}" 
           class="px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 flex-shrink-0 {{ $currStatus === 'registered' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <i class="fas fa-clock text-amber-500"></i>
            <span>Menunggu Konfirmasi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $currStatus === 'registered' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $status_counts['registered'] ?? 0 }}</span>
        </a>

        <a href="{{ route('my.events', ['status' => 'confirmed']) }}" 
           class="px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 flex-shrink-0 {{ $currStatus === 'confirmed' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <i class="fas fa-check-circle text-emerald-500"></i>
            <span>Terkonfirmasi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $currStatus === 'confirmed' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $status_counts['confirmed'] ?? 0 }}</span>
        </a>

        <a href="{{ route('my.events', ['status' => 'attended']) }}" 
           class="px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 flex-shrink-0 {{ $currStatus === 'attended' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <i class="fas fa-check-double text-blue-500"></i>
            <span>Hadir</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $currStatus === 'attended' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $status_counts['attended'] ?? 0 }}</span>
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($filteredEvents as $participant)
        @php
            $event = $participant->event;
            $status = $participant->status;
            $statusStyles = [
                'registered' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'dot' => 'bg-amber-500', 'label' => 'Menunggu Konfirmasi'],
                'confirmed'  => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Terkonfirmasi'],
                'attended'   => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'dot' => 'bg-blue-500', 'label' => 'Sudah Hadir'],
                'canceled'   => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'dot' => 'bg-rose-500', 'label' => 'Dibatalkan'],
            ];
            $st = $statusStyles[$status] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'dot' => 'bg-slate-400', 'label' => ucfirst($status)];
        @endphp

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden relative group">
            <!-- Top Card Header -->
            <div class="p-6 pb-4">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $event->kategori }}
                    </span>

                    <!-- Status Pill -->
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $st['bg'] }} {{ $st['text'] }} border {{ $st['border'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span>
                        {{ $st['label'] }}
                    </span>
                </div>

                <a href="{{ route('event.detail', $event->id) }}" class="block group-hover:text-indigo-600 transition-colors">
                    <h3 class="text-base font-extrabold text-slate-900 leading-snug line-clamp-2 mb-3">
                        {{ $event->nama_event }}
                    </h3>
                </a>

                <!-- Meta Details -->
                <div class="space-y-2 text-xs text-slate-600">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calendar-day text-slate-400 w-4"></i>
                        <span>{{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    @if($event->waktu_mulai)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock text-slate-400 w-4"></i>
                        <span>{{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i') }} WIB</span>
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
            </div>

            <!-- Card Bottom Actions -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                <a href="{{ route('event.detail', $event->id) }}" class="text-xs font-bold text-slate-700 hover:text-slate-900 transition flex items-center gap-1">
                    <span>Lihat Info</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>

                @if(in_array($status, ['registered', 'confirmed']))
                <button type="button" 
                        onclick="openCancelModal('{{ route('my.events.cancel', $event->id) }}', '{{ addslashes($event->nama_event) }}')" 
                        class="px-3.5 py-1.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs transition btn-spring flex items-center gap-1.5">
                    <i class="fas fa-times-circle"></i>
                    <span>Batalkan</span>
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-md mx-auto shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Tiket</h3>
        <p class="text-xs text-slate-500 mb-6 leading-relaxed">
            Anda belum mendaftar kegiatan kampus dengan filter ini. Mari cari kegiatan baru yang menarik!
        </p>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition btn-spring">
            <i class="fas fa-search"></i> Jelajahi Event Kampus
        </a>
    </div>
    @endif
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
</script>
@endpush
