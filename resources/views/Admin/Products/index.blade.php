@include('Admin.Includes.header')

@php
    use Illuminate\Support\Facades\Auth;
    use App\General\ProductClass;
    $logged_in_user = Auth::user();
@endphp
<style>



    .header-title h1 {
        color: #212529;
        font-size: 1.5rem;
    }

    /* Card Styles */
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

    /* Table Styles */
    #product_table {
        font-size: 0.875rem;
    }

    #product_table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    #product_table tbody tr {
        transition: background-color 0.2s ease;
    }

    #product_table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    /* Product Thumbnail */
    .product-thumbnail {
        border-radius: 0.375rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid #dee2e6;
    }

    /* Floating Labels */
    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label,
    .form-floating>.form-select~label {
        opacity: 0.65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    .form-floating>.form-control,
    .form-floating>.form-select {
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
    }

    .form-floating>.form-control:focus,
    .form-floating>.form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Badge Styling */
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    /* Button Styling */
 

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    /* Dropdown Styling */
    .dropdown-menu {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 0.375rem;
    }

    .dropdown-item {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

   
    /* Initial state: label hidden */
    .form-floating label {
        opacity: 0;
       
    }

    /* Focus hone par label dikhai de */
    .form-floating input:focus + label,
    .form-floating input:not(:placeholder-shown) + label {
        opacity: 1;
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .d-flex.justify-content-lg-end {
            justify-content: flex-start !important;
            margin-top: 1rem;
        }
    }

    .border-dashed {
        border-style: dashed !important;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .transition {
        transition: all 0.3s ease;
    }

    .drop-area:hover {
        background-color: rgba(13, 110, 253, 0.05) !important;
        transform: translateY(-2px);
    }
</style>
<div class="page-content">
    <div class="container-fluid">


        <div class="page-inner">
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
            <!--================================-->
            <div class="card shadow-sm mb-4 p-3 ">
                <div class="row">
                    <div class="col-md-4 card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fs-4 mb-1">{{ __('Product List') }}</h4>
                            
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                                <i class="fa-solid fa-house me-1"></i> Dashboard
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            Product List
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-lg-8 p-3">
                        @if (Session::has('permissions') &&
                                (in_array('Create Product', Session::get('permissions')) ||
                                    in_array('Product Trash List', Session::get('permissions')) ||
                                    in_array('Import Products', Session::get('permissions'))))
                            <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                                @if (in_array('Create Product', Session::get('permissions')))
                                    <a href="{{ url('/') }}/admin/products/create" class="btn btn-success"
                                        style="width:165px;">
                                        <i class="fa-solid fa-circle-plus me-1"></i> Create Product
                                    </a>
                                @endif

                                @if (in_array('Product Trash List', Session::get('permissions')))
                                    <a href="{{ url('/') }}/admin/products/list/trash" class="btn btn-danger"
                                        style="width:165px;">
                                        <i class="fa-solid fa-trash me-1"></i> Trash List
                                    </a>
                                @endif

                                @if (in_array('Import Products', Session::get('permissions')))
                                    <button type="button" class="btn btn-primary" style="width:165px;"
                                        data-bs-toggle="modal" data-bs-target="#ImportProduct">
                                        <i class="fa-solid fa-file-import me-1"></i> Import Products
                                    </button>
                                @endif

                                {{-- <form action="{{ route('export.product') }}" method="GET" id="export-form">
                                    <button type="submit" id="export-product" style="width:165px;"
                                        class="btn btn-outline-primary">
                                        <i class="fa-solid fa-cart-arrow-down me-1"></i>
                                        Export Product
                                        <span id="loading-spinner" class="spinner-border spinner-border-sm d-none ms-1"
                                            role="status" aria-hidden="true"></span>
                                    </button>
                                </form> --}}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
            {{-- <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="header-title mb-2">
                        <h1 class="fw-bold mb-1">{{ __('Product List') }}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}/admin"
                                        class="text-decoration-none">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Product List</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="col-lg-6">
                    @if (Session::has('permissions') &&
                            (in_array('Create Product', Session::get('permissions')) ||
                                in_array('Product Trash List', Session::get('permissions')) ||
                                in_array('Import Products', Session::get('permissions'))))
                        <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                            @if (in_array('Create Product', Session::get('permissions')))
                                <a href="{{ url('/') }}/admin/products/create" class="btn btn-success"
                                    style="width:165px;">
                                    <i class="bi bi-plus-circle me-1"></i> Create Product
                                </a>
                            @endif

                            @if (in_array('Product Trash List', Session::get('permissions')))
                                <a href="{{ url('/') }}/admin/products/list/trash" class="btn btn-danger"
                                    style="width:165px;">
                                    <i class="bi bi-trash me-1"></i> Trash List
                                </a>
                            @endif

                            @if (in_array('Import Products', Session::get('permissions')))
                                <button type="button" class="btn btn-primary" style="width:165px;"
                                    data-bs-toggle="modal" data-bs-target="#ImportProduct">
                                    <i class="bi bi-upload me-1"></i> Import Products
                                </button>
                            @endif

                            <form action="{{ route('export.product') }}" method="GET" id="export-form">
                                <button type="submit" id="export-product" style="width:165px;"
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-download me-1"></i>
                                    Export Product
                                    <span id="loading-spinner" class="spinner-border spinner-border-sm d-none ms-1"
                                        role="status" aria-hidden="true"></span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div> --}}




            <div class="card shadow-sm border-0">
                <div class="card-header   py-3">
                    <h6 class="mb-0 fw-semibold">{{ __('Product List') }}</h6>
                </div>

                <!-- Search Filters -->
                <div class="card-body border-bottom pb-3">
                    <form method="GET" action="{{ url('/admin/products/product_search') }}" class="row">
                        <div class="col-lg-2 col-md-6 mb-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="productName" name="product_name"
                                    placeholder="Search Product Name..."
                                    value="{{ session('product_search_params')['title'] ?? '' }}">
                                <label for="productName">Product Name</label>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 mb-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="category" name="category"
                                    placeholder="Search Category..." value="{{ session('product_category') ?? '' }}">
                                <label for="category">Category</label>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 mb-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="sku" name="sku"
                                    placeholder="Search SKU..."
                                    value="{{ session('product_search_params')['sku'] ?? '' }}">
                                <label for="sku">SKU</label>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 mb-2">
                            <div class="form-floating">
                                <select class="form-select" id="stockStatus" name="stock_status">
                                    <option value="">All</option>
                                    <option value="in_stock"
                                        {{ session('product_stock_status') == 'in_stock' ? 'selected' : '' }}>
                                        In Stock
                                    </option>
                                    <option value="out_stock"
                                        {{ session('product_stock_status') == 'out_stock' ? 'selected' : '' }}>
                                        Out of Stock
                                    </option>
                                </select>
                                <label for="stockStatus">Stock Status</label>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 mb-2">
                            <div class="form-floating">
                                <select class="form-select" id="productType" name="product_type">
                                    <option value="">Select Type</option>
                                    @foreach ($producttypes as $producttype)
                                        <option value="{{ $producttype->id }}"
                                            {{ (session('product_search_params')['product_type'] ?? '') == $producttype->id ? 'selected' : '' }}>
                                            {{ $producttype->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="productType">Product Type</label>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 d-flex align-items-center mb-2">
                            <button class="btn btn-primary w-100 me-2" type="submit">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Search
                            </button>
                            <a class="btn btn-light border w-100" href="{{ url('/admin/products') }}">
                                <i class="fa-solid fa-circle-xmark me-1"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Product</th>
                                    <th width="15%">Category</th>
                                    <th width="15%">SKU</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Type</th>
                                    <th width="10%">Active</th>
                                    @if (Session::has('permissions') &&
                                            (in_array('Product Edit', Session::get('permissions')) ||
                                                in_array('Product Delete', Session::get('permissions')) ||
                                                in_array('Product Change Status', Session::get('permissions'))))
                                        <th width="10%">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    {{-- @dd($product) --}}
                                    <tr class="align-middle">
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="product-thumbnail me-3 rounded"
                                                    style="width: 60px; height: 60px; background:url({{ url('/') . '/' . $product->featured_image }});background-size:cover;background-position:center">
                                                </div>
                                                <div>
                                                    {{ $product->title  ?? '' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $product->category->title ?? '' }}</td>
                                        </td>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                                            @if (ProductClass::getOptionValue('_stock_status', $product->productMeta) =="in_stock")
                                                <span
                                                    class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">In
                                                    Stock</span>
                                            @else
                                                <span
                                                    class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">Out
                                                    of Stock</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $product->productType->title ?? '' }}

                                        </td>
                                        <!-- ACTIVE Status Badge Column -->
                                        <td>
                                            @php
                                                $activeStatus = ProductClass::check_active($product->active);
                                                $badgeClass =
                                                    $activeStatus == 'Public'
                                                        ? 'bg-primary-subtle text-primary'
                                                        : ($activeStatus == 'Draft'
                                                            ? 'bg-warning-subtle text-warning'
                                                            : ($activeStatus == 'Private'
                                                                ? 'bg-info-subtle text-info'
                                                                : 'bg-secondary-subtle text-secondary'));
                                            @endphp
                                            <span id="product-status-{{ $product->id }}"
                                                class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $activeStatus }}</span>
                                        </td>

                                        <!-- ACTIONS Dropdown -->
                                        @if (Session::has('permissions') &&
                                                (in_array('Product Edit', Session::get('permissions')) ||
                                                    in_array('Product Delete', Session::get('permissions')) ||
                                                    in_array('Product Change Status', Session::get('permissions'))))
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm text-dark rounded shadow-sm"
                                                        type="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false" style="background-color: #c2d6e7;">
                                                        &#8942;
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if (in_array('Product Edit', Session::get('permissions')))
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('products.edit', $product->id) }}">
                                                                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (in_array('Product Delete', Session::get('permissions')))
                                                            <li>
                                                                <button class="dropdown-item text-danger"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteConfirmModal{{ $product->id }}">
                                                                    <i class="fa-solid fa-trash me-2"></i> Delete
                                                                </button>
                                                            </li>
                                                        @endif

                                                        @if (in_array('Product Change Status', Session::get('permissions')))
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item {{ $product->active == 0 ? 'active' : '' }}"
                                                                    href="#"
                                                                    onclick="ProductUpdateStatus({{ $product->id }}, 0, this)">
                                                                    <i class="fa-solid fa-file-pen me-2"></i> Draft
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item {{ $product->active == 1 ? 'active' : '' }}"
                                                                    href="#"
                                                                    onclick="ProductUpdateStatus({{ $product->id }}, 1, this)">
                                                                    <i class="fa-solid fa-globe me-2"></i> Public
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item {{ $product->active == 2 ? 'active' : '' }}"
                                                                    href="#"
                                                                    onclick="ProductUpdateStatus({{ $product->id }}, 2, this)">
                                                                    <i class="fa-solid fa-lock me-2"></i> Private
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item {{ $product->active == 3 ? 'active' : '' }}"
                                                                    href="#"
                                                                    onclick="ProductUpdateStatus({{ $product->id }}, 3, this)">
                                                                    <i class="fa-solid fa-trash me-2"></i> Trash
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        @endif

                                    </tr>

                                    <div class="modal fade zoomIn" id="deleteConfirmModal{{ $product->id }}"
                                        tabindex="-1" aria-labelledby="deleteConfirmModalLabel{{ $product->id }}"
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
                                                            <i class="fa-solid fa-trash fs-1"></i></i>
                                                        </div>
                                                        <div class="mt-4 fs-15">
                                                            <h4 class="mb-1">Are you sure?</h4>
                                                            <p class="text-muted mx-3 fs-16 mb-0">
                                                                Are you sure you want to delete
                                                                <strong>{{ $product->title }}</strong>?<br>
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

                                                        <form action="{{ route('products.destroy', $product->id) }}"
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
                        </table>
                    </div>
                </div>
            </div>
            @if (empty(session('product_search_params')) &&
                    empty(session('product_stock_status')) &&
                    empty(session('product_category')))
                {{ $products->links('pagination::bootstrap-5') }}
            @else
                {{ $products->appends([
                        'product_name' => session('product_search_params')['title'] ?? '',
                        'sku' => session('product_search_params')['sku'] ?? '',
                        'product_type' => session('product_search_params')['product_type'] ?? '',
                        'stock_status' => session('product_stock_status') ?? '',
                        'category' => session('product_category') ?? '',
                    ])->links('pagination::bootstrap-5') }}
            @endif


        </div>
        <!--/  Order Active dataTable End -->
        <!--================================-->
    </div>



    <div class="modal fade" id="ImportProduct" tabindex="-1" aria-labelledby="ImportProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <form id="importproducts_form"
        action="{{ route('Admin.Product.Import') }}"
        method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="modal-content border-0 shadow">
        <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="ImportProductLabel">
        <i class="fa-solid fa-cart-arrow-down me-2"></i>Import Products
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
        aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
        <div class="mb-4">
        <div class="text-center mb-3">
        <h6 class="fw-bold text-secondary">SELECT CSV FILE</h6>
        </div>

        <label for="fileInput"
        class="drop-area d-flex flex-column align-items-center justify-content-center p-5 rounded-3 border-2 border-dashed border-primary bg-light cursor-pointer mb-3 transition">
        <div class="icon mb-3">
        <i data-feather="upload" class="text-primary" width="40" height="40"></i>
        </div>
        <div class="h6 mb-1 fw-bold">Drag & Drop your file here</div>
        <p class="text-muted small">or click to browse your files</p>
        </label>
        <input type="file" accept=".csv" id="fileInput" name="file" class="d-none" required>
        <div id="fileInfo" class="text-center py-2 small fw-bold text-muted">No File Selected!</div>
        </div>

        <div class="alert alert-warning p-2 d-flex align-items-center mb-4">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        <div class="small">Please note that once imported, changes cannot be reversed.</div>
        </div>

        <div class="form-check mb-4 d-flex justify-content-center">
        <input class="form-check-input me-2" type="checkbox" id="confirm_check">
        <label class="form-check-label user-select-none" for="confirm_check">
        I confirm that I want to proceed with the import
        </label>
        </div>

        <div class="d-flex justify-content-center gap-3">
        <a href="{{ url('/') }}/sample.csv" target="_blank" class="btn btn-outline-secondary">
        <i class="fa-solid fa-download me-1"></i>Download Template
        </a>
        <button type="button" class="btn btn-primary px-4" id="importButton">
        <i class="fa-solid fa-file-import me-1"></i>Import File
        </button>
        </div>
        </div>
        </div>
        </form>
        </div>
        </div>

