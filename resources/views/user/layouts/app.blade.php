<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>@yield('title', 'SBL Riau Pos')</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('uploads/logo/hsbl.png') }}" type="image/png" />

    {{-- Tailwind via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/layoutWeb.css') }}" rel="stylesheet" />
    
    {{-- CSS Kustom untuk Templat Biru Profesional --}}
    <style>
        :root {
            --primary-blue: #3b82f6;
            --secondary-blue: #60a5fa;
            --accent-blue: #93c5fd;
            --light-blue: #dbeafe;
            --dark-blue: #2563eb;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-800: #1f2937;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 10px;
            border: 2px solid #f1f1f1;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--dark-blue), var(--primary-blue));
        }

        /* Firefox Scrollbar */
        * {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-blue) #f1f1f1;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Button Template Styles */
        .btn-primary {
            background-color: var(--primary-blue);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .btn-primary:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }

        .btn-secondary {
            background-color: white;
            color: var(--primary-blue);
            padding: 0.5rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            border: 2px solid var(--primary-blue);
        }

        .btn-secondary:hover {
            background-color: var(--light-blue);
            transform: translateY(-2px);
        }

        /* Card Styles */
        .card {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.08), 0 1px 2px -1px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e5e7eb;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -5px rgba(0, 0, 0, 0.08), 0 6px 8px -5px rgba(0, 0, 0, 0.02);
        }

        .card-header {
            border-bottom: 1px solid var(--light-blue);
            padding-bottom: 0.875rem;
            margin-bottom: 1.25rem;
            font-size: 1rem;
        }

        /* SweetAlert Customization */
        .swal2-container {
            z-index: 999999 !important;
        }

        .swal2-popup {
            border-radius: 1rem !important;
            border: 1px solid var(--primary-blue) !important;
            background: white !important;
            margin-top: 1.5rem !important;
            padding: 1.5rem !important;
        }

        .swal2-title {
            color: var(--primary-blue) !important;
            font-weight: 600 !important;
            font-size: 1.25rem !important;
        }

        .swal2-html-container {
            font-size: 0.9375rem !important;
        }

        .swal2-confirm {
            background-color: var(--primary-blue) !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 1.5rem !important;
            font-weight: 500 !important;
            font-size: 0.875rem !important;
        }

        .swal2-cancel {
            background-color: var(--gray-100) !important;
            color: var(--gray-800) !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 1.5rem !important;
            font-weight: 500 !important;
            font-size: 0.875rem !important;
        }

        /* Navigation Styles */
        .nav-link {
            position: relative;
            padding: 0.375rem 0;
            font-size: 0.875rem;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--secondary-blue);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        /* Developer Menu Special Style */
        .developer-menu {
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4), 
                        0 0 20px rgba(99, 102, 241, 0.2);
            position: relative;
            overflow: hidden;
        }

        .developer-menu::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 30%,
                rgba(255, 255, 255, 0.1) 50%,
                transparent 70%
            );
            animation: shine 3s infinite linear;
        }

        .developer-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 0.65rem;
            padding: 0.125rem 0.5rem;
            border-radius: 1rem;
            margin-left: 0.375rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
            }
            100% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
            }
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4),
                            0 0 20px rgba(99, 102, 241, 0.2);
            }
            50% {
                box-shadow: 0 4px 20px rgba(139, 92, 246, 0.6),
                            0 0 30px rgba(99, 102, 241, 0.4),
                            0 0 40px rgba(139, 92, 246, 0.2);
            }
        }

        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .table-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            font-size: 0.875rem;
        }

        /* Floating Nav Toggle */
        .floating-nav-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: var(--primary-blue);
            color: white;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 15px rgba(59, 130, 246, 0.25);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .floating-nav-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.35);
        }

        /* Floating Nav Menu */
        .floating-nav-menu {
            position: fixed;
            top: 78px;
            right: 20px;
            z-index: 999;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            min-width: 220px;
            max-width: 260px;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            visibility: hidden;
            font-size: 0.875rem;
        }

        .floating-nav-menu.active {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .floating-nav-toggle {
                width: 44px;
                height: 44px;
                top: 15px;
                right: 15px;
            }

            .floating-nav-menu {
                top: 69px;
                right: 15px;
                left: 15px;
                max-width: none;
            }

            .swal2-popup {
                margin: 1rem !important;
                padding: 1.25rem !important;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Content wrapper spacing - Mobile First Approach */
        .content-wrapper {
            margin-top: 0.75rem; /* 12px on mobile - lebih ke atas */
        }

        /* Tablet */
        @media (min-width: 640px) {
            .content-wrapper {
                margin-top: 1.25rem; /* 20px on tablet */
            }
        }

        /* Desktop */
        @media (min-width: 1024px) {
            .content-wrapper {
                margin-top: 2rem; /* 32px on desktop */
            }
        }

        /* Untuk halaman dengan header yang lebih besar, beri sedikit jarak */
        .page-header + .content-wrapper {
            margin-top: 0.5rem; /* 8px jika ada page header */
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gradient-to-br from-blue-50 to-gray-100" id="user-layout">
    @include('partials.sweetalert')

    {{-- ==================== HEADER (Non-Fixed) ==================== --}}
    <header class="bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md"
        x-data="{ openMenu: null, mobileOpen: false }"
        @click.away="openMenu = null">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ url('user/dashboard') }}" class="flex items-center space-x-2 group">
                    <div class="bg-white p-1.5 rounded-lg shadow-sm group-hover:shadow transition-shadow duration-300">
                        <img src="{{ asset('uploads/logo/hsbl.png') }}" 
                             alt="SBL Riau Pos Logo" 
                             class="h-10 w-10 rounded-md" />
                    </div>
                </a>

                {{-- Desktop Navigation --}}
                <nav class="hidden lg:flex items-center space-x-0.5">
                    @php
                    $menu = [
                        ['label' => 'Home', 'url' => url('user/dashboard'), 'icon' => 'fas fa-home text-sm'],
                        ['label' => 'News', 'url' => url('user/news'), 'icon' => 'fas fa-newspaper text-sm'],
                        ['label' => 'Schedules & Results', 'url' => url('user/schedule-result'), 'icon' => 'fas fa-calendar-alt text-sm'],
                        // Statistics - mengarah ke halaman statistics.blade.php
                        ['label' => 'Statistics', 'url' => url('user/statistics'), 'icon' => 'fas fa-chart-bar text-sm'],
                        ['label' => 'Gallery', 'url' => '#', 'icon' => 'fas fa-images text-sm', 'submenu' => [
                            ['label' => 'Videos', 'url' => route('user.media.gallery.videos'), 'icon' => 'fas fa-video text-xs'],
                            // PERBAIKAN: Menggunakan route atau URL yang benar
                            ['label' => 'Photos', 'url' => route('user.gallery.photos.index'), 'icon' => 'fas fa-camera text-xs'],
                        ]],
                        // About
                        ['label' => 'About', 'url' => url('user/media/about'), 'icon' => 'fas fa-landmark text-sm'],
                    ];
                    @endphp

                    @foreach($menu as $index => $item)
                        @if(isset($item['submenu']))
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="nav-link flex items-center space-x-1.5 px-3 py-2 text-xs font-medium rounded-md hover:bg-blue-500/20 transition-colors duration-200"
                                    :class="open ? 'bg-blue-500/20' : ''">
                                    <i class="{{ $item['icon'] }}"></i>
                                    <span class="whitespace-nowrap">{{ $item['label'] }}</span>
                                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition @click.away="open = false"
                                    class="absolute top-full left-0 mt-1 w-40 bg-white rounded-md shadow-lg z-50 border border-blue-100 py-1">
                                    @foreach($item['submenu'] as $sub)
                                        <a href="{{ $sub['url'] }}"
                                            class="flex items-center space-x-2 px-3 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 first:rounded-t-md last:rounded-b-md">
                                            <i class="{{ $sub['icon'] }} w-3.5"></i>
                                            <span>{{ $sub['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $item['url'] }}"
                                class="nav-link flex items-center space-x-1.5 px-3 py-2 text-xs font-medium rounded-md hover:bg-blue-500/20 transition-colors duration-200 {{ request()->is(parse_url($item['url'], PHP_URL_PATH)) ? 'bg-blue-500/20' : '' }}">
                                <i class="{{ $item['icon'] }}"></i>
                                <span class="whitespace-nowrap">{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach

                    {{-- Developer Menu - Tidak ada dropdown, hanya link biasa dengan efek menonjol --}}
                    <a href="{{ url('user/media/developer') }}"
                       class="developer-menu flex items-center space-x-1.5 px-4 py-2 text-xs font-semibold rounded-md hover:opacity-90 transition-all duration-300 relative overflow-hidden pulse-glow ml-2">
                        <i class="fas fa-glasses text-sm"></i>
                        <span class="whitespace-nowrap">Developer</span>
                        <span class="developer-badge">TEAM</span>
                    </a>

                    {{-- Auth Menu --}}
                    <div class="border-l border-blue-400/50 pl-2 ml-1">
                        @auth
                            @php
                                $user = Auth::user();
                                $role = $user->role ?? null;
                            @endphp
                            
                            @if($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" 
                                   target="_blank"
                                   class="flex items-center space-x-1.5 px-3 py-2 bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-md hover:from-emerald-600 hover:to-emerald-500 transition-all duration-200 shadow-sm hover:shadow text-xs font-medium">
                                    <i class="fas fa-user-shield text-xs"></i>
                                    <span class="whitespace-nowrap">Administrator</span>
                                </a>
                            @elseif($role === 'student')
                                <a href="{{ route('student.dashboard') }}" 
                                   target="_blank"
                                   class="flex items-center space-x-1.5 px-3 py-2 bg-gradient-to-r from-violet-500 to-violet-400 rounded-md hover:from-violet-600 hover:to-violet-500 transition-all duration-200 shadow-sm hover:shadow text-xs font-medium">
                                    <i class="fas fa-calendar-alt text-xs"></i>
                                    <span class="whitespace-nowrap">Events</span>
                                </a>
                            @else
                                <a href="{{ route('login.form') }}" 
                                   target="_blank"
                                   class="flex items-center space-x-1.5 px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-400 rounded-md hover:from-blue-600 hover:to-blue-500 transition-all duration-200 text-xs font-medium">
                                    <i class="fas fa-sign-in-alt text-xs"></i>
                                    <span class="whitespace-nowrap">Login</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login.form') }}" 
                               target="_blank"
                               class="flex items-center space-x-1.5 px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-400 rounded-md hover:from-blue-600 hover:to-blue-500 transition-all duration-200 text-xs font-medium">
                                <i class="fas fa-sign-in-alt text-xs"></i>
                                <span class="whitespace-nowrap">Login</span>
                            </a>
                        @endauth
                    </div>
                </nav>

                {{-- Mobile Menu Button --}}
                <button @click="mobileOpen = !mobileOpen"
                    class="lg:hidden flex items-center justify-center w-9 h-9 rounded-md bg-blue-500/20 hover:bg-blue-500/30 transition-colors duration-200">
                    <i class="fas fa-bars text-white text-sm"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Navigation --}}
        <div x-show="mobileOpen" x-transition class="lg:hidden bg-blue-500/10 border-t border-blue-400/20">
            <div class="max-w-7xl mx-auto px-3 py-3">
                <div class="grid grid-cols-1 gap-1">
                    @foreach($menu as $index => $item)
                        @if(isset($item['submenu']))
                            <div x-data="{ open: false }" class="border-b border-blue-400/20 pb-1">
                                <button @click="open = !open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-left rounded-md hover:bg-blue-500/10 transition-colors duration-200">
                                    <div class="flex items-center space-x-2">
                                        <i class="{{ $item['icon'] }}"></i>
                                        <span class="text-sm font-medium">{{ $item['label'] }}</span>
                                    </div>
                                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition class="pl-6 mt-1 space-y-1">
                                    @foreach($item['submenu'] as $sub)
                                        <a href="{{ $sub['url'] }}"
                                            class="flex items-center space-x-2 px-3 py-1.5 rounded-md hover:bg-blue-500/10 transition-colors duration-200 text-sm">
                                            <i class="{{ $sub['icon'] }}"></i>
                                            <span>{{ $sub['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $item['url'] }}"
                                class="flex items-center space-x-2 px-3 py-2 rounded-md hover:bg-blue-500/10 transition-colors duration-200 text-sm {{ request()->is(parse_url($item['url'], PHP_URL_PATH)) ? 'bg-blue-500/10' : '' }}">
                                <i class="{{ $item['icon'] }}"></i>
                                <span class="font-medium">{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                    
                    {{-- Developer Menu Mobile --}}
                    <a href="{{ url('user/media/developer') }}"
                       class="developer-menu flex items-center space-x-2 px-3 py-2 rounded-md transition-all duration-300 relative overflow-hidden mt-1">
                        <i class="fas fa-glasses"></i>
                        <span class="font-semibold">Developer</span>
                        <span class="developer-badge text-xs">TEAM</span>
                    </a>
                    
                    {{-- Mobile Auth Menu --}}
                    <div class="pt-3 border-t border-blue-400/20">
                        @auth
                            @if($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" 
                                   target="_blank"
                                   class="flex items-center space-x-2 px-3 py-2 bg-emerald-500 rounded-md hover:bg-emerald-600 transition-colors duration-200 text-sm">
                                    <i class="fas fa-user-shield"></i>
                                    <span class="font-semibold">Administrator</span>
                                </a>
                            @elseif($role === 'student')
                                <a href="{{ route('student.dashboard') }}" 
                                   target="_blank"
                                   class="flex items-center space-x-2 px-3 py-2 bg-violet-500 rounded-md hover:bg-violet-600 transition-colors duration-200 text-sm">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span class="font-semibold">Events</span>
                                </a>
                            @else
                                <a href="{{ route('login.form') }}" 
                                   target="_blank"
                                   class="flex items-center space-x-2 px-3 py-2 bg-blue-500 rounded-md hover:bg-blue-600 transition-colors duration-200 text-sm">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span>Login</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login.form') }}" 
                               target="_blank"
                               class="flex items-center space-x-2 px-3 py-2 bg-blue-500 rounded-md hover:bg-blue-600 transition-colors duration-200 text-sm">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Login</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- ==================== FLOATING NAV TOGGLE (Muncul saat scroll) ==================== --}}
    <div x-data="{ showFloatingNav: false, floatingMenuOpen: false }"
         x-init="window.addEventListener('scroll', () => { showFloatingNav = window.scrollY > 100; })"
         class="floating-nav-container">
        <div x-show="showFloatingNav"
             x-transition
             class="floating-nav-toggle"
             @click="floatingMenuOpen = !floatingMenuOpen">
            <i class="fas fa-bars text-lg"></i>
        </div>

        {{-- Floating Navigation Menu --}}
        <div x-show="floatingMenuOpen"
             x-transition
             @click.away="floatingMenuOpen = false"
             class="floating-nav-menu"
             :class="floatingMenuOpen ? 'active' : ''">
            <div class="p-3 border-b border-gray-100">
                <h3 class="font-semibold text-base text-gray-800 flex items-center space-x-2">
                    <i class="fas fa-compass text-blue-500 text-sm"></i>
                    <span>Quick Menu</span>
                </h3>
            </div>
            <div class="p-1 max-h-80 overflow-y-auto">
                @foreach($menu as $item)
                    @if(isset($item['submenu']))
                        <div class="mb-1">
                            <div class="flex items-center space-x-1.5 px-2.5 py-1.5 text-gray-700 font-medium text-sm">
                                <i class="{{ $item['icon'] }} text-blue-500 w-3.5"></i>
                                <span>{{ $item['label'] }}</span>
                            </div>
                            <div class="pl-5 space-y-0.5">
                                @foreach($item['submenu'] as $sub)
                                    <a href="{{ $sub['url'] }}"
                                       @click="floatingMenuOpen = false"
                                       class="block px-2.5 py-1.5 text-xs text-gray-600 hover:bg-blue-50 hover:text-blue-500 rounded transition-colors duration-200">
                                        <i class="{{ $sub['icon'] }} mr-1.5 w-3"></i>
                                        {{ $sub['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $item['url'] }}"
                           @click="floatingMenuOpen = false"
                           class="flex items-center space-x-1.5 px-2.5 py-1.5 text-gray-700 hover:bg-blue-50 hover:text-blue-500 rounded transition-colors duration-200 mb-0.5 text-sm">
                            <i class="{{ $item['icon'] }} text-blue-500 w-3.5"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
                
                {{-- Developer Menu Floating --}}
                <a href="{{ url('user/media/developer') }}"
                   @click="floatingMenuOpen = false"
                   class="developer-menu flex items-center space-x-1.5 px-2.5 py-1.5 rounded transition-all duration-300 mb-0.5 text-sm font-semibold">
                    <i class="fas fa-glasses w-3.5"></i>
                    <span>Developer</span>
                    <span class="developer-badge text-xs">TEAM</span>
                </a>
                
                {{-- Floating Auth Menu --}}
                <div class="border-t border-gray-100 mt-1 pt-1">
                    @auth
                        @if($role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" 
                               target="_blank"
                               @click="floatingMenuOpen = false"
                               class="flex items-center space-x-1.5 px-2.5 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded transition-colors duration-200 text-sm">
                                <i class="fas fa-user-shield text-xs"></i>
                                <span class="font-medium">Administrator</span>
                            </a>
                        @elseif($role === 'student')
                            <a href="{{ route('student.dashboard') }}" 
                               target="_blank"
                               @click="floatingMenuOpen = false"
                               class="flex items-center space-x-1.5 px-2.5 py-1.5 bg-violet-50 text-violet-600 hover:bg-violet-100 rounded transition-colors duration-200 text-sm">
                                <i class="fas fa-calendar-alt text-xs"></i>
                                <span class="font-medium">Events</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login.form') }}" 
                           target="_blank"
                           @click="floatingMenuOpen = false"
                           class="flex items-center space-x-1.5 px-2.5 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded transition-colors duration-200 text-sm">
                            <i class="fas fa-sign-in-alt text-xs"></i>
                            <span>Login</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MAIN CONTENT with adjusted mobile margin ==================== --}}
    <main class="flex-grow w-full animate-fadeInUp content-wrapper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Page Content --}}
            @yield('content')
        </div>
    </main>

    {{-- ==================== SPONSORS SECTION (Background Putih) ==================== --}}
    <div class="w-full bg-white py-8">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-8">
                @php
                    $groupedSponsors = $groupedSponsors ?? collect();
                    
                    // Definisikan urutan kategori yang diinginkan
                    $orderedCategories = [
                        'Presented by',
                        'Official Partners', 
                        'Official Suppliers',
                        'Supporting Partners',
                        'Managed by'
                    ];
                @endphp

                @if($groupedSponsors->count() > 0)
                    {{-- Loop melalui kategori yang diurutkan --}}
                    @foreach($orderedCategories as $category)
                        @php
                            // Cari kategori yang cocok (case-insensitive)
                            $matchingKey = null;
                            foreach ($groupedSponsors->keys() as $key) {
                                $lowerKey = strtolower($key);
                                $lowerCategory = strtolower($category);
                                
                                if ($lowerKey === $lowerCategory || 
                                    str_contains($lowerKey, $lowerCategory) ||
                                    str_contains($lowerCategory, $lowerKey)) {
                                    $matchingKey = $key;
                                    break;
                                }
                            }
                        @endphp

                        @if($matchingKey && $groupedSponsors[$matchingKey]->count() > 0)
                            <div class="mb-8">
                                <h4 class="font-medium text-sm uppercase mb-4 text-center text-gray-700 tracking-wider">
                                    {{ $category }}
                                </h4>
                                <div class="flex flex-wrap justify-center gap-4">
                                    @foreach($groupedSponsors[$matchingKey]->sortBy('created_at') as $sponsor)
                                        <a href="{{ $sponsor->sponsors_web ?? '#' }}" 
                                           target="_blank" 
                                           class="sponsor-card transform transition-transform duration-300 hover:scale-105">
                                            @if($sponsor->logo)
                                                <div class="bg-white p-2 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100">
                                                    <img
                                                        src="{{ asset('uploads/sponsors/' . $sponsor->logo) }}"
                                                        alt="{{ $sponsor->sponsor_name }}"
                                                        class="h-10 w-auto object-contain"
                                                    />
                                                </div>
                                            @else
                                                <div class="h-10 bg-gray-50 rounded-lg flex items-center justify-center px-3 border border-gray-200">
                                                    <span class="text-xs font-medium text-gray-700">{{ $sponsor->sponsor_name }}</span>
                                                </div>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                    
                    {{-- Tampilkan kategori lain yang tidak sesuai dengan urutan di atas --}}
                    @php
                        $displayedCategories = [];
                        foreach ($orderedCategories as $cat) {
                            foreach ($groupedSponsors->keys() as $key) {
                                $lowerKey = strtolower($key);
                                $lowerCat = strtolower($cat);
                                if ($lowerKey === $lowerCat || str_contains($lowerKey, $lowerCat)) {
                                    $displayedCategories[] = $key;
                                    break;
                                }
                            }
                        }
                        
                        $remainingCategories = array_diff($groupedSponsors->keys()->toArray(), $displayedCategories);
                    @endphp
                    
                    @foreach($remainingCategories as $otherCategory)
                        @if($groupedSponsors[$otherCategory]->count() > 0)
                            <div class="mb-8">
                                <h4 class="font-medium text-sm uppercase mb-4 text-center text-gray-700 tracking-wider">
                                    {{ $otherCategory }}
                                </h4>
                                <div class="flex flex-wrap justify-center gap-4">
                                    @foreach($groupedSponsors[$otherCategory]->sortBy('created_at') as $sponsor)
                                        <a href="{{ $sponsor->sponsors_web ?? '#' }}" 
                                           target="_blank" 
                                           class="sponsor-card transform transition-transform duration-300 hover:scale-105">
                                            @if($sponsor->logo)
                                                <div class="bg-white p-2 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100">
                                                    <img
                                                        src="{{ asset('uploads/sponsors/' . $sponsor->logo) }}"
                                                        alt="{{ $sponsor->sponsor_name }}"
                                                        class="h-10 w-auto object-contain"
                                                    />
                                                </div>
                                            @else
                                                <div class="h-10 bg-gray-50 rounded-lg flex items-center justify-center px-3 border border-gray-200">
                                                    <span class="text-xs font-medium text-gray-700">{{ $sponsor->sponsor_name }}</span>
                                                </div>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="text-center text-gray-500 py-6">
                        <i class="fas fa-sparkles text-2xl mb-3"></i>
                        <p class="text-sm">Sponsor information will be available soon.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ==================== FOOTER (Background Biru dengan Rounded Top) ==================== --}}
    <footer class="w-full bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-t-3xl">
        <div class="max-w-7xl mx-auto px-6 py-8">
            {{-- Footer Info --}}
            <div class="text-center pt-6">
                <div class="flex flex-col md:flex-row items-center justify-between mb-4">
                    <div class="mb-3 md:mb-0">
                        <img src="{{ asset('uploads/logo/hsbl.png') }}" 
                             alt="SBL Logo" 
                             class="h-10 w-10 rounded-lg mx-auto md:mx-0">
                    </div>
                    <div class="text-center md:text-left">
                        <h3 class="text-lg font-semibold mb-1">Riau Pos - SBL</h3>
                        <p class="text-blue-100 text-xs">Student Basketball League</p>
                    </div>
                </div>
                
                <div class="text-xs text-blue-200">
                    <p class="mb-1">&copy; {{ date('Y') }} Riau Pos - Student Basketball League. All Rights Reserved.</p>
                    <p class="flex items-center justify-center space-x-1">
                        <span>Developed with</span>
                        <i class="fas fa-heart text-red-300"></i>
                        <span>by : Mutia Rizkianti | Wafiq Wardatul Khairani</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    {{-- JavaScript untuk Floating Navigation dan Scrollbar --}}
    <script>
        // Smooth scroll untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Close floating menu ketika link diklik
        document.querySelectorAll('.floating-nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                const alpineData = document.querySelector('[x-data*="floatingMenuOpen"]').__x.$data;
                if (alpineData) {
                    alpineData.floatingMenuOpen = false;
                }
            });
        });

        // Add active class to current page links
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if(link.getAttribute('href') === currentPath || 
                   link.getAttribute('href') === (currentPath + '/')) {
                    link.classList.add('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>