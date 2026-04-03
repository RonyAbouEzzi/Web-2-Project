@extends('layouts.app')
@section('title', 'Citizen Dashboard')
@section('page-title', 'Citizen Dashboard')

@section('content')
@php
    $user = auth()->user();
    $allRequests = $user->serviceRequests;
    $activeRequests = $allRequests->whereIn('status', ['pending', 'in_review', 'missing_documents', 'approved']);
    $recentNotifications = $user->notifications()->latest()->take(5)->get();
    $upcomingAppointments = $upcomingAppointments
        ?? $user->appointments()
            ->with('office')
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

    $completedCount = $allRequests->where('status', 'completed')->count();
    $totalCount = max(1, $allRequests->count());
    $completionRate = (int) round(($completedCount / $totalCount) * 100);
    $missingFields = $user->missingCitizenProfileFields();

    $profileChecks = [
        filled($user->name),
        filled($user->email),
        filled($user->phone),
        filled($user->national_id),
        filled($user->id_document),
    ];
    $profileCompletion = (int) round((collect($profileChecks)->filter()->count() / count($profileChecks)) * 100);
@endphp

<div class="card citizen-hero-card citizen-reveal" data-citizen-reveal>
    <div class="card-body">
        <div class="row g-4 align-items-center">
            <div class="col-12 col-xl-8">
                <span class="citizen-hero-eyebrow">Citizen Workspace</span>
                <h2 class="citizen-hero-title">Welcome back, {{ \Illuminate\Support\Str::before($user->name, ' ') }}.</h2>
                <p class="citizen-hero-copy">
                    Track requests, complete pending actions, and keep your profile ready so submissions stay fast.
                </p>

                <div class="citizen-progress-wrap">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="citizen-progress-label">Request completion progress</span>
                        <span class="citizen-progress-value">{{ $completionRate }}%</span>
                    </div>
                    <div class="progress citizen-progress-bar" role="progressbar" aria-label="Request completion progress" aria-valuenow="{{ $completionRate }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: {{ $completionRate }}%"></div>
                    </div>
                </div>

                @if(!empty($missingFields))
                    <div class="citizen-inline-alert mt-3">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Profile incomplete: {{ implode(', ', $missingFields) }}</span>
                    </div>
                @else
                    <div class="citizen-inline-alert is-success mt-3">
                        <i class="bi bi-check2-circle"></i>
                        <span>Your profile is complete and ready for new requests.</span>
                    </div>
                @endif
            </div>
            <div class="col-12 col-xl-4">
                <div class="citizen-hero-panel">
                    <div class="citizen-hero-orb citizen-hero-orb-one"></div>
                    <div class="citizen-hero-orb citizen-hero-orb-two"></div>
                    <div class="citizen-hero-meta">
                        <div class="citizen-hero-meta-label">Profile readiness</div>
                        <div class="citizen-hero-meta-value" data-citizen-counter="{{ $profileCompletion }}">{{ $profileCompletion }}</div>
                        <div class="citizen-hero-meta-suffix">% complete</div>
                    </div>
                    <a href="{{ route('citizen.profile') }}" class="btn btn-primary w-100 mt-3">
                        <i class="bi bi-person-check me-1"></i> Update Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card citizen-kpi-card citizen-reveal" data-citizen-reveal>
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="citizen-kpi-label">Active</span>
                        <h3 class="citizen-kpi-value" data-citizen-counter="{{ $activeRequests->count() }}">{{ $activeRequests->count() }}</h3>
                    </div>
                    <span class="stat-card-icon bg-teal"><i class="bi bi-activity"></i></span>
                </div>
                <div class="citizen-kpi-sub">Currently in progress</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card citizen-kpi-card citizen-reveal" data-citizen-reveal>
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="citizen-kpi-label">Pending</span>
                        <h3 class="citizen-kpi-value" data-citizen-counter="{{ $allRequests->where('status', 'pending')->count() }}">{{ $allRequests->where('status', 'pending')->count() }}</h3>
                    </div>
                    <span class="stat-card-icon bg-amber"><i class="bi bi-hourglass-split"></i></span>
                </div>
                <div class="citizen-kpi-sub">Waiting to be reviewed</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card citizen-kpi-card citizen-reveal" data-citizen-reveal>
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="citizen-kpi-label">Completed</span>
                        <h3 class="citizen-kpi-value" data-citizen-counter="{{ $completedCount }}">{{ $completedCount }}</h3>
                    </div>
                    <span class="stat-card-icon bg-emerald"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="citizen-kpi-sub">Finished successfully</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card citizen-kpi-card citizen-reveal" data-citizen-reveal>
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="citizen-kpi-label">Unpaid</span>
                        <h3 class="citizen-kpi-value" data-citizen-counter="{{ $allRequests->where('payment_status', '!=', 'paid')->count() }}">{{ $allRequests->where('payment_status', '!=', 'paid')->count() }}</h3>
                    </div>
                    <span class="stat-card-icon bg-rose"><i class="bi bi-credit-card"></i></span>
                </div>
                <div class="citizen-kpi-sub">Need payment action</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card citizen-reveal" data-citizen-reveal>
            <div class="card-body py-3">
                <div class="citizen-actions-grid">
                    <a href="{{ route('citizen.offices') }}" class="citizen-action-link">
                        <span class="citizen-action-icon"><i class="bi bi-search"></i></span>
                        <span>
                            <span class="citizen-action-title">Browse Services</span>
                            <span class="citizen-action-sub">Find office services near you</span>
                        </span>
                    </a>
                    <a href="{{ route('citizen.requests') }}" class="citizen-action-link">
                        <span class="citizen-action-icon"><i class="bi bi-file-earmark-text"></i></span>
                        <span>
                            <span class="citizen-action-title">My Requests</span>
                            <span class="citizen-action-sub">Track and manage submissions</span>
                        </span>
                    </a>
                    <a href="{{ route('citizen.requests') }}?payment_status=unpaid" class="citizen-action-link">
                        <span class="citizen-action-icon"><i class="bi bi-credit-card-2-front"></i></span>
                        <span>
                            <span class="citizen-action-title">Complete Payments</span>
                            <span class="citizen-action-sub">Resolve unpaid requests quickly</span>
                        </span>
                    </a>
                    <a href="{{ route('citizen.profile') }}" class="citizen-action-link">
                        <span class="citizen-action-icon"><i class="bi bi-person-vcard"></i></span>
                        <span>
                            <span class="citizen-action-title">Profile</span>
                            <span class="citizen-action-sub">Keep your account up to date</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
        <div class="card h-100 citizen-reveal" data-citizen-reveal>
            <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
                <div>
                    <h6 class="card-title">Active Requests</h6>
                    <small class="text-muted">Most recent requests with current status</small>
                </div>
                @if($requests->count() > 8)
                    <a href="{{ route('citizen.requests') }}" class="btn btn-sm btn-outline-primary">View all</a>
                @endif
            </div>
            <div class="card-body p-0">
                @if($requests->count())
                    <div class="table-responsive citizen-table-wrap">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Service</th>
                                    <th>Office</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests->take(8) as $request)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $request->reference_number }}</span></td>
                                        <td>{{ $request->service->name }}</td>
                                        <td>{{ $request->office->name }}</td>
                                        <td><x-status-pill :status="$request->status" /></td>
                                        <td><x-status-pill :status="$request->payment_status" /></td>
                                        <td class="text-end">
                                            <a href="{{ route('citizen.requests.show', $request) }}" class="btn btn-sm btn-outline-primary">Open</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center citizen-empty">
                        <div class="mb-2"><i class="bi bi-inbox"></i></div>
                        <div class="fw-semibold mb-1">No service requests yet</div>
                        <div class="text-muted mb-3">Start by browsing available municipality services.</div>
                        <a href="{{ route('citizen.offices') }}" class="btn btn-sm btn-primary">Browse services</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card h-100 citizen-reveal" data-citizen-reveal>
            <div class="card-header">
                <h6 class="card-title">Upcoming Appointments</h6>
                <small class="text-muted">Scheduled visits and timings</small>
            </div>
            <div class="card-body">
                @if($upcomingAppointments->count())
                    <div class="d-flex flex-column gap-2">
                        @foreach($upcomingAppointments as $appointment)
                            <div class="citizen-appointment-card">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold">{{ $appointment->office->name }}</span>
                                    <x-status-pill :status="$appointment->status" />
                                </div>
                                <div class="text-muted citizen-appointment-time">
                                    <i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                                    <span class="mx-1">&middot;</span>
                                    <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted text-center citizen-appointment-empty">No upcoming appointments.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="card citizen-reveal" data-citizen-reveal>
            <div class="card-header">
                <h6 class="card-title">Recent Notifications</h6>
                <small class="text-muted">Latest activity and system messages</small>
            </div>
            <div class="card-body">
                @if($recentNotifications->count())
                    <div class="list-group list-group-flush">
                        @foreach($recentNotifications as $notification)
                            <div class="list-group-item citizen-note-item px-0 d-flex justify-content-between align-items-start bg-transparent border-bottom">
                                <div>
                                    <div class="fw-semibold citizen-note-title">{{ $notification->data['message'] ?? 'Notification received' }}</div>
                                    <div class="text-muted citizen-note-time">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                @if(is_null($notification->read_at))
                                    <span class="badge rounded-pill citizen-note-badge">new</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted text-center citizen-note-empty">No notifications to display.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
