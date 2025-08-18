@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
    use App\General\BrandClass;
@endphp
<div class="page-content">
    <div class="container-fluid">
        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="pageheader pd-t-25 pd-b-35">
                    <div class="d-flex justify-content-between flex-wrap ">
                        <div class="clearfix">
                            <div class="pd-t-5 pd-b-5">
                                <h4 class="pd-0 mg-0 tx-20 tx-dark">{{ __('Trash Brand List') }}</h4>
                            </div>
        
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 m-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                            <i class="fa-solid fa-house me-1"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('/') }}/admin/brands" class="link-primary text-decoration-none">
                                            Brand List
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Trash
                                    </li>
                                </ol>
                            </nav>
                        </div>
        
                        @if (Session::has('permissions') &&
                                (in_array('Brand Create', Session::get('permissions')) || in_array('Brand List', Session::get('permissions'))))
                            <div class="d-flex align-items-center gap-2">
                                @if (in_array('Brand Create', Session::get('permissions')))
                                    <a href="{{ url('/') }}/admin/brands/create" class="btn btn-success">
                                        <i class="bi bi-plus-circle me-1"></i> Create Brand
                                    </a>
                                @endif
                                @if (in_array('Brand List', Session::get('permissions')))
                                    <a href="{{ url('/') }}/admin/brands" class="btn btn-primary">
                                        <i class="bi bi-list-ul me-1"></i> Brand List
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!--/ Breadcrumb End -->
        <!--================================-->
        <!--================================-->
        <div class="col-md-12 col-lg-12 px-0">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}</a>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card mg-b-30">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-header-title tx-13 mb-0">{{ __('Trash Brand List') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body pd-0">
                    <div class="table-responsive">
                        <table id="data_Table" class="table table-borderless  align-middle"
                            style=" width: 100% !important;">
                            <thead>
                                <tr>
                                    <th width="25px">#</th>
                                    <th width="330px">Title</th>
                                    <th width="330px">Created By</th>
                                    <th>Active</th>
                                    @if (Session::has('permissions') &&
                                            (in_array('Brand Edit', Session::get('permissions')) ||
                                                in_array('Brand Delete', Session::get('permissions')) ||
                                                in_array('Change Status', Session::get('permissions'))))
                                        <th width="100px">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($brands as $brand)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>
                                            {{ $brand->title ?? ''}}
                                        </td>
                                        <td>
                                            @php print(BrandClass::CreateBy($brand->created_by)); @endphp
                                        </td>
                                        <td class="active-status">{{ BrandClass::check_active($brand->active) }}</td>
                                        @if (Session::has('permissions') &&
                                                (in_array('Brand Edit', Session::get('permissions')) ||
                                                    in_array('Brand Delete', Session::get('permissions')) ||
                                                    in_array('Change Status', Session::get('permissions'))))
                                            <td>
                                                @if ($brand->id)
                                                <div class="dropdown">
                                                    <button class="btn btn-sm text-dark rounded shadow-sm" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false"
                                                        style="background-color: #c2d6e7;">
                                                        &#8942; {{-- vertical three-dot icon --}}
                                                    </button>



                                                    <ul class="dropdown-menu">
                                                        @if (in_array('Brand Edit', Session::get('permissions')))
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('brands.edit', ['brand' => $brand->id]) }}">Edit</a>
                                                            </li>
                                                        @endif
                                                        @if (in_array('Brand Delete', Session::get('permissions')))
                                                            <li><button class="dropdown-item text-danger"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteConfirmModal{{ $brand->id }}">Delete</button>
                                                            </li>
                                                        @endif
                                                        @if (in_array('Change Status', Session::get('permissions')))
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item cursor-pointer"
                                                                    onclick="ProductUpdateStatus({{ $brand->id }}, 0, this)">Mark
                                                                    as Draft</a></li>
                                                            <li><a class="dropdown-item cursor-pointer"
                                                                    onclick="ProductUpdateStatus({{ $brand->id }}, 1, this)">Mark
                                                                    as Public</a></li>
                                                            <li><a class="dropdown-item cursor-pointer"
                                                                    onclick="ProductUpdateStatus({{ $brand->id }}, 2, this)">Mark
                                                                    as Private</a></li>
                                                            <li><a class="dropdown-item text-warning cursor-pointer"
                                                                    onclick="ProductUpdateStatus({{ $brand->id }}, 3, this)">Move
                                                                    to Trash</a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                    <div class="modal fade zoomIn" id="deleteConfirmModal{{ $brand->id }}" tabindex="-1" aria-labelledby="deleteConfirmModalLabel{{ $brand->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                
                                                <div class="modal-header">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                
                                                <div class="modal-body p-md-5">
                                                    <div class="text-center">
                                                        <div class="text-danger">
                                                            <i class="bi bi-trash display-4"></i>
                                                        </div>
                                                        <div class="mt-4 fs-15">
                                                            <h4 class="mb-1">Are you sure?</h4>
                                                            <p class="text-muted mx-3 fs-16 mb-0">
                                                                Are you sure you want to delete <strong>{{ $brand->title }}</strong>?<br>
                                                                This action cannot be undone.
                                                            </p>
                                                            <p class="text-muted mt-2 mb-0">
                                                                <small>Suggestion: mark it as <em>Draft</em> or <em>Private</em> instead.</small>
                                                            </p>
                                                        </div>
                                                    </div>
                                
                                                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                        <button type="button" class="btn w-sm btn-light btn-hover" data-bs-dismiss="modal">Cancel</button>
                                
                                                        <form action="{{ route('brands.destroy', ['brand' => $brand->id]) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn w-sm btn-danger btn-hover">Yes, Delete It!</button>
                                                        </form>
                                                    </div>
                                                </div>
                                
                                            </div>
                                        </div>
                                    </div>
                                    @php $i++; @endphp
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
                const $row = $(elementObj).closest('tr');
                const $statusCell = $row.find('.active-status');

                // Update status text
                $statusCell.text(newStatusText);

                // Show success alert
                const alertHTML = `
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        Status updated to <strong>${newStatusText}</strong> successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                $('.page-content .container-fluid').prepend(alertHTML);

                // Auto-hide alert
                setTimeout(() => {
                    $('.alert-success').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);

                // If status is Trash and current page is NOT the Trash List, remove the row
                const isTrashPage = window.location.href.includes('/admin/brands/list/trash');
                if (status === 3 && !isTrashPage) {
                    $row.fadeOut(300, function () {
                        $row.remove();
                    });
                }

                // If status was updated on Trash Page, and it's now not Trash, also remove it
                if (isTrashPage && status !== 3) {
                    $row.fadeOut(300, function () {
                        $row.remove();
                    });
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

                // Auto-hide error alert
                setTimeout(() => {
                    $('.alert-danger').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        });
    }

</script>
