{{-- $event, $animate, $delay --}}
@php
    $colors = [
        'Seminar'  => ['#334155', '#475569', 'fa-graduation-cap'],
        'Workshop' => ['#1E40AF', '#2563EB', 'fa-tools'],
        'Lomba'    => ['#B45309', '#D97706', 'fa-trophy'],
    ];
    [$from, $to, $icon] = $colors[$event->kategori] ?? ['#475569', '#64748B', 'fa-calendar'];
    $hasPoster = $event->poster && file_exists(public_path('storage/' . $event->poster));
@endphp
<a href="{{ route('event.detail', $event->id) }}"
   class="event-card"
   style="text-decoration:none;color:inherit;"
   @if($animate) data-aos="fade-up" data-aos-delay="{{ $delay }}" @endif>
    <div class="card-img-wrapper" style="background:linear-gradient(135deg, {{ $from }}, {{ $to }});">
        @if($hasPoster)
            <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->nama_event }}" class="card-img">
        @else
            <div class="card-fallback">
                <i class="fas {{ $icon }}"></i>
                <span>{{ $event->kategori }}</span>
            </div>
        @endif
        <span class="card-badge">{{ $event->kategori }}</span>
    </div>
    <div class="card-body">
        <h3 class="card-title">{{ $event->nama_event }}</h3>
        <div class="card-meta">
            <span><i class="fas fa-calendar-day"></i> {{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</span>
            <span><i class="fas fa-map-marker-alt"></i> {{ $event->lokasi }}</span>
        </div>
    </div>
    <div class="card-footer">
        <span><i class="fas fa-users"></i> {{ $event->participantCount() }} peserta</span>
        <span>{{ $event->creator->nama ?? 'Tidak diketahui' }}</span>
    </div>
</a>