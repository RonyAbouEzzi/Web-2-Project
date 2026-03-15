@extends('layouts.app')
@section('title', $service->name)
@section('page-title', $service->name)

@section('content')

<div style="max-width:680px">

    {{-- Service header card --}}
    <div class="card mb-3">
        <div style="background:linear-gradient(135deg,#0038a8,#0070f3);padding:1.4rem 1.5rem;border-radius:12px 12px 0 0;display:flex;align-items:flex-start;gap:1rem">
            <div style="width:50px;height:50px;border-radius:13px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;font-size:1.25rem;color:#fff;flex-shrink:0">
                <i class="bi bi-file-earmark-check"></i>
            </div>
            <div style="flex:1">
                <h4 style="color:#fff;font-weight:800;margin:0 0 .3rem;font-size:1.05rem">{{ $service->name }}</h4>
                <div style="color:rgba(255,255,255,.7);font-size:.78rem">{{ $service->office->name }} &middot; {{ $service->office->municipality->name }}</div>
            </div>
            <div style="text-align:right;flex-shrink:0">
                <div style="color:#fff;font-size:1.4rem;font-weight:800">${{ number_format($service->price, 2) }}</div>
                <div style="color:rgba(255,255,255,.6);font-size:.72rem">{{ $service->currency }}</div>
            </div>
        </div>
        <div class="card-body">
            @if($service->description)
            <p style="font-size:.85rem;color:#374151;line-height:1.6;margin-bottom:1rem">{{ $service->description }}</p>
            @endif
            <div style="display:flex;flex-wrap:wrap;gap:.65rem">
                <div style="display:flex;align-items:center;gap:.4rem;background:#f8fafc;border-radius:8px;padding:.45rem .75rem">
                    <i class="bi bi-clock" style="color:var(--primary);font-size:.85rem"></i>
                    <span style="font-size:.78rem;font-weight:600;color:#374151">~{{ $service->estimated_duration_days }} business day(s)</span>
                </div>
                @if($service->category)
                <div style="display:flex;align-items:center;gap:.4rem;background:#f8fafc;border-radius:8px;padding:.45rem .75rem">
                    <i class="bi bi-tag" style="color:var(--primary);font-size:.85rem"></i>
                    <span style="font-size:.78rem;font-weight:600;color:#374151">{{ $service->category->name }}</span>
                </div>
                @endif
            </div>

            @if(isset($convertedPrices) && count($convertedPrices) > 0)
            <div style="margin-top:1rem;border-top:1px solid #f3f4f6;padding-top:.85rem">
                <div style="font-size:.72rem;color:#9ca3af;margin-bottom:.5rem;display:flex;align-items:center;gap:.3rem">
                    <i class="bi bi-currency-exchange"></i> Also equivalent to:
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem">
                    @foreach($convertedPrices as $currency => $amount)
                    <span style="background:#f0fdf4;color:#166534;font-size:.78rem;font-weight:600;padding:.35rem .7rem;border-radius:6px">
                        @if($currency === 'USD')$@elseif($currency === 'EUR')&euro;@elseif($currency === 'LBP')LBP @endif{{ number_format($amount, $currency === 'LBP' ? 0 : 2) }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Required Documents info --}}
    @if($service->required_documents && count($service->required_documents) > 0)
    <div class="card mb-3">
        <div class="card-header"><span class="card-title"><i class="bi bi-paperclip me-2" style="color:var(--primary)"></i>Required Documents</span></div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:.5rem">
                @foreach($service->required_documents as $doc)
                <div style="display:flex;align-items:center;gap:.6rem;background:#f8fafc;border-radius:8px;padding:.6rem .85rem">
                    <i class="bi bi-check-circle-fill" style="color:#16a34a;font-size:.85rem;flex-shrink:0"></i>
                    <span style="font-size:.82rem;color:#374151">{{ $doc }}</span>
                </div>
                @endforeach
            </div>
            <p style="font-size:.75rem;color:#9ca3af;margin:.75rem 0 0;display:flex;align-items:center;gap:.3rem">
                <i class="bi bi-info-circle"></i> Prepare these documents before submitting your request.
            </p>
        </div>
    </div>
    @endif

    {{-- Submission form --}}
    <div class="card">
        <div class="card-header"><span class="card-title"><i class="bi bi-send me-2" style="color:var(--primary)"></i>Submit Request</span></div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('citizen.requests.submit', $service) }}" method="POST" enctype="multipart/form-data" id="submitForm">
                @csrf

                {{-- Notes --}}
                <div class="mb-3">
                    <label class="form-label">Additional Notes <span style="color:#9ca3af;font-weight:400">(optional)</span></label>
                    <textarea name="notes" class="form-control" rows="3"
                              placeholder="Any additional information or special requirements...">{{ old('notes') }}</textarea>
                </div>

                {{-- Document uploads --}}
                @if($service->required_documents && count($service->required_documents) > 0)
                <div class="mb-3">
                    <label class="form-label">Upload Required Documents <span style="color:#e53935">*</span></label>
                    <p style="font-size:.75rem;color:#9ca3af;margin-bottom:.6rem">Upload clear scans or photos. Accepted: PDF, JPG, PNG (max 5MB each)</p>

                    @foreach($service->required_documents as $i => $docName)
                    <div style="margin-bottom:.65rem">
                        <label style="font-size:.78rem;font-weight:600;color:#374151;margin-bottom:.3rem;display:block">
                            {{ $docName }} <span style="color:#e53935">*</span>
                        </label>
                        <div class="upload-zone-sm" id="zone_{{ $i }}" onclick="document.getElementById('doc_{{ $i }}').click()"
                             style="border:2px dashed #e2e8f0;border-radius:8px;padding:.75rem;text-align:center;cursor:pointer;transition:all .15s;background:#f9fafb">
                            <i class="bi bi-cloud-upload" style="color:#9ca3af;font-size:1.25rem;display:block;margin-bottom:.25rem"></i>
                            <div id="zone_label_{{ $i }}" style="font-size:.75rem;color:#9ca3af">Tap to upload {{ $docName }}</div>
                            <input type="file" id="doc_{{ $i }}" name="documents[]" accept=".pdf,.jpg,.jpeg,.png" required
                                   onchange="handleUpload({{ $i }}, this)" style="display:none">
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                {{-- Free-form document upload --}}
                <div class="mb-3">
                    <label class="form-label">Supporting Documents <span style="color:#9ca3af;font-weight:400">(optional)</span></label>
                    <div class="upload-zone-sm" onclick="document.getElementById('docsFree').click()"
                         style="border:2px dashed #e2e8f0;border-radius:8px;padding:1rem;text-align:center;cursor:pointer;transition:all .15s;background:#f9fafb">
                        <i class="bi bi-cloud-upload" style="color:#9ca3af;font-size:1.5rem;display:block;margin-bottom:.3rem"></i>
                        <div style="font-size:.78rem;color:#9ca3af">Tap to upload documents (PDF, JPG, PNG)</div>
                        <input type="file" id="docsFree" name="documents[]" accept=".pdf,.jpg,.jpeg,.png" multiple style="display:none">
                    </div>
                </div>
                @endif

                {{-- Summary --}}
                <div style="background:var(--primary-light);border-radius:10px;padding:1rem;margin-bottom:1.1rem">
                    <div style="font-size:.78rem;font-weight:700;color:var(--primary);margin-bottom:.6rem">REQUEST SUMMARY</div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:.35rem;font-size:.82rem">
                        <span style="color:#374151">Service fee</span>
                        <span style="font-weight:700;color:#111827">${{ number_format($service->price, 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:.82rem">
                        <span style="color:#374151">Processing time</span>
                        <span style="font-weight:600;color:#111827">~{{ $service->estimated_duration_days }} day(s)</span>
                    </div>
                    <div style="border-top:1px solid rgba(0,82,204,.15);margin-top:.6rem;padding-top:.6rem;font-size:.75rem;color:rgba(0,82,204,.7);display:flex;align-items:center;gap:.3rem">
                        <i class="bi bi-info-circle"></i>
                        Payment is collected after your request is approved.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding:.65rem" id="submitBtn">
                    <i class="bi bi-send-fill"></i> Submit Request
                </button>
                <a href="{{ route('citizen.offices.show', $service->office) }}" class="btn btn-block mt-2"
                   style="background:#f3f4f6;border:none;color:#374151;padding:.6rem">
                    <i class="bi bi-arrow-left"></i> Back to Office
                </a>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('submitForm').addEventListener('submit', function() {
    var btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Submitting...';
});

function handleUpload(idx, input) {
    const zone  = document.getElementById('zone_' + idx);
    const label = document.getElementById('zone_label_' + idx);
    if (input.files.length > 0) {
        zone.style.borderColor  = '#0052cc';
        zone.style.background   = '#eff4ff';
        zone.querySelector('i').className    = 'bi bi-file-earmark-check';
        zone.querySelector('i').style.color  = '#0052cc';
        label.textContent  = input.files[0].name;
        label.style.color  = '#0052cc';
        label.style.fontWeight = '600';
    }
}
// Hover effects
document.querySelectorAll('.upload-zone-sm').forEach(z => {
    z.addEventListener('mouseover', () => { if (!z.style.borderColor || z.style.borderColor === 'rgb(226, 232, 240)') z.style.borderColor = '#0052cc'; z.style.background = '#eff4ff'; });
    z.addEventListener('mouseout',  () => { if (z.style.borderColor === '#0052cc' && z.querySelector('i').className.includes('upload')) { z.style.borderColor = '#e2e8f0'; z.style.background = '#f9fafb'; } });
});
</script>
@endpush
@endsection
