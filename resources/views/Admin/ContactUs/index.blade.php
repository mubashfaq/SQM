@include('Admin.Includes.header')
<style>
    .transition-all {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12) !important;
    }

    .icon-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
    }

    .modal-content {
        overflow: hidden;
    }
</style>
<div class="page-content">
    <div class="container-fluid">
        <div class="card shadow-sm mb-4">

        <div class="p-3 mb-3">
            <h4 class="mb-1">{{ __('Contact List') }}</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                            <i class="fa-solid fa-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Contact List
                    </li>
                </ol>
            </nav>
        </div>
        </div>
        <div id="alertContainer"></div>
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

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Contact Table</h5>
                    <button type="button" id="bulkDeleteBtn" class="btn btn-danger" disabled>Bulk Delete</button>
            
            </div>

            <div class="card-body">
                <div class="table-responsive table-borderless">
                    <table id="data_Table" class="table table-borderless align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Name</th>
                                <th>Email</th>
                                <!-- <th>Order ID</th>
                                <th>Download File</th>
                                <th>Subject</th> -->
                                <th>Created At</th>
                                <!-- <th>Phone</th> -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $contact)
                                <tr>
                                    <td><input type="checkbox" class="row-checkbox" value="{{ $contact->id }}"></td>
                                    <td>{{ $contact->f_name }} {{ $contact->l_name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <!-- <td>{{ $contact->order_id ?? 'No file available' }}</td>
                                    <td>
                                        @if ($contact->file_path)
                                            <a href="{{ asset('storage/' . $contact->file_path) }}" download
                                                class="btn btn-sm btn-success">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">No file available</span>
                                        @endif
                                    </td>
                                    <td>{{ $contact->subject }}</td> -->
                                    <td>{{ $contact->created_at->format('d-m-Y') }}</td>
                                    <!-- <td>{{ $contact->phone }}</td> -->
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm text-dark rounded shadow-sm"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #c2d6e7;">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#viewModal-{{ $contact->id }}">
                                                        <i class="fas fa-eye me-2"></i>View
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item text-danger" type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal-{{ $contact->id }}">
                                                        <i class="fas fa-trash-alt me-2"></i>Delete
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>


                                {{-- DELETE MODAL --}}
                                <div class="modal fade" id="deleteModal-{{ $contact->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel-{{ $contact->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header border-0">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body p-md-5">
                                                <div class="text-center">
                                                    <div class="text-danger">
                                                        <i class="fa-solid fa-trash fs-1"></i>
                                                    </div>
                                                    <div class="mt-4">
                                                        <h4 class="mb-1">Are you sure?</h4>
                                                        <p class="text-muted mx-3 fs-16 mb-0">
                                                            Do you really want to delete <strong>{{ $contact->f_name }}
                                                                {{ $contact->l_name }}</strong>?<br>
                                                            This action cannot be undone.
                                                        </p>
                                                        <p class="text-muted mt-2 mb-0">
                                                            <small>Consider archiving instead if you're unsure.</small>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="d-flex gap-3 justify-content-center mt-4 mb-2">
                                                    <button type="button" class="btn btn-light btn-hover w-sm"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('contacts.destroy', $contact->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-danger btn-hover w-sm">Yes, Delete
                                                            It!</button>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                   

                                <!-- View Detail Modal -->
                                <div class="modal fade" id="viewModal-{{ $contact->id }}" tabindex="-1"
                                    aria-labelledby="viewModalLabel-{{ $contact->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                            <!-- Header with Bootstrap gradient background -->
                                            <div class="modal-header border-0 p-4 text-white bg-primary">
                                                <div class="d-flex align-items-center">
                                                    <div class="  bg-opacity-25 rounded-circle p-2 me-3">
                                                        <i class="fas fa-user-circle fs-4 text-white"></i>
                                                    </div>
                                                    <h5 class="modal-title fw-bold mb-0"
                                                        id="viewModalLabel-{{ $contact->id }}">
                                                        Contact Information
                                                    </h5>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <!-- Improved Body with Bootstrap-only styling -->
                                            <div class="modal-body p-4">
                                                <!-- Top Section - Primary Info -->
                                                <div class="row g-3 mb-4">
                                                    <div class="col-md-12">
                                                        <div class="card border-0 bg-light rounded-4 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="row align-items-center">
                                                                    <div class="col-auto">
                                                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-flex align-items-center justify-content-center"
                                                                            style="width: 70px; height: 70px;">
                                                                            <span
                                                                                class="fw-bold fs-4 text-primary">{{ substr($contact->f_name, 0, 1) }}{{ substr($contact->l_name, 0, 1) }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <h4 class="fw-bold mb-3">
                                                                            {{ $contact->f_name }}
                                                                            {{ $contact->l_name }}</h4>
                                                                        <div class="d-flex flex-wrap gap-2">
                                                                            <span
                                                                                class="badge bg-primary text-white rounded-pill px-3 py-2">
                                                                                <i class="fas fa-envelope me-1"></i>
                                                                                {{ $contact->email }}
                                                                            </span>
                                                                            <!-- <span
                                                                                class="badge bg-success text-white rounded-pill px-3 py-2">
                                                                                <i class="fas fa-phone me-1"></i>
                                                                                {{ $contact->phone }}
                                                                            </span> -->
                                                                            @if ($contact->order_id)
                                                                                <span
                                                                                    class="badge bg-info text-white rounded-pill px-3 py-2">
                                                                                    <i
                                                                                        class="fas fa-shopping-cart me-1"></i>
                                                                                    Order #{{ $contact->order_id }}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Details Cards -->
                                                <div class="row g-3">
                                                    <!-- Subject Card -->
                                                    <!-- <div class="col-md-6">
                                                        <div class="card border-0 shadow-sm rounded-4 h-100">
                                                            <div class="card-body p-4">
                                                                <div class="d-flex">
                                                                    <div class="bg-danger bg-opacity-10 rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                                                                        style="width: 50px; height: 50px;">
                                                                        <i class="fas fa-tag text-danger"></i>
                                                                    </div>
                                                                    <div>
                                                                        <h6
                                                                            class="text-muted small text-uppercase mb-1">
                                                                            Subject</h6>
                                                                        <p class="fw-semibold mb-0">
                                                                            {{ $contact->subject }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> -->

                                                    <!-- Created Date Card -->
                                                    <div class="col-md-6">
                                                        <div class="card border-0 shadow-sm rounded-4 h-100">
                                                            <div class="card-body p-4">
                                                                <div class="d-flex">
                                                                    <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                                                                        style="width: 50px; height: 50px;">
                                                                        <i
                                                                            class="fas fa-calendar-alt text-warning"></i>
                                                                    </div>
                                                                    <div>
                                                                        <h6
                                                                            class="text-muted small text-uppercase mb-1">
                                                                            Received On</h6>
                                                                        <p class="fw-semibold mb-0">
                                                                            {{ $contact->created_at->format('d M Y') }}
                                                                            <span
                                                                                class="text-muted ms-2 small">{{ $contact->created_at->format('h:i A') }}</span>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Message Content -->
                                                <div class="mt-4">
                                                    <div class="card border-0 shadow-sm rounded-4 bg-light">
                                                        <div class="card-body p-4">
                                                            <h6 class="text-uppercase text-muted small mb-3"><i
                                                                    class="fas fa-comment me-2"></i>Message</h6>
                                                            <div class="  rounded-3 shadow-sm p-3">
                                                                <p class="mb-0">
                                                                    {{ $contact->message ?? 'No message content available.' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Attachment Section -->
                                                @if ($contact->file_path)
                                                    <div class="mt-4">
                                                        <div
                                                            class="card border-0 shadow-sm rounded-4 border-start border-primary border-5">
                                                            <div class="card-body p-4">
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="me-3 fs-3 text-primary">
                                                                            <i class="fas fa-paperclip"></i>
                                                                        </div>
                                                                        <div>
                                                                            <h6
                                                                                class="text-uppercase text-muted small mb-1">
                                                                                Attachment</h6>
                                                                            <p class="mb-0 fw-semibold">
                                                                                {{ basename($contact->file_path) }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <a href="{{ asset('storage/' . $contact->file_path) }}"
                                                                        download
                                                                        class="btn btn-primary rounded-pill px-4">
                                                                        <i class="fas fa-download me-2"></i> Download
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Footer with Styled Buttons -->
                                                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary rounded-pill px-4"
                                                        data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-2"></i> Close
                                                    </button>
                                                    <div>
                                                        @if (in_array('Contact Reply', Session::get('permissions') ?? []))
                                                            <button type="button"
                                                                class="btn btn-primary rounded-pill px-4">
                                                                <i class="fas fa-reply me-2"></i> Reply
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                </div>
            </div>
            @endforeach
            </tbody>
            <tfoot class="">
                <tr>
                    <th rowspan="1" colspan="1"></th>
                    <th rowspan="1" colspan="1">Name</th>
                    <th rowspan="1" colspan="1">Email</th>
                    <!-- <th rowspan="1" colspan="1">Order ID</th>
                    <th rowspan="1" colspan="1">Download File</th>
                    <th rowspan="1" colspan="1">Subject</th> -->
                    <th rowspan="1" colspan="1">Created At</th>
                    <!-- <th rowspan="1" colspan="1">Phone</th> -->
                    <th rowspan="1" colspan="1">Actions</th>
                </tr>
            </tfoot>
            </table>

            

        </div>
    </div>
</div>
<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-md-5">
                <div class="text-center">
                    <div class="text-danger">
                        <i class="fa-solid fa-trash fs-1"></i>
                    </div>
                    <div class="mt-4">
                        <h4 class="mb-1">Are you sure?</h4>
                        <p class="text-muted mx-3 fs-16 mb-0">
                            Do you really want to delete the selected contacts? This action cannot be undone.
                        </p>
                        <p class="text-muted mt-2 mb-0">
                            <small>Consider archiving instead if you're unsure.</small>
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-3 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn btn-light btn-hover w-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmBulkDeleteBtn" class="btn btn-danger btn-hover w-sm">Yes, Delete It!</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   // Select or deselect all checkboxes
$('#selectAll').click(function() {
    $('.row-checkbox').prop('checked', this.checked);
    toggleBulkDeleteBtn();
});

// Enable or disable the bulk delete button based on selected checkboxes
$('.row-checkbox').change(function() {
    toggleBulkDeleteBtn();
});

// Toggle the Bulk Delete button based on selected checkboxes
function toggleBulkDeleteBtn() {
    const anyChecked = $('.row-checkbox:checked').length > 0;
    $('#bulkDeleteBtn').prop('disabled', !anyChecked);
}

// Show Bulk Delete Confirmation Modal
$('#bulkDeleteBtn').click(function() {
    const selectedIds = $('.row-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selectedIds.length > 0) {
        // Set the selected IDs in the modal's data attribute
        $('#bulkDeleteModal').modal('show');
        $('#confirmBulkDeleteBtn').data('selected-ids', selectedIds);
    }
});

// Handle the confirmation of bulk delete
$('#confirmBulkDeleteBtn').click(function() {
    const selectedIds = $(this).data('selected-ids');

    if (selectedIds && selectedIds.length > 0) {
        $.ajax({
            type: "POST",
            url: "{{ route('contacts.bulk-delete') }}",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            data: {
                _token: '{{ csrf_token() }}',
                ids: selectedIds
            },
            // success: function(response) {
            //     $('#bulkDeleteModal').modal('hide'); 
            //     location.reload(); 
            // },
            success: function(response) {
                $('#bulkDeleteModal').modal('hide'); // Close modal

                // Show success alert manually
                const alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                // Append to a container (adjust selector as needed)
                $('#alertContainer').html(alertHtml); // Add a div with this ID somewhere in your layout

                // Optionally: remove deleted rows without reloading
                selectedIds.forEach(id => {
                    $('#row-' + id).remove(); // Make sure each row has id="row-123", etc.
                });

                // Or reload after a short delay
                setTimeout(() => location.reload(), 1500);
            },

            error: function(error) {
                console.log(error);
                alert('There was an error while deleting the selected contacts.');
            }
        });
    }
});

</script>
<script>
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

    document.addEventListener('DOMContentLoaded', function() {
        // Get all modals (both product and contact modals)
        const allModals = document.querySelectorAll('.modal');

        // For each modal
        allModals.forEach(function(modal) {
            // When the modal is about to be shown
            modal.addEventListener('show.bs.modal', function(event) {
                // Store the current scroll position
                modal.setAttribute('data-scroll', window.scrollY);
            });

            // When the modal has been shown
            modal.addEventListener('shown.bs.modal', function(event) {
                // Restore the scroll position
                window.scrollTo(0, parseInt(modal.getAttribute('data-scroll')));
            });
        });
    });
</script>
