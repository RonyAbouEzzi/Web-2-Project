<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ config('variables.templateName', 'E-Services') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Design System --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        /* Dashboard refinements */
        body { background: #F5F0E8; }

        .es-sidebar {
            background: #fff;
            border-right: 1px solid #EAE6DF;
        }

        .es-sidebar-brand {
            border-bottom: 1px solid #EAE6DF;
        }

        /* Black brand mark in dashboard too */
        .es-brand-mark {
            background: #1A1714;
            border-radius: 8px;
            width: 34px;
            height: 34px;
        }

        .es-brand-name {
            font-size: 0.875rem;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .es-brand-sub {
            font-size: 0.6rem;
            letter-spacing: 0.01em;
        }

        /* Nav links */
        .es-nav-link {
            color: #78716C;
            border-radius: 7px;
            margin: 0.0625rem 0.625rem;
            font-size: 0.875rem;
        }
        .es-nav-link:hover {
            background: #F5F0E8;
            color: #1A1714;
        }
        .es-nav-link.active {
            background: #CCFBF1;
            color: #0D9488;
        }

        /* Topbar */
        .es-topbar {
            background: #fff;
            border-bottom: 1px solid #EAE6DF;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        /* Content area */
        .es-content {
            background: #F5F0E8;
        }

        /* Cards get subtle warmth */
        .card {
            border-color: #EAE6DF;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .card-header {
            border-bottom-color: #F0EDE8;
        }

        /* Tables look cleaner */
        .table th {
            background: #FAF8F4;
            border-bottom-color: #EAE6DF;
        }
        .table td {
            border-bottom-color: #F0EDE8;
        }
        .table-hover tbody tr:hover > td {
            background: #FAF8F4;
        }

        /* Footer */
        .es-footer {
            border-top-color: #EAE6DF;
            background: #fff;
        }

        /* Buttons */
        .btn-primary {
            background: #0D9488;
            border-color: #0D9488;
        }
        .btn-primary:hover {
            background: #0B7F76;
            border-color: #0B7F76;
        }

        /* Breadcrumb on cream background */
        .breadcrumb {
            background: transparent;
        }
    </style>

    @yield('vendor-style')
    @yield('page-style')
    @stack('styles')
</head>
<body>

@auth
@php
    $user = auth()->user();
    $baseHome = match ($user->role) {
        'admin'       => route('admin.dashboard'),
        'office_user' => route('office.dashboard'),
        default       => route('citizen.dashboard'),
    };
    $pendingOfficeRequests = $user->isOfficeUser()
        ? ($user->offices()->first()?->requests()->where('status', 'pending')->count() ?? 0)
        : 0;
    $activeCitizenRequests = $user->isCitizen()
        ? $user->serviceRequests()->whereNotIn('status', ['completed', 'rejected'])->count()
        : 0;
    $unreadCount = $user->unreadNotifications()->count();
@endphp

{{-- Sidebar overlay (mobile) --}}
<div class="es-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="es-wrapper">

    {{-- ── Sidebar ──────────────────────────────────────────── --}}
    <aside class="es-sidebar" id="esSidebar">

        {{-- Brand --}}
        <a href="{{ $baseHome }}" class="es-sidebar-brand">
            <span class="es-brand-mark" style="background:#1A1714;"><i class="bi bi-building-check"></i></span>
            <span>
                <span class="es-brand-name d-block">{{ config('variables.templateName', 'E-Services') }}</span>
                <span class="es-brand-sub">Lebanon Gov Portal</span>
            </span>
        </a>

        {{-- Navigation --}}
        <nav class="es-nav">

            @if($user->isAdmin())
                <span class="es-nav-section">Administration</span>

                <a href="{{ route('admin.dashboard') }}"
                   class="es-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i>
                    <span class="es-nav-label">Dashboard</span>
                </a>
                <a href="{{ route('admin.municipalities') }}"
                   class="es-nav-link {{ request()->routeIs('admin.municipalities*') ? 'active' : '' }}">
                    <i class="bi bi-map"></i>
                    <span class="es-nav-label">Municipalities</span>
                </a>
                <a href="{{ route('admin.offices') }}"
                   class="es-nav-link {{ request()->routeIs('admin.offices*') ? 'active' : '' }}">
                    <i class="bi bi-buildings"></i>
                    <span class="es-nav-label">Offices</span>
                </a>
                <a href="{{ route('admin.users') }}"
                   class="es-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span class="es-nav-label">Users</span>
                </a>
                <a href="{{ route('admin.reports') }}"
                   class="es-nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i>
                    <span class="es-nav-label">Reports</span>
                </a>
                <a href="{{ Route::has('admin.settings') ? route('admin.settings') : route('security.2fa') }}"
                   class="es-nav-link {{ request()->routeIs('admin.settings') || request()->routeIs('security.2fa') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span class="es-nav-label">Settings</span>
                </a>

            @elseif($user->isOfficeUser())
                <span class="es-nav-section">Office Panel</span>

                <a href="{{ route('office.dashboard') }}"
                   class="es-nav-link {{ request()->routeIs('office.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i>
                    <span class="es-nav-label">Dashboard</span>
                </a>
                <a href="{{ route('office.services') }}"
                   class="es-nav-link {{ request()->routeIs('office.services*') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span class="es-nav-label">Services</span>
                </a>
                <a href="{{ route('office.requests') }}"
                   class="es-nav-link {{ request()->routeIs('office.requests*') ? 'active' : '' }}">
                    <i class="bi bi-inbox"></i>
                    <span class="es-nav-label">Requests</span>
                    @if($pendingOfficeRequests > 0)
                        <span class="es-nav-badge">{{ $pendingOfficeRequests }}</span>
                    @endif
                </a>
                <a href="{{ route('office.appointments') }}"
                   class="es-nav-link {{ request()->routeIs('office.appointments*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <span class="es-nav-label">Appointments</span>
                </a>
                <a href="{{ route('office.feedback') }}"
                   class="es-nav-link {{ request()->routeIs('office.feedback*') ? 'active' : '' }}">
                    <i class="bi bi-chat-left-text"></i>
                    <span class="es-nav-label">Feedback</span>
                </a>
                <a href="{{ route('office.profile') }}"
                   class="es-nav-link {{ request()->routeIs('office.profile*') ? 'active' : '' }}">
                    <i class="bi bi-id-card"></i>
                    <span class="es-nav-label">Profile</span>
                </a>

            @else
                <span class="es-nav-section">Citizen Portal</span>

                <a href="{{ route('citizen.dashboard') }}"
                   class="es-nav-link {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i>
                    <span class="es-nav-label">Dashboard</span>
                </a>
                <a href="{{ route('citizen.offices') }}"
                   class="es-nav-link {{ request()->routeIs('citizen.offices*') || request()->routeIs('citizen.services*') ? 'active' : '' }}">
                    <i class="bi bi-search"></i>
                    <span class="es-nav-label">Browse Services</span>
                </a>
                <a href="{{ route('citizen.requests') }}"
                   class="es-nav-link {{ request()->routeIs('citizen.requests*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span class="es-nav-label">My Requests</span>
                    @if($activeCitizenRequests > 0)
                        <span class="es-nav-badge">{{ $activeCitizenRequests }}</span>
                    @endif
                </a>
                <a href="{{ route('citizen.requests') }}?appointments=1"
                   class="es-nav-link">
                    <i class="bi bi-calendar-event"></i>
                    <span class="es-nav-label">Appointments</span>
                </a>
                <a href="{{ route('citizen.requests') }}?payment_status=unpaid"
                   class="es-nav-link">
                    <i class="bi bi-credit-card"></i>
                    <span class="es-nav-label">Payments</span>
                </a>
                <a href="{{ route('citizen.profile') }}"
                   class="es-nav-link {{ request()->routeIs('citizen.profile*') ? 'active' : '' }}">
                    <i class="bi bi-person"></i>
                    <span class="es-nav-label">Profile</span>
                </a>
            @endif

            {{-- Shared bottom links --}}
            <span class="es-nav-section" style="margin-top:1.5rem;">Account</span>
            <a href="{{ route('security.2fa') }}"
               class="es-nav-link {{ request()->routeIs('security.2fa') ? 'active' : '' }}">
                <i class="bi bi-shield-check"></i>
                <span class="es-nav-label">Security</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="es-nav-link w-100 text-start border-0 bg-transparent"
                        style="cursor:pointer; color:var(--es-muted);">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="es-nav-label">Log Out</span>
                </button>
            </form>

        </nav>
    </aside>
    {{-- ── / Sidebar ─────────────────────────────────────────── --}}

    {{-- ── Main ────────────────────────────────────────────────── --}}
    <div class="es-main">

        {{-- Topbar --}}
        <header class="es-topbar">
            <button class="es-topbar-toggle" onclick="toggleSidebar()" aria-label="Toggle menu">
                <i class="bi bi-list"></i>
            </button>

            <h1 class="es-topbar-title">@yield('page-title', 'Dashboard')</h1>

            <div class="es-topbar-right">

                {{-- Notifications --}}
                <div class="dropdown">
                    <button class="es-topbar-btn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                        <i class="bi bi-bell"></i>
                        @if($unreadCount > 0)
                            <span class="es-notif-dot"></span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="width:310px; max-height:380px; overflow-y:auto; padding:.5rem 0;">
                        <li>
                            <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom" style="border-color:var(--es-border)!important;">
                                <span style="font-size:.82rem; font-weight:700; color:var(--es-text);">Notifications</span>
                                @if($unreadCount > 0)
                                    <span class="badge rounded-pill" style="background:var(--es-primary-s); color:var(--es-primary); font-size:.65rem;">{{ $unreadCount }} new</span>
                                @endif
                            </div>
                        </li>
                        @forelse($user->unreadNotifications->take(6) as $notification)
                            <li>
                                <div class="dropdown-item py-2" style="border-radius:0;">
                                    <div style="font-size:.82rem; font-weight:600; color:var(--es-text); line-height:1.4;">
                                        {{ $notification->data['message'] ?? 'New notification' }}
                                    </div>
                                    <div style="font-size:.72rem; color:var(--es-muted); margin-top:.2rem;">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li>
                                <div class="text-center py-4" style="color:var(--es-muted); font-size:.84rem;">
                                    <i class="bi bi-bell-slash d-block mb-1" style="font-size:1.5rem; opacity:.4;"></i>
                                    No new notifications
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>

                {{-- User menu --}}
                <div class="dropdown ms-1">
                    <div class="es-avatar" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width:210px;">
                        <li>
                            <div class="px-3 py-2 border-bottom" style="border-color:var(--es-border)!important;">
                                <div style="font-size:.875rem; font-weight:700; color:var(--es-text);">{{ $user->name }}</div>
                                <div style="font-size:.72rem; color:var(--es-muted);">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</div>
                            </div>
                        </li>
                        @if($user->isCitizen())
                            <li><a class="dropdown-item mt-1" href="{{ route('citizen.profile') }}">
                                <i class="bi bi-person me-2" style="color:var(--es-muted);"></i>Profile
                            </a></li>
                        @elseif($user->isOfficeUser())
                            <li><a class="dropdown-item mt-1" href="{{ route('office.profile') }}">
                                <i class="bi bi-id-card me-2" style="color:var(--es-muted);"></i>Profile
                            </a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('security.2fa') }}">
                            <i class="bi bi-shield-check me-2" style="color:var(--es-muted);"></i>Security
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">
                                    <i class="bi bi-box-arrow-right me-2"></i>Log Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            </div>
        </header>
        {{-- / Topbar --}}

        {{-- Content --}}
        <main class="es-content">

            {{-- Breadcrumb --}}
            @php $segments = request()->segments(); @endphp
            @if(count($segments) > 1)
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ $baseHome }}">Home</a></li>
                    @foreach($segments as $i => $segment)
                        @php
                            $label = ucfirst(str_replace('-', ' ', $segment));
                            $isLast = ($i === count($segments) - 1);
                        @endphp
                        @if($isLast)
                            <li class="breadcrumb-item active">{{ $label }}</li>
                        @else
                            <li class="breadcrumb-item">{{ $label }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
            @endif

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <ul class="mb-0 ps-3 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Page content --}}
            @yield('content')

        </main>

        {{-- Footer --}}
        <footer class="es-footer">
            &copy; {{ now()->year }} Municipal E-Services Platform &mdash; Lebanese Municipalities
        </footer>

    </div>
    {{-- ── / Main ───────────────────────────────────────────────── --}}

</div>

@endauth

@guest
    @yield('content')
@endguest

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Sidebar toggle --}}
<script>
function toggleSidebar() {
    document.getElementById('esSidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('esSidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
}
</script>

@stack('scripts')
</body>
</html>
