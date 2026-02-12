<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        
        /* User Menu Styles */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 10px;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .user-profile:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        
        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: white;
        }
        
        /* Style khusus untuk avatar SVG */
        .user-avatar-svg {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .avatar-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--primary-gradient);
            border: 2px solid rgba(255, 255, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
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
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 100px;
            justify-content: center;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Dropdown Menu */
        .dropdown-menu-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 8px 0;
            min-width: 220px;
            margin-top: 10px !important;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .dropdown-header-custom {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-light);
            padding: 8px 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .dropdown-item-custom {
            padding: 12px 16px;
            color: var(--text-dark);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 3px solid transparent;
            font-size: 0.95rem;
            text-decoration: none !important;
        }
        
        .dropdown-item-custom:hover {
            background: #e3f2fd;
            color: var(--primary-color);
            border-left-color: var(--accent-color);
            padding-left: 20px;
        }
        
        .dropdown-item-custom i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .dropdown-divider-custom {
            margin: 6px 0;
            border-top: 1px solid #eee;
        }
        
        /* Profile Preview in Dropdown */
        .dropdown-profile-preview {
            padding: 15px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 8px;
            margin: 0 10px 10px 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(66, 165, 245, 0.3);
        }
        
        .dropdown-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: white;
        }
        
        /* Style khusus untuk avatar SVG di dropdown */
        .dropdown-avatar-svg {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .dropdown-user-info {
            flex: 1;
        }
        
        .dropdown-user-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 1rem;
            margin-bottom: 2px;
        }
        
        .dropdown-user-email {
            font-size: 0.85rem;
            color: var(--text-light);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Avatar Placeholder Styles */
        .avatar-placeholder {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 1rem;
            background: var(--primary-gradient);
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .dropdown-avatar-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 1.2rem;
            background: var(--primary-gradient);
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Footer Styles */
        .footer-custom {
            background: var(--primary-gradient);
            color: white;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Logo Container */
        .logo-container {
            background: white;
            padding: 8px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        
        .logo-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }
        
        .logo-img {
            height: 40px;
            width: auto;
            border-radius: 6px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .user-menu {
                gap: 10px;
            }
            
            .user-info {
                display: none;
            }
            
            .user-profile {
                padding: 6px 10px;
            }
            
            .logout-btn {
                min-width: auto;
                padding: 8px 12px;
            }
            
            .logout-btn span {
                display: none;
            }
            
            .user-avatar, .user-avatar-svg, .avatar-icon, .avatar-placeholder {
                width: 36px;
                height: 36px;
            }
            
            .logo-img {
                height: 32px;
            }
            
            .logo-container {
                padding: 6px;
            }
        }
        
        @media (max-width: 576px) {
            .user-menu {
                gap: 8px;
            }
            
            .user-profile {
                padding: 4px 8px;
            }
            
            .user-avatar, .user-avatar-svg, .avatar-icon, .avatar-placeholder {
                width: 32px;
                height: 32px;
            }
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
        
        /* Active nav link */
        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--primary-gradient);">
        <div class="container">
            <!-- Logo -->
            <a href="{{ route('form.team.choice') }}" class="d-flex align-items-center text-decoration-none">
                <div class="logo-container">
                    <img src="{{ asset('uploads/logo/hsbl.png') }}" 
                         alt="HSBL Riau Pos Logo" 
                         class="logo-img" />
                </div>
                <span class="navbar-brand fw-bold ms-3">HSBL Student Portal</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('form.team.choice') ? 'active' : '' }}" href="{{ route('form.team.choice') }}">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('form.team.create') ? 'active' : '' }}" href="{{ route('form.team.create') }}">
                            <i class="fas fa-plus-circle me-1"></i>Create New Team
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('form.team.join') ? 'active' : '' }}" href="{{ route('form.team.join') }}">
                            <i class="fas fa-user-plus me-1"></i>Join Team
                        </a>
                    </li>
                </ul>
                
                <!-- User Menu (Right Side) -->
                <div class="user-menu">
                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <div class="user-profile" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(auth()->check())
                                @php
                                    $user = auth()->user();
                                    $avatar = $user->avatar;
                                    
                                    // Buat inisial untuk placeholder
                                    $initials = '';
                                    $nameParts = explode(' ', $user->name);
                                    foreach($nameParts as $part) {
                                        if(!empty($part)) {
                                            $initials .= strtoupper(substr($part, 0, 1));
                                        }
                                        if(strlen($initials) >= 2) break;
                                    }
                                    if(empty($initials)) {
                                        $initials = strtoupper(substr($user->email, 0, 1));
                                    }
                                    
                                    // Cek jika avatar adalah URL DiceBear
                                    $isDiceBear = $avatar && strpos($avatar, 'dicebear.com') !== false;
                                    $isValidAvatar = $avatar && (strpos($avatar, 'http') === 0 || strpos($avatar, '//') === 0);
                                @endphp
                                
                                @if($isValidAvatar)
                                    @if($isDiceBear)
                                        <!-- Avatar dari DiceBear API (SVG) -->
                                        <div class="user-avatar-svg" id="navbar-avatar-container">
                                            <img src="{{ $avatar }}" 
                                                 class="user-avatar" 
                                                 alt="{{ $user->name }}"
                                                 id="navbar-avatar-img"
                                                 data-initials="{{ $initials }}"
                                                 style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                            <!-- Fallback placeholder -->
                                            <div class="avatar-placeholder d-none" id="navbar-avatar-placeholder">
                                                {{ $initials }}
                                            </div>
                                        </div>
                                    @else
                                        <!-- Avatar biasa (JPG/PNG) -->
                                        <img src="{{ $avatar }}" 
                                             class="user-avatar" 
                                             alt="{{ $user->name }}"
                                             id="navbar-avatar-img"
                                             data-initials="{{ $initials }}">
                                        <!-- Fallback placeholder -->
                                        <div class="avatar-placeholder d-none" id="navbar-avatar-placeholder">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                @else
                                    <!-- Tampilkan placeholder jika tidak ada avatar atau tidak valid -->
                                    <div class="avatar-placeholder" id="navbar-avatar-placeholder">
                                        {{ $initials }}
                                    </div>
                                @endif
                            @else
                                <!-- Untuk guest user -->
                                <div class="avatar-icon">
                                    <i class="fas fa-basketball-ball"></i>
                                </div>
                            @endif
                            
                            <div class="user-info d-none d-lg-block">
                                <span class="user-name">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</span>
                                <span class="user-role">{{ auth()->check() && auth()->user()->role ? ucfirst(auth()->user()->role) : 'Student' }}</span>
                            </div>
                            <i class="fas fa-chevron-down text-white ms-2"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                            <!-- Profile Preview -->
                            @if(auth()->check())
                                @php
                                    $user = auth()->user();
                                    $avatar = $user->avatar;
                                    
                                    // Buat inisial untuk placeholder
                                    $initials = '';
                                    $nameParts = explode(' ', $user->name);
                                    foreach($nameParts as $part) {
                                        if(!empty($part)) {
                                            $initials .= strtoupper(substr($part, 0, 1));
                                        }
                                        if(strlen($initials) >= 2) break;
                                    }
                                    if(empty($initials)) {
                                        $initials = strtoupper(substr($user->email, 0, 1));
                                    }
                                    
                                    // Cek jika avatar adalah URL DiceBear
                                    $isDiceBear = $avatar && strpos($avatar, 'dicebear.com') !== false;
                                    $isValidAvatar = $avatar && (strpos($avatar, 'http') === 0 || strpos($avatar, '//') === 0);
                                @endphp
                                <li>
                                    <div class="dropdown-profile-preview">
                                        @if($isValidAvatar)
                                            @if($isDiceBear)
                                                <!-- Avatar dari DiceBear API (SVG) untuk dropdown -->
                                                <div class="dropdown-avatar-svg" id="dropdown-avatar-container">
                                                    <img src="{{ $avatar }}" 
                                                         class="dropdown-avatar" 
                                                         alt="{{ $user->name }}"
                                                         id="dropdown-avatar-img"
                                                         data-initials="{{ $initials }}"
                                                         style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                                    <!-- Fallback placeholder -->
                                                    <div class="dropdown-avatar-placeholder d-none" id="dropdown-avatar-placeholder">
                                                        {{ $initials }}
                                                    </div>
                                                </div>
                                            @else
                                                <!-- Avatar biasa (JPG/PNG) untuk dropdown -->
                                                <img src="{{ $avatar }}" 
                                                     class="dropdown-avatar" 
                                                     alt="{{ $user->name }}"
                                                     id="dropdown-avatar-img"
                                                     data-initials="{{ $initials }}">
                                                <!-- Fallback placeholder -->
                                                <div class="dropdown-avatar-placeholder d-none" id="dropdown-avatar-placeholder">
                                                    {{ $initials }}
                                                </div>
                                            @endif
                                        @else
                                            <!-- Tampilkan placeholder jika tidak ada avatar atau tidak valid -->
                                            <div class="dropdown-avatar-placeholder" id="dropdown-avatar-placeholder">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                        
                                        <div class="dropdown-user-info">
                                            <div class="dropdown-user-name">{{ $user->name }}</div>
                                            <div class="dropdown-user-email">{{ $user->email }}</div>
                                            <div class="mt-1">
                                                <span class="badge bg-primary bg-opacity-10 text-primary py-1 px-2" style="font-size: 0.7rem;">
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
                                <a class="dropdown-item-custom" href="{{ route('schooldata.list') }}">
                                    <i class="fas fa-school text-success"></i>
                                    <span>My Schools</span>
                                </a>
                            </li>
                            <!-- MENU REVIEW DATA - BARU DITAMBAHKAN -->
                            <li>
                                <a class="dropdown-item-custom" href="{{ route('review.data') }}">
                                    <i class="fas fa-clipboard-list text-info"></i>
                                    <span>Review Data</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider dropdown-divider-custom mx-3"></li>
                            <li>
                                <a class="dropdown-item-custom" href="{{ route('team.list') }}">
                                    <i class="fas fa-users text-info"></i>
                                    <span>Team List</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="d-none d-md-inline">Logout</span>
                        </button>
                    </form>
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
            
            // Active nav link highlighting
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
            
            // Debug: Tampilkan info avatar
            @if(auth()->check())
                console.log('=== AVATAR DEBUG INFO ===');
                console.log('User:', '{{ auth()->user()->name }}');
                console.log('Avatar URL from DB:', '{{ auth()->user()->avatar }}');
                console.log('Avatar type:', '{{ strpos(auth()->user()->avatar ?? "", "dicebear.com") !== false ? "DiceBear SVG" : "Regular Image" }}');
                console.log('URL starts with https:', '{{ strpos(auth()->user()->avatar ?? "", "https") === 0 }}');
            @endif
            
            // Fungsi untuk menangani error loading avatar
            function handleAvatarError(imgElement) {
                console.log('Avatar loading error for:', imgElement.src);
                
                const placeholderId = imgElement.id.replace('-img', '-placeholder');
                const placeholder = document.getElementById(placeholderId);
                
                if (placeholder) {
                    imgElement.style.display = 'none';
                    placeholder.classList.remove('d-none');
                }
            }
            
            // Setup error handlers untuk semua avatar image
            document.querySelectorAll('img[id$="-avatar-img"]').forEach(img => {
                img.onerror = function() {
                    handleAvatarError(this);
                };
                
                // Coba load ulang gambar dengan timestamp untuk menghindari cache
                if (img.src.includes('dicebear.com')) {
                    const originalSrc = img.src;
                    const timestamp = new Date().getTime();
                    const newSrc = originalSrc + (originalSrc.includes('?') ? '&' : '?') + '_=' + timestamp;
                    
                    // Coba load dengan timestamp baru
                    const testImg = new Image();
                    testImg.onload = function() {
                        console.log('DiceBear avatar loaded successfully');
                    };
                    testImg.onerror = function() {
                        console.log('DiceBear avatar failed to load, showing placeholder');
                        handleAvatarError(img);
                    };
                    testImg.src = newSrc;
                }
            });
            
            // Preload DiceBear avatars
            function preloadDiceBearAvatars() {
                @if(auth()->check() && auth()->user()->avatar && strpos(auth()->user()->avatar, 'dicebear.com') !== false)
                    const avatarUrl = '{{ auth()->user()->avatar }}';
                    
                    // Tambahkan cache busting parameter
                    const timestamp = new Date().getTime();
                    const cacheBustedUrl = avatarUrl + (avatarUrl.includes('?') ? '&' : '?') + '_=' + timestamp;
                    
                    // Preload dengan fetch
                    fetch(cacheBustedUrl, {
                        method: 'GET',
                        mode: 'no-cors',
                        cache: 'no-cache'
                    }).catch(err => {
                        console.log('Avatar preload attempt completed');
                    });
                    
                    // Juga preload dengan Image object
                    const preloadImg = new Image();
                    preloadImg.onload = function() {
                        console.log('DiceBear avatar preloaded successfully');
                    };
                    preloadImg.onerror = function() {
                        console.log('DiceBear avatar preload failed');
                    };
                    preloadImg.src = cacheBustedUrl;
                @endif
            }
            
            // Jalankan preload
            preloadDiceBearAvatars();
            
            // Cek apakah avatar sudah dimuat setelah beberapa detik
            setTimeout(() => {
                document.querySelectorAll('img[id$="-avatar-img"]').forEach(img => {
                    if (img.complete && img.naturalHeight === 0) {
                        console.log('Avatar image appears to be broken:', img.src);
                        handleAvatarError(img);
                    }
                });
            }, 2000);
        });
        
        // Fungsi untuk force reload avatar dengan cache busting
        function reloadAvatarWithCacheBusting() {
            @if(auth()->check() && auth()->user()->avatar)
                const avatarUrl = '{{ auth()->user()->avatar }}';
                const timestamp = new Date().getTime();
                const newUrl = avatarUrl + (avatarUrl.includes('?') ? '&' : '?') + '_=' + timestamp;
                
                // Update semua avatar images dengan URL baru
                document.querySelectorAll('img[src*="dicebear.com"]').forEach(img => {
                    img.src = newUrl;
                });
                
                console.log('Avatars reloaded with cache busting');
            @endif
        }
        
        // Tambahkan button untuk reload avatar (untuk debugging)
        document.addEventListener('DOMContentLoaded', function() {
            // Hanya untuk debugging - bisa dihapus di production
            const debugDiv = document.createElement('div');
            debugDiv.style.position = 'fixed';
            debugDiv.style.bottom = '10px';
            debugDiv.style.right = '10px';
            debugDiv.style.zIndex = '9999';
            debugDiv.innerHTML = `
                <button onclick="reloadAvatarWithCacheBusting()" 
                        style="background: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 5px; font-size: 12px; cursor: pointer;">
                    Reload Avatar
                </button>
            `;
            document.body.appendChild(debugDiv);
        });
    </script>
</body>
</html>