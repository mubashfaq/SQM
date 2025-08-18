@include('Admin.Includes.header')
<div class="page-content">
    <div class="container-fluid py-4">
        <!-- Breadcrumb Start -->
        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">{{ __('Products Types') }}</h4>
                
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-house me-1 "></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a class="link-primary" href="{{ route('products.index') }}">
                                        Products
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Products Types
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>
        <!-- Breadcrumb End -->

        <!-- Alert Messages Section -->
        <div class="response_section mb-3"></div>
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
        <div class="row">
            @if (Session::has('permissions') && in_array('Product Type Add', Session::get('permissions')))
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header">
                            <h5 class="card-title mb-0 fw-bold" id="formTitle">{{ __('Add Product Type') }}</h5>
                        </div>
                        <div class="card-body">
                            <form id="product-type-form" action="{{ route('product-type.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="update_id" id="update_id" value="">
                                <div class="form-group mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input class="form-control form-control-lg restrict-input-alphabets_space addTitle"
                                        type="text" pattern="[A-Za-z_]+" name="title"
                                        placeholder="Enter product type title" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label">SKU <span class="text-danger">*</span></label>
                                    <input class="form-control form-control-lg restrict-input-alphabets addSku"
                                        type="text" pattern="[A-Za-z_]+" name="sku"
                                        placeholder="Enter product SKU" required>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-success btn-lg" id="submitBtn" type="button" onclick="submit_product_form()">
                                        <i class="fas fa-plus-circle me-2" id="btnIcon"></i><span id="btnText">Add Product Type</span>
                                    </button>
                                </div>
                                <div class="d-grid mt-2" id="cancelBtnContainer" style="display: none;">
                                    <button class="btn btn-secondary btn-lg" type="button" onclick="resetForm()">
                                        <i class="fas fa-times-circle me-2"></i>Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <div
                class="col-md-{{ Session::has('permissions') && in_array('Product Type Add', Session::get('permissions')) ? '8' : '12' }}">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header  ">
                        <h5 class="card-title mb-0 fw-bold">{{ __('Products Types') }}</h5>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="data_Table" class="table table-borderless align-middle">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>SKU</th>
                                            <th class="action">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody class="product_type_rows">
                                        @if (sizeof($products) > 0)
                                            @foreach ($products as $producttype)
                                                <tr class="product-row">
                                                    <td>{{ $producttype->title }}</td>
                                                    <td>{{ $producttype->sku }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm text-dark rounded shadow-sm"
                                                                type="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false"
                                                                style="background-color: #c2d6e7;">
                                                                &#8942;
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                @if (Session::has('permissions') && in_array('Product Type Edit', Session::get('permissions')))
                                                                    <li>
                                                                        <a class="dropdown-item edit-category-btn cursor-pointer"
                                                                            product-id="{{ $producttype->id }}"
                                                                            onclick="editProductType(this)">
                                                                            <i class="fas fa-edit me-1"></i>Edit
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                <li><button class="dropdown-item text-danger"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#deleteConfirmModal{{ $producttype->id }}">
                                                                        <i class="fas fa-trash me-1"></i>Delete
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                    
                                                <!-- Delete Modal -->
                                                <div class="modal fade zoomIn"
                                                    id="deleteConfirmModal{{ $producttype->id }}" tabindex="-1"
                                                    aria-labelledby="deleteConfirmModalLabel{{ $producttype->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>

                                                            <!-- Modal Body -->
                                                            <div class="modal-body p-md-5">
                                                                <div class="text-center">
                                                                    <div class="text-danger">
                                                                        <i class="bi bi-trash display-4"></i>
                                                                    </div>
                                                                    <div class="mt-4 fs-15">
                                                                        <h4 class="mb-1">Are you sure?</h4>
                                                                        <p class="text-muted mx-3 fs-16 mb-0">
                                                                            Are you sure you want to delete
                                                                            <strong>{{ $producttype->title }}</strong>?<br>
                                                                            This action cannot be undone.
                                                                        </p>
                                                                        
                                                                    </div>
                                                                </div>

                                                                <!-- Modal Footer -->
                                                                <div
                                                                    class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                                    <button type="button"
                                                                        class="btn w-sm btn-light btn-hover"
                                                                        data-bs-dismiss="modal">Cancel</button>

                                                                    <!-- Form to delete product -->
                                                                    <form
                                                                        action="{{ route('product-type.destroy', $producttype->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn w-sm btn-danger btn-hover">Yes,
                                                                            Delete It!</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <tr class="product-null">
                                                <td colspan="3" class="text-center py-4">No Product Types Found!
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th>Title</th>
                                            <th>SKU</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Font Awesome -->
<style>
    @media (min-width:775px){
        .action{
            width: 180px;
        }
    }
</style>
<script>
   // Auto-generate SKU from title
$('.addTitle').on('input', function() {
    var title = $(this).val();
    var sku = title.replace(/\s+/g, '-').toLowerCase();
    $('.addSku').val(sku);
});

// Function to handle both add and update operations
function submit_product_form() {
    var form = $('#product-type-form');
    var url = form.attr('action');
    var method = form.attr('method');
    var data = form.serialize();
    var isUpdate = $('#update_id').val() !== '';

    $.ajax({
        url: url,
        method: method,
        data: data,
        dataType: 'json',
        success: function(response) {
            $('.response_section').html(
                '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                response.message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>');

                setTimeout(function() {
        window.location.reload();
    }, 1000); // 1000ms = 1 second

            if (isUpdate) {
                // For update: Remove the existing row and prepend the updated row at the top
                var updateId = $('#update_id').val();
                $('.product-row').each(function() {
                    if ($(this).find('[product-id="' + updateId + '"]').length > 0) {
                        $(this).remove();
                    }
                });
                
                // Prepend the updated row at the top of the table
                $('.product_type_rows').prepend(response.updated_row);
                
                // Reset form to add mode
                resetForm();
            } else {
                // Reset form inputs
                $('input[name=title], input[name=sku]').val('');
                
                // Add the new row at the top of the table
                $('.product_type_rows').prepend(response.new_category);

                // Remove no products message if exists
                if ($('.product-null').length) {
                    $('.product-null').remove();
                }
            }

            // Initialize any event handlers for the new/updated row
            initializeRowEventHandlers();

            // Scroll to the top message
            $('html, body').animate({
                scrollTop: $(".response_section").offset().top - 100
            }, 500);
        },
        error: function(xhr) {
            var errors = xhr.responseJSON.errors;
            var errorMessage = '<ul class="mb-0">';

            if (errors) {
                $.each(errors, function(key, value) {
                    errorMessage += '<li>' + value[0] + '</li>';
                });
            } else {
                errorMessage = '<li>Server Error, Try Again Later!</li>';
            }
            errorMessage = errorMessage + '</ul>';
            $('.response_section').html(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                errorMessage +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>');

            // Scroll to the top message
            $('html, body').animate({
                scrollTop: $(".response_section").offset().top - 100
            }, 500);
        }
    });
}

// Function to edit product type - populate form with existing data
function editProductType(elementObj) {
    var product_id = $(elementObj).attr('product-id');
    
    $.ajax({
        type: "GET",
        url: "{{ url('/admin/product-type/') }}/" + product_id,
        data: {
            '_token': '{{ csrf_token() }}'
        },
        success: function(response) {
            // Populate form with product type data
            $('#update_id').val(product_id);
            $('.addTitle').val(response.product_details.title);
            $('.addSku').val(response.product_details.sku);
            
            // Update UI to show we're in edit mode
            $('#formTitle').text('Edit Product Type');
            $('#btnText').text('Update Product Type');
            $('#btnIcon').removeClass('fa-plus-circle').addClass('fa-save');
            $('#cancelBtnContainer').show();
            
            // Scroll to form
            $('html, body').animate({
                scrollTop: $("#product-type-form").offset().top - 100
            }, 500);
        },
        error: function(xhr, status, error) {
            console.log(error);
            $('.response_section').html(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                'Failed to load product data. Please try again.' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>');

            // Scroll to the top message
            $('html, body').animate({
                scrollTop: $(".response_section").offset().top - 100
            }, 500);
        }
    });
}

// Reset form to add mode
function resetForm() {
    $('#update_id').val('');
    $('.addTitle, .addSku').val('');
    $('#formTitle').text('Add Product Type');
    $('#btnText').text('Add Product Type');
    $('#btnIcon').removeClass('fa-save').addClass('fa-plus-circle');
    $('#cancelBtnContainer').hide();
}

// Function to initialize event handlers for newly added/updated rows
function initializeRowEventHandlers() {
    // Re-attach click event to edit buttons
    $('.edit-category-btn').off('click').on('click', function() {
        editProductType(this);
    });
    
    // Re-initialize any other event handlers for the rows if needed
}

// Input restriction functions
function restrict_input_alphabets_space(elementObj) {
    var username = $(elementObj).val();
    var pattern = /^[A-Za-z0-9- ]+$/;
    if (!pattern.test(username)) {
        $(elementObj).val(username.replace(/[^A-Za-z0-9- ]+/g, ''));
    }
}

function restrict_input_alphabets(elementObj) {
    var username = $(elementObj).val();
    var pattern = /^[A-Za-z0-9-]+$/;
    if (!pattern.test(username)) {
        $(elementObj).val(username.replace(/[^A-Za-z0-9-]+/g, ''));
    }
}

// Attach input restriction handlers
$(document).ready(function() {
    // Apply input restrictions
    $(document).on('input', '.restrict-input-alphabets_space', function() {
        restrict_input_alphabets_space(this);
    });
    
    $(document).on('input', '.restrict-input-alphabets', function() {
        restrict_input_alphabets(this);
    });
    
    // Initialize event handlers for existing rows
    initializeRowEventHandlers();
    
    // Auto-dismiss alerts after 5 seconds
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
});
</script>