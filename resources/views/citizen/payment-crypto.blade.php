@extends('layouts.app')
@section('title', 'Crypto Payment')
@section('page-title', 'Complete Crypto Payment')

@section('content')
<div style="max-width:520px;margin:0 auto">
    {{-- Order summary --}}
    <div class="card mb-3">
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:.85rem">
                <div style="width:44px;height:44px;border-radius:12px;background:#fef3c7;color:#f59e0b;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">
                    <i class="bi bi-currency-bitcoin"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:.88rem;font-weight:700">{{ $serviceRequest->service->name }}</div>
                    <div style="font-size:.75rem;color:#9ca3af">{{ $serviceRequest->reference_number }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0">
                    <div style="font-size:1.1rem;font-weight:800;color:#f59e0b">{{ $crypto_amount }} {{ $crypto_currency }}</div>
                    <div style="font-size:.7rem;color:#9ca3af">${{ number_format($serviceRequest->service->price, 2) }} USD</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Wallet address --}}
    <div class="card mb-3">
        <div class="card-header"><span class="card-title">Send {{ $crypto_currency }} to this address</span></div>
        <div class="card-body text-center">
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:1rem;margin-bottom:1rem;word-break:break-all;font-family:monospace;font-size:.85rem;letter-spacing:.5px">
                {{ $wallet_address }}
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyAddress()">
                <i class="bi bi-clipboard"></i> Copy Address
            </button>

            <div style="margin-top:1.2rem;padding:1rem;background:#fef3c7;border-radius:8px;font-size:.8rem;color:#92400e;text-align:left">
                <strong><i class="bi bi-exclamation-triangle-fill"></i> Important:</strong>
                <ul style="margin:0.5rem 0 0;padding-left:1.2rem">
                    <li>Send exactly <strong>{{ $crypto_amount }} {{ $crypto_currency }}</strong></li>
                    <li>Wait for network confirmation before submitting</li>
                    <li>Only send {{ $crypto_currency }} to this address</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Confirm payment --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Confirm Payment</span></div>
        <div class="card-body">
            <form action="{{ route('citizen.payment.crypto.confirm', $serviceRequest) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Transaction Hash</label>
                    <input type="text" name="tx_hash" class="form-control" placeholder="Enter your transaction hash..." required minlength="10">
                    <div class="form-text">Paste the transaction hash from your wallet after sending the payment.</div>
                </div>

                @if($errors->has('payment'))
                    <div class="alert alert-danger mb-3">{{ $errors->first('payment') }}</div>
                @endif

                <button type="submit" class="btn btn-warning btn-block" style="padding:.7rem;color:#fff">
                    <i class="bi bi-check-circle-fill"></i> Confirm Payment
                </button>
            </form>
        </div>
    </div>

    <div style="text-align:center;margin-top:1rem">
        <a href="{{ route('citizen.payment', $serviceRequest) }}" class="text-muted" style="font-size:.8rem">
            <i class="bi bi-arrow-left"></i> Back to payment methods
        </a>
    </div>
</div>

@push('scripts')
<script>
function copyAddress() {
    navigator.clipboard.writeText('{{ $wallet_address }}').then(() => {
        var btn = event.target.closest('button');
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2"></i> Copied!';
        setTimeout(() => btn.innerHTML = orig, 2000);
    });
}
</script>
@endpush
@endsection
