@extends('layouts.app')
@section('title','Admin Dashboard')
@section('page-title','Dashboard')

@section('content')

{{-- ── Hero Banner ─────────────────────────────────────── --}}
<div class="hero-banner" style="margin-bottom:1.4rem">
    <div class="hero-banner-content" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
        <div>
            <div style="color:rgba(255,255,255,.38);font-size:.68rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.25rem">
                {{ now()->format('l, F j, Y') }}
            </div>
            <h1 style="font-family:var(--font-disp);font-style:italic;font-size:1.5rem;font-weight:700;color:#fff;margin:0;letter-spacing:-.03em">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}
            </h1>
            <p style="color:rgba(255,255,255,.4);font-size:.79rem;margin:.25rem 0 0;line-height:1.5">
                Here's what's happening across the platform today.
            </p>
        </div>
        <div style="display:flex;gap:.5rem;flex-shrink:0">
            <a href="{{ route('admin.reports') }}" class="btn btn-sm" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);color:rgba(255,255,255,.8)">
                <i class="bi bi-bar-chart-line"></i> Reports
            </a>
            <a href="{{ route('admin.offices') }}" class="btn btn-sm" style="background:var(--gold);border:none;color:#fff;font-weight:700">
                <i class="bi bi-plus-lg"></i> New Office
            </a>
        </div>
    </div>
</div>

{{-- ── Stat Cards ──────────────────────────────────────── --}}
@php
    $pct = $stats['total_requests'] > 0 ? round(($stats['pending_requests']/$stats['total_requests'])*100) : 0;
@endphp
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.85rem;margin-bottom:1.4rem" class="stats-grid">
    <div class="stat-card" style="--stat-tint:var(--navy-50)">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="stat-icon" style="background:var(--navy-50);color:var(--primary)"><i class="bi bi-people-fill"></i></div>
            <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i> Citizens</span>
        </div>
        <div class="stat-val">{{ number_format($stats['total_users']) }}</div>
        <div class="stat-lbl">Registered Users</div>
    </div>
    <div class="stat-card" style="--stat-tint:var(--green-lt)">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="stat-icon" style="background:var(--green-lt);color:var(--green)"><i class="bi bi-building-fill"></i></div>
            <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i> Active</span>
        </div>
        <div class="stat-val">{{ $stats['total_offices'] }}</div>
        <div class="stat-lbl">Government Offices</div>
    </div>
    <div class="stat-card" style="--stat-tint:var(--amber-lt)">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="stat-icon" style="background:var(--amber-lt);color:var(--amber)"><i class="bi bi-hourglass-split"></i></div>
            <span class="stat-delta {{ $pct > 30 ? 'dn' : 'neutral' }}">{{ $pct }}% of total</span>
        </div>
        <div class="stat-val">{{ number_format($stats['pending_requests']) }}</div>
        <div class="stat-lbl">Awaiting Review</div>
    </div>
    <div class="stat-card" style="--stat-tint:var(--gold-100)">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="stat-icon" style="background:var(--gold-100);color:var(--gold-600)"><i class="bi bi-cash-coin"></i></div>
            <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i> Revenue</span>
        </div>
        <div class="stat-val">${{ number_format($stats['total_revenue'], 0) }}</div>
        <div class="stat-lbl">Total Collected</div>
    </div>
</div>

