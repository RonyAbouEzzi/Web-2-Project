@extends('layouts.app')
@section('title','My Dashboard')
@section('page-title','Dashboard')

@section('content')

@php
    $user    = auth()->user();
    $allReqs = $user->serviceRequests;
    $statusCounts = [
        ['label'=>'Pending',   'key'=>'pending',    'color'=>'var(--amber)',   'bg'=>'var(--amber-lt)',   'icon'=>'bi-hourglass-split'],
        ['label'=>'In Review', 'key'=>'in_review',  'color'=>'var(--primary)', 'bg'=>'var(--blue-50)',    'icon'=>'bi-search'],
        ['label'=>'Completed', 'key'=>'completed',  'color'=>'var(--emerald)', 'bg'=>'var(--emerald-lt)', 'icon'=>'bi-check-circle-fill'],
        ['label'=>'Rejected',  'key'=>'rejected',   'color'=>'var(--rose)',    'bg'=>'var(--rose-lt)',    'icon'=>'bi-x-circle'],
    ];
@endphp

@if(!$user->hasCompletedCitizenProfile())
<div class="card" style="margin-bottom:1rem;border-color:#F59E0B;background:#FFFBEB">
    <div class="card-body" style="display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;flex-wrap:wrap">
        <div>
            <div style="font-size:.86rem;font-weight:700;color:#92400E;margin-bottom:.2rem">
                Complete your profile to submit new requests
            </div>
            <div style="font-size:.78rem;color:#B45309">
                Missing: {{ implode(', ', $user->missingCitizenProfileFields()) }}.
            </div>
        </div>
        <a href="{{ route('citizen.profile') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-person-gear"></i> Complete Profile
        </a>
    </div>
</div>
@endif

{{-- Welcome banner --}}
<div class="hero-banner" style="margin-bottom:1.25rem">
    <div style="position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
        <div>
            <div style="color:rgba(255,255,255,.45);font-size:.73rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;margin-bottom:.2rem">
                {{ now()->format('l, F j') }}
            </div>
            <h1 style="font-family:var(--font-disp);font-size:1.4rem;font-weight:800;color:#fff;margin:0;letter-spacing:-.03em">
                Hi, {{ explode(' ', $user->name)[0] }} 👋
            </h1>
            <p style="color:rgba(255,255,255,.5);font-size:.8rem;margin:.25rem 0 0">
                @if($allReqs->whereIn('status',['pending','in_review'])->count() > 0)
                    You have {{ $allReqs->whereIn('status',['pending','in_review'])->count() }} active request(s) in progress.
                @else
                    Everything is up to date. Start a new request below.
                @endif
            </p>
        </div>
        <a href="{{ route('citizen.offices') }}" class="btn btn-sm" style="background:rgba(255,255,255,.9);border:none;color:#1A56DB;flex-shrink:0">
            <i class="bi bi-plus-circle-fill"></i> New Request
        </a>
    </div>
</div>

{{-- Status cards --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.7rem;margin-bottom:1.25rem" class="status-grid">
    @foreach($statusCounts as $sc)
    @php $cnt = $allReqs->where('status',$sc['key'])->count(); @endphp
    <a href="{{ route('citizen.requests') }}?status={{ $sc['key'] }}"
       style="background:var(--white);border:1.5px solid var(--ink-200);border-radius:var(--radius);padding:1rem;text-decoration:none;transition:all .2s;display:flex;align-items:center;gap:.85rem;box-shadow:var(--shadow-sm)"
       onmouseover="this.style.borderColor='{{ $sc['color'] }}';this.style.transform='translateY(-2px)';this.style.boxShadow='var(--shadow-md)'"
       onmouseout="this.style.borderColor='var(--ink-200)';this.style.transform='';this.style.boxShadow='var(--shadow-sm)'">
        <div style="width:42px;height:42px;border-radius:11px;background:{{ $sc['bg'] }};color:{{ $sc['color'] }};display:flex;align-items:center;justify-content:center;font-size:1.05rem;flex-shrink:0">
            <i class="bi {{ $sc['icon'] }}"></i>
        </div>
        <div>
            <div style="font-family:var(--font-disp);font-size:1.45rem;font-weight:800;color:var(--ink-900);letter-spacing:-.04em;line-height:1">{{ $cnt }}</div>
            <div style="font-size:.72rem;color:var(--ink-400);font-weight:500;margin-top:1px">{{ $sc['label'] }}</div>
        </div>
    </a>
    @endforeach
</div>

{{-- My Requests list --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">My Recent Requests</span>
        <div style="display:flex;gap:.5rem">
            <a href="{{ route('citizen.requests') }}" class="btn btn-sm btn-ghost">View All</a>
            <a href="{{ route('citizen.offices') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> New</a>
        </div>
    </div>
    <div class="card-body p0">
        @forelse($requests as $req)
        <a href="{{ route('citizen.requests.show', $req) }}"
           style="display:flex;align-items:center;gap:.85rem;padding:.95rem 1.2rem;border-bottom:1px solid var(--ink-100);text-decoration:none;color:inherit;transition:background .12s"
           onmouseover="this.style.background='var(--ink-50)'" onmouseout="this.style.background='transparent'">
            {{-- Service icon --}}
            <div style="width:42px;height:42px;border-radius:11px;background:var(--primary-lt);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            {{-- Info --}}
            <div style="flex:1;min-width:0">
                <div style="font-size:.85rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--ink-800)">{{ $req->service->name }}</div>
                <div style="font-size:.73rem;color:var(--ink-400);margin-top:2px">
                    {{ $req->office->name }}
                    <span style="margin:0 .3rem;color:var(--ink-200)">·</span>
                    <code style="font-size:.68rem;background:transparent;padding:0;color:var(--ink-400)">{{ $req->reference_number }}</code>
                    <span style="margin:0 .3rem;color:var(--ink-200)">·</span>
                    {{ $req->created_at->diffForHumans() }}
                </div>
            </div>
            {{-- Status + payment --}}
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.3rem;flex-shrink:0">
                <span class="sbadge s-{{ $req->status }}">{{ ucfirst(str_replace('_',' ',$req->status)) }}</span>
                @if($req->payment_status !== 'paid')
                    <span class="sbadge" style="background:var(--rose-lt);color:#9F1239;font-size:.63rem">
                        <i class="bi bi-credit-card" style="margin-right:.2rem"></i>Unpaid
                    </span>
                @endif
            </div>
            <i class="bi bi-chevron-right" style="color:var(--ink-200);font-size:.8rem;flex-shrink:0"></i>
        </a>
        @empty
        <div class="empty-state" style="padding:3rem 1.5rem">
            <div class="empty-icon"><i class="bi bi-folder-plus"></i></div>
            <h4>No requests yet</h4>
            <p>Browse government offices and submit your first service request.</p>
            <a href="{{ route('citizen.offices') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-building"></i> Browse Offices
            </a>
        </div>
        @endforelse
        @if($requests->hasPages())
        <div style="padding:.75rem 1.2rem;border-top:1px solid var(--ink-100)">{{ $requests->links() }}</div>
        @endif
    </div>
</div>

@push('styles')
<style>
@media (min-width: 640px) { .status-grid { grid-template-columns: repeat(4,1fr) !important; } }
</style>
@endpush
@endsection
