@extends('layouts.app')
@section('title', 'Requests')
@section('page-title', 'Incoming Requests')

@section('content')

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" style="display:flex;flex-wrap:wrap;gap:.6rem;align-items:flex-end">
            <div style="flex:1;min-width:180px">
                <label class="form-label">Search</label>
                <div style="position:relative">
                    <i class="bi bi-search" style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.85rem;pointer-events:none"></i>
                    <input type="text" name="search" class="form-control" style="padding-left:2.3rem"
                           placeholder="Reference or citizen name..." value="{{ request('search') }}">
                </div>
            </div>
            <div style="min-width:160px">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['pending','in_review','missing_documents','approved','rejected','completed'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_',' ',$s)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:auto"><i class="bi bi-funnel"></i> Filter</button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('office.requests') }}" class="btn" style="background:#f3f4f6;border:none;color:#374151;margin-top:auto">Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- Requests --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Requests</span>
        <span style="font-size:.75rem;color:#9ca3af">{{ $requests->total() }} total</span>
    </div>

    {{-- Desktop table --}}
    <div class="d-none d-md-block">
        <div class="table-wrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Citizen</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                    <tr>
                        <td><code>{{ $req->reference_number }}</code></td>
                        <td>
                            <div style="font-weight:600">{{ $req->citizen->name }}</div>
                            <div style="font-size:.72rem;color:#9ca3af">{{ $req->citizen->email }}</div>
                        </td>
                        <td style="color:#6b7280">{{ $req->service->name }}</td>
                        <td><span class="sbadge s-{{ $req->status }}">{{ ucfirst(str_replace('_',' ',$req->status)) }}</span></td>
                        <td><span class="sbadge s-{{ $req->payment_status }}">{{ ucfirst($req->payment_status) }}</span></td>
                        <td style="color:#9ca3af;font-size:.78rem">{{ $req->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('office.requests.show', $req) }}" class="btn btn-sm" style="background:var(--primary-light);color:var(--primary);border:none">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:2.5rem;color:#9ca3af">No requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile card list --}}
    <div class="d-md-none">
        @forelse($requests as $req)
        <a href="{{ route('office.requests.show', $req) }}" style="display:block;padding:.9rem 1rem;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;transition:background .1s" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.35rem">
                <div style="font-size:.83rem;font-weight:700;color:#111827">{{ $req->citizen->name }}</div>
                <span class="sbadge s-{{ $req->status }}">{{ ucfirst(str_replace('_',' ',$req->status)) }}</span>
            </div>
            <div style="font-size:.75rem;color:#6b7280;margin-bottom:.25rem">{{ $req->service->name }}</div>
            <div style="display:flex;justify-content:space-between;align-items:center">
                <code style="font-size:.68rem">{{ $req->reference_number }}</code>
                <span class="sbadge s-{{ $req->payment_status }}" style="font-size:.65rem">{{ ucfirst($req->payment_status) }}</span>
            </div>
        </a>
        @empty
        <div style="text-align:center;padding:3rem 1rem;color:#9ca3af">
            <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:.75rem;color:#d1d5db"></i>
            No requests found.
        </div>
        @endforelse
    </div>

    @if($requests->hasPages())
    <div style="padding:.75rem 1rem;border-top:1px solid #f3f4f6">{{ $requests->links() }}</div>
    @endif
</div>
@endsection
