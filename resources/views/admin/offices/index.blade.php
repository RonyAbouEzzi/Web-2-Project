@extends('layouts.app')
@section('title','Offices')
@section('page-title','Government Offices')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3" style="flex-wrap:wrap;gap:.75rem">
    <div>
        <h5 style="font-weight:800;margin:0;font-size:1rem">Manage Offices</h5>
        <p style="color:#9ca3af;font-size:.78rem;margin:0">{{ $offices->total() }} total</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addOfficeModal">
        <i class="bi bi-plus-lg"></i> Add Office
    </button>
</div>

<div class="card">
    <div class="card-body" style="padding:0 !important">
        <div class="d-none d-md-block table-wrap">
            <table class="table table-hover">
                <thead>
                    <tr><th>Office Name</th><th>Municipality</th><th>Contact</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($offices as $office)
                    <tr>
                        <td>
                            <div style="font-weight:600">{{ $office->name }}</div>
                            <div style="font-size:.72rem;color:#9ca3af">{{ $office->address }}</div>
                        </td>
                        <td style="color:#6b7280">{{ $office->municipality->name }}</td>
                        <td>
                            <div style="font-size:.78rem">{{ $office->phone ?? '—' }}</div>
                            <div style="font-size:.72rem;color:#9ca3af">{{ $office->email ?? '' }}</div>
                        </td>
                        <td><span class="sbadge {{ $office->is_active ? 's-approved' : 's-rejected' }}">{{ $office->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm" style="background:#f3f4f6;border:none"
                                        onclick="editOffice({{ $office->id }}, '{{ addslashes($office->name) }}', {{ $office->municipality_id }}, '{{ $office->is_active ? 1 : 0 }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.offices.destroy', $office) }}" method="POST" onsubmit="return confirm('Delete this office?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="background:#fee2e2;border:none;color:#dc2626"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:2rem;color:#9ca3af">No offices yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-md-none">
            @forelse($offices as $office)
            <div style="padding:.9rem 1rem;border-bottom:1px solid #f3f4f6">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div style="min-width:0;flex:1">
                        <div style="font-weight:700;font-size:.88rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $office->name }}</div>
                        <div style="font-size:.74rem;color:#9ca3af">{{ $office->municipality->name }}</div>
                        <div style="font-size:.73rem;color:#9ca3af;margin-top:2px">{{ $office->phone ?? 'No phone' }}</div>
                    </div>
                    <span class="sbadge {{ $office->is_active ? 's-approved' : 's-rejected' }}" style="margin-left:.75rem;flex-shrink:0">{{ $office->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:2.5rem;color:#9ca3af"><i class="bi bi-building" style="font-size:2rem;display:block;margin-bottom:.5rem;color:#d1d5db"></i>No offices yet.</div>
            @endforelse
        </div>
        @if($offices->hasPages())
        <div style="padding:.75rem 1rem;border-top:1px solid #f3f4f6">{{ $offices->links() }}</div>
        @endif
    </div>
</div>

{{-- Add Office Modal --}}
<div class="modal fade" id="addOfficeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,.15)">
            <div class="modal-header" style="border:none;padding:1.25rem 1.25rem .5rem">
                <h6 class="modal-title" style="font-weight:800">Add Government Office</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.offices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="padding:.75rem 1.25rem">
                    <div class="mb-3">
                        <label class="form-label">Municipality *</label>
                        <select name="municipality_id" class="form-select" required>
                            <option value="">Select municipality...</option>
                            @foreach(\App\Models\Municipality::where('is_active',true)->get() as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Office Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Address *</label><input type="text" name="address" class="form-control" required></div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem;margin-bottom:.75rem">
                        <div><label class="form-label">Latitude</label><input type="text" name="latitude" class="form-control" placeholder="33.8938"></div>
                        <div><label class="form-label">Longitude</label><input type="text" name="longitude" class="form-control" placeholder="35.5018"></div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem">
                        <div><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
                        <div><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
                    </div>
                </div>
                <div class="modal-footer" style="border:none;padding:.75rem 1.25rem 1.25rem;gap:.5rem">
                    <button type="button" class="btn btn-sm" style="background:#f3f4f6;border:none;color:#374151" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Create Office</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editOfficeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,.15)">
            <div class="modal-header" style="border:none;padding:1.25rem 1.25rem .5rem">
                <h6 class="modal-title" style="font-weight:800">Edit Office</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editOfficeForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body" style="padding:.75rem 1.25rem">
                    <div class="mb-3">
                        <label class="form-label">Municipality *</label>
                        <select name="municipality_id" id="editOfficeMuni" class="form-select" required>
                            @foreach(\App\Models\Municipality::all() as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" id="editOfficeName" class="form-control" required></div>
                    <div><label class="form-label">Status</label>
                        <select name="is_active" id="editOfficeStatus" class="form-select">
                            <option value="1">Active</option><option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border:none;padding:.75rem 1.25rem 1.25rem;gap:.5rem">
                    <button type="button" class="btn btn-sm" style="background:#f3f4f6;border:none;color:#374151" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editOffice(id, name, muniId, active) {
    document.getElementById('editOfficeForm').action = `/admin/offices/${id}`;
    document.getElementById('editOfficeName').value  = name;
    document.getElementById('editOfficeMuni').value  = muniId;
    document.getElementById('editOfficeStatus').value= active;
    new bootstrap.Modal(document.getElementById('editOfficeModal')).show();
}
</script>
@endpush
@endsection
