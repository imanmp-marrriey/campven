<?php $__env->startSection('title', $event->nama_event . ' - Campven'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .poster-hero {
        margin-bottom: 1.5rem;
        border-radius: 16px;
        overflow: hidden;
        max-height: 400px;
        background: #E5E7EB;
        position: relative;
    }
    .poster-hero img {
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: cover;
        /* object-fit: contain; */
    }
    .poster-hero .poster-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #6C63FF, #8B83FF);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        gap: 0.5rem;
    }
    .poster-hero .poster-placeholder span {
        font-size: 1rem;
        opacity: 0.8;
    }
    .event-detail {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
    .event-main {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid #E5E7EB;
    }
    .event-main .event-header h1 { font-size: 1.8rem; font-weight: 800; }
    .event-main .event-header .category-badge {
        display: inline-block;
        background: #6C63FF;
        color: white;
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.3rem;
    }
    .event-main .event-meta { display: flex; flex-direction: column; gap: 8px; margin: 1rem 0; }
    .event-main .event-meta .meta-item { display: flex; align-items: center; gap: 10px; color: #6B7280; font-size: 0.95rem; }
    .event-main .event-meta .meta-item i { width: 20px; color: #6C63FF; }
    .event-main .event-description { margin: 1.5rem 0; line-height: 1.8; color: #6B7280; }
    .event-main .event-description h3 { color: #1F2937; margin-bottom: 0.3rem; }

    .event-sidebar { display: flex; flex-direction: column; gap: 1rem; }
    .sidebar-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #E5E7EB;
    }
    .sidebar-card h4 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sidebar-card h4 i { color: #6C63FF; }

    .stat-list { display: flex; flex-direction: column; gap: 0; }
    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    .stat-item:last-child { border-bottom: none; }
    .stat-item .stat-label { color: #6B7280; font-size: 0.85rem; font-weight: 500; }
    .stat-item .stat-value { color: #1F2937; font-size: 0.85rem; font-weight: 600; text-align: right; max-width: 55%; word-break: break-word; }
    .stat-item .stat-value .highlight { color: #6C63FF; }

    .registration-form { margin-top: 1rem; }
    .registration-form .form-group { margin-bottom: 0.8rem; }
    .registration-form .form-group label { display: block; font-weight: 600; font-size: 0.8rem; color: #374151; margin-bottom: 0.2rem; }
    .registration-form .form-group input, .registration-form .form-group textarea {
        width: 100%;
        padding: 0.6rem 0.8rem;
        border: 1.5px solid #E5E7EB;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .registration-form .form-group input:focus, .registration-form .form-group textarea:focus {
        outline: none;
        border-color: #6C63FF;
        box-shadow: 0 0 0 3px rgba(108,99,255,0.1);
    }
    .registration-form .form-group textarea { min-height: 60px; resize: vertical; }

    .participant-list .participant-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.4rem 0;
        border-bottom: 1px solid #F3F4F6;
        font-size: 0.85rem;
    }
    .participant-list .participant-item .avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #6C63FF;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
    }

    .related-events { margin-top: 2rem; }
    .related-events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 0.5rem;
    }
    .related-event-card {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        border: 1px solid #E5E7EB;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s;
    }
    .related-event-card:hover { border-color: #6C63FF; transform: translateY(-2px); }
    .related-event-card h5 { font-size: 0.9rem; font-weight: 600; }
    .related-event-card p { font-size: 0.8rem; color: #6B7280; }

    .joined-badge {
        display: inline-block;
        background: #10B981;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .event-detail { grid-template-columns: 1fr; }
        .container { padding: 1rem; }
        .navbar { padding: 0 1rem; height: 56px; }
        .event-main { padding: 1.2rem; }
        .stat-item .stat-value { max-width: 45%; font-size: 0.8rem; }
        .poster-hero { max-height: 200px; }
        .poster-hero img { max-height: 200px; }
    }
    @media (max-width: 480px) {
        .stat-item { flex-direction: row; flex-wrap: wrap; gap: 2px; }
        .stat-item .stat-value { max-width: 50%; }
    }
</style>

<!-- ===== NAVBAR ===== -->
<nav style="background:rgba(255,255,255,0.92);backdrop-filter:blur(12px);padding:0 2rem;height:64px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #E5E7EB;position:sticky;top:0;z-index:100;">
    <a href="<?php echo e(route('home')); ?>" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
        <div style="width:36px;height:36px;background:linear-gradient(135deg,#6C63FF,#8B83FF);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;font-weight:800;">C</div>
        <div><span style="font-size:20px;font-weight:800;color:#1F2937;">Camp<span style="color:#6C63FF;">ven</span></span></div>
    </a>
    <div style="display:flex;align-items:center;gap:12px;">
        <?php if(Auth::check()): ?>
            <a href="<?php echo e(route('profile')); ?>" style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6C63FF,#8B83FF);color:white;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:14px;text-decoration:none;transition:all 0.2s;">
                <?php echo e(strtoupper(substr(Auth::user()->nama, 0, 1))); ?>

            </a>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline btn-sm" style="padding:0.3rem 0.8rem;border-radius:50px;border:1.5px solid #D1D5DB;background:transparent;color:#6B7280;font-weight:600;font-size:0.75rem;cursor:pointer;font-family:'Inter',sans-serif;">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="btn btn-outline btn-sm">Masuk</a>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-sm">Daftar</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container" style="padding:1.5rem 2rem;max-width:1200px;margin:0 auto;">
    <a href="<?php echo e(route('home')); ?>" style="display:inline-flex;align-items:center;gap:6px;color:#6B7280;text-decoration:none;font-size:0.9rem;margin-bottom:1rem;">
        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
    </a>

    <!-- ===== POSTER HERO ===== -->
    <div class="poster-hero">
        <?php if($event->poster && file_exists(public_path('storage/' . $event->poster))): ?>
            <img src="<?php echo e(asset('storage/' . $event->poster)); ?>" alt="<?php echo e($event->nama_event); ?>">
        <?php else: ?>
            <div class="poster-placeholder">
                <i class="fas fa-calendar-alt"></i>
                <span><?php echo e($event->kategori); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <div class="event-detail">
        <!-- ===== MAIN ===== -->
        <div class="event-main">
            <div class="event-header">
                <span class="category-badge"><?php echo e($event->kategori); ?></span>
                <h1><?php echo e($event->nama_event); ?></h1>
            </div>

            <div class="event-meta">
                <div class="meta-item"><i class="fas fa-calendar-day"></i> <?php echo e(date('l, d M Y', strtotime($event->tanggal))); ?></div>
                <?php if($event->waktu_mulai): ?>
                <div class="meta-item"><i class="fas fa-clock"></i> <?php echo e(date('H:i', strtotime($event->waktu_mulai))); ?> - <?php echo e(date('H:i', strtotime($event->waktu_selesai))); ?> WIB</div>
                <?php endif; ?>
                <div class="meta-item"><i class="fas fa-map-marker-alt"></i> <?php echo e($event->lokasi); ?></div>
                <div class="meta-item"><i class="fas fa-user-tie"></i> Penyelenggara: <?php echo e($event->creator->nama ?? 'Tidak diketahui'); ?></div>
                <div class="meta-item"><i class="fas fa-users"></i> <?php echo e($event->participantCount()); ?> peserta terdaftar</div>
            </div>

            <div class="event-description">
                <h3>Tentang Event</h3>
                <p><?php echo e(nl2br($event->deskripsi)); ?></p>
            </div>

            <?php if(Auth::check() && $is_joined): ?>
            <div style="margin-top:1rem;padding:1rem;background:#D1FAE5;border-radius:10px;text-align:center;">
                <i class="fas fa-check-circle" style="color:#10B981;font-size:1.2rem;"></i>
                <span style="font-weight:600;color:#065F46;">Anda sudah terdaftar di event ini!</span>
            </div>
            <?php endif; ?>
        </div>

        <!-- ===== SIDEBAR ===== -->
        <div class="event-sidebar">

            <!-- Registration Card -->
            <div class="sidebar-card">
                <h4><i class="fas fa-user-plus"></i> Daftar Sekarang</h4>

                <?php if(session('success')): ?>
                <div style="padding:0.8rem 1rem;border-radius:10px;margin-bottom:1rem;display:flex;align-items:center;gap:10px;font-weight:500;font-size:0.9rem;background:#D1FAE5;color:#065F46;border-left:4px solid #10B981;">
                    <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

                </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                <div style="padding:0.8rem 1rem;border-radius:10px;margin-bottom:1rem;display:flex;align-items:center;gap:10px;font-weight:500;font-size:0.9rem;background:#FEE2E2;color:#991B1B;border-left:4px solid #EF4444;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

                </div>
                <?php endif; ?>

                <?php if(Auth::check() && !$is_joined): ?>
                <form method="POST" action="<?php echo e(route('event.register', $event->id)); ?>" class="registration-form">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label>Nama Lengkap <span style="color:#EF4444;">*</span></label>
                        <input type="text" value="<?php echo e(Auth::user()->nama); ?>" readonly style="background:#F9FAFB;color:#6B7280;cursor:not-allowed;">
                    </div>
                    <div class="form-group">
                        <label>Email <span style="color:#EF4444;">*</span></label>
                        <input type="email" value="<?php echo e(Auth::user()->email); ?>" readonly style="background:#F9FAFB;color:#6B7280;cursor:not-allowed;">
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="no_telp" value="<?php echo e(Auth::user()->no_telp ?? ''); ?>" placeholder="08123456789">
                    </div>
                    <div class="form-group">
                        <label>Alasan Mengikuti</label>
                        <textarea name="alasan" placeholder="Ceritakan alasan Anda mengikuti event ini..."></textarea>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.6rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#6C63FF;color:white;">
                        <i class="fas fa-check"></i> Daftar Event
                    </button>
                </form>
                <?php elseif(Auth::check() && $is_joined): ?>
                <div style="text-align:center;padding:0.5rem 0;">
                    <span class="joined-badge"><i class="fas fa-check-circle"></i> Terdaftar</span>
                    <p style="font-size:0.8rem;color:#6B7280;margin-top:0.5rem;">Anda sudah terdaftar di event ini</p>
                </div>
                <?php else: ?>
                <div style="text-align:center;padding:0.5rem 0;">
                    <p style="color:#6B7280;margin-bottom:1rem;">Silakan login terlebih dahulu untuk mendaftar</p>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.6rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#6C63FF;color:white;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:0.5rem;padding:0.6rem 1.2rem;border-radius:50px;border:1.5px solid #D1D5DB;background:transparent;color:#6B7280;font-weight:600;font-size:0.85rem;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fas fa-user-plus"></i> Daftar Akun
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- ===== STATISTIK CARD ===== -->
            <div class="sidebar-card">
                <h4><i class="fas fa-chart-simple"></i> Statistik Event</h4>
                <div class="stat-list">
                    <div class="stat-item">
                        <span class="stat-label">Total Peserta</span>
                        <span class="stat-value"><span class="highlight"><?php echo e($event->participantCount()); ?></span> orang</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Kategori</span>
                        <span class="stat-value"><?php echo e($event->kategori); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Tanggal</span>
                        <span class="stat-value"><?php echo e(date('d M Y', strtotime($event->tanggal))); ?></span>
                    </div>
                    <?php if($event->waktu_mulai): ?>
                    <div class="stat-item">
                        <span class="stat-label">Waktu</span>
                        <span class="stat-value"><?php echo e(date('H:i', strtotime($event->waktu_mulai))); ?> - <?php echo e(date('H:i', strtotime($event->waktu_selesai))); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="stat-item">
                        <span class="stat-label">Lokasi</span>
                        <span class="stat-value"><?php echo e($event->lokasi); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Penyelenggara</span>
                        <span class="stat-value"><?php echo e($event->creator->nama ?? 'Tidak diketahui'); ?></span>
                    </div>
                    <div class="stat-item" style="border-bottom: none; padding-bottom: 0;">
                        <span class="stat-label">Status Event</span>
                        <span class="stat-value">
                            <?php if(strtotime($event->tanggal) > time()): ?>
                                <span style="color:#10B981;">Akan Datang</span>
                            <?php elseif(strtotime($event->tanggal) == date('Y-m-d')): ?>
                                <span style="color:#F59E0B;">Hari Ini</span>
                            <?php else: ?>
                                <span style="color:#6B7280;">Selesai</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- ===== PARTICIPANTS CARD ===== -->
            <?php if(count($participants) > 0): ?>
            <div class="sidebar-card">
                <h4><i class="fas fa-users"></i> Peserta Terbaru</h4>
                <div class="participant-list">
                    <?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="participant-item">
                        <div class="avatar"><?php echo e(strtoupper(substr($p->user->nama, 0, 1))); ?></div>
                        <div>
                            <div style="font-weight:600;font-size:0.85rem;"><?php echo e($p->user->nama); ?></div>
                            <div style="font-size:0.7rem;color:#9CA3AF;"><?php echo e($p->user->no_telp ?? ''); ?></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ===== RELATED EVENTS ===== -->
    <?php if(count($related_events) > 0): ?>
    <div class="related-events">
        <h4 style="font-size:1.1rem;font-weight:700;"><i class="fas fa-link" style="color:#6C63FF;"></i> Event Serupa</h4>
        <div class="related-events-grid">
            <?php $__currentLoopData = $related_events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $re): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('event.detail', $re->id)); ?>" class="related-event-card">
                <h5><?php echo e($re->nama_event); ?></h5>
                <p><i class="fas fa-calendar-day"></i> <?php echo e(date('d M Y', strtotime($re->tanggal))); ?></p>
                <p style="font-size:0.7rem;color:#9CA3AF;"><i class="fas fa-map-marker-alt"></i> <?php echo e($re->lokasi); ?></p>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\campven\resources\views/events/detail.blade.php ENDPATH**/ ?>