@props([
    'status' => 'neutral',
])

<span class="sbadge s-{{ str_replace(' ', '_', strtolower($status)) }}" {{ $attributes }}>
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>