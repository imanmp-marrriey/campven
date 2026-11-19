@php
    $hasPoster = $event->poster && file_exists(public_path('storage/' . $event->poster));
    $quota = $event->kuota;
    $sisa = $event->sisaKuota();
    $isFull = $event->isFull();
    $isPast = \Carbon\Carbon::parse($event->tanggal)->lt(\Carbon\Carbon::today());
    $percent = $event->persentaseTerisi();
    $price = $event->formattedHarga();
    $skpi = (int) ($event->skpi_poin ?? 0);
@endphp

<article class="group bg-white rounded-xl border border-slate-200 overflow-hidden hover:-translate-y-1 hover:border-indigo-200 hover:shadow-md transition-all duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] flex flex-col h-full">
    <!-- Poster Image (Proportional 16:9 Full-Bleed Frame) -->
    <a href="{{ route('event.detail', $event->id) }}" class="relative aspect-[16/9] w-full overflow-hidden bg-slate-900 block flex-shrink-0">
        @if($hasPoster)
            <img src="{{ asset('storage/' . $event->poster) }}"
                 alt="{{ $event->nama_event }}"
                 class="w-full h-full object-cover object-center group-hover:scale-[1.03] transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] {{ $isPast ? 'grayscale-[30%]' : '' }}"
                 loading="lazy">
        @else
            <div class="w-full h-full bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-800 flex flex-col items-center justify-center text-slate-300 p-4 text-center">
                <i class="far fa-calendar-alt text-3xl mb-2 opacity-75 text-teal-400"></i>
                <span class="text-xs font-semibold">{{ $event->kategori }}</span>
            </div>
        @endif

        <!-- Category Badge -->
        <div class="absolute top-2.5 left-2.5 z-20">
            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-white/95 text-slate-800 shadow-sm">
                {{ $event->kategori }}
            </span>
        </div>

        <!-- Status Badge -->
        <div class="absolute top-2.5 right-2.5 z-20">
            @if($isPast)
                <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/90 text-white">
                    Selesai
                </span>
            @elseif($isFull)
                <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-rose-600 text-white">
                    Penuh
                </span>
            @elseif($quota && $sisa <= 10)
                <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-amber-500 text-slate-950">
                    Sisa {{ $sisa }} kursi
                </span>
            @else
                <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-teal-600 text-white">
                    Tersedia
                </span>
            @endif
        </div>
    </a>

    <!-- Card Body -->
    <div class="p-4 flex flex-col flex-grow justify-between gap-3.5">
        <div class="space-y-2">
            <!-- Organizer & SKPI -->
            <div class="flex items-center justify-between gap-2 text-xs text-slate-500">
                <span class="font-medium truncate">{{ $event->creator->nama ?? 'Penyelenggara Kampus' }}</span>
                @if($skpi > 0)
                    <span class="text-[11px] font-semibold text-teal-700 bg-teal-50 border border-teal-100 px-2 py-0.5 rounded flex-shrink-0">+{{ $skpi }} SKPI</span>
                @endif
            </div>

            <!-- Event Title -->
            <a href="{{ route('event.detail', $event->id) }}" class="block">
                <h3 class="text-[15px] font-bold text-slate-900 group-hover:text-indigo-600 transition-colors leading-snug line-clamp-2">
                    {{ $event->nama_event }}
                </h3>
            </a>

            <!-- Date & Venue -->
            <div class="space-y-1 text-xs text-slate-600">
                <div class="flex items-center gap-2">
                    <i class="far fa-calendar text-slate-400 w-3.5 text-center"></i>
                    <span>{{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->isoFormat('D MMMM Y') }}</span>
                    @if($event->waktu_mulai)
                        <span class="text-slate-300">•</span>
                        <span>{{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') }} WIB</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-slate-400 w-3.5 text-center flex-shrink-0"></i>
                    <span class="truncate">{{ $event->lokasi }}</span>
                </div>
            </div>

            <!-- Quota Bar -->
            @if($quota)
            <div class="pt-1.5">
                <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1">
                    <span>Kuota tersisa: <strong class="text-slate-800">{{ $sisa }}/{{ $quota }}</strong></span>
                    <span>{{ $percent }}%</span>
                </div>
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="quota-progress-bar h-full rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-indigo-600' }}" style="width: 0%;" data-target-width="{{ $percent }}"></div>
                </div>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
            <span class="text-sm font-bold {{ $price === 'GRATIS' ? 'text-teal-600' : 'text-slate-900' }}">
                {{ $price }}
            </span>

            <a href="{{ route('event.detail', $event->id) }}" class="btn-tactile active:scale-[0.98] px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200 {{ $isPast || $isFull ? 'bg-slate-100 hover:bg-slate-200 text-slate-700' : 'bg-indigo-600 hover:bg-indigo-700 text-white' }}">
                {{ $isPast || $isFull ? 'Lihat Detail' : 'Daftar' }}
            </a>
        </div>
    </div>
</article>
