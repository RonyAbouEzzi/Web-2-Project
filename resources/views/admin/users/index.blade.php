@extends('layouts.app')
@section('title','Users')
@section('page-title','User Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3" style="flex-wrap:wrap;gap:.75rem">
    <div>
        <h5 style="font-weight:800;margin:0;font-size:1rem">Manage Users</h5>
        <p style="color:#9ca3af;font-size:.78rem;margin:0">{{ $users->total() }} total</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus"></i> Add Office User
    </button>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" style="display:flex;flex-wrap:wrap;gap:.6rem;align-items:flex-end">
            <div style="flex:1;min-width:160px">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name or email..." value="{{ request('search') }}">
            </div>
            <div style="min-width:140px">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="citizen"     {{ request('role')==='citizen'     ? 'selected':'' }}>Citizen</option>
                    <option value="office_user" {{ request('role')==='office_user' ? 'selected':'' }}>Office User</option>
                    <option value="admin"       {{ request('role')==='admin'       ? 'selected':'' }}>Admin</option>
                </select>
            </div>
            <button class="btn btn-primary btn-sm" style="margin-top:auto"><i class="bi bi-funnel"></i> Filter</button>
            @if(request()->hasAny(['search','role']))
                <a href="{{ route('admin.users') }}" class="btn btn-sm" style="background:#f3f4f6;border:none;color:#374151;margin-top:auto">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0 !important">
        <div class="d-none d-md-block table-wrap">
            <table class="table table-hover">
                <thead>
                    <tr><th>User</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:.65rem">
                                <div style="width:34px;height:34px;border-radius:50%;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;flex-shrink:0">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:.83rem">{{ $user->name }}</div>
                                    <div style="font-size:.72rem;color:#9ca3af">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php $roleColors = ['admin'=>['bg'=>'#fdf4ff','c'=>'#a21caf'],'office_user'=>['bg'=>'#f0fdf4','c'=>'#16a34a'],'citizen'=>['bg'=>'#eff6ff','c'=>'#2563eb']]; $rc = $roleColors[$user->role] ?? ['bg'=>'#f3f4f6','c'=>'#374151']; @endphp
                            <span style="background:{{ $rc['bg'] }};color:{{ $rc['c'] }};padding:.22rem .65rem;border-radius:20px;font-size:.7rem;font-weight:600">
                                {{ ucfirst(str_replace('_',' ',$user->role)) }}
                            </span>
                        </td>
                        <td><span class="sbadge {{ $user->is_active ? 's-approved' : 's-rejected' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td style="font-size:.78rem;color:#9ca3af">{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm" style="background:{{ $user->is_active ? '#fee2e2' : '#d1fae5' }};border:none;color:{{ $user->is_active ? '#dc2626' : '#16a34a' }}">
                                    <i class="bi bi-{{ $user->is_active ? 'person-x' : 'person-check' }}"></i>
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            @else
                            <span style="font-size:.72rem;color:#9ca3af">Current user</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:2rem;color:#9ca3af">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-md-none">
            @forelse($users as $user)
            <div style="padding:.9rem 1rem;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:.75rem">
                <div style="width:38px;height:38px;border-radius:50%;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-weight:700;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $user->name }}</div>
                    <div style="font-size:.72rem;color:#9ca3af">{{ $user->email }}</div>
                    <div style="margin-top:3px">
                        <span style="font-size:.68rem;font-weight:600;color:#6b7280">{{ ucfirst(str_replace('_',' ',$user->role)) }}</span>
                    </div>
                </div>
                <span class="sbadge {{ $user->is_active ? 's-approved' : 's-rejected' }}">{{ $user->is_active ? 'Active' : 'Off' }}</span>
            </div>
            @empty
            <div style="text-align:center;padding:2.5rem;color:#9ca3af">No users found.</div>
            @endforelse
        </div>
        @if($users->hasPages())
        <div style="padding:.75rem 1rem;border-top:1px solid #f3f4f6">{{ $users->links() }}</div>
        @endif
    </div>
</div>

{{-- Add Office User Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,.15)">
            <div class="modal-header" style="border:none;padding:1.25rem 1.25rem .5rem">
                <h6 class="modal-title" style="font-weight:800">Create Office User</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.office.create') }}" method="POST">
                @csrf
                <div class="modal-body" style="padding:.75rem 1.25rem">
                    <div class="mb-3"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" minlength="8" required></div>
                    <div>
                        <label class="form-label">Assign to Office *</label>
                        <select name="office_id" class="form-select" required>
                            <option value="">Select office...</option>
                            @foreach(\App\Models\Office::where('is_active',true)->with('municipality')->get() as $office)
                                <option value="{{ $office->id }}">{{ $office->name }} ({{ $office->municipality->name }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border:none;padding:.75rem 1.25rem 1.25rem;gap:.5rem">
                    <button type="button" class="btn btn-sm" style="background:#f3f4f6;border:none;color:#374151" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
