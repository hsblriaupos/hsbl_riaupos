<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'HSBL Registration - Student Portal')</title>
    
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
        }
        
        .avatar-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
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
        
        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--primary-color);
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .notification-btn {
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 1.1rem;
            width: 44px;
            height: 44px;
            padding: 0;
            border-radius: 10px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .notification-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
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
        }
        
        .dropdown-avatar-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-gradient);
            border: 3px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            
            .notification-btn {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            
            .user-avatar, .avatar-icon {
                width: 36px;
                height: 36px;
            }
        }
        
        @media (max-width: 576px) {
            .user-menu {
                gap: 8px;
            }
            
            .user-profile {
                padding: 4px 8px;
            }
            
            .user-avatar, .avatar-icon {
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
            <a class="navbar-brand fw-bold" href="{{ route('form.team.choice') }}">
                <i class="fas fa-basketball-ball me-2"></i>HSBL Student Portal
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
                    <!-- Notification Button -->
                    <a href="{{ route('student.notifications') }}" class="notification-btn position-relative" title="Notifications">
                        <i class="fas fa-bell"></i>
                    </a>
                    
                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <div class="user-profile" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(auth()->check())
                                @php
                                    $user = auth()->user();
                                @endphp
                                
                                @if($user->avatar)
                                    <!-- If user has avatar, show image -->
                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                         class="user-avatar" 
                                         alt="{{ $user->name }}"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <!-- Fallback icon if image error -->
                                    <div class="avatar-icon" style="display: none;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @else
                                    <!-- If no avatar, show user icon -->
                                    <div class="avatar-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            @else
                                <!-- For guest user -->
                                <div class="avatar-icon">
                                    <i class="fas fa-user"></i>
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
                                @endphp
                                <li>
                                    <div class="dropdown-profile-preview">
                                        @if($user->avatar)
                                            <!-- If has avatar -->
                                            <img src="{{ asset('storage/' . $user->avatar) }}" 
                                                 class="dropdown-avatar" 
                                                 alt="{{ $user->name }}"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <!-- Fallback icon -->
                                            <div class="dropdown-avatar-icon" style="display: none;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @else
                                            <!-- If no avatar -->
                                            <div class="dropdown-avatar-icon">
                                                <i class="fas fa-user"></i>
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
                                <a class="dropdown-item-custom" href="{{ route('student.profile.edit') }}">
                                    <i class="fas fa-user-edit text-primary"></i>
                                    <span>Edit Profile</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item-custom" href="{{ route('student.school.edit') }}">
                                    <i class="fas fa-school text-success"></i>
                                    <span>Edit School Data</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider dropdown-divider-custom mx-3"></li>
                            <li>
                                <a class="dropdown-item-custom" href="{{ route('student.team') }}">
                                    <i class="fas fa-users text-info"></i>
                                    <span>My Team</span>
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
    <footer class="mt-5 py-4 text-center" style="background: var(--light-bg); border-top: 1px solid #e0e0e0;">
        <div class="container">
            <p class="mb-1">
                <i class="fas fa-heart text-danger"></i> Riau Pos - Honda HSBL {{ date('Y') }} 
                | Honda Student Basketball League
            </p>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    @stack('scripts')
    
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
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
            
            // Handle avatar loading errors
            document.querySelectorAll('img.user-avatar, img.dropdown-avatar').forEach(img => {
                img.addEventListener('error', function() {
                    console.log('Avatar image failed to load:', this.src);
                    this.style.display = 'none';
                    
                    // Find and show the fallback icon
                    const fallback = this.nextElementSibling;
                    if (fallback && (fallback.classList.contains('avatar-icon') || fallback.classList.contains('dropdown-avatar-icon'))) {
                        fallback.style.display = 'flex';
                    }
                });
            });
        });
    </script>
</body>
</html>