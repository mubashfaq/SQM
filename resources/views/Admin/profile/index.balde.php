@include('Admin.Includes.header')

@php
    use Illuminate\Support\Facades\Auth;
    use App\General\BrandClass;
    $logged_in_user = Auth::user();
@endphp

<div class="page-content">
    <div class="container-fluid">

        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <div>
                        <h4 class="mb-1">{{ __('Brand List') }}</h4>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Brand List
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>



        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
 <div class="container-fluid py-3">
        <div class="row">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Profile</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-md-3 mb-4">
                <div class="card bg-info bg-opacity-25 text-center p-3">
                    <div class="position-relative mx-auto" style="width: 150px;">
                        <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-xdZ7QqLcu0kAmV9abtOLimrTmY7a4m.png" alt="Profile Logo" class="rounded-circle bg-white p-2" width="150">
                        <button class="btn btn-sm btn-light position-absolute bottom-0 end-0 rounded-circle">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                    <h4 class="mt-3 text-white">Dev Team</h4>
                    <p class="text-white mb-1">Admin</p>
                    <div class="d-inline-block bg-white rounded-pill px-3 py-1 mb-3">
                        <span class="text-success"><i class="bi bi-circle-fill me-1 small"></i>Active</span>
                    </div>
                    
                    <div class="d-grid gap-2 px-3">
                        <button class="btn btn-light text-start" type="button">
                            <i class="bi bi-person me-2"></i> Edit Profile
                        </button>
                        <button class="btn btn-light text-start" type="button" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key me-2"></i> Change Password
                        </button>
                    </div>

                    <div class="mt-4 text-start px-3">
                        <h6 class="text-uppercase text-white-50">ACCOUNT DETAILS</h6>
                        <div class="d-flex justify-content-between py-2 border-bottom border-light">
                            <span class="text-white-50">User ID</span>
                            <span class="text-white">#2</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom border-light">
                            <span class="text-white-50">Member Since</span>
                            <span class="text-white">Sep 10, 2023</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-white-50">Last Updated</span>
                            <span class="text-white">Feb 05, 2025</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-person-circle me-2"></i>
                            Profile Information
                        </h5>

                        <div class="mb-4">
                            <label class="form-label text-muted">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" value="Dev Team" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" value="support@g-techsolutions.com.au" readonly>
                                <span class="input-group-text bg-success text-white">
                                    <i class="bi bi-check-circle me-1"></i> Verified
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="tel" class="form-control" value="03468887736" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-shield-lock me-2"></i>
                            Security Settings
                        </h5>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <label class="form-label text-muted">Password</label>
                                <div>••••••••• <span class="text-muted small">Updated 3 months ago</span></div>
                            </div>
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change</button>
                        </div>

                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <div>
                                Security Tip: Regularly update your password and use a combination of letters, numbers, and special characters for better security.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="changePasswordModalLabel">
                        <i class="bi bi-key me-2"></i>Change Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="currentPassword" required>
                                <button class="btn btn-outline-primary" type="button" onclick="togglePassword('currentPassword')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="newPassword" required>
                                <button class="btn btn-outline-primary" type="button" onclick="togglePassword('newPassword')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            
                            <div class="mt-2">
                                <div class="d-flex align-items-center mb-1 text-muted small">
                                    <i class="bi bi-circle-fill me-2 small"></i>
                                    At least 8 characters
                                </div>
                                <div class="d-flex align-items-center mb-1 text-muted small">
                                    <i class="bi bi-circle-fill me-2 small"></i>
                                    Contains uppercase & lowercase letters
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-circle-fill me-2 small"></i>
                                    Contains numbers & special characters
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirmPassword" class="form-label">Confirm Password<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-check2"></i></span>
                                <input type="password" class="form-control" id="confirmPassword" required>
                                <button class="btn btn-outline-primary" type="button" onclick="togglePassword('confirmPassword')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-lock me-2"></i>Update Password
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x me-2"></i>Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>
       
    </div>
</div>

@include('Admin.Includes.footer')

