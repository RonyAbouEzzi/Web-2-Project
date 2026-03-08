@extends('layouts.app')
@section('title','Security Settings')
@section('page-title','Security Settings')

@section('content')
<div style="max-width:960px;display:grid;grid-template-columns:1fr;gap:1.1rem">
    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:.6rem;flex-wrap:wrap">
            <span class="card-title"><i class="bi bi-shield-lock me-2" style="color:var(--primary)"></i>Two-Factor Authentication</span>
            @if($isEnabled)
                <span class="sbadge s-active"><i class="bi bi-check2-circle me-1"></i>Enabled</span>
            @else
                <span class="sbadge s-neutral"><i class="bi bi-slash-circle me-1"></i>Disabled</span>
            @endif
        </div>

        <div class="card-body">
            @if($isEnabled)
                <div style="display:grid;grid-template-columns:1.2fr .8fr;gap:1rem" class="sec-grid">
                    <div>
                        <h3 style="font-size:.93rem;font-weight:700;color:var(--ink-900);margin-bottom:.45rem">2FA is currently active on your account</h3>
                        <p style="font-size:.82rem;color:var(--ink-500);line-height:1.7;margin-bottom:.8rem">
                            To disable it, confirm with a valid 6-digit code from your authenticator app.
                        </p>

                        <form action="{{ route('security.2fa.disable') }}" method="POST" style="max-width:360px">
                            @csrf
                            <label class="form-label" for="disable_otp">Authenticator code</label>
                            <div class="input-icon-wrap" style="margin-bottom:.7rem">
                                <i class="bi bi-key ii"></i>
                                <input type="text" id="disable_otp" name="otp" class="form-control" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="Enter 6-digit code" required>
                            </div>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-shield-x"></i> Disable 2FA
                            </button>
                        </form>
                    </div>

                    <div style="background:var(--ink-50);border:1px solid var(--ink-100);border-radius:12px;padding:.9rem">
                        <div style="font-size:.75rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--ink-400);margin-bottom:.55rem">Tips</div>
                        <ul style="margin:0;padding-left:1.05rem;color:var(--ink-500);font-size:.78rem;line-height:1.7">
                            <li>Keep a backup copy of your authenticator app.</li>
                            <li>Do not share your one-time codes.</li>
                            <li>Use app-based codes, not SMS, for better security.</li>
                        </ul>
                    </div>
                </div>
            @else
                <div style="display:grid;grid-template-columns:1.15fr .85fr;gap:1rem" class="sec-grid">
                    <div>
                        <h3 style="font-size:.93rem;font-weight:700;color:var(--ink-900);margin-bottom:.5rem">Enable 2FA in three steps</h3>
                        <ol style="margin:0 0 .85rem 1.05rem;color:var(--ink-500);font-size:.82rem;line-height:1.75">
                            <li>Scan the QR code with Google Authenticator (or similar app).</li>
                            <li>If needed, enter the manual key shown below.</li>
                            <li>Type the current 6-digit code to confirm activation.</li>
                        </ol>

                        <div style="background:var(--ink-50);border:1px solid var(--ink-100);border-radius:10px;padding:.7rem .8rem;margin-bottom:.8rem">
                            <div style="font-size:.72rem;color:var(--ink-400);margin-bottom:.25rem">Manual setup key</div>
                            <code style="font-size:.81rem;letter-spacing:.05em">{{ $manualKey }}</code>
                        </div>

                        <form action="{{ route('security.2fa.enable') }}" method="POST" style="max-width:360px">
                            @csrf
                            <label class="form-label" for="enable_otp">Verification code</label>
                            <div class="input-icon-wrap" style="margin-bottom:.7rem">
                                <i class="bi bi-key ii"></i>
                                <input type="text" id="enable_otp" name="otp" class="form-control" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="Enter 6-digit code" required>
                            </div>
                            <div style="display:flex;gap:.5rem;flex-wrap:wrap">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-shield-check"></i> Enable 2FA
                                </button>
                            </div>
                        </form>

                        <form action="{{ route('security.2fa.regenerate') }}" method="POST" style="margin-top:.55rem">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-clockwise"></i> Generate new key</button>
                        </form>
                    </div>

                    <div style="background:var(--ink-50);border:1px solid var(--ink-100);border-radius:12px;padding:1rem;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center">
                        <div style="font-size:.74rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--ink-400);margin-bottom:.55rem">Scan QR</div>
                        <div style="background:#fff;border:1px solid var(--ink-200);border-radius:12px;padding:.7rem;display:inline-block;line-height:0">
                            {!! $qrSvg !!}
                        </div>
                        <div style="font-size:.73rem;color:var(--ink-400);margin-top:.55rem">Use any TOTP authenticator app</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media (max-width: 900px) {
    .sec-grid { grid-template-columns: 1fr !important; }
}
</style>
@endpush
