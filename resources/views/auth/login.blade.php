@extends('layouts.app')

@section('title', 'Masuk - Campven')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:#F0F2F5;padding:1.5rem;">
    <div style="display:flex;max-width:1000px;width:100%;background:#FFFFFF;border-radius:24px;overflow:hidden;box-shadow:0 25px 80px rgba(0,0,0,0.08);">
        <div style="flex:1;padding:3rem 2.5rem;background:#FFFFFF;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:1.5rem;">
                <div style="width:40px;height:40px;background:linear-gradient(135deg,#334155,#475569);border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;font-size:20px;font-weight:800;">C</div>
                <div><h1 style="font-size:24px;font-weight:800;color:#1A1A2E;">Camp<span style="color:#334155;">ven</span></h1><p style="font-size:13px;color:#8A8A9E;margin-top:-2px;">Your Campus, Organized.</p></div>
            </div>

            <h3 style="font-size:1.4rem;font-weight:700;color:#1A1A2E;margin-bottom:0.2rem;">Selamat Datang Kembali</h3>
            <p style="color:#8A8A9E;font-size:0.9rem;margin-bottom:1.5rem;">Masuk untuk melanjutkan ke dashboard Anda</p>

            @if(session('success'))
            <div style="background:#D1FAE5;color:#065F46;padding:0.7rem 1rem;border-radius:12px;margin-bottom:1rem;font-size:0.85rem;border-left:4px solid #10B981;">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div style="background:#FEE2E2;color:#DC2626;padding:0.7rem 1rem;border-radius:12px;margin-bottom:1rem;font-size:0.85rem;border-left:4px solid #DC2626;">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-weight:600;font-size:0.85rem;color:#1A1A2E;margin-bottom:0.2rem;">Alamat Email</label>
                    <div style="position:relative;">
                        <i class="fas fa-envelope" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#8A8A9E;font-size:15px;"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required style="width:100%;padding:0.75rem 1rem 0.75rem 2.8rem;border:2px solid #EAEAEF;border-radius:12px;font-size:0.95rem;font-family:'Inter',sans-serif;background:#F8F9FA;transition:all 0.3s;">
                    </div>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-weight:600;font-size:0.85rem;color:#1A1A2E;margin-bottom:0.2rem;">Password</label>
                    <div style="position:relative;">
                        <i class="fas fa-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#8A8A9E;font-size:15px;"></i>
                        <input type="password" name="password" placeholder="Masukkan password" required style="width:100%;padding:0.75rem 1rem 0.75rem 2.8rem;border:2px solid #EAEAEF;border-radius:12px;font-size:0.95rem;font-family:'Inter',sans-serif;background:#F8F9FA;transition:all 0.3s;">
                    </div>
                </div>
                <button type="submit" style="width:100%;padding:0.85rem;background:#334155;color:white;border:none;border-radius:12px;font-size:1rem;font-weight:600;cursor:pointer;transition:all 0.3s;font-family:'Inter',sans-serif;margin-top:0.3rem;">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>

            <div style="text-align:center;margin-top:1.2rem;padding-top:1rem;border-top:1px solid #EAEAEF;font-size:0.9rem;color:#8A8A9E;">
                <span>Belum punya akun?</span><br>
                <a href="{{ route('register') }}" style="display:inline-block;margin-top:0.5rem;padding:0.6rem 1.8rem;background:linear-gradient(135deg,#334155,#5A52D5);color:white;border-radius:50px;text-decoration:none;font-weight:600;font-size:0.9rem;transition:all 0.3s;">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </a>
            </div>
        </div>
        <div style="flex:1;background:linear-gradient(145deg,#334155,#1E293B);padding:3rem 2.5rem;display:flex;flex-direction:column;justify-content:center;color:white;position:relative;overflow:hidden;">
            <div style="position:absolute;top:-40%;right:-30%;width:400px;height:400px;background:rgba(255,255,255,0.05);border-radius:50%;"></div>
            <div style="position:absolute;bottom:-30%;left:-20%;width:300px;height:300px;background:rgba(255,255,255,0.04);border-radius:50%;"></div>
            <div style="position:relative;z-index:1;">
                <h2 style="font-size:2rem;font-weight:800;margin-bottom:0.5rem;">Kampus <span style="color:#FFE66D;">Terorganisir</span></h2>
                <p style="opacity:0.85;font-size:0.95rem;line-height:1.7;max-width:360px;">Temukan, ikuti, dan kelola event kampus dalam satu platform yang terintegrasi.</p>
                <div style="margin-top:1.5rem;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:0.6rem;font-size:0.9rem;opacity:0.9;"><i class="fas fa-calendar-check" style="width:28px;height:28px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;"></i> Temukan event terbaru</div>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:0.6rem;font-size:0.9rem;opacity:0.9;"><i class="fas fa-users" style="width:28px;height:28px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;"></i> Ikuti event favorit</div>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:0.6rem;font-size:0.9rem;opacity:0.9;"><i class="fas fa-bell" style="width:28px;height:28px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;"></i> Notifikasi real-time</div>
                    <div style="display:flex;align-items:center;gap:12px;font-size:0.9rem;opacity:0.9;"><i class="fas fa-chart-line" style="width:28px;height:28px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;"></i> Pantau perkembangan</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection