@extends('layouts.app')
@section('title', 'My Requests')
@section('page-title', 'My Requests')

@section('content')

{{-- Filter bar --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" style="display:flex;flex-wrap:wrap;gap:.6rem;align-items:flex-end">
            <div style="flex:1;min-width:160px;position:relative">
                <i class="bi bi-search" style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none;font-size:.85rem"></i>
                <input type="text" name="search" class="form-control" style="padding-left:2.3rem"
                       placeholder="Search by reference..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-select" style="min-width:160px">
                <option value="">All Statuses</option>
                @foreach(['pending','in_review','missing_documents','approved','rejected','completed'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $s)) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('citizen.requests') }}" class="btn" style="background:#f3f4f6;border:none;color:#374151">Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- Status summary pills --}}
@php
    $allRequests = auth()->user()->serviceRequests;
    $statusGroups = [
        ['key'=>'pending',   'label'=>'Pending',   'color'=>'#d97706','bg'=>'#fffbeb'],
        ['key'=>'in_review', 'label'=>'In Review', 'color'=>'#2563eb','bg'=>'#eff6ff'],
        ['key'=>'approved',  'label'=>'Approved',  'color'=>'#16a34a','bg'=>'#f0fdf4'],
        ['key'=>'completed', 'label'=>'Done',       'color'=>'#065f46','bg'=>'#d1fae5'],
    ];
@endphp
<div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1rem">
    @foreach($statusGroups as $sg)
    <a href="{{ route('citizen.requests', ['status'=>$sg['key']]) }}"
       style="display:flex;align-items:center;gap:.4rem;padding:.35rem .8rem;border-radius:20px;font-size:.75rem;font-weight:600;text-decoration:none;background:{{ request('status')===$sg['key'] ? $sg['color'] : $sg['bg'] }};color:{{ request('status')===$sg['key'] ? '#fff' : $sg['color'] }};border:1.5px solid {{ $sg['color'] }}20;transition:all .15s">
        {{ $sg['label'] }}
        <span style="background:{{ request('status')===$sg['key'] ? 'rgba(255,255,255,.25)' : $sg['color'].'20' }};padding:.05rem .35rem;border-radius:10px;font-size:.68rem">
            {{ $allRequests->where('status',$sg['key'])->count() }}
        </span>
    </a>
    @endforeach
</div>

{{-- Requests list --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">All Requests</span>
        <a href="{{ route('citizen.offices') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> New Request
        </a>
    </div>

    {{-- Desktop table --}}
    <div class="d-none d-md-block">
        <div class="table-wrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Service</th>
                        <th>Office</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                    <tr>
                        <td><code>{{ $req->reference_number }}</code></td>
                        <td style="font-weight:600;font-size:.83rem">{{ $req->service->name }}</td>
                        <td style="color:#6b7280;font-size:.8rem">{{ $req->office->name }}</td>
                        <td><span class="sbadge s-{{ $req->status }}">{{ ucfirst(str_replace('_',' ',$req->status)) }}</span></td>
                        <td><span class="sbadge s-{{ $req->payment_status }}">{{ ucfirst($req->payment_status) }}</span></td>
                        <td style="color:#9ca3af;font-size:.78rem">{{ $req->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('citizen.requests.show', $req) }}"
                               class="btn btn-sm" style="background:var(--primary-light);color:var(--primary);border:none">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:3rem;color:#9ca3af">
                            <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:.75rem;color:#d1d5db"></i>
                            <div style="font-weight:600;margin-bottom:.3rem">No requests found</div>
                            <a href="{{ route('citizen.offices') }}" class="btn btn-primary btn-sm">Browse Services</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile card list --}}
    <div class="d-md-none">
        @forelse($requests as $req)
        <a href="{{ route('citizen.requests.show', $req) }}"
           style="display:flex;align-items:center;gap:.85rem;padding:.9rem 1rem;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;transition:background .12s"
           onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
            <div style="width:40px;height:40px;border-radius:10px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-weight:700;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $req->service->name }}</div>
                <div style="font-size:.72rem;color:#9ca3af">{{ $req->office->name }}</div>
                <code style="font-size:.68rem">{{ $req->reference_number }}</code>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.3rem;flex-shrink:0">
                <span class="sbadge s-{{ $req->status }}">{{ ucfirst(str_replace('_',' ',$req->status)) }}</span>
                @if($req->payment_status !== 'paid')
                    <span class="sbadge s-unpaid" style="font-size:.64rem">Unpaid</span>
                @endif
            </div>
            <i class="bi bi-chevron-right" style="color:#d1d5db;font-size:.8rem;flex-shrink:0"></i>
        </a>
        @empty
        <div style="text-align:center;padding:3rem 1rem;color:#9ca3af">
            <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:.75rem;color:#d1d5db"></i>
            <div style="font-weight:600;margin-bottom:.3rem">No requests yet</div>
            <a href="{{ route('citizen.offices') }}" class="btn btn-primary btn-sm">Browse Services</a>
        </div>
        @endforelse
    </div>

    @if($requests->hasPages())
    <div style="padding:.75rem 1rem;border-top:1px solid #f3f4f6">{{ $requests->links() }}</div>
    @endif
</div>
@endsection
