@extends('layouts.app')
@section('title', 'Office Dashboard')
@section('page-title', 'Office Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted mb-1" style="font-size:.76rem;">Pending</span>
                        <h3 class="mb-0" style="font-size:1.5rem; font-weight:800;">{{ $stats['pending'] }}</h3>
                    </div>
                    <span class="stat-card-icon bg-amber"><i class="bi bi-hourglass-split"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted mb-1" style="font-size:.76rem;">In Review</span>
                        <h3 class="mb-0" style="font-size:1.5rem; font-weight:800;">{{ $stats['in_review'] }}</h3>
                    </div>
                    <span class="stat-card-icon bg-sky"><i class="bi bi-search"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted mb-1" style="font-size:.76rem;">Today Incoming</span>
                        <h3 class="mb-0" style="font-size:1.5rem; font-weight:800;">{{ $stats['pending_today'] }}</h3>
                    </div>
                    <span class="stat-card-icon bg-teal"><i class="bi bi-inbox"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted mb-1" style="font-size:.76rem;">Avg Rating</span>
                        <h3 class="mb-0" style="font-size:1.5rem; font-weight:800;">{{ number_format($stats['avg_rating'], 1) }}</h3>
                    </div>
                    <span class="stat-card-icon bg-violet"><i class="bi bi-star"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-3">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('office.requests') }}" class="btn btn-primary"><i class="bi bi-inbox me-1"></i> Open Requests</a>
                    <a href="{{ route('office.services') }}" class="btn btn-secondary"><i class="bi bi-grid me-1"></i> Manage Services</a>
                    <a href="{{ route('office.appointments') }}" class="btn btn-success"><i class="bi bi-calendar-check me-1"></i> Appointments</a>
                    <a href="{{ route('office.feedback') }}" class="btn btn-secondary"><i class="bi bi-chat-left-dots me-1"></i> Feedback</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Requests Table + Today's Appointments --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-xl-7">
        <div class="card h-100">
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
                    <div class="p-4 text-muted text-center" style="font-size:.84rem;">No pending requests right now.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-5">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title">Today's Appointments</h6>
                <small class="text-muted">Scheduled citizen visits</small>
            </div>
            <div class="card-body">
                @if($todayAppointments->count())
                    <div class="d-flex flex-column gap-2">
                        @foreach($todayAppointments as $appointment)
                            <div class="border rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold" style="font-size:.84rem;">{{ $appointment->citizen->name }}</span>
                                    <x-status-pill :status="$appointment->status" />
                                </div>
                                <div class="text-muted" style="font-size:.76rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $appointment->appointment_time }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted text-center" style="font-size:.84rem;">No appointments scheduled for today.</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Feedback --}}
<div class="row g-3">
    <div class="col-12">
        <div class="card">
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
                                        <td style="max-width:300px;">{{ $feedback->comment ?: 'No comment provided.' }}</td>
                                        <td class="text-muted">{{ $feedback->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-muted text-center" style="font-size:.84rem;">No feedback available yet.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
