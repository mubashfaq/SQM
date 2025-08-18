@php
    use App\General\GeneralSettingsClass;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Sydney Quality Meats</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .login-column {
            position: relative;
        }
    </style>
</head>
<body>

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
                style="background-color: rgba(255, 255, 255, 0.3); z-index: 1;">
            </div>

            <!-- Content -->
            <div class="logo mb-4 position-relative" style="z-index: 2;">
                <img src="{{ url('/') . '/' . GeneralSettingsClass::getRequiredValue('_header_logo') }}" 
                    alt="Sydney Quality Meats" style="max-width: 100%; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.2));">
            </div>

            <div class="heading mb-3 position-relative" style="z-index: 2;">
                <h3 class="fw-bold text-black">We Care about Quality</h3>
            </div>

            <div class="description px-md-5 px-4 text-center position-relative" style="max-width: 500px; z-index: 2;">
                <p class="fs-6 opacity-90 text-black">
                Sydney Quality Meat is your trusted source for premium, locally sourced cuts in the heart of Sydney.
                We take pride in our commitment to ethical farming practices and the highest standards of freshness.
                Experience exceptional taste and friendly service with every visit to Sydney Quality Meats
                </p>
                <p class="small mt-4 opacity-75 text-black">Copyright © 2025 Sydney Quality Meats.</p>
            </div>
        </div>

        <!-- Right side OTP verification form -->
        <div class="col-md-6 d-flex align-items-center justify-content-center py-5" style="background-color: #f8f9fa;">
            <div class="w-100 px-4" style="max-width: 450px;">
                
                <!-- OTP Verification Form -->
                <div id="otpVerificationForm" class="p-4 p-md-5 rounded-4 shadow">
                    <div class="head mb-5 text-center">
                        <h2 class="fw-bold mb-2">Reset Password</h2>
                        <p class="text-muted">Enter the OTP sent to your email to continue</p>
                    </div>
                    
                    @if(session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <ul class="mb-0 list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('password.match-otp') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="otp" class="form-label fw-medium mb-2">OTP Code</label>
                            <div class="input-group input-group-lg">
                                <input type="text" 
                                       class="form-control bg-light border-0"
                                       id="otp" 
                                       name="otp" 
                                       placeholder="Enter OTP code" 
                                       required
                                       style="height: 60px; border-radius: 12px;">
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-lg py-3" 
                                    style="background-color: #A42125 !important; 
                                           color: white !important;
                                           border-radius: 12px;
                                           box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                Verify OTP
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <form method="POST" action="{{ route('password.resend-otp') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('admin_reset_email') }}">
                            <p class="mb-3 text-muted">Didn't receive the code?</p>
                            <button type="button" id="resend-otp-btn" 
                                   class="btn py-2 px-4 d-inline-flex align-items-center gap-2 fw-semibold"
                                   style="background-color: #f8f9fa;
                                          color: #A42125;
                                          border: 1px solid #dee2e6;
                                          border-radius: 12px;">
                                <i class="bi bi-arrow-clockwise"></i>
                                Resend OTP
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $('#resend-otp-btn').on('click', function(e) {
        e.preventDefault();  // Prevent the default form submission

        var email = "{{ session('admin_reset_email') }}"; // Get the email value from session
        var csrfToken = "{{ csrf_token() }}"; // Get the CSRF token

        // Configure toastr options
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000"
        };

        // Send AJAX request using jQuery
        $.ajax({
            url: "{{ route('password.resend-otp') }}",
            method: "POST",
            data: JSON.stringify({
                email: email
            }),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(data) {
                // Show success message using toastr
                toastr.success('An OTP has been sent to your email.');
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);  // Log any errors in the console
                toastr.error('An error occurred. Please try again later.');  // Show error toaster
            }
        });
    });
</script>

</body>
</html>