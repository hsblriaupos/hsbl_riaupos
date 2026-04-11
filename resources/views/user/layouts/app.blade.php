<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes"/>
    <title>@yield('title', 'SBL Riau Pos')</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('uploads/logo/hsbl.png') }}" type="image/png" />

    {{-- Tailwind via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/layoutWeb.css') }}" rel="stylesheet" />
    
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
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--dark-blue), var(--primary-blue));
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-blue) #f1f1f1;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            overflow-x: hidden;
            font-size: 14px;
        }

        /* Responsive font */
        @media (max-width: 640px) {
            body {
                font-size: 13px;
            }
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-blue);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background-color: var(--dark-blue);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }

        .btn-secondary {
            background-color: white;
            color: var(--primary-blue);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            border: 2px solid var(--primary-blue);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background-color: var(--light-blue);
            transform: translateY(-1px);
        }

        /* Card Styles */
        .card {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e5e7eb;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -5px rgba(0, 0, 0, 0.08);
        }

        /* SweetAlert */
        .swal2-container {
            z-index: 999999 !important;
        }

        .swal2-popup {
            border-radius: 1rem !important;
            border: 1px solid var(--primary-blue) !important;
            background: white !important;
            padding: 1rem !important;
            font-size: 0.85rem !important;
        }

        /* Navigation */
        .nav-link {
            position: relative;
            padding: 0.375rem 0;
            font-size: 0.8rem;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--secondary-blue);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        /* Developer Menu */
        .developer-menu {
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
            position: relative;
            overflow: hidden;
        }

        .developer-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 0.6rem;
            padding: 0.125rem 0.5rem;
            border-radius: 1rem;
            margin-left: 0.375rem;
            font-weight: 600;
        }

        /* Floating Nav */
        .floating-nav-toggle {
            position: fixed;
            top: 80px;
            right: 16px;
            z-index: 1000;
            background: var(--primary-blue);
            color: white;
            width: 42px;
            height: 42px;
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
        }

        .floating-nav-menu {
            position: fixed;
            top: 132px;
            right: 16px;
            z-index: 999;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            visibility: hidden;
        }

        .floating-nav-menu.active {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }

        /* Content wrapper - JARAK KE HEADER TIDAK KEJAUHAN */
        .content-wrapper {
            margin-top: 0.5rem;
            min-height: calc(100vh - 200px);
        }

        @media (min-width: 640px) {
            .content-wrapper {
                margin-top: 0.75rem;
            }
        }

        @media (min-width: 1024px) {
            .content-wrapper {
                margin-top: 1rem;
            }
        }

        .content-bottom-spacing {
            margin-bottom: 1.5rem;
        }

        @media (min-width: 640px) {
            .content-bottom-spacing {
                margin-bottom: 2rem;
            }
        }

        /* Sponsor Section */
        .sponsor-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 2rem 0;
        }

        @media (min-width: 640px) {
            .sponsor-section {
                padding: 2.5rem 0;
            }
        }

        .sponsor-card-bg {
            background: white;
            border-radius: 0.75rem;
            padding: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }

        .sponsor-card-bg:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.15);
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 1rem 1rem 0 0;
            margin-top: 0.5rem;
        }

        @media (min-width: 640px) {
            footer {
                border-radius: 1.5rem 1.5rem 0 0;
            }
        }

        /* Table Responsive */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.4s ease-out;
        }

        /* Mobile adjustments */
        @media (max-width: 640px) {
            .floating-nav-toggle {
                width: 38px;
                height: 38px;
                top: 70px;
                right: 12px;
            }
            
            .floating-nav-menu {
                top: 118px;
                right: 12px;
                left: 12px;
                max-width: none;
            }
            
            .container, .max-w-7xl {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .sponsor-card-bg img {
                height: 28px !important;
            }
            
            footer .max-w-7xl {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            footer img {
                height: 60px !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gradient-to-br from-blue-50 to-gray-100" id="user-layout">
    @include('partials.sweetalert')

    {{-- ==================== HEADER ==================== --}}
    <header class="bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md"
        x-data="{ openMenu: null, mobileOpen: false }"
        @click.away="openMenu = null">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
            <div class="flex items-center justify-between h-14 md:h-16">
                {{-- Logo Area - KOSONG (tidak ada logo) --}}
                <div class="w-8"></div>

                {{-- Desktop Navigation --}}
                <nav class="hidden lg:flex items-center space-x-0.5">
                    @php
                    $menu = [
                        ['label' => 'Home', 'url' => url('user/dashboard'), 'icon' => 'fas fa-home'],
                        ['label' => 'News', 'url' => url('user/news'), 'icon' => 'fas fa-newspaper'],
                        ['label' => 'Schedules & Results', 'url' => url('user/schedule-result'), 'icon' => 'fas fa-calendar-alt'],
                        ['label' => 'Statistics', 'url' => url('user/statistics'), 'icon' => 'fas fa-chart-bar'],
                        ['label' => 'Gallery', 'url' => '#', 'icon' => 'fas fa-images', 'submenu' => [
                            ['label' => 'Videos', 'url' => route('user.media.gallery.videos'), 'icon' => 'fas fa-video'],
                            ['label' => 'Photos', 'url' => route('user.gallery.photos.index'), 'icon' => 'fas fa-camera'],
                        ]],
                        ['label' => 'About', 'url' => url('user/media/about'), 'icon' => 'fas fa-landmark'],
                    ];
                    @endphp

                    @foreach($menu as $item)
                        @if(isset($item['submenu']))
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="nav-link flex items-center space-x-1 px-2 py-1.5 text-xs font-medium rounded-md hover:bg-blue-500/20">
                                    <i class="{{ $item['icon'] }} text-xs"></i>
                                    <span>{{ $item['label'] }}</span>
                                    <i class="fas fa-chevron-down text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition @click.away="open = false" class="absolute top-full left-0 mt-1 w-36 bg-white rounded-md shadow-lg z-50 border border-blue-100 py-1">
                                    @foreach($item['submenu'] as $sub)
                                        <a href="{{ $sub['url'] }}" class="flex items-center space-x-2 px-3 py-1.5 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                            <i class="{{ $sub['icon'] }} w-3 text-xs"></i>
                                            <span>{{ $sub['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $item['url'] }}" class="nav-link flex items-center space-x-1 px-2 py-1.5 text-xs font-medium rounded-md hover:bg-blue-500/20">
                                <i class="{{ $item['icon'] }} text-xs"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach

                    <a href="{{ url('user/media/developer') }}" class="developer-menu flex items-center space-x-1 px-3 py-1.5 text-xs font-semibold rounded-md ml-1">
                        <i class="fas fa-globe text-xs"></i>
                        <span>Developer</span>
                        <span class="developer-badge">TEAM</span>
                    </a>

                    <div class="border-l border-blue-400/50 pl-2 ml-1">
                        @auth
                            @php $role = Auth::user()->role ?? null; @endphp
                            @if($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" target="_blank" class="flex items-center space-x-1 px-2 py-1.5 bg-emerald-500 rounded-md hover:bg-emerald-600 text-xs">
                                    <i class="fas fa-user-shield text-xs"></i>
                                    <span>Admin</span>
                                </a>
                            @elseif($role === 'student')
                                <a href="{{ route('student.dashboard') }}" target="_blank" class="flex items-center space-x-1 px-2 py-1.5 bg-violet-500 rounded-md hover:bg-violet-600 text-xs">
                                    <i class="fas fa-calendar-alt text-xs"></i>
                                    <span>Events</span>
                                </a>
                            @else
                                <a href="{{ route('login.form') }}" target="_blank" class="flex items-center space-x-1 px-2 py-1.5 bg-blue-500 rounded-md hover:bg-blue-600 text-xs">
                                    <i class="fas fa-sign-in-alt text-xs"></i>
                                    <span>Login</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login.form') }}" target="_blank" class="flex items-center space-x-1 px-2 py-1.5 bg-blue-500 rounded-md hover:bg-blue-600 text-xs">
                                <i class="fas fa-sign-in-alt text-xs"></i>
                                <span>Login</span>
                            </a>
                        @endauth
                    </div>
                </nav>

                {{-- Mobile Menu Button --}}
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden flex items-center justify-center w-8 h-8 rounded-md bg-blue-500/20">
                    <i class="fas fa-bars text-white text-sm"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Navigation --}}
        <div x-show="mobileOpen" x-transition class="lg:hidden bg-blue-600/95 border-t border-blue-400/20">
            <div class="max-w-7xl mx-auto px-3 py-2">
                <div class="space-y-0.5">
                    @foreach($menu as $item)
                        @if(isset($item['submenu']))
                            <div x-data="{ open: false }" class="border-b border-blue-400/20 pb-1">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-2 py-1.5 text-left rounded-md hover:bg-blue-500/10">
                                    <div class="flex items-center space-x-2">
                                        <i class="{{ $item['icon'] }} text-xs"></i>
                                        <span class="text-sm">{{ $item['label'] }}</span>
                                    </div>
                                    <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition class="pl-6 mt-0.5 space-y-0.5">
                                    @foreach($item['submenu'] as $sub)
                                        <a href="{{ $sub['url'] }}" class="flex items-center space-x-2 px-2 py-1 rounded-md hover:bg-blue-500/10 text-sm">
                                            <i class="{{ $sub['icon'] }} text-xs"></i>
                                            <span>{{ $sub['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $item['url'] }}" class="flex items-center space-x-2 px-2 py-1.5 rounded-md hover:bg-blue-500/10 text-sm">
                                <i class="{{ $item['icon'] }} text-xs"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                    
                    <a href="{{ url('user/media/developer') }}" class="developer-menu flex items-center space-x-2 px-2 py-1.5 rounded-md mt-1 text-sm">
                        <i class="fas fa-globe text-xs"></i>
                        <span class="font-semibold">Developer</span>
                        <span class="developer-badge">TEAM</span>
                    </a>
                    
                    <div class="pt-2 border-t border-blue-400/20 mt-1">
                        @auth
                            @if($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" target="_blank" class="flex items-center space-x-2 px-2 py-1.5 bg-emerald-500 rounded-md text-sm">
                                    <i class="fas fa-user-shield text-xs"></i>
                                    <span>Administrator</span>
                                </a>
                            @elseif($role === 'student')
                                <a href="{{ route('student.dashboard') }}" target="_blank" class="flex items-center space-x-2 px-2 py-1.5 bg-violet-500 rounded-md text-sm">
                                    <i class="fas fa-calendar-alt text-xs"></i>
                                    <span>Events</span>
                                </a>
                            @else
                                <a href="{{ route('login.form') }}" target="_blank" class="flex items-center space-x-2 px-2 py-1.5 bg-blue-500 rounded-md text-sm">
                                    <i class="fas fa-sign-in-alt text-xs"></i>
                                    <span>Login</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login.form') }}" target="_blank" class="flex items-center space-x-2 px-2 py-1.5 bg-blue-500 rounded-md text-sm">
                                <i class="fas fa-sign-in-alt text-xs"></i>
                                <span>Login</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- ==================== FLOATING NAV TOGGLE ==================== --}}
    <div x-data="{ showFloatingNav: false, floatingMenuOpen: false }"
         x-init="window.addEventListener('scroll', () => { showFloatingNav = window.scrollY > 150; })">
        <div x-show="showFloatingNav" x-transition class="floating-nav-toggle" @click="floatingMenuOpen = !floatingMenuOpen">
            <i class="fas fa-bars text-sm"></i>
        </div>

        <div x-show="floatingMenuOpen" x-transition @click.away="floatingMenuOpen = false" class="floating-nav-menu" :class="floatingMenuOpen ? 'active' : ''">
            <div class="p-2 border-b border-gray-100">
                <h3 class="font-semibold text-sm flex items-center space-x-2">
                    <i class="fas fa-compass text-blue-500 text-xs"></i>
                    <span>Quick Menu</span>
                </h3>
            </div>
            <div class="p-1 max-h-64 overflow-y-auto">
                @foreach($menu as $item)
                    @if(isset($item['submenu']))
                        <div class="mb-1">
                            <div class="flex items-center space-x-1 px-2 py-1 text-gray-700 font-medium text-xs">
                                <i class="{{ $item['icon'] }} text-blue-500 w-3"></i>
                                <span>{{ $item['label'] }}</span>
                            </div>
                            <div class="pl-5 space-y-0.5">
                                @foreach($item['submenu'] as $sub)
                                    <a href="{{ $sub['url'] }}" @click="floatingMenuOpen = false" class="block px-2 py-1 text-xs text-gray-600 hover:bg-blue-50 hover:text-blue-500 rounded">
                                        <i class="{{ $sub['icon'] }} mr-1 w-3 text-[10px]"></i> {{ $sub['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $item['url'] }}" @click="floatingMenuOpen = false" class="flex items-center space-x-1 px-2 py-1 text-gray-700 hover:bg-blue-50 hover:text-blue-500 rounded text-xs">
                            <i class="{{ $item['icon'] }} text-blue-500 w-3 text-xs"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
                
                <a href="{{ url('user/media/developer') }}" @click="floatingMenuOpen = false" class="developer-menu flex items-center space-x-1 px-2 py-1 rounded text-xs font-semibold mt-1">
                    <i class="fas fa-globe text-xs"></i>
                    <span>Developer</span>
                    <span class="developer-badge">TEAM</span>
                </a>
                
                <div class="border-t border-gray-100 mt-1 pt-1">
                    @auth
                        @if($role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" target="_blank" @click="floatingMenuOpen = false" class="flex items-center space-x-1 px-2 py-1 bg-emerald-50 text-emerald-600 rounded text-xs">
                                <i class="fas fa-user-shield text-xs"></i>
                                <span>Admin</span>
                            </a>
                        @elseif($role === 'student')
                            <a href="{{ route('student.dashboard') }}" target="_blank" @click="floatingMenuOpen = false" class="flex items-center space-x-1 px-2 py-1 bg-violet-50 text-violet-600 rounded text-xs">
                                <i class="fas fa-calendar-alt text-xs"></i>
                                <span>Events</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login.form') }}" target="_blank" @click="floatingMenuOpen = false" class="flex items-center space-x-1 px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs">
                            <i class="fas fa-sign-in-alt text-xs"></i>
                            <span>Login</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <main class="flex-grow w-full animate-fadeInUp content-wrapper content-bottom-spacing">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
            @yield('content')
        </div>
    </main>

    {{-- ==================== SPONSORS SECTION ==================== --}}
    <div class="sponsor-section">
        <div class="max-w-7xl mx-auto px-4">
            @php
                $groupedSponsors = $groupedSponsors ?? collect();
                $orderedCategories = ['Presented by', 'Official Partners', 'Official Suppliers', 'Supporting Partners', 'Managed by'];
            @endphp

            @if($groupedSponsors->count() > 0)
                @foreach($orderedCategories as $category)
                    @php
                        $matchingKey = null;
                        foreach ($groupedSponsors->keys() as $key) {
                            if (strtolower($key) === strtolower($category) || str_contains(strtolower($key), strtolower($category))) {
                                $matchingKey = $key;
                                break;
                            }
                        }
                    @endphp
                    @if($matchingKey && $groupedSponsors[$matchingKey]->count() > 0)
                        <div class="mb-5">
                            <h4 class="font-medium text-xs uppercase mb-3 text-center text-gray-600 tracking-wider">{{ $category }}</h4>
                            <div class="flex flex-wrap justify-center gap-3">
                                @foreach($groupedSponsors[$matchingKey]->sortBy('created_at') as $sponsor)
                                    <a href="{{ $sponsor->sponsors_web ?? '#' }}" target="_blank" class="transform transition-transform duration-300 hover:scale-105">
                                        <div class="sponsor-card-bg">
                                            @if($sponsor->logo)
                                                <img src="{{ asset('uploads/sponsors/' . $sponsor->logo) }}" alt="{{ $sponsor->sponsor_name }}" class="h-8 md:h-9 w-auto object-contain">
                                            @else
                                                <span class="text-xs font-medium text-gray-700 px-2">{{ $sponsor->sponsor_name }}</span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-sparkles text-2xl mb-2"></i>
                    <p class="text-xs">Sponsor information will be available soon.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="w-full bg-gradient-to-r from-blue-700 to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-3 md:py-4">
            <div class="flex flex-col items-center justify-center text-center">
                <img src="{{ asset('uploads/logo/hsbl.png') }}" alt="SBL Logo" class="h-14 md:h-16 w-auto mb-1">
                <h3 class="text-sm md:text-base font-semibold">Riau Pos - SBL</h3>
                <p class="text-blue-200 text-xs">Student Basketball League</p>
                <div class="mt-2 pt-2 border-t border-blue-400/30 w-full">
                    <p class="text-[10px] md:text-xs text-blue-200">&copy; {{ date('Y') }} Riau Pos - Student Basketball League. All Rights Reserved.</p>
                    <p class="text-[10px] md:text-xs text-blue-200 mt-1">
                        Developed with <i class="fas fa-heart text-red-300 text-[10px]"></i> by Mutia Rizkianti & Wafiq Wardatul Khairani
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({ top: targetElement.offsetTop - 80, behavior: 'smooth' });
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if(link.getAttribute('href') === currentPath || link.getAttribute('href') === (currentPath + '/')) {
                    link.classList.add('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>