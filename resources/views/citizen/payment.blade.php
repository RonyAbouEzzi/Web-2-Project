@extends('layouts.app')
@section('title', 'Payment')
@section('page-title', 'Complete Payment')

@section('content')
<div style="max-width:520px;margin:0 auto">
    {{-- Order summary --}}
    <div class="card mb-3">
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:.85rem">
                <div style="width:44px;height:44px;border-radius:12px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">
                    <i class="bi bi-receipt"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:.88rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $serviceRequest->service->name }}</div>
                    <div style="font-size:.75rem;color:#9ca3af">{{ $serviceRequest->office->name }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0">
                    <div style="font-size:1.2rem;font-weight:800;color:var(--primary)">${{ number_format($serviceRequest->service->price, 2) }}</div>
                    <div style="font-size:.7rem;color:#9ca3af">{{ $serviceRequest->service->currency }}</div>
                </div>
            </div>
            <div style="border-top:1px solid #f3f4f6;margin-top:.9rem;padding-top:.75rem;display:flex;justify-content:space-between;align-items:center">
                <span style="font-size:.75rem;color:#9ca3af">Reference</span>
                <code>{{ $serviceRequest->reference_number }}</code>
            </div>
        </div>
    </div>

    {{-- Payment form --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Select Payment Method</span></div>
        <div class="card-body">
            <form action="{{ route('citizen.payment.process', $serviceRequest) }}" method="POST" id="payForm">
                @csrf

                {{-- Method selector --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem;margin-bottom:1.25rem">
                    <label style="cursor:pointer">
                        <input type="radio" name="payment_method" value="card" class="pm-radio d-none" required>
                        <div class="pm-option" data-method="card" style="border:1.5px solid #e2e8f0;border-radius:10px;padding:.9rem;text-align:center;transition:all .15s">
                            <i class="bi bi-credit-card-2-front" style="font-size:1.5rem;color:var(--primary);display:block;margin-bottom:.35rem"></i>
                            <div style="font-size:.78rem;font-weight:700;color:#374151">Card</div>
                            <div style="font-size:.66rem;color:#9ca3af">Visa / MC</div>
                        </div>
                    </label>
                    <label style="cursor:pointer">
                        <input type="radio" name="payment_method" value="crypto" class="pm-radio d-none">
                        <div class="pm-option" data-method="crypto" style="border:1.5px solid #e2e8f0;border-radius:10px;padding:.9rem;text-align:center;transition:all .15s">
                            <i class="bi bi-currency-bitcoin" style="font-size:1.5rem;color:#f59e0b;display:block;margin-bottom:.35rem"></i>
                            <div style="font-size:.78rem;font-weight:700;color:#374151">Crypto</div>
                            <div style="font-size:.66rem;color:#9ca3af">BTC / ETH / USDT</div>
                        </div>
                    </label>
                </div>

                {{-- Card fields --}}
                <div id="cardFields" style="display:none">
                    <div class="mb-3">
                        <label class="form-label">Card Number</label>
                        <input type="text" id="cardNum" class="form-control" placeholder="1234  5678  9012  3456" maxlength="19" inputmode="numeric">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem;margin-bottom:.75rem">
                        <div>
                            <label class="form-label">Expiry</label>
                            <input type="text" class="form-control" placeholder="MM / YY" maxlength="7" inputmode="numeric">
                        </div>
                        <div>
                            <label class="form-label">CVV</label>
                            <input type="text" class="form-control" placeholder="123" maxlength="4" inputmode="numeric">
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Cardholder Name</label>
                        <input type="text" class="form-control" placeholder="{{ auth()->user()->name }}" autocomplete="cc-name">
                    </div>
                </div>

                {{-- Crypto fields --}}
                <div id="cryptoFields" style="display:none">
                    <div class="mb-3">
                        <label class="form-label">Select Cryptocurrency</label>
                        <select name="crypto_currency" class="form-select">
                            <option value="BTC">Bitcoin (BTC)</option>
                            <option value="ETH">Ethereum (ETH)</option>
                            <option value="USDT">Tether USDT</option>
                        </select>
                    </div>
                    <div style="background:#eff6ff;border-radius:8px;padding:.75rem;font-size:.79rem;color:#1e40af;display:flex;align-items:flex-start;gap:.5rem">
                        <i class="bi bi-info-circle" style="flex-shrink:0;margin-top:1px"></i>
                        <span>You will be redirected to complete the crypto payment after confirming.</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-3" style="padding:.7rem">
                    <i class="bi bi-lock-fill"></i> Pay Securely
                </button>
            </form>

            <div style="text-align:center;margin-top:.9rem;font-size:.72rem;color:#9ca3af;display:flex;align-items:center;justify-content:center;gap:.3rem">
                <i class="bi bi-shield-check" style="color:#16a34a"></i>
                256-bit SSL encryption · Your data is safe
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.pm-option:hover { border-color: var(--primary) !important; background: var(--primary-light); }
.pm-radio:checked + .pm-option {
    border-color: var(--primary) !important;
    background: var(--primary-light);
    box-shadow: 0 0 0 3px rgba(0,82,204,.1);
}
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('.pm-radio').forEach(r => {
    r.addEventListener('change', () => {
        document.getElementById('cardFields').style.display   = 'none';
        document.getElementById('cryptoFields').style.display = 'none';
        if (r.value === 'card')   document.getElementById('cardFields').style.display   = 'block';
        if (r.value === 'crypto') document.getElementById('cryptoFields').style.display = 'block';
    });
});

// Card number formatting
document.getElementById('cardNum')?.addEventListener('input', e => {
    let v = e.target.value.replace(/\D/g,'').slice(0,16);
    e.target.value = v.match(/.{1,4}/g)?.join('  ') ?? v;
});
</script>
@endpush
@endsection
