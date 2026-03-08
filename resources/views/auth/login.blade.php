<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Sign In — E-Services Lebanon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wdth,wght@75..100,400..700&family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    :root {
        --navy: #060D1F; --navy-2: #0B1630; --navy-3: #1A3360;
        --primary: #1E4080; --primary-lt: #EFF6FF; --primary-dk: #162F60;
        --gold: #D4A017; --gold-lt: #FDF7DC;
        --red: #BE123C; --red-lt: #FFF1F2;
        --ink-900:#111318; --ink-700:#2D3748; --ink-500:#718096;
        --ink-300:#CBD5E0; --ink-200:#E2E8F0; --ink-100:#F7FAFC;
        --white:#fff;
        --font: 'Instrument Sans', system-ui, sans-serif;
        --font-disp: 'Fraunces', Georgia, serif;
        --r: 12px; --r-sm: 8px;
    }
    *, *::before, *::after { box-sizing: border-box; margin:0; padding:0; }
    html { font-size: 14px; }
    body {
        font-family: var(--font); min-height: 100vh; margin: 0;
        background: #F0F4FA; display: flex; align-items: stretch;
        -webkit-font-smoothing: antialiased;
    }

    /* ── Left panel ── */
    .auth-left {
        flex: 1; background: var(--navy);
        display: none; flex-direction: column;
        justify-content: space-between; padding: 2.5rem;
        position: relative; overflow: hidden;
    }
    @media(min-width:900px) { .auth-left { display: flex; } }
    .al-orb-1 {
        position: absolute; top: -100px; right: -100px;
        width: 400px; height: 400px; border-radius: 50%;
        background: radial-gradient(circle, rgba(30,64,128,.55), transparent 70%);
        pointer-events: none;
    }
    .al-orb-2 {
        position: absolute; bottom: -120px; left: -60px;
        width: 350px; height: 350px; border-radius: 50%;
        background: radial-gradient(circle, rgba(212,160,23,.18), transparent 70%);
        pointer-events: none;
    }
    .al-grid {
        position: absolute; inset: 0;
        background-image: linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
        background-size: 36px 36px;
    }
    .al-top { position: relative; z-index: 2; }
    .al-logo { display: flex; align-items: center; gap: .65rem; }
    .al-logo-mark {
        width: 40px; height: 40px; border-radius: 11px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--primary), #4B7CD0);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.05rem; color: #fff;
        box-shadow: 0 2px 14px rgba(30,64,128,.5), 0 0 0 1px rgba(255,255,255,.08);
    }
    .al-logo-text .t1 { color: #fff; font-family: var(--font-disp); font-style: italic; font-size: .95rem; font-weight: 600; line-height: 1.2; }
    .al-logo-text .t2 { color: rgba(255,255,255,.3); font-size: .65rem; font-weight: 500; letter-spacing: .06em; text-transform: uppercase; }
    .al-mid { position: relative; z-index: 2; }
    .al-mid h2 {
        font-family: var(--font-disp); font-style: italic;
        color: #fff; font-size: 2.2rem; font-weight: 700;
        line-height: 1.15; letter-spacing: -.03em; margin-bottom: .85rem;
    }
    .al-mid h2 em { color: var(--gold); font-style: italic; }
    .al-mid p { color: rgba(255,255,255,.5); font-size: .88rem; line-height: 1.75; }
    .al-features { margin-top: 2rem; display: flex; flex-direction: column; gap: .65rem; }
    .al-feat {
        display: flex; align-items: flex-start; gap: .7rem;
    }
    .al-feat-icon {
        width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
        background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1);
        display: flex; align-items: center; justify-content: center;
        font-size: .88rem; color: rgba(255,255,255,.8); margin-top: 1px;
    }
    .al-feat-text strong { color: rgba(255,255,255,.82); font-size: .82rem; display: block; margin-bottom: 1px; }
    .al-feat-text span   { color: rgba(255,255,255,.38); font-size: .75rem; }
    .al-bottom { position: relative; z-index: 2; }
    .al-note {
        background: rgba(212,160,23,.12); border: 1px solid rgba(212,160,23,.2);
        border-radius: var(--r-sm); padding: .75rem 1rem;
        display: flex; align-items: flex-start; gap: .6rem;
    }
    .al-note i { color: var(--gold); font-size: .88rem; margin-top: 1px; flex-shrink: 0; }
    .al-note p { color: rgba(255,255,255,.55); font-size: .76rem; line-height: 1.6; margin: 0; }

    /* ── Right form ── */
    .auth-right {
        width: 480px; flex-shrink: 0; background: var(--white);
        display: flex; align-items: center; justify-content: center;
        padding: 2.5rem 2.25rem;
        position: relative;
    }
    @media(max-width:899px) { .auth-right { width: 100%; padding: 2rem 1.5rem; } }
    @media(max-width:400px)  { .auth-right { padding: 1.5rem 1.25rem; } }
    .auth-form { width: 100%; max-width: 360px; }

    .form-top { margin-bottom: 2rem; }
    .form-top .mobile-brand {
        display: flex; align-items: center; gap: .6rem; margin-bottom: 1.5rem;
    }
    .form-top .mobile-brand .logo-m {
        width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--primary), #4B7CD0);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: .88rem;
    }
    .form-top .mobile-brand span { font-family: var(--font-disp); font-style: italic; font-size: .9rem; color: var(--ink-900); font-weight: 600; }
    .form-top h2 { font-family: var(--font-disp); font-style: italic; font-size: 1.55rem; font-weight: 700; color: var(--ink-900); letter-spacing: -.03em; margin-bottom: .28rem; }
    .form-top p  { color: var(--ink-500); font-size: .84rem; }

    .form-label { font-size: .77rem; font-weight: 600; color: var(--ink-700); margin-bottom: .38rem; display: block; }
    .input-group-row { display: flex; flex-direction: column; gap: .95rem; margin-bottom: 1rem; }
    .field { position: relative; }
    .field-icon { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); color: var(--ink-300); font-size: .9rem; pointer-events: none; z-index: 2; }
    input.form-inp {
        width: 100%; border: 1.5px solid var(--ink-200); border-radius: var(--r-sm);
        padding: .62rem .9rem .62rem 2.5rem; font-family: var(--font); font-size: .84rem;
        transition: border-color .14s, box-shadow .14s; outline: none;
        background: var(--white); color: var(--ink-900); min-height: 42px;
    }
    input.form-inp:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(30,64,128,.1); }
    input.form-inp::placeholder { color: var(--ink-300); }
    input.form-inp.has-right { padding-right: 2.5rem; }
    .field-toggle {
        position: absolute; right: .85rem; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: var(--ink-400); cursor: pointer;
        font-size: .9rem; z-index: 2; padding: 0;
        transition: color .14s;
    }
    .field-toggle:hover { color: var(--primary); }

    .form-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.1rem; }
    .check-row { display: flex; align-items: center; gap: .4rem; font-size: .79rem; color: var(--ink-500); cursor: pointer; }
    .check-row input { accent-color: var(--primary); width: 14px; height: 14px; }
    .forgot-link { font-size: .79rem; color: var(--primary); font-weight: 600; text-decoration: none; }
    .forgot-link:hover { text-decoration: underline; }

    .btn-submit {
        width: 100%; padding: .68rem; border-radius: var(--r-sm);
        background: var(--primary); border: none; color: #fff;
        font-family: var(--font); font-size: .88rem; font-weight: 700;
        cursor: pointer; transition: all .18s;
        display: flex; align-items: center; justify-content: center; gap: .45rem;
        min-height: 44px;
    }
    .btn-submit:hover { background: var(--primary-dk); box-shadow: 0 4px 14px rgba(30,64,128,.4); transform: translateY(-1px); }
    .btn-submit:active { transform: translateY(0); }

    .divider { display: flex; align-items: center; gap: .75rem; margin: 1.25rem 0; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--ink-200); }
    .divider span { color: var(--ink-400); font-size: .73rem; white-space: nowrap; }

    .social-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
    .btn-social {
        display: flex; align-items: center; justify-content: center; gap: .5rem;
        padding: .56rem; border-radius: var(--r-sm); border: 1.5px solid var(--ink-200);
        background: var(--white); font-family: var(--font); font-size: .82rem;
        font-weight: 600; color: var(--ink-700); text-decoration: none;
        transition: all .14s; cursor: pointer;
    }
    .btn-social:hover { border-color: var(--ink-400); background: var(--ink-100); color: var(--ink-900); }

    .sign-up { text-align: center; margin-top: 1.35rem; font-size: .82rem; color: var(--ink-500); }
    .sign-up a { color: var(--primary); font-weight: 700; text-decoration: none; }
    .sign-up a:hover { text-decoration: underline; }

    .err-box {
        background: var(--red-lt); border: 1px solid rgba(190,18,60,.2);
        border-radius: var(--r-sm); padding: .65rem .9rem;
        color: #9F1239; font-size: .8rem; margin-bottom: 1.1rem;
    }
    </style>
