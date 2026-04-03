@extends('layouts.app')
@section('title', 'Office Dashboard')
@section('page-title', 'Office Dashboard')

@section('content')
<div class="card office-hero-card office-reveal" data-office-reveal>
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-xl-8">
                <span class="office-hero-eyebrow">Office Workspace</span>
                <h2 class="office-hero-title">{{ $office->name }}</h2>
                <p class="office-hero-copy">
                    Manage incoming citizen requests, appointments, and service quality from one focused workspace.
                </p>
            </div>
            <div class="col-12 col-xl-4">
                <div class="office-hero-panel">
                    <div class="office-hero-panel-label">Completed This Month</div>
                    <div class="office-hero-panel-value" data-office-counter="{{ $stats['completed_this_month'] }}">{{ $stats['completed_this_month'] }}</div>
                    <div class="office-hero-panel-sub">Revenue: ${{ number_format($stats['revenue'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card office-kpi-card office-reveal" data-office-reveal>
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="office-kpi-label">Pending</span>
                        <h3 class="office-kpi-value" data-office-counter="{{ $stats['pending'] }}">{{ $stats['pending'] }}</h3>
                    </div>
                    <span class="stat-card-icon bg-amber"><i class="bi bi-hourglass-split"></i></span>
                </div>
                <div class="office-kpi-sub">Waiting review</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card office-kpi-card office-reveal" data-office-reveal>
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="office-kpi-label">In Review</span>
                        <h3 class="office-kpi-value" data-office-counter="{{ $stats['in_review'] }}">{{ $stats['in_review'] }}</h3>
                    </div>
                    <span class="stat-card-icon bg-sky"><i class="bi bi-search"></i></span>
                </div>
                <div class="office-kpi-sub">Currently processed</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card office-kpi-card office-reveal" data-office-reveal>
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="office-kpi-label">Today Incoming</span>
                        <h3 class="office-kpi-value" data-office-counter="{{ $stats['pending_today'] }}">{{ $stats['pending_today'] }}</h3>
                    </div>
                    <span class="stat-card-icon bg-teal"><i class="bi bi-inbox"></i></span>
                </div>
                <div class="office-kpi-sub">New today</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card office-kpi-card office-reveal" data-office-reveal>
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="office-kpi-label">Avg Rating</span>
                        <h3 class="office-kpi-value">{{ number_format($stats['avg_rating'], 1) }}</h3>
                    </div>
                    <span class="stat-card-icon bg-violet"><i class="bi bi-star"></i></span>
                </div>
                <div class="office-kpi-sub">Citizen feedback</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card office-reveal" data-office-reveal>
            <div class="card-body py-3">
                <div class="office-actions-grid">
                    <a href="{{ route('office.requests') }}" class="office-action-link">
                        <span class="office-action-icon"><i class="bi bi-inbox"></i></span>
                        <span>
                            <span class="office-action-title">Open Requests</span>
                            <span class="office-action-sub">Review incoming submissions</span>
                        </span>
                    </a>
                    <a href="{{ route('office.services') }}" class="office-action-link">
                        <span class="office-action-icon"><i class="bi bi-grid"></i></span>
                        <span>
                            <span class="office-action-title">Manage Services</span>
                            <span class="office-action-sub">Edit pricing and durations</span>
                        </span>
                    </a>
                    <a href="{{ route('office.appointments') }}" class="office-action-link">
                        <span class="office-action-icon"><i class="bi bi-calendar-check"></i></span>
                        <span>
                            <span class="office-action-title">Appointments</span>
                            <span class="office-action-sub">Handle visit scheduling</span>
                        </span>
                    </a>
                    <a href="{{ route('office.feedback') }}" class="office-action-link">
                        <span class="office-action-icon"><i class="bi bi-chat-left-dots"></i></span>
                        <span>
                            <span class="office-action-title">Feedback</span>
                            <span class="office-action-sub">Respond to citizen reviews</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-xl-7">
        <div class="card h-100 office-reveal" data-office-reveal>
            <div class="card-header">
                <h6 class="card-title">Pending & In-Review Requests</h6>
                <small class="text-muted">Requests requiring office action</small>
            </div>
            <div class="card-body p-0">
                @if($recentRequests->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Citizen</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $request)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $request->reference_number }}</span></td>
                                        <td>{{ $request->citizen->name }}</td>
                                        <td>{{ $request->service->name }}</td>
                                        <td><x-status-pill :status="$request->status" /></td>
                                        <td class="text-end">
                                            <a href="{{ route('office.requests.show', $request) }}" class="btn btn-sm btn-outline-primary">Open</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <x-empty-state
                        icon="bi-inbox"
                        title="No pending requests"
                        message="Everything is clear right now."
                        class="py-4"
                    />
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-5">
        <div class="card h-100 office-reveal" data-office-reveal>
            <div class="card-header">
                <h6 class="card-title">Today's Appointments</h6>
                <small class="text-muted">Scheduled citizen visits</small>
            </div>
            <div class="card-body">
                @if($todayAppointments->count())
                    <div class="d-flex flex-column gap-2">
                        @foreach($todayAppointments as $appointment)
                            <div class="office-appointment-card">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold">{{ $appointment->citizen->name }}</span>
                                    <x-status-pill :status="$appointment->status" />
                                </div>
                                <div class="office-appointment-time text-muted">
                                    <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state
                        icon="bi-calendar-x"
                        title="No appointments today"
                        message="New bookings will appear here."
                        class="py-4"
                    />
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="card office-reveal" data-office-reveal>
            <div class="card-header">
                <h6 class="card-title">Recent Citizen Feedback</h6>
                <small class="text-muted">Latest comments and ratings</small>
            </div>
            <div class="card-body p-0">
                @if($recentFeedback->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Citizen</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentFeedback as $feedback)
                                    <tr>
                                        <td class="fw-semibold">{{ $feedback->citizen->name }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-warning-subtle border border-warning-subtle">{{ $feedback->rating }}/5</span>
                                        </td>
                                        <td class="office-feedback-comment">{{ $feedback->comment ?: 'No comment provided.' }}</td>
                                        <td class="text-muted">{{ $feedback->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <x-empty-state
                        icon="bi-chat-left-dots"
                        title="No feedback yet"
                        message="Citizen reviews will appear here."
                        class="py-4"
                    />
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
body.es-role-office_user .office-hero-card {
    margin-bottom: 1rem;
    border: 1px solid color-mix(in srgb, var(--es-primary) 16%, var(--es-border) 84%);
    background: radial-gradient(circle at 12% -20%, rgba(59, 130, 246, 0.2) 0, rgba(59, 130, 246, 0) 50%),
                radial-gradient(circle at 95% 120%, rgba(14, 165, 233, 0.15) 0, rgba(14, 165, 233, 0) 58%),
                linear-gradient(140deg, rgba(255, 255, 255, 0.97) 0%, rgba(243, 248, 255, 0.9) 100%);
}

body.es-role-office_user .office-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    padding: .22rem .58rem;
    border-radius: 999px;
    background: #DBEAFE;
    border: 1px solid #BFDBFE;
    color: #1D4ED8;
    font-size: .67rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
}

body.es-role-office_user .office-hero-title {
    margin: .86rem 0 .34rem;
    font-size: clamp(1.28rem, 2.4vw, 1.75rem);
    font-weight: 800;
    letter-spacing: -0.02em;
    color: #0F172A;
}

body.es-role-office_user .office-hero-copy {
    margin: 0;
    color: #475569;
    font-size: .88rem;
}

body.es-role-office_user .office-hero-panel {
    border-radius: .95rem;
    border: 1px solid #BFDBFE;
    background: linear-gradient(145deg, rgba(219, 234, 254, .8) 0%, rgba(239, 246, 255, .95) 100%);
    padding: .95rem;
}

body.es-role-office_user .office-hero-panel-label {
    color: #475569;
    font-size: .72rem;
    font-weight: 600;
}

body.es-role-office_user .office-hero-panel-value {
    margin-top: .22rem;
    font-size: 1.9rem;
    font-weight: 800;
    line-height: 1;
    color: #0F172A;
}

body.es-role-office_user .office-hero-panel-sub {
    margin-top: .24rem;
    color: #475569;
    font-size: .77rem;
}

body.es-role-office_user .office-kpi-card {
    border-radius: .88rem;
}

body.es-role-office_user .office-kpi-label {
    display: block;
    margin-bottom: .16rem;
    color: #64748B;
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .05em;
    text-transform: uppercase;
}

body.es-role-office_user .office-kpi-value {
    margin: 0;
    font-size: 1.58rem;
    font-weight: 800;
    color: #0F172A;
}

body.es-role-office_user .office-kpi-sub {
    margin-top: .18rem;
    font-size: .75rem;
    color: #64748B;
}

body.es-role-office_user .office-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: .66rem;
}

body.es-role-office_user .office-action-link {
    display: flex;
    align-items: center;
    gap: .65rem;
    border-radius: .8rem;
    padding: .72rem .8rem;
    border: 1px solid #D8E8FE;
    background: linear-gradient(180deg, #FFFFFF 0%, #F6FAFF 100%);
    color: #0F172A;
    text-decoration: none;
    transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
}

body.es-role-office_user .office-action-link:hover {
    transform: translateY(-2px);
    border-color: #93C5FD;
    box-shadow: 0 12px 20px rgba(37, 99, 235, 0.12);
    color: #0F172A;
}

body.es-role-office_user .office-action-icon {
    width: 2rem;
    height: 2rem;
    border-radius: .62rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .92rem;
    color: #1D4ED8;
    background: #DBEAFE;
    border: 1px solid #BFDBFE;
    flex-shrink: 0;
}

body.es-role-office_user .office-action-title {
    display: block;
    font-size: .8rem;
    font-weight: 700;
    line-height: 1.1;
}

body.es-role-office_user .office-action-sub {
    display: block;
    margin-top: .14rem;
    font-size: .72rem;
    color: #64748B;
}

body.es-role-office_user .office-appointment-card {
    border-radius: .8rem;
    border: 1px solid #D8E8FE;
    background: linear-gradient(180deg, #FFFFFF 0%, #F6FAFF 100%);
    padding: .72rem .78rem;
}

body.es-role-office_user .office-appointment-card .fw-semibold {
    font-size: .81rem;
}

body.es-role-office_user .office-appointment-time {
    font-size: .74rem;
}

body.es-role-office_user .office-feedback-comment {
    max-width: 320px;
}

@media (max-width: 991.98px) {
    body.es-role-office_user .office-actions-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
