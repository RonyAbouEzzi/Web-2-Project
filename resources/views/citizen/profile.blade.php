@extends('layouts.app')
@section('title','My Profile')
@section('page-title','My Profile')

@section('content')

<div style="display:grid;grid-template-columns:1fr;gap:1.25rem" class="profile-grid">

    {{-- Profile Header Card --}}
    <div class="card">
        <div class="card-body" style="display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap">
            <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#60A5FA);color:#fff;display:flex;align-items:center;justify-content:center;font-family:var(--font-disp);font-size:1.65rem;font-weight:800;flex-shrink:0;box-shadow:0 8px 24px rgba(26,86,219,.3);border:3px solid #fff;outline:3px solid var(--blue-100)">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            <div style="flex:1;min-width:0">
                <h2 style="font-family:var(--font-disp);font-size:1.15rem;font-weight:800;color:var(--ink-900);margin:0 0 .15rem;letter-spacing:-.02em">{{ $user->name }}</h2>
                <div style="font-size:.8rem;color:var(--ink-400);margin-bottom:.5rem">{{ $user->email }}</div>
                <div style="display:flex;flex-wrap:wrap;gap:.4rem">
                    <span class="sbadge s-active"><i class="bi bi-person-check" style="margin-right:.2rem"></i> Citizen Account</span>
                    @if($user->phone)<span style="background:var(--ink-100);color:var(--ink-600);padding:.22rem .65rem;border-radius:99px;font-size:.69rem;font-weight:600"><i class="bi bi-phone me-1"></i>{{ $user->phone }}</span>@endif
                    <span style="background:var(--ink-100);color:var(--ink-600);padding:.22rem .65rem;border-radius:99px;font-size:.69rem;font-weight:600"><i class="bi bi-calendar3 me-1"></i>Joined {{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    @php
        $totalRequests = $user->serviceRequests()->count();
        $completedRequests = $user->serviceRequests()->where('status','completed')->count();
        $pendingRequests = $user->serviceRequests()->whereIn('status',['pending','in_review'])->count();
    @endphp
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.75rem" class="stats-mini">
        <div class="stat-card" style="--stat-color:var(--blue-50)">
            <div class="stat-icon" style="background:var(--blue-50);color:var(--primary)"><i class="bi bi-file-text"></i></div>
            <div class="stat-val">{{ $totalRequests }}</div>
            <div class="stat-lbl">Total Requests</div>
        </div>
        <div class="stat-card" style="--stat-color:var(--emerald-lt)">
            <div class="stat-icon" style="background:var(--emerald-lt);color:var(--emerald)"><i class="bi bi-check-circle"></i></div>
            <div class="stat-val">{{ $completedRequests }}</div>
            <div class="stat-lbl">Completed</div>
        </div>
        <div class="stat-card" style="--stat-color:var(--amber-lt)">
            <div class="stat-icon" style="background:var(--amber-lt);color:var(--amber)"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-val">{{ $pendingRequests }}</div>
            <div class="stat-lbl">In Progress</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr;gap:1.25rem" class="profile-cols">

        {{-- Edit Profile Form --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="bi bi-person-gear me-2" style="color:var(--primary)"></i>Account Information</span>
            </div>
            <div class="card-body">
                <form action="{{ route('citizen.profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div style="display:grid;grid-template-columns:1fr;gap:1rem" class="form-grid">
                        <div>
                            <label class="form-label">Full Name</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-person ii"></i>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Email Address</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-envelope ii"></i>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled
                                       style="background:var(--ink-50);cursor:not-allowed;opacity:.7">
                            </div>
                            <div class="form-text">Email cannot be changed. Contact support if needed.</div>
                        </div>
                        <div>
                            <label class="form-label">Phone Number</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-phone ii"></i>
                                <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+961 xx xxx xxx">
                            </div>
                        </div>
                        <div style="border-top:1px solid var(--ink-100);padding-top:1rem">
                            <label class="form-label" style="font-size:.82rem;color:var(--ink-500);font-weight:500">Change Password <span style="font-weight:400">(leave blank to keep current)</span></label>
                            <div style="display:grid;grid-template-columns:1fr;gap:.65rem" class="pw-grid">
                                <div>
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="At least 8 characters" autocomplete="new-password">
                                </div>
                                <div>
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:1.25rem">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ID Info Card --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="bi bi-card-text me-2" style="color:var(--primary)"></i>Identity Information</span>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="ir-label">National ID</span>
                    <span class="ir-value font-mono" style="font-size:.82rem;letter-spacing:.04em">{{ $user->national_id ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="ir-label">Account Type</span>
                    <span class="ir-value">Citizen</span>
                </div>
                <div class="info-row">
                    <span class="ir-label">Account Status</span>
                    <span class="sbadge s-{{ $user->is_active ? 'active' : 'inactive' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                <div class="info-row">
                    <span class="ir-label">Member Since</span>
                    <span class="ir-value">{{ $user->created_at->format('F d, Y') }}</span>
                </div>
                @if($user->national_id_doc)
                <div class="info-row">
                    <span class="ir-label">ID Document</span>
                    <span class="sbadge s-approved"><i class="bi bi-check2" style="margin-right:.2rem"></i>Uploaded</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="bi bi-clock-history me-2" style="color:var(--primary)"></i>Recent Activity</span>
                <a href="{{ route('citizen.requests') }}" class="btn btn-sm btn-ghost">View All</a>
            </div>
            <div class="card-body p0">
                @forelse($requests as $req)
                <a href="{{ route('citizen.requests.show', $req) }}"
                   style="display:flex;align-items:center;gap:.85rem;padding:.85rem 1.2rem;border-bottom:1px solid var(--ink-100);text-decoration:none;color:inherit;transition:background .12s"
                   onmouseover="this.style.background='var(--ink-50)'" onmouseout="this.style.background='transparent'">
                    <div style="width:36px;height:36px;border-radius:9px;background:var(--primary-lt);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $req->service->name }}</div>
                        <div style="font-size:.71rem;color:var(--ink-400)">{{ $req->office->name }} · {{ $req->created_at->diffForHumans() }}</div>
                    </div>
                    <span class="sbadge s-{{ $req->status }}" style="flex-shrink:0">{{ ucfirst(str_replace('_',' ',$req->status)) }}</span>
                </a>
                @empty
                <div class="empty-state" style="padding:2rem 1rem">
                    <div class="empty-icon"><i class="bi bi-clock-history"></i></div>
                    <p style="font-size:.82rem;color:var(--ink-400)">No recent activity.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
@media (min-width: 640px) {
    .stats-mini { grid-template-columns: repeat(3, 1fr) !important; }
    .pw-grid { grid-template-columns: 1fr 1fr !important; }
}
@media (min-width: 992px) {
    .profile-cols { grid-template-columns: 1fr 1fr !important; }
    .profile-cols .card:last-child { grid-column: 1 / -1; }
}
</style>
@endpush
@endsection
