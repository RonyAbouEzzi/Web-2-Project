@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
@php
    $monthlyRaw = \App\Models\ServiceRequest::selectRaw('MONTH(created_at) as month_num, COUNT(*) as total')
        ->whereYear('created_at', now()->year)
        ->groupBy('month_num')
        ->orderBy('month_num')
        ->pluck('total', 'month_num');

    $chartLabels = [];
    $chartValues = [];

    for ($m = 1; $m <= 12; $m++) {
        $chartLabels[] = \Carbon\Carbon::create()->month($m)->format('M');
        $chartValues[] = (int) ($monthlyRaw[$m] ?? 0);
    }
@endphp

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted stat-label mb-1">Total Users</span>
                        <h3 class="mb-1 stat-value">{{ number_format($stats['total_users']) }}</h3>
                        <span class="text-muted stat-sub">Registered citizens</span>
                    </div>
                    <span class="stat-card-icon bg-teal"><i class="bi bi-people"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted stat-label mb-1">Offices</span>
                        <h3 class="mb-1 stat-value">{{ number_format($stats['total_offices']) }}</h3>
                        <span class="text-muted stat-sub">Active municipal offices</span>
                    </div>
                    <span class="stat-card-icon bg-sky"><i class="bi bi-buildings"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted stat-label mb-1">Requests</span>
                        <h3 class="mb-1 stat-value">{{ number_format($stats['total_requests']) }}</h3>
                        <span class="text-muted stat-sub">All submitted requests</span>
                    </div>
                    <span class="stat-card-icon bg-amber"><i class="bi bi-file-earmark-text"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted stat-label mb-1">Revenue</span>
                        <h3 class="mb-1 stat-value">${{ number_format($stats['total_revenue'], 0) }}</h3>
                        <span class="text-muted stat-sub">Collected from paid requests</span>
                    </div>
                    <span class="stat-card-icon bg-emerald"><i class="bi bi-currency-dollar"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Requests + Top Offices --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title">Recent Service Requests</h6>
                    <small class="text-muted">Latest request activity across municipalities</small>
                </div>
            </div>
            <div class="card-body p-0">
                @if($recentRequests->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Citizen</th>
                                    <th>Office</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $request)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $request->reference_number }}</span></td>
                                        <td>{{ $request->citizen->name }}</td>
                                        <td>{{ $request->office->name }}</td>
                                        <td>{{ $request->service->name }}</td>
                                        <td><x-status-pill :status="$request->status" /></td>
                                        <td class="text-muted">{{ $request->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-muted text-center text-md">No request records available yet.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title">Top Offices by Volume</h6>
                <small class="text-muted">Highest request counts</small>
            </div>
            <div class="card-body">
                @if($officeStats->count())
                    <div class="d-flex flex-column gap-2">
                        @foreach($officeStats as $office)
                            <div class="d-flex justify-content-between align-items-center border rounded-3 p-3">
                                <div>
                                    <div class="fw-semibold text-md">{{ $office->name }}</div>
                                    <div class="text-muted text-xs">{{ $office->municipality->name ?? 'Municipality' }}</div>
                                </div>
                                <span class="badge rounded-pill bg-info-subtle border border-info-subtle">{{ $office->requests_count }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted text-center text-md">No office statistics to display.</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Monthly Chart --}}
<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Monthly Requests</h6>
                <small class="text-muted">{{ now()->year }} request trend</small>
            </div>
            <div class="card-body">
                <canvas id="monthlyRequestsChart" height="85"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const canvas = document.getElementById('monthlyRequestsChart');
        if (!canvas || typeof Chart === 'undefined') return;

        new Chart(canvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Requests',
                    data: @json($chartValues),
                    borderColor: '#0D9488',
                    backgroundColor: 'rgba(13, 148, 136, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointBackgroundColor: '#0D9488',
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#78716c', font: { size: 11, family: 'Plus Jakarta Sans' } } },
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#78716c', font: { size: 11, family: 'Plus Jakarta Sans' } }, grid: { color: '#f0efee' } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#292524', titleFont: { family: 'Plus Jakarta Sans' }, bodyFont: { family: 'Plus Jakarta Sans' } }
                }
            }
        });
    })();
</script>
@endpush
