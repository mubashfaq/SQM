@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
    use App\General\UserClass;
@endphp
<div class="page-content">
    <div class="card shadow-sm mb-4">

        <div class="  p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div class="clearfix">
                <div class="pd-t-5 pd-b-5">
                    <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Edit User') }}</h1>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin') }}" class="link-primary text-decoration-none">
                                <i class="fa-solid fa-home me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/users') }}" class="link-primary text-decoration-none">
                                Users
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Edit
                        </li>
                    </ol>
                </nav>
            </div>

            @if (Session::has('permissions') &&
                    (in_array('Create User', Session::get('permissions')) ||
                        in_array('User List', Session::get('permissions')) ||
                        in_array('Customer List', Session::get('permissions'))))
                <div class="d-flex gap-2 align-items-center">
                    @if (Session::has('permissions') && in_array('Create User', Session::get('permissions')))
                        <button type="button" onclick="window.location = '{{ url('/') }}/admin/users/create'"
                            class="btn btn-success">
                            <i class="fa-solid fa-circle-plus me-1"></i>Create User</button>
                    @endif
                    @if (Session::has('permissions') && in_array('User List', Session::get('permissions')))
                        <button type="button" onclick="window.location = '{{ url('/') }}/admin/users'"
                            class="btn btn-primary">
                            <i class="fa-solid fa-list-ul me-1"></i>User List</button>
                    @endif
                    @if (Session::has('permissions') && in_array('Customer List', Session::get('permissions')))
                        <button type="button"
                            onclick="window.location = '{{ url('/') }}/admin/users/list/customer'"
                            class="btn btn-danger">
                            <i class="fa-solid fa-trash me-1"></i>Customer List</button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-12 col-lg-12">
        <div class="card mg-b-30">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-header-title tx-13 mb-0">Edit User</h6>
                    </div>
                    <div class="text-right">
                        <div class="d-flex">
                            <a href="" class=""><i class="ti-reload"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="edit_user_account">
                    @csrf
                    <input type="hidden" name="created_by" value="{{ $logged_in_user->id }}">
                    <input type="hidden" class="update_id" name="user_id" value="{{ $User->id }}">
                    <div class="form-layout form-layout-1">
                        <div class="row mg-b-25">
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label class="form-control-label active">User Name: <span
                                            class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="name"
                                        placeholder="Enter User Name" value="{{ old('name', $User->name) }}" required>
                                </div>
                            </div>
                            <!-- col-4 -->
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label class="form-control-label active">User Email: <span
                                            class="tx-danger">*</span></label>
                                    <input class="form-control" type="email" disabled name="email"
                                        placeholder="Enter email address" value="{{ old('email', $User->email) }}"
                                        style="background: aliceblue;">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label class="form-control-label">User Phone: <span
                                            class="tx-danger">*</span></label>
                                    <input class="form-control" type="tel" name="phone"
                                        placeholder="Enter Phone Number" value="{{ old('phone', $User->phone) }}"
                                        required>
                                </div>
                            </div>
                            <!-- col-4 -->
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label class="form-control-label">User Role: <span
                                            class="tx-danger">*</span></label>
                                    <select class="form-select form-select2-demo" required name="role_id"
                                        data-placeholder="Select User Role" tabindex="-1" aria-hidden="true">
                                        <option label="Choose User Role" value=""></option>
                                        @foreach ($roles as $role)
                                            @if ($role->id == 1)
                                                <option value="{{ $role->id }}"
                                                    @if ($User->role_id == $role->id) selected @endif>
                                                    {{ $role->title }}</option>
                                            @elseif($role->id != 1)
                                                <option value="{{ $role->id }}"
                                                    @if ($User->role_id == $role->id) selected @endif>
                                                    {{ $role->title }}</option>
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
                                <div class="form-group mb-3">
                                    <label class="form-control-label active">Business Name:</label>
                                    <input class="form-control" type="text" name="business_name"
                                        value="{{ old('business_name', UserClass::getOptionValue('business_name', $User->userMeta)) }}"
                                        placeholder="WareHub Inc">
                                </div>
                            </div>
                            <!-- col-4 -->
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label class="form-control-label active">Business Email:</label>
                                    <input class="form-control" type="email" name="business_email"
                                        value="{{ old('business_email', UserClass::getOptionValue('business_email', $User->userMeta)) }}"
                                        placeholder="stock@business.com.au">
                                </div>
                            </div>
                            <!-- col-4 -->
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label class="form-control-label">Business Phone:</label>
                                    <input class="form-control" type="tel" name="business_phone"
                                        value="{{ old('business_phone', UserClass::getOptionValue('business_phone', $User->userMeta)) }}"
                                        placeholder="6123456789">
                                </div>
                            </div>
                           
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label class="form-control-label">Business Street Address: <span
                                            class="tx-danger"></span></label>
                                    <input class="form-control address-input" type="address" name="business_street_address"
                                        value="{{ old('business_street_address', UserClass::getOptionValue('business_street_address', $User->userMeta)) }}"
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
                                        value="{{ old('area_address', UserClass::getOptionValue('area_address', $User->userMeta)) }}"
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
                                <input type="checkbox" class="custom-control-input" name="confirmation_email_sending"
                                    id="customCheck2">
                                <label class="custom-control-label" for="customCheck2"> Would you like to send
                                    login details email to user?</label>
                            </div>
                            <hr>
                            <button class="btn btn-success" type="submit">Update</button>
                            @if ($User->role_id == 5)
                                <button class="btn btn-secondary"
                                    onclick="window.location = '{{ url('/') }}/admin/users/list/customer'"
                                    type="button">Cancel</button>
                            @else
                                <button class="btn btn-secondary"
                                    onclick="window.location = '{{ url('/') }}/admin/users'"
                                    type="button">Cancel</button>
                            @endif
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
    $('#edit_user_account').on('submit', function(e) {
        e.preventDefault();
        $('.response_section').html(`<div class="alert alert-info mt-1">Processing...</div>`);
        var formData = $(this).serialize();
        var u_id = $('.update_id').val();
        $.ajax({
            url: "{{ url('/admin/users') }}/" + u_id,
            type: "PUT",
            data: formData,
            success: function(response) {
                switch (response.status) {
                    case 200:
                        $('.response_section').html(
                            `<div class="alert alert-success mt-1">${response.message}</div>`);
                        break;
                    case 500:
                        $('.response_section').html(
                            `<div class="alert alert-danger mt-1">${response.message}</div>`);
                        break;
                }
            },
            error: function(xhr, status, error) {
                // handle error response
                if (xhr.status == 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorList = '<ul>';
                    for (let field in errors) {
                        errorList += '<li>' + errors[field][0] + '</li>';
                    }
                    errorList += '</ul>';
                    $('.response_section').html('<div class="alert alert-danger mt-1">' +
                        errorList + '</div>');
                } else {
                    $('.response_section').html(
                        '<div class="alert alert-danger mt-1">Server Error, Try Again Later!</div>'
                    );
                }
            }
        });
    });
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            // Use Bootstrap's fade out
            alert.classList.remove('show');
            alert.classList.add('fade');
            // Completely remove from DOM after transition
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000)
</script>
