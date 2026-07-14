<?php $__env->startSection('title', 'Profil - Campven'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .profile-wrapper {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2rem;
    }
    .profile-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #E5E7EB;
        padding: 2rem;
        text-align: center;
        position: sticky;
        top: 80px;
    }
    .profile-card .avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6C63FF, #8B83FF);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 auto 1rem;
        border: 4px solid #E5E7EB;
    }
    .profile-card .avatar-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 1rem;
        border: 4px solid #E5E7EB;
        display: block;
    }
    .profile-card h2 { font-size: 1.2rem; font-weight: 700; margin: 0.3rem 0; }
    .profile-card .role-badge {
        display: inline-block;
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .profile-card .stats {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin: 1rem 0 0;
        padding-top: 1rem;
        border-top: 1px solid #E5E7EB;
    }
    .profile-card .stats .stat { text-align: center; }
    .profile-card .stats .stat .number { font-size: 1.2rem; font-weight: 700; }
    .profile-card .stats .stat .label { font-size: 0.7rem; color: #6B7280; }

    .profile-form {
        background: white;
        border-radius: 16px;
        border: 1px solid #E5E7EB;
        padding: 2rem;
    }
    .profile-form h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 1.2rem; }
    .profile-form h3 i { color: #6C63FF; margin-right: 8px; }

    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-weight: 600; font-size: 0.85rem; color: #374151; margin-bottom: 0.2rem; }
    .form-group .input-wrapper { position: relative; }
    .form-group .input-wrapper i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9CA3AF; font-size: 14px; }
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 0.65rem 1rem 0.65rem 2.4rem;
        border: 1.5px solid #E5E7EB;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s;
        background: #FFFFFF;
        color: #1F2937;
    }
    .form-group textarea {
        padding-left: 1rem;
        min-height: 80px;
        resize: vertical;
    }
    .form-group input:focus, .form-group textarea:focus {
        outline: none;
        border-color: #6C63FF;
        box-shadow: 0 0 0 3px rgba(108,99,255,0.1);
    }
    .form-group input:disabled {
        background: #F9FAFB;
        color: #6B7280;
        cursor: not-allowed;
    }
    .form-group .hint { font-size: 0.7rem; color: #9CA3AF; margin-top: 0.2rem; }

    .form-actions { display: flex; gap: 10px; margin-top: 1.2rem; }

    @media (max-width: 768px) {
        .navbar { padding: 0 1rem; height: 56px; }
        .container { padding: 1rem; }
        .profile-wrapper { grid-template-columns: 1fr; }
        .profile-card { position: static; }
        .form-actions { flex-direction: column; }
        .form-actions .btn { justify-content: center; }
    }
</style>

<!-- ===== NAVBAR ===== -->
<nav style="background:rgba(255,255,255,0.92);backdrop-filter:blur(12px);padding:0 2rem;height:64px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #E5E7EB;position:sticky;top:0;z-index:100;">
    <a href="<?php echo e($back_url ?? route('home')); ?>" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
        <div style="width:36px;height:36px;background:linear-gradient(135deg,#6C63FF,#8B83FF);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;font-weight:800;">C</div>
        <div><span style="font-size:20px;font-weight:800;color:#1F2937;">Camp<span style="color:#6C63FF;">ven</span></span></div>
    </a>
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="<?php echo e($back_url ?? route('home')); ?>" class="btn btn-outline btn-sm" style="padding:0.3rem 0.8rem;border-radius:50px;border:1.5px solid #D1D5DB;background:transparent;color:#6B7280;font-weight:600;font-size:0.75rem;text-decoration:none;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline;">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-danger btn-sm" style="padding:0.3rem 0.8rem;border-radius:50px;border:none;font-weight:600;font-size:0.75rem;cursor:pointer;background:#EF4444;color:white;font-family:'Inter',sans-serif;">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</nav>

<div class="container" style="padding:1.5rem 2rem;max-width:900px;margin:0 auto;">
    <div class="profile-wrapper">

        <!-- ===== PROFILE CARD ===== -->
        <div class="profile-card">
            <?php if($user->foto && Storage::disk('public')->exists($user->foto)): ?>
                <img src="<?php echo e(Storage::url($user->foto)); ?>" alt="Foto Profil" class="avatar-img">
            <?php else: ?>
                <div class="avatar-placeholder">
                    <?php echo e(strtoupper(substr($user->nama, 0, 1))); ?>

                </div>
            <?php endif; ?>

            <h2><?php echo e($user->nama); ?></h2>
            <span class="role-badge" style="
                <?php if($user->role == 'admin'): ?>
                    background:#FEF3C7;color:#92400E;
                <?php elseif($user->role == 'penyelenggara'): ?>
                    background:#D1FAE5;color:#065F46;
                <?php else: ?>
                    background:#DBEAFE;color:#1E40AF;
                <?php endif; ?>
            "><?php echo e(ucfirst($user->role)); ?></span>

            <div class="stats">
                <div class="stat">
                    <div class="number"><?php echo e($my_events_count); ?></div>
                    <div class="label">Event Dibuat</div>
                </div>
                <div class="stat">
                    <div class="number"><?php echo e($joined_events_count); ?></div>
                    <div class="label">Event Diikuti</div>
                </div>
            </div>
        </div>

        <!-- ===== PROFILE FORM ===== -->
        <div class="profile-form">
            <h3><i class="fas fa-user-edit"></i> Edit Profil</h3>

            <?php if(session('success')): ?>
            <div class="notification success" style="padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;display:flex;align-items:center;gap:10px;font-weight:500;font-size:0.9rem;background:#D1FAE5;color:#065F46;border-left:4px solid #10B981;">
                <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

                <button onclick="this.parentElement.remove()" style="margin-left:auto;cursor:pointer;background:none;border:none;font-size:16px;opacity:0.5;">&times;</button>
            </div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
            <div class="notification error" style="padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;display:flex;align-items:center;gap:10px;font-weight:500;font-size:0.9rem;background:#FEE2E2;color:#991B1B;border-left:4px solid #EF4444;">
                <i class="fas fa-exclamation-circle"></i> <?php echo e($errors->first()); ?>

                <button onclick="this.parentElement.remove()" style="margin-left:auto;cursor:pointer;background:none;border:none;font-size:16px;opacity:0.5;">&times;</button>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label>Foto Profil</label>
                    <input type="file" name="foto" accept="image/*" style="padding-left:1rem;">
                    <div class="hint">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG, GIF (Max: 2MB)</div>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="nama" value="<?php echo e(old('nama', $user->nama)); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" value="<?php echo e($user->email); ?>" disabled>
                    </div>
                    <div class="hint">Email tidak dapat diubah</div>
                </div>

                <div class="form-group">
                    <label>No. Telepon</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone"></i>
                        <input type="number" name="no_telp" value="<?php echo e(old('no_telp', $user->no_telp ?? '')); ?>" placeholder="08123456789">
                    </div>
                </div>

                <div class="form-group">
                    <label>Bio / Deskripsi Diri</label>
                    <textarea name="bio" placeholder="Ceritakan sedikit tentang diri Anda..."><?php echo e(old('bio', $user->bio ?? '')); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Password Baru <span style="color:#9CA3AF;font-weight:400;">(kosongkan jika tidak diubah)</span></label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Minimal 6 karakter">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="update_profile" class="btn btn-primary" style="padding:0.5rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#6C63FF;color:white;display:inline-flex;align-items:center;gap:6px;font-family:'Inter',sans-serif;">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="<?php echo e($back_url ?? route('home')); ?>" class="btn btn-outline" style="padding:0.5rem 1.2rem;border-radius:50px;border:1.5px solid #D1D5DB;background:transparent;color:#6B7280;font-weight:600;font-size:0.85rem;text-decoration:none;display:inline-flex;align-items:center;gap:6px;font-family:'Inter',sans-serif;">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\campven\resources\views/profile/index.blade.php ENDPATH**/ ?>