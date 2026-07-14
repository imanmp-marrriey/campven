<?php $__env->startSection('title', 'Admin Dashboard - Campven'); ?>

<?php $__env->startSection('content'); ?>
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
    .sidebar .nav-menu li a:hover { background: #F3F4F6; color: #6C63FF; }
    .sidebar .nav-menu li a.active { background: #6C63FF; color: white; }
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
    .stat-card:hover { border-color: #6C63FF; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
    .stat-card .stat-label { font-size: 0.75rem; font-weight: 500; color: #6B7280; text-transform: uppercase; letter-spacing: 0.3px; }
    .stat-card .stat-number { font-size: 1.8rem; font-weight: 700; color: #1F2937; margin-top: 0.2rem; }
    .stat-card .stat-icon { float: right; font-size: 1.5rem; color: #D1D5DB; }

    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.2rem;
    }
    .event-card {
        background: #FFFFFF;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #E5E7EB;
        transition: all 0.2s;
    }
    .event-card:hover { border-color: #6C63FF; box-shadow: 0 4px 20px rgba(0,0,0,0.06); transform: translateY(-3px); }
    .event-card .card-image {
        height: 180px;
        background: linear-gradient(135deg, #D1D5DB, #9CA3AF);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        position: relative;
        overflow: hidden;
    }
    .event-card .card-image img { width: 100%; height: 100%; object-fit: cover; }
    .event-card .card-image .no-poster {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    .event-card .card-image .category-tag {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        color: white;
    }
    .event-card .card-header { padding: 1rem 1.2rem; }
    .event-card .card-header h3 { font-size: 1rem; font-weight: 700; margin: 0; }
    .event-card .card-body { padding: 0 1.2rem 0.5rem; }
    .event-card .card-body .event-meta { display: flex; flex-direction: column; gap: 4px; margin-bottom: 0.3rem; }
    .event-card .card-body .event-meta .meta-item { display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #6B7280; }
    .event-card .card-body .event-meta .meta-item i { width: 16px; font-size: 0.75rem; color: #6C63FF; }
    .event-card .card-footer {
        padding: 0.8rem 1.2rem;
        border-top: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }

    .table-wrapper {
        background: #FFFFFF;
        border-radius: 16px;
        border: 1px solid #E5E7EB;
        overflow: hidden;
    }
    .table-wrapper table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .table-wrapper th { background: #F9FAFB; color: #6B7280; font-weight: 600; padding: 0.7rem 1rem; text-align: left; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px; }
    .table-wrapper td { padding: 0.6rem 1rem; border-bottom: 1px solid #F3F4F6; }
    .table-wrapper tr:hover td { background: #F9FAFB; }

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
    .modal .modal-header h3 i { color: #6C63FF; margin-right: 8px; }
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
        border-color: #6C63FF;
        box-shadow: 0 0 0 3px rgba(108,99,255,0.1);
    }
    .form-group textarea { min-height: 70px; resize: vertical; }
    .form-group input[type="file"] { padding: 0.4rem; border: 1px dashed #D1D5DB; background: #F9FAFB; border-radius: 10px; cursor: pointer; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-actions { display: flex; gap: 10px; margin-top: 1.2rem; justify-content: flex-end; }

    .mobile-toggle { display: none; font-size: 1.5rem; cursor: pointer; color: #6B7280; background: none; border: none; }
    .btn-edit { padding: 0.15rem 0.6rem; font-size: 0.65rem; border-radius: 50px; border: none; font-weight: 600; background: #F59E0B; color: white; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; }
    .btn-edit:hover { background: #D97706; }
    .btn-delete { padding: 0.15rem 0.6rem; font-size: 0.65rem; border-radius: 50px; font-weight: 600; background: #EF4444; color: white; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; }
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
        .event-grid { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
        .table-wrapper { overflow-x: auto; }
        .table-wrapper table { min-width: 600px; }
        .topbar { flex-direction: column; align-items: stretch; }
    }
</style>

<div style="display:flex;min-height:100vh;">

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar" id="sidebar">
        <div style="display:flex;align-items:center;gap:10px;padding:0 1.5rem 1.5rem;border-bottom:1px solid #E5E7EB;">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#6C63FF,#8B83FF);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;font-weight:800;">C</div>
            <div><span style="font-size:20px;font-weight:800;color:#1F2937;">Camp<span style="color:#6C63FF;">ven</span></span></div>
        </div>

        <ul class="nav-menu">
            <li class="nav-label">Menu Utama</li>
            <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="active"><i class="fas fa-calendar-alt"></i> Event</a></li>
            <li><a href="#users"><i class="fas fa-users"></i> User</a></li>
            <li><a href="<?php echo e(route('admin.categories')); ?>"><i class="fas fa-tags"></i> Kategori</a></li>
            <li class="nav-label" style="margin-top:1rem;">Akun</li>
            <li><a href="<?php echo e(route('profile')); ?>"><i class="fas fa-user-circle"></i> Profil</a></li>
            <li><a href="<?php echo e(route('home')); ?>"><i class="fas fa-home"></i> Beranda</a></li>
            <li>
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline;width:100%;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" style="display:flex;align-items:center;gap:12px;padding:0.7rem 1rem;border-radius:10px;color:#6B7280;background:none;border:none;font-weight:500;font-size:0.9rem;width:100%;cursor:pointer;font-family:'Inter',sans-serif;">
                        <i class="fas fa-sign-out-alt" style="width:20px;text-align:center;font-size:1rem;"></i> Keluar
                    </button>
                </form>
            </li>
        </ul>

        <div class="sidebar-footer">
            <a href="<?php echo e(route('profile')); ?>" style="text-decoration:none;color:inherit;display:block;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6C63FF,#8B83FF);color:white;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:14px;"><?php echo e(strtoupper(substr(Auth::user()->nama, 0, 1))); ?></div>
                    <div>
                        <div style="font-weight:600;font-size:0.9rem;"><?php echo e(Auth::user()->nama); ?></div>
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
                    <h1 style="font-size:1.5rem;font-weight:800;">Dashboard Admin</h1>
                    <p style="color:#6B7280;font-size:0.9rem;">Kelola semua event dan user Campven</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <button onclick="document.getElementById('createEventModal').classList.add('show')" class="btn btn-primary" style="padding:0.5rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#6C63FF;color:white;">
                    <i class="fas fa-plus"></i> Tambah Event
                </button>
                <button onclick="document.getElementById('createUserModal').classList.add('show')" class="btn btn-success" style="padding:0.5rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#10B981;color:white;">
                    <i class="fas fa-user-plus"></i> Tambah User
                </button>
            </div>
        </div>

        <!-- ===== STATS ===== -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-calendar-alt stat-icon"></i>
                <div class="stat-label">Total Event</div>
                <div class="stat-number"><?php echo e($total_events); ?></div>
            </div>
            <div class="stat-card">
                <i class="fas fa-users stat-icon"></i>
                <div class="stat-label">Total Peserta</div>
                <div class="stat-number"><?php echo e($total_participants); ?></div>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-friends stat-icon"></i>
                <div class="stat-label">Total User</div>
                <div class="stat-number"><?php echo e($total_users); ?></div>
            </div>
            <div class="stat-card">
                <i class="fas fa-tags stat-icon"></i>
                <div class="stat-label">Kategori</div>
                <div class="stat-number"><?php echo e(count($categories)); ?></div>
            </div>
        </div>

        <?php if(session('success')): ?>
        <div class="notification success" style="padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;display:flex;align-items:center;gap:10px;font-weight:500;font-size:0.9rem;background:#D1FAE5;color:#065F46;border-left:4px solid #10B981;">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button onclick="this.parentElement.remove()" style="margin-left:auto;cursor:pointer;background:none;border:none;font-size:16px;opacity:0.5;">&times;</button>
        </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div class="notification error" style="padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;display:flex;align-items:center;gap:10px;font-weight:500;font-size:0.9rem;background:#FEE2E2;color:#991B1B;border-left:4px solid #EF4444;">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button onclick="this.parentElement.remove()" style="margin-left:auto;cursor:pointer;background:none;border:none;font-size:16px;opacity:0.5;">&times;</button>
        </div>
        <?php endif; ?>

        <!-- ===== EVENTS ===== -->
        <div id="events">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:10px;">
                <h2 style="font-size:1.2rem;font-weight:700;color:#1F2937;"><i class="fas fa-calendar-alt" style="color:#6C63FF;margin-right:8px;"></i> Manajemen Event</h2>
            </div>

            <?php if(count($events) > 0): ?>
            <div class="event-grid">
                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="event-card">
                    <div class="card-image">
                        <?php if($event->poster && Storage::disk('public')->exists($event->poster)): ?>
                            <img src="<?php echo e(Storage::url($event->poster)); ?>" alt="<?php echo e($event->nama_event); ?>">
                        <?php else: ?>
                            <div class="no-poster">
                                <i class="fas fa-calendar-alt"></i>
                                <span>No Poster</span>
                            </div>
                        <?php endif; ?>
                        <span class="category-tag"><?php echo e($event->kategori); ?></span>
                    </div>
                    <div class="card-header">
                        <h3><?php echo e($event->nama_event); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="event-meta">
                            <div class="meta-item"><i class="fas fa-calendar-day"></i> <?php echo e(date('d M Y', strtotime($event->tanggal))); ?></div>
                            <?php if($event->waktu_mulai): ?>
                            <div class="meta-item"><i class="fas fa-clock"></i> <?php echo e(date('H:i', strtotime($event->waktu_mulai))); ?> - <?php echo e(date('H:i', strtotime($event->waktu_selesai))); ?></div>
                            <?php endif; ?>
                            <div class="meta-item"><i class="fas fa-map-marker-alt"></i> <?php echo e($event->lokasi); ?></div>
                            <?php if($event->creator): ?>
                            <div class="meta-item"><i class="fas fa-user-tie"></i> <?php echo e($event->creator->nama); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span style="font-size:0.75rem;color:#6B7280;"><i class="fas fa-users" style="color:#6C63FF;"></i> <?php echo e($event->participants()->count()); ?> peserta</span>
                        <div style="display:flex;gap:4px;">
                            <!-- PERBAIKAN: Gunakan route name yang benar -->
                            <a href="<?php echo e(route('admin.event.edit', $event->id)); ?>" class="btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <?php if($event->participants()->count() > 0): ?>
                            <button class="btn-delete" disabled title="Tidak dapat dihapus karena ada peserta">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                            <?php else: ?>
                            <form method="POST" action="<?php echo e(route('admin.event.delete', $event->id)); ?>" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus event ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div style="text-align:center;padding:3rem 2rem;background:#FFFFFF;border-radius:16px;border:1px solid #E5E7EB;">
                <i class="fas fa-calendar-times" style="font-size:2.5rem;color:#D1D5DB;margin-bottom:0.5rem;"></i>
                <h3 style="font-size:1rem;color:#6B7280;">Belum ada event</h3>
                <p style="color:#9CA3AF;font-size:0.85rem;">Tambahkan event pertama Anda</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- ===== USERS ===== -->
        <div id="users" style="margin-top:2.5rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:10px;">
                <h2 style="font-size:1.2rem;font-weight:700;color:#1F2937;"><i class="fas fa-users" style="color:#6C63FF;margin-right:8px;"></i> Manajemen User</h2>
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
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>#<?php echo e($user->id); ?></td>
                            <td><strong><?php echo e($user->nama); ?></strong></td>
                            <td><?php echo e($user->email); ?></td>
                            <td><?php echo e($user->no_telp ?? '-'); ?></td>
                            <td>
                                <span style="display:inline-block;padding:0.1rem 0.5rem;border-radius:50px;font-size:0.65rem;font-weight:600;<?php echo e($user->role == 'admin' ? 'background:#FEF3C7;color:#92400E;' : ($user->role == 'penyelenggara' ? 'background:#D1FAE5;color:#065F46;' : 'background:#DBEAFE;color:#1E40AF;')); ?>">
                                    <?php echo e(ucfirst($user->role)); ?>

                                </span>
                            </td>
                            <td style="text-align:center;">
                                <?php if($user->role !== 'admin'): ?>
                                <form method="POST" action="<?php echo e(route('admin.user.delete', $user->id)); ?>" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php else: ?>
                                <span style="font-size:0.7rem;color:#9CA3AF;">Protected</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- ===== MODAL CREATE EVENT ===== -->
<div class="modal-overlay" id="createEventModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-calendar-plus"></i> Tambah Event</h3>
            <button class="close-modal" onclick="document.getElementById('createEventModal').classList.remove('show')">&times;</button>
        </div>
        <form method="POST" action="<?php echo e(route('admin.event.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>Nama Event <span style="color:#EF4444;">*</span></label>
                <input type="text" name="nama_event" placeholder="Masukkan nama event" required>
            </div>
            <div class="form-group">
                <label>Kategori <span style="color:#EF4444;">*</span></label>
                <select name="kategori" required>
                    <option value="">Pilih Kategori</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->nama); ?>"><?php echo e($cat->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal <span style="color:#EF4444;">*</span></label>
                <input type="date" name="tanggal" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Waktu Mulai</label>
                    <input type="time" name="waktu_mulai">
                </div>
                <div class="form-group">
                    <label>Waktu Selesai</label>
                    <input type="time" name="waktu_selesai">
                </div>
            </div>
            <div class="form-group">
                <label>Lokasi <span style="color:#EF4444;">*</span></label>
                <input type="text" name="lokasi" placeholder="Masukkan lokasi event" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" placeholder="Masukkan deskripsi event"></textarea>
            </div>
            <div class="form-group">
                <label>Poster / Foto Event</label>
                <input type="file" name="poster" accept="image/*">
                <div style="font-size:0.7rem;color:#9CA3AF;margin-top:0.2rem;">Upload poster event (opsional)</div>
            </div>
            <div class="form-group">
                <label>Dibuat Oleh (ID User)</label>
                <input type="number" name="created_by" placeholder="ID user">
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('createEventModal').classList.remove('show')" style="padding:0.5rem 1.2rem;border-radius:50px;border:1.5px solid #D1D5DB;background:transparent;color:#6B7280;font-weight:600;font-size:0.85rem;cursor:pointer;">Batal</button>
                <button type="submit" class="btn btn-primary" style="padding:0.5rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#6C63FF;color:white;"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL CREATE USER ===== -->
<div class="modal-overlay" id="createUserModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Tambah User</h3>
            <button class="close-modal" onclick="document.getElementById('createUserModal').classList.remove('show')">&times;</button>
        </div>
        <form method="POST" action="<?php echo e(route('admin.user.store')); ?>">
            <?php echo csrf_field(); ?>
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
                <button type="submit" class="btn btn-primary" style="padding:0.5rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#6C63FF;color:white;"><i class="fas fa-save"></i> Simpan</button>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\campven\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>