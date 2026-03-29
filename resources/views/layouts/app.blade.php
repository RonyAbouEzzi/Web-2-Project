<!DOCTYPE html>
<html lang="en" class="layout-navbar-fixed">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Services Management')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('sneat/css/demo.css') }}" rel="stylesheet">
    <link href="{{ asset('sneat/vendor/fonts/iconify/iconify.css') }}" rel="stylesheet">

    <style>
        :root {
            --gov-primary: #0D9488;
            --gov-primary-dark: #0b7f75;
            --gov-primary-soft: #ccfbf1;
            --gov-bg: #f5f5f4;
            --gov-surface: #ffffff;
            --gov-border: #e7e5e4;
            --gov-text: #1f2937;
            --gov-text-muted: #6b7280;
            --gov-warning: #f59e0b;
            --gov-success: #10b981;
            --gov-danger: #ef4444;
            --gov-info: #0ea5e9;
            --gov-sidebar-width: 270px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(180deg, #fafaf9 0%, var(--gov-bg) 100%);
            color: var(--gov-text);
        }

        .gov-shell {
            min-height: 100vh;
        }

        .gov-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--gov-sidebar-width);
            background: #fff;
            border-right: 1px solid var(--gov-border);
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transform: translateX(0);
            transition: transform .25s ease;
        }

        .gov-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: 1.2rem 1.25rem;
            border-bottom: 1px solid var(--gov-border);
            text-decoration: none;
            color: inherit;
        }

        .gov-brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--gov-primary-soft);
            color: var(--gov-primary-dark);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .gov-brand-title {
            font-weight: 800;
            font-size: .92rem;
            color: var(--gov-text);
            line-height: 1.25;
        }

        .gov-brand-sub {
            font-size: .7rem;
            color: var(--gov-text-muted);
            line-height: 1.25;
        }

        .gov-nav {
            padding: .8rem;
            overflow-y: auto;
            flex: 1;
        }

        .gov-nav-section {
            margin: .9rem .6rem .45rem;
            font-size: .66rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #a8a29e;
            font-weight: 700;
        }

        .gov-nav-link {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .62rem .7rem;
            margin-bottom: .2rem;
            border-radius: .6rem;
            text-decoration: none;
            color: #57534e;
            font-size: .84rem;
            font-weight: 600;
            border: 1px solid transparent;
            transition: all .2s ease;
        }

        .gov-nav-link:hover {
            background: #fafaf9;
            border-color: var(--gov-border);
            color: #292524;
        }

        .gov-nav-link.active {
            background: var(--gov-primary-soft);
            color: #134e4a;
            border-color: #99f6e4;
        }

        .gov-nav-badge {
            margin-left: auto;
            border-radius: 999px;
            padding: .12rem .45rem;
            font-size: .62rem;
            font-weight: 800;
            background: #fef3c7;
            color: #92400e;
        }

        .gov-sidebar-foot {
            border-top: 1px solid var(--gov-border);
            padding: .85rem;
        }

        .gov-user-chip {
            display: flex;
            align-items: center;
            gap: .65rem;
            border: 1px solid var(--gov-border);
            border-radius: .75rem;
            padding: .65rem;
            background: #fafaf9;
        }

        .gov-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--gov-primary-soft);
            color: var(--gov-primary-dark);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .gov-user-name {
            font-size: .8rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .gov-user-role {
            font-size: .68rem;
            color: var(--gov-text-muted);
        }

        .gov-main {
            margin-left: var(--gov-sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .gov-topbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: rgba(245, 245, 244, 0.9);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--gov-border);
            padding: .75rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .gov-menu-toggle {
            display: none;
            border: 1px solid var(--gov-border);
            background: #fff;
            border-radius: .6rem;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            color: #57534e;
        }

        .gov-page-title {
            font-size: .95rem;
            font-weight: 700;
            margin: 0;
            color: #292524;
        }

        .gov-topbar-actions {
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        .gov-icon-btn {
            width: 36px;
            height: 36px;
            border-radius: .65rem;
            border: 1px solid var(--gov-border);
            background: #fff;
            color: #57534e;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .gov-icon-dot {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--gov-danger);
            color: #fff;
            border-radius: 999px;
            min-width: 18px;
            height: 18px;
            font-size: .6rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 .2rem;
            border: 2px solid #fff;
        }

        .gov-top-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid var(--gov-border);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #57534e;
            font-weight: 700;
            cursor: pointer;
        }

        .gov-content {
            padding: 1.35rem;
            flex: 1;
        }

        .gov-breadcrumb-wrap {
            margin-bottom: .9rem;
        }

        .breadcrumb {
            margin: 0;
            font-size: .76rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: #a8a29e;
        }

        .breadcrumb-item a {
            text-decoration: none;
            color: #57534e;
        }

        .breadcrumb-item.active {
            color: #0f766e;
            font-weight: 600;
        }

        .card,
        .gov-card {
            background: var(--gov-surface);
            border: 1px solid var(--gov-border);
            box-shadow: none;
            border-radius: .95rem;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--gov-border);
            padding: .95rem 1.1rem;
        }

        .card-body {
            padding: 1.1rem;
        }

        .btn {
            border-radius: .65rem;
            font-weight: 600;
            font-size: .82rem;
        }

        .btn-primary {
            background: var(--gov-primary);
            border-color: var(--gov-primary);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: var(--gov-primary-dark);
            border-color: var(--gov-primary-dark);
        }

        .btn-outline-primary {
            border-color: var(--gov-primary);
            color: var(--gov-primary);
        }

        .badge.bg-warning-subtle { color: #92400e !important; }
        .badge.bg-success-subtle { color: #065f46 !important; }
        .badge.bg-danger-subtle { color: #991b1b !important; }
        .badge.bg-info-subtle { color: #0c4a6e !important; }

        .table > :not(caption) > * > * {
            border-color: #f0efee;
            padding: .75rem .85rem;
            font-size: .82rem;
            vertical-align: middle;
        }

        .form-control,
        .form-select {
            border-radius: .65rem;
            border-color: var(--gov-border);
            font-size: .84rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #5eead4;
            box-shadow: 0 0 0 .2rem rgba(13, 148, 136, 0.12);
        }

        .alert {
            border-radius: .75rem;
            border: 1px solid transparent;
            font-size: .82rem;
        }

        .dropdown-menu {
            border-radius: .75rem;
            border: 1px solid var(--gov-border);
            box-shadow: 0 16px 30px rgba(41, 37, 36, 0.08);
            padding: .35rem;
        }

        .dropdown-item {
            border-radius: .55rem;
            font-size: .82rem;
            padding: .5rem .65rem;
        }

        .gov-footer {
            border-top: 1px solid var(--gov-border);
            padding: .95rem 1.35rem;
            color: #78716c;
            font-size: .75rem;
            background: #fafaf9;
        }

        .gov-overlay {
            position: fixed;
            inset: 0;
            background: rgba(28, 25, 23, .35);
            z-index: 1035;
            display: none;
        }

        .gov-shell.sidebar-open .gov-overlay {
            display: block;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-in_review { background: #e0f2fe; color: #075985; }
        .status-approved,
        .status-completed,
        .status-paid,
        .status-confirmed { background: #dcfce7; color: #166534; }
        .status-rejected,
        .status-unpaid,
        .status-cancelled,
        .status-missing_documents { background: #fee2e2; color: #991b1b; }

        @media (max-width: 991.98px) {
            .gov-sidebar {
                transform: translateX(-100%);
            }

            .gov-shell.sidebar-open .gov-sidebar {
                transform: translateX(0);
            }

            .gov-main {
                margin-left: 0;
            }

            .gov-menu-toggle {
                display: inline-flex;
            }

            .gov-content {
                padding: 1rem;
            }
        }
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

<div class="gov-shell" id="govShell">
    <div class="gov-overlay" id="govOverlay"></div>

    <aside class="gov-sidebar" id="govSidebar" aria-label="Sidebar navigation">
        <a href="{{ $baseHome }}" class="gov-brand">
            <span class="gov-brand-mark"><i class="bi bi-building-check"></i></span>
            <span>
                <span class="gov-brand-title d-block">Municipal E-Services</span>
                <span class="gov-brand-sub d-block">Lebanon Municipal Platform</span>
            </span>
        </a>

        <div class="gov-nav">
            @if($user->isAdmin())
                <div class="gov-nav-section">Administration</div>
                <a href="{{ route('admin.dashboard') }}" class="gov-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ route('admin.municipalities') }}" class="gov-nav-link {{ request()->routeIs('admin.municipalities*') ? 'active' : '' }}"><i class="bi bi-geo-alt"></i> Municipalities</a>
                <a href="{{ route('admin.offices') }}" class="gov-nav-link {{ request()->routeIs('admin.offices*') ? 'active' : '' }}"><i class="bi bi-buildings"></i> Offices</a>
                <a href="{{ route('admin.users') }}" class="gov-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"><i class="bi bi-people"></i> Users</a>
                <a href="{{ route('admin.reports') }}" class="gov-nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}"><i class="bi bi-bar-chart"></i> Reports</a>
                <a href="{{ Route::has('admin.settings') ? route('admin.settings') : route('security.2fa') }}" class="gov-nav-link {{ request()->routeIs('admin.settings') || request()->routeIs('security.2fa') ? 'active' : '' }}"><i class="bi bi-gear"></i> Settings</a>
            @elseif($user->isOfficeUser())
                <div class="gov-nav-section">Office Panel</div>
                <a href="{{ route('office.dashboard') }}" class="gov-nav-link {{ request()->routeIs('office.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ route('office.services') }}" class="gov-nav-link {{ request()->routeIs('office.services*') ? 'active' : '' }}"><i class="bi bi-grid-3x3-gap"></i> Services</a>
                <a href="{{ route('office.requests') }}" class="gov-nav-link {{ request()->routeIs('office.requests*') ? 'active' : '' }}"><i class="bi bi-inbox"></i> Requests @if($pendingOfficeRequests > 0)<span class="gov-nav-badge">{{ $pendingOfficeRequests }}</span>@endif</a>
                <a href="{{ route('office.appointments') }}" class="gov-nav-link {{ request()->routeIs('office.appointments*') ? 'active' : '' }}"><i class="bi bi-calendar-check"></i> Appointments</a>
                <a href="{{ route('office.feedback') }}" class="gov-nav-link {{ request()->routeIs('office.feedback*') ? 'active' : '' }}"><i class="bi bi-chat-left-dots"></i> Feedback</a>
                <a href="{{ route('office.profile') }}" class="gov-nav-link {{ request()->routeIs('office.profile*') ? 'active' : '' }}"><i class="bi bi-person-vcard"></i> Profile</a>
            @else
                <div class="gov-nav-section">Citizen Portal</div>
                <a href="{{ route('citizen.dashboard') }}" class="gov-nav-link {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ route('citizen.offices') }}" class="gov-nav-link {{ request()->routeIs('citizen.offices*') || request()->routeIs('citizen.services*') ? 'active' : '' }}"><i class="bi bi-ui-checks-grid"></i> Services</a>
                <a href="{{ route('citizen.requests') }}" class="gov-nav-link {{ request()->routeIs('citizen.requests*') ? 'active' : '' }}"><i class="bi bi-file-earmark-text"></i> My Requests @if($activeCitizenRequests > 0)<span class="gov-nav-badge">{{ $activeCitizenRequests }}</span>@endif</a>
                <a href="{{ route('citizen.requests') }}?appointments=1" class="gov-nav-link"><i class="bi bi-calendar-event"></i> Appointments</a>
                <a href="{{ route('citizen.requests') }}?payment_status=unpaid" class="gov-nav-link"><i class="bi bi-credit-card"></i> Payments</a>
                <a href="{{ route('citizen.profile') }}" class="gov-nav-link {{ request()->routeIs('citizen.profile*') ? 'active' : '' }}"><i class="bi bi-person"></i> Profile</a>
            @endif
        </div>

        <div class="gov-sidebar-foot">
            <div class="gov-user-chip">
                <span class="gov-user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                <span style="min-width:0; flex:1;">
                    <span class="gov-user-name d-block">{{ $user->name }}</span>
                    <span class="gov-user-role d-block">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-light border" type="submit" title="Logout"><i class="bi bi-box-arrow-right"></i></button>
                </form>
            </div>
        </div>
    </aside>

    <main class="gov-main">
        <header class="gov-topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="gov-menu-toggle" id="govMenuToggle" type="button" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="gov-page-title">@yield('page-title', 'Dashboard')</h1>
            </div>

            <div class="gov-topbar-actions">
                <div class="dropdown">
                    <button class="gov-icon-btn" data-bs-toggle="dropdown" type="button" aria-label="Notifications">
                        <i class="bi bi-bell"></i>
                        @if($unreadCount > 0)
                            <span class="gov-icon-dot">{{ min($unreadCount, 9) }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" style="width: 320px;">
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

                <div class="dropdown">
                    <div class="gov-top-avatar" data-bs-toggle="dropdown" role="button" tabindex="0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="dropdown-menu dropdown-menu-end" style="min-width: 215px;">
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
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <section class="gov-content">
            @php
                $segments = request()->segments();
            @endphp
            <div class="gov-breadcrumb-wrap">
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

            @yield('content')
        </section>

        <footer class="gov-footer">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>Lebanese Municipal E-Services Platform</span>
                <span>{{ now()->year }} - Service continuity and transparent request tracking.</span>
            </div>
        </footer>
    </main>
</div>
@endauth

@guest
    @yield('content')
@endguest

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const shell = document.getElementById('govShell');
        const sidebar = document.getElementById('govSidebar');
        const overlay = document.getElementById('govOverlay');
        const toggle = document.getElementById('govMenuToggle');

        if (!shell || !sidebar || !toggle || !overlay) {
            return;
        }

        const closeSidebar = () => shell.classList.remove('sidebar-open');
        const openSidebar = () => shell.classList.add('sidebar-open');

        toggle.addEventListener('click', () => {
            if (shell.classList.contains('sidebar-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        overlay.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth > 991) {
                closeSidebar();
            }
        });
    })();
</script>
@stack('scripts')
</body>
</html>
