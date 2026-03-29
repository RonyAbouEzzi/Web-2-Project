@props([
    'label' => '',
    'value' => '0',
    'icon' => 'bi-bar-chart',
    'color' => 'primary',
    'subtitle' => null,
])

@php
    $colorMap = [
        'amber'   => ['bg' => 'var(--amber-lt)',   'fg' => 'var(--amber)'],
        'emerald' => ['bg' => 'var(--emerald-lt)',  'fg' => 'var(--emerald)'],
        'sky'     => ['bg' => 'var(--sky-lt)',      'fg' => 'var(--sky)'],
        'violet'  => ['bg' => 'var(--violet-lt)',   'fg' => 'var(--violet)'],
        'primary' => ['bg' => 'var(--primary-lt)',  'fg' => 'var(--primary)'],
        'rose'    => ['bg' => 'var(--rose-lt)',     'fg' => 'var(--rose)'],
    ];
    $c = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div class="stat-card" {{ $attributes }}>
    <div class="stat-icon" style="background:{{ $c['bg'] }};color:{{ $c['fg'] }}">
        <i class="bi {{ $icon }}"></i>
    </div>
    <div class="stat-val">{{ $value }}</div>
    <div class="stat-lbl">{{ $label }}</div>
    @if($subtitle)
        <div style="margin-top:.35rem">{!! $subtitle !!}</div>
    @endif
</div>