@extends('layouts.app')

@section('title', 'Edit Acara (Admin) - ' . $event->nama_event)

@section('content')
<div class="min-h-screen bg-slate-50/70 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto space-y-6">
        
        <!-- Header & Breadcrumbs -->
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 transition">Pusat Admin</a>
                    <i class="fas fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-800 font-bold">Edit Acara</span>
                </nav>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Perbarui Acara (Administrator)</h1>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition flex items-center gap-2 shadow-2xs">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
        </div>

        @if (isset($errors) && $errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <div class="font-bold flex items-center gap-1.5">
                <i class="fas fa-exclamation-circle"></i>
                <span>Terdapat kesalahan input:</span>
            </div>
            <ul class="list-disc list-inside pl-2 space-y-0.5 text-[11px]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Card Container -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
            <form action="{{ route('admin.event.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
                @csrf
                @method('PUT')

                <!-- Nama Acara -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700">Nama Acara / Event <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_event" value="{{ old('nama_event', $event->nama_event) }}" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                </div>

                <!-- Kategori & Tanggal -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700">Kategori <span class="text-rose-500">*</span></label>
                        <select name="kategori" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->nama }}" {{ old('kategori', $event->kategori) == $cat->nama ? 'selected' : '' }}>
                                    {{ $cat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $event->tanggal ? \Carbon\Carbon::parse($event->tanggal)->format('Y-m-d') : '') }}" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                    </div>
                </div>

                <!-- Waktu Mulai & Waktu Selesai -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', $event->waktu_mulai ? \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') : '') }}" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700">Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', $event->waktu_selesai ? \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i') : '') }}" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700">Lokasi / Gedung Acara <span class="text-rose-500">*</span></label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                </div>

                <!-- Kuota & Pembuat Acara (Created By) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700">Batas Kuota Peserta</label>
                        <input type="number" name="kuota" value="{{ old('kuota', $event->kuota) }}" placeholder="Kosongkan jika unlimited" min="1" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700">Dibuat Oleh (Penyelenggara)</label>
                        <select name="created_by" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('created_by', $event->created_by) == $u->id ? 'selected' : '' }}>
                                    {{ $u->nama }} ({{ $u->role }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Deskripsi Acara -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700">Deskripsi Lengkap Acara</label>
                    <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-indigo-600 transition leading-relaxed">{{ old('deskripsi', $event->deskripsi) }}</textarea>
                </div>

                <!-- Poster Saat Ini & Upload Baru -->
                <div class="space-y-3 pt-3 border-t border-slate-100">
                    @if($event->poster && Storage::disk('public')->exists($event->poster))
                    <div class="space-y-2">
                        <label class="block font-bold text-slate-700">Poster Saat Ini</label>
                        <div class="w-48 h-28 rounded-2xl overflow-hidden border border-slate-200 shadow-2xs">
                            <img src="{{ Storage::url($event->poster) }}" alt="Poster" class="w-full h-full object-cover">
                        </div>
                    </div>
                    @endif

                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700">Ganti Poster Acara</label>
                        <input type="file" name="poster" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                        <span class="text-[11px] text-slate-400 block">Kosongkan jika tidak ingin mengganti poster (JPG, PNG, GIF maks 2MB).</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/20 transition btn-spring">
                        Perbarui Acara
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
