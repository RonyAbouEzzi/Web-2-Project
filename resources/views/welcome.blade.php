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
            --soft: #ccfbf1;
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
            background:
                radial-gradient(circle at 12% 18%, rgba(13, 148, 136, 0.08), transparent 34%),
                radial-gradient(circle at 82% 30%, rgba(15, 118, 110, 0.07), transparent 30%),
                linear-gradient(180deg, #fafaf9 0%, var(--bg) 100%);
            min-height: 100vh;
        }

        .nav-shell {
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 20;
            backdrop-filter: blur(8px);
            background: rgba(250, 250, 249, 0.88);
            border-bottom: 1px solid var(--border);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            text-decoration: none;
            color: inherit;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--soft);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-dark);
            font-size: 1.1rem;
        }

        .brand-title {
            font-weight: 800;
            font-size: .96rem;
            line-height: 1.2;
        }

        .brand-sub {
            color: var(--muted);
            font-size: .74rem;
            line-height: 1.2;
        }

        .hero-wrap {
            padding: 3.4rem 0 2.2rem;
        }

        .hero-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1.3fr .9fr;
            align-items: stretch;
        }

        .hero-content {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
        }

        .hero-title {
            font-size: clamp(1.8rem, 4vw, 3rem);
            line-height: 1.1;
            letter-spacing: -.02em;
            margin-bottom: .9rem;
            font-weight: 800;
        }

        .hero-text {
            color: var(--muted);
            font-size: .98rem;
            line-height: 1.75;
            max-width: 60ch;
            margin-bottom: 1.5rem;
        }

        .hero-cta {
            display: flex;
            flex-wrap: wrap;
            gap: .7rem;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            font-weight: 700;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .hero-panel {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .metric {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: .9rem;
            margin-bottom: .65rem;
            background: #fafaf9;
        }

        .metric:last-child {
            margin-bottom: 0;
        }

        .metric-value {
            font-weight: 800;
            font-size: 1.35rem;
            color: #0f766e;
            line-height: 1;
            margin-bottom: .25rem;
        }

        .metric-label {
            color: var(--muted);
            font-size: .77rem;
        }

        .section-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.4rem;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -.01em;
            margin-bottom: .7rem;
        }

        .section-sub {
            color: var(--muted);
            font-size: .92rem;
            line-height: 1.7;
            margin-bottom: 1.3rem;
        }

        .feature-row {
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: .8rem;
            margin-bottom: .8rem;
        }

        .feature-row:last-child {
            margin-bottom: 0;
        }

        .feature-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--soft);
            color: #0f766e;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-bottom: .6rem;
        }

        .feature-box {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem;
            background: #fff;
        }

        .feature-box h3 {
            font-size: .95rem;
            font-weight: 700;
            margin-bottom: .35rem;
        }

        .feature-box p {
            margin: 0;
            color: var(--muted);
            font-size: .82rem;
            line-height: 1.55;
        }

        .steps {
            display: grid;
            grid-template-columns: .55fr 1.45fr;
            gap: .95rem;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--soft);
            color: #0f766e;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: .2rem;
        }

        .step-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            padding: 1rem;
            margin-bottom: .65rem;
        }

        .step-card:last-child {
            margin-bottom: 0;
        }

        .step-card h4 {
            font-size: .9rem;
            margin: 0 0 .35rem;
            font-weight: 700;
        }

        .step-card p {
            font-size: .8rem;
            color: var(--muted);
            margin: 0;
            line-height: 1.6;
        }

        footer {
            padding: 1.5rem 0 2.5rem;
            color: #78716c;
            font-size: .78rem;
        }

        @media (max-width: 991.98px) {
            .hero-grid,
            .feature-row,
            .steps {
                grid-template-columns: 1fr;
            }

            .hero-content,
            .hero-panel {
                padding: 1.3rem;
            }

            .hero-wrap {
                padding-top: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="nav-shell">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                <a href="{{ route('home') }}" class="brand">
                    <span class="brand-mark"><i class="bi bi-building-check"></i></span>
                    <span>
                        <span class="brand-title d-block">Municipal E-Services</span>
                        <span class="brand-sub d-block">Lebanese Municipalities Digital Platform</span>
                    </span>
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm px-3">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3">Register</a>
                </div>
            </div>
        </div>
    </div>

    <main class="container hero-wrap">
        <section class="hero-grid">
            <article class="hero-content">
                <h1 class="hero-title">Government services built for citizens, offices, and administrators.</h1>
                <p class="hero-text">Submit requests, upload supporting documents, pay fees online, and track each service lifecycle from submission to completion. The platform centralizes municipal services while giving offices the tools to process requests efficiently and transparently.</p>
                <div class="hero-cta">
                    <a href="{{ route('register') }}" class="btn btn-primary px-4 py-2">Start with an account</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary px-4 py-2">Access existing account</a>
                </div>
            </article>

            <aside class="hero-panel">
                <div>
                    <div class="metric">
                        <div class="metric-value">24/7</div>
                        <div class="metric-label">Online request submission and status checks</div>
                    </div>
                    <div class="metric">
                        <div class="metric-value">Real-time</div>
                        <div class="metric-label">Notifications for status updates and appointments</div>
                    </div>
                    <div class="metric">
                        <div class="metric-value">Secure</div>
                        <div class="metric-label">Tracked payments and authenticated user access</div>
                    </div>
                </div>
                <div class="small text-muted mt-2">Designed for municipal service continuity and transparency.</div>
            </aside>
        </section>

        <section class="section-card mt-3">
            <h2 class="section-title">Platform capabilities</h2>
            <p class="section-sub">A complete digital workflow for residents and municipal teams.</p>

            <div class="feature-row">
                <div class="feature-box">
                    <span class="feature-icon"><i class="bi bi-credit-card-2-front"></i></span>
                    <h3>Online payments</h3>
                    <p>Citizens can complete payment flows for service requests without visiting municipal offices.</p>
                </div>
                <div class="feature-box">
                    <span class="feature-icon"><i class="bi bi-diagram-3"></i></span>
                    <h3>Request tracking</h3>
                    <p>Every request includes a reference, status timeline, and office-side updates visible to citizens.</p>
                </div>
            </div>

            <div class="feature-row">
                <div class="feature-box">
                    <span class="feature-icon"><i class="bi bi-calendar-check"></i></span>
                    <h3>Appointment scheduling</h3>
                    <p>Book and manage municipal appointments tied to the relevant service request context.</p>
                </div>
                <div class="feature-box">
                    <span class="feature-icon"><i class="bi bi-bell"></i></span>
                    <h3>Notifications and updates</h3>
                    <p>Automatic notifications keep citizens and office users aligned on status changes and required actions.</p>
                </div>
            </div>
        </section>

        <section class="section-card">
            <h2 class="section-title">How the process works</h2>
            <p class="section-sub">Simple, predictable steps from request submission to completion.</p>

            <div class="steps">
                <div>
                    <div class="step-number">1</div>
                    <div class="step-number mt-3">2</div>
                    <div class="step-number mt-3">3</div>
                    <div class="step-number mt-3">4</div>
                </div>
                <div>
                    <div class="step-card">
                        <h4>Create account and verify profile</h4>
                        <p>Sign up once, then access service requests, appointments, and payment history in one place.</p>
                    </div>
                    <div class="step-card">
                        <h4>Choose office and submit request</h4>
                        <p>Select the relevant municipality office, provide documents, and submit your request digitally.</p>
                    </div>
                    <div class="step-card">
                        <h4>Follow status and complete payment</h4>
                        <p>Track progress from pending to completion and pay the fee when requested by the office.</p>
                    </div>
                    <div class="step-card">
                        <h4>Attend appointment or receive outcome</h4>
                        <p>Book an appointment when needed and receive final status updates and service completion feedback.</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="text-center">
            <div>Municipal E-Services Platform - Built for Lebanese municipalities</div>
            <div class="mt-1">{{ now()->year }} All rights reserved</div>
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