body.es-role-citizen .citizen-hero-card {
    position: relative;
    overflow: hidden;
    border: 1px solid color-mix(in srgb, var(--es-primary) 18%, var(--es-border) 82%);
    background: radial-gradient(circle at 16% -10%, rgba(56, 189, 248, 0.2) 0, rgba(56, 189, 248, 0) 52%),
                radial-gradient(circle at 92% 116%, rgba(16, 185, 129, 0.17) 0, rgba(16, 185, 129, 0) 58%),
                linear-gradient(135deg, rgba(255, 255, 255, 0.96) 0%, rgba(244, 248, 255, 0.9) 100%);
}

body.es-role-citizen .citizen-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .22rem .58rem;
    border-radius: 999px;
    font-size: .67rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: #0F766E;
    background: rgba(153, 246, 228, 0.55);
}

body.es-role-citizen .citizen-hero-title {
    margin: .9rem 0 .45rem;
    font-size: clamp(1.38rem, 2.4vw, 1.85rem);
    font-weight: 800;
    letter-spacing: -0.02em;
    color: #0F172A;
}

body.es-role-citizen .citizen-hero-copy {
    margin: 0;
    color: #475569;
    font-size: .9rem;
    max-width: 42rem;
}

body.es-role-citizen .citizen-progress-wrap {
    margin-top: 1rem;
}

