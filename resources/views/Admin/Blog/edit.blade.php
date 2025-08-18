@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
@endphp
<div class="page-content">
    <div class="container-fluid">
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
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <div>
                        <h4 class="mb-1">{{ __('Blog Edit') }}</h4>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('blogs.index') }}" class="link-primary text-decoration-none">
                                        Blog
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Blog Edit
                                </li>
                            </ol>
                        </nav>
                    </div>

                    @if (Session::has('permissions') &&
                            (in_array('Blog List', Session::get('permissions')) ||
                                in_array('Blog Trash List', Session::get('permissions'))))
                        <div class="d-flex gap-2">
                            @if (in_array('Blog List', Session::get('permissions')))
                                <a href="{{ route('blogs.index') }}" class="btn btn-primary">
                                    <i class="bi bi-list me-1"></i> Blog List
                                </a>
                            @endif
                            @if (in_array('Blog Trash List', Session::get('permissions')))
                                <a href="{{ route('blogs.trash') }}" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i> Trash List
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <form id="blog-edit-form" method="POST" action="{{ route('blogs.update', $blog->id) }}"
            enctype="multipart/form-data" autocomplete="off" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <input type="hidden" name="updated_by" value="{{ $logged_in_user->id }}">
            <div class="row">
                <div class="col-xl-9 col-lg-8">
                    <!-- Blog Details -->
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title rounded-circle bg-light text-primary fs-20">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">Blog Details</h5>
                                    <p class="text-muted mb-0">Update essential blog information below.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Title -->
                            <div class="mb-3">
                                <label class="form-label" for="blog-title-input">Blog Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control addTitle" name="title" id="blog-title-input"
                                    placeholder="e.g. How to Use Power Tools Safely"
                                    value="{{ old('title', $blog->title) }}" required>
                                <div class="invalid-feedback">Please enter a blog title.</div>
                            </div>

                            <!-- Permalink -->
                            <div class="form-group mb-3">
                                <label class="form-control-label active">Permalink: <span
                                        class="tx-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend custom-permalink">
                                        <span class="input-group-text">{{ url('/') }}/blog/</span>
                                    </div>
                                    <input class="form-control restrict-input-alphabets permalink" id="permalink"
                                        type="text" name="permalink"
                                        value="{{ old('permalink', $blog->permalink) }}"
                                        placeholder="how-to-use-power-tools-safely" required>
                                </div>
                                <div class="text-danger permalink_error"></div>
                                <input type="hidden" class="permalink_status" name="permalink_status"
                                    value="{{ old('permalink_status', '1') }}" required>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Short description of the blog post...">{{ old('description', $blog->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Categories and Brands -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Categories & Brands</h5>
                            <small class="text-muted">Organize your blog post by selecting relevant categories and
                                brands.</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Categories -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Categories</label>
                                        <select name="category_ids[]" id="categories"
                                            class="form-select select2-multiple" multiple size="5"
                                            style="width: 100%">
                                            <option></option> <!-- for placeholder -->
                                            @if (isset($categories) && $categories->count() > 0)
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ in_array($category->id, old('category_ids', $blog->categories->pluck('id')->toArray()) ?? []) ? 'selected' : '' }}>
                                                        {{ $category->title }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <small class="text-muted">You can select multiple categories</small>
                                    </div>
                                </div>

                                <!-- Brands -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Brands</label>
                                        <select name="brand_ids[]" class="form-select select2-multiple" multiple
                                            size="5" style="width: 100%">
                                            <option></option>
                                            @if (isset($brands) && $brands->count() > 0)
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ in_array($brand->id, old('brand_ids', $blog->brands->pluck('id')->toArray()) ?? []) ? 'selected' : '' }}>
                                                        {{ $brand->title }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <small class="text-muted">You can select multiple brands</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Child Categories -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Child Categories</label>
                                        <select name="child_category_ids[]" id="child-categories"
                                            class="form-select select2-multiple" multiple size="4"
                                            style="width: 100%">
                                            <option></option>
                                            <!-- Options will be populated via AJAX -->
                                        </select>
                                        <small class="text-muted">First select a main category to see child
                                            categories</small>
                                    </div>
                                </div>

                            
                            </div>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">SEO Information <small class="text-muted">(Optional)</small>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">SEO Title</label>
                                <input type="text" name="_seo_title" class="form-control"
                                    value="{{ old('_seo_title', $blog->_seo_title) }}" placeholder="SEO Title...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SEO Tags</label>
                                <input type="text" name="_seo_tags" class="form-control"
                                    value="{{ old('_seo_tags', $blog->_seo_tags) }}" placeholder="SEO Tags...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SEO Description</label>
                                <textarea name="_seo_description" class="form-control" rows="3" placeholder="SEO Description...">{{ old('_seo_description', $blog->_seo_description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Canonical URL</label>
                                <input type="url" name="_canonical_url" class="form-control"
                                    value="{{ old('_canonical_url', $blog->_canonical_url) }}"
                                    placeholder="https://example.com/page-url">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Schema Markup (JSON-LD)</label>
                                <textarea name="_schema_markup" class="form-control" rows="6"
                                    placeholder='{
                                "@context": "https://schema.org",
                                "@type": "BlogPosting",
                                "headline": "Blog Title",
                                "url": "https://example.com/blog-url"
                                }'>{{ old('_schema_markup', $blog->_schema_markup) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Page Index</label>
                                <div class="form-check form-switch">
                                    <!-- Hidden input comes BEFORE checkbox to be overridden when checked -->
                                    <input type="hidden" name="_page_index" value="0">
                                    <input class="form-check-input" type="checkbox" id="pageIndexToggle"
                                        name="_page_index" value="1"
                                        {{ old('_page_index', $blog->_page_index) == '1' || old('_page_index', $blog->_page_index) == 1 ? 'checked' : '' }}>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Blog Content Section -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Blog Content</h5>
                            <small class="text-muted">Update detailed content for your blog post including formatting
                                and
                                media.</small>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Blog Content</label>
                                <textarea name="content" class="form-control rich-editor" rows="15">{{ old('content', $blog->content) }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sidebar Column -->
                <div class="col-xl-3 col-lg-4">
                    <!-- Status -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Status</h5>
                        </div>
                        <div class="card-body">
                            <label for="blog-status" class="form-label">Active Status</label>
                            <select name="active" class="form-select" id="blog-status">
                                <option value="0" {{ old('active', $blog->active) == 0 ? 'selected' : '' }}>Draft
                                </option>
                                <option value="1" {{ old('active', $blog->active) == 1 ? 'selected' : '' }}>
                                    Published</option>
                                <option value="2" {{ old('active', $blog->active) == 2 ? 'selected' : '' }}>
                                    Private</option>
                                <option value="3" {{ old('active', $blog->active) == 3 ? 'selected' : '' }}>Trash
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Media -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Blog Images</h5>
                        </div>
                        <div class="card-body">
                            <!-- Current Cover Image -->
                            @if ($blog->cover_image)
                                <div class="mb-3">
                                    <label class="form-label">Current Cover Image</label>
                                    <div class="current-cover-image">
                                        <img src="{{ asset($blog->cover_image) }}" alt="Current Cover"
                                            class="img-fluid rounded border"
                                            style="max-height: 150px; width: 100%; object-fit: contain;">
                                    </div>
                                </div>
                            @endif

                            <!-- Cover Image -->
                            <div class="mb-3">
                                <label
                                    class="form-label">{{ $blog->cover_image ? 'Replace Cover Image' : 'Cover Image' }}</label>
                                <input class="form-control" type="file" name="cover_image" accept="image/*"
                                    id="coverImageInput">
                                <small class="text-muted">Recommended: 1200x630px</small>
                                <div id="coverImagePreview" class="mt-2"></div>
                            </div>

                            <!-- Current Featured Images -->
                            @if ($blog->featured_images)
                                <div class="mb-3">
                                    <label class="form-label">Current Featured Images</label>
                                    <div class="current-featured-images">
                                        <div class="mb-2">
                                            <img src="{{ asset($blog->featured_images) }}" alt="Featured Image"
                                                class="img-fluid rounded border"
                                                style="max-height: 100px; width: 100%; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Featured Images -->
                            <div class="mb-3">
                                <label
                                    class="form-label">{{ $blog->featured_images ? 'Replace Featured Images' : 'Featured Images' }}</label>
                                <input class="form-control" type="file" name="featured_images[]" accept="image/*"
                                    id="featuredImagesInput" multiple>
                                <div id="featuredImagesPreview" class="mt-2"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="blog-title-input">Feature Image alt Text</label>
                                <input type="text" class="form-control " name="fimage_alt_text"
                                    id="fimage_alt_text" placeholder="Please enter a Feature Image alt Text"
                                    value="{{ old('fimage_alt_text', $blog->fimage_alt_text ?? '') }}" required>
                                <div class="invalid-feedback">Please enter a Feature Image alt Text.</div>
                            </div>

                            <!-- Stay on page checkbox -->
                            <!-- <div class="form-check mt-3 mb-2">
                                <input class="form-check-input" type="checkbox" id="stayOnPage" name="stay_on_page"
                                    value="1" {{ old('stay_on_page') ? 'checked' : '' }}>
                                <label class="form-check-label" for="stayOnPage">
                                    <small>Stay on this page after updating</small>
                                </label>
                            </div> -->
                        </div>
                    </div>

                    <div class="text-end mb-3">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle me-1"></i>Update Blog
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    // Wait for document ready
    $(document).ready(function() {
        // Title to permalink conversion
        $('.addTitle').on('input', function() {
            var title = $(this).val();
            var permalink = title.replace(/\s+/g, '-').toLowerCase().replace(/[^\w\-]+/g, '');
            $('.permalink').val(permalink);
            validate_permalink();
        });

        $('.permalink').on('input', function() {
            validate_permalink();
        });

        // Image preview functionality
        const coverInput = document.getElementById('coverImageInput');
        const coverPreview = document.getElementById('coverImagePreview');
        const featuredInput = document.getElementById('featuredImagesInput');
        const featuredPreview = document.getElementById('featuredImagesPreview');

        // Cover image preview
        if (coverInput && coverPreview) {
            coverInput.addEventListener('change', function(event) {
                showPreviewImage(event.target.files[0], coverPreview);
            });
        }

        // Featured images preview
        if (featuredInput && featuredPreview) {
            featuredInput.addEventListener('change', function(event) {
                showMultiplePreviewImages(event.target.files, featuredPreview);
            });
        }

        function showPreviewImage(file, container) {
            if (!file) {
                container.innerHTML = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100%';
                img.style.maxHeight = '200px';
                img.style.objectFit = 'contain';
                img.className = "rounded border";

                container.innerHTML = '';
                container.appendChild(img);
            };
            reader.readAsDataURL(file);
        }

        function showMultiplePreviewImages(files, container) {
            container.innerHTML = '';

            if (!files || files.length === 0) {
                return;
            }

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'mb-2';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.maxHeight = '150px';
                    img.style.objectFit = 'contain';
                    img.className = "rounded border";

                    const caption = document.createElement('small');
                    caption.className = 'text-muted d-block mt-1';
                    caption.textContent = file.name;

                    div.appendChild(img);
                    div.appendChild(caption);
                    container.appendChild(div);
                };

                reader.readAsDataURL(file);
            }
        }
    });

    // Auto-dismiss alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);

    // Permalink validation function
    function validate_permalink() {
        var permalink = $('#permalink').val();
        var currentBlogId = {{ $blog->id }};

        if (!permalink) {
            $('.permalink_status').val('');
            $('.permalink_error').html('<small class="text-danger">Permalink is required.</small>');
            return;
        }

        $.ajax({
            url: '{{ route('blogs.check.permalink') }}',
            method: 'POST',
            data: {
                permalink: permalink,
                blog_id: currentBlogId, // Exclude current blog from check
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                $('.permalink_status').val('1');
                $('.permalink_error').html('<small class="text-success">Permalink is available.</small>');
            },
            error: function(xhr, status, error) {
                $('.permalink_status').val('');
                $('.permalink_error').html(
                    '<small class="text-danger">This permalink already exists or is invalid. Please change it.</small>'
                );
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        // Initialize rich text editor
        $('.rich-editor').summernote({
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            placeholder: 'Write your blog content here...'
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for all dropdowns
        $('.select2-multiple').select2({
            placeholder: "Select options",
            allowClear: true,
            width: 'resolve'
        });

        // Get initially selected values from the blog data (you'll need to pass this from backend)
        // Alternative: Get from hidden fields or data attributes
        var initialCategories = $('#categories').val() || [];
        var initialChildCategories = @json($blog->childCategories->pluck('id')->toArray());
        var initialGrandChildCategories = @json($blog->grandChildCategories->pluck('id')->toArray());

        // Clear child and grand child dropdowns initially (since they'll be populated dynamically)
        $('select[name="child_category_ids[]"]').empty().append('<option></option>');
        $('select[name="grand_child_category_ids[]"]').empty().append('<option></option>');

        // If there are pre-selected categories, load their child categories
        if (initialCategories.length > 0) {
            loadChildCategoriesForEdit(initialCategories, initialChildCategories);
        }

        // When main categories change
        $('#categories').on('change', function() {
            var selectedCategories = $(this).val();

            // Get currently selected child categories to preserve them
            var currentlySelectedChildCategories = $('select[name="child_category_ids[]"]').val() || [];

            if (selectedCategories && selectedCategories.length > 0) {
                // Load child categories for all selected main categories while preserving current selections
                loadChildCategoriesWithPreservation(selectedCategories,
                    currentlySelectedChildCategories);
            } else {
                // If no categories selected, clear child and grand child categories
                $('select[name="child_category_ids[]"]').empty().append('<option></option>');
                $('select[name="grand_child_category_ids[]"]').empty().append('<option></option>');

                // Refresh Select2
                $('select[name="child_category_ids[]"]').select2('destroy').select2({
                    placeholder: 'Select child categories',
                    allowClear: true
                });
                $('select[name="grand_child_category_ids[]"]').select2('destroy').select2({
                    placeholder: 'Select grand child categories',
                    allowClear: true
                });
            }
        });

        // When child categories change
        $('select[name="child_category_ids[]"]').on('change', function() {
            var selectedChildCategories = $(this).val();

            // Get currently selected grand child categories to preserve them
            var currentlySelectedGrandChildCategories = $('select[name="grand_child_category_ids[]"]')
                .val() || [];

            if (selectedChildCategories && selectedChildCategories.length > 0) {
                // Load grand child categories for all selected child categories while preserving current selections
                loadGrandChildCategoriesWithPreservation(selectedChildCategories,
                    currentlySelectedGrandChildCategories);
            } else {
                // If no child categories selected, clear grand child categories
                $('select[name="grand_child_category_ids[]"]').empty().append('<option></option>');
                $('select[name="grand_child_category_ids[]"]').select2('destroy').select2({
                    placeholder: 'Select grand child categories',
                    allowClear: true
                });
            }
        });

        // Function to load child categories (same as create page)
        function loadChildCategories(categoryIds) {
            var allChildCategories = [];
            var completedRequests = 0;

            categoryIds.forEach(function(categoryId) {
                $.ajax({
                    url: '{{ route('admin.blog.child-categories', ':id') }}'.replace(':id',
                        categoryId),
                    type: 'GET',
                    success: function(data) {
                        allChildCategories = allChildCategories.concat(data);
                        completedRequests++;

                        if (completedRequests === categoryIds.length) {
                            // Remove duplicates
                            var uniqueChildCategories = allChildCategories.filter((category,
                                    index, self) =>
                                index === self.findIndex(c => c.id === category.id)
                            );

                            // Populate child categories dropdown
                            uniqueChildCategories.forEach(function(childCategory) {
                                $('select[name="child_category_ids[]"]').append(
                                    '<option value="' + childCategory.id +
                                    '">' + childCategory.child_title +
                                    '</option>'
                                );
                            });

                            // Refresh Select2
                            $('select[name="child_category_ids[]"]').select2('destroy')
                                .select2({
                                    placeholder: 'Select child categories',
                                    allowClear: true
                                });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading child categories:', error);
                    }
                });
            });
        }

        // Function to load child categories for edit page (preserves selections)
        function loadChildCategoriesForEdit(categoryIds, selectedChildIds) {
            var allChildCategories = [];
            var completedRequests = 0;

            categoryIds.forEach(function(categoryId) {
                $.ajax({
                    url: '{{ route('admin.blog.child-categories', ':id') }}'.replace(':id',
                        categoryId),
                    type: 'GET',
                    success: function(data) {
                        allChildCategories = allChildCategories.concat(data);
                        completedRequests++;

                        if (completedRequests === categoryIds.length) {
                            // Remove duplicates
                            var uniqueChildCategories = allChildCategories.filter((category,
                                    index, self) =>
                                index === self.findIndex(c => c.id === category.id)
                            );

                            // Clear existing options except placeholder
                            $('select[name="child_category_ids[]"]').empty().append(
                                '<option></option>');

                            // Populate child categories dropdown
                            uniqueChildCategories.forEach(function(childCategory) {
                                var isSelected = selectedChildIds.includes(
                                    childCategory.id);
                                $('select[name="child_category_ids[]"]').append(
                                    '<option value="' + childCategory.id + '"' +
                                    (isSelected ? ' selected' : '') + '>' +
                                    childCategory.child_title + '</option>'
                                );
                            });

                            // Refresh Select2
                            $('select[name="child_category_ids[]"]').select2('destroy')
                                .select2({
                                    placeholder: 'Select child categories',
                                    allowClear: true
                                });

                            // If there were initially selected child categories, load grand child categories
                            if (selectedChildIds.length > 0) {
                                loadGrandChildCategoriesForEdit(selectedChildIds,
                                    initialGrandChildCategories);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading child categories:', error);
                    }
                });
            });
        }

        // Function to load grand child categories (same as create page)
        function loadGrandChildCategories(childCategoryIds) {
            var allGrandChildCategories = [];
            var completedRequests = 0;

            childCategoryIds.forEach(function(childCategoryId) {
                $.ajax({
                    url: '{{ route('admin.blog.grand-child-categories', ':id') }}'.replace(
                        ':id', childCategoryId),
                    type: 'GET',
                    success: function(data) {
                        allGrandChildCategories = allGrandChildCategories.concat(data);
                        completedRequests++;

                        if (completedRequests === childCategoryIds.length) {
                            // Remove duplicates
                            var uniqueGrandChildCategories = allGrandChildCategories.filter(
                                (category, index, self) =>
                                index === self.findIndex(c => c.id === category.id)
                            );

                            // Populate grand child categories dropdown
                            uniqueGrandChildCategories.forEach(function(
                            grandChildCategory) {
                                $('select[name="grand_child_category_ids[]"]')
                                    .append(
                                        '<option value="' + grandChildCategory.id +
                                        '">' + grandChildCategory.grand_title +
                                        '</option>'
                                    );
                            });

                            // Refresh Select2
                            $('select[name="grand_child_category_ids[]"]').select2(
                                'destroy').select2({
                                placeholder: 'Select grand child categories',
                                allowClear: true
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading grand child categories:', error);
                    }
                });
            });
        }

        // MISSING FUNCTION - Function to load grand child categories for edit page (preserves selections)
        function loadGrandChildCategoriesForEdit(childCategoryIds, selectedGrandChildIds) {
            var allGrandChildCategories = [];
            var completedRequests = 0;

            childCategoryIds.forEach(function(childCategoryId) {
                $.ajax({
                    url: '{{ route('admin.blog.grand-child-categories', ':id') }}'.replace(
                        ':id', childCategoryId),
                    type: 'GET',
                    success: function(data) {
                        allGrandChildCategories = allGrandChildCategories.concat(data);
                        completedRequests++;

                        if (completedRequests === childCategoryIds.length) {
                            // Remove duplicates
                            var uniqueGrandChildCategories = allGrandChildCategories.filter(
                                (category, index, self) =>
                                index === self.findIndex(c => c.id === category.id)
                            );

                            // Clear existing options except placeholder
                            $('select[name="grand_child_category_ids[]"]').empty().append(
                                '<option></option>');

                            // Populate grand child categories dropdown
                            uniqueGrandChildCategories.forEach(function(grandChildCategory) {
                                var isSelected = selectedGrandChildIds.includes(grandChildCategory.id);
                                $('select[name="grand_child_category_ids[]"]').append(
                                    '<option value="' + grandChildCategory.id + '"' +
                                    (isSelected ? ' selected' : '') + '>' +
                                    grandChildCategory.grand_title + '</option>'
                                );
                            });

                            // Refresh Select2
                            $('select[name="grand_child_category_ids[]"]').select2('destroy')
                                .select2({
                                    placeholder: 'Select grand child categories',
                                    allowClear: true
                                });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading grand child categories:', error);
                    }
                });
            });
        }

        // Function to load child categories while preserving existing selections
        function loadChildCategoriesWithPreservation(categoryIds, preserveSelectedIds) {
            var allChildCategories = [];
            var completedRequests = 0;

            categoryIds.forEach(function(categoryId) {
                $.ajax({
                    url: '{{ route('admin.blog.child-categories', ':id') }}'.replace(':id',
                        categoryId),
                    type: 'GET',
                    success: function(data) {
                        allChildCategories = allChildCategories.concat(data);
                        completedRequests++;

                        if (completedRequests === categoryIds.length) {
                            // Remove duplicates
                            var uniqueChildCategories = allChildCategories.filter((category,
                                    index, self) =>
                                index === self.findIndex(c => c.id === category.id)
                            );

                            // Clear existing options except placeholder
                            $('select[name="child_category_ids[]"]').empty().append(
                                '<option></option>');

                            // Populate child categories dropdown
                            uniqueChildCategories.forEach(function(childCategory) {
                                var isSelected = preserveSelectedIds.includes(
                                        childCategory.id.toString()) ||
                                    preserveSelectedIds.includes(childCategory.id);
                                $('select[name="child_category_ids[]"]').append(
                                    '<option value="' + childCategory.id + '"' +
                                    (isSelected ? ' selected' : '') + '>' +
                                    childCategory.child_title + '</option>'
                                );
                            });

                            // Refresh Select2
                            $('select[name="child_category_ids[]"]').select2('destroy')
                                .select2({
                                    placeholder: 'Select child categories',
                                    allowClear: true
                                });

                            // After loading child categories, check if we need to reload grand child categories
                            var currentChildSelections = $(
                                'select[name="child_category_ids[]"]').val() || [];
                            if (currentChildSelections.length > 0) {
                                var currentGrandChildSelections = $(
                                        'select[name="grand_child_category_ids[]"]')
                                .val() || [];
                                loadGrandChildCategoriesWithPreservation(
                                    currentChildSelections, currentGrandChildSelections);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading child categories:', error);
                    }
                });
            });
        }

        // Function to load grand child categories while preserving existing selections
        function loadGrandChildCategoriesWithPreservation(childCategoryIds, preserveSelectedIds) {
            var allGrandChildCategories = [];
            var completedRequests = 0;

            childCategoryIds.forEach(function(childCategoryId) {
                $.ajax({
                    url: '{{ route('admin.blog.grand-child-categories', ':id') }}'.replace(
                        ':id', childCategoryId),
                    type: 'GET',
                    success: function(data) {
                        allGrandChildCategories = allGrandChildCategories.concat(data);
                        completedRequests++;

                        if (completedRequests === childCategoryIds.length) {
                            // Remove duplicates
                            var uniqueGrandChildCategories = allGrandChildCategories.filter(
                                (category, index, self) =>
                                index === self.findIndex(c => c.id === category.id)
                            );

                            // Clear existing options except placeholder
                            $('select[name="grand_child_category_ids[]"]').empty().append(
                                '<option></option>');

                            // Populate grand child categories dropdown
                            uniqueGrandChildCategories.forEach(function(
                            grandChildCategory) {
                                var isSelected = preserveSelectedIds.includes(
                                        grandChildCategory.id.toString()) ||
                                    preserveSelectedIds.includes(grandChildCategory
                                        .id);
                                $('select[name="grand_child_category_ids[]"]')
                                    .append(
                                        '<option value="' + grandChildCategory.id +
                                        '"' + (isSelected ? ' selected' : '') +
                                        '>' +
                                        grandChildCategory.grand_title + '</option>'
                                    );
                            });

                            // Refresh Select2
                            $('select[name="grand_child_category_ids[]"]').select2(
                                'destroy').select2({
                                placeholder: 'Select grand child categories',
                                allowClear: true
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading grand child categories:', error);
                    }
                });
            });
        }
    });
</script>