@php
    $activeNav = $activeNav ?? 'home';
    $navUser = Auth::user();
@endphp
<nav class="sticky top-0 z-50 glass-nav transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 gap-3">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group flex-shrink-0">
                <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-xl shadow-md shadow-indigo-600/20 group-hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-cubes text-sm"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold text-slate-900 tracking-tight">Camp<span class="text-indigo-600">ven</span></span>
                    <span class="block text-[10px] font-semibold text-slate-400 tracking-wider -mt-1">Portal Kegiatan Kampus</span>
                </div>
            </a>

            <!-- Quick Search Shortcut (Desktop Large) -->
            <form method="GET" action="{{ route('home') }}" class="hidden lg:flex items-center gap-2 bg-slate-100/80 border border-slate-200/80 rounded-full px-4 py-2 w-72 text-slate-500 text-xs">
                <i class="fas fa-search text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari event kampus..." class="bg-transparent text-slate-700 w-full focus:outline-none placeholder-slate-400 text-xs">
                <kbd class="px-1.5 py-0.5 rounded bg-white border border-slate-200 text-[10px] font-bold text-slate-500">⌘K</kbd>
            </form>

            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center gap-1.5 bg-slate-100/70 p-1 rounded-full border border-slate-200/60">
                <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-full text-xs font-bold transition {{ $activeNav === 'home' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Jelajahi Acara
                </a>
                <a href="{{ route('home') }}#categories" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                    Kategori
                </a>
                <a href="{{ route('home') }}#announcements" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                    Pengumuman
                </a>
                @if($navUser && $navUser->role === 'mahasiswa')
                <a href="{{ route('my.events') }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $activeNav === 'my-events' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Tiket Saya
                </a>
                @elseif($navUser && $navUser->role === 'penyelenggara')
                <a href="{{ route('organizer.dashboard') }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
                    Portal Penyelenggara
                </a>
                @elseif($navUser && $navUser->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
                    Dashboard Admin
                </a>
                @endif
            </div>

            <!-- Right Actions + Mobile Hamburger -->
            <div class="flex items-center gap-2.5">
                @if($navUser)
                    <a href="{{ route('profile') }}" class="flex items-center gap-2.5 p-1 rounded-full border border-slate-200 bg-white hover:bg-slate-50 transition shadow-sm">
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-slate-800 text-white flex items-center justify-center font-bold text-xs">
                            @if($navUser->foto && Storage::disk('public')->exists($navUser->foto))
                                <img src="{{ Storage::url($navUser->foto) }}" alt="Foto" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($navUser->nama, 0, 1)) }}
                            @endif
                        </div>
                        <div class="hidden sm:block text-left pr-2">
                            <span class="block text-xs font-bold text-slate-900 leading-none truncate max-w-[100px]">{{ $navUser->nama }}</span>
                            <span class="text-[10px] font-medium text-slate-400 capitalize">{{ $navUser->role }}</span>
                        </div>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-block text-xs font-bold text-slate-700 hover:text-slate-900 px-3 py-2 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-block text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2.5 rounded-full shadow-md shadow-indigo-600/20 transition btn-spring">
                        Daftar Akun
                    </a>
                @endif

                <button type="button" onclick="togglePublicMobileMenu()" class="md:hidden w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-base transition" aria-label="Buka Menu">
                    <i class="fas fa-bars" id="publicMobileMenuIcon"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Dropdown -->
    <div id="publicMobileMenu" class="hidden md:hidden border-t border-slate-200/80 bg-white/95 backdrop-blur-xl px-4 py-4 space-y-2 text-xs">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-4 py-3 rounded-2xl font-bold {{ $activeNav === 'home' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-700 hover:bg-slate-50' }}">
            <i class="fas fa-compass w-4 text-center"></i>
            <span>Jelajahi Acara</span>
        </a>
        <a href="{{ route('home') }}#categories" onclick="togglePublicMobileMenu()" class="flex items-center gap-2.5 px-4 py-3 rounded-2xl font-semibold text-slate-700 hover:bg-slate-50">
            <i class="fas fa-tags w-4 text-center"></i>
            <span>Kategori Acara</span>
        </a>
        <a href="{{ route('home') }}#announcements" onclick="togglePublicMobileMenu()" class="flex items-center gap-2.5 px-4 py-3 rounded-2xl font-semibold text-slate-700 hover:bg-slate-50">
            <i class="fas fa-bullhorn w-4 text-center"></i>
            <span>Pengumuman Resmi</span>
        </a>

        @if($navUser)
            @if($navUser->role === 'mahasiswa')
                <a href="{{ route('my.events') }}" class="flex items-center gap-2.5 px-4 py-3 rounded-2xl font-bold {{ $activeNav === 'my-events' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fas fa-ticket-alt w-4 text-center"></i>
                    <span>Tiket Saya</span>
                </a>
            @elseif($navUser->role === 'penyelenggara')
                <a href="{{ route('organizer.dashboard') }}" class="flex items-center gap-2.5 px-4 py-3 rounded-2xl font-bold text-indigo-700 bg-indigo-50">
                    <i class="fas fa-th-large w-4 text-center"></i>
                    <span>Portal Penyelenggara</span>
                </a>
            @elseif($navUser->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-3 rounded-2xl font-bold text-indigo-700 bg-indigo-50">
                    <i class="fas fa-shield-alt w-4 text-center"></i>
                    <span>Dashboard Admin</span>
                </a>
            @endif
            <a href="{{ route('profile') }}" class="flex items-center gap-2.5 px-4 py-3 rounded-2xl font-semibold text-slate-700 hover:bg-slate-50">
                <i class="fas fa-user-circle w-4 text-center"></i>
                <span>Profil Saya</span>
            </a>
            <button type="button" onclick="openLogoutModal()" class="w-full flex items-center gap-2.5 px-4 py-3 rounded-2xl font-bold text-rose-600 hover:bg-rose-50 text-left">
                <i class="fas fa-sign-out-alt w-4 text-center"></i>
                <span>Keluar dari Akun</span>
            </button>
        @else
            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100">
                <a href="{{ route('login') }}" class="py-2.5 text-center rounded-xl border border-slate-200 font-bold text-slate-700">Masuk</a>
                <a href="{{ route('register') }}" class="py-2.5 text-center rounded-xl bg-indigo-600 font-bold text-white">Daftar Akun</a>
            </div>
        @endif
    </div>
</nav>

<script>
    if (typeof window.togglePublicMobileMenu !== 'function') {
        window.togglePublicMobileMenu = function() {
            const menu = document.getElementById('publicMobileMenu');
            const icon = document.getElementById('publicMobileMenuIcon');
            if (!menu) return;
            menu.classList.toggle('hidden');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            }
        };
    }
    document.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
            const input = document.getElementById('mainSearchInput') || document.querySelector('input[name="search"]');
            if (input) {
                e.preventDefault();
                input.focus();
            }
        }
    });
</script>

