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

        <!-- Right side reset password form -->
        <div class="col-md-6 d-flex align-items-center justify-content-center py-5" style="background-color: #f8f9fa;">
            <div class="w-100 px-4" style="max-width: 450px;">
                
                <!-- Reset Password Form -->
                <div id="resetPasswordForm" class="p-4 p-md-5 rounded-4 shadow">
                    <div class="head mb-5 text-center">
                        <h2 class="fw-bold mb-2">Reset Password</h2>
                        <p class="text-muted">Please enter your new password</p>
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
                    
                    <form id="reset_password_form">
                        @csrf
                        <input type="hidden" name="token" value="{{ request('token') }}">
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium mb-2">New Password</label>
                            <div class="input-group input-group-lg position-relative">
                                <input type="password" 
                                       class="form-control bg-light border-0 password-field"
                                       id="password" 
                                       placeholder="Enter your new password" 
                                       name="password"
                                       style="height: 60px; border-radius: 12px; padding-right: 50px;">
                                <div class="position-absolute end-0 top-50 translate-middle-y d-flex align-items-center pe-3" style="z-index: 5;">
                                    <button type="button" class="btn border-0 bg-transparent p-1 toggle-password" data-target="password">
                                        <i class="bi bi-eye-slash text-muted fs-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-medium mb-2">Confirm New Password</label>
                            <div class="input-group input-group-lg position-relative">
                                <input type="password" 
                                       class="form-control bg-light border-0 password-field"
                                       id="password_confirmation" 
                                       placeholder="Confirm your new password" 
                                       name="password_confirmation"
                                       style="height: 60px; border-radius: 12px; padding-right: 50px;">
                                <div class="position-absolute end-0 top-50 translate-middle-y d-flex align-items-center pe-3" style="z-index: 5;">
                                    <button type="button" class="btn border-0 bg-transparent p-1 toggle-password" data-target="password_confirmation">
                                        <i class="bi bi-eye-slash text-muted fs-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-lg py-3" 
                                    style="background-color: #A42125 !important; 
                                           color: white !important;
                                           border-radius: 12px;
                                           box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                Reset Password
                            </button>
                        </div>
                        
                        <div class="response_section mt-3"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$('#reset_password_form').on('submit', function(e) {
    e.preventDefault();
    $('.response_section').html(`<div class="alert alert-info mt-1">Processing...</div>`);
    var formData = $(this).serialize();
    
    // Configure toastr options
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };
    
    $.ajax({
        url: "{{ route('Admin.PasswordUpdate.Submit') }}",
        type: "POST",
        data: formData,
        success: function(response) {
            switch (response.status) {
                case 200:
                    $('.response_section').html(
                        `<div class="alert alert-success mt-1">${response.message} </div>`
                    );
                    toastr.success(response.message);
                    $('input[name=password_confirmation], input[name=password]').val('');
                    setTimeout(function() {
                        window.location.href = "{{ route('Admin.Login') }}";
                    }, 2000);
                    break;
                case 404:
                    $('.response_section').html(
                        `<div class="alert alert-warning mt-1">${response.message}</div>`);
                    toastr.warning(response.message);
                    break;
                case 500:
                    $('.response_section').html(
                        `<div class="alert alert-danger mt-1">${response.message}</div>`);
                    toastr.error(response.message);
                    break;
            }
        },
        error: function(xhr, status, error) {
            console.log('XHR Status:', xhr.status);
            console.log('Response:', xhr.responseText);
            
            // Handle validation errors (422 status)
            if (xhr.status == 422) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    console.log('Parsed Response:', response);
                    
                    var errorHtml = '<div class="alert alert-danger mt-1"><ul class="mb-0 list-unstyled">';
                    var hasErrors = false;
                    
                    // Check if Laravel validation errors exist
                    if (response.errors && typeof response.errors === 'object') {
                        $.each(response.errors, function(field, messages) {
                            if (Array.isArray(messages)) {
                                $.each(messages, function(index, message) {
                                    errorHtml += '<li>' + message + '</li>';
                                    hasErrors = true;
                                });
                            } else if (typeof messages === 'string') {
                                errorHtml += '<li>' + messages + '</li>';
                                hasErrors = true;
                            }
                        });
                    }
                    
                    // If no errors were found in the errors object, check for message
                    if (!hasErrors && response.message) {
                        errorHtml += '<li>' + response.message + '</li>';
                        hasErrors = true;
                    }
                    
                    // Fallback if still no errors
                    if (!hasErrors) {
                        errorHtml += '<li>Please check your input and try again.</li>';
                    }
                    
                    errorHtml += '</ul></div>';
                    $('.response_section').html(errorHtml);
                    
                    // Show toastr notification for validation errors
                    var toastrMessage = response.message || 'Please fix the validation errors';
                    
                } catch (parseError) {
                    console.log('JSON Parse Error:', parseError);
                    console.log('Raw Response:', xhr.responseText);
                    $('.response_section').html(
                        `<div class="alert alert-danger mt-1">Invalid response format. Please try again.</div>`
                    );
                    toastr.error('Invalid response format. Please try again.');
                }
            } else {
                // Handle other server errors
                $('.response_section').html(
                    `<div class="alert alert-danger mt-1">Server Error, Try Again Later!</div>`
                );
                toastr.error('Server Error, Try Again Later!');
            }
        }
    });
});

    // Password visibility toggle functionality with eye icons
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtns = document.querySelectorAll('.toggle-password');
        
        toggleBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordField = document.getElementById(targetId);
                
                // Toggle password visibility
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                
                // Toggle eye icon
                const icon = this.querySelector('i');
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        });
    });
</script>
</body>
</html>