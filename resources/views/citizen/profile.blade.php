@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
@php
    $missingFields = $user->missingCitizenProfileFields();
    $allRequests = $user->serviceRequests;
    $totalRequests = $allRequests->count();
    $completedRequests = $allRequests->where('status', 'completed')->count();
    $pendingRequests = $allRequests->whereIn('status', ['pending', 'in_review', 'missing_documents', 'approved'])->count();
    $paidRequests = $paidRequests
        ?? $user->serviceRequests()->with(['service', 'office'])->where('payment_status', 'paid')->latest('updated_at')->take(5)->get();
@endphp

<div class="citizen-profile-grid">
    @if(!empty($missingFields))
        <div class="card citizen-profile-alert citizen-reveal" data-citizen-reveal>
            <div class="card-body">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <div class="citizen-profile-alert-title">Complete your profile to submit requests</div>
                    <div class="citizen-profile-alert-copy">
                        Missing: {{ implode(', ', $missingFields) }}. Fill the fields below, then save.
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card citizen-profile-head citizen-reveal" data-citizen-reveal>
        <div class="card-body">
            <div class="citizen-profile-avatar-wrap">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="citizen-profile-avatar-img">
                @else
                    <div class="citizen-profile-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <label class="citizen-avatar-edit" for="avatar-input" title="Change photo">
                    <i class="bi bi-camera-fill"></i>
                </label>
                <form id="avatar-form" action="{{ route('citizen.profile.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="avatar-input" name="avatar" accept="image/jpeg,image/png,image/webp" hidden>
                </form>
            </div>
            <div class="citizen-profile-main">
                <h2 class="citizen-profile-name">{{ $user->name }}</h2>
                <div class="citizen-profile-email">{{ $user->email }}</div>
                <div class="citizen-profile-badges">
                    <span class="citizen-badge is-primary"><i class="bi bi-person-check me-1"></i>Citizen Account</span>
                    @if($user->phone)
                        <span class="citizen-badge"><i class="bi bi-phone me-1"></i>{{ $user->phone }}</span>
                    @endif
                    <span class="citizen-badge"><i class="bi bi-calendar3 me-1"></i>Joined {{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="citizen-stats-mini">
        <div class="card citizen-mini-card citizen-reveal" data-citizen-reveal>
            <div class="card-body">
                <div class="citizen-mini-icon is-info"><i class="bi bi-file-text"></i></div>
                <div class="citizen-mini-value" data-citizen-counter="{{ $totalRequests }}">{{ $totalRequests }}</div>
                <div class="citizen-mini-label">Total Requests</div>
            </div>
        </div>
        <div class="card citizen-mini-card citizen-reveal" data-citizen-reveal>
            <div class="card-body">
                <div class="citizen-mini-icon is-success"><i class="bi bi-check-circle"></i></div>
                <div class="citizen-mini-value" data-citizen-counter="{{ $completedRequests }}">{{ $completedRequests }}</div>
                <div class="citizen-mini-label">Completed</div>
            </div>
        </div>
        <div class="card citizen-mini-card citizen-reveal" data-citizen-reveal>
            <div class="card-body">
                <div class="citizen-mini-icon is-amber"><i class="bi bi-hourglass-split"></i></div>
                <div class="citizen-mini-value" data-citizen-counter="{{ $pendingRequests }}">{{ $pendingRequests }}</div>
                <div class="citizen-mini-label">In Progress</div>
            </div>
        </div>
    </div>

    <div class="citizen-profile-cols">
        <div class="card citizen-reveal" data-citizen-reveal>
            <div class="card-header">
                <span class="card-title"><i class="bi bi-person-gear me-2 text-primary"></i>Account Information</span>
            </div>
            <div class="card-body">
                <form action="{{ route('citizen.profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="citizen-form-grid">
                        <div>
                            <label class="form-label">Full Name</label>
                            <div class="citizen-input-wrap">
                                <i class="bi bi-person citizen-input-icon"></i>
                                <input type="text" class="form-control citizen-disabled-input" value="{{ $user->name }}" disabled>
                            </div>
                            <div class="form-text">Name cannot be changed. Contact support if needed.</div>
                        </div>
                        <div>
                            <label class="form-label">Email Address</label>
                            <div class="citizen-input-wrap">
                                <i class="bi bi-envelope citizen-input-icon"></i>
                                <input type="email" class="form-control citizen-disabled-input" value="{{ $user->email }}" disabled>
                            </div>
                            <div class="form-text">Email cannot be changed. Contact support if needed.</div>
                        </div>
                        <div>
                            <label class="form-label">National ID Number</label>
                            <div class="citizen-input-wrap">
                                <i class="bi bi-credit-card-2-front citizen-input-icon"></i>
                                <input type="text" class="form-control citizen-disabled-input citizen-mono" value="{{ $user->national_id ?? 'Not set' }}" disabled>
                            </div>
                            <div class="form-text">National ID cannot be changed. Contact support if needed.</div>
                        </div>
                        <div>
                            <label class="form-label">National ID Document</label>
                            @if($user->id_document)
                                <div class="citizen-id-verified">
                                    <i class="bi bi-patch-check-fill"></i>
                                    <span>ID document on file — verified at registration</span>
                                </div>
                            @else
                                <div class="citizen-id-missing">
                                    <i class="bi bi-exclamation-circle"></i>
                                    <span>No ID document uploaded. Contact support.</span>
                                </div>
                            @endif
                        </div>
                        {{-- Phone Verification (Firebase) --}}
                        <div>
                            <label class="form-label d-flex align-items-center gap-2">
                                Phone Number
                                @if($user->phone_verified_at)
                                    <span class="citizen-badge is-success" style="font-size:.65rem"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                                @elseif($user->phone)
                                    <span class="citizen-badge" style="font-size:.65rem;color:#B45309;background:#FFFBEB;border-color:#FDE68A"><i class="bi bi-exclamation-circle me-1"></i>Not verified</span>
                                @endif
                            </label>

                            {{-- Step 1: Enter phone + Send Code --}}
                            <div id="fb-step-1">
                                <div class="d-flex gap-2">
                                    <div class="citizen-input-wrap flex-grow-1">
                                        <i class="bi bi-phone citizen-input-icon"></i>
                                        <input type="tel" id="fb-phone-input" class="form-control"
                                               value="{{ $user->phone }}" placeholder="+96170551180">
                                    </div>
                                    <button type="button" id="fb-send-btn" class="btn btn-outline-primary btn-sm text-nowrap">
                                        <i class="bi bi-send me-1"></i>Send Code
                                    </button>
                                </div>
                                <div class="form-text">No spaces — e.g. +96170551180</div>
                                <div id="recaptcha-container"></div>
                                <div id="fb-send-error" class="text-danger mt-1" style="font-size:.75rem;display:none"></div>
                            </div>

                            {{-- Step 2: Enter OTP --}}
                            <div id="fb-step-2" class="citizen-otp-box d-none">
                                <div class="citizen-otp-info">
                                    <i class="bi bi-phone-vibrate"></i>
                                    <span>Code sent! Enter the 6-digit code from your SMS.</span>
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    <input type="text" id="fb-otp-input" class="form-control citizen-otp-input"
                                           maxlength="6" placeholder="_ _ _ _ _ _" autocomplete="one-time-code" inputmode="numeric">
                                    <button type="button" id="fb-verify-btn" class="btn btn-success btn-sm text-nowrap">
                                        <i class="bi bi-check2 me-1"></i>Verify
                                    </button>
                                </div>
                                <div id="fb-otp-error" class="text-danger mt-1" style="font-size:.75rem;display:none"></div>
                                <button type="button" id="fb-restart-btn" style="font-size:.73rem;color:#64748B;background:none;border:none;padding:0;margin-top:.3rem;cursor:pointer">
                                    Wrong number? Start over
                                </button>
                            </div>

                            {{-- Step 3: Success flash --}}
                            <div id="fb-step-3" class="citizen-id-verified d-none">
                                <i class="bi bi-patch-check-fill"></i>
                                <span>Phone verified! Refreshing...</span>
                            </div>
                        </div>
                        <div class="citizen-password-zone">
                            <label class="form-label citizen-password-label">Change Password <span class="fw-normal">(leave blank to keep current)</span></label>
                            <div class="citizen-password-grid">
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

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card citizen-reveal" data-citizen-reveal>
            <div class="card-header">
                <span class="card-title"><i class="bi bi-card-text me-2 text-primary"></i>Identity Information</span>
            </div>
            <div class="card-body">
                <div class="citizen-info-row">
                    <span class="citizen-info-label">National ID</span>
                    <span class="citizen-info-value citizen-mono">{{ $user->national_id ?? '-' }}</span>
                </div>
                <div class="citizen-info-row">
                    <span class="citizen-info-label">Account Type</span>
                    <span class="citizen-info-value">Citizen</span>
                </div>
                <div class="citizen-info-row">
                    <span class="citizen-info-label">Account Status</span>
                    <span class="citizen-badge {{ $user->is_active ? 'is-success' : 'is-muted' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                <div class="citizen-info-row">
                    <span class="citizen-info-label">Member Since</span>
                    <span class="citizen-info-value">{{ $user->created_at->format('F d, Y') }}</span>
                </div>
                @if($user->id_document)
                    <div class="citizen-info-row">
                        <span class="citizen-info-label">ID Document</span>
                        <span class="citizen-badge is-success"><i class="bi bi-check2 me-1"></i>Uploaded</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="card citizen-reveal" data-citizen-reveal>
            <div class="card-header">
                <span class="card-title"><i class="bi bi-credit-card me-2 text-primary"></i>Payment History</span>
            </div>
            <div class="card-body p-0">
                @forelse($paidRequests as $pr)
                    <a href="{{ route('citizen.requests.show', $pr) }}" class="citizen-activity-link">
                        <div class="citizen-activity-icon is-success">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="citizen-activity-main">
                            <div class="citizen-activity-title">{{ $pr->service->name }}</div>
                            <div class="citizen-activity-sub">{{ $pr->office->name }} &middot; {{ ucfirst($pr->payment_method ?? 'card') }} &middot; {{ $pr->updated_at->format('M d, Y') }}</div>
                        </div>
                        <div class="citizen-activity-amount">${{ number_format($pr->amount_paid, 2) }}</div>
                    </a>
                @empty
                    <div class="citizen-panel-empty">
                        <i class="bi bi-credit-card"></i>
                        <p>No payments yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="card citizen-reveal" data-citizen-reveal>
            <div class="card-header d-flex align-items-center justify-content-between gap-2">
                <span class="card-title"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Activity</span>
                <a href="{{ route('citizen.requests') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($requests as $req)
                    <a href="{{ route('citizen.requests.show', $req) }}" class="citizen-activity-link">
                        <div class="citizen-activity-icon is-primary">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <div class="citizen-activity-main">
                            <div class="citizen-activity-title">{{ $req->service->name }}</div>
                            <div class="citizen-activity-sub">{{ $req->office->name }} &middot; {{ $req->created_at->diffForHumans() }}</div>
                        </div>
                        <x-status-pill :status="$req->status" />
                    </a>
                @empty
                    <div class="citizen-panel-empty">
                        <i class="bi bi-clock-history"></i>
                        <p>No recent activity.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
body.es-role-citizen .citizen-profile-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

body.es-role-citizen .citizen-profile-alert {
    border-color: #FCD34D;
    background: linear-gradient(180deg, #FFFBEB 0%, #FFF7E1 100%);
}

body.es-role-citizen .citizen-profile-alert .card-body {
    display: flex;
    align-items: flex-start;
    gap: .68rem;
}

body.es-role-citizen .citizen-profile-alert i {
    color: #B45309;
    font-size: 1rem;
    margin-top: .05rem;
}

body.es-role-citizen .citizen-profile-alert-title {
    font-size: .9rem;
    font-weight: 700;
    color: #92400E;
    margin-bottom: .18rem;
}

body.es-role-citizen .citizen-profile-alert-copy {
    font-size: .79rem;
    color: #9A3412;
}

body.es-role-citizen .citizen-profile-head .card-body {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

body.es-role-citizen .citizen-profile-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}

body.es-role-citizen .citizen-profile-avatar {
    width: 4.5rem;
    height: 4.5rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #fff;
    font-size: 1.7rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    background: linear-gradient(135deg, #0EA5E9 0%, #2563EB 100%);
    border: 3px solid #fff;
    box-shadow: 0 10px 22px rgba(37, 99, 235, 0.2);
}

body.es-role-citizen .citizen-profile-avatar-img {
    width: 4.5rem;
    height: 4.5rem;
    border-radius: 999px;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 10px 22px rgba(37, 99, 235, 0.2);
}

body.es-role-citizen .citizen-avatar-edit {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 1.6rem;
    height: 1.6rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #2563EB;
    color: #fff;
    font-size: .68rem;
    cursor: pointer;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,.15);
    transition: background .18s ease, transform .18s ease;
}

body.es-role-citizen .citizen-avatar-edit:hover {
    background: #1D4ED8;
    transform: scale(1.1);
}

body.es-role-citizen .citizen-profile-main {
    flex: 1;
    min-width: 0;
}

body.es-role-citizen .citizen-profile-name {
    margin: 0 0 .12rem;
    font-size: 1.45rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: #0F172A;
}

body.es-role-citizen .citizen-profile-email {
    font-size: .84rem;
    color: #64748B;
    margin-bottom: .6rem;
}

body.es-role-citizen .citizen-profile-badges {
    display: flex;
    flex-wrap: wrap;
    gap: .38rem;
}

body.es-role-citizen .citizen-badge {
    display: inline-flex;
    align-items: center;
    padding: .24rem .62rem;
    border-radius: 999px;
    font-size: .7rem;
    font-weight: 600;
    color: #475569;
    background: #EEF2FF;
    border: 1px solid #E2E8F0;
}

body.es-role-citizen .citizen-badge.is-primary {
    color: #0369A1;
    background: #E0F2FE;
    border-color: #BAE6FD;
}

body.es-role-citizen .citizen-badge.is-success {
    color: #047857;
    background: #ECFDF5;
    border-color: #A7F3D0;
}

body.es-role-citizen .citizen-badge.is-muted {
    color: #64748B;
    background: #F1F5F9;
    border-color: #E2E8F0;
}

body.es-role-citizen .citizen-stats-mini {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: .75rem;
}

body.es-role-citizen .citizen-mini-card .card-body {
    padding: .95rem 1rem;
}

body.es-role-citizen .citizen-mini-icon {
    width: 2rem;
    height: 2rem;
    border-radius: .6rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .92rem;
    margin-bottom: .5rem;
}

body.es-role-citizen .citizen-mini-icon.is-info {
    color: #0369A1;
    background: #E0F2FE;
    border: 1px solid #BAE6FD;
}

body.es-role-citizen .citizen-mini-icon.is-success {
    color: #047857;
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
}

body.es-role-citizen .citizen-mini-icon.is-amber {
    color: #B45309;
    background: #FFFBEB;
    border: 1px solid #FDE68A;
}

body.es-role-citizen .citizen-mini-value {
    font-size: 1.6rem;
    line-height: 1;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: #0F172A;
}

body.es-role-citizen .citizen-mini-label {
    margin-top: .24rem;
    font-size: .74rem;
    color: #64748B;
}

body.es-role-citizen .citizen-profile-cols {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

body.es-role-citizen .citizen-form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: .95rem;
}

body.es-role-citizen .citizen-input-wrap {
    position: relative;
}

body.es-role-citizen .citizen-input-icon {
    position: absolute;
    left: .76rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
    font-size: .83rem;
    pointer-events: none;
}

body.es-role-citizen .citizen-input-wrap .form-control {
    padding-left: 2.2rem;
}

body.es-role-citizen .citizen-disabled-input {
    background: #F8FAFC;
    cursor: not-allowed;
    opacity: .82;
}

body.es-role-citizen .citizen-upload-zone {
    border: 1.5px dashed #BFDBFE;
    border-radius: .8rem;
    padding: 1rem;
    text-align: center;
    cursor: pointer;
    background: #F8FAFF;
    transition: border-color .2s ease, background-color .2s ease, transform .2s ease;
}

body.es-role-citizen .citizen-upload-zone:hover,
body.es-role-citizen .citizen-upload-zone.drag {
    border-color: #60A5FA;
    background: #EFF6FF;
    transform: translateY(-1px);
}

body.es-role-citizen .citizen-upload-zone input {
    display: none;
}

body.es-role-citizen .citizen-upload-icon {
    font-size: 1.35rem;
    color: #0369A1;
    margin-bottom: .3rem;
}

body.es-role-citizen .citizen-upload-title {
    font-size: .8rem;
    font-weight: 700;
    color: #0F172A;
}

body.es-role-citizen .citizen-upload-sub {
    font-size: .72rem;
    color: #64748B;
}

body.es-role-citizen .citizen-upload-preview {
    display: none;
    align-items: center;
    gap: .5rem;
    margin-top: .55rem;
    padding: .5rem .7rem;
    border-radius: .62rem;
    border: 1px solid #A7F3D0;
    background: #ECFDF5;
    color: #047857;
    font-size: .76rem;
}

body.es-role-citizen .citizen-password-zone {
    border-top: 1px solid #E2E8F0;
    padding-top: .95rem;
}

body.es-role-citizen .citizen-password-label {
    margin-bottom: .55rem;
    font-size: .82rem;
    color: #475569;
}

body.es-role-citizen .citizen-password-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: .7rem;
}

body.es-role-citizen .citizen-otp-box {
    background: #F0FDF4;
    border: 1px solid #A7F3D0;
    border-radius: .65rem;
    padding: .75rem;
}

body.es-role-citizen .citizen-otp-info {
    display: flex;
    align-items: center;
    gap: .45rem;
    font-size: .78rem;
    color: #047857;
    font-weight: 600;
}

body.es-role-citizen .citizen-otp-input {
    font-size: 1.2rem;
    letter-spacing: .3em;
    font-weight: 700;
    text-align: center;
    max-width: 160px;
}

body.es-role-citizen .citizen-id-verified,
body.es-role-citizen .citizen-id-missing {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .65rem .9rem;
    border-radius: .65rem;
    font-size: .8rem;
    font-weight: 600;
}

body.es-role-citizen .citizen-id-verified {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    color: #047857;
}

body.es-role-citizen .citizen-id-missing {
    background: #FFF7ED;
    border: 1px solid #FED7AA;
    color: #9A3412;
}

body.es-role-citizen .citizen-info-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .8rem;
    padding: .5rem 0;
    border-bottom: 1px solid #E2E8F0;
}

body.es-role-citizen .citizen-info-row:last-child {
    border-bottom: 0;
}

body.es-role-citizen .citizen-info-label {
    font-size: .77rem;
    color: #64748B;
}

body.es-role-citizen .citizen-info-value {
    font-size: .8rem;
    color: #0F172A;
    font-weight: 600;
}

body.es-role-citizen .citizen-mono {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    letter-spacing: .04em;
}

body.es-role-citizen .citizen-activity-link {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .8rem 1rem;
    border-bottom: 1px solid #E2E8F0;
    text-decoration: none;
    color: inherit;
    transition: background-color .18s ease;
}

body.es-role-citizen .citizen-activity-link:last-child {
    border-bottom: 0;
}

body.es-role-citizen .citizen-activity-link:hover {
    background: rgba(239, 246, 255, .58);
    color: inherit;
}

body.es-role-citizen .citizen-activity-icon {
    width: 2.2rem;
    height: 2.2rem;
    border-radius: .65rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

body.es-role-citizen .citizen-activity-icon.is-success {
    background: #ECFDF5;
    color: #047857;
    border: 1px solid #A7F3D0;
}

body.es-role-citizen .citizen-activity-icon.is-primary {
    background: #E0F2FE;
    color: #0369A1;
    border: 1px solid #BAE6FD;
}

body.es-role-citizen .citizen-activity-main {
    flex: 1;
    min-width: 0;
}

body.es-role-citizen .citizen-activity-title {
    font-size: .82rem;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

body.es-role-citizen .citizen-activity-sub {
    margin-top: .15rem;
    font-size: .72rem;
    color: #64748B;
}

body.es-role-citizen .citizen-activity-amount {
    font-size: .86rem;
    font-weight: 700;
    color: #047857;
    flex-shrink: 0;
}

body.es-role-citizen .citizen-panel-empty {
    padding: 2rem 1rem;
    text-align: center;
    color: #64748B;
}

body.es-role-citizen .citizen-panel-empty i {
    font-size: 1.8rem;
    color: #94A3B8;
    display: block;
    margin-bottom: .45rem;
}

body.es-role-citizen .citizen-panel-empty p {
    margin: 0;
    font-size: .82rem;
}

@media (min-width: 640px) {
    body.es-role-citizen .citizen-password-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (min-width: 992px) {
    body.es-role-citizen .citizen-profile-cols {
        grid-template-columns: 1fr 1fr;
    }

    body.es-role-citizen .citizen-profile-cols .card:last-child {
        grid-column: 1 / -1;
    }
}

@media (max-width: 767.98px) {
    body.es-role-citizen .citizen-stats-mini {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Avatar auto-upload
const avatarInput = document.getElementById('avatar-input');
if (avatarInput) {
    avatarInput.addEventListener('change', () => {
        if (avatarInput.files.length) {
            document.getElementById('avatar-form').submit();
        }
    });
}

const zone = document.getElementById('idUploadZone');
const input = document.getElementById('national_id_doc');
const preview = document.getElementById('uploadPreview');
const nameEl = document.getElementById('uploadName');
const ocrStatus = document.getElementById('ocrStatus');
const nationalIdInput = document.getElementById('national_id');
const extractEndpoint = '{{ route('citizen.profile.id-extract') }}';
const csrfToken = '{{ csrf_token() }}';

let extractionRunId = 0;

function setStatus(message, type = 'info') {
    if (!ocrStatus) return;

    const styles = {
        info: ['#EFF6FF', '#1E4080'],
        success: ['#ECFDF5', '#0D7A4E'],
        warn: ['#FFF7ED', '#9A3412'],
        error: ['#FFF1F2', '#9F1239'],
    };

    const [bg, color] = styles[type] || styles.info;
    ocrStatus.style.display = 'block';
    ocrStatus.style.background = bg;
    ocrStatus.style.color = color;
    ocrStatus.textContent = message;
}

async function runExtraction(file) {
    if (!file) return;

    const myRunId = ++extractionRunId;
    setStatus('Reading ID data...', 'info');

    const formData = new FormData();
    formData.append('national_id_document', file);

    try {
        const response = await fetch(extractEndpoint, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
            credentials: 'same-origin',
        });

        const payload = await response.json().catch(() => ({}));
        if (myRunId !== extractionRunId) return;

        if (!response.ok) {
            setStatus(payload.message || 'Could not extract fields from this ID file.', 'warn');
            return;
        }

        if (payload?.data?.national_id && nationalIdInput && !nationalIdInput.value.trim()) {
            nationalIdInput.value = payload.data.national_id;
        }

        setStatus(payload.message || 'ID fields extracted successfully.', 'success');
    } catch (error) {
        setStatus('Extraction request failed. Please check your connection and try again.', 'error');
    }
}

function handleSelectedFile(file) {
    if (!file) return;

    if (nameEl && preview) {
        nameEl.textContent = file.name;
        preview.style.display = 'flex';
    }

    runExtraction(file);
}

input?.addEventListener('change', () => {
    handleSelectedFile(input.files[0]);
});

zone?.addEventListener('dragover', (event) => {
    event.preventDefault();
    zone.classList.add('drag');
});

zone?.addEventListener('dragleave', () => {
    zone.classList.remove('drag');
});

zone?.addEventListener('drop', (event) => {
    event.preventDefault();
    zone.classList.remove('drag');

    if (event.dataTransfer.files[0]) {
        input.files = event.dataTransfer.files;
        handleSelectedFile(event.dataTransfer.files[0]);
    }
});
</script>
@endpush

@push('scripts')
{{-- Firebase Phone Auth --}}
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-auth-compat.js"></script>
<script>
if (!firebase.apps.length) {
firebase.initializeApp({
    apiKey:            "AIzaSyAWvJLrqHqRwIN84xDEePsNf9u329foFZ0",
    authDomain:        "cedargov-f6962.firebaseapp.com",
    projectId:         "cedargov-f6962",
    storageBucket:     "cedargov-f6962.firebasestorage.app",
    messagingSenderId: "777539421796",
    appId:             "1:777539421796:web:e19b77c25439d338314171",
});
}

const fbAuth       = firebase.auth();
fbAuth.useDeviceLanguage();
const fbStep1      = document.getElementById('fb-step-1');
const fbStep2      = document.getElementById('fb-step-2');
const fbStep3      = document.getElementById('fb-step-3');
const fbSendBtn    = document.getElementById('fb-send-btn');
const fbVerifyBtn  = document.getElementById('fb-verify-btn');
const fbRestartBtn = document.getElementById('fb-restart-btn');
const fbPhoneInput = document.getElementById('fb-phone-input');
const fbOtpInput   = document.getElementById('fb-otp-input');
const fbSendErr    = document.getElementById('fb-send-error');
const fbOtpErr     = document.getElementById('fb-otp-error');

let recaptchaVerifier = null;
let recaptchaWidgetId = null;
let confirmationResult = null;

function showErr(el, msg) { el.textContent = msg; el.style.display = 'block'; }
function hideErr(el)       { el.style.display = 'none'; }
function resetRecaptchaToken() {
    if (window.grecaptcha && recaptchaWidgetId !== null) {
        window.grecaptcha.reset(recaptchaWidgetId);
    }
}

async function ensureRecaptchaVerifier() {
    if (recaptchaVerifier) {
        resetRecaptchaToken();
        return recaptchaVerifier;
    }

    const container = document.getElementById('recaptcha-container');
    if (!container) {
        throw new Error('reCAPTCHA container not found on page.');
    }

    recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
        size: 'invisible',
        callback: () => {},
        'expired-callback': () => resetRecaptchaToken(),
    });

    recaptchaWidgetId = await recaptchaVerifier.render();
    return recaptchaVerifier;
}

fbSendBtn?.addEventListener('click', async () => {
    hideErr(fbSendErr);
    const phone = (fbPhoneInput?.value ?? '').trim().replace(/\s+/g, '');
    if (!phone) { showErr(fbSendErr, 'Please enter your phone number.'); return; }

    fbSendBtn.disabled = true;
    fbSendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending...';

    try {
        const appVerifier = await ensureRecaptchaVerifier();
        confirmationResult = await fbAuth.signInWithPhoneNumber(phone, appVerifier);
        fbStep1.classList.add('d-none');
        fbStep2.classList.remove('d-none');
    } catch (err) {
        fbSendBtn.disabled = false;
        fbSendBtn.innerHTML = '<i class="bi bi-send me-1"></i>Send Code';
        resetRecaptchaToken();

        if (err?.code === 'auth/invalid-app-credential') {
            showErr(fbSendErr, 'Firebase rejected reCAPTCHA token. Open the app with the exact authorized host (localhost or 127.0.0.1), then retry.');
            return;
        }

        showErr(fbSendErr, err?.message ?? 'Failed. Use format: +96170551180');
    }
});

fbVerifyBtn?.addEventListener('click', async () => {
    hideErr(fbOtpErr);
    const code = (fbOtpInput?.value ?? '').trim();
    if (code.length !== 6) { showErr(fbOtpErr, 'Enter the 6-digit code.'); return; }

    fbVerifyBtn.disabled = true;
    fbVerifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Verifying...';

    try {
        const result = await confirmationResult.confirm(code);
        const phone  = result.user.phoneNumber;

        const resp = await fetch('{{ route("citizen.profile.phone.firebase") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':  '{{ csrf_token() }}',
                'Accept':        'application/json',
            },
            body: JSON.stringify({ phone }),
        });

        if (resp.ok) {
            fbStep2.classList.add('d-none');
            fbStep3.classList.remove('d-none');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error('Server error saving phone.');
        }
    } catch (err) {
        fbVerifyBtn.disabled = false;
        fbVerifyBtn.innerHTML = '<i class="bi bi-check2 me-1"></i>Verify';
        showErr(fbOtpErr, 'Invalid code. Please try again.');
    }
});

fbRestartBtn?.addEventListener('click', () => {
    fbStep2.classList.add('d-none');
    fbStep1.classList.remove('d-none');
    if (fbOtpInput) fbOtpInput.value = '';
});
</script>
@endpush

@endsection
