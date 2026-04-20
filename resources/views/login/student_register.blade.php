<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Register · SBL Riau Pos</title>
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
        background: #eff6ff;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        transition: background 0.3s ease;
        position: relative;
    }

    /* ========== DARK MODE ========== */
    body.dark {
        background: #0f172a;
    }

    body.dark .register-wrapper {
        background: rgba(30, 41, 59, 0.95);
        border: 1px solid #334155;
    }

    body.dark .brand-panel {
        background: #1e293b;
        border-right: 1px solid #334155;
    }

    /* Text di Brand Panel */
    body.dark .brand-text h3,
    body.dark .brand-text span,
    body.dark .tagline,
    body.dark .feat-row,
    body.dark .copyright-compact {
        color: #e2e8f0 !important;
    }

    body.dark .tagline {
        color: #93c5fd !important;
        border-left-color: #60a5fa !important;
    }

    body.dark .feat-row {
        color: #cbd5e1 !important;
    }

    body.dark .feat-row i {
        color: #60a5fa !important;
    }

    body.dark .brand-logo-mini {
        background: rgba(59, 130, 246, 0.15);
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    /* Form Panel */
    body.dark .form-panel {
        background: transparent;
    }

    body.dark .form-title,
    body.dark .form-sub,
    body.dark .input-label,
    body.dark .login-link,
    body.dark .note-compact {
        color: #e2e8f0 !important;
    }

    body.dark .form-title {
        color: #93c5fd !important;
    }

    body.dark .login-link a {
        color: #60a5fa !important;
    }

    body.dark .note-compact {
        background: #1e293b !important;
        color: #cbd5e1 !important;
        border-left-color: #3b82f6 !important;
    }

    /* Input Fields */
    body.dark .input-field {
        background: #0f172a;
        border-color: #334155;
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
        color: #60a5fa;
        background: #1e293b;
    }

    /* Button */
    body.dark .btn-register {
        background: #3b82f6;
    }

    body.dark .btn-register:hover {
        background: #2563eb;
    }

    /* Password Strength */
    body.dark .strength-text {
        color: #94a3b8 !important;
    }

    /* Navigasi */
    body.dark .nav-btn,
    body.dark .theme-toggle {
        background: #1e293b;
        color: #60a5fa;
        border-color: #1e3a8a;
    }

    /* ========== TOMBOL NAVIGASI ========== */
    .nav-buttons {
        position: fixed;
        top: 16px;
        left: 16px;
        right: 16px;
        display: flex;
        justify-content: space-between;
        z-index: 1000;
        pointer-events: none;
    }

    .nav-btn, .theme-toggle {
        background: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        color: #1e40af;
        font-size: 1.1rem;
        transition: all 0.2s;
        border: 1px solid #bfdbfe;
        pointer-events: auto;
        text-decoration: none;
    }

    .nav-btn:hover, .theme-toggle:hover {
        transform: scale(1.05);
    }

    /* ========== CARD ========== */
    .register-wrapper {
        width: 100%;
        max-width: 780px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        border-radius: 28px;
        box-shadow: 0 20px 35px -8px rgba(0, 0, 0, 0.15);
        display: flex;
        overflow: hidden;
        border: 1px solid rgba(59, 130, 246, 0.2);
        transition: background 0.3s, border 0.3s;
    }

    /* ========== LEFT PANEL ========== */
    .brand-panel {
        flex: 1;
        background: #f8fafc;
        padding: 1.8rem 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: #1e3a8a;
        border-right: 1px solid #bfdbfe;
    }

    .brand-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        cursor: pointer;
    }

    .brand-logo-mini {
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.02);
        border: 1px solid #93c5fd;
    }

    .brand-logo-mini img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .brand-text h3 {
        font-weight: 700;
        font-size: 1.5rem;
        color: #1e3a8a;
    }

    .brand-text span {
        font-size: 0.7rem;
        opacity: 0.7;
        font-weight: 500;
    }

    .tagline {
        font-size: 0.8rem;
        color: #2563eb;
        margin: 12px 0 20px;
        border-left: 3px solid #3b82f6;
        padding-left: 14px;
        font-weight: 500;
    }

    .compact-features {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .feat-row {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.8rem;
        color: #1e293b;
    }

    .feat-row i {
        width: 24px;
        color: #3b82f6;
        font-size: 1.1rem;
    }

    .copyright-compact {
        margin-top: auto;
        font-size: 0.65rem;
        opacity: 0.6;
        padding-top: 20px;
    }

    /* ========== RIGHT PANEL ========== */
    .form-panel {
        flex: 1.2;
        padding: 1.8rem 1.8rem;
        background: white;
    }

    .form-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e3a8a;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .form-sub {
        font-size: 0.7rem;
        color: #64748b;
        margin-bottom: 20px;
        text-align: center;
    }

    .input-group {
        margin-bottom: 16px;
    }

    .input-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 500;
        color: #334155;
        margin-bottom: 4px;
    }

    .input-field {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        border: 1.5px solid #bfdbfe;
        border-radius: 16px;
        background: white;
        transition: all 0.2s;
        overflow: hidden;
    }

    .input-field:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .input-icon {
        padding-left: 14px;
        color: #3b82f6;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .input-control {
        width: 100%;
        padding: 12px 10px 12px 10px;
        border: none;
        font-size: 0.85rem;
        background: transparent;
        line-height: 1.4;
        outline: none;
        color: inherit;
    }

    .input-control::placeholder {
        color: #94a3b8;
        font-size: 0.8rem;
    }

    .toggle-pw {
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 8px 12px;
        line-height: 1;
        transition: all 0.15s;
        flex-shrink: 0;
    }

    .toggle-pw:hover {
        color: #3b82f6;
    }

    .btn-register {
        width: 100%;
        padding: 13px;
        border: none;
        border-radius: 40px;
        background: #2563eb;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: 0.2s;
        box-shadow: 0 8px 16px -6px rgba(37, 99, 235, 0.3);
        margin-top: 8px;
    }

    .btn-register:hover {
        background: #1d4ed8;
        transform: scale(1.01);
    }

    .btn-register:disabled {
        opacity: 0.6;
        transform: none;
    }

    .login-link {
        text-align: center;
        margin-top: 16px;
        font-size: 0.75rem;
    }

    .login-link a {
        color: #2563eb;
        font-weight: 600;
        text-decoration: none;
    }

    .note-compact {
        margin-top: 16px;
        background: #eff6ff;
        padding: 10px 12px;
        border-radius: 14px;
        font-size: 0.7rem;
        color: #1e3a8a;
        border-left: 4px solid #3b82f6;
    }

    .error-msg, .success-msg {
        padding: 10px 12px;
        border-radius: 14px;
        font-size: 0.75rem;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .error-msg {
        background: #fef2f2;
        color: #b91c1c;
        border-left: 4px solid #ef4444;
    }

    .success-msg {
        background: #f0fdf4;
        color: #166534;
        border-left: 4px solid #22c55e;
    }

    .spinner {
        display: none;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }

    .btn-register.loading .spinner {
        display: inline-block;
    }

    .btn-register.loading .btn-text {
        display: none;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* Password Strength */
    .password-strength {
        margin-top: 6px;
    }

    .strength-meter {
        height: 3px;
        background: #e2e8f0;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 3px;
    }

    .strength-fill {
        height: 100%;
        width: 0%;
        border-radius: 2px;
        transition: all 0.3s;
    }

    .strength-text {
        font-size: 0.65rem;
        color: #64748b;
    }

    .password-match {
        margin-top: 3px;
        font-size: 0.65rem;
    }

    .password-match.matching { color: #10b981; }
    .password-match.not-matching { color: #ef4444; }

    /* Responsive */
    @media (max-width: 700px) {
        body { padding: 12px; align-items: flex-start; }
        .register-wrapper { flex-direction: column; max-width: 420px; margin-top: 55px; }
        .brand-panel { padding: 1.5rem; border-right: none; border-bottom: 1px solid #bfdbfe; }
        body.dark .brand-panel { border-bottom-color: #334155; }
        .compact-features { display: none; }
        .form-panel { padding: 1.5rem; }
    }

    @media (max-width: 480px) {
        .register-wrapper { border-radius: 22px; }
        .brand-panel { padding: 1.2rem; }
        .form-panel { padding: 1.2rem; }
    }
</style>
</head>
<body>
    <div class="nav-buttons">
        <a href="{{ route('login.form') }}" class="nav-btn" title="Kembali">
            <i class="fas fa-arrow-left"></i>
        </a>
        <button class="theme-toggle" id="themeToggle">
            <i class="fas fa-moon"></i>
        </button>
    </div>

    <div class="register-wrapper">
        <div class="brand-panel">
            <div class="brand-header" onclick="window.location.href='{{ route('user.dashboard') }}'">
                <div class="brand-logo-mini">
                    <img src="{{ asset('uploads/logo/hsbl.png') }}" alt="SBL Logo">
                </div>
                <div class="brand-text">
                    <h3>SBL Riau Pos</h3>
                    <span>Student League</span>
                </div>
            </div>
            <div class="tagline">
                Join the league<br>Create your account
            </div>
            <div class="compact-features">
                <div class="feat-row"><i class="fas fa-basketball"></i> <span>Basketball League</span></div>
                <div class="feat-row"><i class="fas fa-users"></i> <span>Team Management</span></div>
                <div class="feat-row"><i class="fas fa-trophy"></i> <span>Achievements</span></div>
            </div>
            <div class="copyright-compact">
                © SBL Riau Pos · Student Portal
            </div>
        </div>

        <div class="form-panel">
            <div class="form-title"><i class="fas fa-user-graduate"></i> Register</div>
            <div class="form-sub">Create your student account</div>

            <form method="POST" action="{{ route('student.register') }}" id="registerForm">
                @csrf

                @if ($errors->any())
                    <div class="error-msg"><i class="fas fa-exclamation-circle"></i> Please check the form</div>
                @endif

                <div class="input-group">
                    <div class="input-label">Full Name</div>
                    <div class="input-field">
                        <span class="input-icon"><i class="fas fa-user"></i></span>
                        <input type="text" name="name" class="input-control" placeholder="Your full name" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-label">Email</div>
                    <div class="input-field">
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" id="email" class="input-control" placeholder="student@example.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-label">Password</div>
                    <div class="input-field">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="password" class="input-control" placeholder="••••••••" required>
                        <button type="button" class="toggle-pw" data-target="password"><i class="far fa-eye"></i></button>
                    </div>
                    <div class="password-strength" id="passwordStrength" style="display: none;">
                        <div class="strength-meter"><div class="strength-fill" id="strengthFill"></div></div>
                        <div class="strength-text" id="strengthText"></div>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-label">Confirm Password</div>
                    <div class="input-field">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="input-control" placeholder="••••••••" required>
                        <button type="button" class="toggle-pw" data-target="password_confirmation"><i class="far fa-eye"></i></button>
                    </div>
                    <div class="password-match" id="passwordMatch"></div>
                </div>

                <button type="submit" class="btn-register" id="registerBtn">
                    <span class="spinner"></span>
                    <span class="btn-text"><i class="fas fa-user-plus"></i> Create Account</span>
                </button>

                <div class="login-link">
                    Have an account? <a href="{{ route('login.form') }}">Login</a>
                </div>

                <div class="note-compact">
                    <i class="fas fa-circle-info me-1"></i>
                    By registering, you agree to participate in SBL.
                </div>
            </form>
        </div>
    </div>

    <script>
        (function() {
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            const icon = themeToggle.querySelector('i');
            
            if (localStorage.getItem('theme') === 'dark') {
                body.classList.add('dark');
                icon.className = 'fas fa-sun';
            }

            themeToggle.addEventListener('click', () => {
                body.classList.toggle('dark');
                icon.className = body.classList.contains('dark') ? 'fas fa-sun' : 'fas fa-moon';
                localStorage.setItem('theme', body.classList.contains('dark') ? 'dark' : 'light');
            });

            document.querySelectorAll('.toggle-pw').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const input = document.getElementById(btn.dataset.target);
                    const icon = btn.querySelector('i');
                    input.type = input.type === 'password' ? 'text' : 'password';
                    icon.className = input.type === 'password' ? 'far fa-eye' : 'far fa-eye-slash';
                });
            });

            const pwd = document.getElementById('password');
            const strengthDiv = document.getElementById('passwordStrength');
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            
            pwd.addEventListener('input', function() {
                const v = this.value;
                if (!v) { strengthDiv.style.display = 'none'; return; }
                strengthDiv.style.display = 'block';
                
                let s = 0;
                if (v.length >= 8) s++; if (/[a-z]/.test(v)) s++; if (/[A-Z]/.test(v)) s++;
                if (/[0-9]/.test(v)) s++; if (/[@$!%*?&]/.test(v)) s++;
                
                const w = [0,20,40,60,80,100], c = ['#ef4444','#f59e0b','#fbbf24','#84cc16','#10b981','#059669'];
                const t = ['Very Weak','Weak','Fair','Good','Strong','Very Strong'];
                strengthFill.style.width = w[s] + '%';
                strengthFill.style.backgroundColor = c[s];
                strengthText.textContent = t[s];
            });

            const confirm = document.getElementById('password_confirmation');
            const matchDiv = document.getElementById('passwordMatch');
            
            function checkMatch() {
                const c = confirm.value;
                if (!c) { matchDiv.innerHTML = ''; return; }
                matchDiv.innerHTML = pwd.value === c ? '<i class="fas fa-check-circle"></i> Passwords match' : '<i class="fas fa-times-circle"></i> Passwords do not match';
                matchDiv.className = 'password-match ' + (pwd.value === c ? 'matching' : 'not-matching');
            }
            pwd.addEventListener('input', checkMatch);
            confirm.addEventListener('input', checkMatch);

            const form = document.getElementById('registerForm');
            form.addEventListener('submit', function(e) {
                const v = pwd.value;
                if (v !== confirm.value) { e.preventDefault(); alert('Passwords do not match!'); return false; }
                if (v.length < 8 || !/[a-z]/.test(v) || !/[A-Z]/.test(v) || !/[0-9]/.test(v) || !/[@$!%*?&]/.test(v)) {
                    e.preventDefault(); alert('Password must be 8+ chars with lowercase, uppercase, number & special char (@$!%*?&)'); return false;
                }
                document.getElementById('registerBtn').classList.add('loading');
            });
        })();
    </script>
</body>
</html>