</div>
</div>

@include('Admin.Includes.footer')
<!-- Add this script below the modal -->
<script>
document.getElementById('importButton').addEventListener('click', function () {
const checkbox = document.getElementById('confirm_check');
const fileInput = document.getElementById('fileInput');

if (!checkbox.checked) {
alert('Please confirm that you want to proceed with the import.');
return;
}

if (!fileInput.files.length) {
alert('Please select a CSV file.');
return;
}

document.getElementById('importproducts_form').submit();
});

document.getElementById('fileInput').addEventListener('change', function () {
const fileInfo = document.getElementById('fileInfo');
fileInfo.textContent = this.files.length ? this.files[0].name : 'No File Selected!';
});

document.querySelector('label[for="fileInput"]').addEventListener('click', function () {
document.getElementById('fileInput').click();
});
</script> 
<script>
    $(document).ready(function() {
        $('#export-product').click(function() {
            // Show the loading spinner inside the button
            $('#button-text').hide(); // Hide the button text
            $('#loading-spinner').show(); // Show the loading spinner

            // Optionally show a loading message outside the button (if you want)
            $('#loading').show(); // Show the loading text outside the button

            // This is where you handle the AJAX request, if needed.
            $.ajax({
                url: '{{ route('export.product') }}',
                type: 'GET',
                success: function(response) {
                    // Hide the loading spinner after the download is triggered
                    $('#loading').hide(); // Hide the loading message
                    $('#button-text').show(); // Show the button text again
                    $('#loading-spinner').hide(); // Hide the loading spinner
                },
                error: function(xhr, status, error) {
                    $('#loading').hide(); // Hide the loading message on error
                    $('#button-text').show(); // Show the button text again
                    $('#loading-spinner').hide(); // Hide the loading spinner on error
                    alert("An error occurred while exporting the products.");
                }
            });
        });
    });


    $(document).ready(function() {
        @if (isset($_GET['page']))
            $('.pagination a[value="{{ $_GET['page'] }}"]').addClass('bg-dark');
            $('.pagination a[value="{{ $_GET['page'] }}"]').addClass('text-light');
        @endif
        $("#searchProduct").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#product_table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    function clearSession() {
        // Clear session values
        {!! session()->forget(['product_search_params', 'product_category', 'product_stock_status']) !!}
    }

    function ProductUpdateStatus(productId, status, elementObj) {
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
            url: '/admin/products/' + productId + '/update-status',
            type: 'PUT',
            data: {
                active: status,
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                // Update status badge
                const $statusBadge = $('#product-status-' + productId);
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

                // If Trash, optionally hide row
                if (status === 3) {
                    $(elementObj).closest('tr').fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            },
            error: function() {
                const $container = $('.page-content, page-inner').first();
                const alertHTML = `
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Failed to update product status.
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


    // old code for file model
    // function displayFileInfo() {
    //     const fileInput = document.getElementById("fileInput");
    //     const fileInfo = document.getElementById("fileInfo");

    //     fileInput.addEventListener("change", function() {
    //         const selectedFile = fileInput.files[0];

    //         if (selectedFile) {
    //             const fileName = selectedFile.name;
    //             const fileSize = Math.round(selectedFile.size / 1024); // Convert to KB

    //             fileInfo.innerHTML = `Selected File: ${fileName} (${fileSize} KB)`;
    //         } else {
    //             fileInfo.innerHTML = "";
    //         }
    //     });
    // }

    // // Call the function to display file information
    // displayFileInfo();

    // document.getElementById("importButton").addEventListener("click", function() {
    //     const fileInput = document.getElementById("fileInput");
    //     const selectedFile = fileInput.files[0];
    //     const confirm_check = document.getElementById("confirm_check");
    //     const confirmcheck = document.querySelector(".confirmcheck");
    //     if (confirm_check.checked) {
    //         if (selectedFile) {
    //             const reader = new FileReader();

    //             reader.onload = function(event) {
    //                 const csvContent = event.target.result;
    //                 const lines = csvContent.split("\n");
    //                 const header = lines[0].split(",");

    //                 // Check if the required columns are present in the header
    //                 const requiredColumns = ["title", "permalink", "sku", "short_description",
    //                     "long_description", "product_type", "active", "category_id", "child_category",
    //                     "grand_child_category", "brand_id", "featured_image", "gallery_media", "_stock",
    //                     "_buy_price", "_price", "_seo_title", "_seo_tags", "_seo_description", "_alt",
    //                     "_virtual", "_upc", "_weight", "_gst"
    //                 ];
    //                 const missingColumns = requiredColumns.filter(col => !header.includes(col));

    //                 if (missingColumns.length === 0) {
    //                     var formData = new FormData();
    //                     formData.append("file", $("#fileInput")[0].files[0]);
    //                     formData.append("_token", "{{ csrf_token() }}");

    //                     // Send the AJAX request
    //                     $.ajax({
    //                         url: '/admin/products/import/csv',
    //                         type: 'POST',
    //                         data: formData,
    //                         processData: false,
    //                         contentType: false,
    //                         success: function(response) {
    //                             // Handle the success response
    //                             console.log(response);
    //                             alert('CSV file uploaded successfully!');
    //                             window.location.href = window.location.href;
    //                         },
    //                         error: function(xhr, status, error) {
    //                             console.error(error);
    //                             alert('Error uploading CSV file.');
    //                         }
    //                     });
    //                 } else {
    //                     // Display an alert for missing columns
    //                     alert("The following columns are missing in the CSV file: " + missingColumns.join(
    //                         ", "));
    //                 }
    //             };

    //             reader.readAsText(selectedFile);
    //         } else {
    //             alert("Please select a CSV file before importing.");
    //         }
    //     } else {
    //         confirmcheck.style.color = "red";
    //     }

    // });
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

    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('fileInfo');
        const dropArea = document.querySelector('.drop-area');

        // Handle file selection
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileInfo.textContent = this.files[0].name;
                fileInfo.classList.remove('text-muted');
                fileInfo.classList.add('text-success');
                dropArea.classList.add('border-success');
                dropArea.classList.remove('border-primary');
            } else {
                fileInfo.textContent = 'No File Selected!';
                fileInfo.classList.add('text-muted');
                fileInfo.classList.remove('text-success');
                dropArea.classList.remove('border-success');
                dropArea.classList.add('border-primary');
            }
        });

        // Initialize Feather icons if using them
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Optional: Add drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.classList.add('border-primary', 'bg-light');
        }

        function unhighlight() {
            dropArea.classList.remove('border-primary', 'bg-light');
        }

        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;

            // Trigger change event
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    });
</script>
