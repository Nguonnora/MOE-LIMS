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

        .sidebar-toggle, .pin-toggle {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.3rem;
            cursor: pointer;
            margin-right: 8px;
        }
        .sidebar-toggle:focus, .pin-toggle:focus { outline: none; }
        .pin-toggle:hover { color: #a5d6a7; }
        .pin-toggle.pinned { color: #ffd54f; }

        @media (max-width: 768px) {
            .pin-toggle { display: none; }
        }

        .sidebar {
            position: fixed;
            top: 56px;
            left: 0;
            bottom: 0;
            width: 260px;
            background: #1b5e20;
            padding: 20px 0;
            z-index: 1040;
            overflow: hidden;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            transition: width 0.3s ease, transform 0.3s ease;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .sidebar-footer p {
            display: none;
        }
        .sidebar.pinned {
            width: 260px;
        }
        .sidebar.pinned.collapsed {
            width: 260px;
        }

        .sidebar .nav {
            flex: 1;
            flex-direction: column;
            padding: 0 10px;
        }
        .sidebar .nav-link {
            color: #c8e6c9;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: background 0.2s;
            white-space: nowrap;
            overflow: hidden;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #2e7d32;
            color: #fff;
        }
        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
        }
        .sidebar .nav-link span {
            font-size: 0.95rem;
        }

        .sidebar-footer {
            padding: 10px 15px;
            border-top: 1px solid #2e7d32;
            color: #a5d6a7;
            font-size: 0.7rem;
            text-align: center;
            white-space: normal;
            line-height: 1.2;
        }
        .sidebar-footer a { color: #a5d6a7; text-decoration: none; }
        .sidebar-footer a:hover { text-decoration: underline; color: #fff; }

        .main-content {
            margin-left: 260px;
            padding: 86px 30px 30px;
            transition: margin-left 0.3s ease;
            background: #f0f7f0;
            min-height: calc(100vh - 56px);
        }
        .sidebar.collapsed ~ .main-content {
            margin-left: 70px;
        }
        .sidebar.pinned ~ .main-content {
            margin-left: 260px;
        }

        .sidebar-overlay { display: none; }

        @media (max-width: 768px) {
            .sidebar {
                width: 280px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar.collapsed {
                width: 280px;
            }
            .sidebar.pinned {
                width: 280px;
            }
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
            .sidebar-overlay.active {
                display: block;
            }
            .main-content {
                margin-left: 0 !important;
                padding: 76px 15px 20px;
            }
            .sidebar-footer {
                font-size: 0.75rem;
            }
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
            <button class="pin-toggle" id="pinToggle" aria-label="Pin sidebar">
                <i class="fas fa-thumbtack"></i>
            </button>
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

                <a class="nav-link {{ request()->routeIs('work-orders.*') ? 'active' : '' }}" href="{{ route('work-orders.index') }}">
                    <i class="fas fa-clipboard-list"></i> <span>Work Orders</span>
                </a>

                <a class="nav-link {{ request()->routeIs('samples.*') ? 'active' : '' }}" href="{{ route('samples.index') }}">
                    <i class="fas fa-vial"></i> <span>Sample Registration</span>
                </a>

                @if(in_array(auth()->user()->role, ['admin', 'approver']))
                    <a class="nav-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}" href="{{ route('approvals.pending') }}">
                        <i class="fas fa-check-double"></i> <span>Approvals</span>
                    </a>
                @endif

                @if(auth()->user()->role == 'admin')
                    <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
                        <i class="fas fa-address-book"></i> <span>Clients</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('test-parameters.*') ? 'active' : '' }}" href="{{ route('test-parameters.index') }}">
                        <i class="fas fa-flask"></i> <span>Test Parameters</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="fas fa-users"></i> <span>Users</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <i class="fas fa-cog"></i> <span>Settings</span>
                    </a>
                @endif
            @endauth
        </nav>
        <div class="sidebar-footer">
            &copy; {{ date('Y') }} <a href="https://moe.gov.kh" target="_blank">Ministry of Environment</a> – All rights reserved.
            <br>
            <small>Created by: KAO NGUONNORA</small>
        </div>
    </div>

    <div class="main-content" id="mainContent">
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
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const hamburger = document.getElementById('sidebarToggle');
            const pinToggle = document.getElementById('pinToggle');
            const pinIcon = pinToggle.querySelector('i');

            let isMobile = window.innerWidth <= 768;

            function updatePinIcon() {
                if (sidebar.classList.contains('pinned')) {
                    pinIcon.className = 'fas fa-thumbtack';
                    pinToggle.classList.add('pinned');
                } else {
                    pinIcon.className = 'fas fa-thumbtack-slash';
                    pinToggle.classList.remove('pinned');
                }
            }

            hamburger.addEventListener('click', function(e) {
                e.stopPropagation();
                if (isMobile) {
                    sidebar.classList.toggle('open');
                    overlay.classList.toggle('active');
                    return;
                }
                if (sidebar.classList.contains('pinned')) {
                    sidebar.classList.remove('pinned');
                    sidebar.classList.add('collapsed');
                    localStorage.setItem('sidebarPinned', 'false');
                } else {
                    sidebar.classList.toggle('collapsed');
                }
                updatePinIcon();
            });

            pinToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                if (isMobile) return;
                if (sidebar.classList.contains('pinned')) {
                    sidebar.classList.remove('pinned');
                    sidebar.classList.remove('collapsed');
                    localStorage.setItem('sidebarPinned', 'false');
                } else {
                    sidebar.classList.add('pinned');
                    sidebar.classList.remove('collapsed');
                    localStorage.setItem('sidebarPinned', 'true');
                }
                updatePinIcon();
            });

            overlay.addEventListener('click', function() {
                if (sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                }
            });

            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (isMobile) {
                        sidebar.classList.remove('open');
                        overlay.classList.remove('active');
                    }
                });
            });

            const savedPin = localStorage.getItem('sidebarPinned');
            if (savedPin === 'true' && !isMobile) {
                sidebar.classList.add('pinned');
                sidebar.classList.remove('collapsed');
            } else {
                sidebar.classList.remove('pinned');
                sidebar.classList.remove('collapsed');
            }
            updatePinIcon();

            window.addEventListener('resize', function() {
                isMobile = window.innerWidth <= 768;
                if (isMobile) {
                    sidebar.classList.remove('pinned', 'collapsed');
                    if (!sidebar.classList.contains('open')) {
                        overlay.classList.remove('active');
                    }
                } else {
                    if (sidebar.classList.contains('open')) {
                        sidebar.classList.remove('open');
                        overlay.classList.remove('active');
                    }
                    const saved = localStorage.getItem('sidebarPinned');
                    if (saved === 'true') {
                        sidebar.classList.add('pinned');
                        sidebar.classList.remove('collapsed');
                    } else {
                        sidebar.classList.remove('pinned');
                        sidebar.classList.remove('collapsed');
                    }
                    updatePinIcon();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>