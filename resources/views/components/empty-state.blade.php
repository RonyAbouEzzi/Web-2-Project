@props([
    'icon' => 'bi-inbox',
    'title' => 'No data found',
    'message' => '',
    'actionUrl' => null,
    'actionLabel' => 'Get Started',
])

<div class="empty-state" {{ $attributes }}>
    <div class="empty-icon">
        <i class="bi {{ $icon }}"></i>
    </div>
    <h4>{{ $title }}</h4>
    @if($message)
        <p>{{ $message }}</p>
    @endif
    @if($actionUrl)
        <a href="{{ $actionUrl }}" class="btn btn-primary btn-sm">
            {{ $actionLabel }}
        </a>
    @endif
</div>