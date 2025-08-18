@php
    use App\General\GeneralSettingsClass;
@endphp
@include('Admin.Includes.links')

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Left side branding panel with background image -->
        <div class="col-md-6 min-vh-100 d-flex flex-column align-items-center justify-content-center text-white position-relative overflow-hidden login-column">

            <!-- Background image -->
            <div class="position-absolute top-0 start-0 w-100 h-100" 
                style="background-image: url('{{ asset('/assets/images/login-bg.jpg') }}'); 
                        background-size: cover; 
                        background-position: center;
                        z-index: 0;">
            </div>

            <!-- Dark overlay -->
            <div class="position-absolute top-0 start-0 w-100 h-100" 
                style="background-color: rgba(0, 0, 0, 0.6); z-index: 1;">
            </div>

            <!-- Content -->
            <div class="logo mb-4 position-relative" style="z-index: 2;">
                <img src="{{ url('/') . '/' . GeneralSettingsClass::getRequiredValue('_header_logo_dark') }}" 
                    alt="Sydney Quality Meats" style="max-width: 100%; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.2));">
            </div>

            <div class="heading mb-3 position-relative" style="z-index: 2;">
                <h3 class="fw-bold text-white ">We Care about Quality</h3>
            </div>

            <div class="description px-md-5 px-4 text-center position-relative" style="max-width: 550px; z-index: 2;">
                <p class="fs-5 opacity-90 text-white ">
                Sydney Quality Meat is your trusted source for premium, locally sourced cuts in the heart of Sydney.
                We take pride in our commitment to ethical farming practices and the highest standards of freshness.
                Experience exceptional taste and friendly service with every visit to Sydney Quality Meats
                </p>
                <p class="fs-5 mt-4 opacity-75 text-white ">Copyright © 2025 Sydney Quality Meats.</p>
            </div>
        </div>


        <!-- Right side login form -->
        <div class="col-md-6 d-flex align-items-center justify-content-center py-5 " style="background-color: #f8f9fa;">
            <div class="w-100 px-4" style="max-width: 450px;">
                
                <!-- Sign In Form -->
                <div id="signInForm" class="p-4 p-md-5 rounded-4 shadow">
                    <div class="head mb-5 text-center">
                        <h2 class="fw-bold mb-2">Welcome Back</h2>
                        <p class="text-muted">Sign in to your account to continue</p>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <ul class="mb-0 list-unstyled">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                    
                    <form action="{{ route('Admin.Login') }}" method="post" id="login_form_section">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium mb-2">Email address</label>
                            <div class="input-group input-group-lg">
                                <input type="email" 
                                       class="form-control bg-light border-0"
                                       id="email" 
                                       placeholder="name@example.com" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       style="height: 60px; border-radius: 12px;">
                            </div>
                          
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium mb-2">Password</label>
                            <div class="input-group input-group-lg position-relative">
                                <input type="password" 
                                       class="form-control bg-light border-0 @error('password') is-invalid @enderror"
                                       id="password" 
                                       placeholder="Enter your password" 
                                       name="password"
                                       style="height: 60px; border-radius: 12px; padding-right: 50px;">
                                <div class="position-absolute end-0 top-50 translate-middle-y d-flex align-items-center pe-3" style="z-index: 5;">
                                    <button type="button" class="btn border-0 bg-transparent p-1" id="togglePassword">
                                        <i class="bi bi-eye-slash text-muted fs-5"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                            <a href="javascript:void(0);" onclick="toggleForms()" class="text-decoration-none fw-semibold" 
                               style="color: {{ GeneralSettingsClass::getRequiredValue('_button_color') }} !important;">
                               Forgot Password?
                            </a>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-lg py-3" 
                                    style="background-color: {{ GeneralSettingsClass::getRequiredValue('_button_color') }} !important; 
                                           color: white !important;
                                           border-radius: 12px;
                                           box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                Sign In
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Forgot Password Form -->
                <div id="forgotPasswordForm" class="p-4 p-md-5 rounded-4 shadow" style="display: none;">
                    <div class="head mb-5 text-center">
                        <h2 class="fw-bold mb-2">Reset Password</h2>
                        <p class="text-muted">Enter your email to receive reset instructions</p>
                    </div>
                    
                    <!-- Response messages will be shown here -->
                    <div id="forgotPasswordResponse" class="mb-4"></div>
                    
                    <form action="{{ route('Admin.ForgotPassword') }}" method="post" id="forgot_password_section">
                        @csrf
                        <div class="mb-4">
                            <label for="resetEmail" class="form-label fw-medium mb-2">Email address</label>
                            <div class="input-group input-group-lg">
                                <input type="email" 
                                       class="form-control bg-light border-0"
                                       id="resetEmail" 
                                       placeholder="name@example.com" 
                                       name="email" 
                                       required
                                       style="height: 60px; border-radius: 12px;">
                            </div>
                            <div class="text-danger mt-1" id="emailFeedback"></div>
                        </div>
                        
                        <div class="d-grid mb-4 mt-4">
                            <button type="submit" class="btn btn-lg py-3" 
                                    style="background-color: {{ GeneralSettingsClass::getRequiredValue('_button_color') }} !important; 
                                           color: white !important;
                                           border-radius: 12px;
                                           box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            Reset Password</button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="javascript:void(0);" onclick="toggleForms()" 
                               class="text-decoration-none d-inline-flex align-items-center gap-2 fw-semibold"
                               style="color: {{ GeneralSettingsClass::getRequiredValue('_button_color') }} !important;">
                                <i class="bi bi-arrow-left"></i>
                                Back to Sign In
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleForms() {
        let signInForm = document.getElementById('signInForm');
        let forgotPasswordForm = document.getElementById('forgotPasswordForm');
        
        if (signInForm.style.display === "none") {
            signInForm.style.display = "block";
            forgotPasswordForm.style.display = "none";
        } else {
            signInForm.style.display = "none";
            forgotPasswordForm.style.display = "block";
        }
        
        // Clear any previous messages when toggling forms
        document.getElementById('forgotPasswordResponse').innerHTML = '';
        document.getElementById('emailFeedback').innerHTML = '';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            // Toggle password visibility
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle eye icon
            const icon = togglePassword.querySelector('i');
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
        
        // Handle forgot password form submission with AJAX
        document.getElementById('forgot_password_section').addEventListener('submit', function(e) {
            e.preventDefault();

            // Clear previous feedback
            document.getElementById('emailFeedback').innerHTML = '';

            // Show loading message
            document.getElementById('forgotPasswordResponse').innerHTML = 
                '<div class="alert alert-info">Processing your request...</div>';

            // Auto-close after 5 seconds
            setTimeout(() => {
                document.getElementById('forgotPasswordResponse').innerHTML = '';
            }, 5000);

            // Get the email
            const email = document.getElementById('resetEmail').value;

            // Validate email
            if (!email || !email.includes('@')) {
                document.getElementById('emailFeedback').innerHTML = 'Please enter a valid email address';
                document.getElementById('forgotPasswordResponse').innerHTML = '';
                return;
            }

            // Create form data object
            const formData = new FormData();
            formData.append('email', email);
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            // Send AJAX request
            fetch('{{ route("Admin.ForgotPassword") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                let alertClass = 'alert-info';
                if (data.status === 200) {
                    alertClass = 'alert-success';
                    document.getElementById('resetEmail').value = '';
                } else if (data.status === 404) {
                    alertClass = 'alert-warning';
                } else {
                    alertClass = 'alert-danger';
                }

                // Show response message
                document.getElementById('forgotPasswordResponse').innerHTML = 
                    `<div class="alert ${alertClass}">${data.message}</div>`;

                // Auto-close the alert after 5 seconds
                setTimeout(() => {
                    document.getElementById('forgotPasswordResponse').innerHTML = '';
                }, 5000);
                 window.location.href = "{{ route('password.otp') }}";

            })
            .catch(error => {
                document.getElementById('forgotPasswordResponse').innerHTML = 
                    '<div class="alert alert-danger">An error occurred. Please try again later.</div>';

                // Auto-close the error alert
                setTimeout(() => {
                    document.getElementById('forgotPasswordResponse').innerHTML = '';
                }, 5000);

                console.error('Error:', error);
            });
        });
    });
</script>