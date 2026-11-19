@extends('layouts.app')

@section('title', 'Peserta Acara (Admin) - ' . $event->nama_event)

@section('content')
<div class="min-h-screen bg-slate-50/70 flex">

    @include('partials.admin-sidebar', ['activeAdmin' => 'dashboard'])

    <div class="flex-grow md:ml-64 flex flex-col min-h-screen w-full">

        <!-- Header -->
        <header class="h-20 bg-white/95 backdrop-blur-md border-b border-slate-200/90 px-4 sm:px-8 flex items-center justify-between gap-3 sticky top-0 z-30">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" onclick="toggleAdminSidebar()" class="md:hidden w-10 h-10 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0" aria-label="Buka Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="min-w-0">
                    <h1 class="text-sm sm:text-base font-extrabold text-slate-900 truncate">Daftar Peserta Acara (Pemantauan Admin)</h1>
                    <p class="text-[11px] text-slate-400 truncate">{{ $event->nama_event }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('admin.event.exportCsv', $event->id) }}" class="px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-xs hover:bg-emerald-100 transition flex items-center gap-1.5">
                    <i class="fas fa-file-csv"></i>
                    <span class="hidden sm:inline">Ekspor CSV</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition flex items-center gap-1.5">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </header>

        <div class="p-4 sm:p-8 space-y-6 max-w-7xl w-full mx-auto">

            <!-- Ringkasan -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">{{ $event->nama_event }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Penyelenggara: <strong>{{ $event->creator->nama ?? 'Admin' }}</strong> • {{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('D MMMM Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center text-xs pt-2">
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
                    <div class="p-3 rounded-2xl bg-rose-50 border border-rose-100">
                        <div class="text-xl font-black text-rose-700">{{ $counts['canceled'] }}</div>
                        <div class="text-[10px] font-bold text-rose-600 uppercase">Dibatalkan</div>
                    </div>
                </div>
            </div>

            <!-- Filter Status -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <a href="{{ route('admin.event.participants', $event->id) }}" class="px-4 py-2 rounded-xl text-xs font-bold flex-shrink-0 {{ empty($statusFilter) ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600' }}">Semua</a>
                <a href="{{ route('admin.event.participants', [$event->id, 'status' => 'registered']) }}" class="px-4 py-2 rounded-xl text-xs font-bold flex-shrink-0 {{ $statusFilter === 'registered' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600' }}">Menunggu</a>
                <a href="{{ route('admin.event.participants', [$event->id, 'status' => 'confirmed']) }}" class="px-4 py-2 rounded-xl text-xs font-bold flex-shrink-0 {{ $statusFilter === 'confirmed' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600' }}">Dikonfirmasi</a>
                <a href="{{ route('admin.event.participants', [$event->id, 'status' => 'attended']) }}" class="px-4 py-2 rounded-xl text-xs font-bold flex-shrink-0 {{ $statusFilter === 'attended' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600' }}">Hadir</a>
                <a href="{{ route('admin.event.participants', [$event->id, 'status' => 'canceled']) }}" class="px-4 py-2 rounded-xl text-xs font-bold flex-shrink-0 {{ $statusFilter === 'canceled' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600' }}">Dibatalkan</a>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50/70 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">KODE TIKET</th>
                                <th class="px-4 py-3.5">MAHASISWA</th>
                                <th class="px-4 py-3.5">NO. TELEPON</th>
                                <th class="px-4 py-3.5">STATUS</th>
                                <th class="px-6 py-3.5 text-right">WAKTU DAFTAR</th>
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
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-mono font-extrabold text-indigo-700 whitespace-nowrap">{{ $p->ticketCode() }}</td>
                                <td class="px-4 py-4">
                                    <div class="font-bold text-slate-900">{{ $p->user->nama ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $p->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-4">{{ $p->user->no_telp ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold border {{ $st['bg'] }} whitespace-nowrap">{{ $st['label'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-slate-400 whitespace-nowrap">
                                    {{ $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-slate-400">Belum ada peserta pada acara ini.</td>
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

