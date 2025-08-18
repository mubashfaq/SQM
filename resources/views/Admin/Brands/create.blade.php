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
                        <h4 class="mb-1">{{ __('Brand Create') }}</h4>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/') }}/admin/brands"
                                        class="link-primary text-decoration-none">
                                        Brand
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Brand Create
                                </li>
                            </ol>
                        </nav>
                    </div>

                    @if (Session::has('permissions') &&
                            (in_array('Brand Create', Session::get('permissions')) ||
                                in_array('Brand Trash List', Session::get('permissions'))))
                        <div class="d-flex gap-2">
                            @if (in_array('Brand Create', Session::get('permissions')))
                                <a href="{{ url('/') }}/admin/brands" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Brand List
                                </a>
                            @endif
                            @if (in_array('Brand Trash List', Session::get('permissions')))
                                <a href="{{ url('/') }}/admin/brands/list/trash" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i> Trash List
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>


        <form id="brand-create-form" method="POST" action="{{ route('brands.store') }}" enctype="multipart/form-data"
            autocomplete="off" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="created_by" value="{{ $logged_in_user->id }}">
            <div class="row">
                <div class="col-xl-9 col-lg-8">
                    <!-- Brand Details -->
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title rounded-circle bg-light text-primary fs-20">
                                        <i class="bi bi-tags"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">Brand Details</h5>
                                    <p class="text-muted mb-0">Enter essential brand information below.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Title -->
                            <div class="mb-3">
                                <label class="form-label" for="brand-title-input">Brand Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control addTitle" name="title"
                                    id="brand-title-input" placeholder="Brand Title" value="{{ old('title') }}"
                                    required>
                                <div class="invalid-feedback">Please enter a brand title.</div>
                            </div>

                            <!-- Permalink -->
                            <div class="form-group">
                                <label class="form-control-label active">Permalink: <span
                                        class="tx-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend custom-permalink">
                                        <span
                                            class="input-group-text input-group-text">{{ url('/') }}/meat-brands/</span>
                                    </div>
                                    <input class="form-control form-control restrict-input-alphabets permalink"
                                        id="permalink" type="text" name="permalink" value="{{ old('permalink') }}"
                                        placeholder="brand-title" required>
                                </div>
                                <div class="text-danger permalink_error"></div>
                                <input type="hidden" class="permalink_status" name="permalink_status"
                                    value="{{ old('permalink_status') }}" required>
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
                                                placeholder='{
                                "@context": "https://schema.org",
                                "@type": "WebPage",
                                "name": "Page Title",
                                "url": "https://example.com/page-url"
                                }'>{{ old('_schema_markup') }}</textarea>
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Child Category Page Index</label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="_page_index" value="0">
                                    <input class="form-check-input" type="checkbox" id="pageIndexToggle"
                                        name="_page_index" value="1"
                                        {{ old('_page_index') == '1' ? 'checked' : '' }}>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Brand Content Section -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Brand Content</h5>
                            <small class="text-muted">Add detailed content about the brand including images,
                                formatting,
                                and media.</small>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Brand Description</label>
                                <textarea name="_description" class="form-control rich-editor" rows="10">{{ old('_description') }}</textarea>
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
                            <label for="brand-status" class="form-label">Active Status</label>
                            <select name="active" class="form-select" id="brand-status">
                                <option value="0" @if (old('active') == 0) selected @endif>Draft</option>
                                <option value="1" @if (old('active') == 1) selected @endif>Public
                                </option>
                                <option value="2" @if (old('active') == 2) selected @endif>Private
                                </option>
                                <option value="3" @if (old('active') == 3) selected @endif>Trash</option>
                            </select>
                        </div>
                    </div>

                    <!-- Media -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Brand Images</h5>
                        </div>
                        <div class="card-body">
                            <!-- Logo -->
                            <div class="mb-3">
                                <label class="form-label">Brand Logo Image</label>
                                <input class="form-control" type="file" name="logo_image" accept="image/*"
                                    id="featuredimageInput">
                                <small class="text-muted">Recommended: 800x500px</small>
                                <div id="featuredimagePreview" class="mt-2"></div>
                            </div>

                            <!-- Logo Alt Text -->
                            <div class="mb-3">
                                <label class="form-label">Logo Alt Text</label>
                                <input class="form-control" type="text" name="_alt"
                                    value="{{ old('_alt') }}">
                            </div>

                            <!-- Cover -->
                            <div class="mb-3">
                                <label class="form-label">Cover Image</label>
                                <input class="form-control" type="file" name="cover_image" accept="image/*"
                                    id="Wallpaperimage">
                                <small class="text-muted">Recommended: 1920x800px</small>
                                <div id="WallpaperimagePreview" class="mt-2"></div>
                            </div>
                            <div class="check mt-3 mb-2">
                                <input type="checkbox" id="stayOnPage" name="stay_on_page" value="1" {{ old('stay_on_page') ? 'checked' : '' }}>
                                <small class="fs-10">If you want to stay on this page please check the box</small>
                            </div>
                        </div>
                        
                    </div>



                    <div class="text-end mb-3">
                        <button type="submit" class="btn btn-success w-100">Create Brand</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    // First, make sure jQuery and the Bootstrap Tags Input plugin are loaded
    // Add these at the top of your script or in your HTML before your script

    // Wait for document ready
    $(document).ready(function() {
        // Check if tagsinput plugin is available before using it
        if ($.fn.tagsinput) {
            // $('#seo_tags').tagsinput({
            //     tagClass: 'badge badge-dark'
            // });
        } else {
            console.error("Bootstrap Tags Input plugin is not loaded. Please include the library.");
        }

        // Title to permalink conversion
        $('.addTitle').on('input', function() {
            var title = $(this).val();
            var sku = title.replace(/\s+/g, '-').toLowerCase();
            $('.permalink').val(sku);
            validate_permalink();
        });

        $('.permalink').on('input', function() {
            validate_permalink();
        });

        // Image preview functionality
        const logoInput = document.getElementById('featuredimageInput');
        const logoPreview = document.getElementById('featuredimagePreview');
        const coverInput = document.getElementById('Wallpaperimage');
        const coverPreview = document.getElementById('WallpaperimagePreview');

        // Only add event listeners if elements exist
        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', function(event) {
                console.log("Logo file selected:", event.target.files[0]);
                showPreviewImage(event.target.files[0], logoPreview);
            });
        }

        if (coverInput && coverPreview) {
            coverInput.addEventListener('change', function(event) {
                console.log("Cover file selected:", event.target.files[0]);
                showPreviewImage(event.target.files[0], coverPreview);
            });
        }

        // Fix for imageInput1 - make sure this element exists
        const imageInput1 = document.getElementById('imageInput1');
        const imagePreview1 = document.getElementById('imagePreview1');

        if (imageInput1 && imagePreview1) {
            imageInput1.addEventListener('change', function(event) {
                var selectedFile = event.target.files[0];
                if (selectedFile) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        var imageElement = document.createElement('img');
                        imageElement.src = event.target.result;
                        imageElement.style.width = '100%';
                        imageElement.style.height = 'auto';
                        imagePreview1.innerHTML = '';
                        imagePreview1.appendChild(imageElement);
                    }
                    reader.readAsDataURL(selectedFile);
                } else {
                    imagePreview1.innerHTML = '';
                }
            });
        }

        function showPreviewImage(file, container) {
            if (!file) {
                console.log("No file provided");
                container.innerHTML = '';
                return;
            }

            console.log("Processing file:", file.name);

            const reader = new FileReader();

            reader.onload = function(e) {
                console.log("File read complete");
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100%';
                img.style.maxHeight = '250px';
                img.style.objectFit = 'contain';
                img.className = "rounded border";

                container.innerHTML = '';
                container.appendChild(img);
                console.log("Image added to preview");
            };

            reader.onerror = function(e) {
                console.error("Error reading file:", e);
                container.innerHTML = '<div class="alert alert-danger">Error loading image</div>';
            };

            reader.readAsDataURL(file);
        }
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
    }, 5000);

    // Permalink validation function
    function validate_permalink() {
        var permalink = $('#permalink').val();

        $.ajax({
            url: '{{ route('Admin.Brand.CheckPermalink') }}', // Replace with the actual route to handle the permalink check
            method: 'POST',
            data: {
                permalink: permalink,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                $('.permalink_status').val('1');
                $('.permalink_error').html('');
            },
            error: function(xhr, status, error) {
                console.log(xhr);
                $('.permalink_status').val('');
                $('.permalink_error').html(
                    '<small>This permalink already in used or field is empty!, Please change the permalink.</small>'
                );
            }
        });
    }
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
