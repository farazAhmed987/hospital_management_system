<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Care-Connect') }} - @yield('title', 'Welcome')</title>

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
            --bg-dark: #0f172a;
            --card-dark: #1e293b;
            --border-dark: #334155;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.15) 0%, transparent 50%);
            pointer-events: none;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background: var(--card-dark);
            border: 1px solid var(--border-dark);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .auth-card:hover {
            box-shadow: 0 30px 60px rgba(99, 102, 241, 0.3);
            transform: translateY(-5px);
        }

        .auth-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
            color: #fff;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .auth-header h2 {
            margin-bottom: 0.5rem;
            font-weight: 800;
            font-size: 2rem;
            position: relative;
            z-index: 1;
        }

        .auth-header p {
            opacity: 0.95;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
            font-weight: 500;
        }

        .auth-body {
            padding: 2.5rem;
            background: var(--card-dark);
        }

        .form-control, .form-select {
            background: var(--bg-dark);
            border: 1px solid var(--border-dark);
            color: #f1f5f9;
            border-radius: 0.75rem;
            padding: 0.875rem 1.25rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            background: var(--bg-dark);
            border-color: var(--primary-color);
            color: #f1f5f9;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-label {
            color: #cbd5e1;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background: var(--bg-dark);
            border: 1px solid var(--border-dark);
            color: #94a3b8;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
            border: none;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #7c3aed 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }

        .auth-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .auth-link:hover {
            color: #8b5cf6;
            text-decoration: underline;
        }

        .text-muted {
            color: #94a3b8 !important;
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border-left: 4px solid #ef4444;
        }

        .theme-toggle-btn {
            position: fixed;
            top: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid var(--border-dark);
            background: var(--card-dark);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .theme-toggle-btn:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: rotate(180deg) scale(1.1);
        }

        /* Light Theme */
        body.light-theme {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        body.light-theme::before {
            background: 
                radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.08) 0%, transparent 50%);
        }

        body.light-theme .auth-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
        }

        body.light-theme .auth-body {
            background: #ffffff;
        }

        body.light-theme .form-control,
        body.light-theme .form-select {
            background: #f8fafc;
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

        body.light-theme .form-label {
            color: #475569;
        }

        body.light-theme .input-group-text {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #64748b;
        }

        body.light-theme .text-muted {
            color: #64748b !important;
        }

        body.light-theme .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        body.light-theme .theme-toggle-btn {
            background: #ffffff;
            color: #1e293b;
            border-color: #e2e8f0;
        }

        @media (max-width: 576px) {
            .auth-card {
                border-radius: 1rem;
            }
            
            .auth-header {
                padding: 2rem 1.5rem;
            }
            
            .auth-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Theme Toggle Button -->
    <button class="theme-toggle-btn" id="themeToggle" title="Toggle theme">
        <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
    </button>

    <div class="auth-wrapper">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
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
</body>
</html>
