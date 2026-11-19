@extends('layouts.app')

@section('title', 'Edit Event - Admin')

@section('content')
<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .card {
        background: #FFFFFF;
        border-radius: 16px;
        border: 1px solid #E5E7EB;
        overflow: hidden;
    }
    .card-header {
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid #E5E7EB;
        background: #F9FAFB;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-header h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
    }
    .card-header h3 i {
        color: #334155;
        margin-right: 8px;
    }
    .card-body {
        padding: 1.5rem;
    }
    .form-group {
        margin-bottom: 1.2rem;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 0.85rem;
        color: #374151;
        margin-bottom: 0.3rem;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.6rem 0.8rem;
        border: 1.5px solid #E5E7EB;
        border-radius: 10px;
        font-size: 0.9rem;
        transition: all 0.2s;
        background: #FFFFFF;
        color: #1F2937;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #334155;
        box-shadow: 0 0 0 3px rgba(51, 65, 85, 0.1);
    }
    .form-group textarea {
        min-height: 80px;
        resize: vertical;
    }
    .form-group input[type="file"] {
        padding: 0.4rem;
        border: 1px dashed #D1D5DB;
        background: #F9FAFB;
        border-radius: 10px;
        cursor: pointer;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 1.5rem;
        justify-content: flex-end;
    }
    .btn-primary {
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        border: none;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        background: #334155;
        color: white;
        text-decoration: none;
    }
    .btn-primary:hover {
        background: #5A52D5;
    }
    .btn-secondary {
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        border: 1.5px solid #D1D5DB;
        background: transparent;
        color: #6B7280;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-secondary:hover {
        background: #F3F4F6;
    }
    .current-poster {
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        display: inline-block;
    }
    .current-poster img {
        max-width: 150px;
        max-height: 100px;
        border-radius: 8px;
    }
    .text-danger {
        color: #EF4444;
    }
    .text-muted {
        color: #9CA3AF;
        font-size: 0.75rem;
        margin-top: 0.2rem;
    }
    .alert-danger {
        padding: 0.8rem 1.2rem;
        border-radius: 10px;
        margin-bottom: 1.2rem;
        background: #FEE2E2;
        color: #991B1B;
        border-left: 4px solid #EF4444;
    }
    .alert-danger ul {
        margin: 0;
        padding-left: 1.2rem;
    }
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .form-actions {
            flex-direction: column;
        }
        .form-actions a,
        .form-actions button {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-edit"></i> Edit Event</h3>
            <a href="{{ route('admin.dashboard') }}" class="btn-secondary" style="padding:0.3rem 1rem;font-size:0.8rem;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- PERBAIKAN: Gunakan route admin.event.update (sesuai web.php) -->
            <form action="{{ route('admin.event.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Event <span class="text-danger">*</span></label>
                        <input type="text" name="nama_event" value="{{ old('nama_event', $event->nama_event) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->nama }}" {{ old('kategori', $event->kategori) == $cat->nama ? 'selected' : '' }}>
                                    {{ $cat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $event->tanggal ? \Carbon\Carbon::parse($event->tanggal)->format('Y-m-d') : '') }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', $event->waktu_mulai ? \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') : '') }}">
                    </div>
                    <div class="form-group">
                        <label>Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', $event->waktu_selesai ? \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i') : '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi">{{ old('deskripsi', $event->deskripsi) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Kuota Peserta</label>
                    <input type="number" name="kuota" value="{{ old('kuota', $event->kuota) }}" placeholder="Kosongkan untuk tidak terbatas" min="1">
                    <div class="text-muted">Kosongkan jika tidak ada batas peserta</div>
                </div>

                @if($event->poster && Storage::disk('public')->exists($event->poster))
                    <div class="form-group">
                        <label>Poster Saat Ini</label>
                        <div class="current-poster">
                            <img src="{{ Storage::url($event->poster) }}" alt="Poster">
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label>Upload Poster Baru</label>
                    <input type="file" name="poster" accept="image/*">
                    <div class="text-muted">Kosongkan jika tidak ingin mengganti poster. Format: JPG, PNG, GIF (Max: 2MB)</div>
                </div>

                @if(Auth::user()->role === 'admin')
                    <div class="form-group">
                        <label>Dibuat Oleh (ID User)</label>
                        <select name="created_by" class="form-control">
                            <option value="">Pilih Penyelenggara</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('created_by', $event->created_by) == $user->id ? 'selected' : '' }}>
                                    {{ $user->nama }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-actions">
                    <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
