@extends('layouts.app')

@section('title', 'Dashboard Admin - Campven')

@section('content')
<div class="min-h-screen bg-slate-50/70 flex">

    @include('partials.admin-sidebar', ['activeAdmin' => 'dashboard'])

    <!-- ===== MAIN CONTENT AREA ===== -->
    <div class="flex-grow md:ml-64 flex flex-col min-h-screen w-full">

        <!-- Top Operational Header -->
        <header class="h-20 bg-white/95 backdrop-blur-md border-b border-slate-200/90 px-4 sm:px-8 flex items-center justify-between gap-3 sticky top-0 z-30">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <button type="button" onclick="toggleAdminSidebar()" class="md:hidden w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center flex-shrink-0" aria-label="Buka Menu">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="relative w-full max-w-sm">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="adminSearchInput" onkeyup="filterAdminEvents()" placeholder="Cari acara, lokasi, penyelenggara..." class="w-full pl-10 pr-12 py-2 rounded-full bg-slate-100 border border-transparent focus:border-slate-300 focus:bg-white text-xs text-slate-800 placeholder-slate-400 focus:outline-none transition">
                    <kbd class="hidden sm:inline-block absolute right-3 top-1/2 -translate-y-1/2 px-1.5 py-0.5 rounded bg-white border border-slate-200 text-[10px] font-bold text-slate-400">⌘K</kbd>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('home') }}" class="hidden sm:flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-indigo-600 px-3 py-1.5 rounded-full hover:bg-slate-100 transition">
                    <i class="fas fa-external-link-alt text-[10px]"></i>
                    <span>Lihat Web</span>
                </a>

                <a href="{{ route('profile') }}" class="flex items-center gap-2.5 pl-2 border-l border-slate-200">
                    <div class="w-9 h-9 rounded-full overflow-hidden bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                        @if(Auth::user()->foto && Storage::disk('public')->exists(Auth::user()->foto))
                            <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                        @endif
                    </div>
                    <div class="text-left hidden sm:block">
                        <span class="block text-xs font-bold text-slate-900 leading-tight">{{ Auth::user()->nama }}</span>
                        <span class="text-[10px] font-medium text-slate-400">Administrator</span>
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
                        <span>Panel Kontrol Administrator • {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Manajemen Acara &amp; Kegiatan Kampus
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xl">
                        Pantau seluruh event kampus, daftar peserta, kategori kegiatan, pengumuman, dan akun pengguna.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 flex-wrap">
                    <a href="{{ route('admin.announcements') }}" class="px-4 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-sm transition btn-spring flex items-center gap-2">
                        <i class="fas fa-bullhorn text-indigo-600"></i>
                        <span>Kelola Pengumuman ({{ $total_announcements }})</span>
                    </a>
                    <button type="button" onclick="openCreateModal()" class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition btn-spring flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Event Baru</span>
                    </button>
                </div>
            </div>

            <!-- 4 KPI Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">TOTAL ACARA</span>
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $total_events }} <span class="text-xs font-semibold text-slate-400">Acara</span></div>
                    <div class="text-[11px] font-semibold text-slate-500 pt-2 border-t border-slate-100">
                        Seluruh acara terdaftar
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">PARTISIPASI AKTIF</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($total_participants) }} <span class="text-xs font-semibold text-slate-400">Tiket</span></div>
                    <div class="text-[11px] font-semibold text-slate-500 pt-2 border-t border-slate-100">
                        Tiket terdaftar, dikonfirmasi &amp; hadir
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">TOTAL PENGGUNA</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $total_users }} <span class="text-xs font-semibold text-slate-400">Akun</span></div>
                    <div class="text-[11px] font-semibold text-slate-500 pt-2 border-t border-slate-100">
                        <a href="{{ route('admin.users') }}" class="text-indigo-600 font-bold hover:underline flex items-center gap-1">
                            <span>Kelola Akun</span>
                            <i class="fas fa-arrow-right text-[9px]"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">TOTAL KATEGORI</span>
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">{{ count($categories) }} <span class="text-xs font-semibold text-slate-400">Kategori</span></div>
                    <div class="text-[11px] font-semibold text-slate-500 pt-2 border-t border-slate-100">
                        <a href="{{ route('admin.categories') }}" class="text-indigo-600 font-bold hover:underline flex items-center gap-1">
                            <span>Kelola Kategori</span>
                            <i class="fas fa-arrow-right text-[9px]"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Event Management Section -->
            <div class="space-y-6">
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                            <span class="w-1.5 h-5 rounded-full bg-indigo-600 inline-block"></span>
                            <span>Katalog Seluruh Acara Kampus</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar semua acara di sistem beserta penyelenggara, kuota, dan peserta</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-white border border-slate-200 text-xs font-bold text-slate-700 shadow-sm">
                        {{ count($events) }} Acara Terdaftar
                    </span>
                </div>

                @if(count($events) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="adminEventsGrid">
                    @foreach($events as $event)
                    @php
                        $hasPoster = $event->poster && file_exists(public_path('storage/' . $event->poster));
                        $participantCount = $event->activeParticipants()->count();
                    @endphp
                    <div class="admin-event-card bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group" data-title="{{ strtolower($event->nama_event . ' ' . $event->lokasi . ' ' . ($event->creator->nama ?? '')) }}">

                        <!-- Media Header -->
                        <div class="relative h-44 w-full overflow-hidden bg-slate-900">
                            @if($hasPoster)
                                <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->nama_event }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-white/70 p-4">
                                    <i class="far fa-calendar-alt text-4xl mb-2 text-indigo-400"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Belum Ada Poster</span>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-black/20"></div>

                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-white/90 backdrop-blur-md text-slate-800 shadow-sm">
                                    {{ $event->kategori }}
                                </span>
                            </div>

                            <div class="absolute top-3 right-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-indigo-600/90 backdrop-blur-md text-white shadow-sm">
                                    {{ $event->formattedHarga() }}
                                </span>
                            </div>

                            <div class="absolute bottom-3 left-3 right-3 text-white">
                                <span class="text-[10px] font-semibold text-slate-200 block truncate">
                                    <i class="fas fa-user-tie text-[9px] mr-1 text-indigo-300"></i> {{ $event->creator->nama ?? 'Penyelenggara Kampus' }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-grow space-y-3">
                            <a href="{{ route('event.detail', $event->id) }}" class="block font-extrabold text-slate-900 group-hover:text-indigo-600 transition text-base leading-snug line-clamp-2">
                                {{ $event->nama_event }}
                            </a>

                            <div class="space-y-1.5 text-xs text-slate-600 pt-1">
                                <div class="flex items-center gap-2">
                                    <i class="far fa-calendar-alt text-slate-400 w-4"></i>
                                    <span>{{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                                </div>
                                @if($event->waktu_mulai)
                                <div class="flex items-center gap-2">
                                    <i class="far fa-clock text-slate-400 w-4"></i>
                                    <span>{{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') }}{{ $event->waktu_selesai ? ' - ' . \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i') : '' }} WIB</span>
                                </div>
                                @endif
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-rose-500 w-4"></i>
                                    <span class="truncate">{{ $event->lokasi }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer & Actions -->
                        <div class="px-5 py-3.5 border-t border-slate-100 flex items-center justify-between gap-2 text-xs bg-slate-50/50">
                            <span class="font-bold text-slate-600 flex items-center gap-1.5">
                                <i class="fas fa-users text-indigo-600 text-[11px]"></i>
                                <span>{{ $participantCount }}{{ $event->kuota ? ' / ' . $event->kuota : '' }}</span>
                            </span>

                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.event.participants', $event->id) }}" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold text-[11px] transition" title="Lihat Daftar Peserta">
                                    <i class="fas fa-users mr-1"></i>Peserta
                                </a>

                                <a href="{{ route('admin.event.edit', $event->id) }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-indigo-600 hover:border-indigo-300 transition shadow-sm" title="Edit Acara">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>

                                @if($participantCount > 0)
                                    <button type="button" disabled class="p-2 rounded-xl bg-slate-100 text-slate-300 cursor-not-allowed" title="Tidak dapat dihapus karena masih memiliki peserta aktif">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                @else
                                    <button type="button" onclick="openDeleteModal('{{ route('admin.event.delete', $event->id) }}', '{{ addslashes($event->nama_event) }}')" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-rose-600 hover:border-rose-300 transition shadow-sm" title="Hapus Acara">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-16 bg-white rounded-3xl border border-slate-200/90 p-8 space-y-3">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-2xl">
                        <i class="far fa-calendar-times"></i>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900">Belum Ada Acara</h3>
                    <p class="text-xs text-slate-500">Klik tombol "Tambah Event Baru" di atas untuk menambahkan acara pertama.</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- ===== MODAL TAMBAH EVENT BARU (ADMIN) ===== -->
<div id="createEventModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="createModalBackdrop" onclick="closeCreateModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>

    <div id="createModalCard" class="relative bg-white w-full max-w-xl rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fas fa-calendar-plus text-indigo-600"></i>
                <span>Tambah Event Kampus Baru (Admin)</span>
            </h3>
            <button type="button" onclick="closeCreateModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm hover:bg-slate-200 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.event.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nama Acara / Event <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_event" required placeholder="Contoh: Seminar Nasional AI & Data Science 2026" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Kategori Acara <span class="text-rose-500">*</span></label>
                    <select name="kategori" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->nama }}">{{ $cat->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                </div>
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Lokasi / Gedung Acara <span class="text-rose-500">*</span></label>
                <input type="text" name="lokasi" required placeholder="Contoh: Auditorium Utama Gedung D" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Batas Kuota</label>
                    <input type="number" name="kuota" min="1" placeholder="Unlimited" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                </div>
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Harga Tiket (Rp)</label>
                    <input type="number" name="harga" value="0" min="0" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                </div>
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Poin SKPI</label>
                    <input type="number" name="skpi_poin" value="10" min="0" max="100" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Penyelenggara Acara</label>
                <select name="created_by" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                    @foreach($organizers as $org)
                        <option value="{{ $org->id }}" {{ $org->id === Auth::id() ? 'selected' : '' }}>
                            {{ $org->nama }} ({{ ucfirst($org->role) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Deskripsi Lengkap Acara</label>
                <textarea name="deskripsi" rows="3" placeholder="Pemaparan topik materi dan informasi peserta..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition"></textarea>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Upload Poster Acara</label>
                <input type="file" name="poster" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
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

    function filterAdminEvents() {
        const query = document.getElementById('adminSearchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.admin-event-card');
        cards.forEach(card => {
            const title = card.getAttribute('data-title');
            card.style.display = title.includes(query) ? '' : 'none';
        });
    }
</script>
@endpush
