@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\DiscountClass;
    $logged_in_user = Auth::user();
    $currentDate = date('Y-m-d');
@endphp

<div class="page-content">
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
    <div class="card shadow-sm mb-4">

        <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div>
                <h4 class="fs-4 mb-1">{{ __('Discount List') }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center ">
                        <li class="breadcrumb-item">
                            <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                <i class="fa-solid fa-house me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Discount List
                        </li>
                    </ol>
                </nav>
            </div>

            @if (Session::has('permissions') &&
                    (in_array('Create a Discount', Session::get('permissions')) ||
                        in_array('Discount List', Session::get('permissions'))))
                <div class="d-flex gap-2 align-items-center">
                    @if (Session::has('permissions') && in_array('Create a Discount', Session::get('permissions')))
                        <button type="button" onclick="window.location = '{{ url('/') }}/admin/discounts/create'"
                            class="btn btn-success">
                            <i class="fa-solid fa-circle-plus me-1"></i>Create a Discount</button>
                    @endif
                    @if (Session::has('permissions') && in_array('Discount Change Status', Session::get('permissions')))
                        <button type="button"
                            onclick="window.location = '{{ url('/') }}/admin/discounts/list/trash'"
                            class="btn btn-danger">
                            <i class="fa-solid fa-trash-can me-1"></i>Trash List</button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title mb-0">Discount Table</h5>
        </div>
        <div class="card-body  ">
            <div class="table-responsive">
                <table id="data_Table" class="table table-borderless">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Badge Image</th>
                            <th>Badge Text</th>
                            <th>Discount Type</th>
                            <th>Discount Value</th>
                            <th>Created By</th>
                            <th>Discount Status</th>
                            <th>Active</th>
                            @if (Session::has('permissions') &&
                                    (in_array('Discount Edit', Session::get('permissions')) ||
                                        in_array('Discount Delete', Session::get('permissions')) ||
                                        in_array('Discount Change Status', Session::get('permissions'))))
                                <th width="100px">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($discounts as $discount)
                            <tr class="align-middle">
                                <td>{{ $i }}</td>
                                <td>{{ $discount->title }}</td>
                                <td>
                                    @php
                                        $imagePaths = explode(',', $discount->badge_image);
                                    @endphp

                                    @foreach ($imagePaths as $imagePath)
                                        <div>
                                            <div class="product-thumbnail mb-2"
                                                style="background:url({{ url('/') . '/' . $imagePath }});background-size:cover;background-position:center;width:60px;height: 60px; border-radius:0px !important;border: 1px solid #dee2e6;box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);">
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                <td>{{ $discount->badge_text }}</td>
                                <td class="">
                                    @if($discount->discount_type == 1)
                                        Fixed
                                    @else
                                        Percentage
                                    @endif
                                </td>
                                <td>
                                    @if($discount->discount_type == 1)
                                        {{$discount->discount_value}}
                                    @else
                                         {{$discount->discount_value}}%
                                    @endif
                                </td>
                                <td>@php print(DiscountClass::CreateBy($discount->created_by)); @endphp</td>
                                <td>
                                    @php
                                        $isExpired = $currentDate > $discount->end_date;
                                        $expirationBadgeClass = $isExpired ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success';
                                        $expirationStatus = $isExpired ? 'Expired' : 'Active';
                                    @endphp
                                    <span id="expiration-status-{{ $discount->id }}"
                                        class="badge {{ $expirationBadgeClass }} px-3 py-2 rounded-pill">{{ $expirationStatus }} - {{$discount->end_date}}</span>
                                </td>
                                <td>
                                    @php
                                        $activeStatus = DiscountClass::check_active($discount->active);
                                        $badgeClass =
                                            $activeStatus == 'Public'
                                                ? 'bg-primary-subtle text-primary'
                                                : ($activeStatus == 'Draft'
                                                    ? 'bg-warning-subtle text-warning'
                                                    : ($activeStatus == 'Private'
                                                        ? 'bg-info-subtle text-info'
                                                        : 'bg-secondary-subtle text-secondary'));
                                    @endphp
                                    <span id="product-status-{{ $discount->id }}"
                                        class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $activeStatus }}</span>
                                </td>
                               
                                <!-- ACTIONS Dropdown -->
                                @if (Session::has('permissions') &&
                                        (in_array('Discount Edit', Session::get('permissions')) ||
                                            in_array('Discount Delete', Session::get('permissions')) ||
                                            in_array('Discount Change Status', Session::get('permissions'))))
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm text-dark rounded shadow-sm" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                style="background-color: #c2d6e7;">
                                                &#8942;
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if (Session::has('permissions') && in_array('Discount Edit', Session::get('permissions')))
                                                    <li>
                                                        <button class="dropdown-item"
                                                            onclick="window.location='{{ route('discounts.edit', ['discount' => $discount->id]) }}'">
                                                            <i class="fa-solid fa-pen-to-square me-2"></i> Edit
                                                        </button>
                                                    </li>
                                                @endif
                                                @if (Session::has('permissions') && in_array('Discount Delete', Session::get('permissions')))
                                                    <li>
                                                        <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                                            data-bs-target="#deleteConfirmModal{{ $discount->id }}">
                                                            <i class="fa-solid fa-trash me-2"></i> Delete
                                                        </button>
                                                    </li>
                                                @endif

                                                @if (Session::has('permissions') && in_array('Discount Change Status', Session::get('permissions')))
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item {{ $discount->active == 0 ? 'active' : '' }}"
                                                            href="#"
                                                            onclick="CollectionUpdateStatus({{ $discount->id }}, 0, this)">
                                                            <i class="fa-solid fa-file-pen me-2"></i> Draft
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item {{ $discount->active == 1 ? 'active' : '' }}"
                                                            href="#"
                                                            onclick="CollectionUpdateStatus({{ $discount->id }}, 1, this)">
                                                            <i class="fa-solid fa-globe me-2"></i> Public
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button
                                                            class="dropdown-item {{ $discount->active == 2 ? 'active' : '' }}"
                                                            href="#"
                                                            onclick="CollectionUpdateStatus({{ $discount->id }}, 2, this)">
                                                            <i class="fa-solid fa-lock me-2"></i> Private
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item {{ $discount->active == 3 ? 'active' : '' }}"
                                                            href="#"
                                                            onclick="CollectionUpdateStatus({{ $discount->id }}, 3, this)">
                                                            <i class="fa-solid fa-trash me-2"></i> Trash
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                @endif

                            </tr>

                            <div class="modal fade zoomIn" id="deleteConfirmModal{{ $discount->id }}" tabindex="-1"
                                aria-labelledby="deleteConfirmModalLabel{{ $discount->id }}" aria-hidden="true">
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
                                                        <strong>{{ $discount->title }}</strong>?<br>
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

                                                <form action="{{ route('discounts.destroy', $discount->id) }}"
                                                    method="POST">
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
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Badge Image</th>
                            <th>Badge Text</th>
                            <th>Discount Type</th>
                            <th>Discount value</th>
                            <th>Created By</th>
                            <th>Discount Status</th>
                            <th>Active</th>
                            @if (Session::has('permissions') &&
                                    (in_array('Discount Edit', Session::get('permissions')) ||
                                        in_array('Discount Delete', Session::get('permissions')) ||
                                        in_array('Discount Change Status', Session::get('permissions'))))
                                <th width="100px">Actions</th>
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
    function CollectionUpdateStatus(productId, status, elementObj) {
        // Map for status labels and classes
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

        // Send the AJAX request to update the status
        $.ajax({
            url: '/admin/discounts/' + productId + '/update-status',
            type: 'PUT',
            data: {
                active: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Update the status badge
                const $statusBadge = $('#product-status-' + productId);
                $statusBadge
                    .attr('class', 'badge px-3 py-2 rounded-pill ' + statusMap[status].class)
                    .text(statusMap[status].label);

                // Highlight the selected item in the dropdown
                const $dropdown = $(elementObj).closest('.dropdown-menu');
                $dropdown.find('.dropdown-item').removeClass('active');
                $(elementObj).addClass('active');

                // Show success alert at the top
                const $container = $('.page-content, .page-inner').first();
                const alertHTML = `
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> 
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

                // If Trash, redirect to Trash page
                if (status === 3) {
                    window.location = '{{ route('Admin.discount.Trash') }}';
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message :
                    'Error occurred.';
                showToast('error', errorMessage);
            }
        });
    }

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
