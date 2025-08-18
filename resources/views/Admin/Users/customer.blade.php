@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\UserClass;
    $logged_in_user = Auth::user();
@endphp

<div class="page-content">
    <div class="card shadow-sm mb-4">

        <div class="  p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div class="clearfix">
                <div class="pd-t-5 pd-b-5">
                    <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Customer List') }}</h1>
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
                            Customer List
                        </li>
                    </ol>
                </nav>
            </div>

            @if (Session::has('permissions') && in_array('Create User', Session::get('permissions')))
                <div class="d-flex align-items-center gap-2">
                    <button type="button" onclick="window.location = '{{ url('/') }}/admin/users/create'"
                        class="btn btn-success">
                        <i class="fa-solid fa-circle-plus me-1"></i>Create User</button>
                    <button type="button" onclick="window.location = '{{ url('/') }}/admin/users'"
                        class="btn btn-primary">
                        <i class="fas fa-list me-1"></i> Users List
                    </button>
                </div>
            @endif


        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title mb-0">Customer Table</h5>
        </div>
        <div class="card-body  ">
            <div class="table-responsive">
                <table id="data_Table" class="table table-borderless">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Active</th>
                            <th>Registered By</th>
                            <th>Registered At</th>
                            @if (Session::has('permissions') &&
                                    (in_array('Delete User', Session::get('permissions')) ||
                                        in_array('User Change Status', Session::get('permissions'))))
                                <th width="180px">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $user)
                            <tr class="align-middle">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ UserClass::check_role($user->role_id, $roles) }}</td>
                                <td class="status-cell" data-user-id="{{ $user->id }}">
                                    {!! UserClass::check_active($user->active) !!}
                                </td>
                                <td>
                                    @if (!empty($user->created_by))
                                        <span>{{ UserClass::check_user($user->created_by, $users) }}</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at ?? '' }}</td>
                                @if (Session::has('permissions') &&
                                        (in_array('Delete User', Session::get('permissions')) ||
                                            in_array('User Change Status', Session::get('permissions'))))
                                    <td>
                                        @if ($logged_in_user->id != $user->id)
                                            @if ($user->role_id != 1)
                                                <div class="dropdown">
                                                    <button class="btn btn-sm text-dark rounded shadow-sm"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                                        style="background-color: #c2d6e7;">
                                                        &#8942;
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if (Session::has('permissions') && in_array('Edit User', Session::get('permissions')))
                                                            <li>
                                                                <button class="dropdown-item"
                                                                    onclick="window.location='{{ route('users.edit', ['user' => $user->id]) }}'">
                                                                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit
                                                                </button>
                                                            </li>
                                                        @endif
                                                        @if (Session::has('permissions') && in_array('Delete User', Session::get('permissions')))
                                                            <li>
                                                                <button class="dropdown-item text-danger"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteConfirmModal{{ $user->id }}">
                                                                    <i class="fa-solid fa-trash me-2"></i> Delete
                                                                </button>
                                                            </li>
                                                        @endif
                                                        @if (Session::has('permissions') && in_array('User Change Status', Session::get('permissions')))
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <h6 class="dropdown-header">Change Status</h6>
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item"
                                                                    onclick="updateStatus({{ $user->id }}, '0', this)">
                                                                    <i class="bi bi-pencil me-2"></i> Inactive
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item"
                                                                    onclick="updateStatus({{ $user->id }}, '1', this)">
                                                                    <i class="fa-solid fa-globe me-2"></i> Active
                                                                </button>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            <div class="modal fade zoomIn" id="deleteConfirmModal{{ $user->id }}" tabindex="-1"
                                aria-labelledby="deleteConfirmModalLabel{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body p-md-5">
                                            <div class="text-center">
                                                <div class="text-danger">
                                                    <<<<<<< HEAD <i class="bi bi-trash display-4"></i>
                                                        =======
                                                        <i class="fa-solid fa-trash fs-1"></i>
                                                        >>>>>>> 8c9fa5178e509e5ec5a916e0115abd3f6b30792c
                                                </div>
                                                <div class="mt-4 fs-15">
                                                    <h4 class="mb-1">Are you sure?</h4>
                                                    <p class="text-muted mx-3 fs-16 mb-0">
                                                        Are you sure you want to delete
                                                        <strong>{{ $user->name }}</strong>?<br>
                                                        This action cannot be undone.
                                                    </p>
                                                    <p class="text-muted mt-2 mb-0">
                                                        <small>Suggestion: mark it as <em>Draft</em> or
                                                            <em>Private</em> instead.</small>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                <button type="button" class="btn w-sm btn-light btn-hover"
                                                    data-bs-dismiss="modal">Cancel</button>

                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn w-sm btn-danger btn-hover">Yes,
                                                        Delete
                                                        It!</button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Active</th>
                            <th>Registered By</th>
                            <th>Registered At</th>
                            @if (Session::has('permissions') &&
                                    (in_array('Delete User', Session::get('permissions')) ||
                                        in_array('User Change Status', Session::get('permissions'))))
                                <th width="180px">Actions</th>
                            @endif
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    // Add this JavaScript function to your JS file or in a script tag at the bottom of your blade file
    function updateStatus(userId, status, element) {
        // Define status text for better UX
        const statusText = status === '1' ? 'Active' : 'Inactive';

        // CSRF token setup for Laravel AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Perform AJAX request
        $.ajax({
            url: `/admin/users/${userId}/update-status`,
            type: 'PUT',
            data: {
                active: status
            },
            beforeSend: function() {
                // Optional: Show loading state
                $(element).prop('disabled', true);
            },
            success: function(response) {
                if (response.status === 200) {
                    // Show success message with SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Update the status badge in the table
                    const row = $(element).closest('tr');
                    const statusCell = row.find('td:nth-child(5)');

                    if (status === '1') {
                        statusCell.html(
                            '<span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Active</span>'
                        );
                    } else {
                        statusCell.html(
                            '<span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">Inactive</span>'
                        );
                    }
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update status'
                    });
                }
            },
            error: function(xhr) {
                // Handle validation errors or other errors
                let errorMessage = 'Something went wrong';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            },
            complete: function() {
                // Re-enable the button
                $(element).prop('disabled', false);
            }
        });
    }
</script>
