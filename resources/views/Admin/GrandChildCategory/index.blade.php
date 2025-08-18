@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\BrandClass;
    $logged_in_user = Auth::user();
@endphp
<style>
    /* Add these styles to your CSS file */
    select.form-select {
        max-width: 100%;
        text-overflow: ellipsis;
    }
    
    select.form-select option {
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* If using Bootstrap 5, you can add this to override default select styling */
    .form-select {
        width: 100% !important;
    }
    </style>

<div class="page-content margin-left">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fs-4 mb-1 text-dark link-primary">Grand Child Categories</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('products.index') }}" class="link-primary text-decoration-none">
                                        Products
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Grand Child Category
                                </li>
                            </ol>
                        </nav>
                    </div>
                    
                </div>
            </div>
        </div>
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
        <!-- end page title -->

        <div class="row">
            <div class="col-xxl-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Create Grand Child Categories</h6>
                    </div>
                    <div class="card-body">
                        <form autocomplete="off" method="POST" action="{{ route('grandchild.category.store') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="grand_child_category_id" id="grand_child_category_id">
                            <div id="update_success_message"></div>
                            <div id="saveform_errList" role="alert"></div>
                        
                            <div class="row">
                                <!-- Parent Category Dropdown -->
                                <div class="col-xxl-12 col-lg-6">
                                    <div class="mb-3">
                                        <label for="mid_parent_category" class="form-label">Child Category <span class="text-danger">*</span></label>
                                        <select name="mid_parent_category" id="mid_parent_category" class="form-select" required>
                                            <option value="">Select Child Category</option>
                                            @foreach ($childCategories as $category)
                                                <option value="{{ $category->id }}" title="{{ $category->child_title }}">{{ $category->child_title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Please select a parent category.</div>
                                    </div>
                                </div>
                        
                                <!-- Child Title -->
                                <div class="col-xxl-12 col-lg-6">
                                    <div class="mb-3">
                                        <label for="grand_title" class="form-label">Grand Child Category Title<span class="text-danger">*</span></label>
                                        <input type="text" id="grand_title" name="grand_title" required class="form-control" placeholder="Enter category">
                                        <div class="invalid-feedback">Please enter a grand child category title.</div>
                                    </div>
                                </div>
                        
                                <!-- Child SKU -->
                                <div class="col-xxl-12 col-lg-6">
                                    <div class="mb-3">
                                        <label for="grand_sku" class="form-label">Grand SKU <span class="text-danger">*</span></label>
                                        <input type="text" id="grand_sku" name="grand_sku" required class="form-control" placeholder="grand_sku" readonly>
                                    </div>
                                </div>
                        
                                <!-- Submit -->
                                <div class="col-xxl-12 text-end mt-5">
                                    <button type="submit" class="btn btn-success">Add  Category</button>
                                </div>
                            </div>
                        </form>
                        

                    </div>
                </div>
                <!-- end card -->
            </div>
            <div class="col-xxl-9">
                <form method="GET" action="{{ route('grandchild.category.index') }}" class="row g-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <!-- Search Input -->
                                <div class="col-md-3">
                                    <label for="searchKeyword" class="form-label">Search Grand Child Category</label>
                                    <div class="position-relative">
                                        <input type="text" id="searchKeyword" name="keyword" class="form-control"
                                            placeholder="Enter category name..." value="{{ request('keyword') }}">
                                        <i
                                            class="ri-search-line position-absolute top-50 end-0 translate-middle-y me-3"></i>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="parent_category_filter" class="form-label">Parent Category</label>
                                    <select name="parent_category_id" class="form-select" id="parent_category_filter">
                                        <option value="">All Categories</option>
                                        @foreach ($childCategories as $cat)
                                            <option value="{{ $cat->id }}" {{ request('parent_category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->child_title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Status Filter -->
                                <div class="col-md-2">
                                    <label for="idStatus" class="form-label">Filter by Date</label>
                                    <select name="status" class="form-select" id="idStatus">
                                        <option value="">All Time</option>
                                        <option value="today" {{ request('status') == 'today' ? 'selected' : '' }}>
                                            Today</option>
                                        <option value="yesterday"
                                            {{ request('status') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                        <option value="7days" {{ request('status') == '7days' ? 'selected' : '' }}>Last
                                            7 Days</option>
                                        <option value="30days" {{ request('status') == '30days' ? 'selected' : '' }}>
                                            Last 30 Days</option>
                                        <option value="this_month"
                                            {{ request('status') == 'this_month' ? 'selected' : '' }}>This Month
                                        </option>
                                        <option value="last_month"
                                            {{ request('status') == 'last_month' ? 'selected' : '' }}>Last Month
                                        </option>
                                    </select>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-md-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fa-solid fa-magnifying-glass me-1"></i>Search
                                        </button>
                                        <a href="{{ route('child.category.index') }}" class="btn btn-light border w-100">
                                            <i class="fa-solid fa-circle-xmark me-1"></i>Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Child Category Table</h5>
                    </div>
                    <div class="card-body  ">
                        <div class="table-responsive">
                            <table id="data_Table" class="table table-borderless  align-middle">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Grand Category </th>
                                        <th>Parent Category</th>
                                        <th>Actions</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($categories as $grandCategory)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $grandCategory->grand_title ?? '' }}</td>
                                            <td>{{ $grandCategory->parent_child_title ?? '' }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm text-dark rounded shadow-sm"
                                                        type="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false" style="background-color: #c2d6e7;">
                                                        &#8942; {{-- vertical three-dot icon --}}
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item edit-category-btn" data-id="{{ $grandCategory->id }}"
                                                                > <i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                                        </li>
                                                        <li><button class="dropdown-item text-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteConfirmModal{{ $grandCategory->id }}"> <i class="fa-solid fa-trash" ></i> Delete</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade zoomIn"
                                            id="deleteConfirmModal{{ $grandCategory->id }}" tabindex="-1"
                                            aria-labelledby="deleteConfirmModalLabel{{ $grandCategory->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                                    <strong>{{ $grandCategory->grand_title }}</strong>?<br>
                                                                    This action cannot be undone.
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                            <button type="button"
                                                                class="btn w-sm btn-light btn-hover"
                                                                data-bs-dismiss="modal">Cancel</button>

                                                            <form
                                                                action="{{ route('grandchild.category.delete', ['grand_child_category' => $grandCategory->id]) }}"
                                                                method="POST">
                                                                @csrf
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
                                        <th>Grand Category</th>
                                        <th>Parent Category</th>
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
    <!-- container-fluid -->
</div>
@include('Admin.components.offcanvas') <!-- If extracted to a file -->



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const titleInput = document.getElementById("grand_title");
        const skuInput = document.getElementById("grand_sku");

        titleInput.addEventListener("input", function() {
            const rawValue = titleInput.value;

            // Convert to lowercase, remove non-alphanumeric (except space), replace spaces & special chars with dashes
            const slugified = rawValue
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s]/g, '') // Remove special characters
                .replace(/\s+/g, '-'); // Replace spaces with dashes

            skuInput.value = slugified;
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Auto-generate SKU
        const titleInput = document.getElementById('category_title');
        const skuInput = document.getElementById('category_sku');

        titleInput.addEventListener('input', function() {
            let text = titleInput.value.trim().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(
                /^-+|-+$/g, '');
            skuInput.value = text;
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

    document.addEventListener('DOMContentLoaded', function () {
    $('.edit-category-btn').on('click', function (e) {
        e.preventDefault();

        const categoryId = $(this).data('id');

        $.ajax({
            url: '{{ route("grandchild.category.edit") }}',
            method: 'GET',
            data: { id: categoryId },
            success: function (res) {
                // If response is an array, get the first item
                if (Array.isArray(res) && res.length > 0) {
                    const data = res[0];

                    $('#grand_child_category_id').val(data.id);
                    $('#grand_title').val(data.grand_title);
                    $('#grand_sku').val(data.grand_sku);
                    $('#mid_parent_category').val(data.mid_parent_category);

                    // Update form action
                    $('form[method="POST"]').attr('action', '{{ route("grandchild.category.store") }}');
                    $('.btn-success').text('Update Child Category');
                }
            },
            error: function () {
                alert('Failed to load child category.');
            }
        });
    });
});


  
</script>
