<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Care-Connect') }} - @yield('title', 'Hospital Management System')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --sidebar-width: 280px;
            --bg-dark: #0f172a;
            --bg-darker: #020617;
            --card-dark: #1e293b;
            --border-dark: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) var(--bg-darker);
        }

        *::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        *::-webkit-scrollbar-track {
            background: var(--bg-darker);
        }

        *::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        *::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-primary);
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border-right: 1px solid var(--border-dark);
            padding-top: 0;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.4);
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid var(--border-dark);
            text-align: center;
            background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
        }

        .sidebar-brand h4 {
            color: #fff;
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .sidebar-brand small {
            color: rgba(255,255,255,0.9);
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar .nav-link {
            color: var(--text-secondary);
            padding: 1rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
            margin: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), transparent);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .sidebar .nav-link:hover {
            color: var(--text-primary);
            background-color: rgba(99, 102, 241, 0.1);
            border-left-color: transparent;
            transform: translateX(5px);
        }

        .sidebar .nav-link:hover::before {
            width: 100%;
        }

        .sidebar .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-left-color: transparent;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .sidebar .nav-link i {
            margin-right: 1rem;
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar-section {
            padding: 1.5rem 1.5rem 0.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: var(--bg-dark);
        }

        .top-navbar {
            background: linear-gradient(135deg, var(--card-dark) 0%, #1e293b 100%);
            border-bottom: 1px solid var(--border-dark);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            padding: 1rem 2rem;
            backdrop-filter: blur(10px);
        }

        .content-wrapper {
            padding: 2rem;
        }

        .card {
            background: var(--card-dark);
            border: 1px solid var(--border-dark);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            border-radius: 1rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.2);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, transparent 100%);
            border-bottom: 1px solid var(--border-dark);
            font-weight: 600;
            color: var(--text-primary);
            padding: 1.25rem 1.5rem;
        }

        .card-body {
            color: var(--text-primary);
        }

        .stats-card {
            border-radius: 1rem;
            padding: 2rem;
            color: #fff;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: none;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transition: transform 0.6s ease;
        }

        .stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 40px rgba(0,0,0,0.4);
        }

        .stats-card:hover::before {
            transform: translate(-25%, -25%);
        }

        .stats-card.bg-primary { 
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
        }
        .stats-card.bg-success { 
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
        }
        .stats-card.bg-warning { 
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.3);
        }
        .stats-card.bg-info { 
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.3);
        }
        .stats-card.bg-danger { 
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 20px rgba(239, 68, 68, 0.3);
        }

        .stats-card h3 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .stats-card p {
            margin-bottom: 0;
            opacity: 0.95;
            font-weight: 500;
            font-size: 1rem;
        }

        .stats-card i {
            font-size: 3rem;
            opacity: 0.25;
        }

        .table {
            color: var(--text-primary);
        }

        .table th {
            font-weight: 600;
            color: var(--text-primary);
            border-top: none;
            background: rgba(99, 102, 241, 0.1);
            border-bottom: 2px solid var(--primary-color);
            padding: 1rem;
        }

        .table td {
            border-color: var(--border-dark);
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(99, 102, 241, 0.05);
            transform: scale(1.01);
        }

        .btn {
            font-weight: 500;
            border-radius: 0.5rem;
            padding: 0.625rem 1.25rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #3730a3 100%);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }

        .btn-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-outline-secondary {
            border: 2px solid var(--secondary-color);
            color: var(--text-secondary);
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background: var(--secondary-color);
            color: white;
        }

        .btn-outline-success {
            border: 2px solid var(--success-color);
            color: var(--success-color);
            background: transparent;
        }

        .btn-outline-success:hover {
            background: var(--success-color);
            color: white;
        }

        .btn-outline-danger {
            border: 2px solid var(--danger-color);
            color: var(--danger-color);
            background: transparent;
        }

        .btn-outline-danger:hover {
            background: var(--danger-color);
            color: white;
        }

        .btn-outline-info {
            border: 2px solid var(--info-color);
            color: var(--info-color);
            background: transparent;
        }

        .btn-outline-info:hover {
            background: var(--info-color);
            color: white;
        }

        .btn-outline-warning {
            border: 2px solid var(--warning-color);
            color: var(--warning-color);
            background: transparent;
        }

        .btn-outline-warning:hover {
            background: var(--warning-color);
            color: white;
        }

        .btn-outline-light {
            border: 2px solid rgba(255,255,255,0.3);
            color: rgba(255,255,255,0.9);
            background: transparent;
        }

        .btn-outline-light:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            border-color: rgba(255,255,255,0.5);
        }

        .btn-action {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .badge {
            padding: 0.5rem 1rem;
            font-weight: 600;
            border-radius: 0.5rem;
        }

        .badge-role {
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
        }

        .form-control, .form-select {
            background: var(--card-dark);
            border: 1px solid var(--border-dark);
            color: var(--text-primary);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            background: var(--card-dark);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background: var(--card-dark);
            border: 1px solid var(--border-dark);
            color: var(--text-secondary);
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            backdrop-filter: blur(10px);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.1) 100%);
            color: #6ee7b7;
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.1) 100%);
            color: #fca5a5;
            border-left: 4px solid var(--danger-color);
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.1) 100%);
            color: #fcd34d;
            border-left: 4px solid var(--warning-color);
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.2) 0%, rgba(8, 145, 178, 0.1) 100%);
            color: #67e8f9;
            border-left: 4px solid var(--info-color);
        }

        .user-dropdown img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }

        .dropdown-menu {
            background: var(--card-dark);
            border: 1px solid var(--border-dark);
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            border-radius: 0.75rem;
            padding: 0.5rem;
        }

        .dropdown-item {
            color: var(--text-primary);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: rgba(99, 102, 241, 0.2);
            color: var(--text-primary);
            transform: translateX(5px);
        }

        .dropdown-divider {
            border-color: var(--border-dark);
        }

        .page-item .page-link {
            background: var(--card-dark);
            border: 1px solid var(--border-dark);
            color: var(--text-primary);
        }

        .page-item .page-link:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        h1, h2, h3, h4, h5, h6 {
            color: var(--text-primary);
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        .theme-toggle-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--border-dark);
            background: var(--card-dark);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 1rem;
        }

        .theme-toggle-btn:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: rotate(180deg) scale(1.1);
        }

        /* Light Theme Variables */
        body.light-theme {
            --bg-dark: #f8fafc;
            --bg-darker: #e2e8f0;
            --card-dark: #ffffff;
            --border-dark: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
        }

        body.light-theme .sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-right: 1px solid #e2e8f0;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.05);
        }

        body.light-theme .sidebar-brand {
            background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
        }

        body.light-theme .sidebar .nav-link {
            color: #64748b;
        }

        body.light-theme .sidebar .nav-link:hover {
            color: #1e293b;
        }

        body.light-theme .sidebar .nav-link.active {
            color: #fff;
        }

        body.light-theme .sidebar-section {
            color: #94a3b8;
        }

        body.light-theme .top-navbar {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        body.light-theme .top-navbar h5,
        body.light-theme .top-navbar .btn-link {
            color: #1e293b !important;
        }

        body.light-theme .theme-toggle-btn {
            border-color: #e2e8f0;
            background: #ffffff;
            color: #1e293b;
        }

        body.light-theme .card {
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        body.light-theme .card:hover {
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.15);
        }

        body.light-theme .table th {
            background: rgba(99, 102, 241, 0.05);
        }

        body.light-theme .table tbody tr:hover {
            background: rgba(99, 102, 241, 0.03);
        }

        body.light-theme .dropdown-menu {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        body.light-theme .dropdown-item {
            color: #1e293b;
        }

        body.light-theme .dropdown-item:hover {
            background: rgba(99, 102, 241, 0.1);
            color: #1e293b;
        }

        body.light-theme .dropdown-divider {
            border-color: #e2e8f0;
        }

        body.light-theme .form-control,
        body.light-theme .form-select {
            background: #ffffff;
            border-color: #e2e8f0;
            color: #1e293b;
        }

        body.light-theme .form-control:focus,
        body.light-theme .form-select:focus {
            background: #ffffff;
            color: #1e293b;
        }

        body.light-theme .form-control::placeholder {
            color: #94a3b8;
        }

        body.light-theme .input-group-text {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #64748b;
        }

        body.light-theme .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
            color: #059669;
        }

        body.light-theme .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
            color: #dc2626;
        }

        body.light-theme .alert-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.05) 100%);
            color: #d97706;
        }

        body.light-theme .alert-info {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(8, 145, 178, 0.05) 100%);
            color: #0891b2;
        }

        body.light-theme .page-item .page-link {
            background: #ffffff;
            border-color: #e2e8f0;
            color: #1e293b;
        }

        body.light-theme .btn-outline-light {
            border-color: #e2e8f0;
            color: #64748b;
        }

        body.light-theme .btn-outline-light:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-wrapper {
                padding: 1rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-hospital me-2"></i>Care-Connect</h4>
            <small>Hospital Management System</small>
        </div>

        <ul class="nav flex-column mt-3">
            @yield('sidebar-menu')
        </ul>

        <div class="mt-auto p-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="bi bi-box-arrow-left me-2"></i>Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="btn btn-link d-lg-none me-2 text-light" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h5 class="mb-0 text-light">@yield('page-title', 'Dashboard')</h5>
            </div>

            <div class="d-flex align-items-center">
                <!-- Theme Toggle Button -->
                <button class="theme-toggle-btn" id="themeToggle" title="Toggle theme">
                    <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                </button>

                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center text-light" type="button" id="userDropdown" data-bs-toggle="dropdown">
                        <span class="me-2 fw-semibold">{{ auth()->user()->name }}</span>
                        <span class="badge badge-role bg-{{ auth()->user()->role === 'admin' ? 'danger' : (auth()->user()->role === 'doctor' ? 'success' : 'primary') }}">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                        @if(auth()->user()->isPatient())
                            <li><a class="dropdown-item" href="{{ route('patient.profile.show') }}">
                                <i class="bi bi-person me-2"></i>Profile
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-left me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="content-wrapper">
            <!-- Flash Messages -->
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

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;

        // Check for saved theme preference or default to 'dark'
        const currentTheme = localStorage.getItem('theme') || 'dark';
        
        // Apply the saved theme on page load
        if (currentTheme === 'light') {
            body.classList.add('light-theme');
            themeIcon.classList.remove('bi-moon-stars-fill');
            themeIcon.classList.add('bi-sun-fill');
        }

        // Toggle theme on button click
        themeToggle.addEventListener('click', function() {
            body.classList.toggle('light-theme');
            
            if (body.classList.contains('light-theme')) {
                themeIcon.classList.remove('bi-moon-stars-fill');
                themeIcon.classList.add('bi-sun-fill');
                localStorage.setItem('theme', 'light');
            } else {
                themeIcon.classList.remove('bi-sun-fill');
                themeIcon.classList.add('bi-moon-stars-fill');
                localStorage.setItem('theme', 'dark');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
