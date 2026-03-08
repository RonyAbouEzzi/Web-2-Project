<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lebanon Government E-Services Platform — Access all municipal and government services online.">
    <title>E-Services — Lebanon Government Digital Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wdth,wght@75..100,400..700&family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    :root {
        --navy: #060D1F; --navy-2: #0B1630; --navy-3: #1A3360;
        --primary: #1E4080; --primary-lt: #EFF6FF;
        --gold: #D4A017; --gold-lt: #FDF7DC;
        --ink-900:#111318; --ink-700:#2D3748; --ink-500:#718096; --ink-300:#CBD5E0; --ink-100:#F7FAFC;
        --white: #fff;
        --font: 'Instrument Sans', system-ui, sans-serif;
        --font-disp: 'Fraunces', Georgia, serif;
        --r: 14px; --r-sm: 9px;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body { font-family: var(--font); background: var(--white); -webkit-font-smoothing: antialiased; color: var(--ink-700); }

    /* ── Navbar ── */
    .nav-bar {
        position: sticky; top: 0; z-index: 100;
        background: rgba(255,255,255,.92); backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(0,0,0,.06);
        padding: .7rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    }
    .nav-logo { display: flex; align-items: center; gap: .6rem; text-decoration: none; }
    .nav-logo .logo-mark {
        width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--primary), #4B7CD0);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: .95rem;
        box-shadow: 0 2px 8px rgba(30,64,128,.35);
    }
    .nav-logo .logo-text { font-family: var(--font-disp); font-weight: 600; font-size: .92rem; color: var(--ink-900); letter-spacing: -.01em; font-style: italic; }
    .nav-logo .logo-sub  { font-size: .66rem; color: var(--ink-500); font-family: var(--font); display: block; margin-top: -1px; }
    .nav-actions { display: flex; align-items: center; gap: .5rem; }
    .btn-nav {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .46rem 1.1rem; border-radius: var(--r-sm);
        font-size: .82rem; font-weight: 600; text-decoration: none;
        font-family: var(--font); transition: all .15s; border: 1.5px solid transparent; cursor: pointer;
    }
    .btn-outline { border-color: var(--ink-300); color: var(--ink-700); background: transparent; }
    .btn-outline:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-lt); }
    .btn-solid { background: var(--primary); color: #fff; border-color: var(--primary); }
    .btn-solid:hover { background: #162F60; border-color: #162F60; color: #fff; box-shadow: 0 4px 14px rgba(30,64,128,.35); transform: translateY(-1px); }

    /* ── Hero ── */
    .hero {
        background: var(--navy); position: relative; overflow: hidden;
        padding: 6rem 1.5rem 5rem;
    }
    /* Geometric grid overlay */
    .hero::before {
        content: '';
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    /* Glow orbs */
    .hero-orb-1 {
        position: absolute; top: -100px; right: -80px;
        width: 500px; height: 500px; border-radius: 50%;
        background: radial-gradient(circle, rgba(30,64,128,.5) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-orb-2 {
        position: absolute; bottom: -120px; left: -60px;
        width: 400px; height: 400px; border-radius: 50%;
        background: radial-gradient(circle, rgba(212,160,23,.18) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-orb-3 {
        position: absolute; top: 40%; left: 35%;
        width: 300px; height: 300px; border-radius: 50%;
        background: radial-gradient(circle, rgba(75,124,208,.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-inner {
        position: relative; z-index: 2;
        max-width: 740px; margin: 0 auto; text-align: center;
    }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: .45rem;
        background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.14);
        color: rgba(255,255,255,.7); border-radius: 99px;
        padding: .3rem 1rem; font-size: .74rem; font-weight: 600;
        letter-spacing: .04em; text-transform: uppercase; margin-bottom: 1.75rem;
    }
    .hero-eyebrow i { color: var(--gold); }
    .hero h1 {
        font-family: var(--font-disp); font-style: italic;
        color: #fff; font-size: clamp(2rem,5.5vw,3.4rem);
        font-weight: 700; line-height: 1.12; letter-spacing: -.03em;
        margin-bottom: 1.35rem;
    }
    .hero h1 .highlight {
        color: var(--gold); font-style: italic;
    }
    .hero-sub {
        color: rgba(255,255,255,.58); font-size: clamp(.88rem,1.8vw,1.05rem);
        line-height: 1.75; max-width: 560px; margin: 0 auto 2.25rem;
    }
    .hero-cta {
        display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center;
    }
    .btn-hero-primary {
        display: inline-flex; align-items: center; gap: .5rem;
        background: var(--white); color: var(--primary);
        padding: .75rem 2rem; border-radius: var(--r-sm); font-weight: 700;
        font-size: .9rem; text-decoration: none; font-family: var(--font);
        transition: all .18s; border: none; cursor: pointer;
        box-shadow: 0 4px 20px rgba(0,0,0,.25);
    }
    .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,.35); color: var(--primary); }
    .btn-hero-ghost {
        display: inline-flex; align-items: center; gap: .5rem;
        background: rgba(255,255,255,.08); color: rgba(255,255,255,.88);
        padding: .75rem 2rem; border-radius: var(--r-sm); font-weight: 600;
        font-size: .9rem; text-decoration: none; font-family: var(--font);
        transition: all .18s; border: 1.5px solid rgba(255,255,255,.2);
    }
    .btn-hero-ghost:hover { background: rgba(255,255,255,.15); color: #fff; border-color: rgba(255,255,255,.4); }

    /* Hero stats strip */
    .hero-stats {
        display: flex; flex-wrap: wrap; justify-content: center;
        gap: .5rem; margin-top: 3.5rem;
    }
    .hstat {
        background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
        border-radius: var(--r-sm); padding: .75rem 1.25rem; text-align: center;
        min-width: 130px;
    }
    .hstat-num { font-family: var(--font-disp); font-style: italic; font-size: 1.75rem; font-weight: 700; color: var(--gold); line-height: 1; }
    .hstat-lbl { font-size: .72rem; color: rgba(255,255,255,.45); margin-top: .2rem; font-weight: 500; letter-spacing: .02em; }

    /* ── Trust bar ── */
    .trust-bar {
        background: var(--white); border-bottom: 1px solid #F0F4FA;
        padding: 1.1rem 1.5rem;
        display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
        gap: .6rem;
    }
    .trust-chip {
        display: inline-flex; align-items: center; gap: .45rem;
        background: var(--ink-100); border-radius: 99px;
        padding: .35rem .9rem; font-size: .76rem; font-weight: 600; color: var(--ink-700);
    }
    .trust-chip i { color: var(--primary); }

    /* ── Section ── */
    .section { padding: 5rem 1.5rem; }
    .section-inner { max-width: 1100px; margin: 0 auto; }
    .section-label { font-size: .72rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--primary); margin-bottom: .5rem; }
    .section-title {
        font-family: var(--font-disp); font-style: italic;
        font-size: clamp(1.5rem,3.5vw,2.2rem); font-weight: 700;
        letter-spacing: -.03em; color: var(--ink-900); margin-bottom: .75rem;
    }
    .section-sub { color: var(--ink-500); font-size: .9rem; line-height: 1.75; max-width: 560px; }

    /* ── Feature cards ── */
    .feat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px,1fr)); gap: 1.1rem; margin-top: 3rem; }
    .feat-card {
        background: var(--white); border: 1px solid #E2E8F0;
        border-radius: var(--r); padding: 1.6rem;
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s;
        position: relative; overflow: hidden;
    }
    .feat-card::after {
        content: '';
        position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--primary), #4B7CD0);
        transform: scaleX(0); transform-origin: left;
        transition: transform .28s ease;
    }
    .feat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.1); border-color: #CBD5E0; }
    .feat-card:hover::after { transform: scaleX(1); }
    .feat-icon {
        width: 50px; height: 50px; border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; margin-bottom: 1.1rem;
    }
    .feat-card h4 { font-size: .92rem; font-weight: 700; color: var(--ink-900); margin-bottom: .4rem; letter-spacing: -.01em; }
    .feat-card p  { font-size: .8rem; color: var(--ink-500); line-height: 1.65; margin: 0; }

    /* ── How it works ── */
    .how-section { background: #F0F4FA; padding: 5rem 1.5rem; }
    .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 1.5rem; margin-top: 3rem; }
    .step { text-align: center; }
    .step-num {
        width: 52px; height: 52px; border-radius: 50%;
        background: var(--navy); color: var(--gold);
        display: flex; align-items: center; justify-content: center;
        font-family: var(--font-disp); font-style: italic; font-size: 1.25rem; font-weight: 700;
        margin: 0 auto 1rem;
    }
    .step h4 { font-size: .9rem; font-weight: 700; color: var(--ink-900); margin-bottom: .35rem; }
    .step p  { font-size: .8rem; color: var(--ink-500); line-height: 1.65; margin: 0; }

    /* ── Roles ── */
    .roles-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px,1fr)); gap: 1.25rem; margin-top: 3rem; }
    .role-card {
        border-radius: var(--r); overflow: hidden;
        border: 1px solid #E2E8F0; background: var(--white);
        display: flex; flex-direction: column;
        transition: transform .22s ease, box-shadow .22s ease;
    }
    .role-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(0,0,0,.1); }
    .role-card-top { padding: 2rem 1.75rem 1.5rem; }
    .role-icon {
        width: 60px; height: 60px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; margin-bottom: 1.1rem;
    }
    .role-card h3 { font-size: 1.05rem; font-weight: 700; color: var(--ink-900); margin-bottom: .45rem; letter-spacing: -.01em; }
    .role-card p  { font-size: .82rem; color: var(--ink-500); line-height: 1.7; margin: 0; }
    .role-card-foot { border-top: 1px solid #F0F4FA; padding: 1rem 1.75rem; margin-top: auto; }
    .role-card-foot a {
        display: inline-flex; align-items: center; gap: .45rem;
        font-size: .82rem; font-weight: 700; text-decoration: none;
        transition: gap .18s;
    }
    .role-card-foot a:hover { gap: .65rem; }

    /* ── Footer ── */
    footer {
        background: var(--navy); color: rgba(255,255,255,.4);
        padding: 2rem 1.5rem; text-align: center;
    }
    footer .f-logo { display: flex; align-items: center; gap: .6rem; justify-content: center; margin-bottom: 1rem; }
    footer .f-logo .fm {
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(255,255,255,.1); display: flex; align-items: center;
        justify-content: center; font-size: .85rem; color: #fff;
    }
    footer .f-logo span { color: rgba(255,255,255,.7); font-size: .85rem; font-weight: 600; }
    footer p { font-size: .76rem; line-height: 1.6; }
    footer .f-links { display: flex; flex-wrap: wrap; gap: .5rem 1.5rem; justify-content: center; margin-bottom: 1rem; }
    footer .f-links a { color: rgba(255,255,255,.35); text-decoration: none; font-size: .76rem; transition: color .14s; }
    footer .f-links a:hover { color: rgba(255,255,255,.65); }

    @media(max-width:576px) {
        .hero { padding: 4rem 1.25rem 3.5rem; }
        .hstat { min-width: 110px; }
        .hero-stats { gap: .4rem; }
    }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="nav-bar">
    <a class="nav-logo" href="{{ route('home') }}">
        <div class="logo-mark"><i class="bi bi-building-check"></i></div>
        <div>
            <div class="logo-text">E-Services</div>
            <span class="logo-sub">Lebanon Gov Portal</span>
        </div>
    </a>
    <div class="nav-actions">
        <a href="{{ route('login') }}"    class="btn-nav btn-outline"><i class="bi bi-box-arrow-in-right"></i> Sign In</a>
        <a href="{{ route('register') }}" class="btn-nav btn-solid"><i class="bi bi-person-plus"></i><span class="d-none d-sm-inline"> Get Started</span></a>
    </div>
</nav>

{{-- Hero --}}
<section class="hero">
    <div class="hero-orb-1"></div>
    <div class="hero-orb-2"></div>
    <div class="hero-orb-3"></div>
    <div class="hero-inner">
        <div class="hero-eyebrow">
            <i class="bi bi-shield-check"></i> Official Government Digital Platform
        </div>
        <h1>
            Government Services,<br>
            <span class="highlight">Reimagined</span> for You
        </h1>
        <p class="hero-sub">
            Submit requests, pay fees, track progress, and download official documents —
            all from your phone, without a single queue.
        </p>
        <div class="hero-cta">
            <a href="{{ route('register') }}" class="btn-hero-primary">
                <i class="bi bi-person-plus"></i> Create Free Account
            </a>
            <a href="{{ route('login') }}" class="btn-hero-ghost">
                <i class="bi bi-box-arrow-in-right"></i> Sign In
            </a>
        </div>
        <div class="hero-stats">
            <div class="hstat"><div class="hstat-num">3+</div><div class="hstat-lbl">Municipalities</div></div>
            <div class="hstat"><div class="hstat-num">20+</div><div class="hstat-lbl">Services Online</div></div>
            <div class="hstat"><div class="hstat-num">100%</div><div class="hstat-lbl">Paperless</div></div>
            <div class="hstat"><div class="hstat-num">24/7</div><div class="hstat-lbl">Live Tracking</div></div>
        </div>
    </div>
</section>

{{-- Trust bar --}}
<div class="trust-bar">
    <div class="trust-chip"><i class="bi bi-shield-lock-fill"></i> SSL Encrypted</div>
    <div class="trust-chip"><i class="bi bi-phone"></i> Works on All Devices</div>
    <div class="trust-chip"><i class="bi bi-credit-card-2-front"></i> Secure Online Payments</div>
    <div class="trust-chip"><i class="bi bi-qr-code"></i> QR Code Tracking</div>
    <div class="trust-chip"><i class="bi bi-bell"></i> Instant Notifications</div>
</div>

{{-- Features --}}
<section class="section">
    <div class="section-inner">
        <div class="row align-items-center g-5">
            <div class="col-lg-4">
                <div class="section-label">Platform Features</div>
                <h2 class="section-title">Everything You Need, in One Place</h2>
                <p class="section-sub">From birth certificates to building permits — handle everything digitally without leaving your home.</p>
            </div>
            <div class="col-lg-8">
                <div class="feat-grid">
                    <div class="feat-card">
                        <div class="feat-icon" style="background:#EFF6FF;color:#1E4080"><i class="bi bi-file-earmark-text"></i></div>
                        <h4>Online Service Requests</h4>
                        <p>Submit applications and upload required documents from any device, anytime.</p>
                    </div>
                    <div class="feat-card">
                        <div class="feat-icon" style="background:#ECFDF5;color:#0D7A4E"><i class="bi bi-qr-code-scan"></i></div>
                        <h4>QR Code Tracking</h4>
                        <p>Every request gets a unique QR code. Scan to instantly check your status.</p>
                    </div>
                    <div class="feat-card">
                        <div class="feat-icon" style="background:#FDF7DC;color:#9A6F00"><i class="bi bi-credit-card-2-front"></i></div>
                        <h4>Secure Online Payments</h4>
                        <p>Pay service fees with credit/debit cards or cryptocurrency — fast and safe.</p>
                    </div>
                    <div class="feat-card">
                        <div class="feat-icon" style="background:#ECFEFF;color:#0E7490"><i class="bi bi-bell-fill"></i></div>
                        <h4>Real-Time Notifications</h4>
                        <p>Receive instant email and in-app alerts whenever your request status changes.</p>
                    </div>
                    <div class="feat-card">
                        <div class="feat-icon" style="background:#F5F3FF;color:#6D28D9"><i class="bi bi-calendar-check"></i></div>
                        <h4>Appointment Booking</h4>
                        <p>Schedule in-person visits at your preferred time — no waiting in line.</p>
                    </div>
                    <div class="feat-card">
                        <div class="feat-icon" style="background:#FFF1F2;color:#BE123C"><i class="bi bi-chat-dots-fill"></i></div>
                        <h4>Direct Messaging</h4>
                        <p>Communicate securely with office staff directly on each service request.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- How it works --}}
