<!DOCTYPE html>
<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="{{ asset('sneat') . '/' }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Services Management')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('sneat/css/core.css') }}" rel="stylesheet">
    <link href="{{ asset('sneat/css/demo.css') }}" rel="stylesheet">
    <link href="{{ asset('sneat/vendor/fonts/iconify/iconify.css') }}" rel="stylesheet">

    <script src="{{ asset('sneat/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('sneat/js/config.js') }}"></script>

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
                        <h1 class="mb-0" style="font-size:.95rem; font-weight:700; color:#292524;">@yield('page-title', 'Dashboard')</h1>
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
                                        <div class="fw-semibold text-dark" style="font-size:.78rem;">{{ $notification->data['message'] ?? 'New notification' }}</div>
                                        <div class="text-muted" style="font-size:.68rem;">{{ $notification->created_at->diffForHumans() }}</div>
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
                                    <div class="fw-bold text-dark" style="font-size:.84rem;">{{ $user->name }}</div>
                                    <div class="text-muted" style="font-size:.7rem;">{{ $user->email }}</div>
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

                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
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
@endauth

@guest
    @yield('content')
@endguest

<script src="{{ asset('sneat/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('sneat/vendor/libs/popper/popper.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('sneat/vendor/js/menu.js') }}"></script>
<script src="{{ asset('sneat/js/main.js') }}"></script>
@stack('scripts')
</body>
</html>
