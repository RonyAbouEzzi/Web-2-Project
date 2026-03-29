<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Municipal e-services platform for Lebanese municipalities. Submit requests, track progress, and manage appointments online.">
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
            --primary-soft: #ccfbf1;
            --bg: #f5f5f4;
            --surface: #ffffff;
            --border: #e7e5e4;
            --text: #1f2937;
            --muted: #6b7280;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text);
            background: var(--bg);
            min-height: 100vh;
        }

        /* ── Top Navigation ─────────────────── */
        .site-nav {
            padding: 0.85rem 0;
            position: sticky;
            top: 0;
            z-index: 20;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(250, 250, 249, 0.9);
            border-bottom: 1px solid var(--border);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            text-decoration: none;
            color: inherit;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--primary-soft);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-dark);
            font-size: 1.05rem;
        }

        .brand-name {
            font-weight: 800;
            font-size: 0.92rem;
            line-height: 1.2;
        }

        .brand-tagline {
            color: var(--muted);
            font-size: 0.7rem;
            line-height: 1.2;
        }

        /* ── Hero ───────────────────────────── */
        .hero {
            padding: 3rem 0 2rem;
        }

        .hero-layout {
            display: grid;
            grid-template-columns: 1.35fr 0.85fr;
            gap: 1.25rem;
            align-items: stretch;
        }

        .hero-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-label {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--primary-soft);
            color: var(--primary-dark);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.7rem;
            border-radius: 6px;
            margin-bottom: 1.2rem;
            width: fit-content;
            letter-spacing: 0.03em;
        }

        .hero-heading {
            font-size: clamp(1.65rem, 3.2vw, 2.5rem);
            line-height: 1.15;
            letter-spacing: -0.015em;
            font-weight: 800;
            margin-bottom: 1rem;
            color: #1c1917;
        }

        .hero-desc {
            color: var(--muted);
            font-size: 0.92rem;
            line-height: 1.7;
            max-width: 54ch;
            margin-bottom: 1.5rem;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
        }

        .hero-actions .btn {
            border-radius: 0.5rem;
            font-weight: 700;
            padding: 0.6rem 1.35rem;
            font-size: 0.88rem;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        /* ── Side Metrics Panel ─────────────── */
        .metrics-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .metrics-panel-title {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #a8a29e;
            font-weight: 700;
            margin-bottom: 0.15rem;
        }

        .metric-block {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.85rem;
            background: #fafaf9;
        }

        .metric-block-value {
            font-weight: 800;
            font-size: 1.2rem;
            color: #0f766e;
            line-height: 1;
            margin-bottom: 0.15rem;
        }

        .metric-block-label {
            color: var(--muted);
            font-size: 0.74rem;
            line-height: 1.45;
        }

        /* ── Sections ───────────────────────── */
        .content-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1rem;
        }

        .section-heading {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            margin-bottom: 0.4rem;
            color: #1c1917;
        }

        .section-desc {
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        /* ── Features Grid ──────────────────── */
        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }

        .feature-item {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1.15rem;
            background: #fff;
            transition: border-color 0.15s;
        }

        .feature-item:hover {
            border-color: #99f6e4;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-bottom: 0.65rem;
        }

        .feature-icon.teal { background: #ccfbf1; color: #0f766e; }
        .feature-icon.amber { background: #fef3c7; color: #b45309; }
        .feature-icon.sky { background: #e0f2fe; color: #0369a1; }
        .feature-icon.violet { background: #ede9fe; color: #6d28d9; }
        .feature-icon.rose { background: #ffe4e6; color: #be123c; }
        .feature-icon.emerald { background: #dcfce7; color: #047857; }

        .feature-item h3 {
            font-size: 0.88rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .feature-item p {
            margin: 0;
            color: var(--muted);
            font-size: 0.8rem;
            line-height: 1.55;
        }

        /* ── Steps ──────────────────────────── */
        .steps-list {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .step-row {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .step-num {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-soft);
            color: #0f766e;
            font-weight: 800;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 0.15rem;
        }

        .step-body {
            flex: 1;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.85rem 1rem;
            background: #fff;
        }

        .step-body h4 {
            font-size: 0.86rem;
            font-weight: 700;
            margin: 0 0 0.25rem;
        }

        .step-body p {
            font-size: 0.78rem;
            color: var(--muted);
            margin: 0;
            line-height: 1.6;
        }

        /* ── Roles Section ──────────────────── */
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.85rem;
        }

        .role-card {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1.15rem;
            background: #fff;
        }

        .role-card h3 {
            font-size: 0.88rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .role-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .role-card li {
            font-size: 0.78rem;
            color: var(--muted);
            padding: 0.2rem 0;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .role-card li i {
            color: #0D9488;
            font-size: 0.7rem;
        }

        /* ── Footer ─────────────────────────── */
        .site-footer {
            padding: 1.5rem 0 2.5rem;
            color: #78716c;
            font-size: 0.76rem;
            text-align: center;
        }

        /* ── Responsive ─────────────────────── */
        @media (max-width: 991.98px) {
            .hero-layout { grid-template-columns: 1fr; }
            .features-grid { grid-template-columns: 1fr; }
            .roles-grid { grid-template-columns: 1fr; }
            .hero-card { padding: 1.5rem; }
            .hero { padding: 2rem 0 1.5rem; }
            .content-section { padding: 1.5rem; }
        }

        @media (max-width: 575.98px) {
            .hero-heading { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    {{-- ── Navigation ────────────────────────────────────────────── --}}
    <header class="site-nav">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                <a href="{{ route('home') }}" class="brand">
                    <span class="brand-mark"><i class="bi bi-building-check"></i></span>
                    <span>
                        <span class="brand-name d-block">Municipal E-Services</span>
                        <span class="brand-tagline d-block">Lebanese Municipalities</span>
                    </span>
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius:.45rem; font-weight:600; font-size:.82rem;">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3" style="border-radius:.45rem; font-weight:600; font-size:.82rem;">Create account</a>
                </div>
            </div>
        </div>
    </header>

    <main class="container">

        {{-- ── Hero ──────────────────────────────────────────────── --}}
        <section class="hero">
            <div class="hero-layout">
                <article class="hero-card">
                    <span class="hero-label"><i class="bi bi-shield-check"></i> Government Digital Services</span>
                    <h1 class="hero-heading">Municipal services<br>accessible from anywhere.</h1>
                    <p class="hero-desc">Submit requests, upload documents, pay fees, and track progress through a single platform built for Lebanese municipalities. Offices process requests transparently while citizens stay informed at every step.</p>
                    <div class="hero-actions">
                        <a href="{{ route('register') }}" class="btn btn-primary">Create your account</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">Sign in</a>
                    </div>
                </article>

                <aside class="metrics-panel">
                    <span class="metrics-panel-title">Platform at a glance</span>
                    <div class="metric-block">
                        <div class="metric-block-value">24/7</div>
                        <div class="metric-block-label">Submit requests and check status at any time</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-block-value">Real-time</div>
                        <div class="metric-block-label">Get notified when your request status changes</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-block-value">Secure</div>
                        <div class="metric-block-label">2FA authentication and tracked payment records</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-block-value">Multi-office</div>
                        <div class="metric-block-label">Access services from any registered municipality</div>
                    </div>
                </aside>
            </div>
        </section>

        {{-- ── Platform Capabilities ─────────────────────────────── --}}
        <section class="content-section">
            <h2 class="section-heading">What you can do on the platform</h2>
            <p class="section-desc">A complete digital workflow for residents and municipal teams, replacing in-person visits with online processes.</p>

            <div class="features-grid">
                <div class="feature-item">
                    <span class="feature-icon teal"><i class="bi bi-credit-card-2-front"></i></span>
                    <h3>Pay service fees online</h3>
                    <p>Complete payments for municipal service requests without traveling to the office. Supports standard and cryptocurrency options.</p>
                </div>
                <div class="feature-item">
                    <span class="feature-icon sky"><i class="bi bi-diagram-3"></i></span>
                    <h3>Track request lifecycle</h3>
                    <p>Every request has a reference number, status timeline, and transparent updates from the processing office.</p>
                </div>
                <div class="feature-item">
                    <span class="feature-icon amber"><i class="bi bi-calendar-check"></i></span>
                    <h3>Book appointments</h3>
                    <p>Schedule and manage appointments tied to your service requests, with reminders and office confirmation.</p>
                </div>
                <div class="feature-item">
                    <span class="feature-icon violet"><i class="bi bi-bell"></i></span>
                    <h3>Stay informed with notifications</h3>
                    <p>Automatic alerts keep citizens and office users aligned on status changes, required documents, and deadlines.</p>
                </div>
                <div class="feature-item">
                    <span class="feature-icon emerald"><i class="bi bi-file-earmark-arrow-up"></i></span>
                    <h3>Upload documents digitally</h3>
                    <p>Attach required supporting documents when submitting requests. Offices can request additional files if needed.</p>
                </div>
                <div class="feature-item">
                    <span class="feature-icon rose"><i class="bi bi-chat-left-text"></i></span>
                    <h3>Direct messaging with offices</h3>
                    <p>Communicate with the processing office about your request through secure in-platform messaging.</p>
                </div>
            </div>
        </section>

        {{-- ── How It Works ──────────────────────────────────────── --}}
        <section class="content-section">
            <h2 class="section-heading">How the process works</h2>
            <p class="section-desc">From registration to service completion, every step is predictable and trackable.</p>

            <div class="steps-list">
                <div class="step-row">
                    <span class="step-num">1</span>
                    <div class="step-body">
                        <h4>Register and verify your profile</h4>
                        <p>Create an account with email or social login. Set up your profile and enable two-factor authentication for security.</p>
                    </div>
                </div>
                <div class="step-row">
                    <span class="step-num">2</span>
                    <div class="step-body">
                        <h4>Select a municipality office and service</h4>
                        <p>Browse available offices, review their services, and submit a request with any required documents.</p>
                    </div>
                </div>
                <div class="step-row">
                    <span class="step-num">3</span>
                    <div class="step-body">
                        <h4>Track progress and complete payment</h4>
                        <p>Monitor your request status in real time. When the office sets a fee, pay online to keep the process moving.</p>
                    </div>
                </div>
                <div class="step-row">
                    <span class="step-num">4</span>
                    <div class="step-body">
                        <h4>Receive outcome or attend appointment</h4>
                        <p>Get your result digitally, download receipts and certificates, or attend a scheduled appointment at the office.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Built For Section ─────────────────────────────────── --}}
        <section class="content-section">
            <h2 class="section-heading">Built for every role in the process</h2>
            <p class="section-desc">Each user sees only what matters to them, with tools designed for their specific tasks.</p>

            <div class="roles-grid">
                <div class="role-card">
                    <h3><i class="bi bi-person" style="color:#0D9488;"></i> Citizens</h3>
                    <ul>
                        <li><i class="bi bi-check2"></i> Submit and track service requests</li>
                        <li><i class="bi bi-check2"></i> Pay fees and download receipts</li>
                        <li><i class="bi bi-check2"></i> Book appointments with offices</li>
                        <li><i class="bi bi-check2"></i> Get real-time status notifications</li>
                    </ul>
                </div>
                <div class="role-card">
                    <h3><i class="bi bi-buildings" style="color:#0D9488;"></i> Office Users</h3>
                    <ul>
                        <li><i class="bi bi-check2"></i> Review and process requests</li>
                        <li><i class="bi bi-check2"></i> Manage services and appointments</li>
                        <li><i class="bi bi-check2"></i> Communicate with citizens</li>
                        <li><i class="bi bi-check2"></i> View feedback and ratings</li>
                    </ul>
                </div>
                <div class="role-card">
                    <h3><i class="bi bi-shield-lock" style="color:#0D9488;"></i> Administrators</h3>
                    <ul>
                        <li><i class="bi bi-check2"></i> Manage municipalities and offices</li>
                        <li><i class="bi bi-check2"></i> Oversee all users and requests</li>
                        <li><i class="bi bi-check2"></i> View reports and analytics</li>
                        <li><i class="bi bi-check2"></i> Platform-wide configuration</li>
                    </ul>
                </div>
            </div>
        </section>

    </main>

    <footer class="site-footer">
        <div class="container">
            <div>Municipal E-Services Platform &mdash; Built for Lebanese municipalities</div>
            <div class="mt-1">&copy; {{ now()->year }} All rights reserved</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
