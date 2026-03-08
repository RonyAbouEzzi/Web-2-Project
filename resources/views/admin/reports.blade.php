@extends('layouts.app')
@section('title','Reports')
@section('page-title','Reports & Analytics')

@section('content')

{{-- Summary cards --}}
@php
    $totalRevenue  = $revenueByOffice->sum('revenue') ?? 0;
    $totalRequests = $requestsByStatus->sum();
    $completed     = $requestsByStatus->get('completed', 0);
    $rate          = $totalRequests > 0 ? round(($completed / $totalRequests) * 100) : 0;
@endphp
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem;margin-bottom:1.25rem" class="report-stats">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eff6ff;color:#2563eb"><i class="bi bi-file-earmark-check"></i></div>
        <div class="stat-val">{{ number_format($totalRequests) }}</div>
        <div class="stat-lbl">Total Requests</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-cash-coin"></i></div>
        <div class="stat-val">${{ number_format($totalRevenue, 0) }}</div>
        <div class="stat-lbl">Total Revenue</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fefce8;color:#ca8a04"><i class="bi bi-check-circle"></i></div>
        <div class="stat-val">{{ $rate }}%</div>
        <div class="stat-lbl">Completion Rate</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fdf4ff;color:#a21caf"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-val">{{ $requestsByStatus->get('pending', 0) }}</div>
        <div class="stat-lbl">Pending Now</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr;gap:1rem" class="reports-grid">

    {{-- Requests by Status --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Requests by Status</span></div>
        <div class="card-body">
            @php
                $statusLabels = [
                    'pending'            => 'Pending',
                    'in_review'          => 'In Review',
                    'missing_documents'  => 'Missing Docs',
                    'approved'           => 'Approved',
                    'rejected'           => 'Rejected',
                    'completed'          => 'Completed',
                ];
                $statusColors = [
                    'pending'            => '#f59e0b',
                    'in_review'          => '#3b82f6',
                    'missing_documents'  => '#ef4444',
                    'approved'           => '#22c55e',
                    'rejected'           => '#ef4444',
                    'completed'          => '#10b981',
                ];
                $maxCount = max(1, $requestsByStatus->max() ?? 1);
            @endphp
            @foreach($statusLabels as $key => $label)
            @php $count = $requestsByStatus->get($key, 0); @endphp
            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem">
                <div style="font-size:.78rem;font-weight:500;color:#374151;min-width:110px;flex-shrink:0">{{ $label }}</div>
                <div style="flex:1;height:22px;background:#f3f4f6;border-radius:99px;overflow:hidden">
                    <div style="height:100%;background:{{ $statusColors[$key] }};border-radius:99px;width:{{ $maxCount > 0 ? round(($count/$maxCount)*100) : 0 }}%;transition:width .5s ease"></div>
                </div>
                <div style="font-size:.78rem;font-weight:700;color:#374151;min-width:30px;text-align:right">{{ $count }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Requests per Office --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Requests per Office</span></div>
        <div class="card-body" style="padding:0 !important">
            <div class="table-wrap">
                <table class="table table-hover">
                    <thead><tr><th>Office</th><th>Municipality</th><th>Requests</th><th>Revenue</th></tr></thead>
                    <tbody>
                        @forelse($requestsByOffice as $office)
                        <tr>
                            <td style="font-weight:600;font-size:.82rem">{{ $office->name }}</td>
                            <td style="font-size:.78rem;color:#6b7280">{{ optional($office->municipality)->name ?? '—' }}</td>
                            <td>
                                <span style="background:#eff6ff;color:#2563eb;padding:.2rem .6rem;border-radius:20px;font-size:.72rem;font-weight:600">{{ $office->requests_count }}</span>
                            </td>
                            <td style="font-weight:600;font-size:.82rem">${{ number_format($revenueByOffice->firstWhere('id', $office->id)?->revenue ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:1.5rem;color:#9ca3af">No data yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Monthly Requests Chart --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Monthly Requests — {{ date('Y') }}</span></div>
        <div class="card-body">
            @php
                $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                $maxMonth = max(1, $monthlyRequests->max() ?? 1);
            @endphp
            <div style="display:flex;align-items:flex-end;gap:.4rem;height:120px;padding-bottom:.5rem">
                @foreach($months as $i => $m)
                @php $count = $monthlyRequests->get($i+1, 0); $h = max(4, round(($count/$maxMonth)*100)); @endphp
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:.3rem;height:100%">
                    <div style="flex:1;width:100%;display:flex;align-items:flex-end">
                        <div style="width:100%;height:{{ $h }}%;background:{{ ($i+1)==now()->month ? '#0052cc' : '#bfdbfe' }};border-radius:4px 4px 0 0;transition:height .5s ease" title="{{ $m }}: {{ $count }}"></div>
                    </div>
                    <div style="font-size:.6rem;color:#9ca3af;font-weight:500">{{ $m }}</div>
                </div>
                @endforeach
            </div>
            <div style="font-size:.7rem;color:#9ca3af;text-align:center;margin-top:.25rem">
                <span style="display:inline-flex;align-items:center;gap:.25rem">
                    <span style="width:10px;height:10px;border-radius:2px;background:#0052cc;display:inline-block"></span> Current month
                    <span style="width:10px;height:10px;border-radius:2px;background:#bfdbfe;display:inline-block;margin-left:.5rem"></span> Other months
                </span>
            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
@media(min-width:768px) {
    .report-stats  { grid-template-columns: repeat(4,1fr) !important; }
    .reports-grid  { grid-template-columns: 1fr 1fr !important; }
    .reports-grid .card:last-child { grid-column: 1 / -1; }
}
</style>
@endpush
@endsection