<div class="how-section">
    <div class="section-inner">
        <div class="text-center mb-2">
            <div class="section-label">How It Works</div>
            <h2 class="section-title">Four Simple Steps</h2>
            <p class="section-sub" style="margin:0 auto">Getting your government service has never been simpler.</p>
        </div>
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <h4>Create Account</h4>
                <p>Register with your email or social login. Upload your National ID for verification.</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <h4>Find Your Service</h4>
                <p>Browse offices by municipality and select the service you need.</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <h4>Submit & Pay</h4>
                <p>Upload documents, submit your request, and pay the fee online — all in minutes.</p>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <h4>Track & Download</h4>
                <p>Follow your request in real-time and download your official certificate when ready.</p>
            </div>
        </div>
    </div>
</div>

{{-- Roles --}}
<section class="section" style="background:var(--white)">
    <div class="section-inner">
        <div class="text-center mb-2">
            <div class="section-label">Built for Everyone</div>
            <h2 class="section-title">Three Tailored Portals</h2>
            <p class="section-sub" style="margin:0 auto">One platform, three purpose-built experiences.</p>
        </div>
        <div class="roles-grid">
            <div class="role-card">
                <div class="role-card-top">
                    <div class="role-icon" style="background:#EFF6FF;color:#1E4080"><i class="bi bi-person-fill"></i></div>
                    <h3>Citizens</h3>
                    <p>Browse and submit service requests, pay online, track progress in real-time, chat with office staff, and download official documents — all from your phone.</p>
                </div>
                <div class="role-card-foot">
                    <a href="{{ route('register') }}" style="color:var(--primary)">
                        <i class="bi bi-person-plus-fill"></i> Create Account <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="role-card">
                <div class="role-card-top">
                    <div class="role-icon" style="background:#ECFDF5;color:#0D7A4E"><i class="bi bi-building"></i></div>
                    <h3>Government Offices</h3>
                    <p>Manage incoming requests, update statuses, communicate with citizens, issue certificates and approval letters, and monitor office performance analytics.</p>
                </div>
                <div class="role-card-foot">
                    <a href="{{ route('login') }}" style="color:#0D7A4E">
                        <i class="bi bi-box-arrow-in-right"></i> Office Portal <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="role-card">
                <div class="role-card-top">
                    <div class="role-icon" style="background:#FDF7DC;color:#9A6F00"><i class="bi bi-shield-lock-fill"></i></div>
                    <h3>Administrators</h3>
                    <p>Full platform control — manage municipalities, register government offices, create staff accounts, and view comprehensive system-wide analytics and reports.</p>
                </div>
                <div class="role-card-foot">
                    <a href="{{ route('login') }}" style="color:#9A6F00">
                        <i class="bi bi-gear-fill"></i> Admin Portal <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Banner --}}
