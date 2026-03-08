@extends('layouts.app')
@section('title', $office->name)
@section('page-title', $office->name)

@section('content')

{{-- Office header --}}
<div style="background:linear-gradient(135deg,#0038a8,#0070f3);border-radius:14px;padding:1.5rem;margin-bottom:1.25rem">
    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
        <div style="width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#fff;flex-shrink:0">
            <i class="bi bi-building"></i>
        </div>
        <div style="flex:1;min-width:0">
            <h4 style="color:#fff;font-weight:800;margin:0 0 .2rem;font-size:1.1rem">{{ $office->name }}</h4>
            <div style="color:rgba(255,255,255,.7);font-size:.78rem;display:flex;align-items:center;gap:.75rem;flex-wrap:wrap">
                <span><i class="bi bi-geo-alt me-1"></i>{{ $office->municipality->name }}</span>
                @if($office->phone)<span><i class="bi bi-telephone me-1"></i>{{ $office->phone }}</span>@endif
            </div>
        </div>
        @php $rating = $office->averageRating(); @endphp
        @if($rating)
        <div style="text-align:center;flex-shrink:0">
            <div style="font-size:1.5rem;font-weight:800;color:#fff">{{ number_format($rating,1) }}</div>
            <div style="display:flex;gap:2px;justify-content:center">
                @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i <= round($rating) ? '-fill' : '' }}" style="color:#fbbf24;font-size:.75rem"></i>@endfor
            </div>
        </div>
        @endif
    </div>
    @if($office->address)
    <div style="color:rgba(255,255,255,.65);font-size:.78rem;margin-top:.9rem;display:flex;align-items:flex-start;gap:.4rem">
        <i class="bi bi-geo-alt" style="flex-shrink:0;margin-top:1px"></i>
        <span>{{ $office->address }}</span>
    </div>
    @endif
</div>

<div style="display:grid;grid-template-columns:1fr;gap:1rem" class="office-grid">

    {{-- Services --}}
    <div>
        <h6 style="font-weight:800;font-size:.9rem;margin-bottom:.75rem">Available Services</h6>
        @php $grouped = $office->services->where('is_active',true)->groupBy('category_id'); @endphp
        @forelse($grouped as $catId => $services)
            @php $catName = $services->first()->category->name ?? 'General'; @endphp
            <div style="font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;margin-bottom:.5rem;margin-top:.75rem">{{ $catName }}</div>
            @foreach($services as $svc)
            <div style="background:#fff;border:1px solid #e5eaf0;border-radius:12px;padding:1rem;margin-bottom:.65rem;display:flex;align-items:center;gap:.9rem;transition:box-shadow .15s" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow=''">
                <div style="width:40px;height:40px;border-radius:10px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-weight:700;font-size:.85rem">{{ $svc->name }}</div>
                    @if($svc->description)<div style="font-size:.75rem;color:#9ca3af;margin-top:1px">{{ Str::limit($svc->description, 60) }}</div>@endif
                    <div style="display:flex;gap:.75rem;margin-top:.3rem;font-size:.73rem;color:#6b7280;flex-wrap:wrap">
                        <span><i class="bi bi-clock me-1"></i>~{{ $svc->estimated_duration_days }} day(s)</span>
                        @if($svc->required_documents)<span><i class="bi bi-paperclip me-1"></i>{{ count($svc->required_documents) }} doc(s) required</span>@endif
                    </div>
                </div>
                <div style="text-align:right;flex-shrink:0">
                    <div style="font-size:1rem;font-weight:800;color:var(--primary)">${{ number_format($svc->price,2) }}</div>
                    <a href="{{ route('citizen.services.show', $svc) }}" class="btn btn-primary btn-sm" style="margin-top:.4rem;font-size:.72rem;padding:.3rem .7rem">
                        Apply
                    </a>
                </div>
            </div>
            @endforeach
        @empty
        <div style="text-align:center;padding:2rem;color:#9ca3af;background:#fff;border-radius:12px;border:1px solid #e5eaf0">No services available.</div>
        @endforelse
    </div>

    {{-- Sidebar: info + reviews --}}
    <div style="display:flex;flex-direction:column;gap:1rem">
        {{-- Working Hours --}}
        @if($office->working_hours)
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="bi bi-clock me-2" style="color:var(--primary)"></i>Working Hours</span></div>
            <div class="card-body">
                @php $days = ['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun']; @endphp
                @foreach($days as $key => $label)
                @php $hours = $office->working_hours[$key] ?? null; @endphp
                <div style="display:flex;justify-content:space-between;padding:.3rem 0;{{ !$loop->last ? 'border-bottom:1px solid #f3f4f6;' : '' }}">
                    <span style="font-size:.8rem;color:#374151;font-weight:500">{{ $label }}</span>
                    <span style="font-size:.8rem;{{ $hours === 'closed' ? 'color:#dc2626;font-weight:600' : 'color:#16a34a;font-weight:600' }}">
                        {{ $hours ?? 'N/A' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Reviews --}}
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="bi bi-star me-2" style="color:#f59e0b"></i>Recent Reviews</span></div>
            <div class="card-body" style="padding:0 !important">
                @forelse($office->feedbacks->take(3) as $fb)
                <div style="padding:.85rem 1.1rem;{{ !$loop->last ? 'border-bottom:1px solid #f3f4f6;' : '' }}">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.35rem">
                        <span style="font-size:.8rem;font-weight:600">{{ $fb->citizen->name }}</span>
                        <div style="display:flex;gap:1px">
                            @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$fb->rating?'-fill':'' }}" style="color:{{ $i<=$fb->rating?'#f59e0b':'#d1d5db' }};font-size:.7rem"></i>@endfor
                        </div>
                    </div>
                    @if($fb->comment)<p style="font-size:.78rem;color:#6b7280;margin:0;line-height:1.5">{{ $fb->comment }}</p>@endif
                </div>
                @empty
                <div style="padding:1.25rem;text-align:center;color:#9ca3af;font-size:.8rem">No reviews yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Book Appointment --}}
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="bi bi-calendar-plus me-2" style="color:var(--primary)"></i>Book Appointment</span></div>
            <div class="card-body">
                <p style="font-size:.8rem;color:#6b7280;margin-bottom:.9rem">Schedule an in-person visit</p>
                <form action="{{ route('citizen.appointments.book') }}" method="POST">
                    @csrf
                    <input type="hidden" name="office_id" value="{{ $office->id }}">
                    <div class="mb-2">
                        <label class="form-label">Date</label>
                        <input type="date" name="appointment_date" class="form-control" min="{{ now()->addDay()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Time</label>
                        <input type="time" name="appointment_time" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"><i class="bi bi-calendar-check"></i> Book</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media(min-width:768px) {
    .office-grid { grid-template-columns: 1fr 280px !important; }
}
</style>
@endpush
@endsection
