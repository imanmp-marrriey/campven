<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Campven - Platform Kegiatan Kampus Terpadu')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #1E293B;
            --primary-light: #334155;
            --accent: #4F46E5;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F8FAFC;
            color: #1E293B;
            overflow-x: hidden;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.85);
        }

        .interactive-card {
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, box-shadow;
        }
        .interactive-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 35px -10px rgba(30, 41, 59, 0.12), 0 10px 15px -8px rgba(30, 41, 59, 0.06);
        }

        .btn-spring {
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .btn-spring:active {
            transform: scale(0.96);
        }

        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }
        .toast-animate {
            animation: toastSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .toast-progress {
            animation: toastProgress 4.5s linear forwards;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-indigo-600 selection:text-white">

    <!-- Main Content Yield -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Floating Toast Notification System -->
    <div id="toastContainer" class="fixed bottom-5 right-5 z-[99999] flex flex-col gap-3 max-w-md w-full px-4 sm:px-0 pointer-events-none">
        @if(session('success'))
        <div class="toast-item toast-animate pointer-events-auto relative overflow-hidden bg-white/95 backdrop-blur-xl border border-emerald-200 shadow-xl shadow-emerald-500/10 rounded-2xl p-4 flex items-start gap-3.5 transition-all duration-300">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 text-base shadow-sm">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="flex-grow pr-2">
                <h4 class="text-sm font-bold text-slate-900 leading-tight">Berhasil</h4>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-600 p-1 text-sm transition-colors" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
            <div class="toast-progress absolute bottom-0 left-0 h-1 bg-emerald-500"></div>
        </div>
        @endif

        @if(session('error'))
        <div class="toast-item toast-animate pointer-events-auto relative overflow-hidden bg-white/95 backdrop-blur-xl border border-rose-200 shadow-xl shadow-rose-500/10 rounded-2xl p-4 flex items-start gap-3.5 transition-all duration-300">
            <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 text-base shadow-sm">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="flex-grow pr-2">
                <h4 class="text-sm font-bold text-slate-900 leading-tight">Perhatian</h4>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('error') }}</p>
            </div>
            <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-600 p-1 text-sm transition-colors" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
            <div class="toast-progress absolute bottom-0 left-0 h-1 bg-rose-500"></div>
        </div>
        @endif

        @if(isset($errors) && $errors->any())
        <div class="toast-item toast-animate pointer-events-auto relative overflow-hidden bg-white/95 backdrop-blur-xl border border-rose-200 shadow-xl shadow-rose-500/10 rounded-2xl p-4 flex items-start gap-3.5 transition-all duration-300">
            <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 text-base shadow-sm">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="flex-grow pr-2">
                <h4 class="text-sm font-bold text-slate-900 leading-tight">Validasi Gagal</h4>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ $errors->first() }}</p>
            </div>
            <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-600 p-1 text-sm transition-colors" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
            <div class="toast-progress absolute bottom-0 left-0 h-1 bg-rose-500"></div>
        </div>
        @endif
    </div>

    <!-- Global Logout Confirmation Modal -->
    <div id="logoutModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
        <div id="logoutModalBackdrop" onclick="closeLogoutModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity duration-300 opacity-0"></div>
        <div id="logoutModalCard" class="relative bg-white w-full max-w-sm rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 text-center transform scale-95 opacity-0 transition-all duration-300 ease-out">
            <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl shadow-inner">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900 mb-1">Konfirmasi Keluar</h3>
            <p class="text-xs sm:text-sm text-slate-500 mb-6 leading-relaxed">Apakah Anda yakin ingin keluar dari akun Anda sekarang?</p>
            <div class="flex items-center gap-3">
                <button id="btnCancelLogout" onclick="closeLogoutModal()" type="button" class="flex-1 py-3 px-4 rounded-xl border border-slate-200 text-slate-700 font-semibold text-xs sm:text-sm hover:bg-slate-50 transition btn-spring">
                    Batal
                </button>
                <form id="globalLogoutForm" method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button id="btnConfirmLogout" type="submit" class="w-full py-3 px-4 rounded-xl bg-rose-600 text-white font-semibold text-xs sm:text-sm hover:bg-rose-700 shadow-lg shadow-rose-600/20 transition btn-spring">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Global Delete Confirmation Modal -->
    <div id="confirmDeleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
        <div id="confirmDeleteBackdrop" onclick="closeDeleteModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity duration-300 opacity-0"></div>
        <div id="confirmDeleteCard" class="relative bg-white w-full max-w-sm rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 text-center transform scale-95 opacity-0 transition-all duration-300 ease-out">
            <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl shadow-inner">
                <i class="fas fa-trash-alt"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900 mb-1">Hapus Data?</h3>
            <p class="text-xs sm:text-sm text-slate-500 mb-6 leading-relaxed">
                Apakah Anda yakin ingin menghapus <span id="confirmDeleteTargetName" class="font-bold text-slate-900"></span>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 py-3 px-4 rounded-xl border border-slate-200 text-slate-700 font-semibold text-xs sm:text-sm hover:bg-slate-50 transition btn-spring">
                    Batal
                </button>
                <form id="globalDeleteForm" method="POST" action="" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-rose-600 text-white font-semibold text-xs sm:text-sm hover:bg-rose-700 shadow-lg shadow-rose-600/20 transition btn-spring">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Global Scripts -->
    <script>
        function dismissToast(element) {
            if (!element) return;
            element.style.opacity = '0';
            element.style.transform = 'translateY(15px) scale(0.95)';
            setTimeout(() => element.remove(), 300);
        }

        window.openLogoutModal = function() {
            const logoutModal = document.getElementById('logoutModal');
            const backdrop = document.getElementById('logoutModalBackdrop');
            const card = document.getElementById('logoutModalCard');
            logoutModal.classList.remove('hidden');
            logoutModal.classList.add('flex');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                card.classList.remove('opacity-0', 'scale-95');
                card.classList.add('opacity-100', 'scale-100');
            }, 10);
        };

        window.closeLogoutModal = function() {
            const logoutModal = document.getElementById('logoutModal');
            const backdrop = document.getElementById('logoutModalBackdrop');
            const card = document.getElementById('logoutModalCard');
            backdrop.classList.add('opacity-0');
            card.classList.add('opacity-0', 'scale-95');
            card.classList.remove('opacity-100', 'scale-100');
            setTimeout(() => {
                logoutModal.classList.add('hidden');
                logoutModal.classList.remove('flex');
            }, 300);
        };

        window.openDeleteModal = function(actionUrl, itemName) {
            const modal = document.getElementById('confirmDeleteModal');
            const backdrop = document.getElementById('confirmDeleteBackdrop');
            const card = document.getElementById('confirmDeleteCard');
            document.getElementById('globalDeleteForm').action = actionUrl;
            document.getElementById('confirmDeleteTargetName').innerText = itemName ? '"' + itemName + '"' : 'data ini';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                card.classList.remove('opacity-0', 'scale-95');
                card.classList.add('opacity-100', 'scale-100');
            }, 10);
        };

        window.closeDeleteModal = function() {
            const modal = document.getElementById('confirmDeleteModal');
            const backdrop = document.getElementById('confirmDeleteBackdrop');
            const card = document.getElementById('confirmDeleteCard');
            backdrop.classList.add('opacity-0');
            card.classList.add('opacity-0', 'scale-95');
            card.classList.remove('opacity-100', 'scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        };

        document.addEventListener('DOMContentLoaded', () => {
            const toasts = document.querySelectorAll('.toast-item');
            toasts.forEach(toast => {
                setTimeout(() => dismissToast(toast), 4500);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
