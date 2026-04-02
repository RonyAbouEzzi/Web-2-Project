<!DOCTYPE html>
<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="{{ asset('/assets') . '/' }}" dir="ltr" data-skin="default" data-base-url="{{ url('/') }}" data-framework="laravel" data-bs-theme="light" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | {{ config('variables.templateName', 'E-Services') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}">

    <!-- Include Sneat Styles (Vite-processed SCSS) -->
    @include('layouts.sections.styles')

    <!-- Include Sneat Scripts for helpers & config -->
    @include('layouts.sections.scriptsIncludes')

    @yield('vendor-style')
    @yield('page-style')
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
                    <span class="app-brand-logo demo">@include('_partials.macros',['width'=>'25'])</span>
                    <span class="app-brand-text demo menu-text fw-bold ms-2">{{ config('variables.templateName', 'E-Services') }}</span>
                </a>
                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="icon-base bx bx-chevron-left icon-sm d-flex align-items-center justify-content-center"></i>
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
                            <i class="menu-icon icon-base bx bx-home-smile"></i>
                            <div>Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.municipalities*') ? 'active' : '' }}">
                        <a href="{{ route('admin.municipalities') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-map"></i>
                            <div>Municipalities</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.offices*') ? 'active' : '' }}">
                        <a href="{{ route('admin.offices') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-building-house"></i>
                            <div>Offices</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-group"></i>
                            <div>Users</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                        <a href="{{ route('admin.reports') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-bar-chart-alt-2"></i>
                            <div>Reports</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.settings') || request()->routeIs('security.2fa') ? 'active' : '' }}">
                        <a href="{{ Route::has('admin.settings') ? route('admin.settings') : route('security.2fa') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-cog"></i>
                            <div>Settings</div>
                        </a>
                    </li>

                @elseif($user->isOfficeUser())
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Office Panel</span>
                    </li>
                    <li class="menu-item {{ request()->routeIs('office.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('office.dashboard') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-home-smile"></i>
                            <div>Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('office.services*') ? 'active' : '' }}">
                        <a href="{{ route('office.services') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-grid-alt"></i>
                            <div>Services</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('office.requests*') ? 'active' : '' }}">
                        <a href="{{ route('office.requests') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-inbox"></i>
                            <div>Requests</div>
                            @if($pendingOfficeRequests > 0)
                                <div class="badge rounded-pill bg-warning text-uppercase ms-auto">{{ $pendingOfficeRequests }}</div>
                            @endif
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('office.appointments*') ? 'active' : '' }}">
                        <a href="{{ route('office.appointments') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-calendar-check"></i>
                            <div>Appointments</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('office.feedback*') ? 'active' : '' }}">
                        <a href="{{ route('office.feedback') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-chat"></i>
                            <div>Feedback</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('office.profile*') ? 'active' : '' }}">
                        <a href="{{ route('office.profile') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-id-card"></i>
                            <div>Profile</div>
                        </a>
                    </li>

                @else
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Citizen Portal</span>
                    </li>
                    <li class="menu-item {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('citizen.dashboard') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-home-smile"></i>
                            <div>Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('citizen.offices*') || request()->routeIs('citizen.services*') ? 'active' : '' }}">
                        <a href="{{ route('citizen.offices') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-check-square"></i>
                            <div>Services</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('citizen.requests*') ? 'active' : '' }}">
                        <a href="{{ route('citizen.requests') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-file"></i>
                            <div>My Requests</div>
                            @if($activeCitizenRequests > 0)
                                <div class="badge rounded-pill bg-warning text-uppercase ms-auto">{{ $activeCitizenRequests }}</div>
                            @endif
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('citizen.requests') }}?appointments=1" class="menu-link">
                            <i class="menu-icon icon-base bx bx-calendar-event"></i>
                            <div>Appointments</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('citizen.requests') }}?payment_status=unpaid" class="menu-link">
                            <i class="menu-icon icon-base bx bx-credit-card"></i>
                            <div>Payments</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('citizen.profile*') ? 'active' : '' }}">
                        <a href="{{ route('citizen.profile') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-user"></i>
                            <div>Profile</div>
                        </a>
                    </li>
                @endif
            </ul>
        </aside>
        {{-- ── / Sidebar Menu ──────────────────────────────────────── --}}

        {{-- ── Layout Page ─────────────────────────────────────────── --}}
        <div class="layout-page">

            <!-- Navbar -->
            <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                        <i class="icon-base bx bx-menu icon-md"></i>
                    </a>
                </div>

                <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                    <div class="navbar-nav align-items-center">
                        <h1 class="mb-0" style="font-size:.95rem; font-weight:700;">@yield('page-title', 'Dashboard')</h1>
                    </div>

                    <ul class="navbar-nav flex-row align-items-center ms-auto">
                        {{-- Notifications --}}
                        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                <span class="position-relative">
                                    <i class="icon-base bx bx-bell icon-md"></i>
                                    @if($unreadCount > 0)
                                        <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                                    @endif
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="width:320px; max-height:380px; overflow-y:auto;">
                                <li class="dropdown-menu-header border-bottom">
                                    <div class="dropdown-header d-flex align-items-center py-3">
                                        <h6 class="mb-0 me-auto">Notifications</h6>
                                        @if($unreadCount > 0)
                                            <span class="badge rounded-pill bg-label-primary">{{ $unreadCount }} New</span>
                                        @endif
                                    </div>
                                </li>
                                @forelse($user->unreadNotifications->take(6) as $notification)
                                    <li>
                                        <div class="dropdown-item text-wrap py-2">
                                            <div class="fw-semibold text-heading" style="font-size:.8rem;">{{ $notification->data['message'] ?? 'New notification' }}</div>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </li>
                                @empty
                                    <li>
                                        <div class="dropdown-item text-muted py-3 text-center">No new notifications</div>
                                    </li>
                                @endforelse
                            </ul>
                        </li>

                        {{-- User --}}
                        <li class="nav-item navbar-dropdown dropdown-user dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                                <div class="avatar avatar-online">
                                    <span class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar avatar-online">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li><div class="dropdown-divider my-1"></div></li>
                                @if($user->isCitizen())
                                    <li><a class="dropdown-item" href="{{ route('citizen.profile') }}"><i class="icon-base bx bx-user icon-md me-3"></i><span>Profile</span></a></li>
                                @elseif($user->isOfficeUser())
                                    <li><a class="dropdown-item" href="{{ route('office.profile') }}"><i class="icon-base bx bx-id-card icon-md me-3"></i><span>Profile</span></a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('security.2fa') }}"><i class="icon-base bx bx-shield icon-md me-3"></i><span>Security</span></a></li>
                                <li><div class="dropdown-divider my-1"></div></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Log Out</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">

                    {{-- Breadcrumbs --}}
                    @php $segments = request()->segments(); @endphp
                    @if(count($segments) > 0)
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
                    @endif

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

                <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl">
                        <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                            <div class="text-body">
                                &copy; <script>document.write(new Date().getFullYear())</script> Municipal E-Services Platform
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!--/ Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
</div>
@endauth

@guest
    @yield('content')
@endguest

<!-- Include Sneat Scripts (Vite-processed JS) -->
@include('layouts.sections.scripts')
@stack('scripts')
</body>
</html>
