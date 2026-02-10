<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - HSBL Riau Pos')</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('uploads/logo/hsbl.png') }}" type="image/png" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* RESET CSS KONFLIK DARI USER */
        .topbar,
        .wrapper,
        .sidebar,
        .menu-list,
        .submenu,
        .has-submenu,
        .content,
        .footer {
            all: unset !important;
        }

        /* ADMIN LAYOUT STYLING */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
        }

        /* ================================
           HEADER
           ================================ */
        .admin-topbar {
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1100; /* TERTINGGI */
            color: white;
        }

        .admin-topbar a {
            color: white !important;
            text-decoration: none;
        }

        .admin-topbar img {
            height: 50px;
            border-radius: 8px;
        }

        .admin-topbar h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.2rem;
        }

        /* ================================
           WRAPPER & MAIN LAYOUT
           ================================ */
        .admin-wrapper {
            display: flex;
            margin-top: 70px;
            min-height: calc(100vh - 70px); /* Hanya kurangi header */
        }

        /* ================================
           SIDEBAR
           ================================ */
        .admin-sidebar {
            width: 250px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            color: #fff;
            padding: 20px 0;
            flex-shrink: 0;
            height: calc(100vh - 70px);
            overflow-y: auto;
            position: sticky;
            top: 70px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 900;
        }

        /* Section Titles */
        .admin-sidebar h2 {
            margin: 25px 0 15px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #95a5a6;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Menu list */
        .admin-menu-list {
            list-style: none;
            padding: 0;
            margin: 0 0 0 15px;
        }

        .admin-menu-list li {
            margin-bottom: 2px;
            position: relative;
        }

        .admin-menu-list>li>a,
        .admin-dropdown-title {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            border-radius: 8px 0 0 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .admin-menu-list>li>a:hover,
        .admin-dropdown-title:hover {
            background: rgba(255, 255, 255, 0.1);
            padding-left: 25px;
            color: #3498db;
        }

        .admin-menu-list>li>a.active {
            background: #3498db;
            color: white;
            font-weight: 500;
        }

        /* Submenu */
        .admin-submenu {
            display: none;
            list-style: none;
            padding: 5px 0 5px 15px;
            margin: 0 0 0 20px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 0 0 0 8px;
        }

        .admin-has-submenu:hover .admin-submenu {
            display: block;
        }

        .admin-submenu li a {
            display: block;
            padding: 8px 15px;
            color: #bdc3c7;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .admin-submenu li a:hover {
            background: rgba(52, 152, 219, 0.2);
            color: #3498db;
            padding-left: 20px;
        }

        /* ================================
           CONTENT AREA
           ================================ */
        .admin-content {
            flex-grow: 1;
            padding: 0;
            background: #f8f9fa;
            overflow-y: auto;
            min-height: calc(100vh - 70px);
            position: relative;
        }

        /* Content wrapper dengan padding untuk footer */
        .admin-content-wrapper {
            padding: 0 30px 60px; /* Bottom padding lebih besar untuk footer */
            min-height: calc(100vh - 110px); /* Kurangi header + footer */
        }

        /* ================================
           TABS AREA
           ================================ */
        .admin-tabs-area {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 70px;
            z-index: 1000; /* DI ATAS SIDEBAR */
            margin: 0 -30px 25px -30px;
        }

        .admin-tabs-nav {
            display: flex;
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .admin-tabs-nav::-webkit-scrollbar {
            display: none;
        }

        .admin-tabs-item {
            flex: 1;
            min-width: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 14px 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            border-right: 1px solid #f1f5f9;
            color: #64748b;
            position: relative;
            white-space: nowrap;
        }

        .admin-tabs-item:last-child {
            border-right: none;
        }

        .admin-tabs-item:hover {
            background-color: #f8fafc;
            color: #334155;
        }

        .admin-tabs-item.active {
            color: #1d4ed8;
            font-weight: 600;
            background: linear-gradient(to bottom, #eff6ff, #ffffff);
            border-bottom: 3px solid #3b82f6;
        }

        .admin-tabs-item.active .tab-icon {
            color: #3b82f6;
        }

        .admin-tabs-item .tab-icon {
            margin-bottom: 6px;
            color: #94a3b8;
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .admin-tabs-item:hover .tab-icon {
            color: #64748b;
        }

        .admin-tabs-item .tab-label {
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.025em;
        }

        /* ================================
           FOOTER TIDAK NGIMPIT SIDEBAR
           ================================ */
        .admin-footer {
            background: #1a252f;
            color: #bdc3c7;
            text-align: center;
            padding: 8px 0;
            font-size: 0.75rem;
            position: fixed;
            bottom: 0;
            left: 250px; /* MUNDUR SESUAI LEBAR SIDEBAR */
            right: 0;
            z-index: 1200; /* PALING TINGGI */
            height: 40px; /* TIPIS */
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border-top: 1px solid #2c3e50;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.15);
            transition: left 0.3s ease; /* Animasi saat sidebar mengecil */
        }

        .admin-footer p {
            margin: 0;
            line-height: 1.2;
        }

        .admin-footer .mb-1 {
            margin-bottom: 2px !important;
        }

        .admin-footer .mb-0 {
            margin-bottom: 0 !important;
        }

        /* ================================
           MAIN CONTENT STYLING
           ================================ */
        .admin-main-content {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .admin-page-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 1.5rem;
        }

        .admin-page-subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        /* ================================
        TABS STYLES (REVISED - tidak sticky)
        ================================ */
        .admin-tabs-container {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            position: relative;
            /* Bukan sticky */
            z-index: 10;
            margin: 0;
        }

        .admin-tabs-nav {
            display: flex;
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .admin-tabs-nav::-webkit-scrollbar {
            display: none;
        }

        .admin-tabs-item {
            flex: 1;
            min-width: 100px;
            max-width: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 14px 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            border-right: 1px solid #f3f4f6;
            color: #6b7280;
            position: relative;
            white-space: nowrap;
        }

        /* ... rest of tabs styles tetap sama ... */

        /* ================================
           TABS STYLES (FIXED - tidak menimpa konten)
           ================================ */
        .admin-tabs-wrapper {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 70px;
            z-index: 900;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin: 0;
            padding: 0;
        }

        .admin-tabs-container {
            padding: 0 30px;
        }

        .admin-tabs-nav {
            display: flex;
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            /* Firefox */
        }

        .admin-tabs-nav::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Edge */
        }

        .admin-tabs-item {
            flex: 1;
            min-width: 100px;
            max-width: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 12px 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            border-right: 1px solid #f3f4f6;
            color: #6b7280;
            position: relative;
            white-space: nowrap;
        }

        .admin-tabs-item:last-child {
            border-right: none;
        }

        .admin-tabs-item:hover {
            background-color: #f9fafb;
            color: #374151;
        }

        .admin-tabs-item.active {
            background: linear-gradient(to bottom, #eff6ff, white);
            color: #1d4ed8;
            font-weight: 600;
            border-bottom: 2px solid #3b82f6;
        }

        .admin-tabs-item.active .tab-icon {
            color: #3b82f6;
        }

        .admin-tabs-item .tab-icon {
            margin-bottom: 6px;
            color: #9ca3af;
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .admin-tabs-item:hover .tab-icon {
            color: #6b7280;
        }

        .admin-tabs-item .tab-label {
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.025em;
        }

        /* ================================
           RESPONSIVE DESIGN
           ================================ */
        @media (max-width: 768px) {
            /* Sidebar Mobile */
            .admin-sidebar {
                width: 70px;
                z-index: 800;
            }

            .admin-sidebar h2,
            .admin-menu-list>li>a span,
            .admin-dropdown-title span {
                display: none;
            }

            .admin-sidebar h2::after {
                content: "...";
            }

            /* Footer Mobile */
            .admin-footer {
                left: 70px; /* Sesuai lebar sidebar mobile */
                height: 38px;
                padding: 6px 0;
                font-size: 0.7rem;
            }

            /* Content Mobile */
            .admin-content-wrapper {
                padding: 0 15px 50px;
            }

            .admin-tabs-area {
                padding: 0 15px;
                margin: 0 -15px 20px -15px;
                z-index: 1000;
            }

            .admin-tabs-item {
                min-width: 85px;
                padding: 12px 6px;
            }

            .admin-tabs-item .tab-icon {
                font-size: 12px;
                margin-bottom: 4px;
            }

            .admin-tabs-item .tab-label {
                font-size: 11px;
            }

            .admin-main-content {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            /* Topbar Mobile */
            .admin-topbar {
                padding: 0 15px;
            }

            .admin-topbar h4 {
                font-size: 1rem;
            }

            /* Footer Mobile Full Width */
            .admin-footer {
                left: 0; /* Footer full width di mobile */
                font-size: 0.65rem;
                padding: 5px 0;
                height: 36px;
            }

            /* Content Mobile */
            .admin-content-wrapper {
                padding: 0 15px 45px;
            }

            .admin-tabs-item {
                min-width: 75px;
                padding: 10px 4px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- HEADER -->
    <header class="admin-topbar">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                <img src="{{ asset('uploads/logo/hsbl.png') }}" alt="HSBL Logo">
                <h4 class="ms-3 mb-0">Riau Pos - Honda HSBL Administrator</h4>
            </a>
        </div>
        <div class="d-flex align-items-center">
            <i class="fas fa-user-circle fs-4"></i>
            <div class="ms-2 text-start">
                <div class="fw-bold">Administrator</div>
                <small class="opacity-75">Super Admin</small>
            </div>
            <a href="{{ route('logout') }}"
                class="btn btn-sm btn-outline-light ms-3"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </header>

    <!-- MAIN WRAPPER -->
    <div class="admin-wrapper">

        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <h2>Main Menu</h2>
            <ul class="admin-menu-list">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.all_data') }}" class="{{ request()->routeIs('admin.all_data') ? 'active' : '' }}">
                        <i class="fas fa-database me-2"></i> Master Data
                    </a>
                </li>

                <li class="admin-has-submenu">
                    <div class="admin-dropdown-title">
                        <i class="fas fa-users me-2"></i> Team Verification
                    </div>
                    <ul class="admin-submenu">
                        <li><a href="{{ route('admin.tv_team_list') }}">Team List</a></li>
                        <li><a href="{{ route('admin.tv_team_verification') }}">Team Verification Online</a></li>
                        <li><a href="{{ route('admin.tv_team_awards') }}">Team Awards</a></li>
                    </ul>
                </li>
            </ul>

            <h2>Camp</h2>
            <ul class="admin-menu-list">
                <li>
                    <a href="{{ route('admin.camper_team') }}" class="{{ request()->routeIs('admin.camper_team') ? 'active' : '' }}">
                        <i class="fas fa-campground me-2"></i> Campers
                    </a>
                </li>
            </ul>

            <h2>Publications</h2>
            <ul class="admin-menu-list">
                <li class="admin-has-submenu">
                    <div class="admin-dropdown-title">
                        <i class="fas fa-globe me-2"></i> Website
                    </div>
                    <ul class="admin-submenu">
                        <!-- REVISI: Mengarah ke route admin.pub_schedule.index -->
                        <li><a href="{{ route('admin.pub_schedule.index') }}" class="{{ request()->routeIs('admin.pub_schedule.*') ? 'active' : '' }}">Schedules and Results</a></li>
                        <li><a href="{{ route('admin.statistics') }}">Statistics</a></li>
                         <li><a href="{{ route('admin.sponsor.sponsor') }}">Sponsor</a></li>
                    </ul>
                </li>

                <li class="admin-has-submenu">
                    <div class="admin-dropdown-title">
                        <i class="fas fa-newspaper me-2"></i> Media
                    </div>
                    <ul class="admin-submenu">
                        <li><a href="{{ route('admin.news.index') }}">News</a></li>
                        <li><a href="{{ route('admin.videos.index') }}">Videos</a></li>
                        <!-- REVISI: Menu Gallery mengarah ke halaman photos_list -->
                        <li><a href="{{ route('admin.gallery.photos.index') }}" 
                               class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                            Gallery
                        </a></li>
                    </ul>
                </li>
            </ul>

            <!-- PENAMBAHAN: Menu Reset User Password -->
            <h2>User Management</h2>
            <ul class="admin-menu-list">
                <li>
                    <a href="{{ route('admin.resetpassword.index') }}" 
                       class="{{ request()->routeIs('admin.resetpassword.*') ? 'active' : '' }}">
                        <i class="fas fa-key me-2"></i> Reset User Password
                    </a>
                </li>
            </ul>

            <h2>Term and Conditions</h2>
            <ul class="admin-menu-list">
                <li>
                    <a href="{{ route('admin.term_conditions.index') }}" class="{{ request()->routeIs('admin.term_conditions.index') ? 'active' : '' }}">
                        <i class="fas fa-file-contract me-2"></i> Manage S&K Dokumen
                    </a>
                </li>
            </ul>
        </aside>

        <!-- CONTENT AREA -->
        <main class="admin-content">
            <div class="admin-content-wrapper">
                <!-- Content akan dimasukkan di sini -->
                @yield('content')
            </div>
        </main>
    </div>

    <!-- FOOTER TIDAK NGIMPIT SIDEBAR -->
    <footer class="admin-footer">
        <p class="mb-1">Copyright © {{ date('Y') }} HSBL Riau Pos. All Rights Reserved.</p>
        <p class="mb-0">Developed with ❤️ by: Mutia Rizkianti | Wafiq Wardatul Khairani</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Smooth scroll untuk tab navigation
        document.addEventListener('DOMContentLoaded', function() {
            const tabItems = document.querySelectorAll('.admin-tabs-item');
            if(tabItems.length > 0) {
                tabItems.forEach(item => {
                    item.addEventListener('click', function(e) {
                        // Jika link internal (anchor), smooth scroll
                        const href = this.getAttribute('href');
                        if(href && href.startsWith('#')) {
                            e.preventDefault();
                            const targetId = href.substring(1);
                            const targetElement = document.getElementById(targetId);
                            if(targetElement) {
                                targetElement.scrollIntoView({ 
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        }
                    });
                });
            }
        });
    </script>

    <script>
        // Auto refresh hanya untuk halaman tertentu
        const pagesWithAutoRefresh = [
            'admin.dashboard',
            'admin.all_data', 
            'admin.tv_team_verification',
            'admin.tv_team_list',
            'admin.pub_schedule.index',
            'admin.gallery.photos.index',
            'admin.resetpassword.index' // Ditambahkan reset password ke auto-refresh
        ];
        
        // Cek route saat ini
        const currentRoute = "{{ Route::currentRouteName() }}";
        
        if (pagesWithAutoRefresh.includes(currentRoute)) {
            console.log('Auto refresh enabled for:', currentRoute);
            
            // Cek dulu apakah user sedang tidak aktif
            let lastActivity = Date.now();
            ['click', 'keypress', 'mousemove', 'scroll'].forEach(ev => {
                window.addEventListener(ev, () => lastActivity = Date.now());
            });
            
            // Tunggu 2 menit, tapi cek dulu activity terakhir
            setTimeout(() => {
                if (Date.now() - lastActivity > 30000) { // 30 detik tidak aktif
                    console.log('Auto refreshing page...');
                    location.reload();
                } else {
                    console.log('Auto refresh skipped - user is active');
                }
            }, 120000); // 2 menit
        }
    </script>
    
    @stack('scripts')
</body>

</html>