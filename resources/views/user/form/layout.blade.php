<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'HSBL Registration - Student Portal')</title>
    
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
        
        .form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(21, 101, 192, 0.15);
            margin-top: 30px;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .form-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 25px;
            text-align: center;
        }
        
        .btn-hsbl {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-hsbl:hover {
            background: var(--secondary-gradient);
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(21, 101, 192, 0.25);
            color: white;
        }
        
        .btn-outline-hsbl {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-outline-hsbl:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(66, 165, 245, 0.25);
        }
        
        .emoji-title {
            font-size: 1.2em;
            margin-right: 8px;
        }
        
        .badge-hsbl {
            background: linear-gradient(135deg, var(--success-color) 0%, #43a047 100%);
            color: white;
        }
        
        /* Navbar Layout - Fixed */
        .navbar .container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }
        
        /* Brand and Toggler section */
        .navbar-brand-section {
            display: flex;
            align-items: center;
        }
        
        /* Desktop Navigation - positioned between brand and user menu */
        .desktop-nav {
            display: none !important;
        }
        
        @media (min-width: 992px) {
            .desktop-nav {
                display: flex !important;
                margin-left: 2rem;
                margin-right: auto;
            }
        }
        
        /* User Menu Styles - Always at the end */
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
        
        @media (min-width: 992px) {
            .user-menu {
                gap: 15px;
            }
        }
        
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
        
        @media (min-width: 992px) {
            .user-profile {
                padding: 8px 15px;
                gap: 10px;
            }
        }
        
        .user-profile:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        
        /* Avatar Placeholder Styles - HANYA INISIAL */
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
        
        @media (max-width: 575px) {
            .dropdown-avatar-placeholder {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
        }
        
        .user-avatar {
            display: none !important; /* Sembunyikan semua avatar gambar */
        }
        
        .user-avatar-svg {
            display: none !important; /* Sembunyikan semua avatar SVG */
        }
        
        .avatar-icon {
            display: none !important; /* Sembunyikan avatar icon */
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
            text-align: center;
            display: inline-block;
            width: fit-content;
        }
        
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
        
        /* Mobile Navigation Collapse */
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
            
            .mobile-nav .navbar-nav {
                margin-bottom: 20px;
            }
            
            .mobile-nav .nav-link {
                padding: 12px 16px !important;
                border-radius: 10px;
                margin-bottom: 5px;
                color: white !important;
            }
            
            .mobile-nav .nav-link:hover {
                background: rgba(255, 255, 255, 0.15);
            }
            
            .mobile-nav .nav-link i {
                width: 24px;
                text-align: center;
            }
            
            .mobile-nav .nav-link.active {
                background: rgba(255, 255, 255, 0.2);
                font-weight: 600;
            }
            
            .mobile-user-info {
                border-top: 1px solid rgba(255, 255, 255, 0.2);
                margin-top: 15px;
                padding-top: 15px;
                color: rgba(255, 255, 255, 0.9);
            }
        }
        
        /* Dropdown Menu - Fully Responsive */
        .dropdown-menu-custom {
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
            position: absolute !important;
            right: 0 !important;
            left: auto !important;
        }
        
        @media (max-width: 575px) {
            .dropdown-menu-custom {
                position: fixed !important;
                top: 70px !important;
                left: 15px !important;
                right: 15px !important;
                width: auto !important;
                min-width: auto !important;
                max-width: none !important;
                transform: none !important;
                margin: 0 !important;
                border-radius: 20px;
                max-height: calc(100vh - 90px);
                overflow-y: auto;
            }
            
            .dropdown-menu-custom.show {
                display: block !important;
            }
            
            .dropdown-menu-custom::after {
                content: '⬇️ Geser ke bawah';
                display: block;
                text-align: center;
                padding: 12px;
                font-size: 0.8rem;
                color: var(--text-light);
                border-top: 1px solid rgba(0, 0, 0, 0.05);
                margin-top: 8px;
            }
        }
        
        @media (min-width: 576px) and (max-width: 991px) {
            .dropdown-menu-custom {
                position: absolute !important;
                right: 0 !important;
                left: auto !important;
                min-width: 320px;
                max-width: 320px;
            }
        }
        
        .dropdown-header-custom {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-light);
            padding: 8px 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            text-decoration: none !important;
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
        
        /* Profile Preview in Dropdown */
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
        
        @media (max-width: 575px) {
            .dropdown-profile-preview {
                padding: 16px;
                gap: 12px;
            }
        }
        
        .dropdown-user-info {
            flex: 1;
            min-width: 0;
        }
        
        .dropdown-user-name {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 1.1rem;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        @media (max-width: 575px) {
            .dropdown-user-name {
                font-size: 1rem;
                white-space: normal;
                word-break: break-word;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .dropdown-user-email {
                white-space: normal;
                word-break: break-word;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        }
        
        /* Navbar Toggle */
        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 8px 12px;
            margin-left: 10px;
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
        }
        
        /* Brand Responsive */
        .navbar-brand {
            font-size: 1.1rem;
            margin-left: 8px;
        }
        
        @media (min-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
                margin-left: 12px;
            }
        }
        
        @media (max-width: 374px) {
            .navbar-brand {
                display: none;
            }
        }
        
        /* Logo Container */
        .logo-container {
            background: white;
            padding: 6px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        
        @media (min-width: 768px) {
            .logo-container {
                padding: 8px;
                border-radius: 12px;
            }
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
        
        /* Desktop Navigation Links */
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
        
        .desktop-nav .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: white;
            border-radius: 3px 3px 0 0;
        }
        
        /* Footer Styles */
        .footer-custom {
            background: var(--primary-gradient);
            color: white;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Card Styles */
        .card-hsbl {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            overflow: hidden;
        }
        
        .card-hsbl:hover {
            transform: translateY(-5px);
        }
        
        .card-header-hsbl {
            background: var(--light-bg);
            border-bottom: 2px solid #e0e0e0;
            padding: 15px 20px;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        /* Alert Styles */
        .alert-hsbl {
            border: none;
            border-radius: 10px;
            border-left: 4px solid var(--accent-color);
        }
        
        /* Progress Bar */
        .progress-hsbl {
            height: 8px;
            border-radius: 4px;
            background: #e0e0e0;
        }
        
        .progress-bar-hsbl {
            background: var(--primary-gradient);
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--primary-gradient); padding: 0.5rem 0;">
        <div class="container">
            <!-- Brand Section - Left -->
            <div class="navbar-brand-section">
                <a href="{{ route('form.team.choice') }}" class="d-flex align-items-center text-decoration-none">
                    <div class="logo-container">
                        <img src="{{ asset('uploads/logo/hsbl.png') }}" 
                             alt="HSBL Riau Pos Logo" 
                             class="logo-img" />
                    </div>
                    <span class="navbar-brand fw-bold d-none d-sm-inline">HSBL Student Portal</span>
                    <span class="navbar-brand fw-bold d-sm-none">HSBL</span>
                </a>
            </div>
            
            <!-- Desktop Navigation - Center Left -->
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
            
            <!-- User Menu Section - Right (Always at the end) -->
            <div class="user-menu-wrapper">
                <div class="user-menu">
                    <!-- User Profile Dropdown - HANYA MENAMPILKAN INISIAL -->
                    <div class="dropdown" id="user-dropdown">
                        <div class="user-profile" data-bs-toggle="dropdown" aria-expanded="false" id="userProfileTrigger">
                            @if(auth()->check())
                                @php
                                    $user = auth()->user();
                                    
                                    // Buat inisial dari nama user
                                    $initials = '';
                                    $nameParts = explode(' ', trim($user->name));
                                    if (count($nameParts) >= 2) {
                                        // Ambil huruf pertama dari 2 kata pertama
                                        $initials = strtoupper(substr($nameParts[0], 0, 1)) . strtoupper(substr($nameParts[1], 0, 1));
                                    } else {
                                        // Jika hanya 1 kata, ambil 2 huruf pertama
                                        $name = $nameParts[0];
                                        if (strlen($name) >= 2) {
                                            $initials = strtoupper(substr($name, 0, 2));
                                        } else {
                                            // Jika nama terlalu pendek, gunakan huruf pertama + '?'
                                            $initials = strtoupper(substr($name, 0, 1)) . '?';
                                        }
                                    }
                                    
                                    // Jika masih kosong, gunakan huruf pertama email
                                    if (empty($initials) || trim($initials) === '') {
                                        $initials = strtoupper(substr($user->email, 0, 1)) . 'U';
                                    }
                                @endphp
                                
                                <!-- HANYA TAMPILKAN INISIAL - TIDAK ADA AVATAR GAMBAR -->
                                <div class="avatar-placeholder" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);">
                                    {{ $initials }}
                                </div>
                                
                            @else
                                <!-- Untuk guest user -->
                                <div class="avatar-placeholder" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);">
                                    G
                                </div>
                            @endif
                            
                            <div class="user-info d-none d-xl-block">
                                <span class="user-name">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</span>
                                <span class="user-role">{{ auth()->check() && auth()->user()->role ? ucfirst(auth()->user()->role) : 'Student' }}</span>
                            </div>
                            <i class="fas fa-chevron-down text-white"></i>
                        </div>
                        
                        <!-- Dropdown Menu -->
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom" aria-labelledby="userProfileTrigger">
                            <!-- Profile Preview - HANYA INISIAL -->
                            @if(auth()->check())
                                @php
                                    $user = auth()->user();
                                    
                                    // Buat inisial untuk dropdown
                                    $initials = '';
                                    $nameParts = explode(' ', trim($user->name));
                                    if (count($nameParts) >= 2) {
                                        $initials = strtoupper(substr($nameParts[0], 0, 1)) . strtoupper(substr($nameParts[1], 0, 1));
                                    } else {
                                        $name = $nameParts[0];
                                        if (strlen($name) >= 2) {
                                            $initials = strtoupper(substr($name, 0, 2));
                                        } else {
                                            $initials = strtoupper(substr($name, 0, 1)) . '?';
                                        }
                                    }
                                    
                                    if (empty($initials) || trim($initials) === '') {
                                        $initials = strtoupper(substr($user->email, 0, 1)) . 'U';
                                    }
                                @endphp
                                <li>
                                    <div class="dropdown-profile-preview">
                                        <!-- HANYA TAMPILKAN INISIAL DI DROPDOWN -->
                                        <div class="dropdown-avatar-placeholder" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);">
                                            {{ $initials }}
                                        </div>
                                        
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
                            @endif
                            
                            <!-- Dropdown Items -->
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
                            
                            <!-- Mobile-only menu items -->
                            <li class="d-lg-none"><hr class="dropdown-divider dropdown-divider-custom mx-3"></li>
                            <li class="d-lg-none">
                                <a class="dropdown-item-custom" href="{{ route('form.team.choice') }}">
                                    <i class="fas fa-home text-success"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="d-lg-none">
                                <a class="dropdown-item-custom" href="{{ route('form.team.create') }}">
                                    <i class="fas fa-plus-circle text-warning"></i>
                                    <span>Create New Team</span>
                                </a>
                            </li>
                            <li class="d-lg-none">
                                <a class="dropdown-item-custom" href="{{ route('form.team.join') }}">
                                    <i class="fas fa-user-plus text-info"></i>
                                    <span>Join Team</span>
                                </a>
                            </li>
                            
                            <!-- Logout in Dropdown for Mobile -->
                            <li class="d-lg-none"><hr class="dropdown-divider dropdown-divider-custom mx-3"></li>
                            <li class="d-lg-none">
                                <form action="{{ route('logout') }}" method="POST" class="dropdown-item-custom" style="padding: 0;">
                                    @csrf
                                    <button type="submit" class="dropdown-item-custom w-100 border-0 bg-transparent" style="padding: 14px 20px;">
                                        <i class="fas fa-sign-out-alt text-danger"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </li>
                            
                            <!-- Mobile close indicator -->
                            <li class="d-sm-none">
                                <div class="text-center py-2 mt-2 border-top">
                                    <small class="text-muted">
                                        <i class="fas fa-chevron-up me-1"></i> Tap di luar untuk menutup
                                    </small>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Desktop Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="d-none d-lg-block">
                        @csrf
                        <button type="submit" class="logout-btn" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                    
                    <!-- Mobile Toggle Button -->
                    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation Collapse - Full width below -->
            <div class="collapse mobile-nav" id="mobileNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('form.team.choice') ? 'active' : '' }}" href="{{ route('form.team.choice') }}">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('form.team.create') ? 'active' : '' }}" href="{{ route('form.team.create') }}">
                            <i class="fas fa-plus-circle me-2"></i>Create New Team
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('form.team.join') ? 'active' : '' }}" href="{{ route('form.team.join') }}">
                            <i class="fas fa-user-plus me-2"></i>Join Team
                        </a>
                    </li>
                </ul>
                
                <!-- Mobile User Info -->
                <div class="mobile-user-info small">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-circle me-2 fs-5"></i>
                        <span>Logged in as: <strong>{{ auth()->check() ? auth()->user()->name : 'Guest' }}</strong></span>
                    </div>
                </div>
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
                <div class="text-xs" style="color: rgba(255, 255, 255, 0.8);">
                    <p class="mb-1">&copy; {{ date('Y') }} Riau Pos - Honda HSBL. All Rights Reserved.</p>
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
    
    <!-- Custom Scripts -->
    @stack('scripts')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });
            
            // ============ RESPONSIVE DROPDOWN FIXES ============
            
            // Fix dropdown positioning on mobile
            function adjustDropdownPosition() {
                const isMobile = window.innerWidth <= 575;
                const dropdown = document.querySelector('.dropdown-menu-custom');
                
                if (isMobile && dropdown && dropdown.classList.contains('show')) {
                    dropdown.style.position = 'fixed';
                    dropdown.style.top = '70px';
                    dropdown.style.left = '15px';
                    dropdown.style.right = '15px';
                    dropdown.style.transform = 'none';
                    
                    setTimeout(() => {
                        dropdown.scrollTop = 0;
                    }, 100);
                } else if (dropdown) {
                    // Reset untuk layar besar - tetap di ujung kanan
                    dropdown.style.position = 'absolute';
                    dropdown.style.top = '';
                    dropdown.style.left = 'auto';
                    dropdown.style.right = '0';
                    dropdown.style.transform = '';
                    dropdown.style.width = '';
                    dropdown.style.maxWidth = '';
                }
            }
            
            // Close dropdown when clicking outside on mobile
            function handleClickOutside(event) {
                const isMobile = window.innerWidth <= 575;
                const dropdown = document.querySelector('.dropdown-menu-custom.show');
                const trigger = document.querySelector('.user-profile');
                
                if (isMobile && dropdown && trigger) {
                    if (!dropdown.contains(event.target) && !trigger.contains(event.target)) {
                        const dropdownInstance = bootstrap.Dropdown.getInstance(trigger);
                        if (dropdownInstance) {
                            dropdownInstance.hide();
                        }
                    }
                }
            }
            
            // Attach event listeners for dropdown
            const userProfileTrigger = document.getElementById('userProfileTrigger');
            if (userProfileTrigger) {
                userProfileTrigger.addEventListener('shown.bs.dropdown', function () {
                    adjustDropdownPosition();
                    
                    if (window.innerWidth <= 575) {
                        document.addEventListener('click', handleClickOutside);
                        document.body.style.overflow = 'hidden';
                    }
                });
                
                userProfileTrigger.addEventListener('hidden.bs.dropdown', function () {
                    document.removeEventListener('click', handleClickOutside);
                    document.body.style.overflow = '';
                });
            }
            
            // Adjust on window resize
            window.addEventListener('resize', function() {
                adjustDropdownPosition();
                
                const isMobile = window.innerWidth <= 575;
                const dropdown = document.querySelector('.dropdown-menu-custom.show');
                
                if (!isMobile && dropdown) {
                    const trigger = document.querySelector('.user-profile');
                    if (trigger) {
                        const dropdownInstance = bootstrap.Dropdown.getInstance(trigger);
                        if (dropdownInstance) {
                            dropdownInstance.hide();
                        }
                    }
                }
            });
            
            // Auto-close mobile dropdown when clicking on a link
            document.querySelectorAll('.dropdown-item-custom').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 575) {
                        const trigger = document.querySelector('.user-profile');
                        if (trigger) {
                            const dropdownInstance = bootstrap.Dropdown.getInstance(trigger);
                            if (dropdownInstance) {
                                dropdownInstance.hide();
                            }
                        }
                    }
                });
            });
            
            // Handle mobile nav close when clicking links
            document.querySelectorAll('.mobile-nav .nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    const mobileNav = document.getElementById('mobileNav');
                    if (mobileNav && mobileNav.classList.contains('show')) {
                        const bsCollapse = new bootstrap.Collapse(mobileNav, {
                            toggle: false
                        });
                        bsCollapse.hide();
                    }
                });
            });
        });
    </script>
</body>
</html>