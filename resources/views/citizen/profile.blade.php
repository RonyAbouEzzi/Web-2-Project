@extends('layouts.app')
@section('title','My Profile')
@section('page-title','My Profile')

@section('content')

@php
    $missingFields = $user->missingCitizenProfileFields();
@endphp

<div style="display:grid;grid-template-columns:1fr;gap:1.25rem" class="profile-grid">

    @if(!empty($missingFields))
    <div class="card" style="border-color:#F59E0B;background:#FFFBEB">
        <div class="card-body" style="display:flex;align-items:flex-start;gap:.75rem">
            <i class="bi bi-exclamation-triangle-fill" style="color:#B45309;font-size:1rem;margin-top:.1rem"></i>
            <div>
                <div style="font-size:.88rem;font-weight:700;color:#92400E;margin-bottom:.18rem">Complete your profile to submit requests</div>
                <div style="font-size:.79rem;color:#B45309">
                    Missing:
                    {{ implode(', ', $missingFields) }}.
                    Fill the fields below, then save.
                </div>
            </div>
        </div>
    </div>
    @endif

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
                <form action="{{ route('citizen.profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
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
                        <div>
                            <label class="form-label">National ID Number</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-credit-card-2-front ii"></i>
                                <input type="text" id="national_id" name="national_id" class="form-control" value="{{ old('national_id', $user->national_id) }}" placeholder="LB-XXXXXXXXX" required>
                            </div>
                            <div class="form-text">Required to complete your account verification.</div>
                        </div>
                        <div>
                            <label class="form-label">National ID Document <span style="font-weight:400;color:var(--ink-400)">(photo or scan)</span></label>
                            <div class="upload-zone" id="idUploadZone" onclick="document.getElementById('national_id_doc').click()">
                                <div class="upload-icon"><i class="bi bi-cloud-upload"></i></div>
                                <div class="upload-title">Click to upload your ID document</div>
                                <div class="upload-sub">JPG, PNG or PDF · Max 5MB</div>
                                <input type="file" id="national_id_doc" name="national_id_document" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                            <div class="upload-preview" id="uploadPreview">
                                <i class="bi bi-file-earmark-check"></i>
                                <span id="uploadName">File selected</span>
                            </div>
                            <div id="ocrStatus" style="display:none;margin-top:.55rem;font-size:.75rem;border-radius:8px;padding:.45rem .6rem;"></div>
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
                @if($user->id_document)
                <div class="info-row">
                    <span class="ir-label">ID Document</span>
                    <span class="sbadge s-approved"><i class="bi bi-check2" style="margin-right:.2rem"></i>Uploaded</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Payment History --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="bi bi-credit-card me-2" style="color:var(--primary)"></i>Payment History</span>
            </div>
            <div class="card-body p0">
                @forelse($paidRequests ?? [] as $pr)
                <a href="{{ route('citizen.requests.show', $pr) }}"
                   style="display:flex;align-items:center;gap:.85rem;padding:.85rem 1.2rem;border-bottom:1px solid var(--ink-100);text-decoration:none;color:inherit;transition:background .12s"
                   onmouseover="this.style.background='var(--ink-50)'" onmouseout="this.style.background='transparent'">
                    <div style="width:36px;height:36px;border-radius:9px;background:#f0fdf4;color:#16a34a;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $pr->service->name }}</div>
                        <div style="font-size:.71rem;color:var(--ink-400)">{{ $pr->office->name }} &middot; {{ ucfirst($pr->payment_method ?? 'card') }} &middot; {{ $pr->updated_at->format('M d, Y') }}</div>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <div style="font-size:.85rem;font-weight:700;color:#16a34a">${{ number_format($pr->amount_paid, 2) }}</div>
                    </div>
                </a>
                @empty
                <div class="empty-state" style="padding:2rem 1rem">
                    <div class="empty-icon"><i class="bi bi-credit-card"></i></div>
                    <p style="font-size:.82rem;color:var(--ink-400)">No payments yet.</p>
                </div>
                @endforelse
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
.upload-zone {
    border: 2px dashed var(--ink-200);
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    cursor: pointer;
    transition: all .18s;
    background: var(--white);
}
.upload-zone:hover,
.upload-zone.drag {
    border-color: var(--primary);
    background: var(--primary-lt);
}
.upload-zone input { display: none; }
.upload-icon {
    font-size: 1.4rem;
    color: var(--ink-300);
    margin-bottom: .35rem;
}
.upload-title {
    font-size: .79rem;
    font-weight: 600;
    color: var(--ink-700);
}
.upload-sub {
    font-size: .71rem;
    color: var(--ink-400);
}
.upload-preview {
    display: none;
    align-items: center;
    gap: .55rem;
    margin-top: .55rem;
    padding: .5rem .7rem;
    border-radius: 8px;
    background: #ECFDF5;
    color: #0D7A4E;
    font-size: .76rem;
}
.upload-preview i { font-size: .95rem; }
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

@push('scripts')
<script>
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
@endsection