</head>
<body>

{{-- Left panel --}}
<div class="auth-left">
    <div class="al-grid"></div>
    <div class="al-orb-1"></div>
    <div class="al-orb-2"></div>
    <div class="al-top">
        <div class="al-logo">
            <div class="al-logo-mark"><i class="bi bi-building-check"></i></div>
            <div class="al-logo-text">
                <div class="t1">E-Services</div>
                <div class="t2">Lebanon Gov Portal</div>
            </div>
        </div>
    </div>
    <div class="al-mid">
        <h2>Government<br>Services, <em>Digitized</em></h2>
        <p>Submit requests, track progress, pay fees, and receive official documents — all from your device.</p>
        <div class="al-features">
            <div class="al-feat">
                <div class="al-feat-icon"><i class="bi bi-shield-check"></i></div>
                <div class="al-feat-text">
                    <strong>Bank-grade security</strong>
                    <span>Encrypted connections & secure data storage</span>
                </div>
            </div>
            <div class="al-feat">
                <div class="al-feat-icon"><i class="bi bi-qr-code"></i></div>
                <div class="al-feat-text">
                    <strong>QR Code tracking</strong>
                    <span>Follow every request in real-time with a scannable code</span>
                </div>
            </div>
            <div class="al-feat">
                <div class="al-feat-icon"><i class="bi bi-credit-card"></i></div>
                <div class="al-feat-text">
                    <strong>Online payments</strong>
                    <span>Card, cryptocurrency — pay from anywhere</span>
                </div>
            </div>
        </div>
    </div>
    <div class="al-bottom">
        <div class="al-note">
            <i class="bi bi-info-circle"></i>
            <p>This is an official government digital services platform. Use only your real personal information when registering.</p>
        </div>
    </div>
