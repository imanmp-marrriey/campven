@extends('layouts.app')

@section('title', 'Masuk - Campven')

@section('content')
<div class="min-h-screen bg-slate-50/70 flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="max-w-4xl w-full bg-white rounded-3xl border border-slate-200/90 shadow-2xl overflow-hidden flex flex-col md:flex-row">
        
        <!-- Left: Form Column -->
        <div class="flex-1 p-8 sm:p-12 flex flex-col justify-between">
            <div>
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="inline-block mb-8 hover:opacity-95 transition-opacity">
                    <x-campven-logo size="md" subtitle="Pusat Kegiatan Kampus" />
                </a>

                <div class="mb-6">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
                    <p class="text-xs text-slate-500 mt-1">Satu pintu masuk untuk akun <strong>Mahasiswa</strong>, <strong>Penyelenggara Organisasi</strong>, dan <strong>Administrator</strong>.</p>
                </div>

                @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs mb-5 flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(isset($errors) && $errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs mb-5 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-rose-600"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4 text-xs">
                    @csrf
                    
                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700">Alamat Email (Mahasiswa / Penyelenggara / Admin)</label>
                        <div class="relative">
                            <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@campven.com" class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block font-bold text-slate-700">Kata Sandi</label>
                        </div>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="password" name="password" required placeholder="Masukkan kata sandi akun" class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:bg-white focus:border-indigo-600 transition">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs sm:text-sm shadow-md shadow-indigo-600/20 transition btn-spring mt-2 flex items-center justify-center gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk ke Akun</span>
                    </button>
                </form>
            </div>

            <div class="pt-8 border-t border-slate-100 text-center text-xs text-slate-500 mt-6">
                <span>Belum memiliki akun Campven?</span>
                <a href="{{ route('register') }}" class="text-indigo-600 font-extrabold hover:underline ml-1">
                    Daftar Akun Baru →
                </a>
            </div>
        </div>

        <!-- Right: Decorative Visual Column -->
        <div class="hidden md:flex flex-1 bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 p-10 flex-col justify-between text-white relative overflow-hidden">
            <div class="absolute -top-20 -right-20 w-80 h-80 bg-indigo-600/30 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-purple-600/20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <span class="px-3 py-1 rounded-full bg-white/10 text-[10px] font-bold tracking-wider uppercase backdrop-blur-md border border-white/10">
                    Sistem Kemahasiswaan 2026
                </span>
            </div>

            <div class="relative z-10 space-y-4 my-auto">
                <h3 class="text-2xl font-black leading-tight text-white drop-shadow-sm">
                    Aktivitas Kampus <br><span class="text-indigo-300">Lebih Tertata & Terhubung</span>
                </h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Satu portal resmi untuk eksplorasi seminar, pendaftaran kepanitiaan, presensi tiket QR, dan penarikan poin SKPI terakreditasi.
                </p>

                <div class="space-y-2.5 pt-4">
                    <div class="flex items-center gap-3 text-xs text-slate-200">
                        <div class="w-7 h-7 rounded-xl bg-white/10 flex items-center justify-center text-emerald-400 text-xs">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Tiket Digital QR Otomatis</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-200">
                        <div class="w-7 h-7 rounded-xl bg-white/10 flex items-center justify-center text-indigo-300 text-xs">
                            <i class="fas fa-award"></i>
                        </div>
                        <span>Poin SKPI & Sertifikat Resmi</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-200">
                        <div class="w-7 h-7 rounded-xl bg-white/10 flex items-center justify-center text-purple-300 text-xs">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span>Sistem Verifikasi Gerbang Real-time</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10 pt-4 text-[11px] text-slate-400 flex items-center justify-between">
                <span>Politeknik Negeri Indramayu</span>
                <span>Campven Platform v2.6</span>
            </div>
        </div>

    </div>
</div>
@endsection
