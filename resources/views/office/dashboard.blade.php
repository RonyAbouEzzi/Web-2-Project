@extends('layouts.app')
@section('title','Office Dashboard')
@section('page-title','Dashboard')

@section('content')

@php $office = auth()->user()->offices()->first(); @endphp

{{-- Hero Banner --}}
<div class="hero-banner" style="margin-bottom:1.35rem">
    <div style="position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
        <div>
            <div style="color:rgba(255,255,255,.45);font-size:.73rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;margin-bottom:.2rem">
                {{ $office?->name ?? 'Your Office' }}
            </div>
            <h1 style="font-family:var(--font-disp);font-size:1.4rem;font-weight:800;color:#fff;margin:0;letter-spacing:-.03em">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}
            </h1>
            <p style="color:rgba(255,255,255,.5);font-size:.8rem;margin:.25rem 0 0">{{ now()->format('l, F j · ') }}{{ $stats['pending_today'] }} new requests today</p>
        </div>
        <a href="{{ route('office.requests') }}" class="btn btn-sm" style="background:rgba(255,255,255,.9);border:none;color:#1A56DB;flex-shrink:0">
            <i class="bi bi-inbox-fill"></i> View Requests
        </a>
    </div>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.85rem;margin-bottom:1.35rem" class="stats-grid">
    <div class="stat-card" style="--stat-color:var(--amber-lt)">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="stat-icon" style="background:var(--amber-lt);color:var(--amber)"><i class="bi bi-hourglass-split"></i></div>
            <span class="stat-delta dn"><i class="bi bi-dot"></i>Needs action</span>
        </div>
        <div class="stat-val">{{ $stats['pending'] }}</div>
        <div class="stat-lbl">Pending Requests</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--blue-50)">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="stat-icon" style="background:var(--blue-50);color:var(--primary)"><i class="bi bi-eye"></i></div>
            <span class="stat-delta up"><i class="bi bi-dot"></i>In review</span>
        </div>
        <div class="stat-val">{{ $stats['in_review'] }}</div>
        <div class="stat-lbl">Being Reviewed</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--emerald-lt)">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="stat-icon" style="background:var(--emerald-lt);color:var(--emerald)"><i class="bi bi-check-circle-fill"></i></div>
            <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i>This month</span>
        </div>
        <div class="stat-val">{{ $stats['completed_this_month'] }}</div>
        <div class="stat-lbl">Completed</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--violet-lt)">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="stat-icon" style="background:var(--violet-lt);color:var(--violet)"><i class="bi bi-star-fill"></i></div>
            <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i>Avg rating</span>
        </div>
        <div class="stat-val">{{ number_format($stats['avg_rating'] ?? 0, 1) }}</div>
        <div class="stat-lbl">Satisfaction Score</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr;gap:1.1rem" class="office-grid">

    {{-- Recent Requests --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Pending Requests</span>
            <a href="{{ route('office.requests') }}" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body p0">
            @forelse($recentRequests as $req)
            <a href="{{ route('office.requests.show', $req) }}"
               style="display:flex;align-items:center;gap:.85rem;padding:.9rem 1.2rem;border-bottom:1px solid var(--ink-100);text-decoration:none;color:inherit;transition:background .12s"
               onmouseover="this.style.background='var(--ink-50)'" onmouseout="this.style.background='transparent'">
                <div style="width:38px;height:38px;border-radius:10px;background:var(--primary-lt);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($req->citizen->name,0,1)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:.83rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $req->citizen->name }}</div>
                    <div style="font-size:.72rem;color:var(--ink-400)">{{ $req->service->name }} · <code style="font-size:.65rem">{{ $req->reference_number }}</code></div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.25rem;flex-shrink:0">
                    <span class="sbadge s-{{ $req->status }}">{{ ucfirst(str_replace('_',' ',$req->status)) }}</span>
                    <span style="font-size:.68rem;color:var(--ink-400)">{{ $req->created_at->diffForHumans() }}</span>
                </div>
                <i class="bi bi-chevron-right" style="color:var(--ink-200);flex-shrink:0"></i>
            </a>
            @empty
            <div class="empty-state" style="padding:2.5rem 1rem">
                <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                <h4>All caught up!</h4>
                <p>No pending requests right now.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Side column --}}
    <div style="display:flex;flex-direction:column;gap:1.1rem">

        {{-- Today's Appointments --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Today's Appointments</span>
                <a href="{{ route('office.appointments') }}" class="btn btn-sm btn-ghost">All</a>
            </div>
            <div class="card-body p0">
                @forelse($todayAppointments as $apt)
                <div style="display:flex;align-items:center;gap:.75rem;padding:.8rem 1.2rem;border-bottom:1px solid var(--ink-100)">
                    <div style="width:40px;height:40px;border-radius:9px;background:var(--blue-50);color:var(--primary);display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--font-disp)">
                        <span style="font-size:.55rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;line-height:1">{{ $apt->appointment_date->format('M') }}</span>
                        <span style="font-size:1rem;font-weight:800;line-height:1">{{ $apt->appointment_date->format('d') }}</span>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $apt->citizen->name }}</div>
                        <div style="font-size:.71rem;color:var(--ink-400)">{{ $apt->appointment_time }}</div>
                    </div>
                    <span class="sbadge s-{{ $apt->status }}">{{ ucfirst($apt->status) }}</span>
                </div>
                @empty
                <div style="padding:1.5rem;text-align:center;color:var(--ink-400);font-size:.8rem">
                    <i class="bi bi-calendar-check" style="font-size:1.5rem;display:block;margin-bottom:.5rem;color:var(--ink-300)"></i>
                    No appointments today
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Reviews --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Recent Reviews</span>
                <a href="{{ route('office.feedback') }}" class="btn btn-sm btn-ghost">All</a>
            </div>
            <div class="card-body p0">
                @forelse($recentFeedback as $fb)
                <div style="padding:.85rem 1.2rem;border-bottom:1px solid var(--ink-100)">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.35rem">
                        <span style="font-size:.8rem;font-weight:600">{{ $fb->citizen->name }}</span>
                        <div style="display:flex;gap:1px">
                            @for($i=1;$i<=5;$i++)
                            <i class="bi bi-star{{ $i <= $fb->rating ? '-fill' : '' }}" style="font-size:.65rem;color:{{ $i <= $fb->rating ? '#F59E0B' : 'var(--ink-200)' }}"></i>
                            @endfor
                        </div>
                    </div>
                    @if($fb->comment)
                    <p style="font-size:.77rem;color:var(--ink-500);margin:0;line-height:1.45;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $fb->comment }}</p>
                    @endif
                </div>
                @empty
                <div style="padding:1.5rem;text-align:center;color:var(--ink-400);font-size:.8rem">
                    <i class="bi bi-star" style="font-size:1.5rem;display:block;margin-bottom:.5rem;color:var(--ink-300)"></i>
                    No reviews yet
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
@media (min-width: 768px) {
    .stats-grid { grid-template-columns: repeat(4, 1fr) !important; }
}
@media (min-width: 1024px) {
    .office-grid { grid-template-columns: 1fr 320px !important; }
}
</style>
@endpush
@endsection
