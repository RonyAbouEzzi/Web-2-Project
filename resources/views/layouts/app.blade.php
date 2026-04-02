<!DOCTYPE html>
<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="{{ asset('sneat') . '/' }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Services Management')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.css" rel="stylesheet">
    <link href="{{ asset('sneat/css/core.css') }}" rel="stylesheet">
    <link href="{{ asset('sneat/css/demo.css') }}" rel="stylesheet">

    <script src="{{ asset('sneat/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('sneat/js/config.js') }}"></script>

    <style>
        /* ── Font override: Inter across the entire app ── */
        :root { --font-family-base: 'Inter', system-ui, sans-serif; }
        body, .menu-inner, .navbar, .dropdown-menu, .form-control,
        .btn, .badge, .card, .table { font-family: 'Inter', system-ui, sans-serif !important; }

        /* ── NProgress teal theme ── */
        #nprogress .bar { background: #0d9488; height: 3px; }
        #nprogress .peg  { box-shadow: 0 0 10px #0d9488, 0 0 5px #0d9488; }

        /* ── Utility: text sizes ── */
        .text-2xs  { font-size: .68rem; }
        .text-xs   { font-size: .76rem; }
        .text-sm   { font-size: .82rem; }
        .text-md   { font-size: .88rem; }
        .fw-800    { font-weight: 800; }

        /* ── Stat card helpers ── */
        .stat-label { font-size: .76rem; }
        .stat-value { font-size: 1.5rem; font-weight: 800; }
        .stat-sub   { font-size: .72rem; }

        /* ── Office card helpers ── */
        .office-card-header {
            background: linear-gradient(135deg, #0f766e, #0d9488);
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: .85rem;
        }
        .office-card-logo {
            width: 44px; height: 44px; border-radius: 10px;
            object-fit: cover; border: 2px solid rgba(255,255,255,.3);
        }
        .office-card-logo-placeholder {
            width: 44px; height: 44px; border-radius: 10px;
            background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: #fff; flex-shrink: 0;
        }
        .office-card-name {
            color: #fff; font-weight: 800; font-size: .88rem;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .office-card-municipality { color: rgba(255,255,255,.65); font-size: .73rem; }
        .office-card-body { padding: 1rem; }
        .office-card-meta {
            display: flex; align-items: flex-start; gap: .5rem; margin-bottom: .5rem;
        }
        .office-card-meta-icon { color: #9ca3af; font-size: .85rem; margin-top: 1px; flex-shrink: 0; }
        .office-card-meta-text { font-size: .78rem; color: #6b7280; line-height: 1.4; }
        .office-card-footer {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: .75rem; padding-top: .75rem; border-top: 1px solid #f3f4f6;
        }
        .office-card-wrap {
            background: #fff; border-radius: 14px; border: 1px solid #e5eaf0;
            box-shadow: 0 1px 3px rgba(0,0,0,.06); overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .office-card-wrap:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,.1);
        }

        /* ── Notification / user dropdown text sizes ── */
        .notif-msg  { font-size: .78rem; }
        .notif-time { font-size: .68rem; }
        .user-name  { font-size: .84rem; }
        .user-email { font-size: .7rem; }
        .page-title-text { font-size: .95rem; font-weight: 700; color: #292524; }

        /* ── Skeleton shimmer ── */
        @keyframes skeleton-shimmer {
            0%   { background-position: -400px 0; }
            100% { background-position:  400px 0; }
        }
        body.page-loading table tbody tr td,
        body.page-loading .skeleton-target {
            color: transparent !important;
            border-color: transparent !important;
            background: linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%);
            background-size: 800px 100%;
            animation: skeleton-shimmer 1.4s infinite linear;
            border-radius: 4px;
            pointer-events: none;
            user-select: none;
        }
        body.page-loading table tbody tr td * { visibility: hidden; }

        /* ── Toast positioning tweak ── */
        .toast-container { z-index: 9999; }
        .toast { min-width: 280px; }
    </style>

    @stack('styles')
</head>
<body>
@auth
@php
    $user = auth()->user();
    $baseHome = match ($user->role) {
        'admin' => route('admin.dashboard'),
        'office_user' => route('office.dashboard'),
        default => route('citizen.dashboard')
    };
    $pendingOfficeRequests = $user->isOfficeUser() ? ($user->offices()->first()?->requests()->where('status', 'pending')->count() ?? 0) : 0;
    $activeCitizenRequests = $user->isCitizen() ? $user->serviceRequests()->whereNotIn('status', ['completed', 'rejected'])->count() : 0;
    $unreadCount = $user->unreadNotifications()->count();
@endphp

<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        {{-- ── Sidebar Menu ────────────────────────────────────────── --}}
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="{{ $baseHome }}" class="app-brand-link">
                    <span class="app-brand-logo"><i class="bi bi-building-check"></i></span>
                    <span class="app-brand-text demo menu-text fw-bold ms-2">E-Services</span>
                </a>
                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>

            <div class="menu-divider mt-0"></div>
            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                @if($user->isAdmin())
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Administration</span>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="menu-link">
                            <i class="bi bi-speedometer2"></i>
                            <div>Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.municipalities*') ? 'active' : '' }}">
                        <a href="{{ route('admin.municipalities') }}" class="menu-link">
                            <i class="bi bi-geo-alt"></i>
                            <div>Municipalities</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.offices*') ? 'active' : '' }}">
                        <a href="{{ route('admin.offices') }}" class="menu-link">
                            <i class="bi bi-buildings"></i>
                            <div>Offices</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users') }}" class="menu-link">
                            <i class="bi bi-people"></i>
                            <div>Users</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                        <a href="{{ route('admin.reports') }}" class="menu-link">
                            <i class="bi bi-bar-chart"></i>
                            <div>Reports</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.settings') || request()->routeIs('security.2fa') ? 'active' : '' }}">
                        <a href="{{ Route::has('admin.settings') ? route('admin.settings') : route('security.2fa') }}" class="menu-link">
                            <i class="bi bi-gear"></i>
                            <div>Settings</div>
                        </a>
                    </li>

                @elseif($user->isOfficeUser())
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Office Panel</span>
                    </li>
                    <li class="menu-item {{ request()->routeIs('office.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('office.dashboard') }}" class="menu-link">
                            <i class="bi bi-speedometer2"></i>
                            <div>Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('office.services*') ? 'active' : '' }}">
                        <a href="{{ route('office.services') }}" class="menu-link">
                            <i class="bi bi-grid-3x3-gap"></i>
                            <div>Services</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('office.requests*') ? 'active' : '' }}">
                        <a href="{{ route('office.requests') }}" class="menu-link">
                            <i class="bi bi-inbox"></i>
                            <div>Requests</div>
                            @if($pendingOfficeRequests > 0)
                                <span class="badge rounded-pill bg-warning-subtle border border-warning-subtle ms-auto">{{ $pendingOfficeRequests }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('office.appointments*') ? 'active' : '' }}">
                        <a href="{{ route('office.appointments') }}" class="menu-link">
                            <i class="bi bi-calendar-check"></i>
                            <div>Appointments</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('office.feedback*') ? 'active' : '' }}">
                        <a href="{{ route('office.feedback') }}" class="menu-link">
                            <i class="bi bi-chat-left-dots"></i>
                            <div>Feedback</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('office.profile*') ? 'active' : '' }}">
                        <a href="{{ route('office.profile') }}" class="menu-link">
                            <i class="bi bi-person-vcard"></i>
                            <div>Profile</div>
                        </a>
                    </li>

                @else
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Citizen Portal</span>
                    </li>
                    <li class="menu-item {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('citizen.dashboard') }}" class="menu-link">
                            <i class="bi bi-speedometer2"></i>
                            <div>Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('citizen.offices*') || request()->routeIs('citizen.services*') ? 'active' : '' }}">
                        <a href="{{ route('citizen.offices') }}" class="menu-link">
                            <i class="bi bi-ui-checks-grid"></i>
                            <div>Services</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('citizen.requests*') ? 'active' : '' }}">
                        <a href="{{ route('citizen.requests') }}" class="menu-link">
                            <i class="bi bi-file-earmark-text"></i>
                            <div>My Requests</div>
                            @if($activeCitizenRequests > 0)
                                <span class="badge rounded-pill bg-warning-subtle border border-warning-subtle ms-auto">{{ $activeCitizenRequests }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('citizen.requests') }}?appointments=1" class="menu-link">
                            <i class="bi bi-calendar-event"></i>
                            <div>Appointments</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('citizen.requests') }}?payment_status=unpaid" class="menu-link">
                            <i class="bi bi-credit-card"></i>
                            <div>Payments</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('citizen.profile*') ? 'active' : '' }}">
                        <a href="{{ route('citizen.profile') }}" class="menu-link">
                            <i class="bi bi-person"></i>
                            <div>Profile</div>
                        </a>
                    </li>
                @endif
            </ul>

            {{-- Sidebar Footer: User Chip --}}
            <div class="menu-footer">
                <div class="menu-user-chip">
                    <span class="menu-user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    <span style="min-width:0; flex:1;">
                        <span class="menu-user-name d-block">{{ $user->name }}</span>
                        <span class="menu-user-role d-block">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-secondary border" type="submit" title="Logout"><i class="bi bi-box-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </aside>
        {{-- ── / Sidebar Menu ──────────────────────────────────────── --}}

        {{-- ── Layout Page ─────────────────────────────────────────── --}}
        <div class="layout-page">

            {{-- Navbar --}}
            <nav class="layout-navbar navbar navbar-expand-xl align-items-center" id="layout-navbar">
                <div class="container-fluid">
                    <div class="d-flex align-items-center gap-2">
                        <button class="layout-menu-toggle navbar-toggler" type="button" aria-label="Toggle sidebar">
                            <i class="bi bi-list" style="font-size:1.15rem;"></i>
                        </button>
                        <h1 class="mb-0 page-title-text">@yield('page-title', 'Dashboard')</h1>
                    </div>

                    <div class="navbar-nav-right">
                        {{-- Notifications Dropdown --}}
                        <div class="dropdown">
                            <button class="navbar-icon-btn" data-bs-toggle="dropdown" type="button" aria-label="Notifications">
                                <i class="bi bi-bell"></i>
                                @if($unreadCount > 0)
                                    <span class="navbar-icon-dot">{{ min($unreadCount, 9) }}</span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" style="width:320px; max-height:380px; overflow-y:auto;">
                                <h6 class="dropdown-header">Notifications</h6>
                                @forelse($user->unreadNotifications->take(6) as $notification)
                                    <div class="dropdown-item text-wrap">
                                        <div class="fw-semibold text-dark notif-msg">{{ $notification->data['message'] ?? 'New notification' }}</div>
                                        <div class="text-muted notif-time">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                @empty
                                    <div class="dropdown-item text-muted">No new notifications.</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- User Avatar Dropdown --}}
                        <div class="dropdown ms-1">
                            <div class="navbar-user-avatar" data-bs-toggle="dropdown" role="button" tabindex="0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="dropdown-menu dropdown-menu-end" style="min-width:215px;">
                                <div class="px-3 py-2 border-bottom">
                                    <div class="fw-bold text-dark user-name">{{ $user->name }}</div>
                                    <div class="text-muted user-email">{{ $user->email }}</div>
                                </div>
                                @if($user->isCitizen())
                                    <a class="dropdown-item" href="{{ route('citizen.profile') }}"><i class="bi bi-person me-2"></i>Profile</a>
                                @elseif($user->isOfficeUser())
                                    <a class="dropdown-item" href="{{ route('office.profile') }}"><i class="bi bi-person-vcard me-2"></i>Profile</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('security.2fa') }}"><i class="bi bi-shield-check me-2"></i>Security</a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            {{-- / Navbar --}}

            {{-- Content Wrapper --}}
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">

                    {{-- Breadcrumbs --}}
                    @php
                        $segments = request()->segments();
                    @endphp
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ $baseHome }}">Home</a></li>
                                @foreach($segments as $i => $segment)
                                    @php
                                        $label = ucfirst(str_replace('-', ' ', $segment));
                                        $isLast = $i === count($segments) - 1;
                                    @endphp
                                    @if($isLast)
                                        <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                                    @else
                                        <li class="breadcrumb-item">{{ $label }}</li>
                                    @endif
                                @endforeach
                            </ol>
                        </nav>
                    </div>

                    {{-- Validation errors (inline, near forms) --}}
                    @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Page Content --}}
                    @yield('content')

                </div>

                {{-- Footer --}}
                <footer class="content-footer">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span>Lebanese Municipal E-Services Platform</span>
                        <span>{{ now()->year }} &mdash; Service continuity and transparent request tracking.</span>
                    </div>
                </footer>

                <div class="content-backdrop fade"></div>
            </div>
            {{-- / Content Wrapper --}}
        </div>
        {{-- ── / Layout Page ───────────────────────────────────────── --}}

        {{-- Overlay --}}
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
</div>

