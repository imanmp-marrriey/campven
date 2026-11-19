{{-- Modern Event Card Matching Google Stitch Design --}}
@php
    $categoriesConfig = [
        'Seminar'  => ['bg' => 'from-slate-800 to-indigo-950', 'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200', 'tag' => 'Seminar & Konferensi', 'icon' => 'fa-graduation-cap'],
        'Workshop' => ['bg' => 'from-blue-700 to-slate-900', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200', 'tag' => 'Workshop Terakreditasi', 'icon' => 'fa-laptop-code'],
        'Lomba'    => ['bg' => 'from-amber-600 to-slate-900', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200', 'tag' => 'Kompetisi & Lomba', 'icon' => 'fa-trophy'],
        'Webinar'  => ['bg' => 'from-emerald-700 to-slate-900', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'tag' => 'Webinar Daring', 'icon' => 'fa-video'],
        'Festival' => ['bg' => 'from-purple-700 to-pink-900', 'badge' => 'bg-purple-50 text-purple-700 border-purple-200', 'tag' => 'Festival & Seni', 'icon' => 'fa-music'],
    ];
    $cfg = $categoriesConfig[$event->kategori] ?? ['bg' => 'from-slate-800 to-slate-900', 'badge' => 'bg-slate-50 text-slate-700 border-slate-200', 'tag' => $event->kategori, 'icon' => 'fa-calendar'];
    $hasPoster = $event->poster && file_exists(public_path('storage/' . $event->poster));
    $quota = $event->kuota;
    $count = $event->participantCount();
    $sisa = $event->sisaKuota();
    $isFull = $event->isFull();
    $percent = $event->persentaseTerisi();
    $price = $event->formattedHarga();
@endphp

<div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1.5 transition-all duration-300 flex flex-col h-full relative">
    
    <!-- Poster Media Banner -->
    <a href="{{ route('event.detail', $event->id) }}" class="relative h-48 w-full overflow-hidden bg-gradient-to-br {{ $cfg['bg'] }} block flex-shrink-0">
        @if($hasPoster)
            <img src="{{ asset('storage/' . $event->poster) }}" 
                 alt="{{ $event->nama_event }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                 loading="lazy">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-white/80 p-4 text-center">
                <i class="fas {{ $cfg['icon'] }} text-4xl mb-2 group-hover:scale-110 transition-transform duration-300 text-indigo-300"></i>
                <span class="text-[11px] font-bold uppercase tracking-wider opacity-90">{{ $cfg['tag'] }}</span>
            </div>
        @endif

        <!-- Dark Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>

        <!-- Floating Category Tag -->
        <div class="absolute top-3.5 left-3.5">
            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider backdrop-blur-md bg-white/95 text-slate-800 shadow-sm border border-white/40 flex items-center gap-1.5">
                <i class="fas {{ $cfg['icon'] }} text-indigo-600 text-[10px]"></i>
                {{ $event->kategori }}
            </span>
        </div>

        <!-- Availability Badge -->
        <div class="absolute top-3.5 right-3.5">
            @if($isFull)
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-600 text-white shadow-sm flex items-center gap-1">
                    <i class="fas fa-ban text-[9px]"></i> Penuh
                </span>
            @elseif($quota && $sisa <= 10)
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500 text-white shadow-sm animate-pulse flex items-center gap-1">
                    <i class="fas fa-fire text-[9px]"></i> Sisa {{ $sisa }} Kursi
                </span>
            @else
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-600 text-white shadow-sm flex items-center gap-1">
                    <i class="fas fa-check-circle text-[9px]"></i> Tersedia
                </span>
            @endif
        </div>
    </a>

    <!-- Card Body -->
    <div class="p-5 flex flex-col flex-grow justify-between">
        <div>
            <!-- Organizer Row -->
            <div class="flex items-center gap-2 mb-2.5">
                <div class="w-5 h-5 rounded-full bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0">
                    {{ strtoupper(substr($event->creator->nama ?? 'C', 0, 1)) }}
                </div>
                <span class="text-xs font-semibold text-slate-600 truncate">{{ $event->creator->nama ?? 'Penyelenggara Kampus' }}</span>
                <i class="fas fa-check-circle text-indigo-600 text-[11px] ml-0.5" title="Terverifikasi"></i>
            </div>

            <!-- Event Title -->
            <a href="{{ route('event.detail', $event->id) }}" class="block group-hover:text-indigo-600 transition-colors">
                <h3 class="text-base font-extrabold text-slate-900 leading-snug line-clamp-2 mb-3">
                    {{ $event->nama_event }}
                </h3>
            </a>

            <!-- Event Date & Venue Meta -->
            <div class="space-y-1.5 text-xs text-slate-600 mb-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-day text-slate-400 w-3.5"></i>
                    <span>{{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('D MMMM Y') }}</span>
                    @if($event->waktu_mulai)
                        <span class="text-slate-300">•</span>
                        <span>{{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') }} WIB</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-rose-500 w-3.5 flex-shrink-0"></i>
                    <span class="truncate">{{ $event->lokasi }}</span>
                </div>
            </div>

            <!-- Sisa Kuota Meter -->
            @if($quota)
            <div class="mb-4 pt-3 border-t border-slate-100">
                <div class="flex items-center justify-between text-[11px] font-bold text-slate-500 mb-1.5">
                    <span>Sisa Kuota: <strong class="{{ $isFull ? 'text-rose-600' : 'text-slate-800' }}">{{ $sisa }} / {{ $quota }} Kursi</strong></span>
                    <span class="{{ $percent >= 80 ? 'text-amber-600' : 'text-emerald-600' }}">{{ $percent }}% Terisi</span>
                </div>
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 {{ $isFull ? 'bg-rose-500' : ($percent >= 80 ? 'bg-amber-500' : 'bg-indigo-600') }}" style="width: {{ $percent }}%;"></div>
                </div>
            </div>
            @endif
        </div>

        <!-- Price & Action CTA -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
            <div>
                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Biaya Partisipasi</span>
                <span class="text-sm font-extrabold {{ $price === 'GRATIS' ? 'text-emerald-600' : 'text-indigo-600' }}">
                    {{ $price }}
                </span>
            </div>

            <a href="{{ route('event.detail', $event->id) }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition-all btn-spring flex items-center gap-1.5">
                <span>Daftar Sekarang</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>
</div>
