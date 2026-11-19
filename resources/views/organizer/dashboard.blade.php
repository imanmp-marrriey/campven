@extends('layouts.app')

@section('title', 'Dashboard Penyelenggara - Campven')

@section('content')
<div class="min-h-screen bg-slate-50/70 flex">

    @include('partials.organizer-sidebar', ['activeOrganizer' => 'dashboard'])

    <!-- ===== MAIN CONTENT AREA ===== -->
    <div class="flex-grow md:ml-64 flex flex-col min-h-screen w-full">

        <!-- Top Operational Header -->
        <header class="h-20 bg-white/95 backdrop-blur-md border-b border-slate-200/90 px-4 sm:px-8 flex items-center justify-between gap-3 sticky top-0 z-30">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <button type="button" onclick="toggleOrganizerSidebar()" class="md:hidden w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center flex-shrink-0" aria-label="Buka Menu">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="relative w-full max-w-sm">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="organizerSearchInput" onkeyup="filterOrganizerSearch()" placeholder="Cari acara atau lokasi..." class="w-full pl-10 pr-12 py-2 rounded-full bg-slate-100 border border-transparent focus:border-slate-300 focus:bg-white text-xs text-slate-800 placeholder-slate-400 focus:outline-none transition">
                    <kbd class="hidden sm:inline-block absolute right-3 top-1/2 -translate-y-1/2 px-1.5 py-0.5 rounded bg-white border border-slate-200 text-[10px] font-bold text-slate-400">⌘K</kbd>
                </div>
            </div>

            <!-- Right Header Actions -->
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('profile') }}" class="flex items-center gap-2.5 pl-2">
                    <div class="w-9 h-9 rounded-full overflow-hidden bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                        @if($user->foto && Storage::disk('public')->exists($user->foto))
                            <img src="{{ asset('storage/' . $user->foto) }}?v={{ optional($user->updated_at)->timestamp }}" alt="Foto" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                        @endif
                    </div>
                    <div class="text-left hidden sm:block">
                        <span class="block text-xs font-bold text-slate-900 leading-tight">{{ $user->nama }}</span>
                        <span class="text-[10px] font-medium text-slate-400">Penyelenggara</span>
                    </div>
                </a>
            </div>
        </header>

        <!-- Dashboard Body Container -->
        <div class="p-4 sm:p-8 space-y-8 max-w-7xl w-full mx-auto">

            <!-- Welcome Hero & Toolbar -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-2">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-slate-200 text-[11px] font-bold text-slate-600 mb-2 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        <span>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Selamat Datang, {{ $user->nama }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xl">
                        Pusat kendali publikasi acara, manajemen peserta, presensi manual tiket, dan ekspor data kegiatan Anda.
                    </p>
                </div>

                <!-- Action Toolbar -->
                <div class="flex items-center gap-2.5 flex-wrap">
                    <a href="{{ route('organizer.exportReport', ['format' => 'excel']) }}" class="px-4 py-3 rounded-2xl bg-teal-50 border border-teal-200 hover:bg-teal-100 text-teal-800 font-bold text-xs shadow-sm transition btn-spring flex items-center gap-2">
                        <i class="fas fa-file-excel text-teal-600"></i>
                        <span>Ekspor Excel</span>
                    </a>

                    <a href="{{ route('organizer.exportReport', ['format' => 'word']) }}" class="px-4 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-sm transition btn-spring flex items-center gap-2">
                        <i class="fas fa-file-word text-indigo-600"></i>
                        <span>Ekspor Word</span>
                    </a>

                    <button type="button" onclick="openCreateModal()" class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition btn-spring flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Buat Event Baru</span>
                    </button>
                </div>
            </div>

            <!-- 4 KPI Metric Cards (Real Database Metrics) -->
            <div id="kpiCards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">TOTAL ACARA DIADAKAN</span>
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $total_events }} <span class="text-xs font-semibold text-slate-400">Acara</span></div>
                    <div class="text-[11px] font-semibold text-slate-500 pt-2 border-t border-slate-100">
                        <span class="text-indigo-600 font-bold">{{ $upcoming_events_count }}</span> acara mendatang
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">PESERTA AKTIF TERDAFTAR</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($total_participants) }} <span class="text-xs font-semibold text-slate-400">Mahasiswa</span></div>
                    <div class="text-[11px] font-semibold text-slate-500 pt-2 border-t border-slate-100">
                        Tidak termasuk peserta yang dibatalkan
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">TINGKAT KEHADIRAN</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $attendance_rate }}%</div>
                    <div class="text-[11px] font-semibold text-slate-500 pt-2 border-t border-slate-100">
                        <span class="text-emerald-600 font-bold">{{ $attended_participants }}</span> dari {{ $total_participants }} terverifikasi hadir
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">OKUPANSI KUOTA ACARA</span>
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $quota_rate }}%</div>
                    <div class="space-y-1 pt-2 border-t border-slate-100">
                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $quota_rate }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Operational Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Left Column: Event Roster Table -->
                <div class="lg:col-span-8 space-y-8">
                    <div id="eventsTable" class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
                        <div class="p-6 pb-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2.5">
                                    <h2 class="text-lg font-extrabold text-slate-900">Daftar Acara &amp; Pengelolaan</h2>
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold text-xs">{{ $total_events }} Total</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Kelola informasi acara, kuota, dan konfirmasi/presensi peserta per event</p>
                            </div>

                            <!-- Status Filter Tabs -->
                            <div class="flex items-center gap-1.5 p-1 rounded-2xl bg-slate-100 text-xs font-bold self-start">
                                <button type="button" id="tabAll" onclick="filterOrganizerEvents('all')" class="px-3 py-1.5 rounded-xl bg-white text-indigo-700 shadow-sm transition">Semua ({{ $total_events }})</button>
                                <button type="button" id="tabUpcoming" onclick="filterOrganizerEvents('upcoming')" class="px-3 py-1.5 rounded-xl text-slate-500 hover:text-slate-800 transition">Mendatang</button>
                                <button type="button" id="tabPast" onclick="filterOrganizerEvents('past')" class="px-3 py-1.5 rounded-xl text-slate-500 hover:text-slate-800 transition">Selesai</button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-600" id="organizerEventsTable">
                                <thead class="bg-slate-50/70 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-3.5">INFO ACARA</th>
                                        <th class="px-4 py-3.5">KATEGORI</th>
                                        <th class="px-4 py-3.5">PELAKSANAAN &amp; KUOTA</th>
                                        <th class="px-6 py-3.5 text-right">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($events as $ev)
                                    @php
                                        $activeCount = $ev->activeParticipants()->count();
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition organizer-event-row" data-date="{{ \Carbon\Carbon::parse($ev->tanggal)->format('Y-m-d') }}" data-name="{{ strtolower($ev->nama_event . ' ' . $ev->lokasi . ' ' . $ev->kategori) }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3.5">
                                                <div class="w-12 h-12 rounded-2xl overflow-hidden bg-slate-900 flex-shrink-0">
                                                    @if($ev->poster && Storage::disk('public')->exists($ev->poster))
                                                        <img src="{{ Storage::url($ev->poster) }}" alt="{{ $ev->nama_event }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-white text-base bg-indigo-950 font-bold">
                                                            {{ strtoupper(substr($ev->nama_event, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="min-w-0 max-w-[220px]">
                                                    <a href="{{ route('event.detail', $ev->id) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition truncate block text-sm">
                                                        {{ $ev->nama_event }}
                                                    </a>
                                                    <span class="text-[11px] text-slate-400 truncate block mt-0.5">
                                                        <i class="fas fa-map-marker-alt text-rose-500 mr-1"></i> {{ $ev->lokasi }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200 whitespace-nowrap">
                                                {{ $ev->kategori }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="font-semibold text-slate-800 whitespace-nowrap">{{ \Carbon\Carbon::parse($ev->tanggal)->locale('id')->isoFormat('D MMM Y') }}</div>
                                            <div class="text-[10px] text-slate-500 mt-0.5 whitespace-nowrap">
                                                {{ $activeCount }}{{ $ev->kuota ? ' / ' . $ev->kuota : '' }} peserta • {{ $ev->skpi_poin ?? 0 }} SKPI
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <a href="{{ route('organizer.event.participants', $ev->id) }}" class="px-3 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition whitespace-nowrap inline-flex items-center gap-1" title="Kelola &amp; Konfirmasi Peserta">
                                                    <i class="fas fa-users"></i>
                                                    <span>Peserta</span>
                                                </a>
                                                <a href="{{ route('organizer.event.edit', $ev->id) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition" title="Edit Event">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </a>
                                                @if($activeCount > 0)
                                                    <button type="button" disabled class="p-2 rounded-xl bg-slate-100 text-slate-300 cursor-not-allowed" title="Tidak dapat dihapus karena masih memiliki peserta aktif">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                @else
                                                    <button type="button" onclick="openDeleteModal('{{ route('organizer.event.delete', $ev->id) }}', '{{ addslashes($ev->nama_event) }}')" class="p-2 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-500 transition" title="Hapus Event">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-slate-400">
                                            Belum ada acara yang Anda buat. Klik tombol "Buat Event Baru" di atas.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="p-4 px-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <span>Menampilkan {{ count($events) }} acara milik Anda</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Manual Attendance Check-in Panel -->
                <div class="lg:col-span-4 space-y-6">
                    <div id="checkinPanel" class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>Presensi Manual Peserta</span>
                                </h3>
                                <p class="text-[11px] text-slate-400 mt-0.5">Ketik kode tiket / ID / email eksak atau gunakan barcode scanner</p>
                            </div>
                        </div>

                        <!-- Live Session Attendance Summary -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                            <div class="flex items-center justify-between text-xs font-bold">
                                <span class="text-slate-600">Total Kehadiran Semua Acara</span>
                                <span class="text-emerald-600 font-black">{{ $attendance_rate }}%</span>
                            </div>
                            <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $attendance_rate }}%;"></div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span><strong>{{ $attended_participants }}</strong> Hadir</span>
                                <span>{{ $total_participants }} Peserta Aktif</span>
                            </div>
                        </div>

                        <!-- Form Presensi Manual -->
                        @if(count($events) > 0)
                        <form method="POST" action="{{ route('organizer.quickCheckin') }}" class="space-y-3">
                            @csrf
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-600">Pilih Acara</label>
                                <select name="event_id" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 font-semibold focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                                    @foreach($events as $ev)
                                        <option value="{{ $ev->id }}" {{ $active_event && $active_event->id === $ev->id ? 'selected' : '' }}>
                                            {{ $ev->nama_event }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-600">Kode Tiket / ID / Email Peserta</label>
                                <div class="flex gap-2">
                                    <input type="text" name="query" required placeholder="Contoh: CV-1-5 atau email@..." class="flex-grow px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition btn-spring whitespace-nowrap">
                                        Presensi
                                    </button>
                                </div>
                            </div>
                        </form>
                        @else
                        <div class="p-4 rounded-2xl bg-slate-50 text-center text-xs text-slate-400">
                            Buat acara terlebih dahulu untuk mencatat presensi peserta.
                        </div>
                        @endif

                        <!-- Aktivitas Presensi Terbaru -->
                        <div class="space-y-3 pt-2 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-extrabold text-slate-900">Presensi Terverifikasi Terbaru</span>
                            </div>

                            <div class="space-y-2">
                                @forelse($recent_attendances as $rec)
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center gap-2.5 truncate">
                                        <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($rec->user->nama ?? 'M', 0, 1)) }}
                                        </div>
                                        <div class="truncate">
                                            <span class="font-bold text-slate-900 block truncate">{{ $rec->user->nama ?? '-' }}</span>
                                            <span class="text-[10px] text-slate-400 block truncate">{{ $rec->ticketCode() }} • {{ $rec->event->nama_event ?? '' }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px]">Hadir</span>
                                        <span class="block text-[9px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($rec->updated_at)->format('H:i') }} WIB</span>
                                    </div>
                                </div>
                                @empty
                                <div class="p-4 text-center text-xs text-slate-400 bg-slate-50 rounded-2xl">
                                    Belum ada peserta yang tercatat hadir.
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <a href="{{ route('organizer.exportCsv') }}" class="w-full py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition flex items-center justify-center gap-2">
                            <i class="fas fa-file-export text-slate-400"></i>
                            <span>Ekspor Log Peserta (CSV)</span>
                        </a>
                    </div>

                    <!-- Organizer Identity Card -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-lg font-bold">
                                <i class="fas fa-university"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-slate-900 flex items-center gap-1.5">
                                    <span>{{ $user->nama }}</span>
                                    <i class="fas fa-check-circle text-indigo-600 text-[11px]"></i>
                                </h4>
                                <p class="text-[10px] text-slate-500">Akun Penyelenggara Resmi</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs pt-3 border-t border-slate-100">
                            <span class="text-slate-400 text-[11px]">ID Organisasi: #ORG-{{ $user->id }}</span>
                            <a href="{{ route('profile') }}" class="font-bold text-indigo-600 hover:underline">
                                Pengaturan Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL BUAT EVENT BARU ===== -->
<div id="createEventModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="createModalBackdrop" onclick="closeCreateModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>

    <div id="createModalCard" class="relative bg-white w-full max-w-xl rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fas fa-calendar-plus text-indigo-600"></i>
                <span>Publikasikan Event Kampus Baru</span>
            </h3>
            <button type="button" onclick="closeCreateModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm hover:bg-slate-200 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('organizer.event.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nama Acara / Event <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_event" value="{{ old('nama_event') }}" required placeholder="Contoh: Seminar AI & Web Modern 2026" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Kategori <span class="text-rose-500">*</span></label>
                    <select name="kategori" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                        @foreach($categories as $c)
                            <option value="{{ $c->nama }}" {{ old('kategori') === $c->nama ? 'selected' : '' }}>{{ $c->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal') }}" min="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                </div>
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai') }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Lokasi / Gedung Acara <span class="text-rose-500">*</span></label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" required placeholder="Contoh: Auditorium Utama Gd. D" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Batas Kuota</label>
                    <input type="number" name="kuota" value="{{ old('kuota') }}" min="1" placeholder="Unlimited" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                </div>
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Harga Tiket (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga', 0) }}" min="0" placeholder="0 = GRATIS" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                </div>
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Poin SKPI</label>
                    <input type="number" name="skpi_poin" value="{{ old('skpi_poin', 10) }}" min="0" max="100" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Poster Acara</label>
                <input type="file" name="poster" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Deskripsi Lengkap Acara</label>
                <textarea name="deskripsi" rows="4" placeholder="Jelaskan topik materi, pembicara, dan persyaratan peserta..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCreateModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/20 transition btn-spring">
                    Simpan &amp; Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const createModal = document.getElementById('createEventModal');
    const createBackdrop = document.getElementById('createModalBackdrop');
    const createCard = document.getElementById('createModalCard');

    function openCreateModal() {
        createModal.classList.remove('hidden');
        createModal.classList.add('flex');
        setTimeout(() => {
            createBackdrop.classList.remove('opacity-0');
            createCard.classList.remove('opacity-0', 'scale-95');
            createCard.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeCreateModal() {
        createBackdrop.classList.add('opacity-0');
        createCard.classList.add('opacity-0', 'scale-95');
        createCard.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => {
            createModal.classList.add('hidden');
            createModal.classList.remove('flex');
        }, 300);
    }

    function filterOrganizerEvents(type) {
        const rows = document.querySelectorAll('.organizer-event-row');
        const todayStr = '{{ date("Y-m-d") }}';

        document.getElementById('tabAll').className = 'px-3 py-1.5 rounded-xl text-slate-500 hover:text-slate-800 transition';
        document.getElementById('tabUpcoming').className = 'px-3 py-1.5 rounded-xl text-slate-500 hover:text-slate-800 transition';
        document.getElementById('tabPast').className = 'px-3 py-1.5 rounded-xl text-slate-500 hover:text-slate-800 transition';

        if (type === 'all') {
            document.getElementById('tabAll').className = 'px-3 py-1.5 rounded-xl bg-white text-indigo-700 shadow-sm font-bold transition';
            rows.forEach(r => r.style.display = '');
        } else if (type === 'upcoming') {
            document.getElementById('tabUpcoming').className = 'px-3 py-1.5 rounded-xl bg-white text-indigo-700 shadow-sm font-bold transition';
            rows.forEach(r => {
                r.style.display = (r.getAttribute('data-date') >= todayStr) ? '' : 'none';
            });
        } else if (type === 'past') {
            document.getElementById('tabPast').className = 'px-3 py-1.5 rounded-xl bg-white text-indigo-700 shadow-sm font-bold transition';
            rows.forEach(r => {
                r.style.display = (r.getAttribute('data-date') < todayStr) ? '' : 'none';
            });
        }
    }

    function filterOrganizerSearch() {
        const query = document.getElementById('organizerSearchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.organizer-event-row');
        rows.forEach(r => {
            const name = r.getAttribute('data-name');
            r.style.display = name.includes(query) ? '' : 'none';
        });
    }

    document.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
            const input = document.getElementById('organizerSearchInput');
            if (input) {
                e.preventDefault();
                input.focus();
            }
        }
    });
</script>
@endpush
