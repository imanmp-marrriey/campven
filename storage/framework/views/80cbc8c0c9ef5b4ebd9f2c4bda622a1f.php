<?php $__env->startSection('title', 'Daftar - Campven'); ?>

<?php $__env->startSection('content'); ?>
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:#F0F2F5;padding:1.5rem;">
    <div style="display:flex;max-width:1000px;width:100%;background:#FFFFFF;border-radius:24px;overflow:hidden;box-shadow:0 25px 80px rgba(0,0,0,0.08);">
        <div style="flex:1.2;padding:2.5rem 2.5rem;background:#FFFFFF;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:1.2rem;">
                <div style="width:40px;height:40px;background:linear-gradient(135deg,#6C63FF,#8B83FF);border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;font-size:20px;font-weight:800;">C</div>
                <div><h1 style="font-size:24px;font-weight:800;color:#1A1A2E;">Camp<span style="color:#6C63FF;">ven</span></h1></div>
            </div>

            <h3 style="font-size:1.3rem;font-weight:700;color:#1A1A2E;">Buat Akun Baru</h3>
            <p style="color:#8A8A9E;font-size:0.9rem;margin-bottom:1.2rem;">Daftar gratis dan mulai temukan event menarik</p>

            <?php if($errors->any()): ?>
            <div style="background:#FEE2E2;color:#DC2626;padding:0.6rem 1rem;border-radius:12px;margin-bottom:0.8rem;font-size:0.85rem;border-left:4px solid #DC2626;">
                <?php echo e($errors->first()); ?>

            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('register')); ?>">
                <?php echo csrf_field(); ?>
                <div style="margin-bottom:0.8rem;">
                    <label style="display:block;font-weight:600;font-size:0.8rem;color:#1A1A2E;margin-bottom:0.2rem;">Nama Lengkap <span style="color:#EF4444;">*</span></label>
                    <div style="position:relative;">
                        <i class="fas fa-user" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#8A8A9E;font-size:14px;"></i>
                        <input type="text" name="nama" placeholder="Masukkan nama lengkap" required style="width:100%;padding:0.6rem 1rem 0.6rem 2.4rem;border:2px solid #EAEAEF;border-radius:12px;font-size:0.9rem;font-family:'Inter',sans-serif;background:#F8F9FA;transition:all 0.3s;">
                    </div>
                </div>
                <div style="margin-bottom:0.8rem;">
                    <label style="display:block;font-weight:600;font-size:0.8rem;color:#1A1A2E;margin-bottom:0.2rem;">Email <span style="color:#EF4444;">*</span></label>
                    <div style="position:relative;">
                        <i class="fas fa-envelope" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#8A8A9E;font-size:14px;"></i>
                        <input type="email" name="email" placeholder="email@example.com" required style="width:100%;padding:0.6rem 1rem 0.6rem 2.4rem;border:2px solid #EAEAEF;border-radius:12px;font-size:0.9rem;font-family:'Inter',sans-serif;background:#F8F9FA;transition:all 0.3s;">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div style="margin-bottom:0.8rem;">
                        <label style="display:block;font-weight:600;font-size:0.8rem;color:#1A1A2E;margin-bottom:0.2rem;">Password <span style="color:#EF4444;">*</span></label>
                        <div style="position:relative;">
                            <i class="fas fa-lock" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#8A8A9E;font-size:14px;"></i>
                            <input type="password" name="password" placeholder="Min. 6 karakter" required style="width:100%;padding:0.6rem 1rem 0.6rem 2.4rem;border:2px solid #EAEAEF;border-radius:12px;font-size:0.9rem;font-family:'Inter',sans-serif;background:#F8F9FA;transition:all 0.3s;">
                        </div>
                    </div>
                    <div style="margin-bottom:0.8rem;">
                        <label style="display:block;font-weight:600;font-size:0.8rem;color:#1A1A2E;margin-bottom:0.2rem;">Konfirmasi Password <span style="color:#EF4444;">*</span></label>
                        <div style="position:relative;">
                            <i class="fas fa-check-circle" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#8A8A9E;font-size:14px;"></i>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password" required style="width:100%;padding:0.6rem 1rem 0.6rem 2.4rem;border:2px solid #EAEAEF;border-radius:12px;font-size:0.9rem;font-family:'Inter',sans-serif;background:#F8F9FA;transition:all 0.3s;">
                        </div>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div style="margin-bottom:0.8rem;">
                        <label style="display:block;font-weight:600;font-size:0.8rem;color:#1A1A2E;margin-bottom:0.2rem;">No. Telepon</label>
                        <div style="position:relative;">
                            <i class="fas fa-phone" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#8A8A9E;font-size:14px;"></i>
                            <input type="text" name="no_telp" placeholder="08123456789" style="width:100%;padding:0.6rem 1rem 0.6rem 2.4rem;border:2px solid #EAEAEF;border-radius:12px;font-size:0.9rem;font-family:'Inter',sans-serif;background:#F8F9FA;transition:all 0.3s;">
                        </div>
                    </div>
                    <div style="margin-bottom:0.8rem;">
                        <label style="display:block;font-weight:600;font-size:0.8rem;color:#1A1A2E;margin-bottom:0.2rem;">Daftar Sebagai <span style="color:#EF4444;">*</span></label>
                        <div style="position:relative;">
                            <i class="fas fa-user-tag" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#8A8A9E;font-size:14px;"></i>
                            <select name="role" required style="width:100%;padding:0.6rem 1rem 0.6rem 2.4rem;border:2px solid #EAEAEF;border-radius:12px;font-size:0.9rem;font-family:'Inter',sans-serif;background:#F8F9FA;appearance:none;">
                                <option value="mahasiswa">Mahasiswa / Peserta</option>
                                <option value="penyelenggara">Penyelenggara Event</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" style="width:100%;padding:0.8rem;background:#6C63FF;color:white;border:none;border-radius:12px;font-size:1rem;font-weight:600;cursor:pointer;transition:all 0.3s;font-family:'Inter',sans-serif;margin-top:0.3rem;">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </form>

            <div style="text-align:center;margin-top:0.8rem;font-size:0.85rem;color:#8A8A9E;">
                Sudah punya akun? <a href="<?php echo e(route('login')); ?>" style="color:#6C63FF;text-decoration:none;font-weight:600;">Masuk di sini</a>
            </div>
        </div>
        <div style="flex:0.8;background:linear-gradient(145deg,#6C63FF,#4A42B8);padding:3rem 2.5rem;display:flex;flex-direction:column;justify-content:center;color:white;position:relative;overflow:hidden;">
            <div style="position:absolute;top:-40%;right:-30%;width:400px;height:400px;background:rgba(255,255,255,0.05);border-radius:50%;"></div>
            <div style="position:absolute;bottom:-30%;left:-20%;width:300px;height:300px;background:rgba(255,255,255,0.04);border-radius:50%;"></div>
            <div style="position:relative;z-index:1;">
                <h2 style="font-size:2rem;font-weight:800;margin-bottom:0.5rem;">Bergabung dengan <span style="color:#FFE66D;">Campven</span></h2>
                <p style="opacity:0.85;font-size:0.95rem;line-height:1.7;">Ribuan pengguna sudah menggunakan Campven untuk menemukan dan mengelola event terbaik.</p>
                <div style="margin-top:1.5rem;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:0.6rem;font-size:0.9rem;opacity:0.9;"><i class="fas fa-calendar-check" style="width:28px;height:28px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;"></i> Temukan event menarik</div>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:0.6rem;font-size:0.9rem;opacity:0.9;"><i class="fas fa-users" style="width:28px;height:28px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;"></i> Ikuti event favorit</div>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:0.6rem;font-size:0.9rem;opacity:0.9;"><i class="fas fa-bell" style="width:28px;height:28px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;"></i> Notifikasi real-time</div>
                    <div style="display:flex;align-items:center;gap:12px;font-size:0.9rem;opacity:0.9;"><i class="fas fa-certificate" style="width:28px;height:28px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;"></i> Dapatkan sertifikat</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\campven\resources\views/auth/register.blade.php ENDPATH**/ ?>