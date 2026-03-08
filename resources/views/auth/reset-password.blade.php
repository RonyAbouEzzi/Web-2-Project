<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Reset Password — E-Services Lebanon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wdth,wght@75..100,400..700&family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    :root{--navy:#060D1F;--primary:#1E4080;--primary-lt:#EFF6FF;--red:#BE123C;--red-lt:#FFF1F2;--ink-900:#111318;--ink-700:#2D3748;--ink-500:#718096;--ink-200:#E2E8F0;--white:#fff;--font:'Instrument Sans',system-ui,sans-serif;--font-disp:'Fraunces',Georgia,serif;--r-sm:8px;}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    html{font-size:14px;}
    body{font-family:var(--font);min-height:100vh;background:var(--navy);display:flex;align-items:center;justify-content:center;padding:2rem 1rem;-webkit-font-smoothing:antialiased;position:relative;overflow:hidden;}
    .bg-grid{position:fixed;inset:0;background-image:linear-gradient(rgba(255,255,255,.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.02) 1px,transparent 1px);background-size:36px 36px;pointer-events:none;}
    .orb1{position:fixed;top:-100px;right:-80px;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(30,64,128,.4),transparent 70%);pointer-events:none;}
    .card{position:relative;z-index:2;background:rgba(255,255,255,.97);border-radius:20px;padding:2.5rem 2.25rem;width:100%;max-width:420px;box-shadow:0 40px 80px rgba(0,0,0,.4);}
    .logo{display:flex;align-items:center;gap:.55rem;justify-content:center;margin-bottom:1.75rem;}
    .logo-mark{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--primary),#4B7CD0);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.88rem;}
    .logo-text{font-family:var(--font-disp);font-style:italic;font-size:.9rem;font-weight:600;color:var(--ink-700);}
    .icon-box{width:66px;height:66px;border-radius:50%;background:var(--primary-lt);border:2px solid var(--primary);display:flex;align-items:center;justify-content:center;margin:0 auto 1.3rem;font-size:1.5rem;color:var(--primary);}
    h2{font-family:var(--font-disp);font-style:italic;text-align:center;font-size:1.45rem;font-weight:700;color:var(--ink-900);letter-spacing:-.03em;margin-bottom:.35rem;}
    .sub{text-align:center;color:var(--ink-500);font-size:.84rem;line-height:1.65;margin-bottom:1.75rem;}
    .form-label{font-size:.77rem;font-weight:600;color:var(--ink-700);margin-bottom:.38rem;display:block;}
    .field{position:relative;margin-bottom:.95rem;}
    .fi{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:#CBD5E0;font-size:.9rem;pointer-events:none;}
    input.inp{width:100%;border:1.5px solid var(--ink-200);border-radius:var(--r-sm);padding:.62rem .9rem .62rem 2.5rem;font-family:var(--font);font-size:.84rem;transition:border-color .14s,box-shadow .14s;outline:none;background:var(--white);color:var(--ink-900);min-height:41px;}
    input.inp:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(30,64,128,.1);}
    input.inp::placeholder{color:#CBD5E0;}
    .btn{width:100%;padding:.68rem;border-radius:var(--r-sm);background:var(--primary);border:none;color:#fff;font-family:var(--font);font-size:.88rem;font-weight:700;cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center;gap:.45rem;min-height:44px;margin-top:.5rem;}
    .btn:hover{background:#162F60;box-shadow:0 4px 14px rgba(30,64,128,.4);transform:translateY(-1px);}
    .err-box{background:var(--red-lt);border:1px solid rgba(190,18,60,.2);border-radius:var(--r-sm);padding:.65rem .9rem;color:#9F1239;font-size:.8rem;margin-bottom:1.1rem;}
    </style>
</head>
<body>
<div class="bg-grid"></div>
<div class="orb1"></div>
<div class="card">
    <div class="logo">
        <div class="logo-mark"><i class="bi bi-building-check"></i></div>
        <span class="logo-text">E-Services Lebanon</span>
    </div>
    <div class="icon-box"><i class="bi bi-lock-fill"></i></div>
    <h2>Reset your password</h2>
    <p class="sub">Enter your email and choose a new password to regain access to your account.</p>

    @if($errors->any())
    <div class="err-box">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <form action="{{ route('password.update') }}" method="POST" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="form-label" for="email">Email address</label>
            <div class="field">
                <i class="bi bi-envelope fi"></i>
                <input class="inp" id="email" type="email" name="email" value="{{ old('email', request('email')) }}" placeholder="you@example.com" required autofocus>
            </div>
        </div>
        <div>
            <label class="form-label" for="password">New Password</label>
            <div class="field">
                <i class="bi bi-lock fi"></i>
                <input class="inp" id="password" type="password" name="password" placeholder="Min. 8 characters" required autocomplete="new-password">
            </div>
        </div>
        <div>
            <label class="form-label" for="password_confirmation">Confirm New Password</label>
            <div class="field">
                <i class="bi bi-lock-fill fi"></i>
                <input class="inp" id="password_confirmation" type="password" name="password_confirmation" placeholder="Repeat new password" required autocomplete="new-password">
            </div>
        </div>
        <button type="submit" class="btn"><i class="bi bi-key"></i> Reset Password</button>
    </form>
</div>
</body>
</html>
