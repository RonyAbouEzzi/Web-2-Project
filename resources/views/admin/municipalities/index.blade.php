@extends('layouts.app')
@section('title','Municipalities')
@section('page-title','Municipalities')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3" style="flex-wrap:wrap;gap:.75rem">
    <div>
        <h5 style="font-weight:800;margin:0;font-size:1rem">Manage Municipalities</h5>
        <p style="color:#9ca3af;font-size:.78rem;margin:0">{{ $municipalities->total() }} total</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-lg"></i> Add Municipality
    </button>
</div>

<div class="card">
    <div class="card-body" style="padding:0 !important">
        {{-- Desktop --}}
        <div class="d-none d-md-block table-wrap">
            <table class="table table-hover">
                <thead>
                    <tr><th>Name</th><th>Region</th><th>Country</th><th>Offices</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($municipalities as $m)
                    <tr>
                        <td style="font-weight:600">{{ $m->name }}</td>
                        <td style="color:#6b7280">{{ $m->region ?? '—' }}</td>
                        <td style="color:#6b7280">{{ $m->country }}</td>
                        <td><span style="background:#eff6ff;color:#2563eb;padding:.2rem .6rem;border-radius:20px;font-size:.72rem;font-weight:600">{{ $m->offices_count }}</span></td>
                        <td><span class="sbadge {{ $m->is_active ? 's-approved' : 's-rejected' }}">{{ $m->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm" style="background:#f3f4f6;border:none;color:#374151"
                                        onclick="editMunicipality({{ $m->id }}, '{{ $m->name }}', '{{ $m->region }}', '{{ $m->is_active ? 1 : 0 }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.municipalities.destroy', $m) }}" method="POST" onsubmit="return confirm('Delete {{ $m->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="background:#fee2e2;border:none;color:#dc2626"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;color:#9ca3af">No municipalities yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Mobile --}}
        <div class="d-md-none">
            @forelse($municipalities as $m)
            <div style="padding:.9rem 1rem;border-bottom:1px solid #f3f4f6">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div>
                        <div style="font-weight:700;font-size:.88rem">{{ $m->name }}</div>
                        <div style="font-size:.75rem;color:#9ca3af">{{ $m->region ?? 'No region' }} &middot; {{ $m->offices_count }} offices</div>
                    </div>
                    <div style="display:flex;gap:.4rem;align-items:center">
                        <span class="sbadge {{ $m->is_active ? 's-approved' : 's-rejected' }}">{{ $m->is_active ? 'Active' : 'Inactive' }}</span>
                        <button class="btn btn-sm" style="background:#f3f4f6;border:none;padding:.3rem .5rem"
                                onclick="editMunicipality({{ $m->id }}, '{{ $m->name }}', '{{ $m->region }}', '{{ $m->is_active ? 1 : 0 }}')">
                            <i class="bi bi-pencil" style="font-size:.8rem"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:2.5rem;color:#9ca3af"><i class="bi bi-geo-alt" style="font-size:2rem;display:block;margin-bottom:.5rem;color:#d1d5db"></i>No municipalities yet.</div>
            @endforelse
        </div>
        @if($municipalities->hasPages())
        <div style="padding:.75rem 1rem;border-top:1px solid #f3f4f6">{{ $municipalities->links() }}</div>
        @endif
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,.15)">
            <div class="modal-header" style="border:none;padding:1.25rem 1.25rem .5rem">
                <h6 class="modal-title" style="font-weight:800">Add Municipality</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.municipalities.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="padding:.75rem 1.25rem">
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Region</label><input type="text" name="region" class="form-control" placeholder="e.g. Mount Lebanon"></div>
                    <div><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="Lebanon"></div>
                </div>
                <div class="modal-footer" style="border:none;padding:.75rem 1.25rem 1.25rem;gap:.5rem">
                    <button type="button" class="btn btn-sm" style="background:#f3f4f6;border:none;color:#374151" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Add Municipality</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,.15)">
            <div class="modal-header" style="border:none;padding:1.25rem 1.25rem .5rem">
                <h6 class="modal-title" style="font-weight:800">Edit Municipality</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body" style="padding:.75rem 1.25rem">
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" id="editName" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Region</label><input type="text" name="region" id="editRegion" class="form-control"></div>
                    <div><label class="form-label">Status</label>
                        <select name="is_active" class="form-select" id="editStatus">
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
function editMunicipality(id, name, region, active) {
    document.getElementById('editForm').action = `/admin/municipalities/${id}`;
    document.getElementById('editName').value   = name;
    document.getElementById('editRegion').value = region || '';
    document.getElementById('editStatus').value = active;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endpush
@endsection
