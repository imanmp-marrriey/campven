@extends('layouts.app')

@section('title', 'E-Sertifikat - ' . $event->nama_event)

@push('styles')
<style>
    @media print {
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        .no-print {
            display: none !important;
        }
        body {
            background: #ffffff !important;
        }
        .certificate-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }
        .certificate-sheet {
            box-shadow: none !important;
            border: 4px solid #312e81 !important;
            page-break-inside: avoid;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-100 py-8 px-4 sm:px-6 lg:px-8 certificate-wrapper">
    <!-- Toolbar Action (Hidden when printing) -->
    <div class="no-print max-w-5xl mx-auto mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <a href="{{ route('my.events') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition shadow-sm">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Tiket Saya</span>
        </a>

        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition btn-spring">
            <i class="fas fa-print"></i>
            <span>Cetak / Simpan PDF</span>
        </button>
    </div>

    <!-- Certificate Paper -->
    <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-2xl border-8 border-double border-indigo-900/90 p-8 sm:p-14 relative overflow-hidden certificate-sheet">
        <!-- Decorative Corner Accents -->
        <div class="absolute top-0 left-0 w-32 h-32 bg-indigo-50 rounded-br-full pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-32 h-32 bg-indigo-50 rounded-tl-full pointer-events-none"></div>

        <div class="relative z-10 text-center space-y-6">
            <!-- Header Brand -->
            <div class="flex flex-col items-center justify-center gap-2">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl shadow-md">
                    <i class="fas fa-award"></i>
                </div>
                <span class="text-xs font-extrabold uppercase tracking-[0.3em] text-indigo-700">Sertifikat Partisipasi Kampus</span>
                <p class="text-[11px] font-mono text-slate-500">Nomor: {{ $cert_number }}</p>
            </div>

            <div class="w-24 h-1 bg-indigo-600 mx-auto rounded-full"></div>

            <!-- Recipient -->
            <div class="space-y-2 py-2">
                <p class="text-xs uppercase tracking-widest text-slate-500 font-bold">Diberikan Kepada</p>
                <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">{{ $user->nama }}</h1>
                <p class="text-xs text-slate-500">{{ $user->email }}</p>
            </div>

            <!-- Achievement Text -->
            <div class="max-w-2xl mx-auto space-y-2">
                <p class="text-sm text-slate-600 leading-relaxed">
                    Telah berpartisipasi aktif dan tercatat hadir sebagai <strong>Peserta</strong> dalam kegiatan:
                </p>
                <h2 class="text-xl sm:text-2xl font-extrabold text-indigo-800">{{ $event->nama_event }}</h2>
                <p class="text-xs text-slate-600">
                    Kategori <strong>{{ $event->kategori }}</strong> • Diselenggarakan pada
                    <strong>{{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong>
                    bertempat di <strong>{{ $event->lokasi }}</strong>.
                </p>
            </div>

            @if(($event->skpi_poin ?? 0) > 0)
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-extrabold">
                <i class="fas fa-star text-amber-500"></i>
                <span>Nilai Kredit Kegiatan: +{{ $event->skpi_poin }} Poin SKPI</span>
            </div>
            @endif

            <!-- Footer Signature & Verification QR -->
            <div class="pt-8 mt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-6 text-left">
                <div class="flex items-center gap-3.5">
                    <div class="p-1.5 bg-white rounded-xl border border-slate-200 shadow-sm">
                        {!! \App\Support\QrCodeSvg::generate($cert_number, 76) !!}
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Verifikasi Resmi Campven</span>
                        <span class="block text-xs font-extrabold text-slate-800">Kode Tiket: {{ $participant->ticketCode() }}</span>
                        <span class="block text-[10px] text-slate-500">Presensi Terverifikasi: {{ $participant->updated_at ? $participant->updated_at->format('d/m/Y H:i') . ' WIB' : '-' }}</span>
                    </div>
                </div>

                <div class="text-center sm:text-right">
                    <span class="block text-xs text-slate-500 mb-8">Penyelenggara Kegiatan</span>
                    <div class="border-t border-slate-800 pt-1.5 inline-block min-w-[200px]">
                        <span class="block text-sm font-extrabold text-slate-900">{{ $event->creator->nama ?? 'Panitia Kampus' }}</span>
                        <span class="block text-[10px] text-slate-500">Organisasi Penyelenggara Resmi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