</div>

{{-- Right form --}}
<div class="auth-right">
    <div class="auth-form">
        <div class="form-top">
            {{-- Mobile logo --}}
            <div class="mobile-brand d-block" style="display: none !important">
                <div class="logo-m"><i class="bi bi-building-check"></i></div>
                <span>E-Services</span>
            </div>
            <style>@media(max-width:899px){.mobile-brand{display:flex !important}}</style>
            <h2>Welcome back</h2>
            <p>Sign in to your account to continue</p>
        </div>

        @if($errors->any())
        <div class="err-box">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST" novalidate>
            @csrf
            <div class="input-group-row">
                <div>
                    <label class="form-label" for="email">Email address</label>
                    <div class="field">
                        <i class="bi bi-envelope field-icon"></i>
                        <input class="form-inp" id="email" type="email" name="email"
                               value="{{ old('email') }}" placeholder="you@example.com"
                               autocomplete="email" required autofocus>
                    </div>
                </div>
                <div>
                    <label class="form-label" for="password">Password</label>
                    <div class="field">
                        <i class="bi bi-lock field-icon"></i>
                        <input class="form-inp has-right" id="password" type="password" name="password"
                               placeholder="Enter your password" autocomplete="current-password" required>
                        <button type="button" class="field-toggle" id="togglePw" aria-label="Toggle visibility">
                            <i class="bi bi-eye" id="pwIcon"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <label class="check-row">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            </div>
            <button type="submit" class="btn-submit">
                <i class="bi bi-box-arrow-in-right"></i> Sign In to Your Account
            </button>
        </form>

        <div class="divider"><span>or sign in with</span></div>

        <div class="social-grid">
            <a href="{{ route('social.redirect', 'google') }}" class="btn-social">
                <svg width="16" height="16" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Google
            </a>
            <a href="{{ route('social.redirect', 'github') }}" class="btn-social">
                <i class="bi bi-github" style="font-size:1rem"></i>
                GitHub
            </a>
        </div>

        <p class="sign-up">
            Don't have an account? <a href="{{ route('register') }}">Create one free</a>
        </p>
    </div>
</div>

<script>
document.getElementById('togglePw')?.addEventListener('click', function() {
    const pw = document.getElementById('password');
    const ic = document.getElementById('pwIcon');
    if(pw.type==='password') { pw.type='text';     ic.className='bi bi-eye-slash'; }
    else                     { pw.type='password'; ic.className='bi bi-eye'; }
});
</script>
</body>
</html>
