<?php $__env->startSection('title', 'Manajemen Kategori - Campven'); ?>

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

    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.2rem;
    }
    .category-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #E5E7EB;
        text-align: center;
        transition: all 0.2s;
        position: relative;
    }
    .category-card:hover { border-color: #6C63FF; box-shadow: 0 4px 20px rgba(0,0,0,0.06); transform: translateY(-3px); }
    .category-card .category-icon { font-size: 2.5rem; margin-bottom: 0.5rem; display: block; }
    .category-card .category-name { font-weight: 700; font-size: 1rem; margin-bottom: 0.2rem; }
    .category-card .category-desc { font-size: 0.8rem; color: #6B7280; margin-bottom: 0.8rem; }
    .category-card .category-actions {
        display: flex;
        justify-content: center;
        gap: 6px;
        border-top: 1px solid #F3F4F6;
        padding-top: 0.8rem;
    }

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
        max-width: 500px;
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
    .form-actions { display: flex; gap: 10px; margin-top: 1.2rem; justify-content: flex-end; }
    input[type="color"] { width: 50px; height: 40px; padding: 2px; border-radius: 10px; border: 1.5px solid #E5E7EB; cursor: pointer; }

    .mobile-toggle { display: none; font-size: 1.5rem; cursor: pointer; color: #6B7280; background: none; border: none; }

    @media (max-width: 1024px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.open { transform: translateX(0); }
        .main { margin-left: 0; padding: 1rem; }
        .mobile-toggle { display: block; }
    }
    @media (max-width: 768px) {
        .category-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .category-grid { grid-template-columns: 1fr; }
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
            <li><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fas fa-calendar-alt"></i> Event</a></li>
            <li><a href="<?php echo e(route('admin.dashboard')); ?>#users"><i class="fas fa-users"></i> User</a></li>
            <li><a href="<?php echo e(route('admin.categories')); ?>" class="active"><i class="fas fa-tags"></i> Kategori</a></li>
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
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6C63FF,#8B83FF);color:white;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:14px;">A</div>
                <div>
                    <div style="font-weight:600;font-size:0.9rem;"><?php echo e(Auth::user()->nama); ?></div>
                    <div style="font-size:0.75rem;color:#6B7280;">Administrator</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- ===== MAIN ===== -->
    <main class="main">
        <div class="topbar">
            <div>
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="fas fa-bars"></i></button>
                <div style="display:inline-block;margin-left:10px;">
                    <h1 style="font-size:1.5rem;font-weight:800;"><i class="fas fa-tags" style="color:#6C63FF;"></i> Manajemen Kategori</h1>
                    <p style="color:#6B7280;font-size:0.9rem;">Kelola kategori event yang tersedia di Campven</p>
                </div>
            </div>
            <div>
                <button onclick="document.getElementById('createCategoryModal').classList.add('show')" class="btn btn-primary" style="padding:0.5rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#6C63FF;color:white;">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </button>
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

        <?php if(count($categories) > 0): ?>
        <div class="category-grid">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="category-card">
                <span class="category-icon" style="color:<?php echo e($cat->color); ?>;">
                    <i class="fas <?php echo e($cat->icon); ?>"></i>
                </span>
                <div class="category-name"><?php echo e($cat->nama); ?></div>
                <div class="category-desc"><?php echo e($cat->description ?? ''); ?></div>
                <div class="category-actions">
                    <a href="<?php echo e(route('admin.categories')); ?>?edit=<?php echo e($cat->id); ?>" class="btn btn-warning btn-xs" style="padding:0.15rem 0.6rem;font-size:0.65rem;border-radius:50px;border:none;font-weight:600;background:#F59E0B;color:white;text-decoration:none;cursor:pointer;">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="<?php echo e(route('admin.category.delete', $cat->id)); ?>" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" style="padding:0.15rem 0.6rem;font-size:0.65rem;border-radius:50px;font-weight:600;background:#EF4444;color:white;border:none;cursor:pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:3rem 2rem;background:#FFFFFF;border-radius:16px;border:1px solid #E5E7EB;">
            <i class="fas fa-tags" style="font-size:2.5rem;color:#D1D5DB;margin-bottom:0.5rem;"></i>
            <h3 style="font-size:1rem;color:#6B7280;">Belum ada kategori</h3>
            <p style="color:#9CA3AF;font-size:0.85rem;">Tambahkan kategori event pertama Anda</p>
        </div>
        <?php endif; ?>
    </main>
</div>

<!-- ===== MODAL CREATE CATEGORY ===== -->
<div class="modal-overlay" id="createCategoryModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-tag"></i> Tambah Kategori</h3>
            <button class="close-modal" onclick="document.getElementById('createCategoryModal').classList.remove('show')">&times;</button>
        </div>
        <form method="POST" action="<?php echo e(route('admin.category.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>Nama Kategori <span style="color:#EF4444;">*</span></label>
                <input type="text" name="nama" placeholder="Contoh: Seminar" required>
            </div>
            <div class="form-group">
                <label>Icon (FontAwesome) <span style="color:#EF4444;">*</span></label>
                <input type="text" name="icon" placeholder="fa-graduation-cap" required>
                <div style="font-size:0.7rem;color:#9CA3AF;margin-top:0.2rem;">
                    <i class="fas fa-info-circle"></i>
                    Contoh: fa-graduation-cap, fa-tools, fa-trophy, fa-video, fa-music, fa-running
                </div>
            </div>
            <div class="form-group">
                <label>Warna</label>
                <input type="color" name="color" value="#6C63FF">
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" placeholder="Deskripsi singkat tentang kategori ini"></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('createCategoryModal').classList.remove('show')" style="padding:0.5rem 1.2rem;border-radius:50px;border:1.5px solid #D1D5DB;background:transparent;color:#6B7280;font-weight:600;font-size:0.85rem;cursor:pointer;">Batal</button>
                <button type="submit" name="create_category" class="btn btn-primary" style="padding:0.5rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#6C63FF;color:white;"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL EDIT CATEGORY ===== -->
<?php if(request('edit')): ?>
<?php
$editCategory = \App\Models\Category::find(request('edit'));
?>
<?php if($editCategory): ?>
<div class="modal-overlay show" id="editCategoryModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Edit Kategori</h3>
            <a href="<?php echo e(route('admin.categories')); ?>" style="text-decoration:none;font-size:22px;color:#9CA3AF;">&times;</a>
        </div>
        <form method="POST" action="<?php echo e(route('admin.category.update', $editCategory->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="hidden" name="category_id" value="<?php echo e($editCategory->id); ?>">
            <div class="form-group">
                <label>Nama Kategori <span style="color:#EF4444;">*</span></label>
                <input type="text" name="nama" value="<?php echo e($editCategory->nama); ?>" required>
            </div>
            <div class="form-group">
                <label>Icon (FontAwesome) <span style="color:#EF4444;">*</span></label>
                <input type="text" name="icon" value="<?php echo e($editCategory->icon); ?>" required>
            </div>
            <div class="form-group">
                <label>Warna</label>
                <input type="color" name="color" value="<?php echo e($editCategory->color); ?>">
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description"><?php echo e($editCategory->description ?? ''); ?></textarea>
            </div>
            <div class="form-actions">
                <a href="<?php echo e(route('admin.categories')); ?>" class="btn btn-outline" style="padding:0.5rem 1.2rem;border-radius:50px;border:1.5px solid #D1D5DB;background:transparent;color:#6B7280;font-weight:600;font-size:0.85rem;text-decoration:none;">Batal</a>
                <button type="submit" name="update_category" class="btn btn-primary" style="padding:0.5rem 1.2rem;border-radius:50px;border:none;font-weight:600;font-size:0.85rem;cursor:pointer;background:#6C63FF;color:white;"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\campven\resources\views/categories/index.blade.php ENDPATH**/ ?>