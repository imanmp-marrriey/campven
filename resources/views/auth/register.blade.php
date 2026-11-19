@extends('layouts.app')

@section('title', 'Daftar Akun Mahasiswa - Campven')

@section('content')
<div class="min-h-screen bg-slate-50/70 flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="max-w-4xl w-full bg-white rounded-3xl border border-slate-200/90 shadow-2xl overflow-hidden flex flex-col md:flex-row">

        <!-- Left: Form Column -->
        <div class="flex-1 p-8 sm:p-12 flex flex-col justify-between">
            <div>
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-8 group">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-xl shadow-md shadow-indigo-600/20 group-hover:scale-105 transition-transform">
                        <i class="fas fa-cubes text-sm"></i>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold text-slate-900 tracking-tight">Camp<span class="text-indigo-600">ven</span></span>
                        <span class="block text-[10px] font-semibold text-slate-400 tracking-wider -mt-1">Portal Kegiatan Kampus</span>
                    </div>
                </a>

                <div class="mb-6">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Akun Mahasiswa</h2>
                    <p class="text-xs text-slate-500 mt-1">Buat akun mahasiswa untuk mendaftar acara kampus dan mendapatkan e-sertifikat.</p>
                </div>

                @if(isset($errors) && $errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs mb-5 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-rose-600"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4 text-xs">
                    @csrf

                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="far fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Ahmad Fauzi" class="w-full pl-11 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700">Alamat Email <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@kampus.ac.id" class="w-full pl-11 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block font-bold text-slate-700">Kata Sandi <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="password" name="password" required placeholder="Min. 6 karakter" class="w-full pl-11 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block font-bold text-slate-700">Konfirmasi Kata Sandi <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <i class="fas fa-check-circle absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi" class="w-full pl-11 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700">Nomor Telepon / WhatsApp</label>
                        <div class="relative">
                            <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="no_telp" value="{{ old('no_telp') }}" placeholder="08123456789" class="w-full pl-11 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                        </div>
                        <span class="text-[11px] text-slate-400 block">Akun publik terdaftar sebagai Mahasiswa. Akun Penyelenggara dikelola oleh Administrator.</span>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs sm:text-sm shadow-md shadow-indigo-600/20 transition btn-spring mt-2 flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        <span>Daftar Akun Mahasiswa</span>
                    </button>
                </form>
            </div>

            <div class="pt-6 border-t border-slate-100 text-center text-xs text-slate-500 mt-6">
                <span>Sudah memiliki akun?</span>
                <a href="{{ route('login') }}" class="text-indigo-600 font-extrabold hover:underline ml-1">
                    Masuk di sini →
                </a>
            </div>
        </div>

        <!-- Right: Decorative Column -->
        <div class="hidden md:flex flex-1 bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 p-10 flex-col justify-between text-white relative overflow-hidden">
            <div class="absolute -top-20 -right-20 w-80 h-80 bg-indigo-600/30 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-purple-600/20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <span class="px-3 py-1 rounded-full bg-white/10 text-[10px] font-bold tracking-wider uppercase backdrop-blur-md border border-white/10">
                    Gabung Komunitas Kampus
                </span>
            </div>

            <div class="relative z-10 space-y-4 my-auto">
                <h3 class="text-2xl font-black leading-tight text-white drop-shadow-sm">
                    Jadilah Bagian dari <br><span class="text-indigo-300">Ekosistem Campven</span>
                </h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Satu platform terpusat untuk menemukan, mendaftar, dan memantau riwayat kegiatan organisasi kampus Anda.
                </p>

                <div class="space-y-2.5 pt-4">
                    <div class="flex items-center gap-3 text-xs text-slate-200">
                        <div class="w-7 h-7 rounded-xl bg-white/10 flex items-center justify-center text-emerald-400 text-xs">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Registrasi Event 1-Klik</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-200">
                        <div class="w-7 h-7 rounded-xl bg-white/10 flex items-center justify-center text-indigo-300 text-xs">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <span>Tiket Digital dengan Kode QR Unik</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-200">
                        <div class="w-7 h-7 rounded-xl bg-white/10 flex items-center justify-center text-purple-300 text-xs">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <span>E-Sertifikat Siap Cetak &amp; Poin SKPI</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10 pt-4 text-[11px] text-slate-400 flex items-center justify-between">
                <span>Campven Kampus</span>
                <span>Platform Event Organisasi</span>
            </div>
        </div>

    </div>
</div>
@endsection
