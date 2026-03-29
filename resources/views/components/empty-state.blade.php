@props([
    'icon' => 'bi-inbox',
    'title' => 'No data found',
    'message' => '',
    'actionUrl' => null,
    'actionLabel' => 'Get Started',
])

<div {{ $attributes->merge(['class' => 'text-center py-4']) }}>
    <div class="mb-2">
        <i class="bi {{ $icon }} text-muted" style="font-size:2rem;"></i>
    </div>
    <div class="fw-semibold mb-1" style="font-size:.88rem;">{{ $title }}</div>
    @if($message)
        <div class="text-muted mb-3" style="font-size:.8rem;">{{ $message }}</div>
    @endif
    @if($actionUrl)
        <a href="{{ $actionUrl }}" class="btn btn-sm btn-primary">{{ $actionLabel }}</a>
    @endif
</div>
