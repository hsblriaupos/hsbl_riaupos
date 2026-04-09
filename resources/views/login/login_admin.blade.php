<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SBL Riau Pos</title>
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            display: flex;
            width: 1000px;
            min-height: 580px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }

        /* Left Side - Branding */
        .login-left {
            flex: 1.2;
            background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
            color: white;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .brand-logo img {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            margin-bottom: 15px;
            object-fit: contain;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
        }

        .brand-logo h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .brand-logo p {
            font-size: 1rem;
            opacity: 0.8;
            letter-spacing: 0.5px;
        }

        .features {
            margin-top: 30px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .feature-icon {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .feature-icon i {
            font-size: 1.3rem;
        }

        .feature-text h4 {
            font-size: 1.05rem;
            margin-bottom: 3px;
            font-weight: 600;
        }

        .feature-text p {
            font-size: 0.9rem;
            opacity: 0.7;
            line-height: 1.4;
        }

        .copyright {
            margin-top: auto;
            text-align: center;
            font-size: 0.85rem;
            opacity: 0.6;
            padding-top: 20px;
        }

        /* Right Side - Login Form */
        .login-right {
            flex: 1.5;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #f9fafb;
        }

        .tab-container {
            width: 100%;
            margin-bottom: 30px;
        }

        .tabs {
            display: flex;
            background: #edf2f7;
            border-radius: 10px;
            padding: 5px;
            margin-bottom: 30px;
        }

        .tab {
            flex: 1;
            text-align: center;
            padding: 15px;
            font-weight: 600;
            font-size: 1rem;
            color: #64748b;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .tab i {
            font-size: 1.1rem;
        }

        .tab:hover {
            background: rgba(255, 255, 255, 0.5);
            color: #475569;
        }

        .tab.active {
            background: white;
            color: #1a237e;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header h2 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-header p {
            color: #64748b;
            font-size: 0.95rem;
            max-width: 400px;
            margin: 0 auto;
            line-height: 1.5;
        }

        .login-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #37474f;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 1rem;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .password-toggle:hover {
            background: #f3f4f6;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
            cursor: pointer;
        }

        .forgot-password {
            color: #3b82f6;
            text-decoration: none;
            transition: color 0.3s;
            font-weight: 500;
        }

        .forgot-password:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 5px;
        }

        .student-login-button {
            background: linear-gradient(135deg, #1565c0 0%, #1e88e5 100%);
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(26, 35, 126, 0.25);
        }

        .student-login-button:hover {
            box-shadow: 0 7px 20px rgba(21, 101, 192, 0.25);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: #64748b;
            font-size: 0.9rem;
        }

        .register-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .access-note {
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #64748b;
            border-left: 4px solid #3b82f6;
            line-height: 1.5;
        }

        .student-note {
            border-left-color: #42a5f5;
        }

        .access-note i {
            color: #3b82f6;
            margin-right: 8px;
        }

        .student-note i {
            color: #42a5f5;
        }

        /* Error Messages */
        .error-message {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
            background: #fef2f2;
            padding: 10px 12px;
            border-radius: 8px;
            border-left: 4px solid #ef4444;
        }

        .success-message {
            color: #10b981;
            font-size: 0.85rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
            background: #f0fdf4;
            padding: 10px 12px;
            border-radius: 8px;
            border-left: 4px solid #10b981;
            margin-bottom: 20px;
        }

        /* Loading Animation */
        .loading {
            display: none;
        }

        .loading.active {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 900px) {
            .login-container {
                flex-direction: column;
                width: 100%;
                max-width: 500px;
            }

            .login-left {
                padding: 30px;
            }

            .login-right {
                padding: 30px;
            }

            .features {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .tabs {
                flex-direction: column;
                gap: 5px;
            }
            
            .remember-forgot {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .login-header h2 {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="login-left">
            <div class="brand-logo">
                <img src="{{ asset('uploads/logo/hsbl.png') }}" alt="SBL Logo">
                <h1>SBL Riau Pos</h1>
                <p>Student Basketball League</p>
            </div>
            
            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Secure Access</h4>
                        <p>Protected login with encryption for all users</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-basketball-ball"></i>
                    </div>
                    <div class="feature-text">
                        <h4>League Management</h4>
                        <p>Comprehensive system for SBL administration</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Multi-User Platform</h4>
                        <p>Separate portals for administrators and students</p>
                    </div>
                </div>
            </div>
            
            <div class="copyright">
                <p>© SBL Riau Pos. All rights reserved.</p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="tab-container">
                <div class="tabs">
                    <div class="tab active" data-tab="admin">
                        <i class="fas fa-user-shield"></i>
                        Admin Login
                    </div>
                    <div class="tab" data-tab="student">
                        <i class="fas fa-user-graduate"></i>
                        Student Login
                    </div>
                </div>
            </div>

            <!-- Admin Login Form -->
            <div id="admin-tab" class="tab-content active">
                <div class="login-header">
                    <h2><i class="fas fa-user-shield"></i> Admin Portal</h2>
                    <p>Administrator access for managing the SBL league system</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="login-form" id="adminLoginForm">
                    @csrf
                    
                    <!-- Display Errors -->
                    @if ($errors->any() && old('login_type', 'admin') === 'admin')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <input type="hidden" name="login_type" value="admin">

                    <div class="form-group">
                        <label for="admin_name"><i class="fas fa-user"></i> Username</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" 
                                   id="admin_name" 
                                   name="name" 
                                   class="form-control" 
                                   placeholder="Enter admin username"
                                   value="{{ old('name') }}"
                                   required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="admin_password"><i class="fas fa-lock"></i> Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" 
                                   id="admin_password" 
                                   name="password" 
                                   class="form-control" 
                                   placeholder="Enter admin password"
                                   required>
                            <button type="button" class="password-toggle" data-target="admin_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input type="checkbox" id="admin_remember" name="remember">
                            <label for="admin_remember">Remember me</label>
                        </div>
                    </div>

                    <button type="submit" class="login-button" id="adminLoginButton">
                        <span class="loading"></span>
                        <i class="fas fa-sign-in-alt"></i> Login as Admin
                    </button>

                    <div class="access-note">
                        <i class="fas fa-info-circle"></i>
                        For authorized administrators only. All activities are logged and monitored.
                    </div>
                </form>
            </div>

            <!-- Student Login Form -->
            <div id="student-tab" class="tab-content">
                <div class="login-header">
                    <h2><i class="fas fa-user-graduate"></i> Student Portal</h2>
                    <p>Student access for SBL league participants and team members</p>
                </div>

                <form method="POST" action="{{ route('student.login') }}" class="login-form" id="studentLoginForm">
                    @csrf
                    
                    <!-- Display Errors -->
                    @if ($errors->any() && old('login_type', 'admin') === 'student')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if(session('success') && old('login_type', 'admin') === 'student')
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <input type="hidden" name="login_type" value="student">

                    <div class="form-group">
                        <label for="student_email"><i class="fas fa-envelope"></i> Email Address</label>
                        <div class="input-with-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" 
                                   id="student_email" 
                                   name="email" 
                                   class="form-control" 
                                   placeholder="Enter your student email"
                                   value="{{ old('email') }}"
                                   required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="student_password"><i class="fas fa-lock"></i> Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" 
                                   id="student_password" 
                                   name="password" 
                                   class="form-control" 
                                   placeholder="Enter your password"
                                   required>
                            <button type="button" class="password-toggle" data-target="student_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input type="checkbox" id="student_remember" name="remember">
                            <label for="student_remember">Remember me</label>
                        </div>
                        <a href="{{ route('student.password.request') }}" class="forgot-password">Forgot Password?</a>
                    </div>

                    <button type="submit" class="login-button student-login-button" id="studentLoginButton">
                        <span class="loading"></span>
                        <i class="fas fa-sign-in-alt"></i> Login as Student
                    </button>

                    <div class="register-link">
                        Don't have an account? <a href="{{ route('student.register') }}">Register here</a>
                    </div>

                    <div class="access-note student-note">
                        <i class="fas fa-info-circle"></i>
                        This portal is for registered SBL students only. Contact administrator for account issues.
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Tab Switching
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Update active tab
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Show corresponding content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(`${tabId}-tab`).classList.add('active');
                
                // Focus on first input in active tab
                setTimeout(() => {
                    const firstInput = document.getElementById(`${tabId}-tab`).querySelector('input[type="text"], input[type="email"]');
                    if (firstInput) firstInput.focus();
                }, 100);
            });
        });

        // Toggle Password Visibility
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? 
                    '<i class="fas fa-eye"></i>' : 
                    '<i class="fas fa-eye-slash"></i>';
            });
        });

        // Form Submission Handling
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            const loginButton = document.getElementById('adminLoginButton');
            const loadingSpinner = loginButton.querySelector('.loading');
            
            loginButton.disabled = true;
            loadingSpinner.classList.add('active');
            loginButton.innerHTML = '<span class="loading active"></span> Authenticating...';
        });

        document.getElementById('studentLoginForm').addEventListener('submit', function(e) {
            const loginButton = document.getElementById('studentLoginButton');
            const loadingSpinner = loginButton.querySelector('.loading');
            
            loginButton.disabled = true;
            loadingSpinner.classList.add('active');
            loginButton.innerHTML = '<span class="loading active"></span> Authenticating...';
        });

        // Auto-switch tab based on previous errors
        document.addEventListener('DOMContentLoaded', function() {
            const loginType = '{{ old("login_type", "admin") }}';
            
            if (loginType === 'student') {
                // Switch to student tab
                document.querySelector('.tab[data-tab="admin"]').classList.remove('active');
                document.querySelector('.tab[data-tab="student"]').classList.add('active');
                
                document.getElementById('admin-tab').classList.remove('active');
                document.getElementById('student-tab').classList.add('active');
                
                // Focus on email if empty
                if (document.getElementById('student_email').value === '') {
                    document.getElementById('student_email').focus();
                }
            } else {
                // Focus on username if empty
                if (document.getElementById('admin_name').value === '') {
                    document.getElementById('admin_name').focus();
                }
            }
            
            // Add focus effect to inputs
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.15)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.boxShadow = 'none';
                });
            });
        });
    </script>
</body>
</html>