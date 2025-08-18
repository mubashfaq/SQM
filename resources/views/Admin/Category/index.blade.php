@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\BrandClass;
    $logged_in_user = Auth::user();
@endphp


<div class="page-content margin-left">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fs-4 mb-1">Categories</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a class="link-primary" href="{{ route('products.index') }}">
                                        Products
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Categories
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
                <!-- Basic Category Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Create Category</h6>
                    </div>
                    <div class="card-body">
                        <form autocomplete="off" method="POST" action="{{ route('category.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="category_id" id="category_id">
                            <div id="update_success_message"></div>
                            <div id="saveform_errList" role="alert"></div>

                            <div class="row">
                                <!-- Title -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="category_title" class="form-label">Category Title<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="category_title" name="title" required
                                            class="form-control" placeholder="Enter Category">
                                        <div class="invalid-feedback">Please enter a category title.</div>
                                    </div>
                                </div>

                                <!-- SKU -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="category_sku" class="form-label">Category SKU <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="category_sku" name="sku" required
                                            class="form-control" placeholder="SKU" readonly>
                                    </div>
                                </div>

                                <!-- Thumbnail Image -->
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="thumbnail-image-input" class="form-label d-block">Thumbnail Image
                                            <span class="text-danger"></span></label>
                                        <div class="position-relative d-inline-block">
                                            <div class="position-absolute top-100 start-100 translate-middle">
                                                <label for="thumbnail-image-input" class="mb-0"
                                                    data-bs-toggle="tooltip" title="Select Thumbnail Image">
                                                    <span class="avatar-xs d-inline-block">
                                                        <span
                                                            class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                            <i class="fa-solid fa-file-image"></i>
                                                        </span>
                                                    </span>
                                                </label>
                                                <input class="form-control d-none image-preview" name="thumbnail_image"
                                                    id="thumbnail-image-input" type="file"
                                                    accept="image/png, image/jpeg" data-preview="thumbnailPreview" >
                                            </div>
                                            <div class="avatar-lg">
                                                <div class="avatar-title bg-light rounded-3">
                                                    <img src="#" alt="" id="thumbnailPreview"
                                                        class="avatar-md h-auto rounded-3 object-fit-cover">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cover Image -->
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="cover-image-input" class="form-label d-block">Cover Image <span
                                                class="text-danger"></span></label>
                                        <div class="position-relative d-inline-block">
                                            <div class="position-absolute top-100 start-100 translate-middle">
                                                <label for="cover-image-input" class="mb-0"
                                                    data-bs-toggle="tooltip" title="Select Cover Image">
                                                    <span class="avatar-xs d-inline-block">
                                                        <span
                                                            class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                            <i class="fa-solid fa-file-image"></i>
                                                        </span>
                                                    </span>
                                                </label>
                                                <input class="form-control d-none image-preview" name="cover_image"
                                                    id="cover-image-input" type="file"
                                                    accept="image/png, image/jpeg" data-preview="coverPreview" >
                                            </div>
                                            <div class="avatar-lg">
                                                <div class="avatar-title bg-light rounded-3">
                                                    <img src="#" alt="" id="coverPreview"
                                                        class="avatar-md h-auto rounded-3 object-fit-cover">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <!-- SEO Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">SEO Information <small class="text-muted">(Optional)</small></h6>
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
                            <input type="url" name="_canonical_url" id="_canonical_url" class="form-control"
                                value="{{ old('_canonical_url') }}" placeholder="https://example.com/page-url">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Schema Markup (JSON-LD)</label>
                            <textarea name="_schema_markup" id="_schema_markup" class="form-control" rows="6"
                                placeholder='{
                                "@context": "https://schema.org",
                                "@type": "WebPage",
                                "name": "Page Title",
                                "url": "https://example.com/page-url"
                                }'>{{ old('_schema_markup') }}
                            </textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Page Index</label>
                            <div class="form-check form-switch">
                                <!-- Hidden input comes BEFORE checkbox to be overridden when checked -->
                                <input type="hidden" name="_page_index" value="0">
                                <input class="form-check-input" type="checkbox" id="pageIndexToggle"
                                    name="_page_index" value="1"
                                    {{ old('_page_index') == '1' ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">All Category Products Page Index</label>
                            <select class="form-select" name="_all_product_index" id="pageIndexSelect">
                                <option value="3" {{ old('_all_product_index', '3') == '3' ? 'selected' : '' }}>
                                    No Change (Keep Current Settings)
                                </option>
                                <option value="1" {{ old('_all_product_index') == '1' ? 'selected' : '' }}>
                                    Index All Products (Allow Search Engines)
                                </option>
                                <option value="0" {{ old('_all_product_index') == '0' ? 'selected' : '' }}>
                                    No-Index All Products (Block Search Engines)
                                </option>
                            </select>
                            <div id="productStatusMessage" class="mt-2"></div> <!-- Status message container -->
                        </div>


                    </div>
                </div>

                <!-- Category Content Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Category Content</h6>
                        <small class="text-muted">Add detailed content about the category including images, formatting,
                            and media.</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Category Description</label>
                            <textarea name="_description" id="_description" class="form-control rich-editor" rows="10">{{ old('_description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body text-end">
                        <button type="submit" class="btn btn-success w-100">Add Category</button>
                    </div>
                </div>

                </form>
            </div>

            <div class="col-xxl-7">
                <form method="GET" action="{{ route('category.index') }}" class="row g-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <!-- Search Input -->
                                <div class="col-md-5">
                                    <label for="searchKeyword" class="form-label">Search Category</label>
                                    <div class="position-relative">
                                        <input type="text" id="searchKeyword" name="keyword" class="form-control"
                                            placeholder="Enter category name..." value="{{ request('keyword') }}">
                                    </div>
                                </div>

                                <!-- Status Filter -->
                                <div class="col-md-4">
                                    <label for="idStatus" class="form-label">Filter by Date</label>
                                    <select name="status" class="form-select" id="idStatus">
                                        <option value="">All Time</option>
                                        <option value="today" {{ request('status') == 'today' ? 'selected' : '' }}>
                                            Today</option>
                                        <option value="yesterday"
                                            {{ request('status') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                        <option value="7days" {{ request('status') == '7days' ? 'selected' : '' }}>
                                            Last 7 Days</option>
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
                                        <a href="{{ route('category.index') }}" class="btn btn-light border w-100">
                                            <i class="fa-solid fa-circle-xmark me-1"></i>Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div>
                    <div class="d-flex flex-wrap">
                        @foreach ($categories as $category)
                            <div class="col-xxl-4 col-md-6 col-12 ps-2">
                                <div class="card categrory-widgets overflow-hidden">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <h5 class="flex-grow-1 mb-0">{{ $category->title ?? '' }}</h5>
                                            <ul class="flex-shrink-0 list-unstyled hstack gap-1 mb-0">
                                                <li>
                                                    <a href="#"
                                                        class="badge bg-info-subtle text-info edit-category-btn"
                                                        data-id="{{ $category->id }}">Edit</a>
                                                </li>

                                                <li> <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#deleteConfirmModal{{ $category->id }}"
                                                        class="badge bg-danger-subtle text-danger">Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <ul class="list-unstyled vstack gap-2 mb-0">
                                            @foreach ($category->childCategories->take(4) as $childCategory)
                                                <li>{{ $childCategory->child_title }}</li>
                                            @endforeach
                                        </ul>
                                        <div class="mt-3">
                                            <a href="#" class="fw-medium link-effect read-more-btn"
                                                data-id="{{ $category->id }}" data-bs-toggle="offcanvas"
                                                data-bs-target="#overviewOffcanvas">
                                                Read More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade zoomIn" id="deleteConfirmModal{{ $category->id }}" tabindex="-1"
                                aria-labelledby="deleteConfirmModalLabel{{ $category->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-md-5 text-center">
                                            <div class="text-danger mb-4">
                                                <i class="fa-solid fa-trash fs-1"></i>
                                            </div>
                                            <h4 class="mb-1">Are you sure?</h4>
                                            <p class="text-muted fs-16">You are about to delete
                                                <strong>{{ $category->title }}</strong>. This action cannot be undone.
                                            </p>

                                            <div class="d-flex justify-content-center gap-2 mt-4">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('category.delete') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ $category->id }}">
                                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div><!--end row-->
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        {!! $categories->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- container-fluid -->
    </div>
    @include('Admin.components.offcanvas') <!-- If extracted to a file -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const titleInput = document.getElementById("category_title");
            const skuInput = document.getElementById("category_sku");

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
            document.querySelectorAll(".image-preview").forEach(input => {
                input.addEventListener("change", function(event) {
                    const previewId = this.dataset.preview;
                    const file = this.files[0];
                    if (file && previewId) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.getElementById(previewId);
                            img.src = e.target.result;
                            img.classList.remove("d-none");
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });

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

                let categoryId = $(this).data('id');

                $.ajax({
                    url: '/edit_category',
                    method: 'GET',
                    data: {
                        id: categoryId
                    },
                    success: function(res) {
                        if (res && res.id) {
                            console.log('category data', res);

                            // Convert category_meta array to a key-value object
                            let metas = {};
                            if (res.category_meta && Array.isArray(res.category_meta)) {
                                res.category_meta.forEach(meta => {
                                    metas[meta.option] = meta.value;
                                });
                            }

                            // Set form values
                            $('#category_id').val(res.id);
                            $('#category_title').val(res.title);
                            $('#category_sku').val(res.sku);

                            // Set SEO fields using metas object
                            $('#_seo_title').val(metas._seo_title || '');
                            $('#_seo_tags').val(metas._seo_tags || '');
                            $('#_seo_description').val(metas._seo_description || '');
                            $('#_canonical_url').val(metas._canonical_url || '');
                            $('#_schema_markup').val(metas._schema_markup || '');

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
                            $('#_description').summernote('code', summernoteContent);

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


                            // Set thumbnail image preview
                            if (res.thumbnail_image) {
                                $('#thumbnailPreview').attr('src', '/' + res.thumbnail_image);
                            } else {
                                $('#thumbnailPreview').attr('src', '#');
                            }

                            // Set cover image preview
                            if (res.cover_image) {
                                $('#coverPreview').attr('src', '/' + res.cover_image);
                            } else {
                                $('#coverPreview').attr('src', '#');
                            }

                            // Set form action to update route
                            $('form[method="POST"]').attr('action',
                                "{{ route('category.store') }}");
                            $('.btn-success').text('Update Category');
                        }
                    },


                    error: function() {
                        alert('Failed to fetch category details.');
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Open details sidebar when clicking read more
            $('.read-more-btn').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');

                // Show loading state
                $('#overviewOffcanvas .offcanvas-body').find('.loading-spinner')
                    .remove(); // Remove any existing spinners first
                $('#overviewOffcanvas .offcanvas-body').append(
                    '<div class="loading-spinner position-absolute top-50 start-50 translate-middle"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                );

                $.ajax({
                    url: '/show_category_detail',
                    method: 'GET',
                    data: {
                        id: id
                    },
                    success: function(res) {
                        // Remove loading spinner
                        $('.loading-spinner').remove();

                        if (res && res.id) {
                            // Populate title
                            $('.overview-title').text(res.title ?? 'Untitled Category');

                            // Handle thumbnail image
                            let thumbImage = res.thumbnail_image ? '/' + res.thumbnail_image :
                                '/assets/images/default.jpg';
                            if (thumbImage) {
                                $('.overview-thumb-img').attr('src', thumbImage);
                                if ($('.thumbnail-overlay').length) {
                                    $('.thumbnail-overlay').addClass('d-none');
                                }
                            } else {
                                $('.overview-thumb-img').attr('src', '');
                                if ($('.thumbnail-overlay').length) {
                                    $('.thumbnail-overlay').removeClass('d-none');
                                }
                            }

                            // Handle cover image
                            let coverImage = res.cover_image ? '/' + res.cover_image :
                                '/assets/images/default.jpg';
                            if (coverImage) {
                                $('.overview-cover-img').attr('src', coverImage);
                                if ($('.cover-image-overlay').length) {
                                    $('.cover-image-overlay').addClass('d-none');
                                }
                            } else {
                                $('.overview-cover-img').attr('src', '');
                                if ($('.cover-image-overlay').length) {
                                    $('.cover-image-overlay').removeClass('d-none');
                                }
                            }

                            // Subcategories list
                            let subCatCount = 0;
                            let subCatHtml = '';

                            if (res.child_categories && res.child_categories.length > 0) {
                                subCatCount = res.child_categories.length;
                                res.child_categories.forEach(cat => {
                                    subCatHtml += `<li>
                                <i class="fa-solid fa-caret-right me-2 text-primary"></i>
                                <a href="#" class="text-reset fw-medium">${cat.child_title || ''}</a>
                            </li>`;
                                });
                            } else {
                                subCatHtml =
                                    '<li class="text-muted"><i class="fa-solid fa-circle-info me-2"></i>No subcategories available</li>';
                            }

                            // Update subcategory count
                            $('.subcategory-count, .subcategory-count-badge').text(subCatCount);
                            $('.subCategory').html(subCatHtml);

                            // Set action buttons data with the correct ID
                            $('.remove-list').attr('data-id', res.id);
                            $('.remove-list').attr('data-bs-target', '#deleteConfirmModal' + res
                                .id);
                            $('.edit-list').attr('data-id', res.id);

                            // Add animation - only if the classes exist
                            if ($('.category-meta, .subcategory-list').length) {
                                $('.category-meta, .subcategory-list').addClass(
                                    'animate__animated animate__fadeIn');
                                setTimeout(() => {
                                    $('.category-meta, .subcategory-list').removeClass(
                                        'animate__animated animate__fadeIn');
                                }, 1000);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Remove loading spinner
                        $('.loading-spinner').remove();

                        console.log("Ajax error: " + status + " - " + error);

                        // Show error message
                        $('.offcanvas-body').find('.alert')
                            .remove(); // Remove any existing alerts
                        $('.offcanvas-body').prepend(`
                    <div class="alert alert-danger mx-3 mt-3 alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        Failed to load category details
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                    }
                });
            });

            // Delete handler - Use a safer approach to close the offcanvas
            $(document).off('click', '.remove-list').on('click', '.remove-list', function(e) {
                e.preventDefault();
                let id = $(this).data('id');

                try {
                    // Try to dismiss the offcanvas safely
                    var offcanvasElement = document.getElementById('overviewOffcanvas');
                    // Check if the offcanvas is actually open before trying to close it
                    if (offcanvasElement && offcanvasElement.classList.contains('show')) {
                        var bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                        if (bsOffcanvas) {
                            // The proper way to handle the sequence
                            $(offcanvasElement).one('hidden.bs.offcanvas', function() {
                                setTimeout(function() {
                                    $('#deleteConfirmModal' + id).modal('show');
                                }, 100);
                            });
                            bsOffcanvas.hide();
                        } else {
                            // If no instance exists, just show the modal directly
                            $('#deleteConfirmModal' + id).modal('show');
                        }
                    } else {
                        // If offcanvas isn't open, just show the modal
                        $('#deleteConfirmModal' + id).modal('show');
                    }
                } catch (err) {
                    console.log("Error handling delete action:", err);
                    // Fallback - just show the modal
                    $('#deleteConfirmModal' + id).modal('show');
                }
            });

            // Edit handler - Use a safer approach
            $(document).off('click', '.edit-list').on('click', '.edit-list', function(e) {
                e.preventDefault();
                let id = $(this).data('id');

                try {
                    // Try to dismiss the offcanvas safely
                    var offcanvasElement = document.getElementById('overviewOffcanvas');
                    if (offcanvasElement && offcanvasElement.classList.contains('show')) {
                        var bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                        if (bsOffcanvas) {
                            $(offcanvasElement).one('hidden.bs.offcanvas', function() {
                                setTimeout(function() {
                                    $('.edit-category-btn[data-id="' + id + '"]').trigger(
                                        'click');
                                }, 100);
                            });
                            bsOffcanvas.hide();
                        } else {
                            $('.edit-category-btn[data-id="' + id + '"]').trigger('click');
                        }
                    } else {
                        $('.edit-category-btn[data-id="' + id + '"]').trigger('click');
                    }
                } catch (err) {
                    console.log("Error handling edit action:", err);
                    // Fallback
                    setTimeout(function() {
                        $('.edit-category-btn[data-id="' + id + '"]').trigger('click');
                    }, 300);
                }
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
