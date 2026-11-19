<footer class="bg-white text-slate-600 py-14 border-t border-slate-200 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-slate-100">
            <!-- Brand Info -->
            <div class="space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <span class="text-lg font-extrabold text-slate-900 tracking-tight">Camp<span class="text-indigo-600">ven</span></span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs">
                    Platform terpusat manajemen kegiatan, pendaftaran tiket acara organisasi kampus, dan pencatatan keaktifan mahasiswa.
                </p>
            </div>

            <!-- Jelajahi -->
            <div class="space-y-2.5 text-xs">
                <h4 class="font-extrabold text-slate-900 text-xs tracking-wider uppercase">Jelajahi</h4>
                <ul class="space-y-2 text-slate-500">
                    <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Semua Acara Kampus</a></li>
                    <li><a href="{{ route('home') }}#categories" class="hover:text-indigo-600 transition">Kategori Kegiatan</a></li>
                    <li><a href="{{ route('home') }}#announcements" class="hover:text-indigo-600 transition">Pengumuman Resmi</a></li>
                    <li><a href="{{ route('home', ['kategori' => 'Seminar']) }}" class="hover:text-indigo-600 transition">Seminar &amp; Webinar</a></li>
                </ul>
            </div>

            <!-- Akses Akun -->
            <div class="space-y-2.5 text-xs">
                <h4 class="font-extrabold text-slate-900 text-xs tracking-wider uppercase">Portal Pengguna</h4>
                <ul class="space-y-2 text-slate-500">
                    @if(Auth::check())
                        @if(Auth::user()->role === 'mahasiswa')
                            <li><a href="{{ route('my.events') }}" class="hover:text-indigo-600 transition">Tiket &amp; Sertifikat Saya</a></li>
                        @elseif(Auth::user()->role === 'penyelenggara')
                            <li><a href="{{ route('organizer.dashboard') }}" class="hover:text-indigo-600 transition">Dashboard Penyelenggara</a></li>
                        @elseif(Auth::user()->role === 'admin')
                            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Dashboard Administrator</a></li>
                        @endif
                        <li><a href="{{ route('profile') }}" class="hover:text-indigo-600 transition">Pengaturan Profil</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-indigo-600 transition">Masuk Akun</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-indigo-600 transition">Daftar Mahasiswa Baru</a></li>
                    @endif
                    <li><a href="mailto:support@campven.com" class="hover:text-indigo-600 transition">Pusat Bantuan Mahasiswa</a></li>
                </ul>
            </div>

            <!-- Keterangan -->
            <div class="space-y-2.5 text-xs">
                <h4 class="font-extrabold text-slate-900 text-xs tracking-wider uppercase">Integrasi SKPI</h4>
                <p class="text-slate-500 leading-relaxed text-[11px]">
                    Setiap kegiatan yang dihadiri dan diverifikasi oleh panitia menghasilkan e-sertifikat resmi dengan nilai poin SKPI sesuai ketentuan acara.
                </p>
            </div>
        </div>

        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <div>© {{ date('Y') }} Campven — Aplikasi Manajemen Event Organisasi Kampus.</div>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="hover:text-slate-600 transition">Beranda</a>
                <a href="{{ route('home') }}#announcements" class="hover:text-slate-600 transition">Pengumuman</a>
                <a href="mailto:support@campven.com" class="hover:text-slate-600 transition">Kontak Bantuan</a>
            </div>
        </div>
    </div>
</footer>

