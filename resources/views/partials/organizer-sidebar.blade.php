@php
    $activeOrganizer = $activeOrganizer ?? 'dashboard';
@endphp
<!-- Mobile Drawer Backdrop -->
<div id="organizerSidebarBackdrop" onclick="toggleOrganizerSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40 hidden opacity-0 transition-opacity duration-250 ease-[cubic-bezier(0.22,1,0.36,1)] md:hidden"></div>

<aside id="organizerSidebar" class="w-64 bg-white border-r border-slate-200/90 flex flex-col justify-between fixed top-0 bottom-0 left-0 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)]">
    <div>
        <!-- Brand Logo -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100">
            <a href="{{ route('organizer.dashboard') }}" class="block">
                <x-campven-logo size="md" subtitle="Portal Penyelenggara" />
            </a>
            <button type="button" onclick="toggleOrganizerSidebar()" class="md:hidden w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-xs">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Nav Menu List -->
        <div class="p-4 space-y-1.5 text-xs">
            <div class="px-3 py-1.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Menu Penyelenggara</div>

            <a href="{{ route('organizer.dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ $activeOrganizer === 'dashboard' ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-semibold' }}">
                <i class="fas fa-th-large text-sm w-4 {{ $activeOrganizer === 'dashboard' ? '' : 'text-slate-400' }}"></i>
                <span>Ringkasan &amp; Acara</span>
            </a>

            <a href="{{ route('organizer.dashboard') }}#eventsTable" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ $activeOrganizer === 'participants' ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-semibold' }}">
                <i class="fas fa-users text-sm w-4 {{ $activeOrganizer === 'participants' ? '' : 'text-slate-400' }}"></i>
                <span>Daftar Acara &amp; Peserta</span>
            </a>

            <a href="{{ route('organizer.dashboard') }}#checkinPanel" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-semibold transition">
                <i class="fas fa-user-check text-sm w-4 text-slate-400"></i>
                <span>Presensi Peserta</span>
            </a>

            <a href="{{ route('organizer.exportReport', ['format' => 'excel']) }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-semibold transition">
                <i class="fas fa-file-excel text-sm w-4 text-teal-600"></i>
                <span>Ekspor Laporan Excel (.xls)</span>
            </a>

            <a href="{{ route('organizer.exportReport', ['format' => 'word']) }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-semibold transition">
                <i class="fas fa-file-word text-sm w-4 text-indigo-600"></i>
                <span>Ekspor Laporan Word (.doc)</span>
            </a>

            <div class="px-3 pt-4 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Akun</div>

            <a href="{{ route('profile') }}" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-2xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-semibold transition">
                <i class="fas fa-user-circle text-sm w-4 text-slate-400"></i>
                <span>Profil Organisasi</span>
            </a>

            <button type="button" onclick="openLogoutModal()" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-2xl text-rose-600 hover:bg-rose-50 font-semibold transition text-left">
                <i class="fas fa-sign-out-alt text-sm w-4"></i>
                <span>Keluar</span>
            </button>
        </div>
    </div>

    <div class="p-4 border-t border-slate-100">
        <a href="{{ route('home') }}" class="w-full flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 font-bold text-xs transition">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Kembali ke Beranda Kampus</span>
        </a>
    </div>
</aside>

<script>
    if (typeof window.toggleOrganizerSidebar !== 'function') {
        window.toggleOrganizerSidebar = function() {
            const sidebar = document.getElementById('organizerSidebar');
            const backdrop = document.getElementById('organizerSidebarBackdrop');
            if (!sidebar || !backdrop) return;
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                backdrop.classList.remove('hidden');
                requestAnimationFrame(() => {
                    backdrop.classList.remove('opacity-0');
                    sidebar.classList.remove('-translate-x-full');
                });
            } else {
                backdrop.classList.add('opacity-0');
                sidebar.classList.add('-translate-x-full');
                setTimeout(() => backdrop.classList.add('hidden'), 260);
            }
        };
    }
</script>

