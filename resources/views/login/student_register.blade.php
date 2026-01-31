<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - HSBL Student Portal</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1565c0 0%, #1e88e5 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .register-container {
            display: flex;
            width: 1000px;
            min-height: 650px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }

        /* Left Side - Branding */
        .register-left {
            flex: 1.2;
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
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
            opacity: 0.9;
            letter-spacing: 0.5px;
        }

        .benefits {
            margin-top: 30px;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .benefit-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .benefit-icon {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .benefit-icon i {
            font-size: 1.3rem;
            color: #bbdefb;
        }

        .benefit-text h4 {
            font-size: 1.05rem;
            margin-bottom: 3px;
            font-weight: 600;
        }

        .benefit-text p {
            font-size: 0.9rem;
            opacity: 0.9;
            line-height: 1.4;
        }

        .copyright {
            margin-top: auto;
            text-align: center;
            font-size: 0.85rem;
            opacity: 0.8;
            padding-top: 20px;
        }

        /* Right Side - Register Form */
        .register-right {
            flex: 1.5;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #f9fafb;
        }

        .register-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .register-header h2 {
            font-size: 1.8rem;
            color: #1565c0;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .register-header p {
            color: #546e7a;
            font-size: 0.95rem;
            max-width: 450px;
            margin: 0 auto;
            line-height: 1.5;
        }

        .register-form {
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

        .required {
            color: #ef4444;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #546e7a;
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 1px solid #b0bec5;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #1565c0;
            box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.15);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #546e7a;
            cursor: pointer;
            font-size: 1rem;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .password-toggle:hover {
            background: #f3f4f6;
        }

        /* Avatar Upload */
        .avatar-upload {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .avatar-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            background: #e0e0e0;
            border: 2px dashed #90a4ae;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .avatar-preview:hover {
            border-color: #1565c0;
            background: #e3f2fd;
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .avatar-preview i {
            font-size: 2rem;
            color: #90a4ae;
        }

        .avatar-preview:hover i {
            color: #1565c0;
        }

        .avatar-info {
            flex: 1;
        }

        .avatar-info small {
            display: block;
            color: #78909c;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .register-button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #1565c0 0%, #1e88e5 100%);
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
            margin-top: 10px;
        }

        .register-button:hover {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(21, 101, 192, 0.25);
        }

        .register-button:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            color: #546e7a;
            font-size: 0.9rem;
        }

        .login-link a {
            color: #1565c0;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .terms-note {
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #546e7a;
            border-left: 4px solid #42a5f5;
            line-height: 1.5;
        }

        .terms-note i {
            color: #42a5f5;
            margin-right: 8px;
        }

        /* Error Messages */
        .error-message {
            color: #e53935;
            font-size: 0.85rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
            background: #ffebee;
            padding: 10px 12px;
            border-radius: 8px;
            border-left: 4px solid #e53935;
        }

        .success-message {
            color: #43a047;
            font-size: 0.85rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
            background: #e8f5e9;
            padding: 10px 12px;
            border-radius: 8px;
            border-left: 4px solid #43a047;
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

        /* Password Strength Indicator */
        .password-strength {
            margin-top: 10px;
        }

        .strength-meter {
            height: 5px;
            background: #e0e0e0;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 3px;
            transition: all 0.3s;
        }

        .strength-text {
            font-size: 0.8rem;
            color: #78909c;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .register-container {
                flex-direction: column;
                width: 100%;
                max-width: 500px;
            }

            .register-left {
                padding: 30px;
            }

            .register-right {
                padding: 30px;
            }

            .benefits {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .avatar-upload {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .register-header h2 {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Left Side - Branding -->
        <div class="register-left">
            <div class="brand-logo">
                <img src="{{ asset('uploads/logo/hsbl.png') }}" alt="HSBL Logo">
                <h1>HSBL Riau Pos</h1>
                <p>Honda Shooting Basketball League</p>
            </div>
            
            <div class="benefits">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="benefit-text">
                        <h4>Student Benefits</h4>
                        <p>Access exclusive student features and resources</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-basketball-ball"></i>
                    </div>
                    <div class="benefit-text">
                        <h4>Team Participation</h4>
                        <p>Join teams, view schedules and track performance</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="benefit-text">
                        <h4>Event Management</h4>
                        <p>Stay updated with all HSBL events and games</p>
                    </div>
                </div>
            </div>
            
            <div class="copyright">
                <p>© HSBL Riau Pos. Student Portal</p>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="register-right">
            <div class="register-header">
                <h2><i class="fas fa-user-plus"></i> Student Registration</h2>
                <p>Create your account to access the HSBL Student Portal</p>
            </div>

            <form method="POST" action="{{ route('student.register') }}" class="register-form" id="registerForm" enctype="multipart/form-data">
                @csrf
                
                <!-- Display Errors -->
                @if ($errors->any())
                    <div class="error-message" style="margin-bottom: 20px;">
                        <i class="fas fa-exclamation-circle"></i>
                        Please check the form for errors.
                    </div>
                @endif

                @if(session('success'))
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-control" 
                               placeholder="Enter your full name"
                               value="{{ old('name') }}"
                               required
                               autofocus>
                    </div>
                    @error('name')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address <span class="required">*</span></label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="Enter your student email"
                               value="{{ old('email') }}"
                               required>
                    </div>
                    @error('email')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password <span class="required">*</span></label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="Create a strong password (min. 8 characters)"
                               required>
                        <button type="button" class="password-toggle" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="strength-meter">
                            <div class="strength-fill" id="passwordStrengthFill"></div>
                        </div>
                        <div class="strength-text" id="passwordStrengthText">Password strength: None</div>
                    </div>
                    @error('password')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation"><i class="fas fa-lock"></i> Confirm Password <span class="required">*</span></label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="form-control" 
                               placeholder="Re-enter your password"
                               required>
                        <button type="button" class="password-toggle" data-target="password_confirmation">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>


                <button type="submit" class="register-button" id="registerButton">
                    <span class="loading"></span>
                    <i class="fas fa-user-plus"></i> Create Account
                </button>

                <div class="login-link">
                    Already have an account? <a href="{{ route('student.login') }}">Login here</a>
                </div>

                <div class="terms-note">
                    <i class="fas fa-info-circle"></i>
                    By registering, you agree to our Terms of Service and Privacy Policy. Your data will be protected according to our privacy standards.
                </div>
            </form>
        </div>
    </div>

    <script>
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

        // Avatar Preview
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarPreviewImage = document.getElementById('avatarPreviewImage');
        
        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    avatarPreviewImage.src = e.target.result;
                    avatarPreviewImage.style.display = 'block';
                    avatarPreview.querySelector('i').style.display = 'none';
                    avatarPreview.style.border = '2px solid #1565c0';
                    avatarPreview.style.background = '#e3f2fd';
                }
                
                reader.readAsDataURL(file);
            }
        });

        // Password Strength Indicator
        const passwordInput = document.getElementById('password');
        const strengthFill = document.getElementById('passwordStrengthFill');
        const strengthText = document.getElementById('passwordStrengthText');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // Check password strength
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;
            
            // Update strength meter
            const percentages = [0, 20, 40, 60, 80, 100];
            const colors = ['#e53935', '#ef5350', '#ff9800', '#ffb74d', '#4caf50', '#2e7d32'];
            const texts = ['None', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
            
            strengthFill.style.width = percentages[strength] + '%';
            strengthFill.style.backgroundColor = colors[strength];
            strengthText.textContent = 'Password strength: ' + texts[strength];
            strengthText.style.color = colors[strength];
        });

        // Form Submission Handling
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const registerButton = document.getElementById('registerButton');
            const loadingSpinner = registerButton.querySelector('.loading');
            
            // Validate password confirmation
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            // Show loading
            registerButton.disabled = true;
            loadingSpinner.classList.add('active');
            registerButton.innerHTML = '<span class="loading active"></span> Creating Account...';
            
            return true;
        });

        // Auto focus first input
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('name').value === '') {
                document.getElementById('name').focus();
            }
            
            // Add focus effect to inputs
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.boxShadow = '0 0 0 3px rgba(21, 101, 192, 0.15)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.boxShadow = 'none';
                });
            });
        });

        // Avatar preview click handler
        document.getElementById('avatarPreview').addEventListener('click', function() {
            document.getElementById('avatar').click();
        });
    </script>
</body>
</html>