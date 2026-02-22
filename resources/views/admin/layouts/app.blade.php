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
        /* RESET CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
            height: 100%;
        }

        html {
            height: 100%;
        }

        /* ================================
           HEADER FIXED
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
            z-index: 1030;
            color: white;
        }

        .admin-topbar a {
            color: white !important;
            text-decoration: none;
        }

        .admin-topbar img {
            height: 45px;
            width: auto;
            border-radius: 8px;
        }

        /* ================================
           WRAPPER UTAMA
           ================================ */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
            padding-top: 70px;
        }

        /* ================================
           SIDEBAR
           ================================ */
        .admin-sidebar {
            width: 250px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            color: #fff;
            flex-shrink: 0;
            position: fixed;
            top: 70px;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1020;
            transition: width 0.3s ease;
        }

        /* Sidebar content (sama seperti sebelumnya) */
        .admin-sidebar h2 {
            margin: 25px 0 15px 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #95a5a6;
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        .admin-menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .admin-menu-list li {
            margin-bottom: 2px;
        }

        .admin-menu-list>li>a,
        .admin-dropdown-title {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            cursor: pointer;
            white-space: nowrap;
        }

        .admin-menu-list>li>a i,
        .admin-dropdown-title i {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 10px;
        }

        .admin-menu-list>li>a:hover,
        .admin-dropdown-title:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #3498db;
        }

        .admin-menu-list>li>a.active {
            background: #3498db;
            color: white;
            font-weight: 500;
        }

        /* Submenu */
        .admin-has-submenu {
            position: relative;
        }

        .admin-submenu {
            display: none;
            list-style: none;
            padding: 5px 0;
            margin: 0;
            background: rgba(0, 0, 0, 0.2);
        }

        .admin-has-submenu:hover .admin-submenu,
        .admin-has-submenu.active .admin-submenu {
            display: block;
        }

        .admin-submenu li a {
            display: flex;
            align-items: center;
            padding: 8px 20px 8px 54px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .admin-submenu li a:hover {
            background: rgba(52, 152, 219, 0.2);
            color: #3498db;
            padding-left: 60px;
        }

        .admin-submenu li a.active {
            color: #3498db;
            font-weight: 500;
        }

        /* ================================
           CONTENT AREA (PERBAIKAN UTAMA)
           ================================ */
        .admin-content {
            flex: 1;
            margin-left: 250px;
            display: flex;
            flex-direction: column;
            min-height: calc(100vh - 70px);
            background: #f8f9fa;
        }

        .admin-content-wrapper {
            flex: 1 0 auto;
            padding: 20px 30px;
            width: 100%;
        }

        /* ================================
           FOOTER (PERBAIKAN)
           ================================ */
        .admin-footer {
            background: #1a252f;
            color: #bdc3c7;
            text-align: center;
            padding: 15px 0;
            font-size: 0.85rem;
            border-top: 1px solid #2c3e50;
            width: 100%;
            flex-shrink: 0;
            margin-top: auto;
            /* PENTING: dorong footer ke bawah */
        }

        .admin-footer p {
            margin: 0;
            line-height: 1.5;
        }

        /* ================================
   TABS STYLING - FIXED VERSION
   ================================ */
        .admin-tabs-wrapper {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 24px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            width: 100%;
            position: relative;
            z-index: 100;
        }

        .admin-tabs-container {
            max-width: 100%;
            padding: 0;
            margin: 0;
        }

        .admin-tabs-nav {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            background: white;
            gap: 2px;
            padding: 0;
            margin: 0;
        }

        .admin-tabs-nav::-webkit-scrollbar {
            display: none;
        }

        .admin-tabs-item {
            flex: 1 0 auto;
            min-width: 110px;
            max-width: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 14px 8px;
            text-decoration: none;
            background: white;
            color: #64748b;
            transition: all 0.2s ease;
            border-bottom: 2px solid transparent;
            white-space: nowrap;
            position: relative;
            font-size: 0.85rem;
        }

        .admin-tabs-item:hover {
            background-color: #f8fafc;
            color: #334155;
        }

        .admin-tabs-item.active {
            color: #2563eb;
            font-weight: 600;
            border-bottom: 2px solid #3b82f6;
            background: linear-gradient(to bottom, #ffffff, #f0f9ff);
        }

        .admin-tabs-item.active .tab-icon {
            color: #3b82f6;
        }

        .admin-tabs-item .tab-icon {
            margin-bottom: 6px;
            font-size: 1.1rem;
            color: #94a3b8;
            transition: color 0.2s ease;
        }

        .admin-tabs-item:hover .tab-icon {
            color: #64748b;
        }

        .admin-tabs-item .tab-label {
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        /* Responsive Tabs */
        @media (max-width: 768px) {
            .admin-tabs-item {
                min-width: 90px;
                padding: 12px 4px;
            }

            .admin-tabs-item .tab-icon {
                font-size: 1rem;
                margin-bottom: 4px;
            }

            .admin-tabs-item .tab-label {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 576px) {
            .admin-tabs-item {
                min-width: 75px;
                padding: 10px 2px;
            }

            .admin-tabs-item .tab-icon {
                font-size: 0.9rem;
            }

            .admin-tabs-item .tab-label {
                font-size: 0.65rem;
            }
        }

        /* ================================
           MAIN CONTENT STYLING
           ================================ */
        .admin-main-content {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            width: 100%;
            overflow-x: auto;
        }

        .admin-page-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 1.5rem;
        }

        /* ================================
           RESPONSIVE DESIGN
           ================================ */
        @media (max-width: 991px) {
            .admin-sidebar {
                width: 220px;
            }

            .admin-content {
                margin-left: 220px;
            }
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                width: 70px;
            }

            .admin-sidebar h2 {
                text-align: center;
                padding: 0;
                margin: 20px 0 10px;
                font-size: 0.7rem;
            }

            .admin-menu-list>li>a span,
            .admin-dropdown-title span {
                display: none;
            }

            .admin-menu-list>li>a i,
            .admin-dropdown-title i {
                margin: 0 auto;
                font-size: 1.2rem;
            }

            .admin-menu-list>li>a,
            .admin-dropdown-title {
                justify-content: center;
                padding: 12px 0;
            }

            .admin-submenu li a span {
                display: none;
            }

            .admin-submenu li a {
                justify-content: center;
                padding: 8px 0;
            }

            .admin-submenu li a i {
                margin: 0;
            }

            .admin-content {
                margin-left: 70px;
            }

            .admin-content-wrapper {
                padding: 15px;
            }

            .admin-tabs-wrapper {
                padding: 0 15px;
                margin: -15px -15px 15px -15px;
            }

            .admin-tabs-item {
                min-width: 80px;
                padding: 10px 8px;
            }

            .admin-footer {
                font-size: 0.75rem;
                padding: 10px 0;
            }
        }

        @media (max-width: 576px) {
            .admin-topbar {
                padding: 0 15px;
            }

            .admin-topbar h4 {
                font-size: 0.9rem;
            }

            .admin-topbar img {
                height: 35px;
            }

            .admin-content-wrapper {
                padding: 10px;
            }

            .admin-tabs-item {
                min-width: 70px;
                padding: 8px 4px;
            }

            .admin-tabs-item .tab-label {
                font-size: 0.7rem;
            }

            .admin-footer {
                font-size: 0.7rem;
                padding: 8px 5px;
            }
        }

        /* Utility */
        .overflow-auto {
            overflow: auto !important;
        }

        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
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
                <h4 class="ms-3 mb-0 d-none d-sm-block">Riau Pos - Honda HSBL Administrator</h4>
            </a>
        </div>
        <div class="d-flex align-items-center">
            <i class="fas fa-user-circle fs-4"></i>
            <div class="ms-2 text-start d-none d-md-block">
                <div class="fw-bold">Administrator</div>
                <small class="opacity-75">Super Admin</small>
            </div>
            <a href="{{ route('logout') }}"
                class="btn btn-sm btn-outline-light ms-2 ms-md-3"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> <span class="d-none d-sm-inline">Logout</span>
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
            <!-- Sidebar content tetap sama -->
            <h2>Main</h2>
            <ul class="admin-menu-list">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.all_data') }}" class="{{ request()->routeIs('admin.all_data') ? 'active' : '' }}">
                        <i class="fas fa-database"></i>
                        <span>Master Data</span>
                    </a>
                </li>

                <li class="admin-has-submenu {{ request()->routeIs('admin.tv_*') ? 'active' : '' }}">
                    <div class="admin-dropdown-title">
                        <i class="fas fa-users"></i>
                        <span>Team Verification</span>
                    </div>
                    <ul class="admin-submenu">
                        <li><a href="{{ route('admin.tv_team_list') }}" class="{{ request()->routeIs('admin.tv_team_list') ? 'active' : '' }}"><i></i> <span>Team List</span></a></li>
                        <li><a href="{{ route('admin.tv_team_verification') }}" class="{{ request()->routeIs('admin.tv_team_verification') ? 'active' : '' }}"><i></i> <span> Team Verification</span></a></li>
                        <li><a href="{{ route('admin.tv_team_awards') }}" class="{{ request()->routeIs('admin.tv_team_awards') ? 'active' : '' }}"><i></i> <span>Team Awards</span></a></li>
                    </ul>
                </li>
            </ul>

            <h2>Camp</h2>
            <ul class="admin-menu-list">
                <li>
                    <a href="{{ route('admin.camper_team') }}" class="{{ request()->routeIs('admin.camper_team') ? 'active' : '' }}">
                        <i class="fas fa-campground"></i>
                        <span>Campers</span>
                    </a>
                </li>
            </ul>

            <h2>Publications</h2>
            <ul class="admin-menu-list">
                <li class="admin-has-submenu {{ request()->routeIs('admin.pub_*') ? 'active' : '' }}">
                    <div class="admin-dropdown-title">
                        <i class="fas fa-globe"></i>
                        <span>Website</span>
                    </div>
                    <ul class="admin-submenu">
                        <li><a href="{{ route('admin.pub_schedule.index') }}" class="{{ request()->routeIs('admin.pub_schedule.*') ? 'active' : '' }}"><i></i> <span>Schedules</span></a></li>
                        <li><a href="{{ route('admin.statistics') }}" class="{{ request()->routeIs('admin.statistics') ? 'active' : '' }}"><i></i> <span>Statistics</span></a></li>
                        <li><a href="{{ route('admin.sponsor.sponsor') }}" class="{{ request()->routeIs('admin.sponsor.*') ? 'active' : '' }}"><i"></i> <span>Sponsor</span></a></li>
                    </ul>
                </li>

                <li class="admin-has-submenu {{ request()->routeIs('admin.news*') || request()->routeIs('admin.videos*') || request()->routeIs('admin.gallery*') ? 'active' : '' }}">
                    <div class="admin-dropdown-title">
                        <i class="fas fa-newspaper"></i>
                        <span>Media</span>
                    </div>
                    <ul class="admin-submenu">
                        <li><a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}"><i></i> <span>News</span></a></li>
                        <li><a href="{{ route('admin.videos.index') }}" class="{{ request()->routeIs('admin.videos.*') ? 'active' : '' }}"><i></i> <span>Videos</span></a></li>
                        <li><a href="{{ route('admin.gallery.photos.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}"><i></i> <span>Gallery</span></a></li>
                    </ul>
                </li>
            </ul>

            <h2>User Mgmt</h2>
            <ul class="admin-menu-list">
                <li>
                    <a href="{{ route('admin.resetpassword.index') }}" class="{{ request()->routeIs('admin.resetpassword.*') ? 'active' : '' }}">
                        <i class="fas fa-key"></i>
                        <span>Reset Password</span>
                    </a>
                </li>
            </ul>

            <h2>Terms</h2>
            <ul class="admin-menu-list">
                <li>
                    <a href="{{ route('admin.term_conditions.index') }}" class="{{ request()->routeIs('admin.term_conditions.index') ? 'active' : '' }}">
                        <i class="fas fa-file-contract"></i>
                        <span>S&K Dokumen</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- CONTENT AREA (STRUKTUR DIPERBAIKI) -->
        <main class="admin-content">
            <div class="admin-content-wrapper">
                @yield('content')
            </div>

            <!-- FOOTER - PASTI DI BAWAH -->
            <footer class="admin-footer">
                <p class="mb-0">Copyright © {{ date('Y') }} HSBL Riau Pos. All Rights Reserved.</p>
                <p class="mb-0">Developed by: Mutia Rizkianti | Wafiq Wardatul Khairani</p>
            </footer>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle submenu dengan klik (untuk mobile)
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownTitles = document.querySelectorAll('.admin-dropdown-title');

            dropdownTitles.forEach(title => {
                title.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.closest('.admin-has-submenu');
                    parent.classList.toggle('active');

                    // Tutup dropdown lain
                    document.querySelectorAll('.admin-has-submenu').forEach(item => {
                        if (item !== parent) {
                            item.classList.remove('active');
                        }
                    });
                });
            });

            // Handle active states
            const currentPath = window.location.pathname;
            document.querySelectorAll('.admin-submenu a').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                    link.closest('.admin-has-submenu').classList.add('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>