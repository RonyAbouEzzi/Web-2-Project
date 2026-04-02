<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Submit requests, track progress, pay fees, and receive official documents from Lebanese municipalities — all online.">
    <title>E-Services Lebanon — Municipal Digital Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --bg:      #F9F6F1;
        --surface: #FFFFFF;
        --border:  #E5E0D8;
        --text:    #1C1917;
        --muted:   #78716C;
        --label:   #A8A29E;
        --teal:    #0D9488;
        --teal-dk: #0b7f75;
        --font:    'Inter', system-ui, sans-serif;
        --serif:   'Inter', system-ui, sans-serif;
    }

    html { scroll-behavior: smooth; }
    body {
        font-family: var(--font);
        background: var(--bg);
        color: var(--text);
        -webkit-font-smoothing: antialiased;
        min-height: 100vh;
    }

    /* ─── NAV ─────────────────────────────────────────── */
    .nav {
        position: sticky; top: 0; z-index: 50;
        padding: .9rem 0;
        background: rgba(249,246,241,.88);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid var(--border);
    }
    .nav-inner {
        max-width: 1100px; margin: 0 auto; padding: 0 2rem;
        display: flex; align-items: center; justify-content: space-between;
    }
    .brand { display: flex; align-items: center; gap: .7rem; text-decoration: none; color: var(--text); }
    .brand-icon {
        width: 36px; height: 36px; border-radius: 9px;
        background: var(--text);
        display: flex; align-items: center; justify-content: center;
        color: var(--bg); font-size: .9rem; flex-shrink: 0;
    }
    .brand-name  { font-family: var(--serif); font-weight: 700; font-size: .95rem; }
    .brand-sub   { font-size: .67rem; color: var(--label); display: block; margin-top: 1px; letter-spacing: .03em; }
    .nav-links { display: flex; align-items: center; gap: .5rem; }
    .btn-ghost {
        padding: .4rem 1rem; border-radius: .4rem;
        border: 1px solid var(--border); background: transparent;
        color: var(--muted); font-family: var(--font); font-size: .82rem; font-weight: 600;
        text-decoration: none; transition: all .15s; cursor: pointer;
    }
    .btn-ghost:hover { border-color: #c7c0b7; color: var(--text); }
    .btn-dark {
        padding: .42rem 1.1rem; border-radius: .4rem;
        background: var(--text); border: none; color: var(--bg);
        font-family: var(--font); font-size: .82rem; font-weight: 600;
        text-decoration: none; transition: all .15s; cursor: pointer;
    }
    .btn-dark:hover { background: #2d2420; }

    /* ─── HERO ────────────────────────────────────────── */
    .hero {
        position: relative; overflow: hidden;
        padding: 5.5rem 0 4rem;
        background: var(--bg);
    }
    /* Warm amber glow — top right */
    .hero-glow {
        position: absolute; pointer-events: none;
        top: -140px; right: -140px;
        width: 680px; height: 580px; border-radius: 50%;
        background: radial-gradient(circle at 50% 40%,
            rgba(253,224,130,.55) 0%,
            rgba(254,243,199,.35) 35%,
            transparent 70%);
        z-index: 0;
    }
    .hero-inner {
        position: relative; z-index: 1;
        max-width: 1100px; margin: 0 auto; padding: 0 2rem;
        display: grid; grid-template-columns: 1fr 380px; gap: 4rem; align-items: start;
    }
    .hero-eyebrow {
        font-size: .68rem; font-weight: 600; letter-spacing: .14em;
        text-transform: uppercase; color: var(--label);
        margin-bottom: 1.1rem; display: block;
    }
    .hero-h1 {
        font-family: var(--serif); font-weight: 800;        font-size: clamp(2.6rem, 5.5vw, 4rem);
        line-height: 1.06; letter-spacing: -.03em;
        color: var(--text); margin-bottom: 1.25rem;
        max-width: 14ch;
    }
    .hero-desc {
        font-size: .97rem; line-height: 1.78; color: var(--muted);
        max-width: 46ch; margin-bottom: 2rem;
    }
    .hero-actions { display: flex; flex-wrap: wrap; gap: .6rem; margin-bottom: 2.5rem; }
    .btn-primary-hero {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .65rem 1.5rem; border-radius: .4rem;
        background: var(--text); border: none; color: #fff;
        font-family: var(--font); font-size: .88rem; font-weight: 700;
        text-decoration: none; transition: all .18s;
    }
    .btn-primary-hero:hover { background: #2d2420; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(0,0,0,.18); }
    .btn-outline-hero {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .65rem 1.5rem; border-radius: .4rem;
        border: 1px solid var(--border); background: transparent;
        color: var(--muted); font-family: var(--font);
        font-size: .88rem; font-weight: 600;
        text-decoration: none; transition: all .15s;
    }
    .btn-outline-hero:hover { border-color: #c7c0b7; color: var(--text); }
    /* Trust row */
    .hero-trust { display: flex; flex-wrap: wrap; gap: 1.25rem; }
    .trust-item {
        display: flex; align-items: center; gap: .38rem;
        font-size: .77rem; color: var(--label);
    }
    .trust-item i { font-size: .75rem; color: var(--teal); }

    /* Hero right panel */
    .hero-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,.06), 0 20px 50px rgba(0,0,0,.05);
    }
    .hp-top {
        padding: 1.25rem 1.4rem .75rem;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: baseline; justify-content: space-between;
    }
    .hp-eyebrow {
        font-size: .62rem; letter-spacing: .12em; text-transform: uppercase;
        color: var(--label); font-weight: 600;
    }
    .hp-live {
        display: flex; align-items: center; gap: .3rem;
        font-size: .62rem; color: var(--teal); font-weight: 600;
    }
    .hp-live-dot {
        width: 5px; height: 5px; border-radius: 50%;
        background: var(--teal); animation: pulse 2s infinite;
    }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
    .hp-stats { display: grid; grid-template-columns: 1fr 1fr; }
    .hp-stat {
        padding: 1rem 1.4rem;
        border-bottom: 1px solid var(--border);
    }
    .hp-stat:nth-child(odd) { border-right: 1px solid var(--border); }
    .hp-stat-label {
        font-size: .6rem; letter-spacing: .1em; text-transform: uppercase;
        color: var(--label); font-weight: 600; margin-bottom: .3rem;
    }
    .hp-stat-val {
        font-family: var(--serif); font-weight: 800;
        font-size: 1.65rem; line-height: 1; color: var(--text);
    }
    .hp-stat-val.teal { color: var(--teal); }
    .hp-requests { padding: .9rem 1.4rem; }
    .hp-req-label {
        font-size: .6rem; letter-spacing: .1em; text-transform: uppercase;
        color: var(--label); font-weight: 600; margin-bottom: .65rem;
    }
    .hp-req {
        display: flex; align-items: center; justify-content: space-between;
        padding: .45rem 0;
        border-bottom: 1px solid #f0ece7;
    }
    .hp-req:last-child { border-bottom: none; }
    .hp-req-ref { font-size: .73rem; color: var(--muted); font-weight: 600; }
    .hp-req-svc { font-size: .68rem; color: var(--label); }
    .hp-pill {
        font-size: .58rem; font-weight: 700; letter-spacing: .05em;
        text-transform: uppercase; padding: .15rem .5rem; border-radius: 99px;
        border: 1px solid;
    }
    .hp-pill.approved { color: #0f766e; border-color: #99f6e4; background: #f0fdfb; }
    .hp-pill.review   { color: #0369a1; border-color: #bae6fd; background: #f0f9ff; }
    .hp-pill.pending  { color: #b45309; border-color: #fde68a; background: #fffbeb; }

    /* ─── STATS STRIP ─────────────────────────────────── */
    .stats-strip {
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        background: var(--surface);
        padding: 2.75rem 0;
    }
    .stats-inner {
        max-width: 1100px; margin: 0 auto; padding: 0 2rem;
        display: grid; grid-template-columns: repeat(4,1fr);
        divide-x: var(--border);
    }
    .stat-cell {
        text-align: center;
        padding: 0 1rem;
        border-right: 1px solid var(--border);
    }
    .stat-cell:last-child { border-right: none; }
    .stat-eyebrow {
        font-size: .62rem; letter-spacing: .12em; text-transform: uppercase;
        color: var(--label); font-weight: 600; margin-bottom: .5rem;
        display: block;
    }
    .stat-number {
        font-family: var(--serif); font-weight: 800;        font-size: 2.4rem; line-height: 1; color: var(--text);
        display: block;
    }
    .stat-suffix { color: var(--teal); }

    /* ─── SECTION COMMONS ──────────────────────────────── */
    .section { padding: 5rem 0; }
    .wrap { max-width: 1100px; margin: 0 auto; padding: 0 2rem; }
    .sec-eyebrow {
        font-size: .65rem; letter-spacing: .14em; text-transform: uppercase;
        color: var(--label); font-weight: 600; display: block; margin-bottom: .7rem;
    }
    .sec-h2 {
        font-family: var(--serif); font-weight: 800;        font-size: clamp(1.65rem, 3vw, 2.3rem); line-height: 1.1;
        letter-spacing: -.025em; color: var(--text); margin-bottom: .7rem;
    }
    .sec-lead {
        font-size: .95rem; line-height: 1.75; color: var(--muted);
        max-width: 50ch; margin-bottom: 3rem;
    }

    /* ─── FEATURES ─────────────────────────────────────── */
    .features-grid {
        display: grid; grid-template-columns: repeat(3,1fr); gap: 1px;
        background: var(--border); border: 1px solid var(--border); border-radius: 14px;
        overflow: hidden;
    }
    .feat {
        background: var(--surface); padding: 1.65rem 1.5rem;
        transition: background .15s;
    }
    .feat:hover { background: #fdfcfa; }
    .feat-eyebrow {
        font-size: .6rem; letter-spacing: .12em; text-transform: uppercase;
        color: var(--label); font-weight: 600; margin-bottom: .55rem; display: block;
    }
    .feat h3 { font-size: .9rem; font-weight: 700; color: var(--text); margin-bottom: .35rem; }
    .feat p  { font-size: .81rem; color: var(--muted); line-height: 1.65; margin: 0; }
    .feat-icon {
        width: 34px; height: 34px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: .88rem; margin-bottom: .85rem;
        border: 1px solid var(--border); background: var(--bg); color: var(--muted);
    }

    /* ─── PROCESS ──────────────────────────────────────── */
    .process-section { background: var(--surface); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
    .process-layout {
        display: grid; grid-template-columns: 280px 1fr; gap: 5rem; align-items: start;
    }
    .process-sticky { position: sticky; top: 5rem; }
    .process-step {
        display: grid; grid-template-columns: 48px 1fr; gap: 1rem;
        padding: 1.35rem 0; border-bottom: 1px solid var(--border);
        align-items: start;
    }
    .process-step:last-child { border-bottom: none; }
    .process-num {
        font-family: var(--serif); font-weight: 800;        font-size: 1.3rem; color: var(--label); line-height: 1;
        padding-top: .05rem;
    }
    .process-step h3 { font-size: .88rem; font-weight: 700; color: var(--text); margin-bottom: .3rem; }
    .process-step p  { font-size: .8rem; color: var(--muted); line-height: 1.65; margin: 0; }

    /* ─── ROLES ─────────────────────────────────────────── */
    .roles-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.25rem; }
    .role-card {
        border: 1px solid var(--border); border-radius: 14px;
        padding: 1.65rem; background: var(--surface);
        transition: box-shadow .2s, border-color .2s;
    }
    .role-card:hover { border-color: #c7c0b7; box-shadow: 0 8px 28px rgba(0,0,0,.07); }
    .role-eyebrow {
        font-size: .6rem; letter-spacing: .12em; text-transform: uppercase;
        font-weight: 600; color: var(--label); margin-bottom: .5rem; display: block;
    }
    .role-card h3 {
        font-family: var(--serif); font-weight: 800;        font-size: 1.15rem; margin-bottom: 1.1rem; color: var(--text);
    }
    .role-feature {
        display: flex; align-items: flex-start; gap: .5rem;
        font-size: .8rem; color: var(--muted); padding: .3rem 0;
        border-bottom: 1px solid #f0ece7;
    }
    .role-feature:last-child { border-bottom: none; }
    .role-feature i { font-size: .72rem; color: var(--teal); margin-top: .18rem; flex-shrink: 0; }

    /* ─── CTA ───────────────────────────────────────────── */
    .cta-section {
        padding: 5rem 0;
        position: relative; overflow: hidden;
    }
    .cta-glow {
        position: absolute; pointer-events: none;
        top: -120px; left: 50%; transform: translateX(-50%);
        width: 700px; height: 500px; border-radius: 50%;
        background: radial-gradient(circle,
            rgba(253,224,130,.4) 0%,
            rgba(254,243,199,.2) 40%,
            transparent 70%);
    }
    .cta-inner {
        position: relative; z-index: 1;
        text-align: center;
        max-width: 620px; margin: 0 auto; padding: 0 2rem;
    }
    .cta-eyebrow { font-size: .65rem; letter-spacing: .14em; text-transform: uppercase; color: var(--label); font-weight: 600; display: block; margin-bottom: .75rem; }
    .cta-h2 {
        font-family: var(--serif); font-weight: 800;        font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.03em;
        line-height: 1.08; color: var(--text); margin-bottom: .85rem;
    }
    .cta-sub { font-size: .95rem; color: var(--muted); line-height: 1.7; margin-bottom: 2rem; }
    .cta-actions { display: flex; justify-content: center; flex-wrap: wrap; gap: .65rem; }

    /* ─── FOOTER ────────────────────────────────────────── */
    .footer {
        border-top: 1px solid var(--border); padding: 1.75rem 0;
        background: var(--surface);
    }
    .footer-inner {
        max-width: 1100px; margin: 0 auto; padding: 0 2rem;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: .75rem;
    }
    .footer-brand { font-size: .78rem; color: var(--label); }
    .footer-brand strong { color: var(--muted); }
    .footer-links { display: flex; gap: 1.5rem; }
    .footer-links a { font-size: .78rem; color: var(--label); text-decoration: none; transition: color .15s; }
    .footer-links a:hover { color: var(--text); }

    /* ─── SCROLL REVEAL ─────────────────────────────────── */
    .reveal { opacity: 0; transform: translateY(16px); transition: opacity .55s ease, transform .55s ease; }
    .reveal.in { opacity: 1; transform: none; }
    .d1 { transition-delay: .08s; }
    .d2 { transition-delay: .16s; }
    .d3 { transition-delay: .24s; }
    .d4 { transition-delay: .32s; }

    /* ─── RESPONSIVE ────────────────────────────────────── */
    @media(max-width:1024px) {
        .hero-inner { grid-template-columns: 1fr; gap: 2.5rem; }
        .hero-panel { max-width: 440px; }
        .process-layout { grid-template-columns: 1fr; gap: 2rem; }
        .process-sticky { position: static; }
        .features-grid { grid-template-columns: repeat(2,1fr); }
    }
    @media(max-width:768px) {
        .stats-inner { grid-template-columns: repeat(2,1fr); gap: 2rem; }
        .stat-cell { border-right: none; border-bottom: 1px solid var(--border); padding-bottom: 1.5rem; }
        .stat-cell:nth-child(2n) { border-bottom: 1px solid var(--border); }
        .stat-cell:last-child, .stat-cell:nth-last-child(2):nth-child(odd) { border-bottom: none; }
        .roles-grid { grid-template-columns: 1fr; }
        .features-grid { grid-template-columns: 1fr; }
    }
    @media(max-width:560px) {
        .hero { padding: 4rem 0 3rem; }
        .hero-h1 { font-size: 2.4rem; }
        .nav-links .btn-ghost { display: none; }
    }
    </style>
</head>
<body>

@php
    $municipalityCount = \App\Models\Municipality::where('is_active', true)->count();
    $officeCount       = \App\Models\Office::where('is_active', true)->count();
    $serviceCount      = \App\Models\Service::where('is_active', true)->count();
    $requestCount      = \App\Models\ServiceRequest::count();
@endphp

{{-- NAV --}}
<nav class="nav">
    <div class="nav-inner">
        <a href="{{ route('home') }}" class="brand">
            <div class="brand-icon"><i class="bi bi-building-check"></i></div>
            <div>
                <span class="brand-name">E-Services</span>
                <span class="brand-sub">Lebanon Gov Portal</span>
            </div>
        </a>
        <div class="nav-links">
            <a href="{{ route('login') }}" class="btn-ghost">Sign in</a>
            <a href="{{ route('register') }}" class="btn-dark">Get started</a>
        </div>
    </div>
</nav>

{{-- HERO --}}
<section class="hero">
    <div class="hero-glow"></div>
    <div class="hero-inner">
        <div>
            <span class="hero-eyebrow">Municipal E-Services &mdash; Lebanon</span>
            <h1 class="hero-h1">Government services, made simple.</h1>
            <p class="hero-desc">Submit requests, upload documents, pay fees, and track progress through a single platform built for Lebanese municipalities. No queues, no paperwork.</p>
            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn-primary-hero">
                    Create free account <i class="bi bi-arrow-right"></i>
                </a>
                <a href="{{ route('login') }}" class="btn-outline-hero">Sign in</a>
            </div>
            <div class="hero-trust">
                <div class="trust-item"><i class="bi bi-shield-fill-check"></i> 2FA secured</div>
                <div class="trust-item"><i class="bi bi-qr-code"></i> QR tracking</div>
                <div class="trust-item"><i class="bi bi-credit-card"></i> Online payments</div>
                <div class="trust-item"><i class="bi bi-file-earmark-pdf"></i> Digital certificates</div>
            </div>
        </div>

        {{-- Platform card --}}
        <div class="hero-panel">
            <div class="hp-top">
                <span class="hp-eyebrow">Platform overview</span>
                <span class="hp-live"><span class="hp-live-dot"></span>Live</span>
            </div>
            <div class="hp-stats">
                <div class="hp-stat">
                    <div class="hp-stat-label">Municipalities</div>
                    <div class="hp-stat-val teal">{{ $municipalityCount }}</div>
                </div>
                <div class="hp-stat">
                    <div class="hp-stat-label">Active offices</div>
                    <div class="hp-stat-val">{{ $officeCount }}</div>
                </div>
                <div class="hp-stat" style="border-bottom:none;">
                    <div class="hp-stat-label">Services</div>
                    <div class="hp-stat-val">{{ $serviceCount }}</div>
                </div>
                <div class="hp-stat" style="border-bottom:none;">
                    <div class="hp-stat-label">Requests</div>
                    <div class="hp-stat-val">{{ $requestCount }}</div>
                </div>
            </div>
            <div class="hp-requests">
                <div class="hp-req-label">Recent activity</div>
                <div class="hp-req">
                    <div><div class="hp-req-ref">SRQ-2024-00041</div><div class="hp-req-svc">Birth Certificate</div></div>
                    <span class="hp-pill approved">Approved</span>
                </div>
                <div class="hp-req">
                    <div><div class="hp-req-ref">SRQ-2024-00038</div><div class="hp-req-svc">Building Permit</div></div>
                    <span class="hp-pill review">In Review</span>
                </div>
                <div class="hp-req">
                    <div><div class="hp-req-ref">SRQ-2024-00031</div><div class="hp-req-svc">Land Registration</div></div>
                    <span class="hp-pill pending">Pending</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS STRIP --}}
<div class="stats-strip">
    <div class="stats-inner">
        <div class="stat-cell reveal">
            <span class="stat-eyebrow">Municipalities</span>
            <span class="stat-number"><span class="counter" data-target="{{ $municipalityCount }}">0</span></span>
        </div>
        <div class="stat-cell reveal d1">
            <span class="stat-eyebrow">Active offices</span>
            <span class="stat-number"><span class="counter" data-target="{{ $officeCount }}">0</span></span>
        </div>
        <div class="stat-cell reveal d2">
            <span class="stat-eyebrow">Services available</span>
            <span class="stat-number"><span class="counter" data-target="{{ $serviceCount }}">0</span></span>
        </div>
        <div class="stat-cell reveal d3">
            <span class="stat-eyebrow">Requests processed</span>
            <span class="stat-number"><span class="counter" data-target="{{ $requestCount }}">0</span></span>
        </div>
    </div>
</div>

{{-- FEATURES --}}
<section class="section">
    <div class="wrap">
        <span class="sec-eyebrow reveal">Platform capabilities</span>
        <h2 class="sec-h2 reveal">Everything in one place.</h2>
        <p class="sec-lead reveal">A complete digital workflow for residents and municipal teams — replacing in-person visits with transparent, trackable online processes.</p>

        <div class="features-grid reveal">
            <div class="feat">
                <div class="feat-icon"><i class="bi bi-credit-card-2-front"></i></div>
                <span class="feat-eyebrow">Payments</span>
                <h3>Pay fees online</h3>
                <p>Card or cryptocurrency — pay service fees without visiting the office. Full payment history and receipts included.</p>
            </div>
            <div class="feat">
                <div class="feat-icon"><i class="bi bi-qr-code-scan"></i></div>
                <span class="feat-eyebrow">Tracking</span>
                <h3>QR code status tracking</h3>
                <p>Every request gets a unique reference number and QR code with a live status timeline, updated by the processing office.</p>
            </div>
            <div class="feat">
                <div class="feat-icon"><i class="bi bi-calendar-check"></i></div>
                <span class="feat-eyebrow">Scheduling</span>
                <h3>Appointment booking</h3>
                <p>Schedule office visits tied directly to your service request, with confirmation and calendar reminders.</p>
            </div>
            <div class="feat">
                <div class="feat-icon"><i class="bi bi-file-earmark-arrow-up"></i></div>
                <span class="feat-eyebrow">Documents</span>
                <h3>Upload &amp; receive files</h3>
                <p>Attach supporting documents when submitting. Receive official certificates, approvals, and receipts digitally.</p>
            </div>
            <div class="feat">
                <div class="feat-icon"><i class="bi bi-chat-left-text"></i></div>
                <span class="feat-eyebrow">Communication</span>
                <h3>Direct messaging</h3>
                <p>Communicate directly with the processing office through secure, request-linked in-platform messaging.</p>
            </div>
            <div class="feat">
                <div class="feat-icon"><i class="bi bi-bell"></i></div>
                <span class="feat-eyebrow">Notifications</span>
                <h3>Real-time alerts</h3>
                <p>Automatic notifications keep citizens and offices aligned on every status change, deadline, and required action.</p>
            </div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="section process-section">
    <div class="wrap">
        <div class="process-layout">
            <div class="process-sticky">
                <span class="sec-eyebrow reveal">How it works</span>
                <h2 class="sec-h2 reveal">Four steps, start to finish.</h2>
                <p class="sec-lead reveal" style="margin-bottom:0;">Every step is predictable, trackable, and designed to keep you informed throughout.</p>
            </div>
            <div>
                <div class="process-step reveal">
                    <div class="process-num">01</div>
                    <div>
                        <h3>Create your account</h3>
                        <p>Register with email or social login. Verify your national ID and optionally enable two-factor authentication for full platform access.</p>
                    </div>
                </div>
                <div class="process-step reveal d1">
                    <div class="process-num">02</div>
                    <div>
                        <h3>Find a service and submit</h3>
                        <p>Browse offices by municipality, select the service you need, and submit your request with the required supporting documents.</p>
                    </div>
                </div>
                <div class="process-step reveal d2">
                    <div class="process-num">03</div>
                    <div>
                        <h3>Track progress and pay</h3>
                        <p>Follow your request status in real time via dashboard or QR code. When a fee is set, pay online to keep the process moving.</p>
                    </div>
                </div>
                <div class="process-step reveal d3">
                    <div class="process-num">04</div>
                    <div>
                        <h3>Receive your outcome</h3>
                        <p>Download your certificate, receipt, or approval letter digitally — or attend a scheduled appointment at the office.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ROLES --}}
<section class="section">
    <div class="wrap">
        <span class="sec-eyebrow reveal">Who it's for</span>
        <h2 class="sec-h2 reveal">Built for every role.</h2>
        <p class="sec-lead reveal">Each user gets a tailored dashboard — only the tools that matter for their specific responsibilities.</p>

        <div class="roles-grid">
            <div class="role-card reveal">
                <span class="role-eyebrow">Citizen</span>
                <h3>For residents</h3>
                <div class="role-feature"><i class="bi bi-check2"></i>Submit and track service requests</div>
                <div class="role-feature"><i class="bi bi-check2"></i>Pay fees and download receipts</div>
                <div class="role-feature"><i class="bi bi-check2"></i>Book appointments with offices</div>
                <div class="role-feature"><i class="bi bi-check2"></i>Message the processing office</div>
                <div class="role-feature"><i class="bi bi-check2"></i>Get real-time notifications</div>
            </div>
            <div class="role-card reveal d1">
                <span class="role-eyebrow">Office staff</span>
                <h3>For municipal teams</h3>
                <div class="role-feature"><i class="bi bi-check2"></i>Review and process requests</div>
                <div class="role-feature"><i class="bi bi-check2"></i>Manage services and fees</div>
                <div class="role-feature"><i class="bi bi-check2"></i>Generate and send official PDFs</div>
                <div class="role-feature"><i class="bi bi-check2"></i>Handle appointments and feedback</div>
                <div class="role-feature"><i class="bi bi-check2"></i>Communicate directly with citizens</div>
            </div>
            <div class="role-card reveal d2">
                <span class="role-eyebrow">Administrator</span>
                <h3>For platform admins</h3>
                <div class="role-feature"><i class="bi bi-check2"></i>Manage municipalities and offices</div>
                <div class="role-feature"><i class="bi bi-check2"></i>Create and oversee office users</div>
                <div class="role-feature"><i class="bi bi-check2"></i>Monitor all requests and revenue</div>
                <div class="role-feature"><i class="bi bi-check2"></i>View analytics and reports</div>
                <div class="role-feature"><i class="bi bi-check2"></i>Toggle office and user status</div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="cta-section">
    <div class="cta-glow"></div>
    <div class="cta-inner">
        <span class="cta-eyebrow reveal">Get started today</span>
        <h2 class="cta-h2 reveal">Skip the queue.<br>Start online.</h2>
        <p class="cta-sub reveal">Create your free account in under two minutes and access all municipal services from wherever you are.</p>
        <div class="cta-actions reveal">
            <a href="{{ route('register') }}" class="btn-primary-hero">Create free account <i class="bi bi-arrow-right"></i></a>
            <a href="{{ route('login') }}" class="btn-outline-hero">Sign in</a>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="footer">
    <div class="footer-inner">
        <span class="footer-brand"><strong>E-Services Lebanon</strong> &mdash; Municipal Digital Portal &copy; {{ now()->year }}</span>
        <div class="footer-links">
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        </div>
    </div>
</footer>

<script>
// Scroll reveal
const ro = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); ro.unobserve(e.target); } });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => ro.observe(el));

// Counter animation
function animateCounter(el, target) {
    const t0 = performance.now();
    const dur = Math.min(1200 + target * 2, 1800);
    (function tick(now) {
        const p = Math.min((now - t0) / dur, 1);
        el.textContent = Math.round((1 - Math.pow(1 - p, 3)) * target);
        if (p < 1) requestAnimationFrame(tick);
    })(t0);
}
let fired = false;
const co = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting && !fired) {
        fired = true;
        document.querySelectorAll('.counter').forEach(el => animateCounter(el, +el.dataset.target || 0));
        co.disconnect();
    }
}, { threshold: 0.3 });
const strip = document.querySelector('.stats-strip');
if (strip) co.observe(strip);
</script>
</body>
</html>
