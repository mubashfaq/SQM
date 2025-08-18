@include('Admin.Includes.header')

@php
    use Illuminate\Support\Facades\Auth;
    use App\General\CollectionClass;
    $logged_in_user = Auth::user();
@endphp

<div class="page-content">
    <div class="container-fluid">

        <div class="card shadow-sm mb-4">

            <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div>
                    <h4 class="fs-4 mb-1">{{ __('Collection List') }}</h4>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                            <li class="breadcrumb-item">
                                <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                    <i class="fa-solid fa-house me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Collection List
                            </li>
                        </ol>
                    </nav>
                </div>

                @if (Session::has('permissions') &&
                        (in_array('Create a Collection', Session::get('permissions')) ||
                            in_array('Collection Trash List', Session::get('permissions'))))
                    <div class="d-flex gap-2">
                        @if (Session::has('permissions') && in_array('Create a Collection', Session::get('permissions')))
                            <a href="{{ url('/') }}/admin/collections/create" class="btn btn-success">
                                <i class="fa-solid fa-circle-plus me-1"></i>Create Collection
                            </a>
                        @endif
                        @if (Session::has('permissions') && in_array('Collection Trash List', Session::get('permissions')))
                            <a href="{{ url('/') }}/admin/collections/list/trash" class="btn btn-danger">
                                <i class="fa-solid fa-trash me-1"></i> Trash List
                            </a>
                        @endif
                    </div>
                @endif
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
                <h5 class="card-title mb-0">Collection Table</h5>
            </div>
            <div class="card-body  ">
                <div class="table-responsive">
                    <table id="data_Table" class="table table-borderless  align-middle">
                        <thead class="">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Permalink</th>
                                <th>Created By</th>
                                <th>Active</th>
                                @if (Session::has('permissions') &&
                                        (in_array('Collection Edit', Session::get('permissions')) ||
                                            in_array('Collection Delete', Session::get('permissions')) ||
                                            in_array('Collection Change Status', Session::get('permissions'))))
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($collections as $collection)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>
                                        {{ $collection->title }}
                                    </td>
                                    <td>{{ $collection->permalink }}</td>
                                    <td>@php print(CollectionClass::CreateBy($collection->created_by)); @endphp</td>
                                    <td class="active-status">
                                        @php
                                            $activeStatus = CollectionClass::check_active($collection->active);
                                            $badgeClass =
                                                $activeStatus == 'Public'
                                                    ? 'bg-primary-subtle text-primary'
                                                    : ($activeStatus == 'Draft'
                                                        ? 'bg-warning-subtle text-warning'
                                                        : ($activeStatus == 'Private'
                                                            ? 'bg-info-subtle text-info'
                                                            : 'bg-secondary-subtle text-secondary'));
                                        @endphp
                                        <span id="collection-status-{{ $collection->id }}"
                                            class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $activeStatus }}</span>
                                    </td>

                                    <!-- Your existing dropdown code with consistent function names -->
                                    @if (Session::has('permissions') &&
                                            (in_array('Collection Edit', Session::get('permissions')) ||
                                                in_array('Collection Delete', Session::get('permissions')) ||
                                                in_array('Collection Change Status', Session::get('permissions'))))
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm text-dark rounded shadow-sm" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"
                                                    style="background-color: #c2d6e7; padding: 5px 10px;">
                                                    &#8942; {{-- vertical three-dot icon --}}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if (in_array('Collection Edit', Session::get('permissions')))
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('collections.edit', ['collection' => $collection->id]) }}">
                                                                <i class="fa-solid fa-pen-to-square me-2"></i>Edit</a>
                                                        </li>
                                                    @endif
                                                    @if (in_array('Collection Delete', Session::get('permissions')))
                                                        <li><button class="dropdown-item text-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteConfirmModal{{ $collection->id }}">
                                                                <i class="fa-solid fa-trash me-2"></i> Delete</button>
                                                        </li>
                                                    @endif
                                                    @if (in_array('Change Status', Session::get('permissions')))
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li><a class="dropdown-item {{ $collection->active == 0 ? 'active' : '' }} cursor-pointer"
                                                                onclick="CollectionUpdateStatus({{ $collection->id }}, 0, this)">
                                                                <i class="fa-solid fa-file-pen me-2"></i> Draft</a>
                                                        </li>
                                                        <li><a class="dropdown-item {{ $collection->active == 1 ? 'active' : '' }} cursor-pointer"
                                                                onclick="CollectionUpdateStatus({{ $collection->id }}, 1, this)">
                                                                <i class="fa-solid fa-globe me-2"></i> Public</a>
                                                        </li>
                                                        <li><a class="dropdown-item {{ $collection->active == 2 ? 'active' : '' }} cursor-pointer"
                                                                onclick="CollectionUpdateStatus({{ $collection->id }}, 2, this)">
                                                                <i class="fa-solid fa-lock me-2"></i> Private</a>
                                                        </li>
                                                        <li><a class="dropdown-item text-warning {{ $collection->active == 3 ? 'active' : '' }} cursor-pointer"
                                                                onclick="CollectionUpdateStatus({{ $collection->id }}, 3, this)">
                                                                <i class="fa-solid fa-trash me-2"></i> Trash</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    @endif

                                </tr>



                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade zoomIn" id="deleteConfirmModal{{ $collection->id }}"
                                    tabindex="-1" aria-labelledby="deleteConfirmModalLabel{{ $collection->id }}"
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
                                                        <i class="fa-solid fa-trash fs-1"></i>
                                                    </div>
                                                    <div class="mt-4 fs-15">
                                                        <h4 class="mb-1">Are you sure?</h4>
                                                        <p class="text-muted mx-3 fs-16 mb-0">
                                                            Are you sure you want to delete
                                                            <strong>{{ $collection->title }}</strong>?<br>
                                                            This action cannot be undone.
                                                        </p>

                                                    </div>
                                                </div>

                                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                    <button type="button" class="btn w-sm btn-light btn-hover"
                                                        data-bs-dismiss="modal">Cancel</button>

                                                    <form
                                                        action="{{ route('collections.destroy', ['collection' => $collection->id]) }}"
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
                                @php $i++; @endphp
                            @endforeach
                        </tbody>
                        <tfoot class="">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>permalink</th>
                                <th>Created By</th>
                                <th>Active</th>
                                @if (Session::has('permissions') &&
                                        (in_array('Collection Edit', Session::get('permissions')) ||
                                            in_array('Collection Delete', Session::get('permissions')) ||
                                            in_array('Collection Change Status', Session::get('permissions'))))
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

