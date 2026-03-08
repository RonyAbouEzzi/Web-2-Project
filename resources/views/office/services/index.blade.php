@extends('layouts.app')
@section('title','Services')
@section('page-title','Services')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3" style="flex-wrap:wrap;gap:.75rem">
    <div>
        <h5 style="font-weight:800;margin:0;font-size:1rem">Manage Services</h5>
        <p style="color:#9ca3af;font-size:.78rem;margin:0">{{ $services->total() }} services</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSvcModal">
        <i class="bi bi-plus-lg"></i> Add Service
    </button>
</div>

<div class="card">
    <div class="card-body" style="padding:0 !important">
        <div class="d-none d-md-block table-wrap">
            <table class="table table-hover">
                <thead><tr><th>Service</th><th>Category</th><th>Price</th><th>Duration</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($services as $svc)
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:.83rem">{{ $svc->name }}</div>
                            @if($svc->description)<div style="font-size:.72rem;color:#9ca3af">{{ Str::limit($svc->description,50) }}</div>@endif
                        </td>
                        <td style="color:#6b7280;font-size:.8rem">{{ $svc->category->name ?? '—' }}</td>
                        <td style="font-weight:700">${{ number_format($svc->price, 2) }}</td>
                        <td style="color:#6b7280;font-size:.8rem">{{ $svc->estimated_duration_days }} day(s)</td>
                        <td><span class="sbadge {{ $svc->is_active ? 's-approved' : 's-rejected' }}">{{ $svc->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm" style="background:#f3f4f6;border:none"
                                        onclick="editService({{ $svc->id }}, '{{ addslashes($svc->name) }}', {{ $svc->price }}, {{ $svc->estimated_duration_days }}, '{{ $svc->is_active ? 1 : 0 }}', {{ $svc->category_id ?? 'null' }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('office.services.destroy', $svc) }}" method="POST" onsubmit="return confirm('Delete this service?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="background:#fee2e2;border:none;color:#dc2626"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;color:#9ca3af">No services yet. Add your first service.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-md-none">
            @forelse($services as $svc)
            <div style="padding:.9rem 1rem;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:.75rem">
                <div style="width:38px;height:38px;border-radius:10px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0">
                    <i class="bi bi-grid-3x3-gap"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-weight:700;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $svc->name }}</div>
                    <div style="font-size:.73rem;color:#9ca3af">${{ number_format($svc->price,2) }} &middot; {{ $svc->estimated_duration_days }}d</div>
                </div>
                <span class="sbadge {{ $svc->is_active ? 's-approved' : 's-rejected' }}">{{ $svc->is_active ? 'On' : 'Off' }}</span>
            </div>
            @empty
            <div style="text-align:center;padding:2.5rem;color:#9ca3af"><i class="bi bi-grid-3x3-gap" style="font-size:2rem;display:block;margin-bottom:.5rem;color:#d1d5db"></i>No services yet.</div>
            @endforelse
        </div>
        @if($services->hasPages())
        <div style="padding:.75rem 1rem;border-top:1px solid #f3f4f6">{{ $services->links() }}</div>
        @endif
    </div>
</div>

{{-- Add Service Modal --}}
<div class="modal fade" id="addSvcModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width:540px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,.15)">
            <div class="modal-header" style="border:none;padding:1.25rem 1.25rem .5rem">
                <h6 class="modal-title" style="font-weight:800">Add New Service</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('office.services.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="padding:.75rem 1.25rem">
                    <div class="mb-3"><label class="form-label">Service Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem;margin-bottom:.75rem">
                        <div><label class="form-label">Price *</label><input type="number" name="price" class="form-control" min="0" step="0.01" required></div>
                        <div><label class="form-label">Currency</label><select name="currency" class="form-select"><option>USD</option><option>LBP</option><option>EUR</option></select></div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem;margin-bottom:.75rem">
                        <div><label class="form-label">Duration (days) *</label><input type="number" name="estimated_duration_days" class="form-control" min="1" value="1" required></div>
                        <div>
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">None</option>
                                @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Required Documents</label>
                        <div id="docsWrap">
                            <div style="display:flex;gap:.4rem;margin-bottom:.4rem">
                                <input type="text" name="required_documents[]" class="form-control form-control-sm" placeholder="e.g. National ID copy">
                                <button type="button" onclick="addDoc()" class="btn btn-sm" style="background:var(--primary-light);color:var(--primary);border:none;flex-shrink:0"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border:none;padding:.75rem 1.25rem 1.25rem;gap:.5rem">
                    <button type="button" class="btn btn-sm" style="background:#f3f4f6;border:none;color:#374151" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Add Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Service Modal --}}
<div class="modal fade" id="editSvcModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,.15)">
            <div class="modal-header" style="border:none;padding:1.25rem 1.25rem .5rem">
                <h6 class="modal-title" style="font-weight:800">Edit Service</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSvcForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body" style="padding:.75rem 1.25rem">
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" id="esName" class="form-control" required></div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem;margin-bottom:.75rem">
                        <div><label class="form-label">Price *</label><input type="number" name="price" id="esPrice" class="form-control" min="0" step="0.01" required></div>
                        <div><label class="form-label">Duration (days)</label><input type="number" name="estimated_duration_days" id="esDuration" class="form-control" min="1"></div>
                    </div>
                    <div><label class="form-label">Status</label>
                        <select name="is_active" id="esActive" class="form-select">
                            <option value="1">Active</option><option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border:none;padding:.75rem 1.25rem 1.25rem;gap:.5rem">
                    <button type="button" class="btn btn-sm" style="background:#f3f4f6;border:none;color:#374151" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function addDoc() {
    const wrap = document.getElementById('docsWrap');
    const div  = document.createElement('div'); div.style.cssText='display:flex;gap:.4rem;margin-bottom:.4rem';
    div.innerHTML = `<input type="text" name="required_documents[]" class="form-control form-control-sm" placeholder="Document name"><button type="button" onclick="this.parentElement.remove()" class="btn btn-sm" style="background:#fee2e2;border:none;color:#dc2626;flex-shrink:0"><i class="bi bi-dash"></i></button>`;
    wrap.appendChild(div);
}
function editService(id, name, price, duration, active, catId) {
    document.getElementById('editSvcForm').action = `/office/services/${id}`;
    document.getElementById('esName').value     = name;
    document.getElementById('esPrice').value    = price;
    document.getElementById('esDuration').value = duration;
    document.getElementById('esActive').value   = active;
    new bootstrap.Modal(document.getElementById('editSvcModal')).show();
}
</script>
@endpush
@endsection
