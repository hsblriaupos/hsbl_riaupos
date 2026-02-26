<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - SBL Student Portal</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

        .forgot-container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }

        /* Header */
        .forgot-header {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .forgot-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .forgot-header p {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 400px;
            margin: 0 auto;
            line-height: 1.5;
        }

        /* Logo */
        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: contain;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
        }

        /* Content */
        .forgot-content {
            padding: 40px 30px;
        }

        /* Warning Message */
        .warning-box {
            background: #fff3e0;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            border: 2px solid #ffb300;
            text-align: center;
        }

        .warning-box h3 {
            color: #ef6c00;
            font-size: 1.3rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .warning-box p {
            color: #8d6e63;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        /* Admin Contact */
        .admin-contact {
            background: #f0f7ff;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            border: 2px solid #bbdefb;
            text-align: center;
        }

        .admin-contact h3 {
            color: #1565c0;
            font-size: 1.3rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .admin-contact h3 i {
            color: #25D366;
        }

        .contact-info {
            margin: 20px 0;
        }

        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #25D366;
            color: white;
            padding: 14px 25px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            margin: 10px 0;
        }

        .whatsapp-btn:hover {
            background: #128C7E;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
        }

        .whatsapp-btn:active {
            transform: translateY(0);
        }

        .contact-note {
            color: #546e7a;
            font-size: 0.9rem;
            margin-top: 15px;
            line-height: 1.5;
        }

        .contact-note i {
            color: #1565c0;
            margin-right: 5px;
        }

        /* Contact Hours */
        .contact-hours {
            background: #fff8e1;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            border-left: 4px solid #ffb300;
        }

        .contact-hours h4 {
            color: #ef6c00;
            font-size: 0.95rem;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-hours p {
            color: #8d6e63;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        /* Information Box */
        .info-box {
            background: #e8f5e9;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #4caf50;
        }

        .info-box h4 {
            color: #2e7d32;
            font-size: 1.1rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-box ul {
            list-style-type: none;
            padding-left: 5px;
        }

        .info-box li {
            color: #546e7a;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .info-box li i {
            color: #4caf50;
            margin-top: 3px;
            flex-shrink: 0;
        }

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #1565c0;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .back-link a:hover {
            background: #f0f7ff;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .forgot-container {
                margin: 10px;
            }

            .forgot-header {
                padding: 30px 20px;
            }

            .forgot-content {
                padding: 30px 20px;
            }

            .forgot-header h1 {
                font-size: 1.8rem;
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <!-- Header -->
        <div class="forgot-header">
            <div class="logo">
                <img src="{{ asset('uploads/logo/hsbl.png') }}" alt="SBL Logo">
            </div>
            <h1><i class="fas fa-key"></i> Password Recovery</h1>
            <p>Reset your SBL Student Portal password with admin assistance</p>
        </div>

        <!-- Content -->
        <div class="forgot-content">
            <!-- Warning Message -->
            <div class="warning-box">
                <h3><i class="fas fa-exclamation-triangle"></i> Important Notice</h3>
                <p><strong>Email reset feature is currently unavailable.</strong> Please contact our admin team directly via WhatsApp for password recovery assistance.</p>
            </div>

            <!-- Information -->
            <div class="info-box">
                <h4><i class="fas fa-info-circle"></i> What You Need to Prepare:</h4>
                <ul>
                    <li><i class="fas fa-check"></i> Your registered full name</li>
                    <li><i class="fas fa-check"></i> Your registered email address</li>
                    <li><i class="fas fa-check"></i> Your student ID (if available)</li>
                    <li><i class="fas fa-check"></i> Team information (if applicable)</li>
                </ul>
            </div>

            <!-- Admin Contact -->
            <div class="admin-contact">
                <h3><i class="fab fa-whatsapp"></i> Contact Admin via WhatsApp</h3>
                <p>Click the button below to start a WhatsApp conversation with our admin:</p>
                
                <div class="contact-info">
                    <a href="javascript:void(0);" 
                       class="whatsapp-btn" 
                       id="whatsappBtn">
                        <i class="fab fa-whatsapp"></i>
                        Start WhatsApp Conversation
                    </a>
                    
                    <div class="contact-note">
                        <i class="fas fa-phone"></i>
                        <strong>Phone Number:</strong> +62 813-6559-9240
                    </div>
                </div>

                <div class="contact-hours">
                    <h4><i class="fas fa-clock"></i> Admin Available Hours</h4>
                    <p>Monday - Friday: 9:00 AM - 5:00 PM WIB<br>
                       Saturday: 9:00 AM - 1:00 PM WIB<br>
                       Sunday: Closed</p>
                </div>

                <div class="contact-note">
                    <i class="fas fa-lightbulb"></i>
                    <strong>Tip:</strong> Save this number for future reference: <strong>+62 813-6559-9240</strong>
                </div>
            </div>

            <!-- Process Information -->
            <div class="info-box">
                <h4><i class="fas fa-cogs"></i> Recovery Process:</h4>
                <ul>
                    <li><i class="fas fa-phone"></i> Contact admin via WhatsApp</li>
                    <li><i class="fas fa-user-check"></i> Verify your identity</li>
                    <li><i class="fas fa-key"></i> Admin will reset your password</li>
                    <li><i class="fas fa-envelope"></i> Receive new login credentials</li>
                </ul>
            </div>

            <!-- Back to Login -->
            <div class="back-link">
                <a href="{{ route('student.login') }}">
                    <i class="fas fa-arrow-left"></i>
                    Back to Login Page
                </a>
            </div>
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

        // WhatsApp Button Handler
        document.getElementById('whatsappBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Prepare Your Information',
                html: `
                    <div style="text-align: left;">
                        <p>Please prepare the following information before contacting admin:</p>
                        <ul style="padding-left: 20px; margin: 15px 0;">
                            <li>Full name</li>
                            <li>Registered email</li>
                            <li>Student ID (if any)</li>
                            <li>Team name (if applicable)</li>
                        </ul>
                        <p>This will help speed up the verification process.</p>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Continue to WhatsApp',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#25D366',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Default WhatsApp message
                    const defaultMessage = `Hello SBL Admin, I need help with password recovery for my SBL Student Portal account.`;
                    
                    // Ask for additional information
                    Swal.fire({
                        title: 'Add Your Information',
                        html: `
                            <input type="text" id="swalName" class="swal2-input" placeholder="Your Full Name" required>
                            <input type="email" id="swalEmail" class="swal2-input" placeholder="Your Registered Email" required>
                            <textarea id="swalDetails" class="swal2-textarea" placeholder="Additional details (student ID, team, etc.)" rows="3"></textarea>
                        `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Send WhatsApp Message',
                        cancelButtonText: 'Back',
                        confirmButtonColor: '#25D366',
                        cancelButtonColor: '#6b7280',
                        preConfirm: () => {
                            const name = document.getElementById('swalName').value;
                            const email = document.getElementById('swalEmail').value;
                            const details = document.getElementById('swalDetails').value;
                            
                            if (!name || !email) {
                                Swal.showValidationMessage('Please fill in at least your name and email');
                                return false;
                            }
                            
                            return { name, email, details };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const { name, email, details } = result.value;
                            
                            // Construct WhatsApp message
                            let message = `Hello SBL Admin, I need help with password recovery for my SBL Student Portal account.\n\n`;
                            message += `Name: ${name}\n`;
                            message += `Email: ${email}\n`;
                            
                            if (details) {
                                message += `Additional Info: ${details}\n`;
                            }
                            
                            message += `\nPlease help me reset my password.`;
                            
                            // Encode message for URL
                            const encodedMessage = encodeURIComponent(message);
                            
                            // Open WhatsApp
                            window.open(`https://wa.me/6281365599240?text=${encodedMessage}`, '_blank');
                            
                            // Show success message
                            Toast.fire({
                                icon: 'success',
                                title: 'Opening WhatsApp...'
                            });
                            
                            // Log contact attempt
                            console.log('WhatsApp contact initiated for:', name, email);
                        }
                    });
                }
            });
        });

        // Auto focus on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Show welcome message
            setTimeout(() => {
                Toast.fire({
                    icon: 'info',
                    title: 'Contact admin via WhatsApp for password help'
                });
            }, 1000);
        });
    </script>
</body>
</html>