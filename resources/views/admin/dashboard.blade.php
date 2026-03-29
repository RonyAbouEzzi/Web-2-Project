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

<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <x-card bodyClass="p-3">
            <div class="text-muted small">Total users</div>
            <div class="fs-3 fw-bold">{{ number_format($stats['total_users']) }}</div>
            <div class="small text-muted">Citizens registered</div>
        </x-card>
    </div>
    <div class="col-6 col-lg-3">
        <x-card bodyClass="p-3">
            <div class="text-muted small">Offices</div>
            <div class="fs-3 fw-bold">{{ number_format($stats['total_offices']) }}</div>
            <div class="small text-muted">Active municipal offices</div>
        </x-card>
    </div>
    <div class="col-6 col-lg-3">
        <x-card bodyClass="p-3">
            <div class="text-muted small">Requests</div>
            <div class="fs-3 fw-bold">{{ number_format($stats['total_requests']) }}</div>
            <div class="small text-muted">All submitted requests</div>
        </x-card>
    </div>
    <div class="col-6 col-lg-3">
        <x-card bodyClass="p-3">
            <div class="text-muted small">Revenue</div>
            <div class="fs-3 fw-bold">${{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="small text-muted">Collected via paid requests</div>
        </x-card>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
        <x-card title="Recent service requests" subtitle="Latest request activity across municipalities.">
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
                                    <td class="fw-semibold">{{ $request->reference_number }}</td>
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
                <div class="text-muted small">No request records available yet.</div>
            @endif
        </x-card>
    </div>

    <div class="col-12 col-xl-4">
        <x-card title="Top offices by volume" subtitle="Highest request counts.">
            @if($officeStats->count())
                <div class="d-flex flex-column gap-2">
                    @foreach($officeStats as $office)
                        <div class="border rounded-3 p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $office->name }}</div>
                                <div class="small text-muted">{{ $office->municipality->name ?? 'Municipality' }}</div>
                            </div>
                            <span class="badge rounded-pill bg-info-subtle border border-info-subtle">{{ $office->requests_count }} req</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-muted small">No office statistics to display.</div>
            @endif
        </x-card>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <x-card title="Monthly requests chart" subtitle="Current year request trend.">
            <canvas id="monthlyRequestsChart" height="95"></canvas>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const canvas = document.getElementById('monthlyRequestsChart');
        if (!canvas || typeof Chart === 'undefined') return;

        const ctx = canvas.getContext('2d');
        const labels = @json($chartLabels);
        const values = @json($chartValues);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Requests',
                    data: values,
                    borderColor: '#0D9488',
                    backgroundColor: 'rgba(13, 148, 136, .14)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.32,
                    pointRadius: 3,
                    pointHoverRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#78716c', font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#78716c',
                            font: { size: 11 }
                        },
                        grid: { color: '#f0efee' }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    })();
</script>
@endpush
