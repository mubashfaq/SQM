@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\DiscountClass;
    $logged_in_user = Auth::user();
@endphp

<div class="page-content">
    <div class="container-fluid">
        <div class="page-inner">
            <!--================================-->
            <!-- Breadcrumb Start -->
            <!--================================-->
            <div class="card shadow-sm mb-4">

                <div class="pageheader pd-t-25 pd-b-35">
                    <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
                        <div class="clearfix">
                            <div class="pd-t-5 pd-b-5">
                                <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Expired Cards List') }}</h1>
                            </div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('Admin.Dashboard') }}"
                                            class="link-primary text-decoration-none">
                                            <i class="fa-solid fa-house me-1"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('/admin/gift-cards') }}"
                                            class="link-primary text-decoration-none">
                                            Gift Cards
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Expired Cards List
                                    </li>
                                </ol>
                            </nav>
                        </div>

                        <div class="d-flex align-items-center gap-2">

                            <a href="{{ url('/') }}/admin/gift-cards/create" class="btn btn-success">
                                <i class="fa-solid fa-circle-plus me-1"></i> Create Gift Card
                            </a>
                            <a href="{{ url('/') }}/admin/gift-cards" class="btn btn-primary">
                                <i class="fa-solid fa-list-ul me-1"></i> Gift Card List
                            </a>
                            <a href="{{ url('/') }}/admin/gift-cards/expired/list" class="btn btn-warning">
                                <i class="fa-solid fa-calendar-xmark me-1"></i> Expired List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Breadcrumb End -->
            <!--================================-->
            <!--================================-->
            <div class="col-md-12 col-lg-12 px-0">
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
                <div class="card mg-b-30">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-header-title tx-13 mb-0">{{ __('Expired Cards List') }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data_Table" class="table table-hover table-borderless">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Variant</th>
                                        <th>Usage</th>
                                        <th>Discount</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Used</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($gift_cards as $gift_card)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>
                                                <a href="{{ route('Admin.gift_card.purchased', ['card_id' => $gift_card->id]) }}"
                                                    class="product-title">
                                                    {{ $gift_card->title }}
                                                </a>
                                            </td>
                                            <td>
                                                @if ($gift_card->variant && $gift_card->variant == 'gift_card')
                                                    Gift Card
                                                @elseif($gift_card->variant && $gift_card->variant == 'promotion')
                                                    Promotion
                                                @elseif($gift_card->variant && $gift_card->variant == 'coupon')
                                                    Coupon
                                                @endif
                                            </td>
                                            <td>
                                                <a class="product-title" target="_blank">
                                                    @if ($gift_card->usage && $gift_card->usage == 'one_time')
                                                        One Time
                                                    @elseif ($gift_card->usage && $gift_card->usage == 'multiple_times')
                                                        Multiple Times
                                                    @else
                                                        One Time Per User
                                                    @endif
                                                </a>
                                            </td>
                                            <td>
                                                {{ $gift_card->discount ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $gift_card->start_date ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $gift_card->end_date ?? 'N/A' }}
                                            </td>
                                            <td class="active-status">{{ ucwords($gift_card->status) }}</td>
                                            <td>{{ $gift_card->total_used }} Times</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm text-dark rounded shadow-sm"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                                        style="background-color: #c2d6e7;">
                                                        &#8942; {{-- vertical three-dot icon --}}
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('gift-cards.edit', ['gift_card' => $gift_card->id]) }}"><i
                                                                    class="fa-solid fa-pen-to-square  me-2"></i>Edit</a>
                                                        </li>
                                                        <li><button class="dropdown-item text-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteConfirmModal{{ $gift_card->id }}"><i
                                                                    class="fa-solid fa-trash me-2"></i>Delete</button>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        @if (in_array('Change Status', Session::get('permissions')))
                                                            <li><a class="dropdown-item cursor-pointer"
                                                                    href="javascript:void(0);"
                                                                    onclick="GiftCardUpdateStatus({{ $gift_card->id }}, 'active', this)">
                                                                    <i class="fa-solid fa-globe me-2"></i>Active
                                                                </a>
                                                            </li>
                                                            <li><a class="dropdown-item cursor-pointer"
                                                                    href="javascript:void(0);"
                                                                    onclick="GiftCardUpdateStatus({{ $gift_card->id }}, 'expired', this)">
                                                                    <i
                                                                        class="fa-solid fa-calendar-xmark me-2"></i>Expire
                                                                </a>
                                                            </li>
                                                            <li><a class="dropdown-item cursor-pointer"
                                                                    href="javascript:void(0);"
                                                                    onclick="GiftCardUpdateStatus({{ $gift_card->id }}, 'deleted', this)">
                                                                    <i class="fa-solid fa-trash me-2"></i>Trash
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade zoomIn" id="deleteConfirmModal{{ $gift_card->id }}"
                                            tabindex="-1"
                                            aria-labelledby="deleteConfirmModalLabel{{ $gift_card->id }}"
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
                                                                    <strong>{{ $gift_card->title }}</strong>?<br>
                                                                    This action cannot be undone.
                                                                </p>

                                                            </div>
                                                        </div>

                                                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                            <button type="button"
                                                                class="btn w-sm btn-light btn-hover"
                                                                data-bs-dismiss="modal">Cancel</button>

                                                            <form
                                                                action="{{ route('gift-cards.destroy', ['gift_card' => $gift_card->id]) }}"
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
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Variant</th>
                                        <th>Usage</th>
                                        <th>Discount</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Used</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/  Order Active dataTable End -->
            <!--================================-->
        </div>
        <!--/ Page Inner End -->

        <!--/ Reason Modal Starts -->
        <div class="modal fade" id="reasonModal" tabindex="-1" role="dialog" aria-labelledby="reasonModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered shadow" role="document">
                <div class="modal-content rounded-3">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="reasonModalLabel">Write the Reason</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="reason" class="form-label fw-medium">Reason:</label>
                            <textarea class="form-control" id="reason" rows="4" placeholder="Enter your reason here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveReasonBtn">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var card_id = 0;

    var card_id = 0;
    // Add this JavaScript function to your page
    function GiftCardUpdateStatus(id, status, element) {
        // Show the reason modal
        $('#reasonModal').modal('show');

        // Handle the save reason button click
        $('#saveReasonBtn').off('click').on('click', function() {
            const reason = $('#reason').val();

            if (!reason || reason.trim() === '') {
                alert('Please provide a reason.');
                return;
            }

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/gift-cards/${id}/update-status`;
            form.style.display = 'none';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);

            // Add method PUT
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PUT';
            form.appendChild(methodField);

            // Add status
            const statusField = document.createElement('input');
            statusField.type = 'hidden';
            statusField.name = 'status';
            statusField.value = status.toLowerCase();
            form.appendChild(statusField);

            // Add reason
            const reasonField = document.createElement('input');
            reasonField.type = 'hidden';
            reasonField.name = 'reason';
            reasonField.value = reason;
            form.appendChild(reasonField);

            document.body.appendChild(form);
            form.submit();
        });

        // Clear the reason textarea when the modal is shown
        $('#reasonModal').on('shown.bs.modal', function() {
            $('#reason').val('');
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
    }, 5000);
</script>