{{-- ── Toast Stack (flash messages) ── --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    @if(session('success'))
        <div class="toast align-items-center text-bg-success border-0" role="alert" data-bs-autohide="true" data-bs-delay="4000">
            <div class="d-flex">
                <div class="toast-body"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="toast align-items-center text-bg-danger border-0" role="alert" data-bs-autohide="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
    @if(session('info'))
        <div class="toast align-items-center text-bg-info border-0" role="alert" data-bs-autohide="true" data-bs-delay="4000">
            <div class="d-flex">
                <div class="toast-body"><i class="bi bi-info-circle me-2"></i>{{ session('info') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
</div>

@endauth

@guest
    @yield('content')
@endguest

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('sneat/vendor/js/menu.js') }}"></script>
<script src="{{ asset('sneat/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.js"></script>
<script>
    // ── NProgress page-load bar ──
    NProgress.configure({ showSpinner: false, trickleSpeed: 180 });

    document.addEventListener('click', function (e) {
        const a = e.target.closest('a[href]');
        if (!a) return;
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript') || a.target === '_blank' || a.download) return;
        try {
            const url = new URL(href, location.origin);
            if (url.origin !== location.origin) return;
        } catch (_) { return; }
        NProgress.start();
        document.body.classList.add('page-loading');
    });

    document.addEventListener('submit', function () {
        NProgress.start();
        document.body.classList.add('page-loading');
    });

    window.addEventListener('pageshow', function () {
        NProgress.done();
        document.body.classList.remove('page-loading');
    });

    // ── Auto-show flash toasts ──
    document.querySelectorAll('.toast').forEach(function (el) {
        new bootstrap.Toast(el).show();
    });
</script>
@stack('scripts')
</body>
</html>
