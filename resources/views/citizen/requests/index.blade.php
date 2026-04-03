@extends('layouts.app')
@section('title', 'My Requests')
@section('page-title', 'My Requests')

@section('content')
@php
    $allRequests = auth()->user()->serviceRequests;
    $statusGroups = [
        ['key' => null, 'label' => 'All'],
        ['key' => 'pending', 'label' => 'Pending'],
        ['key' => 'in_review', 'label' => 'In Review'],
        ['key' => 'approved', 'label' => 'Approved'],
        ['key' => 'completed', 'label' => 'Done'],
    ];

    $baseFilterParams = request()->except(['status', 'page']);
@endphp

<div class="card citizen-reveal" data-citizen-reveal>
    <div class="card-body citizen-requests-filter-wrap">
        <form method="GET" class="citizen-requests-filter-grid">
            <div class="citizen-filter-input-wrap">
                <i class="bi bi-search citizen-filter-input-icon"></i>
                <input
                    type="text"
                    name="search"
                    class="form-control citizen-filter-input"
                    placeholder="Search by reference, service, or office..."
                    value="{{ request('search') }}"
                >
            </div>
            <select name="status" class="form-select citizen-filter-select">
                <option value="">All Statuses</option>
                @foreach(['pending','in_review','missing_documents','approved','rejected','completed'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
            <select name="payment_status" class="form-select citizen-filter-select">
                <option value="">All Payments</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            </select>
            <button type="submit" class="btn btn-primary citizen-filter-btn">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'payment_status']))
                <a href="{{ route('citizen.requests') }}" class="btn btn-outline-secondary citizen-filter-btn">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="citizen-request-chips citizen-reveal" data-citizen-reveal>
    @foreach($statusGroups as $group)
        @php
            $isActive = request('status') === $group['key'] || (is_null($group['key']) && !request()->filled('status'));
            $targetParams = is_null($group['key'])
                ? $baseFilterParams
                : array_merge($baseFilterParams, ['status' => $group['key']]);
            $count = is_null($group['key']) ? $allRequests->count() : $allRequests->where('status', $group['key'])->count();
        @endphp
        <a href="{{ route('citizen.requests', $targetParams) }}" class="citizen-request-chip {{ $isActive ? 'is-active' : '' }}">
            <span>{{ $group['label'] }}</span>
            <span class="citizen-request-chip-count">{{ $count }}</span>
        </a>
    @endforeach
</div>

<div class="card citizen-reveal" data-citizen-reveal>
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <div>
            <span class="card-title">All Requests</span>
            <div class="citizen-request-subtitle">Track progress, payments, and submission details</div>
        </div>
        <a href="{{ route('citizen.offices') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> New Request
        </a>
    </div>

    <div class="d-none d-md-block">
        <div class="table-responsive citizen-requests-table-wrap">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Service</th>
                        <th>Office</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td><code>{{ $req->reference_number }}</code></td>
                            <td class="citizen-request-service">{{ $req->service->name }}</td>
                            <td class="citizen-request-office">{{ $req->office->name }}</td>
                            <td><x-status-pill :status="$req->status" /></td>
                            <td><x-status-pill :status="$req->payment_status === 'paid' ? 'paid' : 'unpaid'" /></td>
                            <td class="citizen-request-date">{{ $req->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('citizen.requests.show', $req) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="citizen-requests-empty-cell">
                                <x-empty-state
                                    icon="bi-inbox"
                                    title="No requests found"
                                    message="Try changing filters or submit your first request."
                                    :action-url="route('citizen.offices')"
                                    action-label="Browse Services"
                                    class="py-2"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-md-none">
        @forelse($requests as $req)
            <a href="{{ route('citizen.requests.show', $req) }}" class="citizen-request-mobile-item">
                <div class="citizen-request-mobile-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div class="citizen-request-mobile-main">
                    <div class="citizen-request-mobile-title">{{ $req->service->name }}</div>
                    <div class="citizen-request-mobile-sub">{{ $req->office->name }}</div>
                    <code>{{ $req->reference_number }}</code>
                </div>
                <div class="citizen-request-mobile-status">
                    <x-status-pill :status="$req->status" />
                    @if($req->payment_status !== 'paid')
                        <x-status-pill status="unpaid" class="mt-1" />
                    @endif
                </div>
                <i class="bi bi-chevron-right citizen-request-mobile-arrow"></i>
            </a>
        @empty
            <div class="citizen-requests-empty-mobile">
                <x-empty-state
                    icon="bi-inbox"
                    title="No requests yet"
                    message="Start by browsing available municipal services."
                    :action-url="route('citizen.offices')"
                    action-label="Browse Services"
                />
            </div>
        @endforelse
    </div>

    @if($requests->hasPages())
        <div class="citizen-request-pagination">{{ $requests->links() }}</div>
    @endif
</div>
@endsection

@push('styles')
<style>
body.es-role-citizen .citizen-requests-filter-wrap {
    padding: 1rem;
}

body.es-role-citizen .citizen-requests-filter-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 12rem 10rem auto auto;
    gap: .62rem;
    align-items: center;
}

body.es-role-citizen .citizen-filter-input-wrap {
    position: relative;
}

body.es-role-citizen .citizen-filter-input-icon {
    position: absolute;
    left: .78rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
    font-size: .83rem;
    pointer-events: none;
}

body.es-role-citizen .citizen-filter-input {
    padding-left: 2.2rem;
}

body.es-role-citizen .citizen-filter-select,
body.es-role-citizen .citizen-filter-btn {
    height: 2.45rem;
}

body.es-role-citizen .citizen-request-chips {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    margin: 1rem 0;
}

body.es-role-citizen .citizen-request-chip {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .34rem .74rem;
    border-radius: 999px;
    border: 1px solid #BFDBFE;
    background: #F0F9FF;
    color: #0369A1;
    font-size: .74rem;
    font-weight: 700;
    text-decoration: none;
    transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
}

body.es-role-citizen .citizen-request-chip:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 16px rgba(14, 165, 233, 0.15);
    color: #0369A1;
}

