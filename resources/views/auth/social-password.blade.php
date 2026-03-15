<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Set Password - E-Services Lebanon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wdth,wght@75..100,400..700&family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    :root {
        --navy: #060D1F;
        --primary: #1E4080;
        --primary-dk: #162F60;
        --ink-900: #111318;
        --ink-700: #2D3748;
        --ink-500: #718096;
        --ink-300: #CBD5E0;
        --ink-200: #E2E8F0;
        --ink-100: #F7FAFC;
        --red-lt: #FFF1F2;
        --white: #fff;
        --font: 'Instrument Sans', system-ui, sans-serif;
        --font-disp: 'Fraunces', Georgia, serif;
        --r: 12px;
        --r-sm: 8px;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 14px; }
    body {
        min-height: 100vh;
        font-family: var(--font);
        background: linear-gradient(135deg, #F0F4FA 0%, #EAF0FF 45%, #F5F8FF 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .card {
        width: 100%;
        max-width: 430px;
        background: var(--white);
        border: 1px solid var(--ink-200);
        border-radius: var(--r);
        box-shadow: 0 16px 38px rgba(6, 13, 31, 0.12);
        padding: 1.4rem;
    }
    .top {
        display: flex;
        align-items: center;
        gap: .65rem;
        margin-bottom: 1rem;
    }
    .logo {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary), #4B7CD0);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .95rem;
    }
    h1 {
        font-family: var(--font-disp);
        font-size: 1.35rem;
        color: var(--ink-900);
        letter-spacing: -.02em;
        margin-bottom: .18rem;
    }
    .sub {
        color: var(--ink-500);
        font-size: .82rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }
    .meta {
        background: var(--ink-100);
        border: 1px solid var(--ink-200);
        border-radius: var(--r-sm);
        padding: .75rem .85rem;
        margin-bottom: 1rem;
        font-size: .78rem;
        color: var(--ink-700);
        display: grid;
        gap: .25rem;
    }
    .meta strong { color: var(--ink-900); }
    .form-label {
        font-size: .76rem;
        font-weight: 600;
        color: var(--ink-700);
        margin-bottom: .35rem;
        display: block;
    }
    .field { position: relative; margin-bottom: .9rem; }
    .fi {
        position: absolute;
        left: .85rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink-300);
        font-size: .88rem;
        pointer-events: none;
    }
    .inp {
        width: 100%;
        min-height: 42px;
        border: 1.5px solid var(--ink-200);
        border-radius: var(--r-sm);
        padding: .62rem .9rem .62rem 2.45rem;
        font-size: .84rem;
        color: var(--ink-900);
        outline: none;
        transition: border-color .14s, box-shadow .14s;
    }
    .inp:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(30, 64, 128, .1);
    }
    .btn {
        width: 100%;
        min-height: 44px;
        border: none;
        border-radius: var(--r-sm);
        background: var(--primary);
        color: #fff;
        font-weight: 700;
        font-size: .86rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        transition: all .16s;
    }
    .btn:hover {
        background: var(--primary-dk);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(30, 64, 128, .32);
    }
    .hint {
        margin-top: .7rem;
        color: var(--ink-500);
        font-size: .74rem;
        text-align: center;
    }
    .err-box {
        background: var(--red-lt);
        border: 1px solid rgba(190, 18, 60, .2);
        border-radius: var(--r-sm);
        padding: .65rem .9rem;
        color: #9F1239;
        font-size: .8rem;
        margin-bottom: .9rem;
    }
    </style>
</head>
<body>
<div class="card">
    <div class="top">
        <div class="logo"><i class="bi bi-shield-lock"></i></div>
        <div>
            <h1>Set your password</h1>
            <p class="sub">Complete your {{ ucfirst($social['provider'] ?? 'social') }} sign-in by choosing a password.</p>
        </div>
    </div>

    @if(session('info'))
        <div class="meta">{{ session('info') }}</div>
    @endif

    @if($errors->any())
        <div class="err-box">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    <div class="meta">
        <div><strong>Provider:</strong> {{ ucfirst($social['provider'] ?? 'social') }}</div>
        <div><strong>Email:</strong> {{ $social['email'] ?? '-' }}</div>
    </div>

    <form method="POST" action="{{ route('social.password.store') }}" novalidate>
        @csrf
        <label class="form-label" for="password">Password</label>
        <div class="field">
            <i class="bi bi-lock fi"></i>
            <input id="password" class="inp" type="password" name="password" placeholder="Minimum 8 characters" autocomplete="new-password" required>
        </div>

        <label class="form-label" for="password_confirmation">Confirm password</label>
        <div class="field">
            <i class="bi bi-lock-fill fi"></i>
            <input id="password_confirmation" class="inp" type="password" name="password_confirmation" placeholder="Repeat your password" autocomplete="new-password" required>
        </div>

        <button type="submit" class="btn"><i class="bi bi-check-circle"></i> Save password and continue</button>
    </form>

    <p class="hint">After this step, you can sign in with either social login or email/password.</p>
</div>
</body>
</html>
