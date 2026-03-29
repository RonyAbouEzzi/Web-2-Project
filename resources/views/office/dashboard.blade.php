@extends('layouts.app')
@section('title', 'Office Dashboard')
@section('page-title', 'Office Dashboard')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-12 col-lg-9">
        <x-card title="Office workload" subtitle="Operational summary for your municipality office.">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="border rounded-3 p-3 bg-light-subtle h-100">
                        <div class="text-muted small">Pending requests</div>
                        <div class="fs-4 fw-bold">{{ $stats['pending'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="border rounded-3 p-3 bg-light-subtle h-100">
                        <div class="text-muted small">In review</div>
                        <div class="fs-4 fw-bold">{{ $stats['in_review'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="border rounded-3 p-3 bg-light-subtle h-100">
                        <div class="text-muted small">Today incoming</div>
                        <div class="fs-4 fw-bold">{{ $stats['pending_today'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="border rounded-3 p-3 bg-light-subtle h-100">
                        <div class="text-muted small">Average rating</div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['avg_rating'], 1) }}</div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 col-lg-3">
        <x-card title="Quick actions" subtitle="Office shortcuts.">
            <div class="d-grid gap-2">
                <x-ui-button href="{{ route('office.requests') }}" variant="primary"><i class="bi bi-inbox me-1"></i> Open requests</x-ui-button>
                <x-ui-button href="{{ route('office.services') }}" variant="secondary"><i class="bi bi-grid me-1"></i> Manage services</x-ui-button>
                <x-ui-button href="{{ route('office.appointments') }}" variant="success"><i class="bi bi-calendar-check me-1"></i> Appointments</x-ui-button>
                <x-ui-button href="{{ route('office.feedback') }}" variant="secondary"><i class="bi bi-chat-left-dots me-1"></i> Feedback</x-ui-button>
            </div>
        </x-card>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-7">
        <x-card title="Pending and in-review requests" subtitle="Latest requests requiring office action.">
            @if($recentRequests->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Citizen</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th class="text-end">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRequests as $request)
                                <tr>
                                    <td class="fw-semibold">{{ $request->reference_number }}</td>
                                    <td>{{ $request->citizen->name }}</td>
                                    <td>{{ $request->service->name }}</td>
                                    <td><x-status-pill :status="$request->status" /></td>
                                    <td class="text-end"><a href="{{ route('office.requests.show', $request) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted small">No pending requests right now.</div>
            @endif
        </x-card>
    </div>

    <div class="col-12 col-xl-5">
        <x-card title="Today's appointments" subtitle="Citizen visits scheduled for today.">
            @if($todayAppointments->count())
                <div class="d-flex flex-column gap-2">
                    @foreach($todayAppointments as $appointment)
                        <div class="border rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="fw-semibold">{{ $appointment->citizen->name }}</div>
                                <x-status-pill :status="$appointment->status" />
                            </div>
                            <div class="small text-muted">
                                <i class="bi bi-clock me-1"></i>{{ $appointment->appointment_time }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-muted small">No appointments scheduled for today.</div>
            @endif
        </x-card>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <x-card title="Recent citizen feedback" subtitle="Latest comments and rating snapshots.">
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
                                    <td>{{ $feedback->comment ?: 'No comment provided.' }}</td>
                                    <td class="text-muted">{{ $feedback->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted small">No feedback available yet.</div>
            @endif
        </x-card>
    </div>
</div>
@endsection
