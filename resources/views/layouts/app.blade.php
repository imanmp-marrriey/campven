<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Campven - Your Campus, Organized')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <style>
        /* ===== GLOBAL STYLES ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #6C63FF;
            --primary-dark: #5A52D5;
            --primary-light: #8B83FF;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            --radius: 16px;
            --radius-sm: 10px;
            --shadow: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.08);
        }
        body { font-family: 'Inter', sans-serif; background: #FFFFFF; color: var(--gray-800); min-height: 100vh; }

        .btn {
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Inter', sans-serif;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 4px 15px rgba(108,99,255,0.3); }
        .btn-outline { background: transparent; color: var(--gray-700); border: 1.5px solid var(--gray-300); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-sm { padding: 0.3rem 0.8rem; font-size: 0.75rem; }
        .btn-success { background: var(--success); color: white; }
        .btn-success:hover { opacity: 0.85; transform: translateY(-2px); }
        .btn-danger { background: var(--danger); color: white; }
        .btn-danger:hover { opacity: 0.85; transform: translateY(-2px); }
        .btn-warning { background: var(--warning); color: white; }
        .btn-warning:hover { opacity: 0.85; transform: translateY(-2px); }

        .container { max-width: 1280px; margin: 0 auto; padding: 0 2rem; }

        .notification {
            padding: 0.8rem 1.2rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            font-size: 0.9rem;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .notification.success { background: #D1FAE5; color: #065F46; border-left: 4px solid var(--success); }
        .notification.error { background: #FEE2E2; color: #991B1B; border-left: 4px solid var(--danger); }

        @media (max-width: 768px) {
            .container { padding: 0 1rem; }
        }

        /* ===== LOGOUT MODAL STYLES ===== */
        .logout-modal-open {
            overflow: hidden;
        }
        #btnCancelLogout, #btnConfirmLogout {
            transition: all 0.2s ease;
        }
        #btnCancelLogout:hover {
            background-color: #F3F4F6 !important;
            border-color: #9CA3AF !important;
            color: #111827 !important;
        }
        #btnConfirmLogout:hover {
            background-color: #1D4ED8 !important;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3) !important;
        }
    </style>
</head>
<body>
    @yield('content')

    @if(session('success'))
    <script>
        setTimeout(function() {
            const notif = document.querySelector('.notification.success');
            if (notif) {
                notif.style.transition = 'opacity 0.5s';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            }
        }, 5000);
    </script>
    @endif

    @if(session('error'))
    <script>
        setTimeout(function() {
            const notif = document.querySelector('.notification.error');
            if (notif) {
                notif.style.transition = 'opacity 0.5s';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            }
        }, 5000);
    </script>
    @endif

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; padding: 1.5rem; box-sizing: border-box;">
        <!-- Backdrop -->
        <div id="logoutModalBackdrop" style="position: absolute; inset: 0; background: rgba(17, 24, 39, 0.4); backdrop-filter: blur(8px); transition: opacity 0.3s ease; opacity: 0;"></div>
        
        <!-- Modal Card -->
        <div id="logoutModalCard" style="position: relative; background: #ffffff; width: 100%; max-width: 400px; border-radius: 20px; box-shadow: var(--shadow-lg); border: 1px solid var(--gray-200); padding: 2rem; text-align: center; transform: scale(0.95); opacity: 0; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); max-height: 90vh; overflow-y: auto;">
            
            <!-- Icon Container -->
            <div style="width: 64px; height: 64px; background: #DBEAFE; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #2563EB; font-size: 1.75rem;">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            
            <!-- Text -->
            <h3 style="font-family: 'Inter', sans-serif; font-size: 1.25rem; font-weight: 700; color: var(--gray-900); margin-bottom: 0.5rem;">Konfirmasi Keluar</h3>
            <p style="font-family: 'Inter', sans-serif; font-size: 0.9rem; color: var(--gray-500); line-height: 1.5; margin-bottom: 2rem;">Apakah Anda yakin ingin keluar dari akun Anda?</p>
            
            <!-- Action Buttons -->
            <div style="display: flex; gap: 0.75rem; justify-content: center;">
                <button id="btnCancelLogout" type="button" style="flex: 1; padding: 0.75rem 1.5rem; border-radius: 12px; border: 1px solid var(--gray-300); background: #FFFFFF; color: var(--gray-700); font-weight: 600; font-size: 0.9rem; cursor: pointer; font-family: 'Inter', sans-serif;">
                    Batal
                </button>
                <button id="btnConfirmLogout" type="button" style="flex: 1; padding: 0.75rem 1.5rem; border-radius: 12px; border: none; background: #2563EB; color: #FFFFFF; font-weight: 600; font-size: 0.9rem; cursor: pointer; font-family: 'Inter', sans-serif; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
                    Keluar
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutModal = document.getElementById('logoutModal');
            const backdrop = document.getElementById('logoutModalBackdrop');
            const card = document.getElementById('logoutModalCard');
            const btnCancel = document.getElementById('btnCancelLogout');
            const btnConfirm = document.getElementById('btnConfirmLogout');
            let pendingLogoutForm = null;

            // Intercept all forms posting to logout
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form && form.action && form.action.includes('/logout')) {
                    e.preventDefault();
                    pendingLogoutForm = form;
                    openModal();
                }
            });

            function openModal() {
                document.body.classList.add('logout-modal-open');
                logoutModal.style.display = 'flex';
                
                // Trigger reflow
                void logoutModal.offsetWidth;
                
                backdrop.style.opacity = '1';
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            }

            function closeModal() {
                backdrop.style.opacity = '0';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                
                document.body.classList.remove('logout-modal-open');
                
                setTimeout(() => {
                    if (backdrop.style.opacity === '0') {
                        logoutModal.style.display = 'none';
                        pendingLogoutForm = null;
                    }
                }, 300);
            }

            btnCancel.addEventListener('click', closeModal);
            backdrop.addEventListener('click', closeModal);
            
            btnConfirm.addEventListener('click', function() {
                if (pendingLogoutForm) {
                    pendingLogoutForm.submit();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && logoutModal.style.display === 'flex') {
                    closeModal();
                }
            });
        });
    </script>
</body>
</html>