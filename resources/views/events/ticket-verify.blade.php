@extends('layouts.app')

@section('title', 'Verifikasi Tiket ' . strtoupper($code) . ' - Campven')

@section('content')
@include('partials.public-navbar', ['activeNav' => 'home'])

<div class="max-w-lg mx-auto px-4 py-10 sm:py-16">
    @if($participant && $participant->event)
        @php
            $event = $participant->event;
            $status = $participant->status;
            $statusMap = [
                'registered' => ['bg' => 'bg-amber-50 border-amber-200 text-amber-800', 'icon' => 'fa-clock text-amber-500', 'label' => 'Menunggu Konfirmasi'],
                'confirmed'  => ['bg' => 'bg-emerald-50 border-emerald-200 text-emerald-800', 'icon' => 'fa-check-circle text-emerald-600', 'label' => 'Terkonfirmasi'],
                'attended'   => ['bg' => 'bg-blue-50 border-blue-200 text-blue-800', 'icon' => 'fa-check-double text-blue-600', 'label' => 'Sudah Hadir (Terverifikasi)'],
                'canceled'   => ['bg' => 'bg-rose-50 border-rose-200 text-rose-800', 'icon' => 'fa-ban text-rose-600', 'label' => 'Dibatalkan'],
            ];
            $st = $statusMap[$status] ?? ['bg' => 'bg-slate-50 border-slate-200 text-slate-700', 'icon' => 'fa-info-circle text-slate-500', 'label' => ucfirst($status)];
        @endphp

        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-indigo-600 to-slate-900 p-6 text-white text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-md flex items-center justify-center mx-auto text-xl">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <span class="block text-[10px] font-extrabold uppercase tracking-[0.25em] text-indigo-200">E-Tiket Resmi Campven</span>
                <h1 class="font-mono text-2xl font-black tracking-wider">{{ $participant->ticketCode() }}</h1>
            </div>

            <!-- Verification Body -->
            <div class="p-6 sm:p-8 space-y-6">
                <!-- Status Pill -->
                <div class="p-4 rounded-2xl border {{ $st['bg'] }} flex items-center justify-center gap-2.5 text-sm font-extrabold">
                    <i class="fas {{ $st['icon'] }} text-base"></i>
                    <span>Status: {{ $st['label'] }}</span>
                </div>

                <!-- Participant & Event Details -->
                <div class="space-y-3 text-xs">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2.5">
                        <div class="flex justify-between gap-4">
                            <span class="text-slate-400 font-semibold">Nama Peserta</span>
                            <span class="font-extrabold text-slate-900 text-right">{{ $participant->user->nama ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between gap-4 border-t border-slate-200/60 pt-2">
                            <span class="text-slate-400 font-semibold">Nama Acara</span>
                            <span class="font-extrabold text-indigo-700 text-right">{{ $event->nama_event }}</span>
                        </div>
                        <div class="flex justify-between gap-4 border-t border-slate-200/60 pt-2">
                            <span class="text-slate-400 font-semibold">Kategori</span>
                            <span class="font-bold text-slate-700 text-right">{{ $event->kategori }}</span>
                        </div>
                        <div class="flex justify-between gap-4 border-t border-slate-200/60 pt-2">
                            <span class="text-slate-400 font-semibold">Tanggal</span>
                            <span class="font-bold text-slate-700 text-right">{{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                        </div>
                        <div class="flex justify-between gap-4 border-t border-slate-200/60 pt-2">
                            <span class="text-slate-400 font-semibold">Lokasi</span>
                            <span class="font-bold text-slate-700 text-right">{{ $event->lokasi }}</span>
                        </div>
                        <div class="flex justify-between gap-4 border-t border-slate-200/60 pt-2">
                            <span class="text-slate-400 font-semibold">Penyelenggara</span>
                            <span class="font-bold text-slate-700 text-right">{{ $event->creator->nama ?? 'Panitia Kampus' }}</span>
                        </div>
                        @if(($event->skpi_poin ?? 0) > 0)
                        <div class="flex justify-between gap-4 border-t border-slate-200/60 pt-2">
                            <span class="text-slate-400 font-semibold">Poin SKPI</span>
                            <span class="font-extrabold text-amber-700 text-right">+{{ $event->skpi_poin }} Poin</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons by Role -->
                <div class="space-y-2.5 pt-2">
                    @if($isOwnerOrganizer && in_array($status, ['registered', 'confirmed']))
                        <form method="POST" action="{{ route('organizer.participant.attend', $participant->id) }}">
                            @csrf
                            <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-indigo-600/20 transition btn-spring flex items-center justify-center gap-2">
                                <i class="fas fa-user-check"></i>
                                <span>Verifikasi &amp; Catat Hadir Sekarang</span>
                            </button>
                        </form>
                    @elseif($isTicketOwner && $status === 'attended')
                        <a href="{{ route('my.events.certificate', $event->id) }}" class="w-full py-3.5 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-indigo-600/20 transition btn-spring flex items-center justify-center gap-2">
                            <i class="fas fa-certificate"></i>
                            <span>Cetak E-Sertifikat</span>
                        </a>
                    @endif

                    <a href="{{ route('event.detail', $event->id) }}" class="block w-full py-3 px-4 rounded-2xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition">
                        Lihat Detail Acara
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-rose-200 shadow-xl p-8 text-center space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto text-2xl">
                <i class="fas fa-times-circle"></i>
            </div>
            <h1 class="text-xl font-black text-slate-900">Tiket Tidak Ditemukan</h1>
            <p class="text-xs text-slate-500 leading-relaxed">
                Kode tiket <span class="font-mono font-bold text-rose-600">{{ strtoupper($code) }}</span> tidak terdaftar di sistem Campven.
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition">
                <i class="fas fa-home"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    @endif
</div>

@include('partials.footer')
@endsection

