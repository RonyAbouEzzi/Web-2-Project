@extends('layouts.app')
@section('title', 'Citizen Dashboard')
@section('page-title', 'Citizen Dashboard')

@section('content')
@php
    $user = auth()->user();
    $allRequests = $user->serviceRequests;
    $activeRequests = $allRequests->whereIn('status', ['pending', 'in_review', 'missing_documents', 'approved']);
    $recentNotifications = $user->notifications()->latest()->take(5)->get();
@endphp

<div class="row g-3 mb-3">
    <div class="col-12 col-lg-8">
        <x-card title="Current service activity" subtitle="Overview of your requests and scheduled visits.">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="border rounded-3 p-3 bg-light-subtle h-100">
                        <div class="text-muted small">Active requests</div>
                        <div class="fs-4 fw-bold text-dark">{{ $activeRequests->count() }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="border rounded-3 p-3 bg-light-subtle h-100">
                        <div class="text-muted small">Pending</div>
                        <div class="fs-4 fw-bold text-dark">{{ $allRequests->where('status', 'pending')->count() }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="border rounded-3 p-3 bg-light-subtle h-100">
                        <div class="text-muted small">Completed</div>
                        <div class="fs-4 fw-bold text-dark">{{ $allRequests->where('status', 'completed')->count() }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="border rounded-3 p-3 bg-light-subtle h-100">
                        <div class="text-muted small">Unpaid</div>
                        <div class="fs-4 fw-bold text-dark">{{ $allRequests->where('payment_status', '!=', 'paid')->count() }}</div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 col-lg-4">
        <x-card title="Quick actions" subtitle="Common tasks for citizens.">
            <div class="d-grid gap-2">
                <x-ui-button href="{{ route('citizen.offices') }}" variant="primary"><i class="bi bi-search me-1"></i> Browse services</x-ui-button>
                <x-ui-button href="{{ route('citizen.requests') }}" variant="secondary"><i class="bi bi-file-earmark-text me-1"></i> View my requests</x-ui-button>
                <x-ui-button href="{{ route('citizen.requests') }}?payment_status=unpaid" variant="success"><i class="bi bi-credit-card me-1"></i> Complete payments</x-ui-button>
                <x-ui-button href="{{ route('citizen.profile') }}" variant="secondary"><i class="bi bi-person me-1"></i> Update profile</x-ui-button>
            </div>
        </x-card>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
        <x-card title="Active requests" subtitle="Most recent requests with current status.">
            @if($requests->count())
                <div class="table-responsive">
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
                <x-empty-state icon="bi-inbox" title="No service requests yet" message="Start by browsing available municipality services." :actionUrl="route('citizen.offices')" actionLabel="Browse services" />
            @endif
        </x-card>
    </div>

    <div class="col-12 col-xl-4">
        <x-card title="Upcoming appointments" subtitle="Scheduled citizen visits.">
            @if($upcomingAppointments->count())
                <div class="d-flex flex-column gap-2">
                    @foreach($upcomingAppointments as $appointment)
                        <div class="border rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="fw-semibold">{{ $appointment->office->name }}</div>
                                <x-status-pill :status="$appointment->status" />
                            </div>
                            <div class="small text-muted">
                                <i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                                <span class="mx-1">-</span>
                                <i class="bi bi-clock me-1"></i>{{ $appointment->appointment_time }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-muted small">No upcoming appointments.</div>
            @endif
        </x-card>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <x-card title="Recent notifications" subtitle="Latest activity and system messages.">
            @if($recentNotifications->count())
                <div class="list-group list-group-flush">
                    @foreach($recentNotifications as $notification)
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-start bg-transparent">
                            <div>
                                <div class="fw-semibold" style="font-size:.85rem;">{{ $notification->data['message'] ?? 'Notification received' }}</div>
                                <div class="text-muted" style="font-size:.74rem;">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>
                            @if(is_null($notification->read_at))
                                <span class="badge rounded-pill bg-info-subtle border border-info-subtle">new</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-muted small">No notifications to display.</div>
            @endif
        </x-card>
    </div>
</div>
@endsection