body.es-role-citizen .citizen-request-chip.is-active {
    color: #0F172A;
    border-color: #93C5FD;
    background: linear-gradient(135deg, #DBEAFE 0%, #E0F2FE 100%);
}

body.es-role-citizen .citizen-request-chip-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 1.2rem;
    border-radius: 999px;
    padding: .05rem .38rem;
    font-size: .65rem;
    background: rgba(255, 255, 255, .72);
}

body.es-role-citizen .citizen-request-subtitle {
    margin-top: .18rem;
    font-size: .74rem;
    color: #64748B;
}

body.es-role-citizen .citizen-requests-table-wrap thead th {
    font-size: .68rem;
}

body.es-role-citizen .citizen-request-service {
    font-size: .84rem;
    font-weight: 600;
}

body.es-role-citizen .citizen-request-office,
body.es-role-citizen .citizen-request-date {
    font-size: .79rem;
    color: #64748B;
}

body.es-role-citizen .citizen-requests-empty-cell {
    padding: 1.8rem .8rem !important;
}

body.es-role-citizen .citizen-request-mobile-item {
    display: flex;
    align-items: center;
    gap: .72rem;
    padding: .82rem .92rem;
    border-bottom: 1px solid #E2E8F0;
    text-decoration: none;
    color: inherit;
    transition: background-color .15s ease;
}

body.es-role-citizen .citizen-request-mobile-item:hover {
    background: rgba(239, 246, 255, .58);
    color: inherit;
}

body.es-role-citizen .citizen-request-mobile-icon {
    width: 2.4rem;
    height: 2.4rem;
    border-radius: .7rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #E0F2FE;
    border: 1px solid #BAE6FD;
    color: #0369A1;
    flex-shrink: 0;
}

body.es-role-citizen .citizen-request-mobile-main {
    flex: 1;
    min-width: 0;
}

body.es-role-citizen .citizen-request-mobile-title {
    font-size: .83rem;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

body.es-role-citizen .citizen-request-mobile-sub {
    margin-top: .1rem;
    color: #64748B;
    font-size: .72rem;
}

body.es-role-citizen .citizen-request-mobile-main code {
    display: inline-block;
    margin-top: .2rem;
    font-size: .68rem;
}

body.es-role-citizen .citizen-request-mobile-status {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    flex-shrink: 0;
}

body.es-role-citizen .citizen-request-mobile-arrow {
    color: #CBD5E1;
    font-size: .82rem;
    flex-shrink: 0;
}

body.es-role-citizen .citizen-requests-empty-mobile {
    padding: 1.25rem .55rem 1.4rem;
}

body.es-role-citizen .citizen-request-pagination {
    border-top: 1px solid #E2E8F0;
    padding: .85rem 1rem;
}

@media (max-width: 1199.98px) {
    body.es-role-citizen .citizen-requests-filter-grid {
        grid-template-columns: minmax(0, 1fr) repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 767.98px) {
    body.es-role-citizen .citizen-requests-filter-grid {
        grid-template-columns: 1fr;
    }

    body.es-role-citizen .citizen-filter-select,
    body.es-role-citizen .citizen-filter-btn {
        width: 100%;
    }
}
</style>
@endpush
