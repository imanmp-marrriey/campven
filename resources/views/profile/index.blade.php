@extends('layouts.app')

@section('title', 'Profil Akun - Campven')

@section('content')
<!-- ===== TOP MODERN STICKY NAVBAR ===== -->
<nav class="sticky top-0 z-50 glass-nav transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <a href="{{ $back_url ?? route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-slate-900 to-slate-700 flex items-center justify-center text-white font-extrabold text-xl shadow-md shadow-slate-900/10 group-hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-cubes text-sm"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold text-slate-900 tracking-tight">Camp<span class="text-indigo-600">ven</span></span>
                    <span class="block text-[10px] font-semibold text-slate-400 tracking-wider uppercase -mt-1">Campus Events</span>
                </div>
            </a>

            <!-- Right Action -->
            <div class="flex items-center gap-3">
                <a href="{{ $back_url ?? route('home') }}" class="inline-flex items-center gap-2 text-xs font-bold px-4 py-2.5 rounded-full border border-slate-200 text-slate-700 hover:bg-slate-50 transition btn-spring">
                    <i class="fas fa-arrow-left text-slate-400"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        <!-- Profile Summary Card (Left) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm text-center md:sticky md:top-28">
            <div class="relative w-28 h-28 mx-auto mb-4 group">
                <div class="w-28 h-28 rounded-full overflow-hidden ring-4 ring-slate-100 shadow-md bg-slate-900 text-white flex items-center justify-center text-3xl font-extrabold">
                    @if($user->foto && Storage::disk('public')->exists($user->foto))
                        <img id="avatarPreview" src="{{ Storage::url($user->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                    @else
                        <span id="avatarPlaceholder">{{ strtoupper(substr($user->nama, 0, 1)) }}</span>
                        <img id="avatarPreview" src="" alt="Foto Profil" class="w-full h-full object-cover hidden">
                    @endif
                </div>
            </div>

            <h2 class="text-lg font-extrabold text-slate-900 leading-snug">{{ $user->nama }}</h2>
            <p class="text-xs text-slate-500 mb-3">{{ $user->email }}</p>

            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 capitalize mb-6">
                <i class="fas fa-shield-alt text-[10px]"></i>
                {{ $user->role }}
            </span>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-3 pt-6 border-t border-slate-100">
                <div class="p-3 rounded-2xl bg-slate-50">
                    <div class="text-xl font-extrabold text-slate-900">{{ $joined_events_count ?? 0 }}</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Event Diikuti</div>
                </div>
                @if(in_array($user->role, ['admin', 'penyelenggara']))
                <div class="p-3 rounded-2xl bg-slate-50">
                    <div class="text-xl font-extrabold text-slate-900">{{ $my_events_count ?? 0 }}</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Event Dibuat</div>
                </div>
                @else
                <div class="p-3 rounded-2xl bg-slate-50">
                    <div class="text-xl font-extrabold text-emerald-600"><i class="fas fa-check"></i></div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Akun Aktif</div>
                </div>
                @endif
            </div>

            <!-- Tombol Keluar dari Akun (Logout) -->
            <div class="pt-6 mt-6 border-t border-slate-100">
                <button type="button" onclick="openLogoutModal()" class="w-full py-3 px-4 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 font-bold text-xs transition-all flex items-center justify-center gap-2 btn-spring shadow-2xs">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar dari Akun</span>
                </button>
            </div>
        </div>

        <!-- Edit Form Card (Right 2 cols) -->
        <div class="md:col-span-2 bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-sm">
            <h3 class="text-xl font-extrabold text-slate-900 mb-2 flex items-center gap-2.5">
                <i class="fas fa-user-edit text-indigo-600"></i>
                <span>Pengaturan Profil</span>
            </h3>
            <p class="text-xs text-slate-500 mb-8 leading-relaxed">
                Perbarui informasi identitas pribadi, nomor kontak, serta kata sandi akun Anda.
            </p>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Nama -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-slate-900 focus:bg-white transition">
                    </div>
                </div>

                <!-- Email (Readonly) -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Alamat Email (Akun)</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="email" value="{{ $user->email }}" readonly disabled class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-100 border border-slate-200 text-xs sm:text-sm text-slate-500 cursor-not-allowed">
                    </div>
                    <span class="text-[11px] text-slate-400">Email akun tidak dapat diubah sembarangan.</span>
                </div>

                <!-- No Telepon -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Nomor WhatsApp / Telepon</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" placeholder="Contoh: 08123456789" class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-slate-900 focus:bg-white transition">
                    </div>
                </div>

                <!-- Bio -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Bio Singkat</label>
                    <textarea name="bio" rows="3" placeholder="Ceritakan jurusan, minat kegiatan, atau organisasi Anda..." class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-slate-900 focus:bg-white transition">{{ old('bio', $user->bio) }}</textarea>
                </div>

                <!-- Ganti Foto -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Unggah Foto Profil Baru</label>
                    <input type="file" name="foto" id="fotoInput" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 file:cursor-pointer file:transition">
                    <span class="text-[11px] text-slate-400 block">Maksimal 2MB (Format: JPG, PNG, GIF).</span>
                </div>

                <!-- Password Baru (Optional) -->
                <div class="pt-4 border-t border-slate-100 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Ganti Password (Opsional)</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-slate-900 focus:bg-white transition">
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="submit" class="px-8 py-3.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm shadow-md shadow-slate-900/10 transition btn-spring">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const fotoInput = document.getElementById('fotoInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarPlaceholder = document.getElementById('avatarPlaceholder');

    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    avatarPreview.src = evt.target.result;
                    avatarPreview.classList.remove('hidden');
                    if (avatarPlaceholder) avatarPlaceholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush
