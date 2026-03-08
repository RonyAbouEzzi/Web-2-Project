@extends('layouts.app')
@section('title','Appointments')
@section('page-title','Appointments')

@section('content')
@php $aptColors = ['scheduled'=>'s-pending','confirmed'=>'s-approved','cancelled'=>'s-rejected','completed'=>'s-completed']; @endphp
<div class="card">
    <div class="card-header">
        <span class="card-title">Scheduled Appointments</span>
        <span style="font-size:.75rem;color:#9ca3af">{{ $appointments->total() }} total</span>
    </div>
    <div class="card-body" style="padding:0 !important">
        <div class="d-none d-md-block table-wrap">
            <table class="table table-hover">
                <thead><tr><th>Citizen</th><th>Date</th><th>Time</th><th>Notes</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($appointments as $apt)
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:.83rem">{{ $apt->citizen->name }}</div>
                            <div style="font-size:.72rem;color:#9ca3af">{{ $apt->citizen->email }}</div>
                        </td>
                        <td style="font-weight:600">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</td>
                        <td style="font-size:.78rem;color:#6b7280;max-width:150px">{{ $apt->notes ? Str::limit($apt->notes,40) : '—' }}</td>
                        <td>
                            <span class="sbadge {{ $aptColors[$apt->status] ?? 's-pending' }}">{{ ucfirst($apt->status) }}</span>
                        </td>
                        <td>
                            <div style="display:flex;gap:.4rem">
                                @foreach(['confirmed'=>['bg'=>'#d1fae5','c'=>'#16a34a'],'cancelled'=>['bg'=>'#fee2e2','c'=>'#dc2626'],'completed'=>['bg'=>'#dbeafe','c'=>'#2563eb']] as $action => $style)
                                @if($apt->status !== $action)
                                <form action="{{ route('office.appointments.update', $apt) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $action }}">
                                    <button class="btn btn-sm" style="background:{{ $style['bg'] }};border:none;color:{{ $style['c'] }};font-size:.7rem;padding:.3rem .55rem">
                                        {{ ucfirst($action) }}
                                    </button>
                                </form>
                                @endif
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;color:#9ca3af">No appointments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-md-none">
            @forelse($appointments as $apt)
            <div style="padding:.9rem 1rem;border-bottom:1px solid #f3f4f6">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.4rem">
                    <div>
                        <div style="font-weight:700;font-size:.87rem">{{ $apt->citizen->name }}</div>
                        <div style="font-size:.73rem;color:#9ca3af">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</div>
                    </div>
                    <span class="sbadge {{ $aptColors[$apt->status] ?? 's-pending' }}">{{ ucfirst($apt->status) }}</span>
                </div>
                <div style="display:flex;gap:.4rem;flex-wrap:wrap">
                    @foreach(['confirmed','cancelled','completed'] as $action)
                    @if($apt->status !== $action)
                    <form action="{{ route('office.appointments.update', $apt) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $action }}">
                        <button class="btn btn-sm" style="font-size:.72rem;padding:.28rem .6rem;background:#f3f4f6;border:none;color:#374151">{{ ucfirst($action) }}</button>
                    </form>
                    @endif
                    @endforeach
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:2.5rem;color:#9ca3af"><i class="bi bi-calendar-x" style="font-size:2rem;display:block;margin-bottom:.5rem;color:#d1d5db"></i>No appointments yet.</div>
            @endforelse
        </div>
        @if($appointments->hasPages())
        <div style="padding:.75rem 1rem;border-top:1px solid #f3f4f6">{{ $appointments->links() }}</div>
        @endif
    </div>
</div>
@endsection
