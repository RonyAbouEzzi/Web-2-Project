<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Create Account — E-Services Lebanon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wdth,wght@75..100,400..700&family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    :root {
        --navy:#060D1F; --primary:#1E4080; --primary-lt:#EFF6FF; --primary-dk:#162F60;
        --gold:#D4A017; --red:#BE123C; --red-lt:#FFF1F2;
        --green:#0D7A4E; --green-lt:#ECFDF5;
        --ink-900:#111318; --ink-700:#2D3748; --ink-500:#718096;
        --ink-300:#CBD5E0; --ink-200:#E2E8F0; --ink-100:#F7FAFC;
        --white:#fff;
        --font:'Instrument Sans',system-ui,sans-serif;
        --font-disp:'Fraunces',Georgia,serif;
        --r:12px; --r-sm:8px;
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    html{font-size:14px;}
    body{font-family:var(--font);min-height:100vh;background:#F0F4FA;-webkit-font-smoothing:antialiased;}

    .reg-wrap{min-height:100vh;display:flex;align-items:stretch;}

    /* Left panel */
    .reg-left{width:360px;flex-shrink:0;background:var(--navy);position:relative;overflow:hidden;display:none;flex-direction:column;justify-content:space-between;padding:2.5rem;}
    @media(min-width:1024px){.reg-left{display:flex;}}
    .rl-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:36px 36px;}
    .rl-orb{position:absolute;top:-80px;right:-80px;width:360px;height:360px;border-radius:50%;background:radial-gradient(circle,rgba(30,64,128,.5),transparent 70%);pointer-events:none;}
    .rl-logo{position:relative;z-index:2;display:flex;align-items:center;gap:.6rem;}
    .rl-logo-mark{width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,var(--primary),#4B7CD0);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.95rem;box-shadow:0 2px 12px rgba(30,64,128,.4);}
    .rl-logo-text .t1{color:#fff;font-family:var(--font-disp);font-style:italic;font-size:.9rem;font-weight:600;}
    .rl-logo-text .t2{color:rgba(255,255,255,.28);font-size:.63rem;font-weight:500;letter-spacing:.06em;text-transform:uppercase;}
    .rl-mid{position:relative;z-index:2;}
    .rl-mid h2{font-family:var(--font-disp);font-style:italic;color:#fff;font-size:1.85rem;font-weight:700;line-height:1.18;letter-spacing:-.03em;margin-bottom:.75rem;}
    .rl-mid h2 em{color:var(--gold);}
    .rl-mid p{color:rgba(255,255,255,.45);font-size:.84rem;line-height:1.7;}
    .rl-checklist{margin-top:1.75rem;display:flex;flex-direction:column;gap:.55rem;}
    .rl-check{display:flex;align-items:center;gap:.6rem;font-size:.81rem;color:rgba(255,255,255,.55);}
    .rl-check i{color:var(--gold);font-size:.85rem;flex-shrink:0;}
    .rl-bottom{position:relative;z-index:2;}
    .rl-bottom .already{font-size:.78rem;color:rgba(255,255,255,.35);}
    .rl-bottom .already a{color:rgba(255,255,255,.65);font-weight:600;text-decoration:none;}
    .rl-bottom .already a:hover{color:#fff;}

    /* Right form */
    .reg-right{flex:1;overflow-y:auto;display:flex;align-items:flex-start;justify-content:center;padding:2.5rem 1.5rem;}
    @media(max-width:1023px){.reg-right{align-items:flex-start;padding-top:2rem;}}
    .reg-form{width:100%;max-width:540px;}

    .rf-header{margin-bottom:1.75rem;}
    .rf-header .mobile-brand{display:none;align-items:center;gap:.6rem;margin-bottom:1.25rem;}
    @media(max-width:1023px){.rf-header .mobile-brand{display:flex;}}
    .rf-header .logo-m{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--primary),#4B7CD0);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.88rem;}
    .rf-header h2{font-family:var(--font-disp);font-style:italic;font-size:1.5rem;font-weight:700;color:var(--ink-900);letter-spacing:-.03em;margin-bottom:.25rem;}
    .rf-header p{color:var(--ink-500);font-size:.84rem;}

    .form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:.85rem;}
    @media(max-width:480px){.form-row-2{grid-template-columns:1fr;}}
    .mb-r{margin-bottom:.95rem;}
    .form-label{font-size:.76rem;font-weight:600;color:var(--ink-700);margin-bottom:.35rem;display:block;}
    .field{position:relative;}
    .fi{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:var(--ink-300);font-size:.88rem;pointer-events:none;z-index:2;}
    input.inp,select.inp,textarea.inp{width:100%;border:1.5px solid var(--ink-200);border-radius:var(--r-sm);padding:.6rem .9rem .6rem 2.5rem;font-family:var(--font);font-size:.83rem;transition:border-color .14s,box-shadow .14s;outline:none;background:var(--white);color:var(--ink-900);min-height:41px;}
    input.inp:focus,select.inp:focus,textarea.inp:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(30,64,128,.1);}
    input.inp::placeholder,textarea.inp::placeholder{color:var(--ink-300);}
    select.inp{padding-right:2.5rem;cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23718096' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right .9rem center;}

    .upload-zone{border:2px dashed var(--ink-200);border-radius:var(--r-sm);padding:1.1rem;text-align:center;cursor:pointer;transition:all .18s;background:var(--white);}
    .upload-zone:hover,.upload-zone.drag{border-color:var(--primary);background:var(--primary-lt);}
    .upload-zone input{display:none;}
    .upload-icon{font-size:1.5rem;color:var(--ink-300);margin-bottom:.35rem;transition:color .18s;}
    .upload-zone:hover .upload-icon{color:var(--primary);}
    .upload-title{font-size:.8rem;font-weight:600;color:var(--ink-700);margin-bottom:.15rem;}
    .upload-sub{font-size:.72rem;color:var(--ink-400);}
    .upload-preview{display:none;align-items:center;gap:.6rem;background:var(--green-lt);border-radius:var(--r-sm);padding:.6rem .85rem;margin-top:.65rem;font-size:.78rem;color:var(--green);}
    .upload-preview i{font-size:1rem;flex-shrink:0;}

    .section-sep{display:flex;align-items:center;gap:.75rem;margin:1.4rem 0 1rem;}
    .section-sep::before,.section-sep::after{content:'';flex:1;height:1px;background:var(--ink-200);}
    .section-sep span{font-size:.71rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-400);white-space:nowrap;}

    .social-grid{display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-bottom:1.35rem;}
    .btn-social{display:flex;align-items:center;justify-content:center;gap:.5rem;padding:.56rem;border-radius:var(--r-sm);border:1.5px solid var(--ink-200);background:var(--white);font-family:var(--font);font-size:.82rem;font-weight:600;color:var(--ink-700);text-decoration:none;transition:all .14s;cursor:pointer;}
    .btn-social:hover{border-color:var(--ink-400);background:var(--ink-100);color:var(--ink-900);}

    .terms-check{display:flex;align-items:flex-start;gap:.5rem;font-size:.79rem;color:var(--ink-500);margin-bottom:1.1rem;cursor:pointer;}
    .terms-check input{accent-color:var(--primary);width:15px;height:15px;margin-top:1px;flex-shrink:0;}
    .terms-check a{color:var(--primary);font-weight:600;text-decoration:none;}
    .terms-check a:hover{text-decoration:underline;}

    .btn-submit{width:100%;padding:.68rem;border-radius:var(--r-sm);background:var(--primary);border:none;color:#fff;font-family:var(--font);font-size:.88rem;font-weight:700;cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center;gap:.45rem;min-height:44px;}
    .btn-submit:hover{background:var(--primary-dk);box-shadow:0 4px 14px rgba(30,64,128,.4);transform:translateY(-1px);}
    .btn-submit:active{transform:translateY(0);}

    .sign-in{text-align:center;margin-top:1.2rem;font-size:.82rem;color:var(--ink-500);}
    .sign-in a{color:var(--primary);font-weight:700;text-decoration:none;}
    .sign-in a:hover{text-decoration:underline;}

    .err-box{background:var(--red-lt);border:1px solid rgba(190,18,60,.2);border-radius:var(--r-sm);padding:.65rem .9rem;color:#9F1239;font-size:.8rem;margin-bottom:1.1rem;}
    </style>
</head>
<body>
<div class="reg-wrap">
    {{-- Left panel --}}
    <div class="reg-left">
        <div class="rl-grid"></div>
        <div class="rl-orb"></div>
        <div class="rl-logo">
            <div class="rl-logo-mark"><i class="bi bi-building-check"></i></div>
            <div class="rl-logo-text">
                <div class="t1">E-Services</div>
                <div class="t2">Lebanon Gov Portal</div>
            </div>
        </div>
        <div class="rl-mid">
            <h2>Your gateway to<br><em>government services</em></h2>
            <p>Register once and access all municipal services from your phone — no queues, no paperwork.</p>
            <div class="rl-checklist">
                <div class="rl-check"><i class="bi bi-check-circle-fill"></i> Free account — no credit card needed</div>
                <div class="rl-check"><i class="bi bi-check-circle-fill"></i> Submit requests from any device</div>
                <div class="rl-check"><i class="bi bi-check-circle-fill"></i> Real-time status tracking</div>
                <div class="rl-check"><i class="bi bi-check-circle-fill"></i> Download official documents online</div>
                <div class="rl-check"><i class="bi bi-check-circle-fill"></i> Secure & encrypted connection</div>
            </div>
        </div>
        <div class="rl-bottom">
            <p class="already">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
        </div>
    </div>

    {{-- Right form --}}
    <div class="reg-right">
        <div class="reg-form">
            <div class="rf-header">
                <div class="mobile-brand">
                    <div class="logo-m"><i class="bi bi-building-check"></i></div>
                    <span style="font-family:var(--font-disp);font-style:italic;font-size:.9rem;font-weight:600;color:var(--ink-900)">E-Services</span>
                </div>
                <h2>Create your account</h2>
                <p>It only takes a minute to get started.</p>
            </div>

            @if($errors->any())
            <div class="err-box">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
            @endif

            {{-- Social sign-up --}}
            <div class="social-grid">
                <a href="{{ route('social.redirect', 'google') }}" class="btn-social">
                    <svg width="15" height="15" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Continue with Google
                </a>
                <a href="{{ route('social.redirect', 'github') }}" class="btn-social">
                    <i class="bi bi-github" style="font-size:1rem"></i>
                    Continue with GitHub
                </a>
            </div>

            <div class="section-sep"><span>or register with email</span></div>

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- Name --}}
                <div class="form-row-2 mb-r">
                    <div>
                        <label class="form-label" for="first_name">First Name</label>
                        <div class="field">
                            <i class="bi bi-person fi"></i>
                            <input class="inp" id="first_name" type="text" name="first_name"
                                   value="{{ old('first_name') }}" placeholder="Ahmad" required autofocus>
                        </div>
                    </div>
                    <div>
                        <label class="form-label" for="last_name">Last Name</label>
                        <div class="field">
                            <i class="bi bi-person fi"></i>
                            <input class="inp" id="last_name" type="text" name="last_name"
                                   value="{{ old('last_name') }}" placeholder="Khoury" required>
                        </div>
                    </div>
                </div>

                {{-- Email + Phone --}}
                <div class="form-row-2 mb-r">
                    <div>
                        <label class="form-label" for="email">Email Address</label>
                        <div class="field">
                            <i class="bi bi-envelope fi"></i>
                            <input class="inp" id="email" type="email" name="email"
                                   value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
                        </div>
                    </div>
                    <div>
                        <label class="form-label" for="phone">Phone Number</label>
                        <div class="field">
                            <i class="bi bi-telephone fi"></i>
                            <input class="inp" id="phone" type="tel" name="phone"
                                   value="{{ old('phone') }}" placeholder="+961 7x xxx xxx">
                        </div>
                    </div>
                </div>

                {{-- National ID --}}
                <div class="mb-r">
                    <label class="form-label" for="national_id">National ID Number</label>
                    <div class="field">
                        <i class="bi bi-credit-card-2-front fi"></i>
                        <input class="inp" id="national_id" type="text" name="national_id"
                               value="{{ old('national_id') }}" placeholder="LB-XXXXXXXXX" required>
                    </div>
                    <div style="font-size:.71rem;color:var(--ink-400);margin-top:.28rem">Used for identity verification. Your number is encrypted and protected.</div>
                </div>

                {{-- National ID Document upload --}}
                <div class="mb-r">
                    <label class="form-label">National ID Document <span style="color:var(--ink-400);font-weight:400">(photo or scan)</span></label>
                    <div class="upload-zone" id="idUploadZone" onclick="document.getElementById('national_id_doc').click()">
                        <div class="upload-icon"><i class="bi bi-cloud-upload"></i></div>
                        <div class="upload-title">Click to upload your ID document</div>
                        <div class="upload-sub">JPG, PNG or PDF · Max 5MB</div>
                        <input type="file" id="national_id_doc" name="national_id_document" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                    <div class="upload-preview" id="uploadPreview">
                        <i class="bi bi-file-earmark-check"></i>
                        <span id="uploadName">File selected</span>
                    </div>
                    <div id="ocrStatus" style="display:none;margin-top:.55rem;font-size:.75rem;border-radius:8px;padding:.45rem .6rem;"></div>
                </div>

                {{-- Password --}}
                <div class="form-row-2 mb-r">
                    <div>
                        <label class="form-label" for="password">Password</label>
                        <div class="field">
                            <i class="bi bi-lock fi"></i>
                            <input class="inp" id="password" type="password" name="password"
                                   placeholder="Min. 8 characters" required autocomplete="new-password">
                        </div>
                    </div>
                    <div>
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <div class="field">
                            <i class="bi bi-lock-fill fi"></i>
                            <input class="inp" id="password_confirmation" type="password" name="password_confirmation"
                                   placeholder="Repeat password" required autocomplete="new-password">
                        </div>
                    </div>
                </div>

                {{-- Terms --}}
                <label class="terms-check">
                    <input type="checkbox" name="terms" required>
                    <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a> of the E-Services Lebanon platform.</span>
                </label>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-person-check"></i> Create My Account
                </button>
            </form>

            <p class="sign-in">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
        </div>
    </div>
</div>

<script>
const zone = document.getElementById('idUploadZone');
const input = document.getElementById('national_id_doc');
const preview = document.getElementById('uploadPreview');
const nameEl = document.getElementById('uploadName');
const ocrStatus = document.getElementById('ocrStatus');
const firstNameInput = document.getElementById('first_name');
const lastNameInput = document.getElementById('last_name');
const nationalIdInput = document.getElementById('national_id');
const extractEndpoint = '{{ route('register.id-extract') }}';
const csrfToken = '{{ csrf_token() }}';

let extractionRunId = 0;

function setStatus(message, type = 'info') {
    if (!ocrStatus) return;

    const styles = {
        info: ['#EFF6FF', '#1E4080'],
        success: ['#ECFDF5', '#0D7A4E'],
        warn: ['#FFF7ED', '#9A3412'],
        error: ['#FFF1F2', '#9F1239'],
    };

    const [bg, color] = styles[type] || styles.info;
    ocrStatus.style.display = 'block';
    ocrStatus.style.background = bg;
    ocrStatus.style.color = color;
    ocrStatus.textContent = message;
}

function applyExtractedFields(data) {
    let filledAny = false;

    if (data.national_id && nationalIdInput && !nationalIdInput.value.trim()) {
        nationalIdInput.value = data.national_id;
        filledAny = true;
    }

    if (data.first_name && firstNameInput && !firstNameInput.value.trim()) {
        firstNameInput.value = data.first_name;
        filledAny = true;
    }

    if (data.last_name && lastNameInput && !lastNameInput.value.trim()) {
        lastNameInput.value = data.last_name;
        filledAny = true;
    }

    return filledAny;
}

async function runAzureExtraction(file) {
    if (!file) return;

    const myRunId = ++extractionRunId;
    setStatus('Reading ID data...', 'info');

    const formData = new FormData();
    formData.append('national_id_document', file);

    try {
        const response = await fetch(extractEndpoint, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
            credentials: 'same-origin',
        });

        const payload = await response.json().catch(() => ({}));
        if (myRunId !== extractionRunId) return;

        if (!response.ok) {
            setStatus(payload.message || 'Could not extract fields from this ID file.', 'warn');
            return;
        }

        const filledAny = applyExtractedFields(payload.data || {});

        if (filledAny) {
            setStatus(payload.message || 'ID data extracted and form fields were auto-filled.', 'success');
        } else {
            setStatus(payload.message || 'No clear fields found. Please complete manually.', 'warn');
        }
    } catch (error) {
        setStatus('Extraction request failed. Please check your connection and try again.', 'error');
    }
}

function handleSelectedFile(file) {
    if (!file) return;

    nameEl.textContent = file.name;
    preview.style.display = 'flex';
    runAzureExtraction(file);
}

input?.addEventListener('change', () => {
    handleSelectedFile(input.files[0]);
});

zone?.addEventListener('dragover', (event) => {
    event.preventDefault();
    zone.classList.add('drag');
});

zone?.addEventListener('dragleave', () => {
    zone.classList.remove('drag');
});

zone?.addEventListener('drop', (event) => {
    event.preventDefault();
    zone.classList.remove('drag');

    if (event.dataTransfer.files[0]) {
        input.files = event.dataTransfer.files;
        handleSelectedFile(event.dataTransfer.files[0]);
    }
});
</script>
</body>
</html>
