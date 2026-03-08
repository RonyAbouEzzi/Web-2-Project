<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Track Request — E-Services</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary:#0052cc; --font:'Plus Jakarta Sans',sans-serif; --mono:'JetBrains Mono',monospace; }
        *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family:var(--font); min-height:100vh;
            background:linear-gradient(145deg,#0038a8 0%,#0052cc 50%,#0070f3 100%);
            display:flex; flex-direction:column; align-items:center;
            justify-content:center; padding:1.5rem 1rem;
            -webkit-font-smoothing:antialiased;
        }

        .logo-bar {
            display:flex; align-items:center; gap:.6rem;
            margin-bottom:2rem;
        }
        .logo-icon {
            width:40px; height:40px; background:rgba(255,255,255,.2);
            border:1px solid rgba(255,255,255,.3); border-radius:11px;
            display:flex; align-items:center; justify-content:center;
            font-size:1.1rem; color:#fff;
        }
        .logo-bar span { color:#fff; font-weight:800; font-size:.95rem; }

        .track-card {
            width:100%; max-width:480px;
            background:#fff; border-radius:18px;
            box-shadow:0 20px 60px rgba(0,0,0,.25);
            overflow:hidden;
        }

        .ref-header {
            background:linear-gradient(135deg,#0f1923,#1e2d3d);
            padding:1.5rem;
            display:flex; align-items:center; gap:.9rem;
        }
        .ref-icon {
            width:46px; height:46px; border-radius:12px;
            background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12);
            display:flex; align-items:center; justify-content:center;
            font-size:1.2rem; color:rgba(255,255,255,.8); flex-shrink:0;
        }
        .ref-title { color:rgba(255,255,255,.5); font-size:.72rem; font-weight:600; margin-bottom:.2rem; letter-spacing:.05em; text-transform:uppercase; }
        .ref-num { color:#fff; font-family:var(--mono); font-size:1rem; font-weight:500; }

        .card-body { padding:1.5rem; }

        /* Status indicator */
        .status-block {
            display:flex; align-items:center; gap:1rem;
            background:#f8fafc; border-radius:12px; padding:1rem 1.1rem;
            margin-bottom:1.25rem; border:1.5px solid #e5eaf0;
        }
        .status-dot {
            width:44px; height:44px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:1.1rem; flex-shrink:0;
        }
        .status-label { font-size:.72rem; color:#9ca3af; font-weight:500; margin-bottom:.15rem; }
        .status-value { font-size:1rem; font-weight:800; letter-spacing:-.01em; }

        /* Timeline */
        .timeline { display:flex; flex-direction:column; gap:0; }
        .tl-item { display:flex; gap:.75rem; }
        .tl-left { display:flex; flex-direction:column; align-items:center; flex-shrink:0; width:28px; }
        .tl-dot {
            width:28px; height:28px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:.7rem; font-weight:700; flex-shrink:0;
        }
        .tl-line { width:2px; flex:1; min-height:16px; background:#e5eaf0; margin:2px 0; }
        .tl-content { padding:.1rem 0 1rem; }
        .tl-status { font-size:.83rem; font-weight:700; color:#111827; }
        .tl-meta { font-size:.72rem; color:#9ca3af; margin-top:1px; }
        .tl-comment { font-size:.78rem; color:#6b7280; margin-top:.25rem; font-style:italic; }

        /* Info grid */
        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:.65rem; margin-top:1.25rem; }
        .info-item { background:#f8fafc; border-radius:10px; padding:.75rem; }
        .info-label { font-size:.68rem; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.2rem; }
        .info-value { font-size:.83rem; font-weight:700; color:#111827; }

        .login-cta {
            margin-top:1.25rem; padding-top:1.25rem;
            border-top:1px solid #f0f4f8;
            text-align:center;
        }
        .login-cta p { font-size:.8rem; color:#6b7280; margin-bottom:.65rem; }
        .btn-login {
            display:inline-flex; align-items:center; gap:.4rem;
            background:var(--primary); color:#fff;
            padding:.55rem 1.25rem; border-radius:8px;
            font-weight:700; font-size:.83rem; text-decoration:none;
            transition:all .15s; font-family:var(--font);
        }
        .btn-login:hover { background:#003d99; color:#fff; transform:translateY(-1px); box-shadow:0 4px 10px rgba(0,82,204,.35); }

        /* Not found state */
        .not-found {
            text-align:center; padding:2rem;
        }
        .not-found i { font-size:3rem; color:#d1d5db; display:block; margin-bottom:.75rem; }
        .not-found h3 { font-size:1rem; font-weight:800; color:#374151; margin-bottom:.4rem; }
        .not-found p { font-size:.82rem; color:#9ca3af; }

        footer { color:rgba(255,255,255,.4); font-size:.72rem; margin-top:1.5rem; text-align:center; }

        @media(max-width:400px) {
            .info-grid { grid-template-columns:1fr; }
            .ref-num { font-size:.88rem; }
        }
    </style>
</head>
<body>

<div class="logo-bar">
    <div class="logo-icon"><i class="bi bi-building-check"></i></div>
    <span>E-Services Government Portal</span>
</div>

<div class="track-card">
    @if(isset($req))

    <div class="ref-header">
        <div class="ref-icon"><i class="bi bi-qr-code-scan"></i></div>
        <div>
            <div class="ref-title">Request Reference</div>
            <div class="ref-num">{{ $req->reference_number }}</div>
        </div>
    </div>

    <div class="card-body">

        {{-- Current status --}}
        @php
            $statusConfig = [
                'pending'            => ['icon'=>'bi-hourglass-split','color'=>'#d97706','bg'=>'#fffbeb','label'=>'Pending Review'],
                'in_review'          => ['icon'=>'bi-search',         'color'=>'#2563eb','bg'=>'#eff6ff','label'=>'In Review'],
                'missing_documents'  => ['icon'=>'bi-exclamation-circle','color'=>'#dc2626','bg'=>'#fef2f2','label'=>'Missing Documents'],
                'approved'           => ['icon'=>'bi-check-circle',   'color'=>'#16a34a','bg'=>'#f0fdf4','label'=>'Approved'],
                'rejected'           => ['icon'=>'bi-x-circle',       'color'=>'#dc2626','bg'=>'#fef2f2','label'=>'Rejected'],
                'completed'          => ['icon'=>'bi-patch-check',    'color'=>'#065f46','bg'=>'#d1fae5','label'=>'Completed'],
            ];
            $sc = $statusConfig[$req->status] ?? ['icon'=>'bi-circle','color'=>'#6b7280','bg'=>'#f3f4f6','label'=>ucfirst($req->status)];
        @endphp

        <div class="status-block">
            <div class="status-dot" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }}">
                <i class="bi {{ $sc['icon'] }}"></i>
            </div>
            <div>
                <div class="status-label">Current Status</div>
                <div class="status-value" style="color:{{ $sc['color'] }}">{{ $sc['label'] }}</div>
            </div>
        </div>

        {{-- Timeline --}}
        @if($req->statusLogs->count())
        <div style="margin-bottom:1rem">
            <div style="font-size:.75rem;font-weight:700;color:#374151;margin-bottom:.75rem;letter-spacing:.03em">STATUS HISTORY</div>
            <div class="timeline">
                @foreach($req->statusLogs->sortByDesc('created_at') as $log)
                @php $lsc = $statusConfig[$log->to_status] ?? ['icon'=>'bi-arrow-right','color'=>'#6b7280','bg'=>'#f3f4f6']; @endphp
                <div class="tl-item">
                    <div class="tl-left">
                        <div class="tl-dot" style="background:{{ $lsc['bg'] }};color:{{ $lsc['color'] }}">
                            <i class="bi {{ $lsc['icon'] }}" style="font-size:.65rem"></i>
                        </div>
                        @if(!$loop->last)<div class="tl-line"></div>@endif
                    </div>
                    <div class="tl-content">
                        <div class="tl-status">{{ $statusConfig[$log->to_status]['label'] ?? ucfirst(str_replace('_',' ',$log->to_status)) }}</div>
                        <div class="tl-meta">{{ $log->created_at->format('M d, Y \a\t H:i') }}</div>
                        @if($log->comment)<div class="tl-comment">"{{ $log->comment }}"</div>@endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Request Info --}}
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Service</div>
                <div class="info-value">{{ $req->service->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Office</div>
                <div class="info-value">{{ $req->office->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Submitted</div>
                <div class="info-value">{{ $req->created_at->format('M d, Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Payment</div>
                <div class="info-value" style="color:{{ $req->payment_status==='paid' ? '#16a34a' : '#dc2626' }}">
                    {{ ucfirst($req->payment_status) }}
                </div>
            </div>
        </div>

        <div class="login-cta">
            <p>Sign in to manage your request, upload documents, and chat with the office.</p>
            <a href="{{ route('login') }}" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Sign In to My Account
            </a>
        </div>
    </div>

    @else

    <div class="card-body">
        <div class="not-found">
            <i class="bi bi-search"></i>
            <h3>Request Not Found</h3>
            <p>The QR code reference <code style="background:#f3f4f6;padding:.1em .4em;border-radius:4px;font-size:.85em">{{ $reference ?? '' }}</code> does not match any request in our system.</p>
        </div>
        <div class="login-cta">
            <a href="{{ route('login') }}" class="btn-login">
                <i class="bi bi-house"></i> Go to Portal
            </a>
        </div>
    </div>

    @endif
</div>

<footer>&copy; {{ date('Y') }} E-Services Government Portal</footer>

</body>
</html>
