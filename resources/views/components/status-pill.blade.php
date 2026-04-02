@props(['status' => 'pending'])

@php
    $normalized = strtolower(str_replace(' ', '_', $status));
    $map = [
        'pending' => 'warning',
        'in_review' => 'info',
        'approved' => 'success',
        'completed' => 'success',
        'paid' => 'success',
        'rejected' => 'danger',
        'cancelled' => 'danger',
        'unpaid' => 'danger',
        'missing_documents' => 'danger',
        'confirmed' => 'success',
        'scheduled' => 'info',
    ];

    $color = $map[$normalized] ?? 'secondary';
@endphp

<span {{ $attributes->merge(['class' => 'badge rounded-pill text-uppercase bg-' . $color . '-subtle border border-' . $color . '-subtle']) }} style="font-size:.66rem; letter-spacing:.04em;">
    {{ str_replace('_', ' ', $normalized) }}
</span>
