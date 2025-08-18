@include('Admin.Includes.header')
@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
    use App\General\BrandClass;
@endphp
<div class="page-content">
    <div class="container-fluid">
        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="pageheader pd-t-25 pd-b-35">
                    <div class="d-flex justify-content-between">
                        <div class="clearfix">
                            <div class="pd-t-5 pd-b-5">
                                <h4 class="pd-0 mg-0 tx-20 tx-dark">{{ __('Edit Brand') }}</h4>
                            </div>

                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 m-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('Admin.Dashboard') }}"
                                            class="link-primary text-decoration-none">
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
                                        Edit
                                    </li>
                                </ol>
                            </nav>
                        </div>

                        @if (Session::has('permissions') &&
                                (in_array('Brand Create', Session::get('permissions')) ||
                                    in_array('Brand List', Session::get('permissions')) ||
                                    in_array('Brand Trash List', Session::get('permissions'))))
                            <div class="d-flex align-items-center gap-2">
                                @if (in_array('Brand Create', Session::get('permissions')))
                                    <a href="{{ url('/') }}/admin/brands/create" class="btn btn-success">
                                        <i class="bi bi-plus-circle me-1"></i> Create Brand
                                    </a>
                                @endif

                                @if (in_array('Brand List', Session::get('permissions')))
                                    <a href="{{ url('/') }}/admin/brands" class="btn btn-primary">
                                        <i class="bi bi-list-ul me-1"></i> Brand List
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
        </div>


        <div class="col-md-12 col-lg-12 px-0">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        <li>If you have already selected featured image, Please select it again!</li>
                    </ul>
                </div>
            @endif
            <form id="Brandr-create-form" method="POST" action="{{ route('brands.update', $brand->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="created_by" value="{{ $logged_in_user->id }}">
                <div class="row">
                    <div class="col-md-9">
                        <div class="card mg-b-30">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-header-title tx-13 mb-0">{{ __('Brand Details (Compulsory)') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="form-control-label active">Title: <span
                                            class="tx-danger">*</span></label>
                                    <input class="form-control form-control-lg  addTitle" type="text" name="title"
                                        placeholder="Brand Title" value="{{ old('title', $brand->title) }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label active">Permalink: <span
                                            class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span
                                                class="input-group-text input-group-text">{{ url('/') }}/meat-brands/</span>
                                        </div>
                                        <input class="form-control form-control  permalink" id="permalink"
                                            type="text" name="permalink"
                                            value="{{ old('title', $brand->permalink) }}" placeholder="brand-title"
                                            required>
                                    </div>
                                    <div class="text-danger permalink_error"></div>
                                    <input type="hidden" class="permalink_status" name="permalink_status"
                                        value="{{ old('permalink_status') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="card mg-b-30">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-header-title tx-13 mb-0">
                                            {{ __('Product SEO Details (Optional)') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="form-label">SEO Title</label>
                                    <input type="text" class="form-control" name="_seo_title"
                                        value="{{ old('_seo_title', BrandClass::getOptionValue('_seo_title', $brand->brandMeta)) }}"
                                        placeholder="SEO Title...">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">SEO Tags</label>
                                    <input type="text" name="_seo_tags"
                                        value="{{ old('_seo_tags', BrandClass::getOptionValue('_seo_tags', $brand->brandMeta)) }}"
                                        class="form-control" placeholder="SEO Tags...">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">SEO Description</label>
                                    <textarea name="_seo_description" class="form-control" rows="3" placeholder="SEO Description...">{{ old('_seo_description', BrandClass::getOptionValue('_seo_description', $brand->brandMeta)) }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Canonical URL</label>
                                    <input type="url" name="_canonical_url" class="form-control"
                                        value="{{ old('_canonical_url', BrandClass::getOptionValue('_canonical_url', $brand->brandMeta)) }}"
                                        placeholder="https://example.com/page-url">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Schema Markup (JSON-LD)</label>
                                    <textarea name="_schema_markup" class="form-control" rows="6"
                                        placeholder='{
                                "@context": "https://schema.org",
                                "@type": "WebPage",
                                "name": "Page Title",
                                "url": "https://example.com/page-url"
                            }'>{{ old('_schema_markup', BrandClass::getOptionValue('_schema_markup', $brand->brandMeta)) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Page Index</label>
                                    <div class="form-check form-switch">
                                        <!-- Hidden input comes BEFORE checkbox to be overridden when checked -->
                                        <input type="hidden" name="_page_index" value="0">

                                        <!-- The checkbox sends 1 when checked, and the hidden input sends 0 when unchecked -->
                                        <input class="form-check-input" type="checkbox" id="pageIndexToggle"
                                            name="_page_index" value="1"
                                            {{ old('_page_index', BrandClass::getOptionValue('_page_index', $brand->brandMeta)) == '1' || old('_page_index', BrandClass::getOptionValue('_page_index', $brand->brandMeta)) == 1 ? 'checked' : '' }}>

                                    </div>
                                </div>



                            </div>

                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Brand Content</h5>
                                <small class="text-muted">Add detailed content about the brand including images,
                                    formatting, and media.</small>
                            </div>
                            @php
                                $description = old(
                                    '_description',
                                    BrandClass::getOptionValue('_description', $brand->brandMeta),
                                );
                                $editorImage = BrandClass::getOptionValue('_editor_image', $brand->brandMeta);
                                $imageTag = $editorImage
                                    ? "<p><img src='" . asset($editorImage) . "' class='img-fluid'></p>"
                                    : '';
                            @endphp

                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Brand Description</label>
                                    <textarea name="_description" class="form-control rich-editor" rows="10">
                                    {!! $description . $imageTag !!}
                                </textarea>
                                </div>
                            </div>


                        </div>

                        <div class="card mg-b-30">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-header-title tx-13 mb-0">{{ __('Brand Actions') }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>Created By: @php print(BrandClass::CreateBy($brand->created_by)); @endphp</p>
                                <p>Created Date:
                                    {{ $brand->created_at ? Carbon::parse($brand->created_at)->timezone('Australia/Sydney')->format('j F Y, g:i A T') : '' }}
                                </p>
                                <p>Updated Date:
                                    {{ $brand->updated_at ? Carbon::parse($brand->updated_at)->timezone('Australia/Sydney')->format('j F Y, g:i A T') : '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mg-b-30">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-header-title tx-13 mb-0">{{ __('More Details (Required)') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-control-label active">Active Status: </label>
                                    <select class="form-select form-select2-demo" name="active">
                                        <option value="0" @if (old('active', $brand->active) == 0) selected @endif>Draft
                                        </option>
                                        <option value="1" @if (old('active', $brand->active) == 1) selected @endif>Public
                                        </option>
                                        <option value="2" @if (old('active', $brand->active) == 2) selected @endif>
                                            Private
                                        </option>
                                        <option value="3" @if (old('active', $brand->active) == 3) selected @endif>Trash
                                        </option>
                                    </select>
                                </div>
                                <div class="check mt-3 mb-2">
                                    <input type="checkbox" id="stayOnPage" name="stay_on_page" value="1"
                                        {{ old('stay_on_page') ? 'checked' : '' }}>
                                    <small class="fs-10">If you want to stay on this page please check the box</small>
                                </div>
                                <input class="btn btn-success float-right m-2" type="submit" value="update">
                                <input class="btn btn-secondary float-right mx-1" type="button"
                                    onclick="window.location = '{{ url('/') }}/admin/brands'" value="Cancel">
                            </div>
                        </div>

                        <div class="card mg-b-30">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-header-title tx-13 mb-0">{{ __('Featured Media (Optional)') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="form-control-label active">Brand Logo Image: </label>
                                    <input class="form-control" name="logo_image" accept="image/*" type="file"
                                        id="featuredimageInput">
                                    <div class="text-secondary"><small>Preferred Dimension: (800px) x (500px)</small>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-control-label active">Logo Alt Text: </label>
                                    <input class="form-control" name="_alt" type="text"
                                        value="{{ old('_seo_title', BrandClass::getOptionValue('_alt', $brand->brandMeta)) }}">
                                </div>
                                <div id="featuredimagePreview">
                                    @if ($brand->logo_image != '')
                                        <img src="{{ url('/') }}/{{ $brand->logo_image }}" width="100%"
                                            height="auto">
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label active">Cover Image: </label>
                                    <input class="form-control" name="cover_image" accept="image/*" type="file"
                                        id="Wallpaperimage">
                                    <div class="text-secondary"><small>Preferred Dimension: (1920px) x (800px)</small>
                                    </div>
                                </div>
                                <div id="WallpaperimagePreview">
                                    @if ($brand->cover_image != '')
                                        <img src="{{ url('/') }}/{{ $brand->cover_image }}" width="100%"
                                            height="auto">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--/ Page Inner End -->




@include('Admin.Includes.footer')
<script>
    $('.addTitle').on('input', function() {
        var title = $(this).val();
        var sku = title.replace(/\s+/g, '-').toLowerCase();
        $('.permalink').val(sku);
        validate_permalink();
    });
    $('.permalink').on('input', function() {
        validate_permalink();
    });
    // $('#seo_tags').tagsinput({
    //     tagClass: 'badge badge-dark'
    // });



    // Get references to the input field and the div for image preview
    var imageInput = document.getElementById('featuredimageInput');
    var imagePreview = document.getElementById('featuredimagePreview');

    // Get references to the input field and the div for image preview
    var imageInput1 = document.getElementById('Wallpaperimage');
    var imagePreview1 = document.getElementById('WallpaperimagePreview');

    imageInput.addEventListener('change', function(event) {
        var selectedFile = event.target.files[0];
        if (selectedFile) {
            var reader = new FileReader();
            reader.onload = function(event) {
                var imageElement = document.createElement('img');
                imageElement.src = event.target.result;
                imageElement.style.width = '100%';
                imageElement.style.height = 'auto';
                imagePreview.innerHTML = '';
                imagePreview.appendChild(imageElement);
            }
            reader.readAsDataURL(selectedFile);
        } else {
            imagePreview.innerHTML = '';
        }
    });

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
