<!DOCTYPE html>
<html lang="id" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <title>{{ $reportTitle }}</title>
    <style>
        body {
            font-family: Calibri, Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #0F172A;
            margin: 16px;
        }
        .header-title {
            font-size: 16pt;
            font-weight: bold;
            color: #312E81;
            margin: 0 0 4px 0;
        }
        .header-subtitle {
            font-size: 10pt;
            color: #475569;
            margin: 0 0 14px 0;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10pt;
        }
        .meta-table td {
            padding: 4px 8px;
            border: 1px solid #CBD5E1;
        }
        .meta-label {
            background-color: #F1F5F9;
            font-weight: bold;
            color: #334155;
            width: 18%;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }
        .data-table th {
            background-color: #4F46E5;
            color: #FFFFFF;
            font-weight: bold;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #3730A3;
        }
        .data-table td {
            padding: 7px 10px;
            border: 1px solid #CBD5E1;
            vertical-align: top;
        }
        .data-table tr.alt td {
            background-color: #F8FAFC;
        }
        .text-center { text-align: center; }
        .text-nowrap { white-space: nowrap; }
        .text-phone { mso-number-format:"\@"; }
        .badge-attended { color: #0F766E; font-weight: bold; }
        .badge-confirmed { color: #1D4ED8; font-weight: bold; }
        .badge-registered { color: #B45309; font-weight: bold; }
        .badge-canceled { color: #BE123C; font-weight: bold; }
        .footer-note {
            margin-top: 16px;
            font-size: 9pt;
            color: #64748B;
        }
        .signature-box {
            margin-top: 24px;
            width: 100%;
            border-collapse: collapse;
        }
        .signature-box td {
            border: none;
            padding: 4px;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    @php
        $statusLabels = [
            'registered' => ['label' => 'Menunggu Konfirmasi', 'class' => 'badge-registered'],
            'confirmed'  => ['label' => 'Terkonfirmasi', 'class' => 'badge-confirmed'],
            'attended'   => ['label' => 'Sudah Hadir', 'class' => 'badge-attended'],
            'canceled'   => ['label' => 'Dibatalkan', 'class' => 'badge-canceled'],
        ];
        $totalAll = $participants->count();
        $totalAttended = $participants->where('status', 'attended')->count();
        $totalConfirmed = $participants->where('status', 'confirmed')->count();
        $totalRegistered = $participants->where('status', 'registered')->count();
    @endphp

    <div class="header-title">CAMPVEN — {{ strtoupper($reportTitle) }}</div>
    <div class="header-subtitle">Dicetak pada {{ now()->locale('id')->isoFormat('dddd, D MMMM Y [pukul] HH:mm') }} WIB oleh {{ $exportedBy }}</div>

    <!-- Ringkasan Informasi Laporan -->
    <table class="meta-table">
        @if($event)
        <tr>
            <td class="meta-label">Nama Acara</td>
            <td><strong>{{ $event->nama_event }}</strong></td>
            <td class="meta-label">Kategori</td>
            <td>{{ $event->kategori }}</td>
        </tr>
        <tr>
            <td class="meta-label">Tanggal Acara</td>
            <td>{{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</td>
            <td class="meta-label">Lokasi</td>
            <td>{{ $event->lokasi }}</td>
        </tr>
        <tr>
            <td class="meta-label">Penyelenggara</td>
            <td>{{ $event->creator->nama ?? 'Panitia Kampus' }}</td>
            <td class="meta-label">Poin SKPI / Biaya</td>
            <td>+{{ (int) ($event->skpi_poin ?? 0) }} SKPI • {{ $event->formattedHarga() }}</td>
        </tr>
        @endif
        <tr>
            <td class="meta-label">Total Peserta</td>
            <td><strong>{{ $totalAll }}</strong> orang</td>
            <td class="meta-label">Rekap Status</td>
            <td>Hadir: <strong>{{ $totalAttended }}</strong> | Terkonfirmasi: <strong>{{ $totalConfirmed }}</strong> | Menunggu: <strong>{{ $totalRegistered }}</strong></td>
        </tr>
    </table>

    <!-- Tabel Daftar Peserta -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 36px;">No</th>
                <th style="width: 95px;">Kode Tiket</th>
                <th>Nama Mahasiswa</th>
                <th>Email</th>
                <th style="width: 115px;">No. Telepon</th>
                @if(!$event)
                <th>Nama Acara</th>
                <th style="width: 95px;">Tanggal</th>
                @endif
                <th style="width: 120px;">Status Kehadiran</th>
                <th style="width: 125px;">Waktu Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants as $index => $p)
                @php
                    $st = $statusLabels[$p->status] ?? ['label' => ucfirst($p->status), 'class' => ''];
                @endphp
                <tr class="{{ $index % 2 === 1 ? 'alt' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-nowrap"><strong>{{ $p->ticketCode() }}</strong></td>
                    <td>{{ $p->user->nama ?? '-' }}</td>
                    <td>{{ $p->user->email ?? '-' }}</td>
                    <td class="text-phone text-nowrap">{{ $p->user->no_telp ?? '-' }}</td>
                    @if(!$event)
                    <td>{{ $p->event->nama_event ?? '-' }}</td>
                    <td class="text-nowrap">{{ $p->event && $p->event->tanggal ? \Carbon\Carbon::parse($p->event->tanggal)->format('d/m/Y') : '-' }}</td>
                    @endif
                    <td class="{{ $st['class'] }}">{{ $st['label'] }}</td>
                    <td class="text-nowrap">{{ $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $event ? 7 : 9 }}" class="text-center">Belum ada data peserta.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($format === 'word')
    <table class="signature-box">
        <tr>
            <td style="width: 65%;"></td>
            <td style="width: 35%; text-align: center;">
                <div>Mengetahui,</div>
                <div><strong>{{ $event ? ($event->creator->nama ?? 'Panitia Penyelenggara') : $exportedBy }}</strong></div>
                <br><br><br>
                <div style="border-top: 1px solid #0F172A; padding-top: 4px;">Penanggung Jawab Kegiatan</div>
            </td>
        </tr>
    </table>
    @endif

    <div class="footer-note">
        Dokumen ini dihasilkan secara otomatis oleh sistem Campven (Platform Manajemen Event Organisasi Kampus).
    </div>
</body>
</html>