@include('Admin.Includes.footer')

<script>
    function CollectionUpdateStatus(productId, status, elementObj) {
        const statusMap = {
            0: {
                label: 'Draft',
                class: 'bg-warning-subtle text-warning'
            },
            1: {
                label: 'Public',
                class: 'bg-primary-subtle text-primary'
            },
            2: {
                label: 'Private',
                class: 'bg-info-subtle text-info'
            },
            3: {
                label: 'Trash',
                class: 'bg-secondary-subtle text-secondary'
            }
        };

        $.ajax({
            url: '/admin/collections/' + productId + '/update-status',
            type: 'PUT',
            data: {
                active: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Update status badge
                const $statusBadge = $('#collection-status-' + productId);
                $statusBadge
                    .attr('class', 'badge px-3 py-2 rounded-pill ' + statusMap[status].class)
                    .text(statusMap[status].label);

                // Highlight the selected item
                const $dropdown = $(elementObj).closest('.dropdown-menu');
                $dropdown.find('.dropdown-item').removeClass('active');
                $(elementObj).addClass('active');

                // Show success alert at the top
                const $container = $('.page-content, .page-inner').first();
                const alertHTML = `
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    Status changed to <strong>${statusMap[status].label}</strong> successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
                $container.prepend(alertHTML);

                setTimeout(() => {
                    $('.alert-success').fadeOut(400, function() {
                        $(this).remove();
                    });
                }, 4000);

                // If status is Trash, redirect to trash page
                if (status == 3) {
                    window.location = '{{ route('Admin.collection.Trash') }}';
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message :
                    'Error occurred.';

                const $container = $('.page-content, .page-inner').first();
                const alertHTML = `
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i></i>
                    ${errorMessage}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
                $container.prepend(alertHTML);

                setTimeout(() => {
                    $('.alert-danger').fadeOut(400, function() {
                        $(this).remove();
                    });
                }, 4000);
            }
        });
    }

    // Fix any incorrect onclick handlers (ProductUpdateStatus → CollectionUpdateStatus)
    $(document).ready(function() {
        $('[onclick*="ProductUpdateStatus"]').each(function() {
            const originalOnclick = $(this).attr('onclick');
            const correctedOnclick = originalOnclick.replace('ProductUpdateStatus',
                'CollectionUpdateStatus');
            $(this).attr('onclick', correctedOnclick);
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
    }, 5000);
</script>
