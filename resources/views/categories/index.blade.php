@extends('layouts.app')

@section('title', 'Manajemen Kategori - Admin Campven')

@section('content')
<div class="min-h-screen bg-slate-50/70 flex">

    <!-- ===== LEFT SIDEBAR ===== -->
    <aside class="w-64 bg-white border-r border-slate-200/90 flex flex-col justify-between fixed top-0 bottom-0 left-0 z-40 hidden md:flex">
        <div>
            <!-- Brand Logo -->
            <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-100">
                <div class="w-9 h-9 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-md shadow-indigo-600/20">
                    <i class="fas fa-cubes text-sm"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold text-slate-900 tracking-tight">Camp<span class="text-indigo-600">ven</span></span>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider -mt-1">Pusat Administrator</span>
                </div>
            </div>

            <!-- Nav Menu List -->
            <div class="p-4 space-y-1.5 text-xs">
                <div class="px-3 py-1.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Navigasi Utama</div>
                
                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-semibold transition">
                    <i class="fas fa-calendar-alt text-sm w-4 text-slate-400"></i>
                    <span>Manajemen Acara</span>
                </a>

                <a href="{{ route('admin.users') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-semibold transition">
                    <i class="fas fa-users text-sm w-4 text-slate-400"></i>
                    <span>Manajemen Pengguna</span>
                </a>

                <a href="{{ route('admin.categories') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20 transition">
                    <i class="fas fa-tags text-sm w-4"></i>
                    <span>Kategori Acara</span>
                </a>

                <div class="px-3 pt-4 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Akses Cepat</div>
                
                <a href="{{ route('profile') }}" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-2xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-semibold transition">
                    <i class="fas fa-user-circle text-sm w-4 text-slate-400"></i>
                    <span>Profil Saya</span>
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
            <div>
                <h1 class="text-base font-extrabold text-slate-900">Kategori Kegiatan</h1>
                <p class="text-xs text-slate-400">Taksonomi klasifikasi acara mahasiswa</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200">
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
                </div>
            </div>
        </header>

        <!-- Body Container -->
        <div class="p-6 sm:p-8 space-y-8 max-w-7xl w-full mx-auto">
            
            <!-- Hero Banner -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-2">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-slate-200 text-[11px] font-bold text-slate-600 mb-2 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        <span>Struktur Taksonomi Acara</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Kategori & Label Acara 🏷️
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xl">
                        Atur kategori kegiatan kampus untuk pengelompokan pendaftaran, filter pencarian, dan penerbitan sertifikat.
                    </p>
                </div>

                <button type="button" onclick="openCreateCatModal()" class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition btn-spring flex items-center gap-2 self-start lg:self-auto">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Kategori Baru</span>
                </button>
            </div>

            <!-- Categories Grid -->
            @if(count($categories) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($categories as $cat)
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs p-6 flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition-all duration-300 space-y-4 group">
                    <div class="space-y-3">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-xs transition-transform group-hover:scale-110" style="background-color: {{ $cat->color ?? '#4F46E5' }}15; color: {{ $cat->color ?? '#4F46E5' }};">
                            <i class="fas {{ $cat->icon ?? 'fa-tag' }}"></i>
                        </div>

                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 group-hover:text-indigo-600 transition">{{ $cat->nama }}</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2">
                                {{ $cat->description ?: 'Kategori kegiatan resmi mahasiswa' }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="font-mono text-[10px] text-slate-400 px-2 py-0.5 rounded bg-slate-50 border border-slate-100">
                            {{ $cat->icon }}
                        </span>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.categories') }}?edit={{ $cat->id }}" class="p-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-amber-50 hover:text-amber-600 transition" title="Edit Kategori">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.category.delete', $cat->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $cat->nama }}?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-slate-100 text-slate-500 hover:bg-rose-50 hover:text-rose-600 transition" title="Hapus Kategori">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16 bg-white rounded-3xl border border-slate-200/90 p-8 space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-2xl">
                    <i class="fas fa-tags"></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Belum Ada Kategori</h3>
                <p class="text-xs text-slate-500">Klik tombol "Tambah Kategori Baru" untuk membuat klasifikasi pertama.</p>
            </div>
            @endif

        </div>
    </div>
</div>

<!-- ===== MODAL TAMBAH KATEGORI ===== -->
<div id="createCatModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="createCatBackdrop" onclick="closeCreateCatModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>

    <div id="createCatCard" class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fas fa-tag text-indigo-600"></i>
                <span>Tambah Kategori Baru</span>
            </h3>
            <button onclick="closeCreateCatModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm hover:bg-slate-200 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.category.store') }}" class="space-y-4 text-xs">
            @csrf

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" required placeholder="Contoh: Simposium, Hackathon" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Icon FontAwesome <span class="text-rose-500">*</span></label>
                <input type="text" name="icon" required placeholder="fa-graduation-cap" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                <span class="text-[10px] text-slate-400 block">Pilihan icon: fa-graduation-cap, fa-tools, fa-trophy, fa-video, fa-music, fa-running</span>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Warna Identitas (Hex)</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="color" value="#4F46E5" class="w-10 h-10 rounded-xl cursor-pointer border border-slate-200 p-0.5">
                    <span class="text-slate-500 font-mono text-xs">Pilih palet warna badge</span>
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Deskripsi Singkat</label>
                <textarea name="description" rows="3" placeholder="Jelaskan jenis kegiatan dalam kategori ini..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCreateCatModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/20 transition btn-spring">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL EDIT KATEGORI ===== -->
@if(request('edit'))
@php
$editCategory = \App\Models\Category::find(request('edit'));
@endphp
@if($editCategory)
<div class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
    <a href="{{ route('admin.categories') }}" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md"></a>

    <div class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 max-h-[90vh] overflow-y-auto z-10">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fas fa-edit text-indigo-600"></i>
                <span>Perbarui Kategori</span>
            </h3>
            <a href="{{ route('admin.categories') }}" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm hover:bg-slate-200 transition">
                <i class="fas fa-times"></i>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.category.update', $editCategory->id) }}" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_id" value="{{ $editCategory->id }}">

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" value="{{ $editCategory->nama }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Icon FontAwesome <span class="text-rose-500">*</span></label>
                <input type="text" name="icon" value="{{ $editCategory->icon }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Warna Identitas</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="color" value="{{ $editCategory->color ?? '#4F46E5' }}" class="w-10 h-10 rounded-xl cursor-pointer border border-slate-200 p-0.5">
                    <span class="text-slate-500 font-mono text-xs">{{ $editCategory->color ?? '#4F46E5' }}</span>
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">{{ $editCategory->description }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.categories') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/20 transition btn-spring">
                    Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endif
@endsection

@push('scripts')
<script>
    const catModal = document.getElementById('createCatModal');
    const catBackdrop = document.getElementById('createCatBackdrop');
    const catCard = document.getElementById('createCatCard');

    function openCreateCatModal() {
        catModal.classList.remove('hidden');
        catModal.classList.add('flex');
        setTimeout(() => {
            catBackdrop.classList.remove('opacity-0');
            catCard.classList.remove('opacity-0', 'scale-95');
            catCard.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeCreateCatModal() {
        catBackdrop.classList.add('opacity-0');
        catCard.classList.add('opacity-0', 'scale-95');
        catCard.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => {
            catModal.classList.add('hidden');
            catModal.classList.remove('flex');
        }, 300);
    }
</script>
@endpush
