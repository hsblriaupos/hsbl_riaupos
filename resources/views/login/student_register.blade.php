<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - HSBL Student Portal</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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

        /* Form Guidance */
        .form-guidance {
            background: #f8fafc;
            border-radius: 8px;
            padding: 10px 12px;
            margin-top: 8px;
            border-left: 4px solid #42a5f5;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .form-guidance.error {
            border-left-color: #e53935;
            background: #ffebee;
            color: #c62828;
            display: none;
        }

        .form-guidance.success {
            border-left-color: #4caf50;
            background: #e8f5e9;
            color: #2e7d32;
            display: none;
        }

        .form-guidance.warning {
            border-left-color: #ff9800;
            background: #fff3e0;
            color: #ef6c00;
        }

        .guidance-content {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .guidance-content i {
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* Email Validation */
        .email-validation {
            margin-top: 5px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .email-validation.valid {
            color: #4caf50;
        }

        .email-validation.invalid {
            color: #e53935;
        }

        /* Password Match Indicator */
        .password-match {
            margin-top: 5px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .password-match.matching {
            color: #4caf50;
        }

        .password-match.not-matching {
            color: #e53935;
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
            display: none;
        }

        .password-strength.visible {
            display: block;
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

        /* Password Requirements */
        .password-requirements {
            margin-top: 5px;
            padding-left: 20px;
        }

        .password-requirements ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .password-requirements li {
            font-size: 0.8rem;
            color: #546e7a;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .requirement-met {
            color: #4caf50;
        }

        .requirement-unmet {
            color: #e53935;
        }

        .requirement-met i {
            color: #4caf50;
        }

        .requirement-unmet i {
            color: #e53935;
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
                        <i class="fas fa-basketball-ball"></i>
                    </div>
                    <div class="benefit-text">
                        <h4>Basketball League</h4>
                        <p>Join HSBL basketball competitions</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="benefit-text">
                        <h4>Team Management</h4>
                        <p>Create or join basketball teams</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="benefit-text">
                        <h4>Achievements</h4>
                        <p>Track your basketball achievements</p>
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
                <h2><i class="fas fa-user-graduate"></i> Student Registration</h2>
                <p>Create your student account to access the HSBL Basketball League</p>
            </div>

            <form method="POST" action="{{ route('student.register') }}" class="register-form" id="registerForm">
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
                               placeholder="student@example.com"
                               value="{{ old('email') }}"
                               required>
                    </div>
                    
                    <!-- Email Validation Message -->
                    <div class="email-validation" id="emailValidation">
                        <i class="fas fa-info-circle"></i>
                        <span>Use your personal email address</span>
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
                               placeholder="Create a strong password"
                               required
                               oncopy="return false" 
                               onpaste="return false"
                               oncut="return false">
                        <button type="button" class="password-toggle" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="password-requirements" id="passwordRequirements">
                        <ul>
                            <li id="reqLength" class="requirement-unmet">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                <span>Minimum 8 characters</span>
                            </li>
                            <li id="reqLowercase" class="requirement-unmet">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                <span>At least one lowercase letter</span>
                            </li>
                            <li id="reqUppercase" class="requirement-unmet">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                <span>At least one uppercase letter</span>
                            </li>
                            <li id="reqNumber" class="requirement-unmet">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                <span>At least one number</span>
                            </li>
                            <li id="reqSpecial" class="requirement-unmet">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                <span>At least one special character (@$!%*?&)</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Password Strength -->
                    <div class="password-strength" id="passwordStrengthContainer">
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
                               placeholder="Confirm your password"
                               required
                               oncopy="return false" 
                               onpaste="return false"
                               oncut="return false">
                        <button type="button" class="password-toggle" data-target="password_confirmation">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    
                    <!-- Password Match Indicator -->
                    <div class="password-match" id="passwordMatchIndicator">
                        <i class="fas fa-info-circle"></i>
                        <span>Re-enter password to confirm</span>
                    </div>
                </div>

                <button type="submit" class="register-button" id="registerButton">
                    <span class="loading"></span>
                    <i class="fas fa-user-plus"></i> Create Student Account
                </button>

                <div class="login-link">
                    Already have an account? <a href="{{ route('login.form') }}">Login here</a>
                </div>

                <div class="terms-note">
                    <i class="fas fa-info-circle"></i>
                    By registering as a student, you agree to participate in HSBL basketball competitions and follow all rules.
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize SweetAlert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
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

        // Prevent copy-paste in password fields
        document.querySelectorAll('#password, #password_confirmation').forEach(input => {
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                Toast.fire({
                    icon: 'error',
                    title: 'Pasting not allowed in password fields'
                });
                return false;
            });
            
            input.addEventListener('copy', function(e) {
                e.preventDefault();
                Toast.fire({
                    icon: 'error',
                    title: 'Copying not allowed in password fields'
                });
                return false;
            });
            
            input.addEventListener('cut', function(e) {
                e.preventDefault();
                Toast.fire({
                    icon: 'error',
                    title: 'Cutting not allowed in password fields'
                });
                return false;
            });
        });

        // Password Strength Indicator and Requirements Checker
        const passwordInput = document.getElementById('password');
        const strengthContainer = document.getElementById('passwordStrengthContainer');
        const strengthFill = document.getElementById('passwordStrengthFill');
        const strengthText = document.getElementById('passwordStrengthText');
        
        // Password requirements elements
        const reqLength = document.getElementById('reqLength');
        const reqLowercase = document.getElementById('reqLowercase');
        const reqUppercase = document.getElementById('reqUppercase');
        const reqNumber = document.getElementById('reqNumber');
        const reqSpecial = document.getElementById('reqSpecial');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const requirements = document.getElementById('passwordRequirements');
            
            if (password.length === 0) {
                requirements.style.display = 'none';
                strengthContainer.style.display = 'none';
                resetRequirements();
                return;
            }
            
            requirements.style.display = 'block';
            strengthContainer.style.display = 'block';
            
            // Check password requirements
            const hasLength = password.length >= 8;
            const hasLowercase = /[a-z]/.test(password);
            const hasUppercase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[@$!%*?&]/.test(password);
            
            // Update requirement indicators
            updateRequirement(reqLength, hasLength, 'Minimum 8 characters');
            updateRequirement(reqLowercase, hasLowercase, 'At least one lowercase letter');
            updateRequirement(reqUppercase, hasUppercase, 'At least one uppercase letter');
            updateRequirement(reqNumber, hasNumber, 'At least one number');
            updateRequirement(reqSpecial, hasSpecial, 'At least one special character (@$!%*?&)');
            
            // Calculate strength score
            let strength = 0;
            if (hasLength) strength++;
            if (hasLowercase) strength++;
            if (hasUppercase) strength++;
            if (hasNumber) strength++;
            if (hasSpecial) strength++;
            
            // Update strength meter
            const percentages = [0, 20, 40, 60, 80, 100];
            const colors = ['#e53935', '#ef5350', '#ff9800', '#ffb74d', '#4caf50', '#2e7d32'];
            const texts = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
            
            strengthFill.style.width = percentages[strength] + '%';
            strengthFill.style.backgroundColor = colors[strength];
            strengthText.textContent = 'Password strength: ' + texts[strength];
            strengthText.style.color = colors[strength];
        });

        function updateRequirement(element, isMet, text) {
            if (isMet) {
                element.className = 'requirement-met';
                element.innerHTML = `<i class="fas fa-check-circle"></i><span>${text}</span>`;
            } else {
                element.className = 'requirement-unmet';
                element.innerHTML = `<i class="fas fa-times-circle"></i><span>${text}</span>`;
            }
        }

        function resetRequirements() {
            const requirements = [
                { element: reqLength, text: 'Minimum 8 characters' },
                { element: reqLowercase, text: 'At least one lowercase letter' },
                { element: reqUppercase, text: 'At least one uppercase letter' },
                { element: reqNumber, text: 'At least one number' },
                { element: reqSpecial, text: 'At least one special character (@$!%*?&)' }
            ];
            
            requirements.forEach(req => {
                req.element.className = 'requirement-unmet';
                req.element.innerHTML = `<i class="fas fa-circle" style="font-size: 0.5rem;"></i><span>${req.text}</span>`;
            });
        }

        // Password Match Validation
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const passwordMatchIndicator = document.getElementById('passwordMatchIndicator');
        
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = passwordConfirmInput.value;
            
            if (confirmPassword.length === 0) {
                passwordMatchIndicator.className = 'password-match';
                passwordMatchIndicator.innerHTML = '<i class="fas fa-info-circle"></i><span>Re-enter password to confirm</span>';
                return;
            }
            
            if (password === confirmPassword && password.length > 0) {
                passwordMatchIndicator.className = 'password-match matching';
                passwordMatchIndicator.innerHTML = '<i class="fas fa-check-circle"></i><span>Passwords match</span>';
            } else {
                passwordMatchIndicator.className = 'password-match not-matching';
                passwordMatchIndicator.innerHTML = '<i class="fas fa-times-circle"></i><span>Passwords do not match</span>';
            }
        }
        
        passwordInput.addEventListener('input', checkPasswordMatch);
        passwordConfirmInput.addEventListener('input', checkPasswordMatch);

        // Personal Email Validation - Disesuaikan dengan controller
        function isValidPersonalEmail(email) {
            if (!email) return false;
            
            // Semua email diperbolehkan untuk registrasi siswa
            // Controller akan memvalidasi uniqueness
            return true;
        }

        // Email Validation
        const emailInput = document.getElementById('email');
        const emailValidation = document.getElementById('emailValidation');
        
        emailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            
            if (!email) {
                emailValidation.className = 'email-validation';
                emailValidation.innerHTML = '<i class="fas fa-info-circle"></i><span>Enter your email address</span>';
                return;
            }
            
            // Validasi format email sederhana
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                emailValidation.className = 'email-validation invalid';
                emailValidation.innerHTML = '<i class="fas fa-times-circle"></i><span>Please enter a valid email address</span>';
                return;
            }
            
            emailValidation.className = 'email-validation valid';
            emailValidation.innerHTML = '<i class="fas fa-check-circle"></i><span>Valid email format</span>';
        });

        // Form Submission Handling dengan SweetAlert
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const registerButton = document.getElementById('registerButton');
            const loadingSpinner = registerButton.querySelector('.loading');
            
            // Get form values
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            // Validate required fields
            if (!name) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please enter your full name',
                    confirmButtonColor: '#1565c0'
                });
                return false;
            }
            
            if (!email) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please enter your email address',
                    confirmButtonColor: '#1565c0'
                });
                return false;
            }
            
            if (!password) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please create a password',
                    confirmButtonColor: '#1565c0'
                });
                return false;
            }
            
            if (!confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please confirm your password',
                    confirmButtonColor: '#1565c0'
                });
                return false;
            }
            
            // Email format validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Email',
                    text: 'Please enter a valid email address',
                    confirmButtonColor: '#1565c0'
                });
                return false;
            }
            
            // Password confirmation validation
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Passwords do not match. Please make sure both passwords are identical.',
                    confirmButtonColor: '#1565c0'
                });
                return false;
            }
            
            // Password strength validation sesuai controller
            if (password.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Too Short',
                    text: 'Password must be at least 8 characters long',
                    confirmButtonColor: '#1565c0'
                });
                return false;
            }
            
            // Validasi regex password sesuai controller
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/;
            if (!passwordRegex.test(password)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Requirements Not Met',
                    html: 'Password must contain:<br><br>' +
                          '✓ At least one lowercase letter (a-z)<br>' +
                          '✓ At least one uppercase letter (A-Z)<br>' +
                          '✓ At least one number (0-9)<br>' +
                          '✓ At least one special character (@$!%*?&)<br><br>' +
                          'Example: Password123@',
                    confirmButtonColor: '#1565c0'
                });
                return false;
            }
            
            // All validations passed, submit form
            submitForm();
            
            function submitForm() {
                // Show loading
                registerButton.disabled = true;
                loadingSpinner.classList.add('active');
                registerButton.innerHTML = '<span class="loading active"></span> Creating Student Account...';
                
                // Show processing message
                Swal.fire({
                    title: 'Creating Student Account',
                    html: 'Generating your personalized avatar and setting up account...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                setTimeout(() => {
                    document.getElementById('registerForm').submit();
                }, 1500);
            }
            
            return false;
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
            
            // Check initial password match
            checkPasswordMatch();
            
            // Hide password requirements initially
            document.getElementById('passwordRequirements').style.display = 'none';
            document.getElementById('passwordStrengthContainer').style.display = 'none';
        });

        // Email validation on input
        emailInput.addEventListener('input', function() {
            const email = this.value.trim();
            
            if (!email) {
                emailValidation.className = 'email-validation';
                emailValidation.innerHTML = '<i class="fas fa-info-circle"></i><span>Enter your email address</span>';
                return;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                emailValidation.className = 'email-validation invalid';
                emailValidation.innerHTML = '<i class="fas fa-times-circle"></i><span>Please enter a valid email address</span>';
            } else {
                emailValidation.className = 'email-validation valid';
                emailValidation.innerHTML = '<i class="fas fa-check-circle"></i><span>Valid email format</span>';
            }
        });
    </script>
</body>
</html>