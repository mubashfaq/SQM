@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
@endphp

<style>
    .form-check-label {
        font-weight: 500;
        color: #6c757d;
    }

    .form-check-input:checked+.form-check-label {
        color: #198754;
    }
</style>
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
                        <h4 class="mb-1">{{ __('Blog Create') }}</h4>

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
                                    Blog Create
                                </li>
                            </ol>
                        </nav>
                    </div>

                    {{-- @if (Session::has('permissions') && (in_array('Blog List', Session::get('permissions')) || in_array('Blog Trash List', Session::get('permissions')))) --}}
                    <div class="d-flex gap-2">
                        {{-- @if (in_array('Blog List', Session::get('permissions'))) --}}
                        <a href="{{ route('blogs.index') }}" class="btn btn-primary">
                            <i class="bi bi-list me-1"></i> Blog List
                        </a>
                        {{-- @endif --}}
                        {{-- @if (in_array('Blog Trash List', Session::get('permissions'))) --}}
                        <a href="{{ route('Admin.Blog.Trash') }}" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> Trash List
                        </a>
                        {{-- @endif --}}
                    </div>
                    {{-- @endif --}}
                </div>
            </div>
        </div>

        <form id="blog-create-form" method="POST" action="{{ route('blogs.store') }}" enctype="multipart/form-data"
            autocomplete="off" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="created_by" value="{{ $logged_in_user->id }}">
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
                                    <p class="text-muted mb-0">Enter essential blog information below.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Title -->
                            <div class="mb-3">
                                <label class="form-label" for="blog-title-input">Blog Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control addTitle" name="title" id="blog-title-input"
                                    placeholder="Blog Title" value="{{ old('title') }}"
                                    required>
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
                                        type="text" name="permalink" value="{{ old('permalink') }}"
                                        placeholder="blog-title" required>
                                </div>
                                <div class="text-danger permalink_error"></div>
                                <input type="hidden" class="permalink_status" name="permalink_status"
                                    value="{{ old('permalink_status') }}" required>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Short description of the blog post...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Categories and Brands -->
                   <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Categories & Brands</h5>
                            <small class="text-muted">Organize your blog post by selecting relevant categories and brands.</small>
                        </div>
                        <div class="card-body">     
                            <div class="row">
                                <!-- Categories -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Categories</label>
                                        <select name="category_ids[]" id="categories" class="form-select select2-multiple" multiple data-placeholder="Select categories">
                                            @if (isset($categories))
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', [])) ? 'selected' : '' }}>
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
                                        <select name="brand_ids[]" id="brands" class="form-select select2-multiple" multiple data-placeholder="Select brands">
                                            @if (isset($brands))
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ in_array($brand->id, old('brand_ids', [])) ? 'selected' : '' }}>
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
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Child Categories</label>
                                        <select name="child_category_ids[]" id="child-categories" class="form-select select2-multiple" multiple data-placeholder="Select child categories">
                                            <!-- Options will be populated via AJAX -->
                                        </select>
                                        <small class="text-muted">First select a main category to see child categories</small>
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
                                    value="{{ old('_seo_title') }}" placeholder="SEO Title...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SEO Tags</label>
                                <input type="text" name="_seo_tags" class="form-control"
                                    value="{{ old('_seo_tags') }}" placeholder="SEO Tags...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SEO Description</label>
                                <textarea name="_seo_description" class="form-control" rows="3" placeholder="SEO Description...">{{ old('_seo_description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Canonical URL</label>
                                <input type="url" name="_canonical_url" class="form-control"
                                    value="{{ old('_canonical_url') }}" placeholder="https://example.com/page-url">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Schema Markup (JSON-LD)</label>
                                <textarea name="_schema_markup" class="form-control" rows="6"
                                    placeholder='{ "@context": "https://schema.org", "@type": "BlogPosting", "headline": "Blog Title", "url": "https://example.com/blog-url" }'>{{ old('_schema_markup') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Page Index</label>
                                <div class="form-check form-switch">
                                    <!-- Hidden input comes BEFORE checkbox to be overridden when checked -->
                                    <input type="hidden" name="_page_index" value="0">
                                    <input class="form-check-input" type="checkbox" id="pageIndexToggle"
                                        name="_page_index" value="1"
                                        {{ old('_page_index') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pageIndexToggle">
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>



                    <!-- Blog Content Section -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Blog Content</h5>
                            <small class="text-muted">Add detailed content for your blog post including formatting and
                                media.</small>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Blog Content</label>
                                <textarea name="content" class="form-control rich-editor" rows="15">{{ old('content') }}</textarea>
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
                                <option value="0" @if (old('active') == 0) selected @endif>Draft</option>
                                <option value="1" @if (old('active') == 1) selected @endif>Published
                                </option>
                                <option value="2" @if (old('active') == 2) selected @endif>Private
                                </option>
                                <option value="3" @if (old('active') == 3) selected @endif>Trash
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
                            <!-- Cover Image -->
                            <div class="mb-3">
                                <label class="form-label">Cover Image</label>
                                <input class="form-control" type="file" name="cover_image" accept="image/*"
                                    id="coverImageInput">
                                <small class="text-muted">Recommended: 1200x630px</small>
                                <div id="coverImagePreview" class="mt-2"></div>
                            </div>

                            <!-- Featured Images -->
                            <div class="mb-3">
                                <label class="form-label">Featured Images</label>
                                <input class="form-control" type="file" name="featured_images[]" accept="image/*"
                                    id="featuredImagesInput" multiple>
                                <div id="featuredImagesPreview" class="mt-2"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="blog-title-input">Feature Image alt Text </label>
                                <input type="text" class="form-control " name="fimage_alt_text"
                                    id="fimage_alt_text" placeholder="Please enter a Feature Image alt Text"
                                    value="{{ old('fimage_alt_text') }}" required>
                                <div class="invalid-feedback">Please enter a Feature Image alt Text.</div>
                            </div>


                            <!-- Stay on page checkbox -->
                            <div class="form-check mt-3 mb-2">
                                <input class="form-check-input" type="checkbox" id="stayOnPage" name="stay_on_page"
                                    value="1" {{ old('stay_on_page') ? 'checked' : '' }}>
                                <label class="form-check-label" for="stayOnPage">
                                    <small>Stay on this page after creating</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mb-3">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-plus-circle me-1"></i>Create Blog
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
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
    // Test Select2 availability immediately
    console.log('Testing Select2 availability...');
    console.log('jQuery version:', $.fn.jquery);
    console.log('Select2 available:', typeof $.fn.select2 !== 'undefined');
    
    if (typeof $.fn.select2 !== 'undefined') {
        console.log('Select2 version:', $.fn.select2.amd.require.version);
    }
    
    // Wait for document ready
    $(document).ready(function() {
        console.log('Document ready, initializing Select2...');
        
        // Check if Select2 is available
        if (typeof $.fn.select2 === 'undefined') {
            console.error('Select2 is not loaded! Please check if the library is included.');
            alert('Select2 is not loaded! Please check the console for details.');
            return;
        }
        
        console.log('Select2 is available, initializing...');
        
        try {
            // Initialize Select2 with basic configuration first
            $('.select2-multiple').select2({
                placeholder: 'Select options',
                allowClear: true,
                width: '100%'
            });
            
            console.log('Select2 initialized successfully on', $('.select2-multiple').length, 'elements');
        } catch (error) {
            console.error('Error initializing Select2:', error);
            alert('Error initializing Select2: ' + error.message);
        }

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

        // When main categories change
        $('#categories').on('change', function() {
            var selectedCategories = $(this).val();
            console.log('Categories changed:', selectedCategories);
            
            // Clear child categories
            $('#child-categories').empty();
            
            // Refresh Select2
            try {
                $('#child-categories').select2('destroy').select2({
                    placeholder: 'Select child categories',
                    allowClear: true,
                    width: '100%'
                });
            } catch (error) {
                console.error('Error refreshing Select2:', error);
            }

            if (selectedCategories && selectedCategories.length > 0) {
                // Load child categories for all selected main categories
                loadChildCategories(selectedCategories);
            }
        });

        function loadChildCategories(categoryIds) {
            var allChildCategories = [];
            var completedRequests = 0;

            categoryIds.forEach(function(categoryId) {
                $.ajax({
                    url: '{{ route("admin.blog.child-categories", ":id") }}'.replace(':id', categoryId),
                    type: 'GET',
                    success: function(data) {
                        allChildCategories = allChildCategories.concat(data);
                        completedRequests++;
                        
                        if (completedRequests === categoryIds.length) {
                            // Remove duplicates
                            var uniqueChildCategories = allChildCategories.filter((category, index, self) =>
                                index === self.findIndex(c => c.id === category.id)
                            );
                            
                            // Populate child categories dropdown
                            uniqueChildCategories.forEach(function(childCategory) {
                                $('#child-categories').append(
                                    '<option value="' + childCategory.id + '">' + childCategory.child_title + '</option>'
                                );
                            });
                            
                            // Refresh Select2
                            try {
                                $('#child-categories').select2('destroy').select2({
                                    placeholder: 'Select child categories',
                                    allowClear: true,
                                    width: '100%'
                                });
                            } catch (error) {
                                console.error('Error refreshing child categories Select2:', error);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading child categories:', error);
                    }
                });
            });
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

        if (!permalink) {
            $('.permalink_status').val('');
            $('.permalink_error').html('<small class="text-danger">Permalink is required.</small>');
            return;
        }

        $.ajax({
            url: '{{ route("blogs.check.permalink") }}',
            method: 'POST',
            data: {
                permalink: permalink,
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

<style>
/* Enhanced Select2 styling for better appearance */
.select2-container {
    width: 100% !important;
    font-family: inherit;
}

.select2-container--default .select2-selection--multiple {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    min-height: 38px;
    padding: 4px 8px;
    background-color: #fff;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.select2-container--default .select2-selection--multiple:hover {
    border-color: #adb5bd;
}

.select2-container--default .select2-selection--multiple:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    padding: 0;
    margin: 0;
    line-height: 1.5;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #0d6efd;
    border: 1px solid #0d6efd;
    color: #fff;
    padding: 2px 20px;
    margin: 2px 4px 2px 0;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    display: inline-block;
    max-width: calc(100% - 20px);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff;
    margin-right: 5px;
    font-weight: bold;
    cursor: pointer;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #ffcccc;
}

.select2-container--default .select2-selection--multiple .select2-selection__placeholder {
    color: #6c757d;
    font-style: italic;
}

.select2-dropdown {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    z-index: 9999;
    background-color: #fff;
}

.select2-container--default .select2-results__option {
    padding: 8px 12px;
    font-size: 0.875rem;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #0d6efd;
    color: #fff;
}

.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #e9ecef;
    color: #495057;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 6px 8px;
    font-size: 0.875rem;
}

.select2-container--default .select2-search--dropdown .select2-search__field:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Clear button styling */
.select2-container--default .select2-selection--multiple .select2-selection__clear {
    color: #6c757d;
    margin-right: 5px;
    cursor: pointer;
    font-weight: bold;
}

.select2-container--default .select2-selection--multiple .select2-selection__clear:hover {
    color: #dc3545;
}

/* Responsive design */
@media (max-width: 768px) {
    .select2-container {
        font-size: 14px;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        font-size: 12px;
        padding: 1px 6px;
        margin: 1px 2px 1px 0;
    }
    
    .select2-container--default .select2-results__option {
        padding: 6px 10px;
        font-size: 13px;
    }
}

/* Loading state */
.select2-container--default.select2-container--loading .select2-selection--multiple {
    background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20fill%3D%22%23000%22%20d%3D%22M10%203.5a.5.5%200%200%201%20.5.5v4a.5.5%200%200%201-.5.5H4a.5.5%200%200%201%200-1h3.5V4a.5.5%200%200%201%20.5-.5z%22/%3E%3C/svg%3E');
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 16px;
    padding-right: 32px;
}
</style>