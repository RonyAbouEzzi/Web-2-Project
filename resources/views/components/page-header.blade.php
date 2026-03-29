@props([
    'title' => '',
])

<div class="pg-header" {{ $attributes }}>
    <h1>{{ $title }}</h1>
    @if(isset($actions))
        <div style="display:flex;align-items:center;gap:.4rem;flex-wrap:wrap">
            {{ $actions }}
        </div>
    @endif
</div>