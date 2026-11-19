@php
    $activeNav = $activeNav ?? 'home';
    $heroDark = $heroDark ?? false;
    $navUser = Auth::user();
@endphp
<nav class="sticky top-0 z-50 transition-colors duration-200 {{ $heroDark ? 'bg-slate-950/90 backdrop-blur-md border-b border-white/10 text-white' : 'bg-white/95 backdrop-blur-md border-b border-slate-200 text-slate-900' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-[72px] gap-4">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0 hover:opacity-95 transition-opacity">
                <x-campven-logo size="md" :variant="$heroDark ? 'light' : 'dark'" subtitle="Event Kampus" />
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center gap-7 text-sm" id="publicDesktopNav" data-hero-dark="{{ $heroDark ? '1' : '0' }}" data-active-nav="{{ $activeNav }}">
                <a href="{{ route('home') }}" data-nav-key="home" class="public-nav-link py-1 transition {{ $activeNav === 'home' ? ($heroDark ? 'font-semibold text-teal-400 border-b-2 border-teal-400' : 'font-semibold text-indigo-600 border-b-2 border-indigo-600') : ($heroDark ? 'font-medium text-slate-200 hover:text-white' : 'font-medium text-slate-600 hover:text-slate-900') }}">
                    Beranda
                </a>
                <a href="{{ route('home') }}#events" data-nav-key="events" class="public-nav-link py-1 font-medium transition {{ $heroDark ? 'text-slate-200 hover:text-white' : 'text-slate-600 hover:text-slate-900' }}">
                    Jelajahi Acara
                </a>
                <a href="{{ route('home') }}#categories" data-nav-key="categories" class="public-nav-link py-1 font-medium transition {{ $heroDark ? 'text-slate-200 hover:text-white' : 'text-slate-600 hover:text-slate-900' }}">
                    Kategori
                </a>
                <a href="{{ route('home') }}#announcements" data-nav-key="announcements" class="public-nav-link py-1 font-medium transition {{ $heroDark ? 'text-slate-200 hover:text-white' : 'text-slate-600 hover:text-slate-900' }}">
                    Pengumuman
                </a>
                @if($navUser && $navUser->role === 'mahasiswa')
                <a href="{{ route('my.events') }}" data-nav-key="my-events" class="public-nav-link py-1 transition {{ $activeNav === 'my-events' ? ($heroDark ? 'font-semibold text-teal-400 border-b-2 border-teal-400' : 'font-semibold text-indigo-600 border-b-2 border-indigo-600') : ($heroDark ? 'font-medium text-slate-200 hover:text-white' : 'font-medium text-slate-600 hover:text-slate-900') }}">
                    Tiket Saya
                </a>
                @endif
            </div>

            <!-- Right Actions + Mobile Hamburger -->
            <div class="flex items-center gap-2.5">
                @if($navUser)
                    @if($navUser->role === 'penyelenggara')
                        <a href="{{ route('organizer.dashboard') }}" class="btn-tactile active:scale-[0.98] hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wide transition {{ $heroDark ? 'bg-indigo-600 text-white hover:bg-indigo-500' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                            <span>Portal Penyelenggara</span>
                        </a>
                    @elseif($navUser->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn-tactile active:scale-[0.98] hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wide transition {{ $heroDark ? 'bg-indigo-600 text-white hover:bg-indigo-500' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                            <span>Dashboard Admin</span>
                        </a>
                    @endif

                    <!-- Upgraded Profile Pill + Dropdown Menu -->
                    <div class="relative" id="publicProfileDropdownWrapper">
                        <button type="button"
                                id="publicProfileDropdownBtn"
                                onclick="togglePublicProfileMenu()"
                                aria-expanded="false"
                                aria-haspopup="true"
                                class="btn-tactile flex items-center gap-2.5 py-1 pl-1.5 pr-3 rounded-full border transition-all duration-200 {{ $heroDark ? 'border-white/15 bg-white/[0.07] hover:bg-white/[0.14] hover:border-teal-400/40 text-white' : 'border-slate-200 bg-white hover:bg-slate-50 hover:border-indigo-200 text-slate-900 shadow-2xs' }}">
                            <div class="relative flex-shrink-0">
                                <div class="w-8 h-8 rounded-full overflow-hidden ring-2 {{ $heroDark ? 'ring-teal-400/60 bg-gradient-to-br from-indigo-500 to-teal-500 text-white' : 'ring-indigo-100 bg-gradient-to-br from-indigo-600 to-teal-500 text-white' }} flex items-center justify-center font-extrabold text-xs">
                                    @if($navUser->foto && Storage::disk('public')->exists($navUser->foto))
                                        <img src="{{ asset('storage/' . $navUser->foto) }}?v={{ optional($navUser->updated_at)->timestamp }}" alt="Foto {{ $navUser->nama }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($navUser->nama, 0, 1)) }}
                                    @endif
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-teal-400 ring-2 {{ $heroDark ? 'ring-slate-950' : 'ring-white' }}"></span>
                            </div>
                            <div class="hidden sm:block text-left pr-0.5">
                                <span class="block text-xs font-bold leading-tight truncate max-w-[115px]">{{ $navUser->nama }}</span>
                                <span class="block text-[10px] font-medium capitalize leading-tight {{ $heroDark ? 'text-teal-300/90' : 'text-slate-500' }}">{{ $navUser->role }}</span>
                            </div>
                            <i id="publicProfileChevron" class="fas fa-chevron-down text-[10px] hidden sm:inline-block transition-transform duration-200 {{ $heroDark ? 'text-slate-400' : 'text-slate-400' }}"></i>
                        </button>

                        <!-- Dropdown Panel -->
                        <div id="publicProfileMenu"
                             class="hidden absolute right-0 mt-2.5 w-64 rounded-2xl bg-white border border-slate-200/90 shadow-xl shadow-slate-900/15 overflow-hidden z-50 text-slate-800 origin-top-right transition-all duration-200">
                            <!-- User Header -->
                            <div class="px-4 py-3.5 bg-slate-50/90 border-b border-slate-100 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-gradient-to-br from-indigo-600 to-teal-500 text-white flex items-center justify-center font-extrabold text-sm flex-shrink-0 ring-2 ring-white shadow-xs">
                                    @if($navUser->foto && Storage::disk('public')->exists($navUser->foto))
                                        <img src="{{ asset('storage/' . $navUser->foto) }}?v={{ optional($navUser->updated_at)->timestamp }}" alt="Foto {{ $navUser->nama }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($navUser->nama, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-extrabold text-slate-900 truncate">{{ $navUser->nama }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $navUser->email }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold capitalize bg-indigo-50 text-indigo-700">
                                        {{ $navUser->role }}
                                    </span>
                                </div>
                            </div>

                            <!-- Menu Links -->
                            <div class="p-1.5 text-xs space-y-0.5">
                                <a href="{{ route('profile') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                    <i class="fas fa-user-cog w-4 text-center text-slate-400"></i>
                                    <span>Pengaturan Profil</span>
                                </a>

                                @if($navUser->role === 'mahasiswa')
                                <a href="{{ route('my.events') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                    <i class="fas fa-ticket-alt w-4 text-center text-teal-600"></i>
                                    <span>Tiket &amp; E-Sertifikat Saya</span>
                                </a>
                                @elseif($navUser->role === 'penyelenggara')
                                <a href="{{ route('organizer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                    <i class="fas fa-th-large w-4 text-center text-indigo-600"></i>
                                    <span>Portal Penyelenggara</span>
                                </a>
                                @elseif($navUser->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                    <i class="fas fa-shield-alt w-4 text-center text-indigo-600"></i>
                                    <span>Dashboard Admin</span>
                                </a>
                                @endif
                            </div>

                            <!-- Logout Action -->
                            <div class="p-1.5 border-t border-slate-100 text-xs">
                                <button type="button" onclick="closePublicProfileMenu(); openLogoutModal();" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl font-semibold text-rose-600 hover:bg-rose-50 transition text-left">
                                    <i class="fas fa-sign-out-alt w-4 text-center"></i>
                                    <span>Keluar dari Akun</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-block text-xs font-semibold px-3 py-2 transition {{ $heroDark ? 'text-slate-200 hover:text-white' : 'text-slate-700 hover:text-slate-900' }}">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn-tactile active:scale-[0.98] hidden sm:inline-block text-xs font-bold uppercase tracking-wide px-4 py-2.5 rounded-lg transition {{ $heroDark ? 'bg-teal-500 text-slate-950 hover:bg-teal-400' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                        Daftar Akun
                    </a>
                @endif

                <button type="button" onclick="togglePublicMobileMenu()" class="md:hidden w-9 h-9 rounded-lg border flex items-center justify-center text-sm transition {{ $heroDark ? 'border-white/20 bg-white/10 text-white hover:bg-white/20' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}" aria-label="Buka Menu">
                    <i class="fas fa-bars" id="publicMobileMenuIcon"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Dropdown (Smooth Slide-In) -->
    <div id="publicMobileMenu" class="mobile-nav-panel md:hidden border-t {{ $heroDark ? 'border-white/10 bg-slate-950 text-white' : 'border-slate-200 bg-white text-slate-900' }}">
        <div class="px-4 py-3 space-y-1 text-sm">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg font-semibold {{ $heroDark ? 'text-teal-400 bg-white/5' : 'text-indigo-600 bg-indigo-50' }}">
                <i class="fas fa-home w-4 text-center text-xs"></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('home') }}#events" onclick="togglePublicMobileMenu()" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg font-medium {{ $heroDark ? 'text-slate-200 hover:bg-white/10' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fas fa-compass w-4 text-center text-xs"></i>
                <span>Jelajahi Acara</span>
            </a>
            <a href="{{ route('home') }}#categories" onclick="togglePublicMobileMenu()" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg font-medium {{ $heroDark ? 'text-slate-200 hover:bg-white/10' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fas fa-tags w-4 text-center text-xs"></i>
                <span>Kategori Acara</span>
            </a>
            <a href="{{ route('home') }}#announcements" onclick="togglePublicMobileMenu()" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg font-medium {{ $heroDark ? 'text-slate-200 hover:bg-white/10' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fas fa-bullhorn w-4 text-center text-xs"></i>
                <span>Pengumuman Resmi</span>
            </a>

            @if($navUser)
                @if($navUser->role === 'mahasiswa')
                    <a href="{{ route('my.events') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg font-semibold {{ $heroDark ? 'text-teal-400 hover:bg-white/10' : 'text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fas fa-ticket-alt w-4 text-center text-xs"></i>
                        <span>Tiket Saya</span>
                    </a>
                @elseif($navUser->role === 'penyelenggara')
                    <a href="{{ route('organizer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg font-semibold {{ $heroDark ? 'text-teal-400 hover:bg-white/10' : 'text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fas fa-th-large w-4 text-center text-xs"></i>
                        <span>Portal Penyelenggara</span>
                    </a>
                @elseif($navUser->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg font-semibold {{ $heroDark ? 'text-teal-400 hover:bg-white/10' : 'text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fas fa-shield-alt w-4 text-center text-xs"></i>
                        <span>Dashboard Admin</span>
                    </a>
                @endif
                <a href="{{ route('profile') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg font-medium {{ $heroDark ? 'text-slate-200 hover:bg-white/10' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fas fa-user-circle w-4 text-center text-xs"></i>
                    <span>Profil Saya</span>
                </a>
                <button type="button" onclick="openLogoutModal()" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg font-semibold text-rose-500 hover:bg-rose-500/10 text-left">
                    <i class="fas fa-sign-out-alt w-4 text-center text-xs"></i>
                    <span>Keluar</span>
                </button>
            @else
                <div class="grid grid-cols-2 gap-2 pt-2 border-t {{ $heroDark ? 'border-white/10' : 'border-slate-100' }}">
                    <a href="{{ route('login') }}" class="btn-tactile active:scale-[0.98] py-2 text-center rounded-lg border font-semibold text-xs {{ $heroDark ? 'border-white/20 text-white' : 'border-slate-200 text-slate-700' }}">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-tactile active:scale-[0.98] py-2 text-center rounded-lg font-semibold text-xs {{ $heroDark ? 'bg-teal-500 text-slate-950' : 'bg-indigo-600 text-white' }}">Daftar Akun</a>
                </div>
            @endif
        </div>
    </div>
</nav>

<script>
    if (typeof window.togglePublicMobileMenu !== 'function') {
        window.togglePublicMobileMenu = function() {
            const menu = document.getElementById('publicMobileMenu');
            const icon = document.getElementById('publicMobileMenuIcon');
            if (!menu) return;
            menu.classList.toggle('is-open');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            }
        };
    }

    if (typeof window.togglePublicProfileMenu !== 'function') {
        window.closePublicProfileMenu = function() {
            const menu = document.getElementById('publicProfileMenu');
            const btn = document.getElementById('publicProfileDropdownBtn');
            const chevron = document.getElementById('publicProfileChevron');
            if (!menu) return;
            menu.classList.add('hidden');
            if (btn) btn.setAttribute('aria-expanded', 'false');
            if (chevron) chevron.classList.remove('rotate-180');
        };

        window.togglePublicProfileMenu = function() {
            const menu = document.getElementById('publicProfileMenu');
            const btn = document.getElementById('publicProfileDropdownBtn');
            const chevron = document.getElementById('publicProfileChevron');
            if (!menu) return;
            const willOpen = menu.classList.contains('hidden');
            menu.classList.toggle('hidden');
            if (btn) btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            if (chevron) chevron.classList.toggle('rotate-180', willOpen);
        };

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('publicProfileDropdownWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                window.closePublicProfileMenu();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.closePublicProfileMenu();
            }
        });
    }

    (function initPublicNavActiveIndicator() {
        const navContainer = document.getElementById('publicDesktopNav');
        if (!navContainer || navContainer.dataset.bound === '1') return;
        navContainer.dataset.bound = '1';

        const isHeroDark = navContainer.dataset.heroDark === '1';
        const defaultNav = navContainer.dataset.activeNav || 'home';
        const links = navContainer.querySelectorAll('.public-nav-link[data-nav-key]');

        const activeClasses = isHeroDark
            ? ['font-semibold', 'text-teal-400', 'border-b-2', 'border-teal-400']
            : ['font-semibold', 'text-indigo-600', 'border-b-2', 'border-indigo-600'];
        const inactiveClasses = isHeroDark
            ? ['font-medium', 'text-slate-200', 'hover:text-white']
            : ['font-medium', 'text-slate-600', 'hover:text-slate-900'];

        function setActiveNav(key) {
            links.forEach((link) => {
                const isMatch = link.dataset.navKey === key;
                link.classList.remove(...activeClasses, ...inactiveClasses);
                link.classList.add(...(isMatch ? activeClasses : inactiveClasses));
            });
        }

        function syncFromHash() {
            if (defaultNav !== 'home') {
                setActiveNav(defaultNav);
                return;
            }
            const hash = (window.location.hash || '').replace('#', '');
            if (['events', 'categories', 'announcements'].includes(hash)) {
                setActiveNav(hash);
            } else {
                setActiveNav('home');
            }
        }

        links.forEach((link) => {
            link.addEventListener('click', function() {
                const key = this.dataset.navKey;
                if (['home', 'events', 'categories', 'announcements'].includes(key) && defaultNav === 'home') {
                    setActiveNav(key);
                }
            });
        });

        window.addEventListener('hashchange', syncFromHash);
        syncFromHash();
    })();
</script>
