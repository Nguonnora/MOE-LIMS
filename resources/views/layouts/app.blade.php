<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MOE LIMS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background: #f0f7f0; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .navbar-custom { background: #2e7d32 !important; padding: 0.5rem 1rem; z-index: 1050; }
        .navbar-custom .navbar-brand { color: #fff; font-weight: bold; }
        .navbar-custom .navbar-brand img { height: 40px; margin-right: 10px; }
        .navbar-custom .nav-link { color: #e8f5e9 !important; }
        .navbar-custom .nav-link:hover { color: #fff !important; }
        .navbar-custom .dropdown-menu { background: #fff; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .navbar-custom .dropdown-item:hover { background: #e8f5e9; }
        .sidebar-toggle { background: transparent; border: none; color: #fff; font-size: 1.5rem; cursor: pointer; margin-right: 10px; }
        .sidebar-toggle:focus { outline: none; }
        .sidebar {
            position: fixed;
            top: 56px;
            left: 0;
            bottom: 0;
            width: 260px;
            background: #1b5e20;
            padding: 20px 0;
            transition: width 0.3s, transform 0.3s;
            z-index: 1040;
            overflow: hidden;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        .sidebar.icon-only { width: 70px; }
        .sidebar.icon-only .nav-link span { display: none; }
        .sidebar.icon-only .nav-link { text-align: center; padding: 10px 0; }
        .sidebar.icon-only .nav-link i { margin-right: 0; font-size: 1.4rem; }
        .sidebar.icon-only .sidebar-footer p { display: none; }
        .sidebar.closed { transform: translateX(-100%); }
        .sidebar .nav { flex: 1; flex-direction: column; padding: 0 10px; }
        .sidebar .nav-link { color: #c8e6c9; padding: 10px 15px; border-radius: 8px; margin-bottom: 5px; transition: background 0.2s; white-space: nowrap; overflow: hidden; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #2e7d32; color: #fff; }
        .sidebar .nav-link i { width: 24px; text-align: center; margin-right: 10px; }
        .sidebar-footer { padding: 15px 20px; border-top: 1px solid #2e7d32; color: #a5d6a7; font-size: 0.8rem; text-align: center; white-space: nowrap; overflow: hidden; }
        .main-content {
            margin-left: 260px;
            padding: 86px 30px 30px;
            transition: margin-left 0.3s ease;
            background: #f0f7f0;
            min-height: calc(100vh - 56px);
        }
        .main-content.icon-only { margin-left: 70px; }
        .main-content.closed { margin-left: 0; }
        .sidebar-overlay {
            position: fixed;
            top: 56px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            z-index: 1030;
            display: none;
        }
        .sidebar-overlay.active { display: block; }
        @media (max-width: 768px) {
            .sidebar { width: 280px; transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar.icon-only { width: 70px; transform: translateX(0); }
            .sidebar.icon-only .nav-link span { display: none; }
            .main-content { margin-left: 0 !important; padding: 76px 15px 20px; }
            .main-content.closed { margin-left: 0; }
            .main-content.icon-only { margin-left: 0; }
        }
        .card-header { background: #a5d6a7; color: #1b3b1a; font-weight: bold; }
        .btn-success { background: #388e3c; border-color: #2e7d32; }
        .btn-success:hover { background: #2e7d32; border-color: #1b5e20; }
        .badge-received { background: #4caf50; }
        .badge-in_progress { background: #ffa726; }
        .badge-results_entered { background: #42a5f5; }
        .badge-approved { background: #66bb6a; }
        .badge-reported { background: #00897b; }
        .badge-pending { background: #9e9e9e; }
        .badge-entered { background: #ffa726; }
        .badge-rejected { background: #f44336; }
        .table-responsive { overflow-x: auto; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-custom fixed-top">
        <div class="container-fluid">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/moe-logo.png') }}" alt="MOE" onerror="this.style.display='none';">
                <i class="fas fa-leaf" style="color:#fff; font-size:1.5rem; display:none;" id="logoFallback"></i>
                MOE LIMS
            </a>
            <ul class="navbar-nav ms-auto">
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-id-card"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
                @endauth
            </ul>
        </div>
    </nav>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="sidebar" id="sidebar">
        <nav class="nav flex-column">
            @auth
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
                @if(in_array(auth()->user()->role, ['admin','technician','receptionist']))
                    <a class="nav-link {{ request()->routeIs('samples.*') ? 'active' : '' }}" href="{{ route('samples.index') }}">
                        <i class="fas fa-clipboard-list"></i> <span>Work Orders</span>
                    </a>
                @endif
                @if(in_array(auth()->user()->role, ['admin','approver']))
                    <a class="nav-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}" href="{{ route('approvals.pending') }}">
                        <i class="fas fa-check-double"></i> <span>Approvals</span>
                    </a>
                @endif
                @if(auth()->user()->role == 'admin')
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="fas fa-users"></i> <span>Users</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <i class="fas fa-cog"></i> <span>Settings</span>
                    </a>
                @endif
            @endauth
        </nav>
        <div class="sidebar-footer"><p>Created by: KAO NGUONNORA</p></div>
    </div>

    <div class="main-content" id="mainContent">
        @if(session('success')) <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div> @endif
        @if(session('error')) <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div> @endif
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const toggle = document.getElementById('sidebarToggle');
            let isIconOnly = false, isMobile = window.innerWidth <= 768;

            function apply() {
                if (isMobile) {
                    if (sidebar.classList.contains('open')) {
                        sidebar.classList.remove('icon-only');
                        main.classList.remove('icon-only','closed');
                        overlay.classList.add('active');
                    } else {
                        sidebar.classList.remove('icon-only','open');
                        main.classList.add('closed');
                        overlay.classList.remove('active');
                    }
                } else {
                    if (isIconOnly) {
                        sidebar.classList.add('icon-only');
                        main.classList.add('icon-only');
                        main.classList.remove('closed');
                        sidebar.classList.remove('closed','open');
                    } else {
                        sidebar.classList.remove('icon-only');
                        main.classList.remove('icon-only','closed');
                        sidebar.classList.remove('closed','open');
                    }
                    overlay.classList.remove('active');
                }
            }

            toggle.addEventListener('click', function() {
                if (isMobile) {
                    sidebar.classList.toggle('open');
                    apply();
                } else {
                    isIconOnly = !isIconOnly;
                    apply();
                }
            });

            overlay.addEventListener('click', function() {
                if (isMobile) {
                    sidebar.classList.remove('open');
                    apply();
                }
            });

            window.addEventListener('resize', function() {
                const newMobile = window.innerWidth <= 768;
                if (newMobile !== isMobile) {
                    isMobile = newMobile;
                    if (isMobile) {
                        sidebar.classList.remove('icon-only','open');
                        main.classList.remove('icon-only');
                        main.classList.add('closed');
                        overlay.classList.remove('active');
                    } else {
                        isIconOnly = false;
                        sidebar.classList.remove('icon-only','open','closed');
                        main.classList.remove('icon-only','closed');
                        overlay.classList.remove('active');
                    }
                    apply();
                }
            });

            isMobile = window.innerWidth <= 768;
            if (isMobile) {
                sidebar.classList.remove('icon-only','open');
                main.classList.add('closed');
                overlay.classList.remove('active');
            } else {
                isIconOnly = false;
                sidebar.classList.remove('icon-only','open','closed');
                main.classList.remove('icon-only','closed');
            }
            apply();
        });
    </script>
    @stack('scripts')
</body>
</html>