<section style="background:var(--navy);padding:4.5rem 1.5rem;text-align:center;position:relative;overflow:hidden">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:600px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(30,64,128,.5),transparent);pointer-events:none"></div>
    <div style="position:relative;z-index:1;max-width:580px;margin:0 auto">
        <div style="font-size:.72rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:.75rem">Get Started Today</div>
        <h2 style="font-family:var(--font-disp);font-style:italic;color:#fff;font-size:clamp(1.5rem,3.5vw,2.2rem);font-weight:700;letter-spacing:-.03em;margin-bottom:1rem">Join Thousands of Citizens<br>Already Using E-Services</h2>
        <p style="color:rgba(255,255,255,.5);font-size:.88rem;line-height:1.7;margin-bottom:2rem">Free to register. No paperwork. No queues. Your time matters.</p>
        <a href="{{ route('register') }}" class="btn-hero-primary" style="display:inline-flex">
            <i class="bi bi-person-plus"></i> Create Your Free Account
        </a>
    </div>
</section>

{{-- Footer --}}
<footer>
    <div class="f-logo">
        <div class="fm"><i class="bi bi-building-check"></i></div>
        <span>E-Services Government Portal</span>
    </div>
    <div class="f-links">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('login') }}">Sign In</a>
        <a href="{{ route('register') }}">Register</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Use</a>
    </div>
    <p>&copy; {{ date('Y') }} E-Services Lebanon. All rights reserved.<br>
    <span style="font-size:.7rem">PROG322-EC20 · Lebanese-American University · Built with Laravel 11</span></p>
</footer>

</body>
</html>
