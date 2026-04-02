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

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted stat-label mb-1">Active</span>
                        <h3 class="mb-0 stat-value">{{ $activeRequests->count() }}</h3>
                    </div>
                    <span class="stat-card-icon bg-teal"><i class="bi bi-activity"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted stat-label mb-1">Pending</span>
                        <h3 class="mb-0 stat-value">{{ $allRequests->where('status', 'pending')->count() }}</h3>
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
                        <span class="d-block text-muted stat-label mb-1">Completed</span>
                        <h3 class="mb-0 stat-value">{{ $allRequests->where('status', 'completed')->count() }}</h3>
                    </div>
                    <span class="stat-card-icon bg-emerald"><i class="bi bi-check-circle"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted stat-label mb-1">Unpaid</span>
                        <h3 class="mb-0 stat-value">{{ $allRequests->where('payment_status', '!=', 'paid')->count() }}</h3>
                    </div>
                    <span class="stat-card-icon bg-rose"><i class="bi bi-credit-card"></i></span>
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
                    <a href="{{ route('citizen.offices') }}" class="btn btn-primary"><i class="bi bi-search me-1"></i> Browse Services</a>
                    <a href="{{ route('citizen.requests') }}" class="btn btn-secondary"><i class="bi bi-file-earmark-text me-1"></i> My Requests</a>
                    <a href="{{ route('citizen.requests') }}?payment_status=unpaid" class="btn btn-success"><i class="bi bi-credit-card me-1"></i> Complete Payments</a>
                    <a href="{{ route('citizen.profile') }}" class="btn btn-secondary"><i class="bi bi-person me-1"></i> Update Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Active Requests + Upcoming Appointments --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
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
                    <div class="p-4 text-center">
                        <div class="mb-2"><i class="bi bi-inbox text-muted" style="font-size:2rem;"></i></div>
                        <div class="fw-semibold mb-1 text-md">No service requests yet</div>
                        <div class="text-muted mb-3 text-sm">Start by browsing available municipality services.</div>
                        <a href="{{ route('citizen.offices') }}" class="btn btn-sm btn-primary">Browse services</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title">Upcoming Appointments</h6>
                <small class="text-muted">Scheduled visits</small>
            </div>
            <div class="card-body">
                @if($upcomingAppointments->count())
                    <div class="d-flex flex-column gap-2">
                        @foreach($upcomingAppointments as $appointment)
                            <div class="border rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold text-md">{{ $appointment->office->name }}</span>
                                    <x-status-pill :status="$appointment->status" />
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                                    <span class="mx-1">&middot;</span>
                                    <i class="bi bi-clock me-1"></i>{{ $appointment->appointment_time }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted text-center text-md">No upcoming appointments.</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Notifications --}}
<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Recent Notifications</h6>
                <small class="text-muted">Latest activity and system messages</small>
            </div>
            <div class="card-body">
                @if($recentNotifications->count())
                    <div class="list-group list-group-flush">
                        @foreach($recentNotifications as $notification)
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start bg-transparent border-bottom">
                                <div>
                                    <div class="fw-semibold text-md">{{ $notification->data['message'] ?? 'Notification received' }}</div>
                                    <div class="text-muted text-xs">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                @if(is_null($notification->read_at))
                                    <span class="badge rounded-pill bg-info-subtle border border-info-subtle text-2xs">new</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted text-center text-md">No notifications to display.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
