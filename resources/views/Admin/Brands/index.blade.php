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
                <div class="d-flex justify-content-between align-items-center flex-wrap ">
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

                    @if (Session::has('permissions') &&
                            (in_array('Brand Create', Session::get('permissions')) ||
                                in_array('Brand Trash List', Session::get('permissions'))))
                        <div class="d-flex gap-2">
                            @if (in_array('Brand Create', Session::get('permissions')))
                                <a href="{{ url('/') }}/admin/brands/create" class="btn btn-success">
                                    <i class="bi bi-plus-circle me-1"></i> Create Brand
                                </a>
                            @endif

                            @if (in_array('Brand Trash List', Session::get('permissions')))
                                <a href="{{ url('/') }}/admin/brands/list/trash" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i> Trash List
                                </a>
                            @endif
                        </div>
                    @endif
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

        <!-- Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title mb-0">Brands Table</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="data_Table" class="table table-borderless  align-middle">
                        <thead class="">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Created By</th>
                                <th>Active</th>
                                @if (Session::has('permissions') &&
                                        (in_array('Brand Edit', Session::get('permissions')) ||
                                            in_array('Brand Delete', Session::get('permissions')) ||
                                            in_array('Change Status', Session::get('permissions'))))
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($brands as $brand)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ url('/') . '/' . $brand->logo_image }}" alt="Logo"
                                                class="rounded border me-2"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <span>{{ $brand->title }}</span>
                                        </div>
                                    </td>
                                    <td>{!! BrandClass::CreateBy($brand->created_by) !!}</td>
                                    <td class="active-status">{{ BrandClass::check_active($brand->active) }}</td>

                                    @if (Session::has('permissions') &&
                                            (in_array('Brand Edit', Session::get('permissions')) ||
                                                in_array('Brand Delete', Session::get('permissions')) ||
                                                in_array('Change Status', Session::get('permissions'))))
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm text-dark rounded shadow-sm" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"
                                                    style="background-color: #c2d6e7;">
                                                    &#8942; {{-- vertical three-dot icon --}}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if (in_array('Brand Edit', Session::get('permissions')))
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('brands.edit', ['brand' => $brand->id]) }}"><i
                                                                    class="bi bi-pencil-square me-2"></i>Edit</a>
                                                        </li>
                                                    @endif
                                                    @if (in_array('Brand Delete', Session::get('permissions')))
                                                        <li><button class="dropdown-item text-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteConfirmModal{{ $brand->id }}"><i
                                                                    class="bi bi-trash me-2"></i>Delete</button>
                                                        </li>
                                                    @endif
                                                    @if (in_array('Change Status', Session::get('permissions')))
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item {{ $brand->active == 0 ? 'active' : '' }} cursor-pointer"
                                                                onclick="ProductUpdateStatus({{ $brand->id }}, 0, this)">
                                                                <i class="bi bi-pencil me-2"></i> Draft
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item {{ $brand->active == 1 ? 'active' : '' }} cursor-pointer"
                                                                onclick="ProductUpdateStatus({{ $brand->id }}, 1, this)">
                                                                <i class="bi bi-globe me-2"></i> Public
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item {{ $brand->active == 2 ? 'active' : '' }} cursor-pointer"
                                                                onclick="ProductUpdateStatus({{ $brand->id }}, 2, this)">
                                                                <i class="bi bi-lock me-2"></i> Private
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-warning {{ $brand->active == 3 ? 'active' : '' }} cursor-pointer"
                                                                onclick="ProductUpdateStatus({{ $brand->id }}, 3, this)">
                                                                <i class="bi bi-trash me-2"></i> Trash
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    @endif
                                </tr>



                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade zoomIn" id="deleteConfirmModal{{ $brand->id }}"
                                    tabindex="-1" aria-labelledby="deleteConfirmModalLabel{{ $brand->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body p-md-5">
                                                <div class="text-center">
                                                    <div class="text-danger">
                                                        <i class="bi bi-trash display-4"></i>
                                                    </div>
                                                    <div class="mt-4 fs-15">
                                                        <h4 class="mb-1">Are you sure?</h4>
                                                        <p class="text-muted mx-3 fs-16 mb-0">
                                                            Are you sure you want to delete
                                                            <strong>{{ $brand->title }}</strong>?<br>
                                                            This action cannot be undone.
                                                        </p>

                                                    </div>
                                                </div>

                                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                    <button type="button" class="btn w-sm btn-light btn-hover"
                                                        data-bs-dismiss="modal">Cancel</button>

                                                    <form
                                                        action="{{ route('brands.destroy', ['brand' => $brand->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn w-sm btn-danger btn-hover">Yes, Delete
                                                            It!</button>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                        <tfoot class="">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Created By</th>
                                <th>Active</th>
                                @if (Session::has('permissions') &&
                                        (in_array('Brand Edit', Session::get('permissions')) ||
                                            in_array('Brand Delete', Session::get('permissions')) ||
                                            in_array('Change Status', Session::get('permissions'))))
                                    <th>Actions</th>
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
    function ProductUpdateStatus(productId, status, elementObj) {
        const statusLabels = ['Draft', 'Public', 'Private', 'Trash'];
        const newStatusText = statusLabels[status] || 'Unknown';

        $.ajax({
            url: '/admin/brands/' + productId + '/update-status',
            type: 'PUT',
            data: {
                active: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Update status text in the table
                const $statusCell = $(elementObj).closest('tr').find('.active-status');
                $statusCell.text(newStatusText);

                // Show success alert
                const alertHTML = `
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        Status updated to <strong>${newStatusText}</strong> successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;

                $('.page-content .container-fluid').prepend(alertHTML);

                // Auto-hide alert after 5 seconds
                setTimeout(() => {
                    $('.alert-success').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);

                // If moved to Trash, redirect
                if (status === 3) {
                    window.location = '{{ route('Admin.Brand.Trash') }}';
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Something went wrong.';
                const alertHTML = `
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        ${errorMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;

                $('.page-content .container-fluid').prepend(alertHTML);

                // Auto-hide error after 5 seconds
                setTimeout(() => {
                    $('.alert-danger').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        });
    }

    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            // Add fade out effect using Bootstrap classes
            alert.classList.remove('show');
            alert.classList.add('fade');

            // Completely remove from DOM after transition
            setTimeout(() => alert.remove(), 500); // 500ms matches the fade-out transition duration
        });
    }, 5000); // 5 seconds
</script>
