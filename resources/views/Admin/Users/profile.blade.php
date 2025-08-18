@include('Admin.Includes.header')
<div class="page-content bg-light">
    <div class="container-fluid py-5">
        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>{{ session('success') }}</strong>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Profile Content -->
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-lg-3 mb-4">
                <!-- Profile Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="text-center p-4" style="background-color: #a2cae1;">
                            <div class="position-relative d-inline-block mb-3">
                                @if ($user->profile_image && file_exists(public_path($user->profile_image)))
                                    <img src="{{ asset($user->profile_image) }}" class="rounded-circle img-thumbnail shadow border-3 border-white" 
                                        style="width: 120px; height: 120px; object-fit: cover;" alt="Profile Image">
                                @else
                                    <div class="rounded-circle   d-flex align-items-center justify-content-center shadow border-3 border-white"
                                        style="width: 120px; height: 120px; color: #6c757d; font-size: 48px; margin: 0 auto;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <button class="btn btn-sm btn-light position-absolute bottom-0 end-0 rounded-circle p-2 shadow"
                                    data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                            <h5 class="fw-bold text-white mb-1">{{ $user->name }}</h5>
                            <p class="fw-bold text-white mb-2">{{ ucfirst($user->role->name ?? 'Admin') }}</p>
                            <span class="badge bg-light text-primary fw-normal">
                                <i class="fas fa-circle text-success me-1"></i> Active
                            </span>
                        </div>
                        
                        <div class="p-4">
                            <div class="d-grid gap-2 mb-4">
                                <button type="button" class="btn" style="background-color: #a2cae1;" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    <i class="fas fa-user-edit me-2"></i>Edit Profile
                                </button>
                                <button type="button" class="btn" style="background-color: #a2cae1;" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="fas fa-key me-2"></i>Change Password
                                </button>
                            </div>
                            
                            <h6 class="fw-bold text-uppercase text-muted small mb-3">Account Details</h6>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item border-0 px-0 d-flex justify-content-between">
                                    <span class="text-muted">User ID</span>
                                    <span class="fw-medium">#{{ $user->id }}</span>
                                </div>
                                <div class="list-group-item border-0 px-0 d-flex justify-content-between">
                                    <span class="text-muted">Member Since</span>
                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</span>
                                </div>
                                <div class="list-group-item border-0 px-0 d-flex justify-content-between">
                                    <span class="text-muted">Last Updated</span>
                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($user->updated_at)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Content -->
            <div class="col-lg-9">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}/admin" class="text-decoration-none">
                              Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active">User Profile</li>
                    </ol>
                </nav>
                
                <!-- Profile Information -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header   p-4 border-bottom-0">
                        <h4 class="fw-bold mb-0">
                            <i class="fas fa-user-circle me-2 "></i>Profile Information
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="text-muted small mb-1">Full Name</label>
                                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-user me-2 "></i>{{ $user->name }}</h6>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="text-muted small mb-1">Email Address</label>
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 fw-bold me-2"><i class="fa-solid fa-envelope me-2 "></i>{{ $user->email }}</h6>
                                        @if ($user->email_verified_at)
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="fas fa-check-circle me-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="fas fa-exclamation-circle me-1"></i>Not Verified
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="text-muted small mb-1">Phone Number</label>
                                    <h6 class="mb-0 fw-bold">
                                        @if($user->phone)
                                            <i class="fas fa-phone me-2 "></i>{{ $user->phone }}
                                        @else
                                            <span class="text-muted fst-italic">Not provided</span>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light rounded-4 border-0 h-100">
                                    <div class="card-body">
                                        <h5 class="d-flex align-items-center fw-bold mb-4">
                                            <i class="fas fa-shield-alt me-2 text-dark"></i>Security Settings
                                        </h5>
                                        
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <label class="text-muted small mb-1">Password</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">••••••••</div>
                                                        <span class="badge bg-light text-muted">
                                                            Updated {{ \Carbon\Carbon::parse($user->updated_at)->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <button class="btn btn-sm" style="background-color: #a2cae1;" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                                    Change
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-info mb-0 mt-4 d-flex">
                                            <i class="fas fa-info-circle me-2 mt-1"></i>
                                            <div>
                                                <strong>Security Tip:</strong> Regularly update your password and use a combination of letters, numbers, and special characters for better security.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header  text-white">
                    <h5 class="modal-title" id="editProfileModalLabel">
                        <i class="fas fa-user-edit me-2"></i>Edit Profile
                    </h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4 text-center">
                            <div class="position-relative d-inline-block">
                                @if ($user->profile_image && file_exists(public_path($user->profile_image)))
                                    <img src="{{ asset($user->profile_image) }}" class="rounded-circle img-thumbnail" 
                                        style="width: 120px; height: 120px; object-fit: cover;" alt="Profile Image">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                                        style="width: 120px; height: 120px; color: #6c757d; font-size: 48px; margin: 0 auto;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-3">
                                <label for="profile_image" class="btn btn-outline-primary">
                                    <i class="fas fa-upload me-2"></i>Upload Photo
                                </label>
                                <input type="file" class="form-control d-none" id="profile_image" name="profile_image">
                                <div class="form-text">Recommended: Square image, at least 300x300px</div>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" placeholder="Full Name">
                                    <label for="name">Full Name</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" placeholder="Email Address">
                                    <label for="email">Email Address</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}" placeholder="Phone Number">
                                    <label for="phone">Phone Number</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success px-5 py-2">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <button type="button" class="btn btn-secondary px-4 py-2 ms-2" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header  text-white">
                    <h5 class="modal-title" id="changePasswordModalLabel">
                        <i class="fas fa-key me-2"></i>Change Password
                    </h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('admin.profile.update') }}" method="POST" id="passwordForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="update_type" value="password">
                        
                        <div class="mb-4">
                            <label for="current_password" class="form-label">Current Password<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="new_password" class="form-label">New Password<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <div class="password-strength mt-3">
                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <ul class="list-unstyled mt-2 mb-0 small">
                                    <li class="mb-1">
                                        <i class="fas fa-check-circle text-muted me-2"></i>At least 8 characters
                                    </li>
                                    <li class="mb-1">
                                        <i class="fas fa-check-circle text-muted me-2"></i>Contains uppercase & lowercase letters
                                    </li>
                                    <li>
                                        <i class="fas fa-check-circle text-muted me-2"></i>Contains numbers & special characters
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label">Confirm Password<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-check-double text-muted"></i>
                                </span>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="password-match-feedback" class="form-text"></div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success py-2">
                                <i class="fas fa-lock me-2"></i>Update Password
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Preview profile image before upload
    document.getElementById('profile_image').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.querySelector('.modal-body .rounded-circle');
                if (img.tagName === 'IMG') {
                    img.src = e.target.result;
                } else {
                    const parent = img.parentNode;
                    const newImg = document.createElement('img');
                    newImg.src = e.target.result;
                    newImg.className = 'rounded-circle img-thumbnail';
                    newImg.style = 'width: 120px; height: 120px; object-fit: cover;';
                    parent.replaceChild(newImg, img);
                }
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Password strength meter
    document.getElementById('new_password').addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        const progressBar = document.querySelector('.password-strength .progress-bar');
        const checks = document.querySelectorAll('.password-strength li i');
        
        // Length check
        if (password.length >= 8) {
            strength += 1;
            checks[0].classList.remove('text-muted');
            checks[0].classList.add('text-success');
        } else {
            checks[0].classList.remove('text-success');
            checks[0].classList.add('text-muted');
        }
        
        // Case check
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
            strength += 1;
            checks[1].classList.remove('text-muted');
            checks[1].classList.add('text-success');
        } else {
            checks[1].classList.remove('text-success');
            checks[1].classList.add('text-muted');
        }
        
        // Number and special character check
        if (/[0-9]/.test(password) && /[^a-zA-Z0-9]/.test(password)) {
            strength += 1;
            checks[2].classList.remove('text-muted');
            checks[2].classList.add('text-success');
        } else {
            checks[2].classList.remove('text-success');
            checks[2].classList.add('text-muted');
        }
        
        // Update progress bar
        const strengthPercentage = (strength / 3) * 100;
        progressBar.style.width = `${strengthPercentage}%`;
        
        if (strength === 0) {
            progressBar.className = 'progress-bar bg-danger';
        } else if (strength === 1) {
            progressBar.className = 'progress-bar bg-warning';
        } else if (strength === 2) {
            progressBar.className = 'progress-bar bg-info';
        } else {
            progressBar.className = 'progress-bar bg-success';
        }
    });
    
    // Password match check
    document.getElementById('new_password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('new_password').value;
        const confirmPassword = this.value;
        const feedback = document.getElementById('password-match-feedback');
        
        if (confirmPassword === '') {
            feedback.innerHTML = '';
            this.classList.remove('is-valid', 'is-invalid');
        } else if (password === confirmPassword) {
            feedback.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Passwords match</span>';
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            feedback.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Passwords do not match</span>';
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    });
    
    // Fade out alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>