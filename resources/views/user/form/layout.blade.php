<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'SBL Registration - Student Portal')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('uploads/logo/hsbl.png') }}" type="image/png" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1565c0;
            --primary-gradient: linear-gradient(135deg, #1565c0 0%, #1e88e5 100%);
            --secondary-gradient: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            --accent-color: #42a5f5;
            --light-bg: #f8fafc;
            --text-dark: #37474f;
            --text-light: #546e7a;
            --success-color: #2e7d32;
            --warning-color: #f57c00;
            --danger-color: #d32f2f;
        }
        
        body {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
        }
        
        /* Navbar Styles */
        .navbar {
            background: var(--primary-gradient) !important;
            padding: 0.5rem 0;
        }
        
        .navbar .container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }
        
        /* Brand Section */
        .navbar-brand-section {
            display: flex;
            align-items: center;
        }
        
        .logo-container {
            background: white;
            padding: 6px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        
        .logo-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }
        
        .logo-img {
            height: 32px;
            width: auto;
            border-radius: 6px;
        }
        
        @media (min-width: 768px) {
            .logo-img {
                height: 40px;
            }
        }
        
        .navbar-brand {
            font-size: 1.1rem;
            margin-left: 8px;
            color: white !important;
        }
        
        @media (min-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
                margin-left: 12px;
            }
        }
        
        /* Desktop Navigation */
        .desktop-nav {
            display: none !important;
        }
        
        @media (min-width: 992px) {
            .desktop-nav {
                display: flex !important;
                margin-left: 2rem;
                margin-right: auto;
            }
            
            .desktop-nav .nav-link {
                color: rgba(255, 255, 255, 0.9);
                padding: 8px 16px !important;
                border-radius: 8px;
                transition: all 0.3s;
            }
            
            .desktop-nav .nav-link:hover {
                background: rgba(255, 255, 255, 0.15);
                color: white;
            }
            
            .desktop-nav .nav-link.active {
                background: rgba(255, 255, 255, 0.2);
                color: white;
                font-weight: 600;
            }
            
            .desktop-nav .nav-link i {
                margin-right: 6px;
            }
        }
        
        /* User Menu */
        .user-menu-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }
        
        @media (min-width: 992px) {
            .user-menu-wrapper {
                gap: 15px;
            }
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* User Profile */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 10px;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .user-profile:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        
        @media (min-width: 992px) {
            .user-profile {
                padding: 8px 15px;
                gap: 10px;
            }
        }
        
        /* Avatar Placeholder */
        .avatar-placeholder {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 1rem;
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-transform: uppercase;
        }
        
        @media (min-width: 992px) {
            .avatar-placeholder {
                width: 42px;
                height: 42px;
                font-size: 1.1rem;
            }
        }
        
        /* User Info */
        .user-info {
            display: none;
        }
        
        @media (min-width: 1200px) {
            .user-info {
                display: flex;
                flex-direction: column;
            }
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: white;
            margin-bottom: 2px;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.15);
            padding: 2px 8px;
            border-radius: 20px;
            display: inline-block;
        }
        
        /* Logout Button - Desktop Only */
        .logout-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
            justify-content: center;
        }
        
        @media (min-width: 768px) {
            .logout-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
                gap: 8px;
                min-width: 100px;
            }
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .logout-btn span {
            display: none;
        }
        
        @media (min-width: 576px) {
            .logout-btn span {
                display: inline;
            }
        }
        
        /* Navbar Toggler */
        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 8px 12px;
            margin-left: 10px;
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
        }
        
        /* Mobile Navigation */
        .mobile-nav {
            width: 100%;
            display: none;
        }
        
        @media (max-width: 991px) {
            .mobile-nav.show {
                display: block;
                margin-top: 15px;
                background: var(--secondary-gradient);
                padding: 20px;
                border-radius: 16px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                max-height: calc(100vh - 100px);
                overflow-y: auto;
            }
            
            /* Profile Section in Mobile Nav */
            .mobile-profile-section {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 20px;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            .mobile-profile-header {
                display: flex;
                align-items: center;
                gap: 15px;
            }
            
            .mobile-avatar-large {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                color: white;
                font-size: 1.5rem;
                background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
                border: 3px solid rgba(255, 255, 255, 0.4);
                text-transform: uppercase;
            }
            
            .mobile-profile-info h5 {
                color: white;
                font-weight: 600;
                margin-bottom: 4px;
                font-size: 1rem;
            }
            
            .mobile-profile-info p {
                color: rgba(255, 255, 255, 0.9);
                font-size: 0.85rem;
                margin-bottom: 4px;
                word-break: break-word;
            }
            
            .mobile-profile-badge {
                display: inline-block;
                background: rgba(255, 255, 255, 0.2);
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 0.75rem;
                color: white;
            }
            
            /* Menu Items in Mobile Nav */
            .mobile-menu-section {
                margin-bottom: 20px;
            }
            
            .mobile-menu-title {
                color: rgba(255, 255, 255, 0.7);
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                margin-bottom: 10px;
                padding-left: 12px;
            }
            
            .mobile-menu-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 16px;
                color: white;
                text-decoration: none;
                border-radius: 10px;
                transition: all 0.3s;
                margin-bottom: 4px;
                background: rgba(255, 255, 255, 0.05);
            }
            
            .mobile-menu-item:hover {
                background: rgba(255, 255, 255, 0.15);
                transform: translateX(5px);
            }
            
            .mobile-menu-item i {
                width: 24px;
                text-align: center;
                font-size: 1.1rem;
            }
            
            .mobile-menu-item.active {
                background: rgba(255, 255, 255, 0.2);
                font-weight: 600;
                border-left: 4px solid white;
            }
            
            .mobile-logout-btn {
                width: 100%;
                padding: 14px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 10px;
                color: white;
                font-weight: 600;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                transition: all 0.3s;
                margin-top: 20px;
            }
            
            .mobile-logout-btn:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: translateY(-2px);
            }
        }
        
        /* Dropdown Menu - Hanya untuk Desktop */
        .dropdown-menu-custom {
            display: none;
            border: none;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            padding: 12px 0;
            min-width: 280px;
            margin-top: 15px !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            z-index: 9999;
        }
        
        @media (min-width: 992px) {
            .dropdown-menu-custom.show {
                display: block;
                position: absolute;
                inset: 0px 0px auto auto;
                transform: translate(0px, 50px);
            }
        }
        
        .dropdown-profile-preview {
            padding: 20px;
            background: linear-gradient(135deg, #f5f9ff 0%, #e8f1fe 100%);
            border-radius: 12px;
            margin: 0 12px 12px 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid rgba(66, 165, 245, 0.3);
        }
        
        .dropdown-avatar-placeholder {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 1.3rem;
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            border: 3px solid white;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-transform: uppercase;
        }
        
        .dropdown-item-custom {
            padding: 14px 20px;
            color: var(--text-dark);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 14px;
            border-left: 4px solid transparent;
            font-size: 0.95rem;
            text-decoration: none;
        }
        
        .dropdown-item-custom:hover {
            background: #e3f2fd;
            color: var(--primary-color);
            border-left-color: var(--accent-color);
            padding-left: 26px;
        }
        
        .dropdown-item-custom i {
            width: 22px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .dropdown-divider-custom {
            margin: 8px 0;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .dropdown-user-name {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 1.1rem;
            margin-bottom: 4px;
        }
        
        .dropdown-user-email {
            font-size: 0.85rem;
            color: var(--text-light);
        }
        
        /* Footer */
        .footer-custom {
            background: var(--primary-gradient);
            color: white;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Hide desktop logout on mobile */
        @media (max-width: 991px) {
            .desktop-logout {
                display: none !important;
            }
            
            /* Di mobile, user-profile tidak membuka dropdown */
            .user-profile {
                cursor: default;
            }
            
            .user-profile:hover {
                transform: none;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <!-- Brand Section -->
            <div class="navbar-brand-section">
                <a href="{{ route('form.team.choice') }}" class="d-flex align-items-center text-decoration-none">
                    <div class="logo-container">
                        <img src="{{ asset('uploads/logo/hsbl.png') }}" alt="SBL Riau Pos Logo" class="logo-img" />
                    </div>
                    <span class="navbar-brand fw-bold d-none d-sm-inline">SBL Student Portal</span>
                    <span class="navbar-brand fw-bold d-sm-none">SBL</span>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="desktop-nav">
                <ul class="navbar-nav flex-row">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('form.team.choice') ? 'active' : '' }}" href="{{ route('form.team.choice') }}">
                            <i class="fas fa-home"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('form.team.create') ? 'active' : '' }}" href="{{ route('form.team.create') }}">
                            <i class="fas fa-plus-circle"></i>Create
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('form.team.join') ? 'active' : '' }}" href="{{ route('form.team.join') }}">
                            <i class="fas fa-user-plus"></i>Join
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- User Menu -->
            <div class="user-menu-wrapper">
                <div class="user-menu">
                    @if(auth()->check())
                        @php
                            $user = auth()->user();
                            $nameParts = explode(' ', trim($user->name));
                            $initials = '';
                            
                            if (count($nameParts) >= 2) {
                                $initials = strtoupper(substr($nameParts[0], 0, 1)) . strtoupper(substr($nameParts[1], 0, 1));
                            } else {
                                $name = $nameParts[0];
                                $initials = strlen($name) >= 2 ? strtoupper(substr($name, 0, 2)) : strtoupper(substr($name, 0, 1)) . '?';
                            }
                            
                            if (empty($initials) || trim($initials) === '') {
                                $initials = strtoupper(substr($user->email, 0, 1)) . 'U';
                            }
                        @endphp
                        
                        <!-- Profile Dropdown - BISA DIKLIK DI DESKTOP -->
                        <div class="dropdown" id="userDropdown">
                            <div class="user-profile" id="userProfileTrigger" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar-placeholder">{{ $initials }}</div>
                                <div class="user-info d-none d-xl-block">
                                    <span class="user-name">{{ $user->name }}</span>
                                    <span class="user-role">Student</span>
                                </div>
                                <i class="fas fa-chevron-down text-white d-none d-lg-inline"></i>
                            </div>
                            
                            <!-- Dropdown Menu - HANYA MUNCUL SAAT DIKLIK -->
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                <li>
                                    <div class="dropdown-profile-preview">
                                        <div class="dropdown-avatar-placeholder">{{ $initials }}</div>
                                        <div class="dropdown-user-info">
                                            <div class="dropdown-user-name">{{ $user->name }}</div>
                                            <div class="dropdown-user-email">{{ $user->email }}</div>
                                            <div class="mt-2">
                                                <span class="badge bg-opacity-15 text-primary py-1 px-3" style="background-color: rgba(21,101,192,0.1); font-size: 0.75rem; border-radius: 20px;">
                                                    <i class="fas fa-user-graduate me-1"></i>Student
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider dropdown-divider-custom mx-3"></li>
                                <li>
                                    <a class="dropdown-item-custom" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-edit text-primary"></i>
                                        <span>Edit Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item-custom" href="{{ route('event.histories') }}">
                                        <i class="fas fa-history text-info"></i>
                                        <span>My Event Histories</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Desktop Logout Button -->
                        <div class="desktop-logout">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Untuk guest user -->
                        <div class="user-profile">
                            <div class="avatar-placeholder">G</div>
                            <div class="user-info d-none d-xl-block">
                                <span class="user-name">Guest</span>
                                <span class="user-role">Visitor</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Mobile Toggle Button -->
                    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation - SEMUA MENU ADA DI SINI (NAVIGASI + PROFIL) -->
            <div class="collapse mobile-nav" id="mobileNav">
                @if(auth()->check())
                    @php
                        $user = auth()->user();
                        $nameParts = explode(' ', trim($user->name));
                        $initials = '';
                        
                        if (count($nameParts) >= 2) {
                            $initials = strtoupper(substr($nameParts[0], 0, 1)) . strtoupper(substr($nameParts[1], 0, 1));
                        } else {
                            $name = $nameParts[0];
                            $initials = strlen($name) >= 2 ? strtoupper(substr($name, 0, 2)) : strtoupper(substr($name, 0, 1)) . '?';
                        }
                        
                        if (empty($initials) || trim($initials) === '') {
                            $initials = strtoupper(substr($user->email, 0, 1)) . 'U';
                        }
                    @endphp
                    
                    <!-- Profile Section -->
                    <div class="mobile-profile-section">
                        <div class="mobile-profile-header">
                            <div class="mobile-avatar-large">{{ $initials }}</div>
                            <div class="mobile-profile-info">
                                <h5>{{ $user->name }}</h5>
                                <p>{{ $user->email }}</p>
                                <span class="mobile-profile-badge">
                                    <i class="fas fa-user-graduate me-1"></i>Student
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Menu -->
                    <div class="mobile-menu-section">
                        <div class="mobile-menu-title">NAVIGASI</div>
                        <a class="mobile-menu-item {{ request()->routeIs('form.team.choice') ? 'active' : '' }}" href="{{ route('form.team.choice') }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a class="mobile-menu-item {{ request()->routeIs('form.team.create') ? 'active' : '' }}" href="{{ route('form.team.create') }}">
                            <i class="fas fa-plus-circle"></i>
                            <span>Create New Team</span>
                        </a>
                        <a class="mobile-menu-item {{ request()->routeIs('form.team.join') ? 'active' : '' }}" href="{{ route('form.team.join') }}">
                            <i class="fas fa-user-plus"></i>
                            <span>Join Team</span>
                        </a>
                    </div>
                    
                    <!-- Profile Menu (dari dropdown) -->
                    <div class="mobile-menu-section">
                        <div class="mobile-menu-title">PROFIL</div>
                        <a class="mobile-menu-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit"></i>
                            <span>Edit Profile</span>
                        </a>
                        <a class="mobile-menu-item" href="{{ route('event.histories') }}">
                            <i class="fas fa-history"></i>
                            <span>My Event Histories</span>
                        </a>
                    </div>
                    
                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="mobile-logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                @else
                    <!-- Untuk guest user -->
                    <div class="mobile-profile-section">
                        <div class="mobile-profile-header">
                            <div class="mobile-avatar-large">G</div>
                            <div class="mobile-profile-info">
                                <h5>Guest</h5>
                                <p>Silakan login untuk mengakses fitur</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="container">
        @yield('content')
    </div>
    
    <!-- Footer -->
    <footer class="mt-5 py-4 footer-custom">
        <div class="container">
            <div class="text-center">
                <div style="color: rgba(255, 255, 255, 0.8);">
                    <p class="mb-1">&copy; {{ date('Y') }} Riau Pos - Student Basketball League. All Rights Reserved.</p>
                    <p class="d-flex align-items-center justify-content-center gap-1 mt-2 flex-wrap">
                        <span>Developed with</span>
                        <i class="fas fa-heart" style="color: #ff6b6b;"></i>
                        <span>by : Mutia Rizkianti | Wafiq Wardatul Khairani</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.style.display = 'none', 500);
                }, 5000);
            });
            
            // Close mobile nav when clicking links
            document.querySelectorAll('.mobile-menu-item').forEach(link => {
                link.addEventListener('click', function() {
                    const mobileNav = document.getElementById('mobileNav');
                    if (mobileNav?.classList.contains('show')) {
                        const bsCollapse = new bootstrap.Collapse(mobileNav, {
                            toggle: false
                        });
                        bsCollapse.hide();
                    }
                });
            });
            
            // Tutup mobile nav saat klik di luar
            document.addEventListener('click', function(event) {
                const mobileNav = document.getElementById('mobileNav');
                const toggler = document.querySelector('.navbar-toggler');
                
                if (mobileNav?.classList.contains('show') && 
                    !mobileNav.contains(event.target) && 
                    !toggler?.contains(event.target)) {
                    const bsCollapse = new bootstrap.Collapse(mobileNav, {
                        toggle: false
                    });
                    bsCollapse.hide();
                }
            });
            
            // Fungsi untuk mengatur perilaku dropdown berdasarkan ukuran layar
            function setupDropdownBehavior() {
                const isMobile = window.innerWidth < 992;
                const dropdownTrigger = document.getElementById('userProfileTrigger');
                const dropdownMenu = document.querySelector('.dropdown-menu-custom');
                
                if (isMobile) {
                    // Di mobile, nonaktifkan dropdown
                    if (dropdownTrigger) {
                        dropdownTrigger.removeAttribute('data-bs-toggle');
                        dropdownTrigger.style.cursor = 'default';
                        
                        // Sembunyikan dropdown jika terbuka
                        if (dropdownMenu?.classList.contains('show')) {
                            dropdownMenu.classList.remove('show');
                        }
                    }
                } else {
                    // Di desktop, aktifkan dropdown
                    if (dropdownTrigger && !dropdownTrigger.hasAttribute('data-bs-toggle')) {
                        dropdownTrigger.setAttribute('data-bs-toggle', 'dropdown');
                        dropdownTrigger.style.cursor = 'pointer';
                    }
                }
            }
            
            // Panggil saat load
            setupDropdownBehavior();
            
            // Panggil saat resize
            window.addEventListener('resize', setupDropdownBehavior);
            
            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function(event) {
                const dropdown = document.querySelector('.dropdown-menu-custom.show');
                const trigger = document.getElementById('userProfileTrigger');
                
                if (dropdown && trigger && !trigger.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.remove('show');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>