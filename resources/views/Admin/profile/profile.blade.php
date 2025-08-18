@include('Admin.Includes.header')

@php
    use Illuminate\Support\Facades\Auth;
    use App\General\UserClass;
    $logged_in_user = Auth::user();
@endphp

<div class="page-content">
    <div class="container-fluid">

        <!-- <div class="card shadow-sm mb-4">
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
        </div> -->



        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Place this right before your form -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
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
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}"
                                class="text-decoration-none text-danger">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User Profile</li>
                    </ol>

                </nav>
            </div>
            <div class="row">
                <!-- Left Sidebar -->
                <div class="col-md-3 mb-4">
                    <div class="card bg-info bg-opacity-10 text-center p-3">
                        <div class="position-relative mx-auto mb-3" style="width: 120px; height: 120px;">
                            @if ($user->profile_image)
                                <img id="profileImage" src="{{ url( $user->profile_image) }}"
                                    alt="Profile Image" class="img-fluid rounded-circle w-100 h-100 object-fit-cover">
                            @else
                                <div class="d-flex justify-content-center align-items-center bg-secondary text-white rounded-circle"
                                    style="width: 120px; height: 120px; font-size: 48px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>


                        <h4 class="mt-2" id="profileName">{{ $user->name }}</h4>
                        <p class="mb-2"> {{ UserClass::check_role($user->role_id, $roles) }}</p>
                        <div class="d-inline-block bg-white rounded-pill px-3 py-1 mb-3">
                            <span class="text-success"><i class="bi bi-circle-fill me-1 small"></i>Active</span>
                        </div>

                        <div class="d-grid gap-2 px-3 mt-2">
                            <button class="btn btn-light text-start" type="button" data-bs-toggle="modal"
                                data-bs-target="#editProfileModal">
                                <i class="bi bi-person-fill me-2 text-danger"></i> Edit Profile
                            </button>
                            <button class="btn btn-light text-start" type="button" data-bs-toggle="modal"
                                data-bs-target="#changePasswordModal">
                                <i class="bi bi-key-fill me-2 text-danger"></i> Change Password
                            </button>
                        </div>

                        <div class="mt-4 text-start px-3">
                            <h6 class="text-uppercase text-danger">ACCOUNT DETAILS</h6>
                            <div
                                class="d-flex justify-content-between py-2 border-bottom border-danger border-opacity-25">
                                <span class="text-danger">User ID</span>
                                <span class="light:text-dark dark:text-white">#{{ $user->id }}</span>
                            </div>
                            <div
                                class="d-flex justify-content-between py-2 border-bottom border-danger border-opacity-25">
                                <span class="text-danger">Member Since</span>
                                <span class="light:text-dark dark:text-white">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-danger">Last Updated</span>
                                <span class="light:text-dark dark:text-white">{{ $user->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-md-9">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="bi bi-person-circle me-2 light:text-dark dark:text-light"></i>
                                Profile Information
                            </h5>

                            <div class="mb-4">
                                <p class="text-muted mb-1">Full Name</p>
                                <p class="mb-0 fs-6" id="displayName">
                                    <i class="bi bi-person me-2"></i>
                                    {{ $user->name }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <p class="text-muted mb-1">Email Address</p>
                                <p class="mb-0 fs-6" id="displayEmail">
                                    <i class="bi bi-envelope me-2"></i>
                                    {{ $user->email }}
                                </p>

                                </p>
                            </div>

                            <div class="mb-4">
                                <p class="text-muted mb-1">Phone Number</p>
                                <p class="mb-0 fs-6" id="displayPhone">
                                    <i class="bi bi-telephone me-2"></i>
                                    {{ $user->phone }}
                                </p>

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="bi bi-shield-lock me-2 light:text-dark dark:text-light"></i>
                                Security Settings
                            </h5>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <p class="text-muted mb-1">Password</p>
                                    <p class="mb-0">••••••••• <span class="text-muted small">Updated
                                            {{ $user->updated_at->diffForHumans() }}</span></p>
                                </div>
                                <button class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#changePasswordModal">Change</button>
                            </div>

                            <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 d-flex align-items-center"
                                role="alert">
                                <i class="bi bi-info-circle me-2 text-danger"></i>
                                <div>
                                    Security Tip: Regularly update your password and use a combination of letters,
                                    numbers, and special characters for better security.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="editProfileModalLabel">
                            <i class="bi bi-person me-2"></i>Edit Profile
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/admin/profile/update') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <img id="profilePreview" src="{{ asset($user->profile_image) }}"
                                        alt="Profile Preview" class="rounded-circle"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                    <label for="profileImageInput"
                                        class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 cursor-pointer"
                                        style="cursor: pointer;">
                                        <i class="bi bi-camera text-danger"></i>
                                        <input type="file" name="profile_image" id="profileImageInput"
                                            class="d-none" accept="image/*">
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name<span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control" id="fullName"
                                        value="{{ $user->name }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="emailAddress" class="form-label">Email Address<span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" id="emailAddress"
                                        value="{{ $user->email }}" required>

                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                    <input type="tel" class="form-control" name="phone" id="phoneNumber"
                                        value="{{ $user->phone }}">
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-save me-2"></i>Save Changes
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

        <!-- Change Password Modal -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="changePasswordModalLabel">
                            <i class="bi bi-key me-2"></i>Change Password
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.profile.change-password') }}" method="post">
                            <div class="mb-3">
                                @csrf

                                <label for="currentPassword" class="form-label">Current Password<span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="current_password" class="form-control"
                                        id="currentPassword" required>
                                    <button class="btn btn-outline-danger" type="button"
                                        onclick="togglePassword('currentPassword')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password<span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                    <input type="password" name="new_password" class="form-control" id="newPassword"
                                        required>
                                    <button class="btn btn-outline-danger" type="button"
                                        onclick="togglePassword('newPassword')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>


                            </div>

                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label">Confirm Password<span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-check2"></i></span>
                                    <input type="password" name="new_password_confirmation" class="form-control"
                                        id="confirmPassword" required>
                                    <button class="btn btn-outline-danger" type="button"
                                        onclick="togglePassword('confirmPassword')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">
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

        <script>
            // Toggle password visibility
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

            // Profile image preview
            document.getElementById('profileImageInput').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('profilePreview').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Update profile information
        </script>
        <script>
            // Hide the success alert after 3 seconds
            setTimeout(function() {
                var alert = document.getElementById('success-alert');
                if (alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 3000);
        </script>
    </div>
</div>
