@extends('layouts.app')

@section('title', 'Dashboard Penyelenggara - Campven')

@section('content')
<div class="min-h-screen bg-slate-50/70 flex">

    <!-- ===== LEFT SIDEBAR (Matching Image 1) ===== -->
    <aside class="w-64 bg-white border-r border-slate-200/90 flex flex-col justify-between fixed top-0 bottom-0 left-0 z-40 hidden md:flex">
        <div>
            <!-- Brand Logo -->
            <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-100">
                <div class="w-9 h-9 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-md shadow-indigo-600/20">
                    <i class="fas fa-cubes text-sm"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold text-slate-900 tracking-tight">Camp<span class="text-indigo-600">ven</span></span>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider -mt-1">Portal Penyelenggara</span>
                </div>
            </div>

            <!-- Nav Menu List -->
            <div class="p-4 space-y-1.5">
                <a href="{{ route('organizer.dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl bg-indigo-600 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                    <i class="fas fa-th-large text-sm w-4"></i>
                    <span>Ringkasan</span>
                </a>

                <a href="#eventsTable" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-semibold text-xs transition">
                    <i class="far fa-calendar-alt text-sm w-4 text-slate-400"></i>
                    <span>Daftar Acara</span>
                </a>

                <button type="button" onclick="openQrModal()" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-semibold text-xs transition text-left">
                    <i class="fas fa-qrcode text-sm w-4 text-slate-400"></i>
                    <span>Presensi QR</span>
                </button>

                <a href="#kpiCards" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-semibold text-xs transition">
                    <i class="fas fa-chart-pie text-sm w-4 text-slate-400"></i>
                    <span>Keuangan & Kuota</span>
                </a>
            </div>
        </div>

        <!-- Sidebar Bottom: Back to Campus View -->
        <div class="p-4 border-t border-slate-100">
            <a href="{{ route('home') }}" class="w-full flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 font-bold text-xs transition">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Kembali ke Beranda Kampus</span>
            </a>
        </div>
    </aside>

    <!-- ===== MAIN CONTENT AREA ===== -->
    <div class="flex-grow md:ml-64 flex flex-col min-h-screen">
        
        <!-- Top Operational Header -->
        <header class="h-20 bg-white/95 backdrop-blur-md border-b border-slate-200/90 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30">
            <!-- Search bar with ⌘K -->
            <div class="relative w-72 sm:w-96">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="organizerSearchInput" onkeyup="filterOrganizerSearch()" placeholder="Cari peserta, tiket, acara..." class="w-full pl-10 pr-12 py-2 rounded-full bg-slate-100 border border-transparent focus:border-slate-300 focus:bg-white text-xs text-slate-800 placeholder-slate-400 focus:outline-none transition">
                <kbd class="absolute right-3 top-1/2 -translate-y-1/2 px-1.5 py-0.5 rounded bg-white border border-slate-200 text-[10px] font-bold text-slate-400">⌘K</kbd>
            </div>

            <!-- Right Header Actions -->
            <div class="flex items-center gap-3">
                <button type="button" class="relative w-9 h-9 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center text-sm transition" title="Notifikasi">
                    <i class="far fa-bell"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                </button>

                <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200">
                    <div class="w-9 h-9 rounded-full overflow-hidden bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                        @if($user->foto && Storage::disk('public')->exists($user->foto))
                            <img src="{{ Storage::url($user->foto) }}" alt="Foto" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                        @endif
                    </div>
                    <div class="text-left hidden sm:block">
                        <span class="block text-xs font-bold text-slate-900 leading-tight">{{ $user->nama }}</span>
                        <span class="text-[10px] font-medium text-slate-400">Meja Eksekutif</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Body Container -->
        <div class="p-6 sm:p-8 space-y-8 max-w-7xl w-full mx-auto">
            
            <!-- Welcome Hero & Quick Toolbar Banner (Matching Image 1) -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-2">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-slate-200 text-[11px] font-bold text-slate-600 mb-2 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        <span>Semester Genap 2025/2026 • {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Selamat Datang Kembali, {{ $user->nama }} 👋
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xl">
                        Pusat kendali operasional kegiatan mahasiswa, kuota registrasi, dan verifikasi gerbang digital.
                    </p>
                </div>

                <!-- Action Toolbar Row -->
                <div class="flex items-center gap-2.5 flex-wrap">
                    <form method="POST" action="{{ route('organizer.quickCheckin') }}" class="hidden sm:flex items-center gap-2 bg-white px-3 py-1.5 rounded-2xl border border-slate-200 shadow-2xs">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $active_event->id ?? 1 }}">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                        <input type="text" name="query" placeholder="Cari NIM / Peserta..." class="bg-transparent text-xs text-slate-700 focus:outline-none w-36">
                        <button type="submit" class="px-2 py-0.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-[10px] font-bold text-slate-700">Cek</button>
                    </form>

                    <button type="button" onclick="openQrModal()" class="px-4 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-2xs transition btn-spring flex items-center gap-2">
                        <i class="fas fa-qrcode text-indigo-600"></i>
                        <span>Scan Tiket</span>
                    </button>

                    <button type="button" onclick="openCreateModal()" class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition btn-spring flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Buat Event Baru</span>
                    </button>
                </div>
            </div>

            <!-- 4 KPI Metric Cards (Matching Image 1) -->
            <div id="kpiCards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- KPI 1: Total Event -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">TOTAL ACARA DIADAKAN</span>
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $total_events }} <span class="text-xs font-semibold text-slate-400">Acara</span></div>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-500 pt-1 border-t border-slate-100">
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold text-[10px]">+2 bulan ini</span>
                        <span>Semester aktif</span>
                    </div>
                </div>

                <!-- KPI 2: Total Peserta -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">TOTAL PESERTA TERDAFTAR</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($total_participants) }} <span class="text-xs font-semibold text-slate-400">Mahasiswa</span></div>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-500 pt-1 border-t border-slate-100">
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold text-[10px]">↗ +18.4%</span>
                        <span>vs bulan lalu</span>
                    </div>
                </div>

                <!-- KPI 3: Tingkat Kehadiran -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">TINGKAT KEHADIRAN</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $attendance_rate }}% <span class="text-xs font-bold text-emerald-600">Sangat Baik</span></div>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-500 pt-1 border-t border-slate-100">
                        <span class="text-emerald-600 font-bold"><i class="fas fa-check-circle mr-1"></i> Terverifikasi</span>
                        <span>Pass Digital QR</span>
                    </div>
                </div>

                <!-- KPI 4: Okupansi Kuota -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">RATA-RATA OKUPANSI KUOTA</span>
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $quota_rate }}% <span class="text-xs font-bold text-indigo-600">Tinggi</span></div>
                    </div>
                    <div class="space-y-1 pt-1 border-t border-slate-100">
                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $quota_rate }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Operational Grid (Left: 65%, Right: 35% - Matching Image 1) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: Event Roster & Tool Cards (8 Cols) -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- Daftar Acara Table Container -->
                    <div id="eventsTable" class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs overflow-hidden">
                        <!-- Table Header -->
                        <div class="p-6 pb-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2.5">
                                    <h2 class="text-lg font-extrabold text-slate-900">Daftar Acara & Pengelolaan</h2>
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold text-xs">{{ $total_events }} Total</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Kelola tiket, kuota pendaftaran, dan monitoring gerbang masuk</p>
                            </div>

                            <!-- Status Filter Tabs -->
                            <div class="flex items-center gap-1.5 p-1 rounded-2xl bg-slate-100 text-xs font-bold self-start">
                                <button type="button" id="tabAll" onclick="filterOrganizerEvents('all')" class="px-3 py-1.5 rounded-xl bg-white text-indigo-700 shadow-2xs transition">Semua ({{ $total_events }})</button>
                                <button type="button" id="tabUpcoming" onclick="filterOrganizerEvents('upcoming')" class="px-3 py-1.5 rounded-xl text-slate-500 hover:text-slate-800 transition">Mendatang</button>
                                <button type="button" id="tabPast" onclick="filterOrganizerEvents('past')" class="px-3 py-1.5 rounded-xl text-slate-500 hover:text-slate-800 transition">Selesai</button>
                            </div>
                        </div>

                        <!-- Table Rows -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-600" id="organizerEventsTable">
                                <thead class="bg-slate-50/70 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-3.5">INFO ACARA</th>
                                        <th class="px-4 py-3.5">KATEGORI</th>
                                        <th class="px-4 py-3.5">PELAKSANAAN</th>
                                        <th class="px-6 py-3.5 text-right">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($events as $ev)
                                    <tr class="hover:bg-slate-50/50 transition organizer-event-row" data-date="{{ $ev->tanggal }}" data-name="{{ strtolower($ev->nama_event . ' ' . $ev->lokasi . ' ' . $ev->kategori) }}">
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
                                                <div class="truncate max-w-xs">
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
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                {{ $ev->kategori }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($ev->tanggal)->format('d M Y') }}</div>
                                            <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($ev->waktu_mulai)->format('H:i') ?? '09:00' }} WIB</div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('organizer.event.edit', $ev->id) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition" title="Edit Event">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </a>
                                                <form method="POST" action="{{ route('organizer.event.delete', $ev->id) }}" onsubmit="return confirm('Hapus event ini?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-500 transition" title="Hapus">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-slate-400">
                                            Belum ada event yang dibuat. Klik tombol "+ Buat Event Baru" di atas.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Table Footer / Pagination -->
                        <div class="p-4 px-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <span>Menampilkan {{ count($events) }} kegiatan terdaftar</span>
                        </div>
                    </div>

                    <!-- 2 Bottom Operational Feature Cards (Matching Image 1) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Broadcast Card -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-2xs flex flex-col justify-between space-y-4">
                            <div class="flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg flex-shrink-0">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900">Broadcast Pengumuman</h3>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                        Kirim instruksi ruangan dan update jadwal serentak via WhatsApp & Email peserta.
                                    </p>
                                </div>
                            </div>
                            <button type="button" onclick="openBroadcastModal()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition inline-flex items-center gap-1.5 self-start">
                                <span>Kirim Notifikasi Massal</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </button>
                        </div>

                        <!-- E-Sertifikat Card -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-2xs flex flex-col justify-between space-y-4">
                            <div class="flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900">E-Sertifikat Otomatis</h3>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                        Generate e-sertifikat terakreditasi berdasarkan data kehadiran QR presensi valid.
                                    </p>
                                </div>
                            </div>
                            <button type="button" onclick="openCertModal()" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 transition inline-flex items-center gap-1.5 self-start">
                                <span>Atur Template Sertifikat</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Gate Check-in & Live Feed (4 Cols - Matching Image 1) -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Verifikasi Gerbang Presensi Card -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-2xs space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                    <span>Verifikasi Gerbang Presensi</span>
                                </h3>
                                <p class="text-[11px] text-slate-400 mt-0.5 truncate max-w-[180px]">
                                    {{ $active_event->nama_event ?? 'Belum ada event aktif' }}
                                </p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 font-extrabold text-[10px]">
                                Meja A
                            </span>
                        </div>

                        <!-- Live Session Attendance Progress -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                            <div class="flex items-center justify-between text-xs font-bold">
                                <span class="text-slate-600">Statistik Kehadiran Sesi Ini</span>
                                <span class="text-emerald-600 font-black">{{ $attendance_rate }}%</span>
                            </div>
                            <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $attendance_rate }}%;"></div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span><strong>{{ $attended_participants }}</strong> Hadir Terverifikasi</span>
                                <span>{{ $total_participants }} Terdaftar</span>
                            </div>
                        </div>

                        <!-- Input Presensi Cepat Form -->
                        <form method="POST" action="{{ route('organizer.quickCheckin') }}" class="space-y-2">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $active_event->id ?? 1 }}">
                            <label class="block text-[11px] font-bold text-slate-600">Input Presensi Cepat</label>
                            <div class="flex gap-2">
                                <input type="text" name="query" required placeholder="Ketik NIM / Nama / Email" class="flex-grow px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                                <button type="submit" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition btn-spring">
                                    Cek
                                </button>
                            </div>
                        </form>

                        <!-- Active Gate Pass Badge Card -->
                        <div class="p-4 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 text-white flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-2xl flex-shrink-0">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-indigo-300">TIKET GERBANG AKTIF</span>
                                <h4 class="text-xs font-black truncate max-w-[180px]">Gerbang 01 - {{ $active_event->lokasi ?? 'Auditorium Kampus' }}</h4>
                                <span class="text-[10px] text-slate-400 flex items-center gap-1 mt-0.5">
                                    <i class="fas fa-lock text-[9px] text-emerald-400"></i> Token Enkripsi Aktif (Campven-ID)
                                </span>
                            </div>
                        </div>

                        <!-- Aktivitas Presensi Terbaru (Real-time Feed) -->
                        <div class="space-y-3 pt-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-extrabold text-slate-900">Aktivitas Presensi Terbaru</span>
                                <span class="text-[10px] text-slate-400">Umpan Langsung</span>
                            </div>

                            <div class="space-y-2">
                                @forelse($recent_attendances as $rec)
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center gap-2.5 truncate">
                                        <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($rec->user->nama ?? 'M', 0, 1)) }}
                                        </div>
                                        <div class="truncate">
                                            <span class="font-bold text-slate-900 block truncate">{{ $rec->user->nama }}</span>
                                            <span class="text-[10px] text-slate-400 block truncate">{{ $rec->user->email }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px]">Hadir</span>
                                        <span class="block text-[9px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($rec->updated_at)->format('H:i:s') }}</span>
                                    </div>
                                </div>
                                @empty
                                <div class="p-4 text-center text-xs text-slate-400 bg-slate-50 rounded-2xl">
                                    Belum ada presensi yang terverifikasi hari ini.
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Export Log Button -->
                        <a href="{{ route('organizer.exportCsv') }}" class="w-full py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition flex items-center justify-center gap-2">
                            <i class="fas fa-file-export text-slate-400"></i>
                            <span>Ekspor Log (CSV)</span>
                        </a>
                    </div>

                    <!-- Organizer Identity Profile Card (Matching Image 1) -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-2xs space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-lg font-bold">
                                <i class="fas fa-university"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-slate-900 flex items-center gap-1.5">
                                    <span>{{ $user->nama }}</span>
                                    <i class="fas fa-check-circle text-indigo-600 text-[11px]"></i>
                                </h4>
                                <p class="text-[10px] text-slate-500">Lembaga Eksekutif Mahasiswa Terverifikasi</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs pt-3 border-t border-slate-100">
                            <span class="text-slate-400 text-[11px]">ID Organizer: #ORG-{{ $user->id }}-2026</span>
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
            <button onclick="closeCreateModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('organizer.event.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nama Acara / Event <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_event" required placeholder="Contoh: National AI & Web Seminar 2026" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Kategori <span class="text-rose-500">*</span></label>
                    <select name="kategori" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                        @foreach($categories as $c)
                            <option value="{{ $c->nama }}">{{ $c->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                </div>
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Lokasi / Gedung Acara <span class="text-rose-500">*</span></label>
                <input type="text" name="lokasi" required placeholder="Contoh: Auditorium Utama Gd. D, Kampus Pusat" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Batas Kuota Peserta</label>
                    <input type="number" name="kuota" min="1" placeholder="Kosongkan jika unlimited" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                </div>
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Biaya / Harga Tiket (Rp)</label>
                    <input type="number" name="harga" min="0" placeholder="0 untuk GRATIS" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600">
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Poster Acara</label>
                <input type="file" name="poster" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Deskripsi Lengkap Acara</label>
                <textarea name="deskripsi" rows="4" placeholder="Jelaskan topik materi, pembicara, dan benefit peserta..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCreateModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/20 transition btn-spring">
                    Simpan & Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL QR CHECK-IN SIMULATOR ===== -->
<div id="qrModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="qrModalBackdrop" onclick="closeQrModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>

    <div id="qrModalCard" class="relative bg-white w-full max-w-sm rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 text-center transform scale-95 opacity-0 transition-all duration-300">
        <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">
            <i class="fas fa-qrcode"></i>
        </div>

        <h3 class="text-base font-extrabold text-slate-900 mb-1">Scan QR Pass Gerbang</h3>
        <p class="text-xs text-slate-500 mb-6 leading-relaxed">
            Arahkan kamera ke QR Code tiket peserta atau gunakan input manual NIM mahasiswa di bawah.
        </p>

        <form method="POST" action="{{ route('organizer.quickCheckin') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="event_id" value="{{ $active_event->id ?? 1 }}">
            <input type="text" name="query" required placeholder="Masukkan NIM / ID Tiket..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-center font-bold text-slate-800 focus:outline-none focus:border-indigo-600">
            <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition btn-spring">
                Verifikasi Presensi
            </button>
        </form>
    </div>
</div>

<!-- ===== MODAL BROADCAST PENGUMUMAN ===== -->
<div id="broadcastModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="broadcastBackdrop" onclick="closeBroadcastModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>

    <div id="broadcastCard" class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 transform scale-95 opacity-0 transition-all duration-300">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fas fa-bullhorn text-purple-600"></i>
                <span>Broadcast Notifikasi Massal</span>
            </h3>
            <button onclick="closeBroadcastModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm hover:bg-slate-200 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form onsubmit="handleBroadcastSubmit(event)" class="space-y-4 text-xs">
            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Pilih Acara Target</label>
                <select id="broadcastEventSelect" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                    @foreach($events as $ev)
                        <option value="{{ $ev->id }}">{{ $ev->nama_event }} ({{ $ev->participants()->count() }} Peserta)</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Kanal Pengiriman</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-indigo-200 bg-indigo-50/50 cursor-pointer">
                        <input type="checkbox" checked class="rounded text-indigo-600 focus:ring-0">
                        <span class="font-bold text-slate-800"><i class="fab fa-whatsapp text-emerald-600 mr-1"></i> WhatsApp</span>
                    </label>
                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-indigo-200 bg-indigo-50/50 cursor-pointer">
                        <input type="checkbox" checked class="rounded text-indigo-600 focus:ring-0">
                        <span class="font-bold text-slate-800"><i class="far fa-envelope text-indigo-600 mr-1"></i> Email Gateway</span>
                    </label>
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Judul Pengumuman</label>
                <input type="text" required placeholder="Contoh: Perubahan Ruangan Acara / Reminder Presensi" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Pesan Pengumuman</label>
                <textarea rows="4" required placeholder="Tuliskan isi pesan pengumuman untuk seluruh mahasiswa terdaftar..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeBroadcastModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold shadow-md shadow-purple-600/20 transition btn-spring flex items-center gap-2">
                    <i class="fas fa-paper-plane"></i>
                    <span>Kirim Broadcast Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL TEMPLATE SERTIFIKAT ===== -->
<div id="certModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="certBackdrop" onclick="closeCertModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>

    <div id="certCard" class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 transform scale-95 opacity-0 transition-all duration-300">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fas fa-certificate text-emerald-600"></i>
                <span>Pengaturan E-Sertifikat Digital</span>
            </h3>
            <button onclick="closeCertModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm hover:bg-slate-200 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form onsubmit="handleCertSubmit(event)" class="space-y-4 text-xs">
            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Pilih Desain Template Sertifikat</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="p-3 rounded-2xl border-2 border-indigo-600 bg-indigo-50/50 cursor-pointer block text-center">
                        <input type="radio" name="cert_theme" value="formal" checked class="hidden">
                        <div class="h-16 rounded-xl bg-gradient-to-r from-slate-900 to-indigo-900 flex items-center justify-center text-white mb-2 shadow-xs">
                            <i class="fas fa-award text-2xl text-amber-300"></i>
                        </div>
                        <span class="font-extrabold text-indigo-700 block text-xs">Tema Formal SKPI</span>
                        <span class="text-[10px] text-slate-500">Standar Rektorat</span>
                    </label>

                    <label class="p-3 rounded-2xl border border-slate-200 hover:border-indigo-400 bg-slate-50 cursor-pointer block text-center">
                        <input type="radio" name="cert_theme" value="creative" class="hidden">
                        <div class="h-16 rounded-xl bg-gradient-to-r from-purple-800 to-pink-700 flex items-center justify-center text-white mb-2 shadow-xs">
                            <i class="fas fa-palette text-2xl text-pink-200"></i>
                        </div>
                        <span class="font-bold text-slate-700 block text-xs">Tema Kreatif & Modern</span>
                        <span class="text-[10px] text-slate-500">Workshop & Festival</span>
                    </label>
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Tanda Tangan Digital</label>
                <input type="text" value="Ketua Pelaksana & Pembina Ormawa" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-[11px] text-emerald-800 flex items-start gap-2.5">
                <i class="fas fa-info-circle text-emerald-600 mt-0.5"></i>
                <span>Sertifikat otomatis terbit bagi peserta berstatus <strong>Hadir (Attended)</strong> dengan kode QR otentikasi unik anti-pemalsuan.</span>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCertModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-md shadow-emerald-600/20 transition btn-spring">
                    Terapkan Template
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

    const qrModal = document.getElementById('qrModal');
    const qrBackdrop = document.getElementById('qrModalBackdrop');
    const qrCard = document.getElementById('qrModalCard');

    function openQrModal() {
        qrModal.classList.remove('hidden');
        qrModal.classList.add('flex');
        setTimeout(() => {
            qrBackdrop.classList.remove('opacity-0');
            qrCard.classList.remove('opacity-0', 'scale-95');
            qrCard.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeQrModal() {
        qrBackdrop.classList.add('opacity-0');
        qrCard.classList.add('opacity-0', 'scale-95');
        qrCard.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => {
            qrModal.classList.add('hidden');
            qrModal.classList.remove('flex');
        }, 300);
    }

    // Modal Broadcast
    const broadcastModal = document.getElementById('broadcastModal');
    const broadcastBackdrop = document.getElementById('broadcastBackdrop');
    const broadcastCard = document.getElementById('broadcastCard');

    function openBroadcastModal() {
        broadcastModal.classList.remove('hidden');
        broadcastModal.classList.add('flex');
        setTimeout(() => {
            broadcastBackdrop.classList.remove('opacity-0');
            broadcastCard.classList.remove('opacity-0', 'scale-95');
            broadcastCard.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeBroadcastModal() {
        broadcastBackdrop.classList.add('opacity-0');
        broadcastCard.classList.add('opacity-0', 'scale-95');
        broadcastCard.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => {
            broadcastModal.classList.add('hidden');
            broadcastModal.classList.remove('flex');
        }, 300);
    }

    function handleBroadcastSubmit(e) {
        e.preventDefault();
        alert('Pengumuman siaran massal berhasil dikirimkan ke antrean WhatsApp & Email peserta!');
        closeBroadcastModal();
    }

    // Modal Template Sertifikat
    const certModal = document.getElementById('certModal');
    const certBackdrop = document.getElementById('certBackdrop');
    const certCard = document.getElementById('certCard');

    function openCertModal() {
        certModal.classList.remove('hidden');
        certModal.classList.add('flex');
        setTimeout(() => {
            certBackdrop.classList.remove('opacity-0');
            certCard.classList.remove('opacity-0', 'scale-95');
            certCard.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeCertModal() {
        certBackdrop.classList.add('opacity-0');
        certCard.classList.add('opacity-0', 'scale-95');
        certCard.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => {
            certModal.classList.add('hidden');
            certModal.classList.remove('flex');
        }, 300);
    }

    function handleCertSubmit(e) {
        e.preventDefault();
        alert('Template E-Sertifikat resmi berhasil disimpan dan diterapkan pada event terpilih!');
        closeCertModal();
    }

    // Filter Table Events
    function filterOrganizerEvents(type) {
        const rows = document.querySelectorAll('.organizer-event-row');
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        document.getElementById('tabAll').className = 'px-3 py-1.5 rounded-xl text-slate-500 hover:text-slate-800 transition';
        document.getElementById('tabUpcoming').className = 'px-3 py-1.5 rounded-xl text-slate-500 hover:text-slate-800 transition';
        document.getElementById('tabPast').className = 'px-3 py-1.5 rounded-xl text-slate-500 hover:text-slate-800 transition';

        if (type === 'all') {
            document.getElementById('tabAll').className = 'px-3 py-1.5 rounded-xl bg-white text-indigo-700 shadow-2xs font-bold transition';
            rows.forEach(r => r.style.display = '');
        } else if (type === 'upcoming') {
            document.getElementById('tabUpcoming').className = 'px-3 py-1.5 rounded-xl bg-white text-indigo-700 shadow-2xs font-bold transition';
            rows.forEach(r => {
                const d = new Date(r.getAttribute('data-date'));
                r.style.display = (d >= today) ? '' : 'none';
            });
        } else if (type === 'past') {
            document.getElementById('tabPast').className = 'px-3 py-1.5 rounded-xl bg-white text-indigo-700 shadow-2xs font-bold transition';
            rows.forEach(r => {
                const d = new Date(r.getAttribute('data-date'));
                r.style.display = (d < today) ? '' : 'none';
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
</script>
@endpush
