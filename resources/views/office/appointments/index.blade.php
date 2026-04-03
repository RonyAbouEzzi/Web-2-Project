@extends('layouts.app')
@section('title', 'Appointments')
@section('page-title', 'Appointments')

@section('content')
<div class="card office-reveal" data-office-reveal>
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <span class="card-title">Scheduled Appointments</span>
        <span class="office-apt-total">{{ $appointments->total() }} total</span>
    </div>
    <div class="card-body p-0">
        <div class="d-none d-md-block table-responsive office-apt-table-wrap">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Citizen</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Notes</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $apt)
                        <tr>
                            <td>
                                <div class="office-apt-name">{{ $apt->citizen->name }}</div>
                                <div class="office-apt-email">{{ $apt->citizen->email }}</div>
                            </td>
                            <td class="office-apt-date">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</td>
                            <td class="office-apt-notes">{{ $apt->notes ? Str::limit($apt->notes, 44) : '-' }}</td>
                            <td><x-status-pill :status="$apt->status" /></td>
                            <td>
                                <div class="office-apt-actions">
                                    @foreach(['confirmed' => 'Confirm', 'cancelled' => 'Cancel', 'completed' => 'Complete'] as $action => $label)
                                        @if($apt->status !== $action)
                                            <form action="{{ route('office.appointments.update', $apt) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $action }}">
                                                <button class="btn btn-sm office-apt-action-btn">{{ $label }}</button>
                                            </form>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="office-apt-empty-cell">
                                <x-empty-state
                                    icon="bi-calendar-x"
                                    title="No appointments yet"
                                    message="Upcoming citizen bookings will appear here."
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-md-none">
            @forelse($appointments as $apt)
                <div class="office-apt-mobile-row">
                    <div class="office-apt-mobile-head">
                        <div>
                            <div class="office-apt-mobile-name">{{ $apt->citizen->name }}</div>
                            <div class="office-apt-mobile-time">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</div>
                        </div>
                        <x-status-pill :status="$apt->status" />
                    </div>
                    <div class="office-apt-mobile-actions">
                        @foreach(['confirmed' => 'Confirm', 'cancelled' => 'Cancel', 'completed' => 'Complete'] as $action => $label)
                            @if($apt->status !== $action)
                                <form action="{{ route('office.appointments.update', $apt) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $action }}">
                                    <button class="btn btn-sm office-apt-mobile-btn">{{ $label }}</button>
                                </form>
                            @endif
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="office-apt-empty-mobile">
                    <x-empty-state
                        icon="bi-calendar-x"
                        title="No appointments yet"
                        message="New bookings will appear here."
                    />
                </div>
            @endforelse
        </div>

        @if($appointments->hasPages())
            <div class="office-apt-pagination">{{ $appointments->links() }}</div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
body.es-role-office_user .office-apt-total {
    font-size: .75rem;
    color: #64748B;
}

body.es-role-office_user .office-apt-name {
    font-weight: 600;
    font-size: .83rem;
}

body.es-role-office_user .office-apt-email {
    font-size: .72rem;
    color: #94A3B8;
}

body.es-role-office_user .office-apt-date {
    font-weight: 600;
}

body.es-role-office_user .office-apt-notes {
    font-size: .78rem;
    color: #64748B;
    max-width: 160px;
}

body.es-role-office_user .office-apt-actions {
    display: flex;
    gap: .4rem;
    flex-wrap: wrap;
}

body.es-role-office_user .office-apt-action-btn {
    font-size: .7rem;
    padding: .3rem .56rem;
    background: #F1F5F9;
    border: none;
    color: #334155;
}

body.es-role-office_user .office-apt-empty-cell {
    padding: 1.8rem .8rem !important;
}

body.es-role-office_user .office-apt-mobile-row {
    padding: .9rem 1rem;
    border-bottom: 1px solid #E2E8F0;
}

body.es-role-office_user .office-apt-mobile-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: .4rem;
    gap: .6rem;
}

body.es-role-office_user .office-apt-mobile-name {
    font-weight: 700;
    font-size: .87rem;
}

body.es-role-office_user .office-apt-mobile-time {
    font-size: .73rem;
    color: #94A3B8;
}

body.es-role-office_user .office-apt-mobile-actions {
    display: flex;
    gap: .4rem;
    flex-wrap: wrap;
}

body.es-role-office_user .office-apt-mobile-btn {
    font-size: .72rem;
    padding: .28rem .6rem;
    background: #F1F5F9;
    border: none;
    color: #334155;
}

body.es-role-office_user .office-apt-empty-mobile {
    padding: 1.25rem .55rem 1.4rem;
}

body.es-role-office_user .office-apt-pagination {
    padding: .75rem 1rem;
    border-top: 1px solid #E2E8F0;
}
</style>
@endpush
