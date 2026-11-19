@extends('layouts.app')

@section('title', 'Manajemen User - Admin')

@section('content')
<style>
    .sidebar {
        width: 260px;
        background: #FFFFFF;
        border-right: 1px solid #E5E7EB;
        padding: 1.5rem 0;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 50;
        overflow-y: auto;
        transition: transform 0.3s ease;
    }
    .sidebar .nav-menu { list-style: none; padding: 1rem 0.8rem; }
    .sidebar .nav-menu li { margin-bottom: 2px; }
    .sidebar .nav-menu li a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0.7rem 1rem;
        border-radius: 10px;
        color: #6B7280;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .sidebar .nav-menu li a:hover { background: #F1F5F9; color: #334155; }
    .sidebar .nav-menu li a.active { background: #334155; color: white; }
    .sidebar .nav-menu li a i { width: 20px; text-align: center; font-size: 1rem; }
    .sidebar .nav-menu .nav-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #9CA3AF;
        padding: 0.5rem 1rem 0.2rem;
        font-weight: 600;
    }
    .sidebar .sidebar-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #E5E7EB;
        margin-top: auto;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
    }
    .main { margin-left: 260px; flex: 1; padding: 1.5rem 2rem 2rem; min-height: 100vh; }
    .topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 10px;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 1.2rem 1.5rem;
        border: 1px solid #E5E7EB;
        transition: all 0.2s;
    }
    .stat-card:hover { border-color: #334155; box-shadow: 0 4px 20px rgba(51,65,85,0.08); }
    .stat-card .stat-label { font-size: 0.75rem; font-weight: 500; color: #6B7280; text-transform: uppercase; letter-spacing: 0.3px; }
    .stat-card .stat-number { font-size: 1.8rem; font-weight: 700; color: #1F2937; margin-top: 0.2rem; }
    .stat-card .stat-icon { float: right; font-size: 1.5rem; color: #D1D5DB; }

    .table-wrapper {
        background: #FFFFFF;
        border-radius: 16px;
        border: 1px solid #E5E7EB;
        overflow: hidden;
    }
    .table-wrapper table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .table-wrapper th { background: #F8FAFC; color: #6B7280; font-weight: 600; padding: 0.7rem 1rem; text-align: left; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px; }
    .table-wrapper td { padding: 0.6rem 1rem; border-bottom: 1px solid #F1F5F9; }
    .table-wrapper tr:hover td { background: #F8FAFC; }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.show { display: flex; }
    .modal {
        background: #FFFFFF;
        border-radius: 16px;
        max-width: 560px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        padding: 1.8rem;
        animation: modalIn 0.25s ease;
    }
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    .modal .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.2rem; }
    .modal .modal-header h3 { font-size: 1.1rem; font-weight: 700; color: #1F2937; }
    .modal .modal-header h3 i { color: #334155; margin-right: 8px; }
    .modal .close-modal { background: none; border: none; font-size: 22px; cursor: pointer; color: #9CA3AF; transition: all 0.2s; }
    .modal .close-modal:hover { color: #EF4444; transform: rotate(90deg); }

    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-weight: 600; font-size: 0.8rem; color: #374151; margin-bottom: 0.2rem; }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 0.6rem 0.8rem;
        border: 1.5px solid #E5E7EB;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s;
        background: #FFFFFF;
        color: #1F2937;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        outline: none;
        border-color: #334155;
        box-shadow: 0 0 0 3px rgba(51,65,85,0.1);
    }
    .form-actions { display: flex; gap: 10px; margin-top: 1.2rem; justify-content: flex-end; }

    .mobile-toggle { display: none; font-size: 1.5rem; cursor: pointer; color: #6B7280; background: none; border: none; }
    .btn-edit { padding: 0.15rem 0.6rem; font-size: 0.65rem; border-radius: 50px; border: none; font-weight: 600; background: #F59E0B; color: white; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 3px; line-height: 1.4; }
    .btn-edit:hover { background: #D97706; }
    .btn-delete { padding: 0.15rem 0.6rem; font-size: 0.65rem; border-radius: 50px; font-weight: 600; background: #EF4444; color: white; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 3px; line-height: 1.4; }
    .btn-delete:hover { background: #DC2626; }
    .btn-delete:disabled { opacity: 0.5; cursor: not-allowed; }
    
    @media (max-width: 1024px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.open { transform: translateX(0); }
        .main { margin-left: 0; padding: 1rem; }
        .mobile-toggle { display: block; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
        .table-wrapper { overflow-x: auto; }
        .table-wrapper table { min-width: 600px; }
        .topbar { flex-direction: column; align-items: stretch; }
    }
</style>

<div style="display:flex;min-height:100vh;">

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar" id="sidebar">
        <div style="display:flex;align-items:center;gap:10px;padding:0 1.5rem 1.5rem;border-bottom:1px solid #E5E7EB;">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#334155,#475569);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;font-weight:800;">C</div>
            <div><span style="font-size:20px;font-weight:800;color:#1E293B;">Camp<span style="color:#334155;">ven</span></span></div>
        </div>

        <ul class="nav-menu">
            <li class="nav-label">Menu Utama</li>
            <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-calendar-alt"></i> Event</a></li>
            <li><a href="{{ route('admin.users') }}" class="active"><i class="fas fa-users"></i> User</a></li>
            <li><a href="{{ route('admin.categories') }}"><i class="fas fa-tags"></i> Kategori</a></li>
            <li class="nav-label" style="margin-top:1rem;">Akun</li>
            <li><a href="{{ route('profile') }}"><i class="fas fa-user-circle"></i> Profil</a></li>
            <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;width:100%;">
                    @csrf
                    <button type="submit" style="display:flex;align-items:center;gap:12px;padding:0.7rem 1rem;border-radius:10px;color:#6B7280;background:none;border:none;font-weight:500;font-size:0.9rem;width:100%;cursor:pointer;font-family:'Inter',sans-serif;">
                        <i class="fas fa-sign-out-alt" style="width:20px;text-align:center;font-size:1rem;"></i> Keluar
                    </button>
                </form>
            </li>
        </ul>

        <div class="sidebar-footer">
            <a href="{{ route('profile') }}" style="text-decoration:none;color:inherit;display:block;">
                <div style="display:flex;align-items:center;gap:10px;">
                    @if(Auth::user()->foto && Storage::disk('public')->exists(Auth::user()->foto))
                        <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto Profil" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #E5E7EB;">
                    @else
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#334155,#475569);color:white;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:14px;">{{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}</div>
                    @endif
                    <div>
                        <div style="font-weight:600;font-size:0.9rem;">{{ Auth::user()->nama }}</div>
                        <div style="font-size:0.75rem;color:#6B7280;">Administrator</div>
                    </div>
                </div>
            </a>
        </div>
    </aside>

    <!-- ===== MAIN ===== -->
    <main class="main">
        <div class="topbar">
            <div>
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="fas fa-bars"></i></button>
                <div style="display:inline-block;margin-left:10px;">
                    <h1 style="font-size:1.5rem;font-weight:800;">Manajemen User</h1>
                    <p style="color:#6B7280;font-size:0.9rem;">Kelola semua pengguna Campven</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <button onclick="document.getElementById('createUserModal').classList.add('show')" class="btn btn-primary" style="padding:0.5rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#334155;color:white;">
                    <i class="fas fa-user-plus"></i> Tambah User
                </button>
                <a href="{{ route('profile') }}" style="text-decoration:none;">
                    @if(Auth::user()->foto && Storage::disk('public')->exists(Auth::user()->foto))
                        <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto Profil" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #E5E7EB;">
                    @else
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#334155,#475569);color:white;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:14px;">{{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}</div>
                    @endif
                </a>
            </div>
        </div>

        <!-- ===== STATS ===== -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users stat-icon"></i>
                <div class="stat-label">Total User</div>
                <div class="stat-number">{{ $total_users }}</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-shield stat-icon"></i>
                <div class="stat-label">Admin</div>
                <div class="stat-number">{{ $total_admin }}</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-tie stat-icon"></i>
                <div class="stat-label">Penyelenggara</div>
                <div class="stat-number">{{ $total_penyelenggara }}</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-graduate stat-icon"></i>
                <div class="stat-label">Mahasiswa</div>
                <div class="stat-number">{{ $total_mahasiswa }}</div>
            </div>
        </div>

        @if(session('success'))
        <div class="notification success" style="padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;display:flex;align-items:center;gap:10px;font-weight:500;font-size:0.9rem;background:#D1FAE5;color:#065F46;border-left:4px solid #10B981;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button onclick="this.parentElement.remove()" style="margin-left:auto;cursor:pointer;background:none;border:none;font-size:16px;opacity:0.5;">&times;</button>
        </div>
        @endif
        @if(session('error'))
        <div class="notification error" style="padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;display:flex;align-items:center;gap:10px;font-weight:500;font-size:0.9rem;background:#FEE2E2;color:#991B1B;border-left:4px solid #EF4444;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button onclick="this.parentElement.remove()" style="margin-left:auto;cursor:pointer;background:none;border:none;font-size:16px;opacity:0.5;">&times;</button>
        </div>
        @endif

        <!-- ===== USERS TABLE ===== -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:10px;">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1F2937;"><i class="fas fa-users" style="color:#334155;margin-right:8px;"></i> Semua User</h2>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. Telp</th>
                        <th>Role</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td><strong>{{ $user->nama }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->no_telp ?? '-' }}</td>
                        <td>
                            <span style="display:inline-block;padding:0.1rem 0.5rem;border-radius:50px;font-size:0.65rem;font-weight:600;{{ $user->role == 'admin' ? 'background:#FEF3C7;color:#92400E;' : ($user->role == 'penyelenggara' ? 'background:#D1FAE5;color:#065F46;' : 'background:#DBEAFE;color:#1E40AF;') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            @if($user->role !== 'admin')
                            <form method="POST" action="{{ route('admin.user.delete', $user->id) }}" style="display:inline-flex;margin:0;padding:0;" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <span style="font-size:0.7rem;color:#9CA3AF;">Protected</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- ===== MODAL CREATE USER ===== -->
<div class="modal-overlay" id="createUserModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Tambah User</h3>
            <button class="close-modal" onclick="document.getElementById('createUserModal').classList.remove('show')">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.user.store') }}">
            @csrf
            <div class="form-group">
                <label>Nama <span style="color:#EF4444;">*</span></label>
                <input type="text" name="nama" placeholder="Nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Email <span style="color:#EF4444;">*</span></label>
                <input type="email" name="email" placeholder="email@example.com" required>
            </div>
            <div class="form-group">
                <label>Password <span style="color:#EF4444;">*</span></label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required>
            </div>
            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="no_telp" placeholder="08123456789">
            </div>
            <div class="form-group">
                <label>Role <span style="color:#EF4444;">*</span></label>
                <select name="role" required>
                    <option value="admin">Admin</option>
                    <option value="penyelenggara">Penyelenggara</option>
                    <option value="mahasiswa">Mahasiswa</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('createUserModal').classList.remove('show')" style="padding:0.5rem 1.2rem;border-radius:50px;border:1.5px solid #D1D5DB;background:transparent;color:#6B7280;font-weight:600;font-size:0.85rem;cursor:pointer;">Batal</button>
                <button type="submit" class="btn btn-primary" style="padding:0.5rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#334155;color:white;"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.remove('show');
        }
    });
    setTimeout(function() {
        const notif = document.querySelector('.notification');
        if (notif) {
            notif.style.transition = 'opacity 0.5s';
            notif.style.opacity = '0';
            setTimeout(() => notif.remove(), 500);
        }
    }, 5000);
</script>
@endsection