body.es-role-citizen .citizen-progress-label {
    font-size: .74rem;
    color: #475569;
    font-weight: 600;
}

body.es-role-citizen .citizen-progress-value {
    font-size: .77rem;
    color: #0F766E;
    font-weight: 700;
}

body.es-role-citizen .citizen-progress-bar {
    height: .5rem;
    border-radius: 999px;
    background: #DBEAFE;
}

body.es-role-citizen .citizen-progress-bar .progress-bar {
    border-radius: 999px;
    background: linear-gradient(90deg, #06B6D4 0%, #0EA5E9 55%, #2563EB 100%);
}

body.es-role-citizen .citizen-inline-alert {
    display: inline-flex;
    align-items: center;
    gap: .42rem;
    border-radius: 999px;
    padding: .32rem .68rem;
    font-size: .74rem;
    font-weight: 600;
    background: #FFF7ED;
    color: #9A3412;
    border: 1px solid #FED7AA;
}

body.es-role-citizen .citizen-inline-alert.is-success {
    background: #ECFDF5;
    border-color: #A7F3D0;
    color: #047857;
}

body.es-role-citizen .citizen-hero-panel {
    position: relative;
    border: 1px solid rgba(37, 99, 235, 0.18);
    border-radius: 1rem;
    padding: 1rem;
    background: linear-gradient(150deg, rgba(219, 234, 254, 0.76) 0%, rgba(240, 249, 255, 0.92) 100%);
    box-shadow: 0 10px 24px rgba(37, 99, 235, 0.12);
    overflow: hidden;
}

body.es-role-citizen .citizen-hero-orb {
    position: absolute;
    border-radius: 999px;
    filter: blur(1px);
    animation: citizenFloat 6s ease-in-out infinite;
}

body.es-role-citizen .citizen-hero-orb-one {
    width: 6rem;
    height: 6rem;
    top: -2.6rem;
    right: -2.1rem;
    background: rgba(14, 165, 233, 0.32);
}

body.es-role-citizen .citizen-hero-orb-two {
    width: 4.8rem;
    height: 4.8rem;
    bottom: -2rem;
    left: -1.4rem;
    background: rgba(16, 185, 129, 0.28);
    animation-delay: .8s;
}

@keyframes citizenFloat {
    0%, 100% {
        transform: translateY(0) scale(1);
    }
    50% {
        transform: translateY(-8px) scale(1.03);
    }
}

body.es-role-citizen .citizen-hero-meta {
    position: relative;
    z-index: 2;
}

body.es-role-citizen .citizen-hero-meta-label {
    font-size: .72rem;
    color: #475569;
    font-weight: 600;
}

body.es-role-citizen .citizen-hero-meta-value {
    margin-top: .25rem;
    line-height: 1;
    font-size: 2rem;
    letter-spacing: -0.03em;
    font-weight: 800;
    color: #0F172A;
}

body.es-role-citizen .citizen-hero-meta-suffix {
    margin-top: .2rem;
    font-size: .77rem;
    color: #475569;
}

body.es-role-citizen .citizen-kpi-card {
    border-radius: .9rem;
    border-color: color-mix(in srgb, var(--es-border) 72%, #BFDBFE 28%);
}

body.es-role-citizen .citizen-kpi-label {
    display: block;
    margin-bottom: .18rem;
    color: #64748B;
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .05em;
}

body.es-role-citizen .citizen-kpi-value {
    margin: 0;
    font-size: 1.6rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: #0F172A;
}

body.es-role-citizen .citizen-kpi-sub {
    margin-top: .2rem;
    font-size: .75rem;
    color: #64748B;
}

body.es-role-citizen .citizen-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: .65rem;
}

body.es-role-citizen .citizen-action-link {
    display: flex;
    align-items: center;
    gap: .68rem;
    border-radius: .8rem;
    padding: .72rem .8rem;
    border: 1px solid color-mix(in srgb, var(--es-border) 74%, #BFDBFE 26%);
    background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFF 100%);
    color: #0F172A;
    text-decoration: none;
    transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
}

body.es-role-citizen .citizen-action-link:hover {
    transform: translateY(-2px);
    border-color: #93C5FD;
    box-shadow: 0 12px 22px rgba(37, 99, 235, 0.12);
    color: #0F172A;
}

body.es-role-citizen .citizen-action-icon {
    width: 2rem;
    height: 2rem;
    border-radius: .62rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .94rem;
    color: #0369A1;
    background: #E0F2FE;
    border: 1px solid #BAE6FD;
    flex-shrink: 0;
}

body.es-role-citizen .citizen-action-title {
    display: block;
    font-size: .8rem;
    font-weight: 700;
    line-height: 1.1;
}

body.es-role-citizen .citizen-action-sub {
    display: block;
    margin-top: .16rem;
    font-size: .72rem;
    color: #64748B;
}

body.es-role-citizen .citizen-table-wrap thead th {
    font-size: .68rem;
}

body.es-role-citizen .citizen-empty i {
    font-size: 2rem;
    color: #94A3B8;
}

body.es-role-citizen .citizen-empty .fw-semibold {
    font-size: .85rem;
}

body.es-role-citizen .citizen-empty .text-muted {
    font-size: .78rem;
}

body.es-role-citizen .citizen-appointment-card {
    border-radius: .8rem;
    border: 1px solid color-mix(in srgb, var(--es-border) 76%, #BFDBFE 24%);
    background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFF 100%);
    padding: .7rem .78rem;
    transition: transform .18s ease, box-shadow .18s ease;
}

body.es-role-citizen .citizen-appointment-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 16px rgba(37, 99, 235, 0.1);
}

