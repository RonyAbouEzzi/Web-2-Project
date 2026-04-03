@extends('layouts.app')
@section('title','Users')
@section('page-title','User Management')

@push('styles')
<style>
    .admin-filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: .6rem;
        align-items: flex-end;
    }
    .admin-filter-search {
        flex: 1;
        min-width: 160px;
    }
    .admin-filter-role {
        min-width: 140px;
    }
    .admin-plain-btn {
        margin-top: auto;
    }
    .admin-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--es-primary-s);
        color: var(--es-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .78rem;
        font-weight: 700;
        flex-shrink: 0;
        border: 1px solid var(--es-primary-m);
    }
    .admin-avatar.mobile {
        width: 38px;
        height: 38px;
        font-size: .85rem;
    }
    .admin-row-name {
        font-weight: 600;
        font-size: .83rem;
    }
    .admin-row-email {
        font-size: .72rem;
        color: var(--es-muted);
    }
    .admin-role-chip {
        padding: .22rem .65rem;
        border-radius: 20px;
        font-size: .7rem;
        font-weight: 600;
        border: 1px solid transparent;
    }
    .admin-role-admin {
        background: #EEF2FF;
        color: #4338CA;
        border-color: #C7D2FE;
    }
    .admin-role-office_user {
        background: #DCFCE7;
        color: #15803D;
        border-color: #BBF7D0;
    }
    .admin-role-citizen {
        background: var(--es-primary-s);
        color: var(--es-primary);
        border-color: var(--es-primary-m);
    }
    .admin-role-default {
        background: #EEF2F7;
        color: #475569;
        border-color: #DDE5EF;
    }
    .admin-date {
        font-size: .78rem;
        color: var(--es-muted);
    }
    .admin-action-danger {
        background: #FEE2E2;
        border: 1px solid #FECACA;
        color: #DC2626;
    }
    .admin-action-danger:hover {
        background: #FECACA;
        color: #B91C1C;
    }
    .admin-action-success {
        background: #D1FAE5;
        border: 1px solid #A7F3D0;
        color: #059669;
    }
    .admin-action-success:hover {
        background: #A7F3D0;
        color: #047857;
    }
    .admin-empty {
        text-align: center;
        padding: 2rem;
        color: var(--es-muted);
    }
    .admin-mobile-item {
        padding: .9rem 1rem;
        border-bottom: 1px solid var(--es-border-soft);
        display: flex;
        align-items: center;
        gap: .75rem;
    }
    .admin-mobile-name {
        font-weight: 700;
        font-size: .85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .admin-modal .modal-dialog {
        max-width: 440px;
    }
    .admin-modal .modal-content {
        border: 1px solid var(--es-border-soft);
        border-radius: .9rem;
        box-shadow: 0 20px 48px rgba(15, 23, 42, .14);
    }
    .admin-modal .modal-header {
        border: none;
        padding: 1.1rem 1.25rem .45rem;
    }
    .admin-modal .modal-title {
        font-weight: 700;
        color: #566A7F;
    }
    .admin-modal .modal-body {
        padding: .75rem 1.25rem;
    }
    .admin-modal .modal-footer {
        border: none;
        padding: .75rem 1.25rem 1.1rem;
        gap: .5rem;
    }
    .admin-select-cell {
        width: 34px;
        text-align: center;
    }
    .admin-select-input {
        cursor: pointer;
        width: 1rem;
        height: 1rem;
    }
    .admin-bulk-bar {
        display: none;
        align-items: center;
        gap: .5rem;
    }
    .admin-bulk-bar.show {
        display: inline-flex;
    }
    .admin-bulk-count {
        font-size: .76rem;
        color: #566A7F;
        font-weight: 600;
        margin-right: .2rem;
    }
</style>
@endpush

@section('content')
<x-admin.page-header title="Manage Users" :subtitle="$users->total() . ' total'">
    <x-slot:actions>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus"></i> Add Office User
        </button>
    </x-slot:actions>
</x-admin.page-header>

{{-- Filters --}}
<div class="card mb-3 admin-reveal">
    <div class="card-body">
        <form method="GET" class="admin-filter-form" data-admin-busy-target="#adminUsersTableCard">
            <div class="admin-filter-search">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name or email..." value="{{ request('search') }}">
            </div>
            <div class="admin-filter-role">
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
                <a href="{{ route('admin.users') }}" class="btn btn-sm admin-plain-btn" data-admin-busy-target="#adminUsersTableCard">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="card admin-reveal admin-busy-target" id="adminUsersTableCard">
    <div class="card-body" style="padding:0 !important">
        <x-admin.table-toolbar title="User Directory" subtitle="Select multiple users to apply bulk status actions.">
            <x-slot:actions>
                <div id="adminUserBulkBar" class="admin-bulk-bar">
                    <span id="adminUserBulkCount" class="admin-bulk-count">0 selected</span>
                    <button type="button" id="adminUserBulkActivate" class="btn btn-sm admin-action-success">
                        <i class="bi bi-check2-circle"></i> Activate
                    </button>
                    <button type="button" id="adminUserBulkDeactivate" class="btn btn-sm admin-action-danger">
                        <i class="bi bi-x-circle"></i> Deactivate
                    </button>
                    <button type="button" id="adminUserBulkClear" class="btn btn-sm admin-plain-btn">
                        Clear
                    </button>
                </div>
                <div class="admin-density-switch">
                    <button
                        type="button"
                        class="admin-density-btn is-active"
                        data-admin-density-target="#adminUsersTable"
                        data-admin-density="comfortable">Comfort</button>
                    <button
                        type="button"
                        class="admin-density-btn"
                        data-admin-density-target="#adminUsersTable"
                        data-admin-density="compact">Compact</button>
                </div>
            </x-slot:actions>
        </x-admin.table-toolbar>

        <div class="admin-chip-filters" data-admin-filter-group>
            <button type="button" class="admin-chip-filter is-active" data-admin-table-filter data-admin-table-filter-target="#adminUsersTable" data-admin-filter-field="status" data-admin-filter-value="all">All</button>
            <button type="button" class="admin-chip-filter" data-admin-table-filter data-admin-table-filter-target="#adminUsersTable" data-admin-filter-field="status" data-admin-filter-value="active">Active</button>
            <button type="button" class="admin-chip-filter" data-admin-table-filter data-admin-table-filter-target="#adminUsersTable" data-admin-filter-field="status" data-admin-filter-value="inactive">Inactive</button>
        </div>

        <div class="d-none d-md-block admin-table-wrap">
            <table id="adminUsersTable" class="table table-hover admin-table-sticky admin-table-interactive" data-admin-table>
                <thead>
                    <tr>
                        <th class="admin-select-cell">
                            <input type="checkbox" id="adminUsersSelectAll" class="form-check-input admin-select-input" aria-label="Select all users">
                        </th>
                        <th data-sort="1" data-sort-type="text">User</th>
                        <th data-sort="2" data-sort-type="text">Role</th>
                        <th data-sort="3" data-sort-type="text">Status</th>
                        <th data-sort="4" data-sort-type="date">Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr
                        data-user-id="{{ $user->id }}"
                        data-user-active="{{ $user->is_active ? 1 : 0 }}"
                        data-status="{{ $user->is_active ? 'active' : 'inactive' }}"
                        data-role="{{ $user->role }}"
                        data-toggle-url="{{ route('admin.users.toggle', $user) }}">
                        <td class="admin-select-cell">
                            @if($user->id !== auth()->id())
                                <input type="checkbox" class="form-check-input admin-select-input js-user-select" aria-label="Select user {{ $user->name }}">
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.65rem">
                                <div class="admin-avatar">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                                <div>
                                    <div class="admin-row-name">{{ $user->name }}</div>
                                    <div class="admin-row-email">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $roleClass = match($user->role) {
                                    'admin' => 'admin-role-admin',
                                    'office_user' => 'admin-role-office_user',
                                    'citizen' => 'admin-role-citizen',
                                    default => 'admin-role-default',
                                };
                            @endphp
                            <span class="admin-role-chip {{ $roleClass }}">
                                {{ ucfirst(str_replace('_',' ',$user->role)) }}
                            </span>
                        </td>
                        <td><span class="sbadge {{ $user->is_active ? 's-approved' : 's-rejected' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="admin-date" data-sort-value="{{ $user->created_at->timestamp }}">{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="js-user-toggle-form">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $user->is_active ? 'admin-action-danger' : 'admin-action-success' }}">
                                    <i class="bi bi-{{ $user->is_active ? 'person-x' : 'person-check' }}"></i>
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            @else
                            <span class="admin-row-email">Current user</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="admin-empty">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-md-none">
            @forelse($users as $user)
            <div class="admin-mobile-item">
                <div class="admin-avatar mobile">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <div class="admin-mobile-name">{{ $user->name }}</div>
                    <div class="admin-row-email">{{ $user->email }}</div>
                    <div style="margin-top:3px">
                        <span class="admin-row-email" style="font-weight:600">{{ ucfirst(str_replace('_',' ',$user->role)) }}</span>
                    </div>
                </div>
                <span class="sbadge {{ $user->is_active ? 's-approved' : 's-rejected' }}">{{ $user->is_active ? 'Active' : 'Off' }}</span>
            </div>
            @empty
            <div class="admin-empty">No users found.</div>
            @endforelse
        </div>
        @if($users->hasPages())
        <div style="padding:.75rem 1rem;border-top:1px solid var(--es-border-soft)">{{ $users->links() }}</div>
        @endif
    </div>
</div>

{{-- Add Office User Modal --}}
<div class="modal fade admin-modal" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Create Office User</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.office.create') }}" method="POST">
                @csrf
                <div class="modal-body">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm admin-plain-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const url = new URL(window.location.href);
        if (url.searchParams.get('quick') === 'add') {
            const quickModalEl = document.getElementById('addUserModal');
            if (quickModalEl && typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(quickModalEl).show();
                url.searchParams.delete('quick');
                const nextQuery = url.searchParams.toString();
                history.replaceState({}, '', `${url.pathname}${nextQuery ? `?${nextQuery}` : ''}${url.hash}`);
            }
        }

        const table = document.querySelector('.admin-table-sticky');
        if (!table) return;

        const selectAll = document.getElementById('adminUsersSelectAll');
        const rowCheckboxes = Array.from(document.querySelectorAll('.js-user-select'));
        const bulkBar = document.getElementById('adminUserBulkBar');
        const bulkCount = document.getElementById('adminUserBulkCount');
        const bulkActivateBtn = document.getElementById('adminUserBulkActivate');
        const bulkDeactivateBtn = document.getElementById('adminUserBulkDeactivate');
        const bulkClearBtn = document.getElementById('adminUserBulkClear');
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        const selectedRows = () => rowCheckboxes
            .filter((cb) => cb.checked)
            .map((cb) => cb.closest('tr'))
            .filter(Boolean);

        const refreshBulkState = () => {
            const selected = selectedRows();
            const selectedCount = selected.length;
            if (bulkCount) {
                bulkCount.textContent = `${selectedCount} selected`;
            }
            if (bulkBar) {
                bulkBar.classList.toggle('show', selectedCount > 0);
            }
            if (selectAll) {
                const allChecked = rowCheckboxes.length > 0 && rowCheckboxes.every((cb) => cb.checked);
                selectAll.checked = allChecked;
                selectAll.indeterminate = selectedCount > 0 && !allChecked;
            }
        };

        const setBusy = (busy) => {
            [bulkActivateBtn, bulkDeactivateBtn, bulkClearBtn, selectAll, ...rowCheckboxes].forEach((el) => {
                if (!el) return;
                el.disabled = busy;
            });
        };

        const bulkToggle = async (targetActive) => {
            const rows = selectedRows();
            if (!rows.length) return;

            const targets = rows.filter((row) => Number(row.dataset.userActive) !== targetActive);
            if (!targets.length) {
                refreshBulkState();
                return;
            }

            setBusy(true);
            try {
                await Promise.all(targets.map((row) =>
                    fetch(row.dataset.toggleUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin',
                        body: '_method=PATCH'
                    })
                ));
                window.location.reload();
            } catch (error) {
                console.error(error);
                alert('Bulk update failed. Please try again.');
                setBusy(false);
            }
        };

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                rowCheckboxes.forEach((cb) => {
                    cb.checked = selectAll.checked;
                });
                refreshBulkState();
            });
        }

        rowCheckboxes.forEach((cb) => {
            cb.addEventListener('change', refreshBulkState);
        });

        bulkActivateBtn?.addEventListener('click', () => bulkToggle(1));
        bulkDeactivateBtn?.addEventListener('click', () => bulkToggle(0));
        bulkClearBtn?.addEventListener('click', () => {
            rowCheckboxes.forEach((cb) => {
                cb.checked = false;
            });
            refreshBulkState();
        });

        refreshBulkState();
    })();
</script>
@endpush
@endsection

