@extends('layouts.app')

@section('title', 'Kelola Peserta - ' . $event->nama_event)

@section('content')
<div class="min-h-screen bg-slate-50/70 flex">

    @include('partials.organizer-sidebar', ['activeOrganizer' => 'participants'])

    <div class="flex-grow md:ml-64 flex flex-col min-h-screen w-full">

        <!-- Header -->
        <header class="h-20 bg-white/95 backdrop-blur-md border-b border-slate-200/90 px-4 sm:px-8 flex items-center justify-between gap-3 sticky top-0 z-30">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" onclick="toggleOrganizerSidebar()" class="md:hidden w-10 h-10 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0" aria-label="Buka Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="min-w-0">
                    <h1 class="text-sm sm:text-base font-extrabold text-slate-900 truncate">Kelola Peserta Acara</h1>
                    <p class="text-[11px] text-slate-400 truncate">{{ $event->nama_event }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('organizer.exportReport', ['format' => 'excel', 'event_id' => $event->id]) }}" class="px-3 py-2 rounded-xl bg-teal-50 text-teal-700 border border-teal-200 font-bold text-xs hover:bg-teal-100 transition flex items-center gap-1.5" title="Unduh Tabel Rapi Excel (.xls)">
                    <i class="fas fa-file-excel"></i>
                    <span class="hidden sm:inline">Excel</span>
                </a>
                <a href="{{ route('organizer.exportReport', ['format' => 'word', 'event_id' => $event->id]) }}" class="px-3 py-2 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-200 font-bold text-xs hover:bg-indigo-100 transition flex items-center gap-1.5" title="Unduh Laporan Resmi Word (.doc)">
                    <i class="fas fa-file-word"></i>
                    <span class="hidden sm:inline">Word</span>
                </a>
                <a href="{{ route('organizer.exportCsv', $event->id) }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs hover:bg-slate-200 transition flex items-center gap-1.5" title="Unduh Data Mentah CSV">
                    <i class="fas fa-file-csv"></i>
                    <span class="hidden md:inline">CSV</span>
                </a>
                <a href="{{ route('organizer.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition flex items-center gap-1.5">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </header>

        <div class="p-4 sm:p-8 space-y-6 max-w-7xl w-full mx-auto">

            <!-- Event Info & Quick Check-In Card -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">{{ $event->kategori }}</span>
                        <span class="text-xs text-slate-500">
                            <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </span>
                    </div>
                    <h2 class="text-xl font-black text-slate-900">{{ $event->nama_event }}</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 text-center text-xs">
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="text-xl font-black text-slate-900">{{ $counts['all'] }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase">Total</div>
                        </div>
                        <div class="p-3 rounded-2xl bg-amber-50 border border-amber-100">
                            <div class="text-xl font-black text-amber-700">{{ $counts['registered'] }}</div>
                            <div class="text-[10px] font-bold text-amber-600 uppercase">Menunggu</div>
                        </div>
                        <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-100">
                            <div class="text-xl font-black text-emerald-700">{{ $counts['confirmed'] }}</div>
                            <div class="text-[10px] font-bold text-emerald-600 uppercase">Dikonfirmasi</div>
                        </div>
                        <div class="p-3 rounded-2xl bg-blue-50 border border-blue-100">
                            <div class="text-xl font-black text-blue-700">{{ $counts['attended'] }}</div>
                            <div class="text-[10px] font-bold text-blue-600 uppercase">Hadir</div>
                        </div>
                    </div>
                </div>

                <!-- Presensi Manual Khusus Acara Ini -->
                <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-user-check text-indigo-600"></i>
                            <span>Presensi Manual Acara Ini</span>
                        </h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Masukkan kode tiket (`CV-{{ $event->id }}-...`), ID tiket, atau email mahasiswa secara eksak.</p>
                    </div>
                    <form method="POST" action="{{ route('organizer.quickCheckin') }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <div class="flex gap-2">
                            <input type="text" name="query" required placeholder="Kode Tiket / ID / Email..." class="flex-grow px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-900 focus:outline-none focus:bg-white focus:border-indigo-600">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition btn-spring">
                                Catat Hadir
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Filter Status & Search -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-2 overflow-x-auto pb-1">
                    <a href="{{ route('organizer.event.participants', $event->id) }}" class="px-4 py-2 rounded-xl text-xs font-bold flex-shrink-0 {{ empty($statusFilter) ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600' }}">
                        Semua ({{ $counts['all'] }})
                    </a>
                    <a href="{{ route('organizer.event.participants', [$event->id, 'status' => 'registered']) }}" class="px-4 py-2 rounded-xl text-xs font-bold flex-shrink-0 {{ $statusFilter === 'registered' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600' }}">
                        Menunggu ({{ $counts['registered'] }})
                    </a>
                    <a href="{{ route('organizer.event.participants', [$event->id, 'status' => 'confirmed']) }}" class="px-4 py-2 rounded-xl text-xs font-bold flex-shrink-0 {{ $statusFilter === 'confirmed' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600' }}">
                        Dikonfirmasi ({{ $counts['confirmed'] }})
                    </a>
                    <a href="{{ route('organizer.event.participants', [$event->id, 'status' => 'attended']) }}" class="px-4 py-2 rounded-xl text-xs font-bold flex-shrink-0 {{ $statusFilter === 'attended' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600' }}">
                        Hadir ({{ $counts['attended'] }})
                    </a>
                    <a href="{{ route('organizer.event.participants', [$event->id, 'status' => 'canceled']) }}" class="px-4 py-2 rounded-xl text-xs font-bold flex-shrink-0 {{ $statusFilter === 'canceled' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600' }}">
                        Dibatalkan ({{ $counts['canceled'] }})
                    </a>
                </div>

                <div class="relative w-full sm:w-64">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="participantSearchInput" onkeyup="filterParticipantRows()" placeholder="Filter nama / kode tiket..." class="w-full pl-9 pr-4 py-2 rounded-xl bg-white border border-slate-200 text-xs focus:outline-none focus:border-indigo-600">
                </div>
            </div>

            <!-- Participants Table -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600" id="participantsTable">
                        <thead class="bg-slate-50/70 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">KODE TIKET</th>
                                <th class="px-4 py-3.5">PESERTA</th>
                                <th class="px-4 py-3.5">KONTAK</th>
                                <th class="px-4 py-3.5">BUKTI BAYAR</th>
                                <th class="px-4 py-3.5">STATUS</th>
                                <th class="px-6 py-3.5 text-right">AKSI PANITIA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($participants as $p)
                            @php
                                $statusMap = [
                                    'registered' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Menunggu Konfirmasi'],
                                    'confirmed'  => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Terkonfirmasi'],
                                    'attended'   => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Sudah Hadir'],
                                    'canceled'   => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'label' => 'Dibatalkan'],
                                ];
                                $st = $statusMap[$p->status] ?? ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'label' => ucfirst($p->status)];
                                $isPaidEvent = ((int) ($event->harga ?? 0)) > 0;
                                $hasProof = $p->bukti_bayar && \Illuminate\Support\Facades\Storage::disk('public')->exists($p->bukti_bayar);
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition participant-row">
                                <td class="px-6 py-4 font-mono font-extrabold text-indigo-700 whitespace-nowrap">
                                    {{ $p->ticketCode() }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-bold text-slate-900">{{ $p->user->nama ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $p->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-4 text-slate-500">
                                    {{ $p->user->no_telp ?? '-' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if(!$isPaidEvent)
                                        <span class="text-[11px] text-teal-600 font-semibold">Gratis</span>
                                    @elseif($hasProof)
                                        <a href="{{ asset('storage/' . $p->bukti_bayar) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 font-bold text-[11px] transition">
                                            <i class="fas fa-receipt text-[10px]"></i>
                                            <span>Lihat Bukti</span>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-amber-600 font-semibold">Belum unggah</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold border {{ $st['bg'] }} whitespace-nowrap">
                                        {{ $st['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        @if($p->status === 'registered')
                                        <form method="POST" action="{{ route('organizer.participant.confirm', $p->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition">
                                                Konfirmasi
                                            </button>
                                        </form>
                                        @endif

                                        @if(in_array($p->status, ['registered', 'confirmed']))
                                        <form method="POST" action="{{ route('organizer.participant.attend', $p->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-[11px] transition">
                                                Tandai Hadir
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('organizer.participant.cancel', $p->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-bold text-[11px] transition">
                                                Tolak
                                            </button>
                                        </form>
                                        @elseif($p->status === 'attended')
                                        <span class="text-[11px] text-emerald-600 font-bold"><i class="fas fa-check-double mr-1"></i> Selesai Presensi</span>
                                        @else
                                        <span class="text-[11px] text-slate-400">Tidak aktif</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center text-slate-400">
                                    Belum ada peserta yang sesuai dengan filter ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function filterParticipantRows() {
        const q = document.getElementById('participantSearchInput').value.toLowerCase();
        document.querySelectorAll('.participant-row').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    }
</script>
@endpush

