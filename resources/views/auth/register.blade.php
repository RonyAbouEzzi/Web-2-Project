<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — E-Services Lebanon</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
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

        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            min-height: 100vh;
            position: relative;
            padding: 2.5rem 1.5rem;
        }

        /* Amber glow */
        .auth-glow {
            position: fixed; pointer-events: none;
            top: -160px; right: -160px;
            width: 600px; height: 520px; border-radius: 50%;
            background: radial-gradient(circle at 50% 40%,
                rgba(253,224,130,.52) 0%,
                rgba(254,243,199,.32) 35%,
                transparent 68%);
            z-index: 0;
        }
        .auth-glow-2 {
            position: fixed; pointer-events: none;
            bottom: -180px; left: -160px;
            width: 480px; height: 420px; border-radius: 50%;
            background: radial-gradient(circle at 50% 60%,
                rgba(20,184,166,.12) 0%,
                transparent 65%);
            z-index: 0;
        }

        .auth-wrap {
            position: relative; z-index: 1;
            width: 100%; max-width: 520px;
        }

        /* Card */
        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 2.25rem 2.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,.06), 0 1px 4px rgba(0,0,0,.04);
        }

        /* Brand */
        .auth-brand {
            display: flex; align-items: center; justify-content: center; gap: .65rem;
            text-decoration: none; color: var(--text);
            margin-bottom: 1.75rem;
        }
        .auth-brand-icon {
            width: 36px; height: 36px; border-radius: 9px;
            background: var(--text);
            display: flex; align-items: center; justify-content: center;
            color: var(--bg); font-size: .9rem; flex-shrink: 0;
        }
        .auth-brand-name {
            font-family: var(--serif); font-weight: 700; font-size: 1rem; color: var(--text);
        }
        .auth-brand-sub {
            font-size: .62rem; color: var(--label); display: block;
            margin-top: 1px; letter-spacing: .04em;
        }

        /* Heading */
        .auth-heading {
            font-family: var(--serif); font-weight: 600;
            font-size: 1.5rem; color: var(--text);
            margin-bottom: .3rem; line-height: 1.25;
        }
        .auth-sub { font-size: .83rem; color: var(--muted); margin-bottom: 1.5rem; }

        /* Section label */
        .section-label {
            display: flex; align-items: center; gap: .7rem;
            font-size: .7rem; font-weight: 600; letter-spacing: .1em;
            text-transform: uppercase; color: var(--label);
            margin: 1.1rem 0 1rem;
        }
        .section-label::before, .section-label::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }

        /* Social buttons */
        .btn-social {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: .5rem;
            padding: .55rem; border: 1px solid var(--border); border-radius: .5rem;
            background: var(--surface); color: var(--muted);
            font-family: var(--font); font-size: .82rem; font-weight: 600;
            text-decoration: none; transition: border-color .15s, box-shadow .15s, color .15s;
        }
        .btn-social:hover {
            border-color: #c7c0b7; color: var(--text);
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
        }

        /* Form */
        .form-label {
            font-size: .78rem; font-weight: 600; letter-spacing: .02em;
            color: var(--muted); margin-bottom: .3rem; display: block;
        }
        .form-control, .form-select {
            font-family: var(--font); font-size: .87rem; color: var(--text);
            background: var(--bg); border: 1px solid var(--border);
            border-radius: .45rem; padding: .55rem .8rem;
            width: 100%; transition: border-color .15s, box-shadow .15s;
            outline: none; appearance: none;
        }
        .form-control::placeholder { color: var(--label); }
        .form-control:focus, .form-select:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(13,148,136,.15);
            background: var(--surface);
        }
        .form-text { font-size: .73rem; color: var(--label); margin-top: .3rem; }

        /* Upload zone */
        .upload-zone {
            border: 2px dashed var(--border); border-radius: .5rem;
            padding: 1.1rem; text-align: center; cursor: pointer;
            transition: border-color .18s, background .18s;
            background: var(--bg);
        }
        .upload-zone:hover, .upload-zone.drag {
            border-color: var(--teal); background: rgba(13,148,136,.04);
        }
        .upload-zone input { display: none; }
        .upload-icon { font-size: 1.4rem; color: var(--label); margin-bottom: .25rem; transition: color .15s; }
        .upload-zone:hover .upload-icon { color: var(--teal); }
        .upload-title { font-size: .8rem; font-weight: 600; color: var(--muted); margin-bottom: .1rem; }
        .upload-sub   { font-size: .73rem; color: var(--label); }
        .upload-preview {
            display: none; align-items: center; gap: .5rem;
            background: #f0fdf9; border-radius: .4rem;
            padding: .45rem .8rem; margin-top: .5rem;
            font-size: .77rem; color: var(--teal); border: 1px solid rgba(13,148,136,.2);
        }

        /* Submit */
        .btn-submit {
            width: 100%; padding: .65rem;
            background: var(--teal); border: none; border-radius: .5rem;
            color: #fff; font-family: var(--font); font-size: .88rem; font-weight: 700;
            cursor: pointer; transition: background .15s, box-shadow .15s, transform .1s;
            letter-spacing: .01em;
        }
        .btn-submit:hover {
            background: var(--teal-dk);
            box-shadow: 0 4px 14px rgba(13,148,136,.38);
            transform: translateY(-1px);
        }
        .btn-submit:active { transform: translateY(0); }

        /* Checkbox */
        .form-check { display: flex; align-items: flex-start; gap: .5rem; }
        .form-check-input {
            width: 15px; height: 15px; border: 1px solid var(--border);
            border-radius: 4px; background: var(--bg); cursor: pointer;
            flex-shrink: 0; accent-color: var(--teal); margin-top: 2px;
        }
        .form-check-label { font-size: .8rem; color: var(--muted); cursor: pointer; line-height: 1.5; }

        /* Links */
        .auth-link { color: var(--teal); font-weight: 600; text-decoration: none; }
        .auth-link:hover { text-decoration: underline; color: var(--teal-dk); }

        /* Error box */
        .auth-error {
            background: #fff1f0; border: 1px solid #fecaca;
            border-radius: .45rem; padding: .6rem .85rem;
            color: #b91c1c; font-size: .8rem; margin-bottom: 1rem;
        }

        /* Footer text */
        .auth-footer-text {
            text-align: center; font-size: .8rem; color: var(--muted); margin-top: 1.25rem;
        }

        /* Back to home */
        .auth-back {
            display: block; text-align: center;
            font-size: .75rem; color: var(--label); margin-top: 1rem;
            text-decoration: none; transition: color .15s;
        }
        .auth-back:hover { color: var(--muted); }
    </style>
