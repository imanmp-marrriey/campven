<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Sertifikat - {{ $event->nama_event }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F1F5F9;
            color: #0F172A;
            margin: 0;
        }
        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
            }
            html, body {
                width: 100% !important;
                height: auto !important;
                min-height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: visible !important;
                background: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            /* Sembunyikan semua elemen lain termasuk overlay ekstensi browser agar tidak memicu halaman 2 kosong */
            body > *:not(.certificate-wrapper),
            .no-print {
                display: none !important;
            }
            .certificate-wrapper {
                position: static !important;
                width: 100% !important;
                height: auto !important;
                min-height: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
                background: #ffffff !important;
                display: block !important;
                overflow: visible !important;
            }
            .certificate-sheet {
                position: relative !important;
                width: 100% !important;
                height: auto !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 7mm 12mm !important;
                box-sizing: border-box !important;
                box-shadow: none !important;
                border: 4px double #312e81 !important;
                border-radius: 10px !important;
                display: block !important;
                overflow: visible !important;
                page-break-inside: auto !important;
                break-inside: auto !important;
            }
            .cert-decor {
                display: none !important;
            }
            .cert-inner {
                height: auto !important;
                gap: 3.5mm !important;
            }
            .cert-recipient-name {
                font-size: 24pt !important;
                line-height: 1.15 !important;
            }
            .cert-event-title {
                font-size: 16pt !important;
                line-height: 1.25 !important;
            }
            .cert-footer {
                padding-top: 4mm !important;
            }
            .cert-signature-gap {
                margin-bottom: 7mm !important;
            }
        }
    </style>
</head>
<body class="antialiased">
<div class="py-6 px-4 sm:px-6 lg:px-8 certificate-wrapper">
    <!-- Toolbar Action (Hidden when printing) -->
    <div class="no-print max-w-5xl mx-auto mb-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <a href="{{ route('my.events') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition shadow-sm">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Tiket Saya</span>
        </a>

        <div class="flex items-center gap-2.5">
            <span class="hidden sm:inline-block text-[11px] text-slate-500">Otomatis pas 1 lembar A4 Landscape</span>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition cursor-pointer">
                <i class="fas fa-print"></i>
                <span>Cetak / Simpan PDF (1 Halaman)</span>
            </button>
        </div>
    </div>

    <!-- Certificate Paper -->
    <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-2xl border-8 border-double border-indigo-900/90 p-7 sm:p-10 relative overflow-hidden certificate-sheet">
        <!-- Decorative Corner Accents -->
        <div class="cert-decor absolute top-0 left-0 w-28 h-28 bg-indigo-50 rounded-br-full pointer-events-none"></div>
        <div class="cert-decor absolute bottom-0 right-0 w-28 h-28 bg-teal-50 rounded-tl-full pointer-events-none"></div>

        <div class="relative z-10 text-center flex flex-col justify-between gap-5 cert-inner">
            <!-- Zone 1: Header Brand -->
            <div class="flex flex-col items-center justify-center gap-1.5">
                <x-campven-logo size="md" variant="dark" subtitle="E-Sertifikat Resmi" />
                <span class="text-xs font-extrabold uppercase tracking-[0.28em] text-indigo-700 mt-1">Sertifikat Partisipasi Kampus</span>
                <p class="text-[11px] font-mono text-slate-500">Nomor: {{ $cert_number }}</p>
                <div class="w-20 h-1 bg-gradient-to-r from-indigo-600 to-teal-500 mx-auto rounded-full mt-1"></div>
            </div>

            <!-- Zone 2: Recipient & Event Achievement -->
            <div class="space-y-3 py-1">
                <div class="space-y-1">
                    <p class="text-[11px] uppercase tracking-widest text-slate-500 font-bold">Diberikan Kepada</p>
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight cert-recipient-name">{{ $user->nama }}</h1>
                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                </div>

                <div class="max-w-2xl mx-auto space-y-1.5">
                    <p class="text-xs sm:text-sm text-slate-600">
                        Telah berpartisipasi aktif dan tercatat hadir sebagai <strong>Peserta</strong> dalam kegiatan:
                    </p>
                    <h2 class="text-lg sm:text-2xl font-extrabold text-indigo-800 leading-snug cert-event-title">{{ $event->nama_event }}</h2>
                    <p class="text-xs text-slate-600">
                        Kategori <strong>{{ $event->kategori }}</strong> • Diselenggarakan pada
                        <strong>{{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong>
                        bertempat di <strong>{{ $event->lokasi }}</strong>.
                    </p>
                </div>

                @if(($event->skpi_poin ?? 0) > 0)
                <div class="pt-1">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-teal-50 border border-teal-200 text-teal-800 text-xs font-extrabold">
                        <i class="fas fa-award text-teal-600"></i>
                        <span>Nilai Kredit Kegiatan: +{{ $event->skpi_poin }} Poin SKPI</span>
                    </span>
                </div>
                @endif
            </div>

            <!-- Zone 3: Footer Verification QR & Signature -->
            <div class="pt-5 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-left cert-footer">
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-white rounded-xl border border-slate-200 shadow-xs flex-shrink-0">
                        {!! \App\Support\QrCodeSvg::generate(route('ticket.verify', $participant->ticketCode()), 58) !!}
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Verifikasi Resmi Campven</span>
                        <span class="block text-xs font-extrabold text-slate-800">Kode Tiket: {{ $participant->ticketCode() }}</span>
                        <span class="block text-[10px] text-slate-500">Presensi Terverifikasi: {{ $participant->updated_at ? $participant->updated_at->format('d/m/Y H:i') . ' WIB' : '-' }}</span>
                    </div>
                </div>

                <div class="text-center sm:text-right">
                    <span class="block text-xs text-slate-500 mb-6 cert-signature-gap">Penyelenggara Kegiatan</span>
                    <div class="border-t border-slate-800 pt-1.5 inline-block min-w-[190px]">
                        <span class="block text-sm font-extrabold text-slate-900">{{ $event->creator->nama ?? 'Panitia Kampus' }}</span>
                        <span class="block text-[10px] text-slate-500">Organisasi Penyelenggara Resmi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
