<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Municipal e-services platform for Lebanese municipalities.">
    <title>Municipal E-Services Platform</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0D9488;
            --primary-dark: #0b7f75;
            --primary-deeper: #0a6e66;
            --primary-soft: #ccfbf1;
            --bg: #f5f5f4;
            --surface: #ffffff;
            --border: #e7e5e4;
            --text: #1c1917;
            --muted: #6b7280;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text);
            background: var(--bg);
        }

        /* ── Navbar ──────────────────────── */
        .site-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(255,255,255,0.85);
            border-bottom: 1px solid var(--border);
            padding: 0.7rem 0;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            color: inherit;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1rem;
        }

        .brand-name {
            font-weight: 800;
            font-size: 0.95rem;
        }

        .brand-tagline {
            color: var(--muted);
            font-size: 0.68rem;
        }

        /* ── Hero ────────────────────────── */
        .hero {
            background: linear-gradient(165deg, #0D9488 0%, #0f766e 40%, #115e59 100%);
            padding: 4.5rem 0 3.5rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -80px;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -200px;
            left: -100px;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }

        .hero-inner {
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(4px);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .hero h1 {
            font-size: clamp(2rem, 4.5vw, 3.2rem);
            font-weight: 800;
            line-height: 1.12;
            letter-spacing: -0.025em;
            color: #fff;
            margin-bottom: 1.2rem;
            max-width: 650px;
        }

        .hero p {
            color: rgba(255,255,255,0.8);
            font-size: 1.05rem;
            line-height: 1.7;
            max-width: 540px;
            margin-bottom: 2rem;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 3rem;
        }

        .hero-actions .btn-light {
            background: #fff;
            border: none;
            color: var(--primary-dark);
            font-weight: 700;
            padding: 0.65rem 1.6rem;
            border-radius: 0.55rem;
            font-size: 0.9rem;
        }

        .hero-actions .btn-light:hover {
            background: #f0fdfa;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
        }

        .hero-actions .btn-outline-light {
            border: 1.5px solid rgba(255,255,255,0.5);
            color: #fff;
            font-weight: 600;
            padding: 0.65rem 1.6rem;
            border-radius: 0.55rem;
            font-size: 0.9rem;
            background: transparent;
        }

        .hero-actions .btn-outline-light:hover {
            background: rgba(255,255,255,0.12);
            border-color: rgba(255,255,255,0.8);
        }

        /* Hero stat strip */
        .hero-stats {
            display: flex;
            gap: 2.5rem;
            flex-wrap: wrap;
        }

        .hero-stat {
            display: flex;
            flex-direction: column;
        }

        .hero-stat-value {
            font-size: 1.3rem;
            font-weight: 800;
            color: #fff;
        }

        .hero-stat-label {
            font-size: 0.74rem;
            color: rgba(255,255,255,0.6);
            margin-top: 0.1rem;
        }

        /* ── Features ────────────────────── */
        .section {
            padding: 4rem 0;
        }

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: var(--primary-soft);
            color: var(--primary-dark);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.7rem;
            border-radius: 50px;
            margin-bottom: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.015em;
            margin-bottom: 0.5rem;
        }

        .section-desc {
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.65;
            max-width: 520px;
            margin-bottom: 2.5rem;
        }

        .feature-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.5rem;
            height: 100%;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .feature-card:hover {
            border-color: #99f6e4;
            box-shadow: 0 8px 25px rgba(13,148,136,0.06);
        }

        .feature-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            margin-bottom: 1rem;
        }

        .fi-teal { background: #ccfbf1; color: #0f766e; }
        .fi-sky { background: #e0f2fe; color: #0369a1; }
        .fi-amber { background: #fef3c7; color: #b45309; }
        .fi-violet { background: #ede9fe; color: #6d28d9; }
        .fi-emerald { background: #dcfce7; color: #047857; }
        .fi-rose { background: #ffe4e6; color: #be123c; }

        .feature-card h3 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .feature-card p {
            color: var(--muted);
            font-size: 0.84rem;
            line-height: 1.6;
            margin: 0;
        }

        /* ── How It Works ────────────────── */
        .steps-section {
            background: #fff;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .step-item {
            display: flex;
            gap: 1.25rem;
            align-items: flex-start;
            padding: 1.25rem 0;
        }

        .step-item + .step-item {
            border-top: 1px solid #f0efee;
        }

        .step-num {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            font-weight: 800;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .step-item h4 {
            font-size: 0.92rem;
            font-weight: 700;
            margin: 0 0 0.25rem;
        }

        .step-item p {
            font-size: 0.82rem;
            color: var(--muted);
            margin: 0;
            line-height: 1.6;
        }

        /* ── Roles ───────────────────────── */
        .role-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.5rem;
            height: 100%;
            transition: border-color 0.2s;
        }

        .role-card:hover {
            border-color: #99f6e4;
        }

        .role-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-bottom: 0.85rem;
        }

        .role-card h3 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.7rem;
        }

        .role-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .role-card li {
            font-size: 0.82rem;
            color: #44403c;
            padding: 0.3rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .role-card li .bi-check-circle-fill {
            color: var(--primary);
            font-size: 0.72rem;
            flex-shrink: 0;
        }

        /* ── CTA Banner ──────────────────── */
        .cta-banner {
            background: linear-gradient(135deg, #0D9488 0%, #115e59 100%);
            border-radius: 16px;
            padding: 3rem 2.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .cta-banner::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }

        .cta-banner h2 {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            position: relative;
        }

        .cta-banner p {
            color: rgba(255,255,255,0.75);
            font-size: 0.92rem;
            margin-bottom: 1.5rem;
            max-width: 450px;
            position: relative;
        }

        .cta-banner .btn {
            position: relative;
        }

        /* ── Footer ──────────────────────── */
        .site-footer {
            padding: 2rem 0;
            border-top: 1px solid var(--border);
        }

        .footer-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            color: #78716c;
            font-size: 0.78rem;
        }

        /* ── Responsive ──────────────────── */
        @media (max-width: 767.98px) {
            .hero { padding: 3rem 0 2.5rem; }
            .hero h1 { font-size: 1.8rem; }
            .hero p { font-size: 0.92rem; }
            .hero-stats { gap: 1.5rem; }
            .section { padding: 3rem 0; }
            .section-title { font-size: 1.4rem; }
            .cta-banner { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

    {{-- ── Navigation ──────────────────────────────── --}}
    <header class="site-nav">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('home') }}" class="brand">
                    <span class="brand-mark"><i class="bi bi-building-check"></i></span>
                    <span>
                        <span class="brand-name d-block">Municipal E-Services</span>
                        <span class="brand-tagline d-block">Lebanese Municipalities</span>
                    </span>
                </a>
                <div class="d-flex gap-2 align-items-center">
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary px-3" style="border-radius:.45rem; font-weight:600;">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-sm px-3" style="border-radius:.45rem; font-weight:600; background:var(--primary); border-color:var(--primary); color:#fff;">Get started</a>
                </div>
            </div>
        </div>
    </header>

    {{-- ── Hero ────────────────────────────────────── --}}
    <section class="hero">
        <div class="container hero-inner">
            <span class="hero-badge"><i class="bi bi-shield-check"></i> Official Government Platform</span>
            <h1>Access municipal services without visiting the office.</h1>
            <p>Submit requests, upload documents, pay fees, and track your application status — all online. Built for citizens, municipal offices, and administrators across Lebanon.</p>
            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn btn-light"><i class="bi bi-arrow-right me-1"></i> Create free account</a>
                <a href="{{ route('login') }}" class="btn btn-outline-light">Sign in to your account</a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <span class="hero-stat-value">24/7</span>
                    <span class="hero-stat-label">Online availability</span>
                </div>
                <div class="hero-stat">
                    <span class="hero-stat-value">Real-time</span>
                    <span class="hero-stat-label">Status notifications</span>
                </div>
                <div class="hero-stat">
                    <span class="hero-stat-value">Secure</span>
                    <span class="hero-stat-label">2FA & encrypted data</span>
                </div>
                <div class="hero-stat">
                    <span class="hero-stat-value">Multi-office</span>
                    <span class="hero-stat-label">All municipalities</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Features ────────────────────────────────── --}}
    <section class="section">
        <div class="container">
            <span class="section-label"><i class="bi bi-grid-3x3-gap-fill"></i> Platform capabilities</span>
            <h2 class="section-title">Everything you need in one place</h2>
            <p class="section-desc">A complete digital workflow replacing in-person visits with secure, trackable online processes.</p>

            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon fi-teal"><i class="bi bi-credit-card-2-front"></i></span>
                        <h3>Online payments</h3>
                        <p>Pay service fees directly through the platform. Supports standard and cryptocurrency payment methods.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon fi-sky"><i class="bi bi-diagram-3"></i></span>
                        <h3>Request tracking</h3>
                        <p>Every request gets a unique reference number with a full status timeline visible to both citizens and offices.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon fi-amber"><i class="bi bi-calendar-check"></i></span>
                        <h3>Appointment scheduling</h3>
                        <p>Book and manage appointments tied to your service requests with confirmation and reminders.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon fi-violet"><i class="bi bi-bell"></i></span>
                        <h3>Smart notifications</h3>
                        <p>Get alerted on status changes, required documents, payment deadlines, and appointment confirmations.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon fi-emerald"><i class="bi bi-file-earmark-arrow-up"></i></span>
                        <h3>Document uploads</h3>
                        <p>Attach supporting documents digitally when submitting requests. Offices can request additional files.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon fi-rose"><i class="bi bi-chat-left-text"></i></span>
                        <h3>Direct messaging</h3>
                        <p>Communicate with the processing office about your request through secure in-platform messaging.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── How It Works ────────────────────────────── --}}
    <section class="steps-section section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <span class="section-label"><i class="bi bi-signpost-split"></i> How it works</span>
                    <h2 class="section-title">Simple, predictable process</h2>
                    <p class="section-desc mb-0">From registration to service completion, every step is transparent and trackable.</p>
                </div>
                <div class="col-lg-7 offset-lg-1">
                    <div class="step-item">
                        <span class="step-num">1</span>
                        <div>
                            <h4>Register and verify your profile</h4>
                            <p>Create an account with email or social login. Set up your profile and optionally enable two-factor authentication.</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <span class="step-num">2</span>
                        <div>
                            <h4>Select a municipality and submit a request</h4>
                            <p>Browse offices, review available services, and submit your request with any required supporting documents.</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <span class="step-num">3</span>
                        <div>
                            <h4>Track progress and pay fees</h4>
                            <p>Monitor your request status in real time. When the office sets a fee, complete payment online to proceed.</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <span class="step-num">4</span>
                        <div>
                            <h4>Get your result</h4>
                            <p>Receive outcome digitally, download receipts and certificates, or attend a scheduled appointment at the office.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Built For Every Role ────────────────────── --}}
    <section class="section">
        <div class="container">
            <div class="text-center mb-4">
                <span class="section-label"><i class="bi bi-people"></i> Multi-role platform</span>
                <h2 class="section-title">Designed for every user in the process</h2>
                <p class="section-desc mx-auto">Each role gets a tailored dashboard with tools specific to their tasks.</p>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="role-card">
                        <span class="role-icon fi-teal"><i class="bi bi-person"></i></span>
                        <h3>Citizens</h3>
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> Submit and track service requests</li>
                            <li><i class="bi bi-check-circle-fill"></i> Pay fees and download receipts</li>
                            <li><i class="bi bi-check-circle-fill"></i> Book appointments with offices</li>
                            <li><i class="bi bi-check-circle-fill"></i> Receive real-time notifications</li>
                            <li><i class="bi bi-check-circle-fill"></i> Message offices about requests</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="role-card">
                        <span class="role-icon fi-sky"><i class="bi bi-buildings"></i></span>
                        <h3>Office Users</h3>
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> Review and process requests</li>
                            <li><i class="bi bi-check-circle-fill"></i> Manage services and fees</li>
                            <li><i class="bi bi-check-circle-fill"></i> Handle appointments and schedules</li>
                            <li><i class="bi bi-check-circle-fill"></i> Respond to citizen feedback</li>
                            <li><i class="bi bi-check-circle-fill"></i> Generate reports and documents</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="role-card">
                        <span class="role-icon fi-amber"><i class="bi bi-shield-lock"></i></span>
                        <h3>Administrators</h3>
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> Manage municipalities and offices</li>
                            <li><i class="bi bi-check-circle-fill"></i> Oversee all platform users</li>
                            <li><i class="bi bi-check-circle-fill"></i> Monitor requests and revenue</li>
                            <li><i class="bi bi-check-circle-fill"></i> View analytics and reports</li>
                            <li><i class="bi bi-check-circle-fill"></i> Configure platform settings</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA Banner ──────────────────────────────── --}}
    <section class="section pt-0">
        <div class="container">
            <div class="cta-banner">
                <h2>Ready to get started?</h2>
                <p>Join the platform and access municipal services from the comfort of your home. Registration takes less than a minute.</p>
                <a href="{{ route('register') }}" class="btn btn-light btn-lg" style="font-weight:700; border-radius:.55rem; color:var(--primary-dark);">
                    <i class="bi bi-arrow-right me-1"></i> Create your free account
                </a>
            </div>
        </div>
    </section>

    {{-- ── Footer ──────────────────────────────────── --}}
    <footer class="site-footer">
        <div class="container">
            <div class="footer-inner">
                <span>Municipal E-Services Platform &mdash; Lebanese Municipalities</span>
                <span>&copy; {{ now()->year }} All rights reserved</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