</head>
<body>

<div class="auth-glow"></div>
<div class="auth-glow-2"></div>

<div class="auth-wrap">
    <div class="auth-card">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="auth-brand">
            <span class="auth-brand-icon"><i class="bi bi-building-check"></i></span>
            <span>
                <span class="auth-brand-name">E-Services</span>
                <span class="auth-brand-sub">MUNICIPAL PORTAL · LEBANON</span>
            </span>
        </a>

        <h1 class="auth-heading">Create your account</h1>
        <p class="auth-sub">Register once and access all municipal services online.</p>

        {{-- Social sign-up --}}
        <div class="d-flex gap-2 mb-1">
            <a href="{{ route('social.redirect', 'google') }}" class="btn-social">
                <svg width="16" height="16" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue with Google
            </a>
            <a href="{{ route('social.redirect', 'github') }}" class="btn-social">
                <i class="bi bi-github" style="font-size:.95rem;"></i>
                GitHub
            </a>
        </div>

        <div class="section-label">or register with email</div>

        {{-- Errors --}}
        @if($errors->any())
            <div class="auth-error">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label" for="first_name">First Name</label>
                    <input class="form-control" id="first_name" type="text" name="first_name"
                           value="{{ old('first_name') }}" placeholder="Ahmad" required autofocus>
                </div>
                <div class="col-6">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input class="form-control" id="last_name" type="text" name="last_name"
                           value="{{ old('last_name') }}" placeholder="Khoury" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label" for="email">Email Address</label>
                    <input class="form-control" id="email" type="email" name="email"
                           value="{{ old('email') }}" placeholder="you@example.com" required>
                </div>
                <div class="col-6">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input class="form-control" id="phone" type="tel" name="phone"
                           value="{{ old('phone') }}" placeholder="+961 7x xxx xxx">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="national_id">National ID Number</label>
                <input class="form-control" id="national_id" type="text" name="national_id"
                       value="{{ old('national_id') }}" placeholder="LB-XXXXXXXXX" required>
                <div class="form-text">Used for identity verification. Encrypted and protected.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    National ID Document
                    <span style="font-weight:400; color:var(--label);">(photo or scan)</span>
                </label>
                <div class="upload-zone" id="upload-zone" onclick="document.getElementById('id-doc').click()">
                    <div class="upload-icon"><i class="bi bi-cloud-upload"></i></div>
                    <div class="upload-title">Click to upload your ID document</div>
                    <div class="upload-sub">JPG, PNG or PDF · Max 5 MB</div>
                    <input type="file" id="id-doc" name="national_id_document" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                <div class="upload-preview" id="upload-preview">
                    <i class="bi bi-file-earmark-check"></i>
                    <span id="upload-name">File selected</span>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" id="password" type="password" name="password"
                           placeholder="Min. 8 characters" required autocomplete="new-password">
                </div>
                <div class="col-6">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input class="form-control" id="password_confirmation" type="password"
                           name="password_confirmation" placeholder="Repeat password" required autocomplete="new-password">
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">
                    I agree to the <a href="#" class="auth-link">Terms of Service</a> and <a href="#" class="auth-link">Privacy Policy</a>
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-person-check me-1"></i> Create My Account
            </button>
        </form>

        <p class="auth-footer-text">
            Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign in</a>
        </p>

    </div>

    <a href="{{ route('home') }}" class="auth-back">
        <i class="bi bi-arrow-left me-1"></i> Back to home
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const zone    = document.getElementById('upload-zone');
    const input   = document.getElementById('id-doc');
    const preview = document.getElementById('upload-preview');
    const nameEl  = document.getElementById('upload-name');

    input?.addEventListener('change', () => {
        if (input.files[0]) { nameEl.textContent = input.files[0].name; preview.style.display = 'flex'; }
    });
    zone?.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag'); });
    zone?.addEventListener('dragleave', ()=> zone.classList.remove('drag'));
    zone?.addEventListener('drop', e => {
        e.preventDefault(); zone.classList.remove('drag');
        if (e.dataTransfer.files[0]) {
            input.files = e.dataTransfer.files;
            nameEl.textContent = e.dataTransfer.files[0].name;
            preview.style.display = 'flex';
        }
    });
</script>
</body>
</html>
