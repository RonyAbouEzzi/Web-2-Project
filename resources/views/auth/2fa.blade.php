<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Two-Factor Verification — E-Services Lebanon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wdth,wght@75..100,400..700&family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    :root{--navy:#060D1F;--primary:#1E4080;--primary-lt:#EFF6FF;--gold:#D4A017;--red:#BE123C;--red-lt:#FFF1F2;--ink-900:#111318;--ink-700:#2D3748;--ink-500:#718096;--ink-200:#E2E8F0;--ink-100:#F7FAFC;--white:#fff;--font:'Instrument Sans',system-ui,sans-serif;--font-disp:'Fraunces',Georgia,serif;--font-mono:'JetBrains Mono',monospace;}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    html{font-size:14px;}
    body{font-family:var(--font);min-height:100vh;background:var(--navy);display:flex;align-items:center;justify-content:center;-webkit-font-smoothing:antialiased;padding:2rem 1rem;position:relative;overflow:hidden;}
    .bg-orb-1{position:fixed;top:-100px;right:-100px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(30,64,128,.4),transparent 70%);pointer-events:none;}
    .bg-orb-2{position:fixed;bottom:-120px;left:-60px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(212,160,23,.14),transparent 70%);pointer-events:none;}
    .bg-grid{position:fixed;inset:0;background-image:linear-gradient(rgba(255,255,255,.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.02) 1px,transparent 1px);background-size:36px 36px;pointer-events:none;}

    .card-wrap{position:relative;z-index:2;background:rgba(255,255,255,.97);border-radius:20px;padding:2.5rem 2.25rem;width:100%;max-width:400px;text-align:center;box-shadow:0 40px 80px rgba(0,0,0,.4);}

    .lock-icon{width:68px;height:68px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;margin:0 auto 1.35rem;font-size:1.6rem;color:#fff;box-shadow:0 8px 24px rgba(30,64,128,.45);}
    h2{font-family:var(--font-disp);font-style:italic;font-size:1.5rem;font-weight:700;color:var(--ink-900);letter-spacing:-.03em;margin-bottom:.35rem;}
    .sub{font-size:.84rem;color:var(--ink-500);line-height:1.65;margin-bottom:1.75rem;}
    .sub strong{color:var(--ink-700);}

    .otp-row{display:flex;justify-content:center;gap:.55rem;margin-bottom:1.6rem;}
    .otp-input{width:48px;height:56px;border:2px solid var(--ink-200);border-radius:12px;text-align:center;font-family:var(--font-mono);font-size:1.35rem;font-weight:600;color:var(--ink-900);outline:none;background:var(--white);transition:border-color .14s,box-shadow .14s,transform .18s;caret-color:var(--primary);}
    .otp-input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(30,64,128,.12);transform:scale(1.05);}
    .otp-input.filled{border-color:var(--primary);}

    .btn-verify{width:100%;padding:.68rem;border-radius:10px;background:var(--primary);border:none;color:#fff;font-family:var(--font);font-size:.88rem;font-weight:700;cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center;gap:.45rem;min-height:44px;}
    .btn-verify:hover{background:#162F60;box-shadow:0 4px 14px rgba(30,64,128,.4);transform:translateY(-1px);}
    .btn-verify:active{transform:translateY(0);}
    .btn-verify:disabled{opacity:.6;cursor:not-allowed;transform:none;}

    .help-links{display:flex;justify-content:center;gap:1.5rem;margin-top:1.25rem;}
    .help-links a{font-size:.79rem;color:var(--primary);font-weight:600;text-decoration:none;}
    .help-links a:hover{text-decoration:underline;}

    .err-box{background:var(--red-lt);border:1px solid rgba(190,18,60,.2);border-radius:9px;padding:.6rem .9rem;color:#9F1239;font-size:.79rem;margin-bottom:1.1rem;text-align:left;}

    .logo-mini{display:flex;align-items:center;justify-content:center;gap:.5rem;margin-bottom:1.75rem;}
    .lm-mark{width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,var(--primary),#4B7CD0);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.82rem;}
    .lm-text{font-family:var(--font-disp);font-style:italic;font-size:.88rem;font-weight:600;color:var(--ink-700);}

    @media(max-width:440px){.otp-input{width:42px;height:50px;font-size:1.2rem;}.card-wrap{padding:2rem 1.5rem;}}
    </style>
</head>
<body>
<div class="bg-grid"></div>
<div class="bg-orb-1"></div>
<div class="bg-orb-2"></div>

<div class="card-wrap">
    <div class="logo-mini">
        <div class="lm-mark"><i class="bi bi-building-check"></i></div>
        <span class="lm-text">E-Services</span>
    </div>

    <div class="lock-icon"><i class="bi bi-shield-check"></i></div>
    <h2>Two-Factor Verification</h2>
    <p class="sub">
        Open your <strong>Google Authenticator</strong> (or similar) app and enter the 6-digit code for E-Services.
    </p>

    @if($errors->any())
    <div class="err-box">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <form action="{{ route('2fa.verify') }}" method="POST" id="otpForm">
        @csrf
        <input type="hidden" name="otp_code" id="otpHidden">
        <div class="otp-row" id="otpRow">
            @for($i=0;$i<6;$i++)
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="{{ $i===0 ? 'one-time-code' : 'off' }}" required>
            @endfor
        </div>
        <button type="submit" class="btn-verify" id="verifyBtn" disabled>
            <i class="bi bi-shield-lock"></i> Verify & Sign In
        </button>
    </form>

    <div class="help-links">
        <a href="{{ route('login') }}">← Back to Login</a>
        <a href="#">Lost access?</a>
    </div>
</div>

<script>
const inputs  = [...document.querySelectorAll('.otp-input')];
const hidden  = document.getElementById('otpHidden');
const btn     = document.getElementById('verifyBtn');
const form    = document.getElementById('otpForm');

function updateState() {
    const val = inputs.map(i => i.value).join('');
    hidden.value = val;
    btn.disabled = val.length < 6;
    inputs.forEach((inp, i) => inp.classList.toggle('filled', !!inp.value));
}

inputs.forEach((inp, i) => {
    inp.addEventListener('input', e => {
        const v = e.target.value.replace(/\D/,'').slice(-1);
        inp.value = v;
        updateState();
        if(v && i < 5) inputs[i+1].focus();
    });
    inp.addEventListener('keydown', e => {
        if(e.key==='Backspace' && !inp.value && i > 0) { inputs[i-1].focus(); inputs[i-1].value=''; updateState(); }
        if(e.key==='ArrowLeft'  && i > 0) { e.preventDefault(); inputs[i-1].focus(); }
        if(e.key==='ArrowRight' && i < 5) { e.preventDefault(); inputs[i+1].focus(); }
    });
    inp.addEventListener('paste', e => {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'').slice(0,6);
        [...paste].forEach((c, j) => { if(inputs[i+j]) inputs[i+j].value = c; });
        updateState();
        const nextEmpty = inputs.findIndex((inp,j) => j >= i && !inp.value);
        if(nextEmpty !== -1) inputs[nextEmpty].focus();
        else inputs[5].focus();
    });
});

inputs[0].focus();
</script>
</body>
</html>
