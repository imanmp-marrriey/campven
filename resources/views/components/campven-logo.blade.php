@props([
    'variant' => 'dark',
    'showText' => true,
    'size' => 'md',
    'subtitle' => null,
    'dark' => null,
])

@php
    $resolvedVariant = $variant;
    if ($dark !== null) {
        $resolvedVariant = $dark ? 'light' : 'dark';
    }
    $isLightText = ($resolvedVariant === 'light');

    $iconSizes = [
        'sm' => 'w-7 h-7',
        'md' => 'w-9 h-9',
        'lg' => 'w-11 h-11',
    ];
    $textSizes = [
        'sm' => 'text-base',
        'md' => 'text-lg sm:text-xl',
        'lg' => 'text-2xl',
    ];
    $iconClass = $iconSizes[$size] ?? $iconSizes['md'];
    $textClass = $textSizes[$size] ?? $textSizes['md'];
    $uid = 'cv_' . uniqid();
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 flex-shrink-0 select-none']) }}>
    <!-- Original Geometric Campven Monogram ("C" + Teal "V" Check Mark on Clean Rounded Tile) -->
    <svg class="{{ $iconClass }} flex-shrink-0" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <defs>
            <linearGradient id="{{ $uid }}_bg" x1="2" y1="2" x2="38" y2="38" gradientUnits="userSpaceOnUse">
                <stop offset="0%" stop-color="#4F46E5"/>
                <stop offset="100%" stop-color="#3730A3"/>
            </linearGradient>
        </defs>
        <!-- Clean Rounded Square Tile -->
        <rect x="2" y="2" width="36" height="36" rx="11" fill="url(#{{ $uid }}_bg)"/>
        <!-- Top-Right Teal Corner Accent -->
        <path d="M25 2H27C33.075 2 38 6.925 38 13V15L25 2Z" fill="#0D9488"/>
        <!-- Bold Geometric "C" Arc -->
        <path d="M23.2 13.8C21.8 12.6 20 11.9 18 11.9C13.6 11.9 10 15.5 10 20C10 24.5 13.6 28.1 18 28.1C20.2 28.1 22.1 27.2 23.5 25.8" stroke="#FFFFFF" stroke-width="4.2" stroke-linecap="round"/>
        <!-- Crisp Geometric "V" / Check Mark in Teal -->
        <path d="M22.2 17.6L25.8 23.4L30.8 15.2" stroke="#2DD4BF" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>

    @if($showText)
    <div class="leading-tight text-left">
        <span class="{{ $textClass }} font-extrabold tracking-tight" style="color: {{ $isLightText ? '#FFFFFF' : '#0F172A' }};">
            Camp<span style="color: {{ $isLightText ? '#2DD4BF' : '#4F46E5' }};">ven</span>
        </span>
        @if($subtitle)
            <span class="block text-[10px] font-semibold tracking-wide -mt-0.5 {{ $isLightText ? 'text-slate-300' : 'text-slate-500' }}">
                {{ $subtitle }}
            </span>
        @endif
    </div>
    @endif
</div>