body.es-role-citizen .citizen-appointment-card .fw-semibold {
    font-size: .81rem;
}

body.es-role-citizen .citizen-appointment-time {
    font-size: .74rem;
}

body.es-role-citizen .citizen-appointment-empty,
body.es-role-citizen .citizen-note-empty {
    font-size: .84rem;
}

body.es-role-citizen .citizen-note-item {
    transition: background-color .18s ease;
}

body.es-role-citizen .citizen-note-item:hover {
    background: rgba(239, 246, 255, 0.55) !important;
}

body.es-role-citizen .citizen-note-title {
    font-size: .84rem;
}

body.es-role-citizen .citizen-note-time {
    font-size: .73rem;
}

body.es-role-citizen .citizen-note-badge {
    background: #E0F2FE;
    color: #0369A1;
    border: 1px solid #BAE6FD;
    font-size: .62rem;
}

@media (max-width: 991.98px) {
    body.es-role-citizen .citizen-actions-grid {
        grid-template-columns: 1fr;
    }
}

@media (prefers-reduced-motion: reduce) {
    body.es-role-citizen .citizen-hero-orb,
    body.es-role-citizen .citizen-action-link,
    body.es-role-citizen .citizen-appointment-card {
        animation: none !important;
        transition: none !important;
    }
}
</style>
@endpush