{{-- ── Main grid ────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr;gap:1.1rem" class="dash-grid">

    {{-- Recent requests table --}}
    <div class="card">
        <div class="card-hd">
            <span class="card-hd-title">Recent Service Requests</span>
            <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-graph-up"></i> Full Report
            </a>
        </div>
        <div class="card-body p0">
            <div class="table-wrap">
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th>Reference</th>
                        <th>Citizen</th>
                        <th class="hide-mob">Service</th>
                        <th>Status</th>
                        <th class="hide-mob">Submitted</th>
                    </tr></thead>
                    <tbody>
                    @forelse($recentRequests as $req)
                    <tr>
                        <td><code>{{ $req->reference_number }}</code></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.55rem">
                                <div style="width:28px;height:28px;border-radius:50%;background:var(--primary-lt);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:.64rem;font-weight:700;flex-shrink:0">
                                    {{ strtoupper(substr($req->citizen->name,0,1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:.82rem;line-height:1.2">{{ $req->citizen->name }}</div>
                                    <div class="d-md-none" style="font-size:.69rem;color:var(--ink-400)">{{ $req->office->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="hide-mob" style="font-size:.8rem;color:var(--ink-500)">
                            <div style="font-size:.8rem;font-weight:500">{{ $req->service->name }}</div>
                            <div style="font-size:.72rem;color:var(--ink-400)">{{ $req->office->name }}</div>
                        </td>
                        <td><span class="sbadge s-{{ $req->status }}">{{ ucfirst(str_replace('_',' ',$req->status)) }}</span></td>
                        <td class="hide-mob" style="font-size:.76rem;color:var(--ink-400)">{{ $req->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5">
                        <div class="empty-state" style="padding:3rem 1rem">
                            <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                            <h4>No requests yet</h4>
                            <p>Service requests will appear here once citizens start submitting.</p>
                        </div>
                    </td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Bottom 2-col --}}
    <div style="display:grid;grid-template-columns:1fr;gap:1.1rem" class="bot-row">

        {{-- Top offices --}}
        <div class="card">
            <div class="card-hd">
                <span class="card-hd-title">Top Offices by Volume</span>
                <a href="{{ route('admin.offices') }}" class="btn btn-sm btn-ghost">Manage</a>
            </div>
            <div class="card-body">
                @forelse($officeStats as $i => $office)
                @php $colors = ['#1E4080','#0D7A4E','#B45309','#6D28D9','#0E7490']; $bgs = ['var(--primary-lt)','var(--green-lt)','var(--amber-lt)','var(--violet-lt)','var(--cyan-lt)']; @endphp
                <div style="display:flex;align-items:center;gap:.85rem;{{ !$loop->last ? 'margin-bottom:1rem' : '' }}">
                    <div style="width:32px;height:32px;border-radius:9px;background:{{ $bgs[$i%5] }};color:{{ $colors[$i%5] }};display:flex;align-items:center;justify-content:center;font-family:var(--font-disp);font-style:italic;font-size:.8rem;font-weight:700;flex-shrink:0">{{ $i+1 }}</div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.82rem;font-weight:600;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $office->name }}</div>
                        <div class="prog-track">
                            <div class="prog-bar" style="width:{{ $officeStats->first()->requests_count > 0 ? round(($office->requests_count/$officeStats->first()->requests_count)*100) : 0 }}%;background:{{ $colors[$i%5] }}"></div>
                        </div>
                    </div>
                    <div style="font-size:.82rem;font-weight:700;color:var(--ink-600);min-width:24px;text-align:right;flex-shrink:0">{{ $office->requests_count }}</div>
                </div>
                @empty
                <div class="empty-state" style="padding:1.5rem 0">
                    <p>No office data available yet.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="card">
            <div class="card-hd"><span class="card-hd-title">Quick Actions</span></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:.6rem">
                @foreach([
                    ['route'=>'admin.municipalities','icon'=>'bi-geo-alt-fill','bg'=>'var(--navy-50)','ic'=>'var(--primary)','title'=>'Add Municipality','sub'=>'Create a new administrative district'],
                    ['route'=>'admin.offices','icon'=>'bi-building-add','bg'=>'var(--green-lt)','ic'=>'var(--green)','title'=>'Register Office','sub'=>'Add a government service office'],
                    ['route'=>'admin.users','icon'=>'bi-person-add','bg'=>'var(--amber-lt)','ic'=>'var(--amber)','title'=>'Create Staff Account','sub'=>'Assign new office managers'],
                    ['route'=>'admin.reports','icon'=>'bi-bar-chart-line','bg'=>'var(--violet-lt)','ic'=>'var(--violet)','title'=>'View Reports','sub'=>'Full analytics & statistics'],
                ] as $action)
                <a href="{{ route($action['route']) }}"
                   style="display:flex;align-items:center;gap:.8rem;padding:.8rem .95rem;border-radius:var(--r-sm);border:1.5px solid var(--ink-200);text-decoration:none;color:inherit;transition:all .18s;background:var(--white)"
                   onmouseover="this.style.borderColor='{{ $action['ic'] }}';this.style.background='{{ $action['bg'] }}'"
                   onmouseout="this.style.borderColor='var(--ink-200)';this.style.background='var(--white)'">
                    <div style="width:36px;height:36px;border-radius:9px;background:{{ $action['bg'] }};color:{{ $action['ic'] }};display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0">
                        <i class="bi {{ $action['icon'] }}"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.82rem;font-weight:600;color:var(--ink-800)">{{ $action['title'] }}</div>
                        <div style="font-size:.71rem;color:var(--ink-400)">{{ $action['sub'] }}</div>
                    </div>
                    <i class="bi bi-chevron-right" style="color:var(--ink-200);font-size:.78rem;flex-shrink:0"></i>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media(min-width:640px)  { .stats-grid { grid-template-columns: repeat(2,1fr) !important; } }
@media(min-width:1024px) { .stats-grid { grid-template-columns: repeat(4,1fr) !important; } }
@media(min-width:1100px) {
    .dash-grid { grid-template-columns: 1fr 400px !important; }
    .dash-grid > .card:first-child { grid-row: 1 / 3; }
    .bot-row { grid-template-columns: 1fr !important; }
}
</style>
@endpush
@endsection
