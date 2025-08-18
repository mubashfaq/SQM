@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
@endphp
<div class="page-content">

    <div class="card shadow-sm mb-4">

        <div class="  p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div class="clearfix">
                <div class="pd-t-5 pd-b-5">
                    <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Create User') }}</h1>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                        <li class="breadcrumb-item">
                            <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                <i class="fa-solid fa-home me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/users') }}" class="link-primary text-decoration-none">
                                Users
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Create
                        </li>
                    </ol>
                </nav>
            </div>
            
             @if (Session::has('permissions') && in_array('User List', Session::get('permissions')))
            <div class="d-flex align-items-center">
                <button type="button" onclick="window.location = '{{ url('/') }}/admin/users'"
                    class="btn btn-primary">
                    <i class="fa-solid fa-list-ul me-1"></i>User List
                </button>
            </div>
        @endif
        </div>

    </div>

        <div class="col-md-12 col-lg-12">
            <div class="fullfilled-request my-2"></div>
            <div class="card mg-b-30">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-header-title tx-13 mb-0">Create New User</h6>
                        </div>
                        <div class="text-right">
                            <div class="d-flex">
                                <a href="" class=""><i class="ti-reload"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="create_user_account">
                        @csrf
                        <input type="hidden" name="created_by" value="{{ $logged_in_user->id }}">
                        <div class="form-layout form-layout-1">
                            <h4>Account Info</h4>
                            <div class="row mg-b-25">
                                <div class="col-lg-4">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">User Name: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control" type="text" name="name"
                                            placeholder="Enter User Name" required autocomplete="off">
                                    </div>
                                </div>
                                <!-- col-4 -->
                                <div class="col-lg-4">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">User Email: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control" type="email" name="email"
                                            placeholder="Enter email address" required>
                                    </div>
                                </div>
                                <!-- col-4 -->
                                <div class="col-lg-4">
                                    
                                    <div class="form-group">
                                        <label class="form-control-label">User Password: <span class="tx-danger">*</span></label>
                                        <div class="position-relative">
                                            <input class="form-control" id="password" type="password" name="password" placeholder="Enter Password" required autocomplete="off">
                                            <i class="bi bi-eye-slash position-absolute" id="toggle-password" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label">User Phone: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control" type="tel" name="phone"
                                            placeholder="Enter Phone Number" required>
                                    </div>
                                </div>
                                <!-- col-4 -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">User Role: <span
                                                class="tx-danger">*</span></label>
                                        <select class="form-select form-select2-demo" required name="role_id"
                                            data-placeholder="Select User Role" tabindex="-1" aria-hidden="true">
                                            <option label="Choose User Role" value=""></option>
                                            @foreach ($roles as $role)
                                                @if ($role->id == 1)
                                                    @if ($logged_in_user->role_id == 1)
                                                        <option value="{{ $role->id }}">{{ $role->title }}</option>
                                                    @endif
                                                @elseif($role->id != 1)
                                                    <option value="{{ $role->id }}">{{ $role->title }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- col-4 -->
                            </div>
                            <hr>
                            <h4>Business Info</h4>
                            <div class="row mg-b-25">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label active">Business Name:</label>
                                        <input class="form-control" type="text" name="business_name"
                                            placeholder="WareHub Inc">
                                    </div>
                                </div>
                                <!-- col-4 -->
                                <div class="col-lg-4">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">Business Email:</label>
                                        <input class="form-control" type="email" name="business_name"
                                            placeholder="stock@business.com.au">
                                    </div>
                                </div>
                                <!-- col-4 -->
                                <div class="col-lg-4">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label">Business Phone:</label>
                                        <input class="form-control" type="tel" name="business_phone"
                                            placeholder="6123456789">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label">Business Street Address: <span
                                                class="tx-danger"></span></label>
                                        <input class="form-control address-input" type="address" name="business_street_address"
                                            placeholder="123 Main Street, Sydney, NSW 2000">
                                            <div class="suggestions" style="background:white;"></div>
                                              <div class="details"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label">Business Area: <span
                                                class="tx-danger"></span></label>
                                        <input class="form-control address-input" type="address" name="area_address"
                                            placeholder="Sydney, Australia">
                                            <div class="suggestions" style="background:white;"></div>
                                              <div class="details"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- row -->
                            <div class="form-layout-footer">
                                <hr>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                        name="confirmation_email_sending" id="customCheck2">
                                    <label class="custom-control-label" for="customCheck2"> Would you like to send
                                        login details email to user?</label>
                                </div>
                                <hr>
                                <button class="btn btn-success" type="submit">Submit Form</button>
                                <button class="btn btn-secondary"
                                    onclick="window.location = '{{ url('/') }}/admin/users'"
                                    type="button">Cancel</button>
                                <div class="response_section"></div>
                            </div>
                            <!-- form-layout-footer -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>        
   
@include('Admin.Includes.footer')
<script>
     document.getElementById('toggle-password').addEventListener('click', function() {
        var passwordField = document.getElementById('password');
        var icon = document.getElementById('toggle-password');

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            passwordField.type = "password";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    });
    $('#create_user_account').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Please wait...',
            text: 'Processing your request',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        var formData = $(this).serialize();

        $.ajax({
            url: "{{ route('users.store') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                Swal.close(); // close loading

                switch (response.status) {
                    case 200:
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('input[name=password_confirmation], input[name=password], input[name=name], input[name=phone],input[name=_b_name],input[name=_b_email],input[name=_b_number],input[name=_b_street_address],input[name=_b_area_address]')
                            .val('');
                        break;

                    case 500:
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                        break;
                }
            },
            error: function(xhr, status, error) {
                Swal.close(); // close loading

                if (xhr.status == 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorList = '<ul>';
                    for (let field in errors) {
                        errorList += '<li>' + errors[field][0] + '</li>';
                    }
                    errorList += '</ul>';
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errorList
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Something went wrong. Please try again later.'
                    });
                }
            }
        });
    });
</script>

