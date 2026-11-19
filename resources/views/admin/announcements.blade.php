@extends('layouts.app')

@section('title', 'Pengumuman Kampus - Admin Campven')

@section('content')
<div class="min-h-screen bg-slate-50/70 flex">

    @include('partials.admin-sidebar', ['activeAdmin' => 'announcements'])

    <div class="flex-grow md:ml-64 flex flex-col min-h-screen w-full">

        <!-- Header -->
        <header class="h-20 bg-white/95 backdrop-blur-md border-b border-slate-200/90 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleAdminSidebar()" class="md:hidden w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center" aria-label="Buka Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h1 class="text-base font-extrabold text-slate-900">Pengumuman Kampus</h1>
                    <p class="text-xs text-slate-400">Publikasikan informasi resmi di halaman beranda</p>
                </div>
            </div>

            <a href="{{ route('profile') }}" class="flex items-center gap-2.5 pl-2">
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
        </header>

        <div class="p-4 sm:p-8 space-y-8 max-w-7xl w-full mx-auto">

            <!-- Hero -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-2">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-slate-200 text-[11px] font-bold text-slate-600 mb-2 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        <span>Papan Pengumuman Resmi</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Kelola Pengumuman Kampus
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xl">
                        Pengumuman yang dipublikasikan di sini akan tampil pada panel Pemberitahuan Resmi di halaman utama.
                    </p>
                </div>

                <button type="button" onclick="openCreateAnnModal()" class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition btn-spring flex items-center gap-2 self-start lg:self-auto">
                    <i class="fas fa-plus"></i>
                    <span>Buat Pengumuman Baru</span>
                </button>
            </div>

            <!-- List Pengumuman -->
            @if(count($announcements) > 0)
            <div class="space-y-4">
                @foreach($announcements as $ann)
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                PENGUMUMAN
                            </span>
                            <span class="text-[11px] text-slate-400">
                                <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($ann->created_at)->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB
                            </span>
                            <span class="text-[11px] text-slate-500 font-semibold">
                                • Oleh {{ $ann->creator->nama ?? 'Administrator' }}
                            </span>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">{{ $ann->title }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $ann->content }}</p>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button type="button" onclick="openEditAnnModal({{ $ann->id }}, '{{ addslashes($ann->title) }}', `{{ addslashes($ann->content) }}`)" class="p-2.5 rounded-xl bg-slate-100 text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 transition" title="Edit Pengumuman">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <button type="button" onclick="openDeleteModal('{{ route('admin.announcement.delete', $ann->id) }}', '{{ addslashes($ann->title) }}')" class="p-2.5 rounded-xl bg-slate-100 text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus Pengumuman">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16 bg-white rounded-3xl border border-slate-200/90 p-8 space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-2xl">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Belum Ada Pengumuman</h3>
                <p class="text-xs text-slate-500">Klik tombol "Buat Pengumuman Baru" untuk mempublikasikan informasi kampus pertama.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- ===== MODAL TAMBAH PENGUMUMAN ===== -->
<div id="createAnnModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="createAnnBackdrop" onclick="closeCreateAnnModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>
    <div id="createAnnCard" class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 transform scale-95 opacity-0 transition-all duration-300">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fas fa-bullhorn text-indigo-600"></i>
                <span>Buat Pengumuman Baru</span>
            </h3>
            <button type="button" onclick="closeCreateAnnModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm hover:bg-slate-200 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.announcement.store') }}" class="space-y-4 text-xs">
            @csrf
            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Judul Pengumuman <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="Contoh: Ketentuan Presensi & E-Sertifikat Semester Ini" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>
            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Isi Pengumuman <span class="text-rose-500">*</span></label>
                <textarea name="content" rows="4" required placeholder="Tuliskan detail pengumuman untuk seluruh mahasiswa dan penyelenggara..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition"></textarea>
            </div>
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCreateAnnModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/20 transition btn-spring">Publikasikan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL EDIT PENGUMUMAN ===== -->
<div id="editAnnModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="editAnnBackdrop" onclick="closeEditAnnModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>
    <div id="editAnnCard" class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 transform scale-95 opacity-0 transition-all duration-300">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fas fa-edit text-indigo-600"></i>
                <span>Edit Pengumuman</span>
            </h3>
            <button type="button" onclick="closeEditAnnModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm hover:bg-slate-200 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="editAnnForm" method="POST" action="" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Judul Pengumuman <span class="text-rose-500">*</span></label>
                <input type="text" id="editAnnTitle" name="title" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>
            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Isi Pengumuman <span class="text-rose-500">*</span></label>
                <textarea id="editAnnContent" name="content" rows="4" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition"></textarea>
            </div>
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditAnnModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/20 transition btn-spring">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openCreateAnnModal() {
        const m = document.getElementById('createAnnModal');
        const b = document.getElementById('createAnnBackdrop');
        const c = document.getElementById('createAnnCard');
        m.classList.remove('hidden'); m.classList.add('flex');
        setTimeout(() => { b.classList.remove('opacity-0'); c.classList.remove('opacity-0', 'scale-95'); c.classList.add('opacity-100', 'scale-100'); }, 10);
    }
    function closeCreateAnnModal() {
        const m = document.getElementById('createAnnModal');
        const b = document.getElementById('createAnnBackdrop');
        const c = document.getElementById('createAnnCard');
        b.classList.add('opacity-0'); c.classList.add('opacity-0', 'scale-95'); c.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => { m.classList.add('hidden'); m.classList.remove('flex'); }, 300);
    }
    function openEditAnnModal(id, title, content) {
        document.getElementById('editAnnForm').action = '/admin/announcements/' + id;
        document.getElementById('editAnnTitle').value = title;
        document.getElementById('editAnnContent').value = content;
        const m = document.getElementById('editAnnModal');
        const b = document.getElementById('editAnnBackdrop');
        const c = document.getElementById('editAnnCard');
        m.classList.remove('hidden'); m.classList.add('flex');
        setTimeout(() => { b.classList.remove('opacity-0'); c.classList.remove('opacity-0', 'scale-95'); c.classList.add('opacity-100', 'scale-100'); }, 10);
    }
    function closeEditAnnModal() {
        const m = document.getElementById('editAnnModal');
        const b = document.getElementById('editAnnBackdrop');
        const c = document.getElementById('editAnnCard');
        b.classList.add('opacity-0'); c.classList.add('opacity-0', 'scale-95'); c.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => { m.classList.add('hidden'); m.classList.remove('flex'); }, 300);
    }
</script>
@endpush

