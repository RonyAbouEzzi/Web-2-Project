@extends('layouts.app')
@section('title', 'Crypto Payment')
@section('page-title', 'Complete Crypto Payment')

@section('content')
<div class="citizen-crypto-shell citizen-reveal" data-citizen-reveal>
    <div class="card mb-3">
        <div class="card-body">
            <div class="citizen-crypto-summary-row">
                <div class="citizen-crypto-summary-icon">
                    <i class="bi bi-currency-bitcoin"></i>
                </div>
                <div class="citizen-crypto-summary-main">
                    <div class="citizen-crypto-summary-title">{{ $serviceRequest->service->name }}</div>
                    <div class="citizen-crypto-summary-sub">{{ $serviceRequest->reference_number }}</div>
                </div>
                <div class="citizen-crypto-summary-amount">
                    <div class="citizen-crypto-main-amount">{{ $crypto_amount }} {{ $crypto_currency }}</div>
                    <div class="citizen-crypto-usd-amount">${{ number_format($serviceRequest->service->price, 2) }} USD</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <span class="card-title">Send {{ $crypto_currency }} to this address</span>
        </div>
        <div class="card-body text-center">
            <div class="citizen-crypto-wallet-box">{{ $wallet_address }}</div>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyAddress(this)">
                <i class="bi bi-clipboard me-1"></i> Copy Address
            </button>

            <div class="citizen-crypto-warning">
                <strong><i class="bi bi-exclamation-triangle-fill me-1"></i>Important:</strong>
                <ul>
                    <li>Send exactly <strong>{{ $crypto_amount }} {{ $crypto_currency }}</strong></li>
                    <li>Wait for network confirmation before submitting</li>
                    <li>Only send {{ $crypto_currency }} to this address</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Confirm Payment</span>
        </div>
        <div class="card-body">
            <form action="{{ route('citizen.payment.crypto.confirm', $serviceRequest) }}" method="POST" id="cryptoConfirmForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Transaction Hash</label>
                    <input type="text" name="tx_hash" class="form-control" placeholder="Enter your transaction hash..." required minlength="10">
                    <div class="form-text">Paste the transaction hash from your wallet after sending the payment.</div>
                </div>

                @if($errors->has('payment'))
                    <div class="alert alert-danger mb-3">{{ $errors->first('payment') }}</div>
                @endif

                <button type="submit" class="btn btn-warning w-100 text-white citizen-crypto-submit-btn" id="cryptoConfirmBtn">
                    <i class="bi bi-check-circle-fill me-1"></i> Confirm Payment
                </button>
            </form>
        </div>
    </div>

    <div class="citizen-crypto-back">
        <a href="{{ route('citizen.payment', $serviceRequest) }}" class="text-muted">
            <i class="bi bi-arrow-left me-1"></i> Back to payment methods
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
body.es-role-citizen .citizen-crypto-shell {
    max-width: 520px;
    margin: 0 auto;
}

body.es-role-citizen .citizen-crypto-summary-row {
    display: flex;
    align-items: center;
    gap: .85rem;
}

body.es-role-citizen .citizen-crypto-summary-icon {
    width: 2.75rem;
    height: 2.75rem;
    border-radius: .72rem;
    background: #FEF3C7;
    border: 1px solid #FDE68A;
    color: #D97706;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
    flex-shrink: 0;
}

body.es-role-citizen .citizen-crypto-summary-main {
    flex: 1;
    min-width: 0;
}

body.es-role-citizen .citizen-crypto-summary-title {
    font-size: .88rem;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

body.es-role-citizen .citizen-crypto-summary-sub {
    font-size: .75rem;
    color: #64748B;
}

body.es-role-citizen .citizen-crypto-summary-amount {
    text-align: right;
    flex-shrink: 0;
}

body.es-role-citizen .citizen-crypto-main-amount {
    font-size: 1.1rem;
    font-weight: 800;
    color: #D97706;
    line-height: 1.1;
}

body.es-role-citizen .citizen-crypto-usd-amount {
    font-size: .7rem;
    color: #94A3B8;
}

body.es-role-citizen .citizen-crypto-wallet-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: .58rem;
    padding: 1rem;
    margin-bottom: 1rem;
    word-break: break-all;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    font-size: .84rem;
    letter-spacing: .02em;
    color: #0F172A;
}

body.es-role-citizen .citizen-crypto-warning {
    margin-top: 1.1rem;
    padding: .9rem;
    background: #FEF3C7;
    border: 1px solid #FDE68A;
    border-radius: .6rem;
    font-size: .8rem;
    color: #92400E;
    text-align: left;
}

body.es-role-citizen .citizen-crypto-warning ul {
    margin: .45rem 0 0;
    padding-left: 1.2rem;
}

body.es-role-citizen .citizen-crypto-warning li + li {
    margin-top: .18rem;
}

body.es-role-citizen .citizen-crypto-submit-btn {
    padding: .7rem;
}

body.es-role-citizen .citizen-crypto-back {
    text-align: center;
    margin-top: 1rem;
}

body.es-role-citizen .citizen-crypto-back a {
    font-size: .8rem;
}
</style>
@endpush

@push('scripts')
<script>
const cryptoConfirmForm = document.getElementById('cryptoConfirmForm');
const cryptoConfirmBtn = document.getElementById('cryptoConfirmBtn');

cryptoConfirmForm?.addEventListener('submit', () => {
    cryptoConfirmBtn.disabled = true;
    cryptoConfirmBtn.setAttribute('aria-busy', 'true');
    cryptoConfirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Confirming...';
});

function copyAddress(button) {
    navigator.clipboard.writeText(@json($wallet_address)).then(() => {
        const original = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check2 me-1"></i>Copied!';
        setTimeout(() => {
            button.innerHTML = original;
        }, 1800);
    });
}
</script>
@endpush
