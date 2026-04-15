<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Login · SBL Riau Pos</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('uploads/logo/hsbl.png') }}" type="image/png" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            background: #f4f7fc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            transition: background 0.3s ease;
            position: relative;
        }

        /* Dark Mode Overrides */
        body.dark {
            background: #0f172a;
        }

        body.dark .login-wrapper {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid #334155;
        }

        body.dark .brand-panel {
            background: #1e293b;
            border-right: 1px solid #334155;
        }

        body.dark .brand-text h3,
        body.dark .brand-text span,
        body.dark .tagline,
        body.dark .feat-row,
        body.dark .copyright-compact,
        body.dark .form-title,
        body.dark .form-sub,
        body.dark .input-label,
        body.dark .checkbox-label,
        body.dark .register-link,
        body.dark .note-compact {
            color: #e2e8f0;
        }

        body.dark .tagline {
            background: linear-gradient(to right, #334155, transparent);
        }

        body.dark .brand-logo-mini {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        body.dark .brand-logo-mini img {
            filter: brightness(1.2) contrast(1.1);
        }

        body.dark .tabs-mini {
            background: #0f172a;
        }

        body.dark .tab-btn {
            color: #94a3b8;
        }

        body.dark .tab-btn.active {
            background: #1e293b;
            color: white;
        }

        body.dark .input-control {
            background: #0f172a;
            border-color: #334155;
            color: white;
        }

        body.dark .input-control::placeholder {
            color: #64748b;
        }

        body.dark .input-icon {
            color: #64748b;
        }

        body.dark .toggle-pw {
            color: #94a3b8;
        }

        body.dark .toggle-pw:hover {
            background: #1e293b;
        }

        body.dark .note-compact {
            background: #0f172a;
        }

        body.dark .btn-login {
            background: #3b82f6;
            box-shadow: 0 8px 16px -6px rgba(59, 130, 246, 0.25);
        }

        body.dark .btn-login:hover {
            background: #2563eb;
        }

        /* Tombol Navigasi */
        .nav-buttons {
            position: fixed;
            top: 20px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            z-index: 1000;
            pointer-events: none;
        }

        .nav-btn {
            background: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            color: #0b1e33;
            font-size: 1.2rem;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            pointer-events: auto;
            text-decoration: none;
        }

        .nav-btn:hover {
            transform: scale(1.05);
            background: #f8fafc;
        }

        body.dark .nav-btn {
            background: #1e293b;
            color: #e2e8f0;
            border-color: #334155;
        }

        body.dark .nav-btn:hover {
            background: #334155;
        }

        .theme-toggle {
            background: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            color: #0b1e33;
            font-size: 1.2rem;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            pointer-events: auto;
        }

        body.dark .theme-toggle {
            background: #1e293b;
            color: #fbbf24;
            border-color: #334155;
        }

        .theme-toggle:hover {
            transform: scale(1.05);
        }

        /* Compact modern card */
        .login-wrapper {
            width: 100%;
            max-width: 860px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-radius: 36px;
            box-shadow: 0 25px 40px -12px rgba(0, 0, 0, 0.2);
            display: flex;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.6);
            transition: background 0.3s, border 0.3s;
        }

        /* Left panel */
        .brand-panel {
            flex: 1;
            background: #ffffff;
            padding: 2.2rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #0f172a;
            border-right: 1px solid #f1f5f9;
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .brand-header:hover {
            opacity: 0.8;
        }

        .brand-logo-mini {
            width: 56px;
            height: 56px;
            background: #fafcff;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            box-shadow: 0 6px 10px rgba(0,0,0,0.02);
            border: 1px solid #eef2ff;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .brand-logo-mini img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: filter 0.3s ease;
        }

        .brand-logo-mini:hover {
            transform: scale(1.05);
        }

        .brand-text h3 {
            font-weight: 700;
            font-size: 1.8rem;
            letter-spacing: -0.02em;
            line-height: 1.2;
            color: #0b1e33;
        }

        .brand-text span {
            font-size: 0.8rem;
            opacity: 0.7;
            font-weight: 500;
            letter-spacing: 1px;
            color: #1e3a8a;
        }

        .tagline {
            font-size: 0.95rem;
            color: #334155;
            margin: 18px 0 28px;
            border-left: 4px solid #1e3a8a;
            padding-left: 18px;
            font-weight: 500;
        }

        .compact-features {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .feat-row {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 0.9rem;
            padding: 4px 0;
            color: #1e293b;
        }

        .feat-row i {
            width: 30px;
            color: #1e3a8a;
            font-size: 1.3rem;
            text-align: center;
        }

        .copyright-compact {
            margin-top: auto;
            font-size: 0.7rem;
            color: #64748b;
            padding-top: 24px;
        }

        /* Right panel */
        .form-panel {
            flex: 1.2;
            padding: 2.2rem 2.2rem;
            background: transparent;
        }

        .tabs-mini {
            display: flex;
            gap: 8px;
            background: #f1f5f9;
            padding: 6px;
            border-radius: 50px;
            margin-bottom: 30px;
        }

        .tab-btn {
            flex: 1;
            border: none;
            background: transparent;
            padding: 12px 8px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab-btn i {
            font-size: 1rem;
        }

        .tab-btn.active {
            background: white;
            color: #0f172a;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }

        body.dark .tab-btn.active {
            background: #1e293b;
            color: white;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        .form-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0b1e33;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-sub {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 28px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #334155;
            margin-bottom: 6px;
        }

        /* Container input field dengan flex */
        .input-field {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            background: white;
            transition: all 0.2s;
            overflow: hidden;
        }

        body.dark .input-field {
            background: #0f172a;
            border-color: #334155;
        }

        .input-field:focus-within {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.08);
        }

        .input-icon {
            padding-left: 16px;
            color: #64748b;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .input-control {
            width: 100%;
            padding: 14px 12px 14px 12px;
            border: none;
            font-size: 0.95rem;
            background: transparent;
            line-height: 1.4;
            outline: none;
            color: inherit;
        }

        .input-control::placeholder {
            color: #94a3b8;
        }

        /* Toggle password - di dalam flex container */
        .toggle-pw {
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 10px 14px;
            line-height: 1;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin: 0;
            border-radius: 0;
        }

        .toggle-pw:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        body.dark .toggle-pw:hover {
            background: #1e293b;
        }

        .row-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 20px 0 28px;
            font-size: 0.85rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #334155;
            cursor: pointer;
        }

        .link-forgot {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: 500;
        }

        body.dark .link-forgot {
            color: #60a5fa;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 60px;
            background: #0b1e33;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 12px 20px -8px rgba(11, 30, 51, 0.2);
        }

        .btn-login.student {
            background: #1e3a8a;
        }

        .btn-login:hover {
            background: #163a5e;
            transform: scale(1.01);
        }

        .btn-login:disabled {
            opacity: 0.6;
            transform: none;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
        }

        .register-link a {
            color: #1e3a8a;
            font-weight: 600;
            text-decoration: none;
        }

        body.dark .register-link a {
            color: #93c5fd;
        }

        .note-compact {
            margin-top: 24px;
            background: #f8fafc;
            padding: 14px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: #334155;
            border-left: 5px solid #1e3a8a;
        }

        .error-msg, .success-msg {
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 0.85rem;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-msg {
            background: #fef2f2;
            color: #b91c1c;
            border-left: 5px solid #ef4444;
        }

        .success-msg {
            background: #f0fdf4;
            color: #166534;
            border-left: 5px solid #22c55e;
        }

        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        .btn-login.loading .spinner {
            display: inline-block;
        }

        .btn-login.loading .btn-text {
            display: none;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* Responsive Design */
        @media (max-width: 700px) {
            body {
                padding: 12px;
                align-items: flex-start;
            }

            .nav-buttons {
                top: 12px;
                left: 12px;
                right: 12px;
            }

            .nav-btn, .theme-toggle {
                width: 40px;
                height: 40px;
            }

            .login-wrapper {
                flex-direction: column;
                max-width: 100%;
                border-radius: 28px;
                margin-top: 60px;
            }

            .brand-panel {
                padding: 1.5rem 1.5rem;
                border-right: none;
                border-bottom: 1px solid #f1f5f9;
            }

            body.dark .brand-panel {
                border-bottom: 1px solid #334155;
            }

            .brand-header {
                margin-bottom: 16px;
            }

            .brand-logo-mini {
                width: 48px;
                height: 48px;
            }

            .brand-text h3 {
                font-size: 1.5rem;
            }

            .tagline {
                margin: 12px 0 16px;
                font-size: 0.85rem;
            }

            .compact-features {
                display: none;
            }

            .copyright-compact {
                padding-top: 16px;
                text-align: center;
            }

            .form-panel {
                padding: 1.5rem 1.5rem;
            }

            .tabs-mini {
                margin-bottom: 24px;
            }

            .tab-btn {
                padding: 10px 6px;
                font-size: 0.85rem;
            }

            .form-title {
                font-size: 1.2rem;
            }

            .form-sub {
                margin-bottom: 20px;
            }

            .input-control {
                padding: 12px 8px;
                font-size: 0.9rem;
            }

            .toggle-pw {
                padding: 8px 12px;
            }

            .btn-login {
                padding: 14px;
            }

            .note-compact {
                margin-top: 20px;
                padding: 12px 14px;
            }
        }

        @media (max-width: 480px) {
            .login-wrapper {
                border-radius: 24px;
            }

            .brand-panel {
                padding: 1.2rem;
            }

            .form-panel {
                padding: 1.2rem;
            }

            .tabs-mini {
                flex-direction: row;
                gap: 4px;
            }

            .tab-btn {
                padding: 8px 4px;
                gap: 4px;
            }

            .tab-btn i {
                font-size: 0.9rem;
            }

            .row-actions {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .input-icon {
                padding-left: 12px;
            }

            .toggle-pw {
                padding: 8px 10px;
            }
        }

        /* Fix untuk layar sangat kecil */
        @media (max-width: 360px) {
            .brand-header {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }

            .tab-btn span {
                display: none;
            }

            .tab-btn i {
                font-size: 1.1rem;
            }
        }

        /* Landscape mode di HP */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                padding: 8px;
            }

            .login-wrapper {
                flex-direction: row;
                max-width: 700px;
            }

            .brand-panel {
                padding: 1rem;
            }

            .compact-features {
                display: none;
            }

            .tagline {
                margin: 8px 0;
            }

            .copyright-compact {
                padding-top: 8px;
            }

            .form-panel {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Tombol Navigasi -->
    <div class="nav-buttons">
        <a href="{{ route('user.dashboard') }}" class="nav-btn" title="Kembali ke Dashboard">
            <i class="fas fa-arrow-left"></i>
        </a>
        <button class="theme-toggle" id="themeToggle" aria-label="Dark/Light Mode">
            <i class="fas fa-moon"></i>
        </button>
    </div>

    <div class="login-wrapper">
        <!-- Left - Brand -->
        <div class="brand-panel">
            <div class="brand-header" onclick="window.location.href='{{ route('user.dashboard') }}'" title="Klik untuk kembali ke Dashboard">
                <div class="brand-logo-mini">
                    <img src="{{ asset('uploads/logo/hsbl.png') }}" alt="SBL Logo">
                </div>
                <div class="brand-text">
                    <h3>SBL Riau Pos</h3>
                    <span>Student Basketball League</span>
                </div>
            </div>
            <div class="tagline">
                One platform · unified access<br>for admins & students
            </div>
            <div class="compact-features">
                <div class="feat-row"><i class="fas fa-shield"></i> <span>Secure access</span></div>
                <div class="feat-row"><i class="fas fa-basketball"></i> <span>Full league tools</span></div>
                <div class="feat-row"><i class="fas fa-users"></i> <span>Separate portals</span></div>
            </div>
            <div class="copyright-compact">
                © SBL Riau Pos · v2.0
            </div>
        </div>

        <!-- Right - Forms -->
        <div class="form-panel">
            <div class="tabs-mini">
                <button class="tab-btn active" data-tab="admin">
                    <i class="fas fa-user-shield"></i>
                    <span>Admin</span>
                </button>
                <button class="tab-btn" data-tab="student">
                    <i class="fas fa-user-graduate"></i>
                    <span>Student</span>
                </button>
            </div>

            <!-- Admin Pane -->
            <div id="pane-admin" class="tab-pane active">
                <div class="form-title"><i class="fas fa-user-shield"></i> Admin Portal</div>
                <div class="form-sub">Administrator sign‑in</div>

                <form method="POST" action="{{ route('login') }}" id="adminLoginForm">
                    @csrf
                    <input type="hidden" name="login_type" value="admin">

                    @if ($errors->any() && old('login_type', 'admin') === 'admin')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
                    @endif

                    <div class="input-group">
                        <div class="input-label">Username</div>
                        <div class="input-field">
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                            <input type="text" name="name" class="input-control" placeholder="admin" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="input-label">Password</div>
                        <div class="input-field">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="adminPass" class="input-control" placeholder="••••••••" required>
                            <button type="button" class="toggle-pw" data-target="adminPass"><i class="far fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="row-actions">
                        <label class="checkbox-label"><input type="checkbox" name="remember"> Remember me</label>
                        <span></span>
                    </div>
                    <button type="submit" class="btn-login" id="adminBtn">
                        <span class="spinner"></span>
                        <span class="btn-text"><i class="fas fa-sign-in-alt"></i> Login as Admin</span>
                    </button>
                    <div class="note-compact">
                        <i class="fas fa-circle-info" style="margin-right: 8px;"></i>Authorized personnel only
                    </div>
                </form>
            </div>

            <!-- Student Pane -->
            <div id="pane-student" class="tab-pane">
                <div class="form-title"><i class="fas fa-user-graduate"></i> Student Portal</div>
                <div class="form-sub">Access your account</div>

                <form method="POST" action="{{ route('student.login') }}" id="studentLoginForm">
                    @csrf
                    <input type="hidden" name="login_type" value="student">

                    @if ($errors->any() && old('login_type', 'admin') === 'student')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
                    @endif
                    @if(session('success') && old('login_type', 'admin') === 'student')
                        <div class="success-msg"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                    @endif

                    <div class="input-group">
                        <div class="input-label">Email</div>
                        <div class="input-field">
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="input-control" placeholder="student@example.com" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="input-label">Password</div>
                        <div class="input-field">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="studentPass" class="input-control" placeholder="••••••••" required>
                            <button type="button" class="toggle-pw" data-target="studentPass"><i class="far fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="row-actions">
                        <label class="checkbox-label"><input type="checkbox" name="remember"> Remember me</label>
                        <a href="{{ route('student.password.request') }}" class="link-forgot">Forgot?</a>
                    </div>
                    <button type="submit" class="btn-login student" id="studentBtn">
                        <span class="spinner"></span>
                        <span class="btn-text"><i class="fas fa-sign-in-alt"></i> Login as Student</span>
                    </button>
                    <div class="register-link">
                        New here? <a href="{{ route('student.register') }}">Create account</a>
                    </div>
                    <div class="note-compact student-note">
                        <i class="fas fa-circle-info"></i> Registered participants only
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            // Dark/Light toggle
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            const icon = themeToggle.querySelector('i');
            
            if (localStorage.getItem('theme') === 'dark') {
                body.classList.add('dark');
                icon.className = 'fas fa-sun';
            }

            themeToggle.addEventListener('click', () => {
                body.classList.toggle('dark');
                if (body.classList.contains('dark')) {
                    icon.className = 'fas fa-sun';
                    localStorage.setItem('theme', 'dark');
                } else {
                    icon.className = 'fas fa-moon';
                    localStorage.setItem('theme', 'light');
                }
            });

            // Tabs
            const tabs = document.querySelectorAll('.tab-btn');
            const panes = {
                admin: document.getElementById('pane-admin'),
                student: document.getElementById('pane-student')
            };

            function switchTab(tabId) {
                tabs.forEach(t => t.classList.remove('active'));
                document.querySelector(`.tab-btn[data-tab="${tabId}"]`).classList.add('active');
                Object.values(panes).forEach(p => p.classList.remove('active'));
                panes[tabId].classList.add('active');
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', () => switchTab(tab.dataset.tab));
            });

            // Toggle password
            document.querySelectorAll('.toggle-pw').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const targetId = btn.dataset.target;
                    const input = document.getElementById(targetId);
                    const icon = btn.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.className = 'far fa-eye-slash';
                    } else {
                        input.type = 'password';
                        icon.className = 'far fa-eye';
                    }
                });
            });

            // Loading
            const adminForm = document.getElementById('adminLoginForm');
            const studentForm = document.getElementById('studentLoginForm');
            const adminBtn = document.getElementById('adminBtn');
            const studentBtn = document.getElementById('studentBtn');

            function setLoading(btn, loading) {
                if (loading) {
                    btn.classList.add('loading');
                    btn.disabled = true;
                } else {
                    btn.classList.remove('loading');
                    btn.disabled = false;
                }
            }

            adminForm.addEventListener('submit', () => setLoading(adminBtn, true));
            studentForm.addEventListener('submit', () => setLoading(studentBtn, true));

            // Auto tab
            const loginType = '{{ old("login_type", "admin") }}';
            if (loginType === 'student') {
                switchTab('student');
                const emailInput = document.querySelector('#pane-student input[name="email"]');
                if (emailInput && emailInput.value === '') emailInput.focus();
            } else {
                const username = document.querySelector('#pane-admin input[name="name"]');
                if (username && username.value === '') username.focus();
            }
        })();
    </script>
</body>
</html>