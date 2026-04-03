@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@push('styles')
<style>
    .admin-dashboard-filters {
        border: 1px solid var(--es-border-soft);
        border-radius: .85rem;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.88) 0%, rgba(255, 255, 255, 0.96) 100%);
        margin-bottom: 1rem;
    }
    .admin-dashboard-filters .card-body {
        padding: .9rem 1rem;
    }
    .admin-filter-grid {
        display: grid;
        grid-template-columns: 1.25fr 1fr 1fr auto;
        gap: .65rem;
        align-items: end;
    }
    .admin-filter-actions {
        display: flex;
        gap: .5rem;
        align-items: center;
    }
    .admin-filter-label {
        font-size: .72rem;
        color: var(--es-muted);
        font-weight: 600;
        margin-bottom: .26rem;
    }
    .admin-filter-badge {
        display: inline-flex;
        align-items: center;
        gap: .24rem;
        background: var(--es-primary-s);
        color: var(--es-primary);
        border: 1px solid var(--es-primary-m);
        border-radius: 999px;
        padding: .18rem .52rem;
        font-size: .66rem;
        font-weight: 700;
        margin-left: .45rem;
    }
    .admin-kpi-card {
        position: relative;
        overflow: hidden;
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    }
    .admin-kpi-card::before {
        content: '';
        position: absolute;
        inset: 0 auto auto 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--es-primary) 0%, color-mix(in srgb, var(--es-primary) 45%, #ffffff 55%) 100%);
        opacity: .24;
        transition: opacity .22s ease;
    }
    .admin-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, .08);
        border-color: color-mix(in srgb, var(--es-primary) 28%, var(--es-border) 72%);
    }
    .admin-kpi-card:hover::before {
        opacity: .8;
    }
    .admin-list-item {
        transition: border-color .18s ease, background-color .18s ease, transform .18s ease;
    }
    .admin-list-item:hover {
        border-color: color-mix(in srgb, var(--es-primary) 28%, var(--es-border) 72%) !important;
        background: color-mix(in srgb, var(--es-primary-s) 42%, #ffffff 58%);
        transform: translateY(-1px);
    }
    .admin-list-item-main {
        font-weight: 700;
        font-size: .84rem;
        color: #1F2A3D;
    }
    .admin-list-item-sub {
        color: var(--es-muted);
        font-size: .72rem;
        margin-top: .12rem;
    }
    .admin-count-badge {
        background: var(--es-primary-s) !important;
        color: var(--es-primary) !important;
        border-color: var(--es-primary-m) !important;
        min-width: 28px;
        text-align: center;
    }
    .admin-chart-wrap {
        position: relative;
        min-height: 250px;
    }
    .admin-chart-wrap canvas {
        height: 250px !important;
    }
    .admin-table-tight td,
    .admin-table-tight th {
        padding-top: .62rem;
        padding-bottom: .62rem;
    }
    .admin-card-meta {
        font-size: .72rem;
        color: var(--es-muted);
        font-weight: 500;
    }
    .admin-empty-state .btn {
        margin-top: .75rem;
    }
    .admin-dashboard-live {
        position: relative;
        transition: opacity .22s ease;
    }
    .admin-dashboard-live.is-loading {
        opacity: .58;
        pointer-events: none;
    }
    .admin-dashboard-live.is-loading::before {
        content: '';
        position: absolute;
        inset: 0;
        z-index: 19;
        background: rgba(255, 255, 255, .3);
        backdrop-filter: blur(1px);
        -webkit-backdrop-filter: blur(1px);
    }
    .admin-dashboard-live.is-loading::after {
        content: '';
        position: absolute;
        top: 1rem;
        left: 1rem;
        width: min(18rem, calc(100% - 2rem));
        height: .82rem;
        border-radius: .45rem;
        background: linear-gradient(90deg, rgba(148, 163, 184, .2) 0%, rgba(148, 163, 184, .45) 50%, rgba(148, 163, 184, .2) 100%);
        z-index: 20;
        animation: adminBusyShimmer 1.05s ease-in-out infinite;
    }
    @media (max-width: 1199.98px) {
        .admin-filter-grid {
            grid-template-columns: 1fr 1fr;
        }
        .admin-filter-actions {
            grid-column: 1 / -1;
            justify-content: flex-start;
        }
    }
    @media (max-width: 767.98px) {
        .admin-filter-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div id="adminDashboardLiveRoot" class="admin-dashboard-live admin-busy-target">
<x-admin.page-header
    title="Admin Overview"
    :subtitle="'Live operational summary for ' . strtolower($dashboardFilters['period_label']) . '.'">
    <x-slot:actions>
        @if($dashboardFilters['is_scoped'])
            <span class="admin-filter-badge"><i class="bi bi-funnel"></i> Filtered</span>
        @endif
    </x-slot:actions>
</x-admin.page-header>

<div class="card admin-dashboard-filters admin-reveal">
    <div class="card-body">
        <form
            id="adminDashboardFilters"
            method="GET"
            action="{{ route('admin.dashboard') }}"
            class="admin-filter-grid"
            data-admin-busy-target="#adminDashboardLiveRoot">
            <div>
                <label class="admin-filter-label" for="dashboardPeriod">Date Range</label>
                <select id="dashboardPeriod" name="period" class="form-select form-select-sm">
                    <option value="this_month" {{ $dashboardFilters['period'] === 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_30" {{ $dashboardFilters['period'] === 'last_30' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="last_90" {{ $dashboardFilters['period'] === 'last_90' ? 'selected' : '' }}>Last 90 Days</option>
                    <option value="this_year" {{ $dashboardFilters['period'] === 'this_year' ? 'selected' : '' }}>This Year</option>
                    <option value="all" {{ $dashboardFilters['period'] === 'all' ? 'selected' : '' }}>All Time</option>
                </select>
            </div>
            <div>
                <label class="admin-filter-label" for="dashboardMunicipality">Municipality</label>
                <select id="dashboardMunicipality" name="municipality_id" class="form-select form-select-sm">
                    <option value="">All Municipalities</option>
                    @foreach($municipalities as $municipality)
                        <option value="{{ $municipality->id }}" {{ (int) $dashboardFilters['municipality_id'] === $municipality->id ? 'selected' : '' }}>
                            {{ $municipality->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="admin-filter-label" for="dashboardOffice">Office</label>
                <select id="dashboardOffice" name="office_id" class="form-select form-select-sm">
                    <option value="">All Offices</option>
                    @foreach($officeOptions as $officeOption)
                        <option value="{{ $officeOption->id }}" {{ (int) $dashboardFilters['office_id'] === $officeOption->id ? 'selected' : '' }}>
                            {{ $officeOption->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-filter-actions">
                <button id="adminDashboardApply" type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-check2"></i> Apply
                </button>
                @if($dashboardFilters['is_scoped'])
                    <a id="adminDashboardClear" href="{{ route('admin.dashboard') }}" class="btn btn-sm admin-plain-btn">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 admin-reveal">
        <x-admin.stat-card
            label="Total Users"
            :value="number_format($stats['total_users'])"
            subtitle="Platform citizens"
            icon="bi-people"
            color="teal"
            :trend="$trends['total_users']['text']"
            :trend-direction="$trends['total_users']['direction']"
            :animate="true"
            :value-raw="$stats['total_users']"
            value-decimals="0" />
    </div>
    <div class="col-6 col-lg-3 admin-reveal">
        <x-admin.stat-card
            label="Offices"
            :value="number_format($stats['total_offices'])"
            subtitle="Offices in scope"
            icon="bi-buildings"
            color="sky"
            :trend="$trends['total_offices']['text']"
            :trend-direction="$trends['total_offices']['direction']"
            :animate="true"
            :value-raw="$stats['total_offices']"
            value-decimals="0" />
    </div>
    <div class="col-6 col-lg-3 admin-reveal">
        <x-admin.stat-card
            label="Requests"
            :value="number_format($stats['total_requests'])"
            :subtitle="'During ' . strtolower($dashboardFilters['period_label'])"
            icon="bi-file-earmark-text"
            color="amber"
            :trend="$trends['total_requests']['text']"
            :trend-direction="$trends['total_requests']['direction']"
            :animate="true"
            :value-raw="$stats['total_requests']"
            value-decimals="0" />
    </div>
    <div class="col-6 col-lg-3 admin-reveal">
        <x-admin.stat-card
            label="Revenue"
            :value="'$' . number_format($stats['total_revenue'], 0)"
            subtitle="Collected from paid requests"
            icon="bi-currency-dollar"
            color="emerald"
            :trend="$trends['total_revenue']['text']"
            :trend-direction="$trends['total_revenue']['direction']"
            :animate="true"
            :value-raw="$stats['total_revenue']"
            value-prefix="$"
            value-decimals="0" />
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-xl-8 admin-reveal">
        <div class="card h-100 admin-busy-target" id="adminDashboardRequestsCard">
            <x-admin.table-toolbar
                title="Recent Service Requests"
                subtitle="Latest request activity across municipalities and offices.">
                <x-slot:actions>
                    <span class="admin-card-meta">Pending: {{ number_format($stats['pending_requests']) }}</span>
                    <div class="admin-density-switch ms-2">
                        <button
                            type="button"
                            class="admin-density-btn is-active"
                            data-admin-density-target="#adminDashboardRequestsTable"
                            data-admin-density="comfortable">Comfort</button>
                        <button
                            type="button"
                            class="admin-density-btn"
                            data-admin-density-target="#adminDashboardRequestsTable"
                            data-admin-density="compact">Compact</button>
                    </div>
                </x-slot:actions>
            </x-admin.table-toolbar>
            <div class="card-body p-0">
                @if($recentRequests->count())
                    <div class="admin-table-wrap">
                        <table
                            id="adminDashboardRequestsTable"
                            class="table table-hover admin-table-sticky admin-table-tight admin-table-interactive mb-0"
                            data-admin-table>
                            <thead>
                                <tr>
                                    <th data-sort="0" data-sort-type="text">Reference</th>
                                    <th data-sort="1" data-sort-type="text">Citizen</th>
                                    <th data-sort="2" data-sort-type="text">Office</th>
                                    <th data-sort="3" data-sort-type="text">Service</th>
                                    <th data-sort="4" data-sort-type="text">Status</th>
                                    <th data-sort="5" data-sort-type="date">Submitted</th>
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
                                        <td class="admin-muted" data-sort-value="{{ $request->created_at->timestamp }}">{{ $request->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="admin-empty-state">
                        <div class="admin-empty-state-icon"><i class="bi bi-inbox"></i></div>
                        <div class="admin-empty-state-title">No Requests In This Range</div>
                        <div class="admin-empty-state-copy">Try widening the date range or clearing office filters to see more activity.</div>
                        @if($dashboardFilters['is_scoped'])
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm admin-plain-btn">Reset Filters</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4 admin-reveal">
        <div class="card h-100">
            <x-admin.table-toolbar
                title="Top Offices by Volume"
                subtitle="Highest request counts in the selected scope." />
            <div class="card-body">
                @if($officeStats->count())
                    <div class="d-flex flex-column gap-2">
                        @foreach($officeStats as $office)
                            <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 admin-list-item">
                                <div>
                                    <div class="admin-list-item-main">{{ $office->name }}</div>
                                    <div class="admin-list-item-sub">{{ $office->municipality->name ?? 'Municipality' }}</div>
                                </div>
                                <span class="badge rounded-pill border admin-count-badge">{{ $office->requests_count }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="admin-empty-state" style="padding-top:1.5rem;padding-bottom:1.5rem;">
                        <div class="admin-empty-state-icon"><i class="bi bi-bar-chart"></i></div>
                        <div class="admin-empty-state-title">No Office Ranking Yet</div>
                        <div class="admin-empty-state-copy">Office activity appears once requests are submitted in the selected range.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 admin-reveal">
        <div class="card">
            <x-admin.table-toolbar
                title="Monthly Requests"
                :subtitle="now()->year . ' trend for ' . strtolower($dashboardFilters['period_label']) . '.'" />
            <div class="card-body">
                @if(collect($chartValues)->sum() > 0)
                    <div class="admin-chart-wrap">
                        <canvas
                            id="monthlyRequestsChart"
                            aria-label="Monthly requests chart"
                            data-chart-labels='@json($chartLabels)'
                            data-chart-values='@json($chartValues)'></canvas>
                    </div>
                @else
                    <div class="admin-empty-state" style="padding-top:1.25rem;padding-bottom:1.25rem;">
                        <div class="admin-empty-state-icon"><i class="bi bi-graph-up"></i></div>
                        <div class="admin-empty-state-title">No Trend Data Yet</div>
                        <div class="admin-empty-state-copy">No requests matched your current scope for chart visualization.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let autoSubmitTimer = null;

        const formatNumber = (value, decimals) =>
            Number(value).toLocaleString(undefined, {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });

        const setApplyLoading = (form, loading) => {
            if (!form) return;
            const applyBtn = form.querySelector('#adminDashboardApply');
            if (!applyBtn) return;
            if (!applyBtn.dataset.originalHtml) {
                applyBtn.dataset.originalHtml = applyBtn.innerHTML;
            }
            applyBtn.disabled = loading;
            applyBtn.innerHTML = loading
                ? '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Applying'
                : applyBtn.dataset.originalHtml;
        };

        const animateCounters = (scope) => {
            scope.querySelectorAll('[data-counter]').forEach((el, index) => {
                const target = Number(el.dataset.counterTarget || 0);
                const prefix = el.dataset.counterPrefix || '';
                const suffix = el.dataset.counterSuffix || '';
                const decimals = Number(el.dataset.counterDecimals || 0);

                const setValue = (value) => {
                    el.textContent = `${prefix}${formatNumber(value, decimals)}${suffix}`;
                };

                if (!Number.isFinite(target) || prefersReducedMotion) {
                    setValue(target);
                    return;
                }

                const duration = 850 + (index * 120);
                const startTime = performance.now();

                const tick = (now) => {
                    const progress = Math.min((now - startTime) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    setValue(target * eased);
                    if (progress < 1) {
                        window.requestAnimationFrame(tick);
                    }
                };

                window.requestAnimationFrame(tick);
            });
        };

        const renderMonthlyChart = (scope) => {
            const canvas = scope.querySelector('#monthlyRequestsChart');
            if (window.__adminMonthlyChart) {
                window.__adminMonthlyChart.destroy();
                window.__adminMonthlyChart = null;
            }
            if (!canvas || typeof Chart === 'undefined') return;

            const labels = JSON.parse(canvas.dataset.chartLabels || '[]');
            const values = JSON.parse(canvas.dataset.chartValues || '[]');

            const css = getComputedStyle(document.body);
            const primary = css.getPropertyValue('--es-primary').trim() || '#2563EB';
            const muted = css.getPropertyValue('--es-muted').trim() || '#64748B';
            const border = css.getPropertyValue('--es-border-soft').trim() || '#E7EDF5';

            window.__adminMonthlyChart = new Chart(canvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Requests',
                        data: values,
                        borderColor: primary,
                        backgroundColor: `${primary}1f`,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointBackgroundColor: primary,
                        pointHoverRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: muted, font: { size: 11, family: 'Public Sans' } },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: muted, font: { size: 11, family: 'Public Sans' } },
                            grid: { color: border },
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            titleFont: { family: 'Public Sans' },
                            bodyFont: { family: 'Public Sans' }
                        }
                    }
                }
            });
        };

        const buildFilterUrl = (form) => {
            const url = new URL(form.action, window.location.origin);
            const params = new URLSearchParams();
            const formData = new FormData(form);
            for (const [key, value] of formData.entries()) {
                if (String(value).trim() === '') continue;
                params.set(key, value);
            }
            if (!params.has('period')) {
                params.set('period', 'this_month');
            }
            url.search = params.toString();
            return url;
        };

        const swapDashboard = async (url, sourceForm) => {
            const currentRoot = document.getElementById('adminDashboardLiveRoot');
            if (!currentRoot) return;

            currentRoot.classList.add('is-loading');
            setApplyLoading(sourceForm, true);

            try {
                const response = await fetch(url.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });

                if (!response.ok) throw new Error(`Dashboard request failed (${response.status})`);
                const html = await response.text();
                const parser = new DOMParser();
                const nextDoc = parser.parseFromString(html, 'text/html');
                const nextRoot = nextDoc.getElementById('adminDashboardLiveRoot');
                if (!nextRoot) throw new Error('Live root not found in response');

                currentRoot.replaceWith(nextRoot);
                history.replaceState({}, '', url.toString());
                initDashboardEnhancements();
            } catch (error) {
                window.location.href = url.toString();
            } finally {
                setApplyLoading(sourceForm, false);
                currentRoot.classList.remove('is-loading');
            }
        };

        const bindDashboardFilters = () => {
            const form = document.getElementById('adminDashboardFilters');
            if (!form || form.dataset.ajaxBound === '1') return;

            form.dataset.ajaxBound = '1';
            const municipalitySelect = form.querySelector('#dashboardMunicipality');
            const officeSelect = form.querySelector('#dashboardOffice');
            const periodSelect = form.querySelector('#dashboardPeriod');
            const clearLink = form.querySelector('#adminDashboardClear');

            const requestLiveUpdate = () => {
                if (form.dataset.loading === '1') return;
                const url = buildFilterUrl(form);
                form.dataset.loading = '1';
                swapDashboard(url, form).finally(() => {
                    form.dataset.loading = '0';
                });
            };

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                requestLiveUpdate();
            });

            periodSelect?.addEventListener('change', () => {
                window.clearTimeout(autoSubmitTimer);
                autoSubmitTimer = window.setTimeout(() => form.requestSubmit(), 120);
            });

            municipalitySelect?.addEventListener('change', () => {
                if (officeSelect) officeSelect.value = '';
                window.clearTimeout(autoSubmitTimer);
                autoSubmitTimer = window.setTimeout(() => form.requestSubmit(), 120);
            });

            officeSelect?.addEventListener('change', () => {
                window.clearTimeout(autoSubmitTimer);
                autoSubmitTimer = window.setTimeout(() => form.requestSubmit(), 120);
            });

            clearLink?.addEventListener('click', (event) => {
                event.preventDefault();
                form.reset();
                const clearUrl = new URL(clearLink.href, window.location.origin);
                form.dataset.loading = '1';
                swapDashboard(clearUrl, form).finally(() => {
                    form.dataset.loading = '0';
                });
            });
        };

        const initDashboardEnhancements = () => {
            const root = document.getElementById('adminDashboardLiveRoot');
            if (!root) return;
            animateCounters(root);
            renderMonthlyChart(root);
            bindDashboardFilters();
            if (typeof window.initAdminGlobalUX === 'function') {
                window.initAdminGlobalUX(root);
            }
        };

        initDashboardEnhancements();
    })();
</script>
@endpush
