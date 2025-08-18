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
                        <h4 class="fs-4 mb-1">Child Categories</h4>
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
                                    Child Category
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
            <div class="col-xxl-5">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Create Child Categories</h6>
                    </div>
                    <div class="card-body">
                        <form autocomplete="off" method="POST" action="{{ route('child.category.strore') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="child_category_id" id="child_category_id">
                            <div id="update_success_message"></div>
                            <div id="saveform_errList" role="alert"></div>

                            <div class="row">
                                <!-- Parent Category Dropdown -->
                                <div class="col-xxl-12 col-lg-6">
                                    <div class="mb-3">
                                        <label for="main_parent_category" class="form-label">Parent Category <span
                                                class="text-danger">*</span></label>
                                        <select name="main_parent_category" id="main_parent_category"
                                            class="form-select" required>
                                            <option value="">Select Parent Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" title="{{ $category->title }}">
                                                    {{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Please select a parent category.</div>
                                    </div>
                                </div>

                                <!-- Child Title -->
                                <div class="col-xxl-12 col-lg-6">
                                    <div class="mb-3">
                                        <label for="child_title" class="form-label">Child Category Title<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="child_title" name="child_title" required
                                            class="form-control" placeholder="Enter category">
                                        <div class="invalid-feedback">Please enter a child category title.</div>
                                    </div>
                                </div>

                                <!-- Child SKU -->
                                <div class="col-xxl-12 col-lg-6">
                                    <div class="mb-3">
                                        <label for="child_sku" class="form-label">Child Category SKU <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="child_sku" name="child_sku" required
                                            class="form-control" placeholder="child_sku" readonly>
                                    </div>
                                </div>

                                <div class="card-header">
                                    <h6 class="card-title mb-0">SEO Information <small
                                            class="text-muted">(Optional)</small></h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">SEO Title</label>
                                        <input type="text" name="_seo_title" id="_seo_title" class="form-control"
                                            value="{{ old('_seo_title') }}" placeholder="SEO Title...">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">SEO Tags</label>
                                        <input type="text" name="_seo_tags" id="_seo_tags" class="form-control"
                                            value="{{ old('_seo_tags') }}" placeholder="SEO Tags...">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">SEO Description</label>
                                        <textarea name="_seo_description" id="_seo_description" class="form-control" rows="3"
                                            placeholder="SEO Description...">{{ old('_seo_description') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Canonical URL</label>
                                        <input type="url" name="_canonical_url" id="_canonical_url"
                                            class="form-control" value="{{ old('_canonical_url') }}"
                                            placeholder="https://example.com/page-url">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Schema Markup (JSON-LD)</label>
                                        <textarea name="_schema_markup" id="_schema_markup" class="form-control" rows="6"
                                            placeholder='{
                                        "@context": "https://schema.org",
                                        "@type": "WebPage",
                                        "name": "Page Title",
                                        "url": "https://example.com/page-url"
                                        }'>{{ old('_schema_markup') ?? '' }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Child Category Page Index</label>
                                        <div class="form-check form-switch">
                                            <!-- Hidden input comes BEFORE checkbox to be overridden when checked -->
                                            <input type="hidden" name="_page_index" value="0">
                                            <input class="form-check-input" type="checkbox" id="pageIndexToggle"
                                                name="_page_index" value="1"
                                                {{ old('_page_index') == '1' ? 'checked' : '' }}>
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label">All Child Category Products Page Index</label>
                                        <select class="form-select" name="_all_product_index" id="pageIndexSelect">
                                            <option value="3"
                                                {{ old('_all_product_index', '3') == '3' ? 'selected' : '' }}>
                                                No Change (Keep Current Settings)
                                            </option>
                                            <option value="1"
                                                {{ old('_all_product_index') == '1' ? 'selected' : '' }}>
                                                Index All Products (Allow Search Engines)
                                            </option>
                                            <option value="0"
                                                {{ old('_all_product_index') == '0' ? 'selected' : '' }}>
                                                No-Index All Products (Block Search Engines)
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">Child Category Content</h6>
                                            <small class="text-muted">Add detailed content about the child category
                                                including images, formatting,
                                                and media.</small>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Child Category Description</label>
                                                <textarea name="_description" id="_description" class="form-control rich-editor" rows="10">{{ old('_description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <!-- Submit -->
                                <div class="col-xxl-12 text-end mt-5">
                                    <button type="submit" class="btn btn-success">Add Child Category</button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
                <!-- end card -->
            </div>
            <div class="col-xxl-7">
                <form method="GET" action="{{ route('child.category.index') }}" class="row g-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <!-- Search Input -->
                                <div class="col-md-3">
                                    <label for="searchKeyword" class="form-label">Search Child Category</label>
                                    <div class="position-relative">
                                        <input type="text" id="searchKeyword" name="keyword" class="form-control"
                                            placeholder="Enter category name..." value="{{ request('keyword') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="parent_category_filter" class="form-label">Parent Category</label>
                                    <select name="parent_category_id" class="form-select"
                                        id="parent_category_filter">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ request('parent_category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->title }}
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
                                        <option value="7days" {{ request('status') == '7days' ? 'selected' : '' }}>
                                            Last
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
                                        <a href="{{ route('child.category.index') }}"
                                            class="btn btn-light border w-100">
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
                                        <th>Childcategory</th>
                                        <th>Category</th>
                                        <th>Actions</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($childCategories as $childCategory)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $childCategory->child_title ?? '' }}</td>
                                            <td>{{ $childCategory->parent_title ?? '' }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm text-dark rounded shadow-sm"
                                                        type="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false" style="background-color: #c2d6e7;">
                                                        &#8942; {{-- vertical three-dot icon --}}
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item edit-category-btn"
                                                                data-id="{{ $childCategory->id }}"> <i
                                                                    class="fa-solid fa-pen-to-square me-1"></i>
                                                                Edit</a>
                                                        </li>
                                                        <li><button class="dropdown-item text-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteConfirmModal{{ $childCategory->id }}"><i
                                                                    class="fa-solid fa-trash me-1"></i> Delete</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade zoomIn"
                                            id="deleteConfirmModal{{ $childCategory->id }}" tabindex="-1"
                                            aria-labelledby="deleteConfirmModalLabel{{ $childCategory->id }}"
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
                                                                    <strong>{{ $childCategory->child_title }}</strong>?<br>
                                                                    This action cannot be undone.
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                            <button type="button"
                                                                class="btn w-sm btn-light btn-hover"
                                                                data-bs-dismiss="modal">Cancel</button>

                                                            <form
                                                                action="{{ route('child.category.delete', ['child_category' => $childCategory->id]) }}"
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
                                        <th>Childcategory</th>
                                        <th>Category</th>
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
        const titleInput = document.getElementById("child_title");
        const skuInput = document.getElementById("child_sku");

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

    document.addEventListener('DOMContentLoaded', function() {
        $('.edit-category-btn').on('click', function(e) {
            e.preventDefault();

            const categoryId = $(this).data('id');

            $.ajax({
                url: '{{ route('child.category.edit') }}',
                method: 'GET',
                data: {
                    id: categoryId
                },
                success: function(res) {
                    if (res && res.id) {
                        $('#child_category_id').val(res.id);
                        $('#child_title').val(res.child_title);
                        $('#child_sku').val(res.child_sku);
                        $('#main_parent_category').val(res.main_parent_category);

                        let metas = {};
                        if (res.child_category_meta && Array.isArray(res
                                .child_category_meta)) {
                            res.child_category_meta.forEach(meta => {
                                metas[meta.option] = meta.value;
                            });
                        }

                        console.log(metas);

                        // Set SEO fields using metas object
                        $('#_seo_title').val(metas._seo_title || '');
                        $('#_seo_tags').val(metas._seo_tags || '');
                        $('#_seo_description').val(metas._seo_description || '');
                        $('#_canonical_url').val(metas._canonical_url || '');
                        $('#_schema_markup').val(metas._schema_markup || '');


                        if (metas._page_index == '1') {
                            $('#pageIndexToggle').prop('checked', true);
                            $('#toggleStatus').text('1');
                        } else {
                            $('#pageIndexToggle').prop('checked', false);
                            $('#toggleStatus').text('0');
                        }
                       $('#pageIndexSelect').val(metas._all_product_index || '3');
                            updateProductIndexStatus(metas._all_product_index ||
                            '3'); // Call the function to update the status message

                            // Function to update the product index status
                            function updateProductIndexStatus(selectedValue) {
                                let statusMessage = '';

                                if (selectedValue == '1') {
                                    statusMessage =
                                        'All products are indexed and can be searched by engines.';
                                } else if (selectedValue == '0') {
                                    statusMessage =
                                        'All products are not indexed and will be blocked by search engines.';
                                } else {
                                    statusMessage =
                                        'Current product index settings will remain unchanged.';
                                }

                                // Set the text in the #productStatusMessage
                                $('#productStatusMessage').text(statusMessage);
                            }


                        // Prepare editor image HTML if available
                        let editorImageHtml = '';
                        if (metas.editor_image) {
                            editorImageHtml =
                                `<p><img src="/${metas.editor_image}" alt="Editor Image" style="max-width: 100%; height: auto; margin-bottom: 15px;"></p>`;
                        }

                        // Combine editor image and category description content
                        let combinedContent = metas._description || '';
                        let summernoteContent = editorImageHtml + combinedContent;

                        // Set Summernote content
                        $('#_description').summernote('code',
                            summernoteContent);



                        // Change form action to update route
                        $('form[method="POST"]').attr('action',
                            '{{ route('child.category.strore') }}');
                        $('.btn-success').text('Update Child Category');
                    }
                },
                error: function() {
                    alert('Failed to load child category.');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.rich-editor').summernote({
            height: 300, // Set editor height
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
<script>
    $(document).ready(function() {
        if ($.fn.dataTable.isDataTable('#data_Table')) {
            $('#data_Table').DataTable().destroy();
        }

        $('#data_Table').DataTable({
            pageLength: 25,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ]
        });
    });

</script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('pageIndexSelect');

            // Optional: Add visual feedback when selection changes
            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                console.log('Page Index changed to:', this.value, '-', selectedOption.text);

                // Optional: Show confirmation for bulk operations
                if (this.value !== '3') {
                    const action = this.value === '1' ? 'index' : 'no-index';
                    // You can add a subtle visual indicator here if needed
                    console.log(`Will ${action} all products in this category`);
                }
            });
        });
    </script>

