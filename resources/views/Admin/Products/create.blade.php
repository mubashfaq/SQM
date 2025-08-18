@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
@endphp
<style>
       .img-preview {
        max-width: 200px;
        height: auto;
        margin: 10px;
    }


    .btn-remove {
        position: absolute;
        top: 10px;
        right: 0px;
        z-index: 1;
    }

    .preview-wrapper {
        position: relative;
        display: inline-block;
        border: 2px solid transparent;
        margin: 5px;
        width: 25%;
    }

    .preview-wrapper img{
        max-width: 100%;
        height: 270px;
        object-fit: cover;
    }

    .preview-wrapper.selected {
        border-color: #007bff;
    }

    .radio-button {
        position: absolute;
        top: 5px;
        left: 5px;
    }


    .noselect {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Custom Multi-select Styles */
    .custom-multiselect-container {
        position: relative;
        width: 100%;
        font-family: inherit;
        font-size: 14px;
    }

    .custom-multiselect-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        border: 1px solid #132846; 
        border-radius: 4px;
        cursor: pointer;
        min-height: 38px;
    }

    .custom-multiselect-selected-display {
        flex-grow: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .custom-multiselect-arrow {
        margin-left: 5px;
        transition: transform 0.2s;
    }

    .custom-multiselect-dropdown {
        position: absolute;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        display: none;
        margin-top: 2px;
    }

    html[data-bs-theme="light"] .custom-multiselect-dropdown {
    background-color: white;
    border: 1px solid #132846;
    }

    /* Styles for dark theme */
    html[data-bs-theme="dark"] .custom-multiselect-dropdown {
        background-color: #132846;
        border: 1px solid white;
    }

    html[data-bs-theme="light"] .custom-multiselect-search {
        border: 1px solid #ddd;
    }

    html[data-bs-theme="light"] .custom-multiselect-search:focus-visible {
        border: 1px solid #ddd !important;
        outline: none !important;
    }

    html[data-bs-theme="dark"] .custom-multiselect-search {
        background-color: #132846;
        border: 1px solid white;
    }
    html[data-bs-theme="dark"] .custom-multiselect-search:focus-visible {
        outline: none !important;
    }

    .custom-multiselect-search-container {
        padding: 8px;
        position: sticky;
        top: 0;
    }

    .custom-multiselect-search {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .custom-multiselect-items {
        padding: 5px 0;
    }

    .custom-multiselect-optgroup {
        margin-bottom: 8px;
    }

    .custom-multiselect-optgroup-label {
        padding: 5px 12px;
        font-weight: bold;
        color: #666;
    }

    .custom-multiselect-option {
        padding: 5px 12px 5px 24px;
    }

    .custom-multiselect-option label {
        display: flex;
        align-items: center;
        width: 100%;
        margin: 0;
        cursor: pointer;
    }

    .custom-multiselect-option input[type="checkbox"] {
        margin-right: 8px;
    }


    /* For showing dropdown */
    .custom-multiselect-container.active .custom-multiselect-dropdown {
        display: block;
    }

    .custom-multiselect-container.active .custom-multiselect-arrow {
        transform: rotate(180deg);
    }

    /* Selected item badges */
    .selected-item-badge {
        display: inline-block;
        padding: 2px 6px;
        margin: 2px;
        border-radius: 3px;
        font-size: 12px;
    }

    .selected-item-badge .remove-item {
        margin-left: 5px;
        cursor: pointer;
        color: #666;
    }
</style>

<div class="page-content">
    <div class="container-fluid">

        <div class="page-inner">
            {{-- <div class="card shadow-sm mb-4">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fs-4 mb-1">{{ __('Create Product') }}</h4>
                            <nav class="d-flex align-items-center fw-semibold">
                                <a href="http://127.0.0.1:8000/admin" class="text-primary text-decoration-none me-1">
                                    <i class="fa-solid fa-house me-1"></i>Dashboard
                                </a>
                                <i class="fa-solid fa-angle-right text-muted mx-1"></i>
                            
                                <a href="#" class="text-primary text-decoration-none me-1">
                                    <i class="fa-solid fa-shopping-bag me-1"></i>Products
                                </a>
                                <i class="fa-solid fa-angle-right text-active mx-1"></i>
                                <span class="">Create</span>
                            </nav>                        
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if (Session::has('permissions') && in_array('Product List', Session::get('permissions')))
                        <a href="{{ url('/') }}/admin/products" class="btn btn-primary">
                            <i class="bi bi-list me-1"></i> Product List
                        </a>
                    @endif
                    @if (Session::has('permissions') && in_array('Product Trash List', Session::get('permissions')))
                        <a href="{{ url('/') }}/admin/products/list/trash" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> Trash List
                        </a>
                    @endif
                </div>
            </div> --}}
            <!--================================-->
            <!-- Breadcrumb Start -->
            <!--================================-->
            <div class="card shadow-sm mb-4 p-3 pageheader pd-t-25 pd-b-35">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <div class="clearfix">
                        <div class="pd-t-5 pd-b-5">
                            <h4 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Create Product') }}</h4>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0">
                                <li class="breadcrumb-item">
                                    <a class="link-primary text-decoration-none" href="{{ route('Admin.Dashboard') }}">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a class="link-primary text-decoration-none" href="{{ route('products.index') }}">
                                        Products
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Create
                                </li>
                            </ol>
                        </nav>
                        
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        @if (Session::has('permissions') && in_array('Product List', Session::get('permissions')))
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fa-solid fa-list-ul me-1"></i> Product List
                            </a>
                        @endif
                        @if (Session::has('permissions') && in_array('Product Trash List', Session::get('permissions')))
                            <a href="{{ route('Admin.Product.Trash') }}" class="btn btn-danger">
                                <i class="fa-solid fa-trash me-1"></i>Trash List
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <!--/ Breadcrumb End -->
            <!--================================-->
            <!--================================-->
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
                <form id="product-create-form" method="POST" action="{{ route('products.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="created_by" value="{{ $logged_in_user->id }}">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('Product Details (Compulsory)') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">Title: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control addTitle" type="text"
                                            name="title" placeholder="Meat Name" value="{{ old('title') }}"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label active">Permalink: <span
                                                class="tx-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span
                                                    class="input-group-text input-group-text">{{ url('/') }}/product/</span>
                                            </div>
                                            <input class="form-control form-control  permalink" id="permalink"
                                                type="text" name="permalink" value="{{ old('permalink') }}"
                                                placeholder="meat-name" required>
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
                                                {{ __('Product Details (Optional)') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">Short Description: </label>
                                        <textarea class="form-control" id="short_description" name="short_description" placeholder="Short Description...">{{ old('short_description') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label active">Long Description: </label>
                                        <textarea class="form-control" id="long_description" name="long_description" placeholder="Long Description..."> {{ old('long_description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('Gallery Media (Optional)') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body gallery-media">

                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" id="imageInput"
                                            name="gallery_media[]" multiple accept="image/*"
                                            placeholder="Select Images">
                                        <button type="button" class="btn btn-primary"
                                            onclick="uploadImages()">Upload</button>
                                    </div>
                                    <input type="hidden" name="variations_count[]" value="variation">
                                    <div class="row mt-3" id="imagePreviewContainer">

                                        @if ($errors->any())
                                            @php
                                                $product_variations = DB::table('product_temp_variation')
                                                    ->where('created_by', $logged_in_user->id)
                                                    ->get();
                                            @endphp
                                            @if (sizeof($product_variations) > 0)
                                                @if ($product->gallery_media != '')

                                                    @php
                                                        $imagePaths = explode(',', $product_variations->gallery_media);
                                                    @endphp
                                                    @foreach ($imagePaths as $imagePath)
                                                        <img class="m-1" src="{{ url('/') . '/' . $imagePath }}"
                                                            width="25%" height="25%">
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endif
                                    </div>

                                    <div class="response_section"> </div>
                                    <small class="text-secondary">Select a featured image by clicking on an
                                        image</small>
                                </div>

                            </div>
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('Product SEO Details (Optional)') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">SEO Title: </label>
                                        <input type="text" class="form-control" name="_seo_title"
                                            value="{{ old('_seo_title') }}" placeholder="SEO Title...">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">SEO Tags: </label>
                                        <input type="text" name="_seo_tags" value="{{ old('_seo_tags') }}"
                                            class="form-control rounded-0" id="seo_tags">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">SEO Description: </label>
                                        <textarea class="form-control" name="_seo_description" placeholder="SEO Description...">{{ old('_seo_description') }}</textarea>
                                    </div>
                                    <!-- <div class="form-group mb-3">
                                        <label class="form-control-label active">Canonical URL: </label>
                                        <input type="url" class="form-control" name="_canonical_url"
                                            value="{{ old('_canonical_url') }}" placeholder="Canonical URL..."
                                            pattern="https?://.*"
                                            title="Enter a valid URL (e.g., https://example.com)">
                                    </div> -->

                                    <!-- <div class="form-group">
                                        <label class="form-control-label active">Schema Markup (JSON-LD): </label>
                                        <textarea class="form-control" name="_schema_markup" placeholder="Enter JSON-LD Schema Markup for the page">{{ old('_schema_markup') }}</textarea>
                                        <small class="form-text text-muted">Ensure the JSON-LD is valid.</small>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('Shipping Areas (Required)') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-control-label active">Select Shipping Area: </label>
                                        <div class="custom-multiselect-container">
                                            <div class="custom-multiselect-header">
                                                <div class="custom-multiselect-selected-display">Select areas...</div>
                                                <div class="custom-multiselect-arrow">▼</div>
                                            </div>
                                            <div class="custom-multiselect-dropdown">
                                                <div class="custom-multiselect-search-container">
                                                    <input type="text" class="custom-multiselect-search"
                                                        placeholder="Search...">
                                                </div>
                                                <div class="custom-multiselect-items">
                                                    <div class="custom-multiselect-optgroup">
                                                        <div class="custom-multiselect-optgroup-label">Over the Globe
                                                        </div>
                                                        <div class="custom-multiselect-option">
                                                            <label>
                                                                <input type="checkbox" name="area_group_id[]"
                                                                    value="0" data-label="All Countries">
                                                                <span>All Countries</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @foreach ($areagroups as $areagroup)
                                                        <div class="custom-multiselect-optgroup">
                                                            <div class="custom-multiselect-optgroup-label">
                                                                {{ $areagroup->title }}</div>
                                                            @foreach ($areagroup->groupsMeta as $groupmeta)
                                                                <div class="custom-multiselect-option">
                                                                    <label>
                                                                        <input type="checkbox" name="area_group_id[]"
                                                                            value="{{ $groupmeta->postcode }}"
                                                                            data-label="{{ $groupmeta->title }}">
                                                                        <span>{{ $groupmeta->title }}</span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('More Details (Optional)') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active"><i class=""></i>Brand:</label>
                                        <select class="form-select select2-demo" name="brand_id" style="width: 100%">
                                                <option selected>Select Brand</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        @if (old('brand_type') == $brand->id) selected @endif>
                                                        {{ $brand->title }}
                                                    </option>
                                                @endforeach
                                            </select> 
                                    </div> 
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active"><i class=""></i>Discount: </label>
                                        <select class="form-select form-select2-demo" name="discount_id">
                                            <option selected>Select Discount On Product</option>
                                            @foreach ($discounts as $discount)
                                                <option value="{{ $discount->id }}"
                                                    @if (old('discount_id') == $discount->id) selected @endif>
                                                    {{ $discount->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                       <label class="form-control-label active">GST Included:</label>
                                       <select class="form-select form-select2-demo " id="_gst" name="_gst">
                                           <option selected>Add GST On Product</option>
                                           <option value="1" @if (old('_gst') == 1) selected @endif>True
                                           </option>
                                           <option value="0" @if (old('_gst') == 2) selected @endif>false
                                           </option>
                                       </select>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active"><i class=""></i>Product Type:</label>
                                        <select name="product_type" class="form-select "
                                        id="active_check" required>
                                        <option selected>Select Product Type</option>
                                            @foreach ($producttypes as $producttype)
                                                <option value="{{ $producttype->id }}"
                                                    @if (old('product_type') == $producttype->id) selected @endif>
                                                    {{ $producttype->title }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                        
                                        
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active"><i class=""></i>Product Varient: </label>
                                        <select class="form-select" id="variant" name="variant">
                                            <option value="1" @if (old('product_type') == 1) selected @endif>
                                                Individual</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">Product Stock: </label>
                                        <select class="form-select form-select2-demo" name="_stock_status">
                                    <option value="in_stock" {{ old('_stock_status') === 'in_stock' ? 'selected' : '' }}>
                                        In Stock
                                    </option>
                                    <option value="out_stock" {{ old('_stock_status') === 'out_stock' ? 'selected' : '' }}>
                                        Out of Stock
                                    </option>
                                </select>
                                    </div>
                                    <div class="form-group regular_price_Section mb-3">
                                        <label class="form-control-label active">Buy Price: </label>
                                        <input class="form-control" type="number" min="0"
                                            value="{{ old('_buy_price', 0) }}" step="0.1" name="_buy_price"
                                            required>
                                    </div>
                                    <div class="form-group regular_price_Section mb-3">
                                        <label class="form-control-label active">Price: </label>
                                        <input class="form-control" type="number" min="0"
                                            value="{{ old('_price', 0) }}" step="0.0001" name="_price">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">SKU:(Required) </label>
                                        <input class="form-control" type="text" value="{{ old('sku') }}"
                                            name="sku" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">Type:</label>
                                        <div class="mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="_is_weight" id="weight" value="1" checked>
                                                <label class="form-check-label" for="weight">
                                                    Weight
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="_is_weight" id="item" value="0">
                                                <label class="form-check-label" for="item">
                                                    Item
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 d-none">
                                        <label class="form-control-label active">Weight: </label>
                                        <input class="form-control" type="text" value="1"
                                            name="_weight">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">UPC/Barcode:(Required) </label>
                                        <input class="form-control" type="text" value="{{ old('_upc') }}"
                                            name="_upc"  >
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label active"> <i class=""></i>Active Status: </label>
                                        <select class="form-select" name="active">
                                            <option value="0" @if (old('active') == 0) selected @endif>
                                                Draft
                                            </option>
                                            <option value="1" @if (old('active') == 1) selected @endif>
                                                Public
                                            </option>
                                            <option value="2" @if (old('active') == 2) selected @endif>
                                                Private
                                            </option>
                                            <option value="3" @if (old('active') == 3) selected @endif>
                                                Trash
                                            </option>
                                        </select>
                                    </div>

                                    <hr>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="_virtual"
                                            value="1" id="customCheckvirtual"
                                            @if (old('_virtual') == 1) checked @endif>
                                        <label class="custom-control-label" for="customCheckvirtual">Is this a
                                            featured
                                            Product?</label>
                                    </div>
                                    <hr>
                                    <input class="btn btn-success float-right" type="submit" value="Submit">
                                </div>
                            </div>

                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('Featured Media (Optional)') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">Featured Image: </label>
                                        <input class="form-control" name="featured_image" accept="image/*"
                                            type="file" id="featuredimageInput">
                                        <div class="text-secondary"><small>Preferred Dimension: (720px) x
                                        (800px)</small></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label active">Featured Image Alt: </label>
                                        <input class="form-control" name="_alt" type="text" value="{{old('_alt')}}">
                                    </div>
                                    <div id="featuredimagePreview"></div>
                                </div>
                            </div>
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('Product Category (Optional)') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                               <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">Main Category: </label>
                                        <select class="form-control" id="main_category" name="category_id" required>
                                            <option value="" {{ old('category_id') == '' ? 'selected' : '' }}>Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                    <!-- <div class="form-group mb-3">
                                        <label class="form-control-label active">Child Category: </label>
                                        <select class="form-control" id="child_category" name="child_category">
                                            <option value="" selected >Select Child Category</option>
                                            @forEach ($childcats as $childCat)
                                                <option value="{{$childCat->id}}">{{$childCat->child_title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label active">Sub Child Category: </label>
                                        <select class="form-control" id="grand_child_category"
                                            name="grand_child_category">
                                            <option value="" selected >Select Sub Child Category</option>
                                            @forEach ($grandcats as $GrandChildCategory)
                                                <option value="{{$GrandChildCategory->id}}">{{$GrandChildCategory->grand_title}}</option>
                                            @endforeach
                                        </select>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!--/  Order Active dataTable End -->
            <!--================================-->
        </div>

    </div>
</div>
<script>
    $(document).ready(function(){
   $('#short_description').summernote({
        placeholder: 'Short Description...',
        tabsize: 2,
        height: 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });
    $('#long_description').summernote({
        placeholder: 'Long Description...',
        tabsize: 2,
        height: 250,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['link']],
            ['view', ['codeview']],
        ],
        popover: {
            image: [
                ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                ['float', ['floatLeft', 'floatRight', 'floatNone']],
                ['remove', ['removeMedia']]
            ],
            link: [
                ['link', ['unlink']]
            ],
            air: [
                ['color', ['color']],
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']]
            ]
        }

    });
    });
</script>
<script>
    document.getElementById('featuredimageInput').addEventListener('change', function (event) {
        const preview = document.getElementById('featuredimagePreview');
        preview.innerHTML = '';
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Preview Image';
                img.style.maxWidth = '200px';
                img.style.marginTop = '10px';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            preview.textContent = 'Selected file is not an image.';
        }
    });
</script>

<script>

// Permalink generator script
document.addEventListener('DOMContentLoaded', function() {
    // Get the title and permalink input elements
    const titleInput = document.querySelector('.addTitle');
    const permalinkInput = document.querySelector('#permalink');
    const permalinkStatus = document.querySelector('.permalink_status');
    const permalinkError = document.querySelector('.permalink_error');
    
    // Flag to track if the permalink has been manually edited
    let permalinkManuallyEdited = false;
    
    // Function to generate permalink from title
    function generatePermalink(title) {
        if (!title) return '';
        
        return title
            .toLowerCase()                      // Convert to lowercase
            .trim()                             // Remove leading and trailing whitespace
            .replace(/[^\w\s-]/g, '')           // Remove special characters
            .replace(/\s+/g, '-')               // Replace spaces with hyphens
            .replace(/-+/g, '-')                // Replace multiple hyphens with single hyphen
            .replace(/^-+|-+$/g, '');           // Remove leading and trailing hyphens
    }
    
    // Listen for changes in the title input if permalink hasn't been manually edited
    titleInput.addEventListener('input', function() {
        if (!permalinkManuallyEdited) {
            const generatedPermalink = generatePermalink(this.value);
            permalinkInput.value = generatedPermalink;
            
            // You might want to validate the permalink here
            validatePermalink(generatedPermalink);
        }
    });
    
    // Set a flag when permalink is manually edited
    permalinkInput.addEventListener('input', function() {
        permalinkManuallyEdited = true;
        
        // Validate the manually entered permalink
        validatePermalink(this.value);
    });
    
    // Validate permalink (optional - you can customize this function)
    function validatePermalink(permalink) {
        // Reset error message
        permalinkError.textContent = '';
        
        // Example validation
        if (!permalink) {
            permalinkError.textContent = 'Permalink cannot be empty';
            permalinkStatus.value = 0;
            return false;
        }
        
        // More validation could be added here
        // For example, check if permalink already exists using AJAX
        
        permalinkStatus.value = 1;
        return true;
    }
    
    // If there's already a title but no permalink, generate one on page load
    if (titleInput.value && !permalinkInput.value) {
        permalinkInput.value = generatePermalink(titleInput.value);
    }
});


    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the custom multiselect
        const multiselectContainer = document.querySelector('.custom-multiselect-container');
        const selectedDisplay = document.querySelector('.custom-multiselect-selected-display');
        const dropdown = document.querySelector('.custom-multiselect-dropdown');
        const searchInput = document.querySelector('.custom-multiselect-search');
        const options = document.querySelectorAll('.custom-multiselect-option input[type="checkbox"]');
        const header = document.querySelector('.custom-multiselect-header');

        let selectedItems = [];

        // Toggle dropdown on header click
        header.addEventListener('click', function(e) {
            multiselectContainer.classList.toggle('active');
            if (multiselectContainer.classList.contains('active')) {
                searchInput.focus();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!multiselectContainer.contains(e.target)) {
                multiselectContainer.classList.remove('active');
            }
        });

        // Search functionality
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const optgroups = document.querySelectorAll('.custom-multiselect-optgroup');

            optgroups.forEach(optgroup => {
                let showOptgroup = false;
                const optgroupLabel = optgroup.querySelector(
                    '.custom-multiselect-optgroup-label');
                const options = optgroup.querySelectorAll('.custom-multiselect-option');

                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        option.style.display = '';
                        showOptgroup = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                if (showOptgroup) {
                    optgroup.style.display = '';
                    optgroupLabel.style.display = '';
                } else {
                    optgroup.style.display = 'none';
                }
            });
        });

        // Handle checkbox changes
        options.forEach(option => {
            option.addEventListener('change', function(e) {
                e.stopPropagation();
                updateSelectedItems();
            });
        });

        // Update the selected items display
        function updateSelectedItems() {
            selectedItems = [];
            options.forEach(option => {
                if (option.checked) {
                    selectedItems.push({
                        value: option.value,
                        label: option.getAttribute('data-label')
                    });
                }
            });

            if (selectedItems.length === 0) {
                selectedDisplay.textContent = 'Select areas...';
            } else if (selectedItems.length <= 2) {
                // Show selected items directly
                selectedDisplay.innerHTML = selectedItems.map(item => {
                    return `<span class="selected-item-badge">${item.label} <span class="remove-item" data-value="${item.value}">×</span></span>`;
                }).join(' ');

                // Add remove event listeners
                const removeButtons = selectedDisplay.querySelectorAll('.remove-item');
                removeButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const value = this.getAttribute('data-value');
                        const checkbox = document.querySelector(
                            `.custom-multiselect-option input[value="${value}"]`);
                        if (checkbox) {
                            checkbox.checked = false;
                            updateSelectedItems();
                        }
                    });
                });
            } else {
                // Just show the count
                selectedDisplay.textContent = `${selectedItems.length} areas selected`;
            }
        }

        // Initialize the display
        updateSelectedItems();
    });



    const mainCategorySelect = document.getElementById('main_category');
    const childCategorySelect = document.getElementById('child_category');
    const grandChildCategorySelect = document.getElementById('grand_child_category');

    const childCategoriesData = {!! json_encode($childcats) !!};
    const grandChildCategoriesData = {!! json_encode($grandcats) !!};

    function updateChildCategories(mainCategoryId) {
        // childCategorySelect.innerHTML = '';

        const filteredChildCategories = childCategoriesData.filter((childCategory) => {
            return childCategory.main_parent_category === mainCategoryId;
        });

        filteredChildCategories.forEach((childCategory) => {
            const option = document.createElement('option');
            option.value = childCategory.id;
            option.textContent = childCategory.child_title;
            childCategorySelect.appendChild(option);
        });

        childCategorySelect.dispatchEvent(new Event('change'));
    }

    function updateGrandChildCategories(childCategoryId) {
        // grandChildCategorySelect.innerHTML = '';

        const filteredGrandChildCategories = grandChildCategoriesData.filter((grandChildCategory) => {
            return grandChildCategory.mid_parent_category === childCategoryId;
        });

        filteredGrandChildCategories.forEach((grandChildCategory) => {
            const option = document.createElement('option');
            option.value = grandChildCategory.id;
            option.textContent = grandChildCategory.grand_title;
            grandChildCategorySelect.appendChild(option);
        });
    }

    mainCategorySelect.addEventListener('change', (event) => {
        const selectedMainCategoryId = event.target.value;
        updateChildCategories(selectedMainCategoryId);
    });

    childCategorySelect.addEventListener('change', (event) => {
        const selectedChildCategoryId = event.target.value;
        updateGrandChildCategories(selectedChildCategoryId);
    });

    const initialMainCategoryId = mainCategorySelect.value;
    updateChildCategories(initialMainCategoryId);





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
    //     tagClass: 'badge badge-primary'
    // });

    var imageInput = document.getElementById('featuredimageInput');
    var imagePreview = document.getElementById('featuredimagePreview');
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

    function validate_permalink() {
        var permalink = $('#permalink').val();

        $.ajax({
            url: '{{ route('Admin.Product.CheckPermalink') }}',
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
    $(function() {
        $('.select2-demo').each(function() {
            $(this)
                .wrap('<div class="position-relative"></div>')
                .select2({
                    placeholder: 'Select value',
                    dropdownParent: $(this).parent()
                });
        })
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
    }, 5000);
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('imageInput');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');

        imageInput.addEventListener('change', function(event) {
            const selectedImages = event.target.files;
            // Convert the selectedImages FileList to an array to use .forEach
            const imagesArray = Array.from(selectedImages);

            // Check if any images have been added previously and get their URLs
            const existingImages = Array.from(imagePreviewContainer.querySelectorAll('img')).map(img =>
                img.src);

            // Remove any previous previews that are no longer selected
            imagePreviewContainer.querySelectorAll('.preview-wrapper').forEach(wrapper => {
                const img = wrapper.querySelector('img');
                if (!imagesArray.includes(img.src)) {
                    wrapper.remove();
                }
            });

            // Create new previews for newly added images
            imagesArray.forEach(image => {
                if (!existingImages.includes(URL.createObjectURL(image))) {
                    const reader = new FileReader();

                    reader.onload = function() {
                        const imgPreview = document.createElement('img');
                        imgPreview.src = reader.result;
                        imgPreview.classList.add('img-thumbnail', 'img-preview');

                        // Add a radio button for featured image selection
                        const radioInput = document.createElement('input');
                        radioInput.type = 'radio';
                        radioInput.name = 'featuredImage';
                        // radioInput.value = reader.result;
                        radioInput.value = '1';
                        radioInput.classList.add('radio-button');
                        radioInput.addEventListener('click', function() {
                            setFeaturedImage(reader.result);
                        });

                        // Add a "Remove" button for removing the image
                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.classList.add('btn', 'btn-sm', 'btn-danger', 'badge',
                            'btn-remove', 'reduce_button');
                        removeButton.textContent = 'X';
                        removeButton.addEventListener('click', function() {
                            const previewWrapper = removeButton.parentNode;
                            // removeImageAndFile(removeButton);
                            if (previewWrapper.classList.contains('selected')) {
                                setFeaturedImage(null);
                            }
                            previewWrapper.remove();
                        });

                        // Wrap the image, radio button, and remove button in a container
                        const previewWrapper = document.createElement('label');
                        previewWrapper.classList.add('preview-wrapper');
                        previewWrapper.appendChild(imgPreview);
                        previewWrapper.appendChild(radioInput);
                        previewWrapper.appendChild(removeButton);

                        imagePreviewContainer.appendChild(previewWrapper);
                    }

                    reader.readAsDataURL(image);
                }
            });
        });


        function setFeaturedImage(imageSrc) {
            // Remove any existing selection
            const prevSelected = document.querySelector('.preview-wrapper.selected');
            if (prevSelected) {
                prevSelected.classList.remove('selected');
                const prevRadio = prevSelected.querySelector('.radio-button');
                prevRadio.checked = false;
            }

            // Set the new featured image
            if (imageSrc) {
                const selectedWrapper = document.querySelector(`.preview-wrapper [value="${imageSrc}"]`)
                    .parentNode;
                selectedWrapper.classList.add('selected');
                const selectedRadio = selectedWrapper.querySelector('.radio-button');
                selectedRadio.checked = true;
            }
        }
    });


    function uploadImages() {
        const fileInput = $('#imageInput');
        if (fileInput[0].files.length === 0) {
            alert('Please select at least one image.');
            return;
        }

        const radioInput = $('.radio-button:checked');
        if (radioInput.length === 0) {
            alert('Please select a featured image.');
            return;
        }

        const formData = new FormData();

        const selectedFiles = fileInput[0].files;
        for (let i = 0; i < selectedFiles.length; i++) {
            formData.append('gallery_media[]', selectedFiles[i]);
        }

        formData.append('featuredImage', radioInput.val());
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        $.ajax({
            url: '/admin/products/add_product_variation',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('.response_section').html('<div class="alert alert-success">' + response.message +
                    '</div>');
            },
            error: function(xhr, status, error) {
                $('.response_section').html(
                    '<div class="alert alert-danger">Error saving data to the server.</div>');
            },
        });
    }


    function dataURItoBlob(dataURI) {
        const byteString = atob(dataURI.split(',')[1]);
        const mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], {
            type: mimeString
        });
    }

    Array.prototype.search = function(elem) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] == elem) return i;
        }

        return -1;
    };
</script>

<script>
 document.addEventListener('DOMContentLoaded', function() {
        $('#short_description').summernote({
            placeholder: 'Short Description...',
            tabsize: 2,
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript', 'fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
            fontNames: [
                'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New',
                'Georgia', 'Impact', 'Lucida Grande', 'Tahoma', 
                'Times New Roman', 'Verdana', 
                'Segoe UI', 'Helvetica Neue', 'Roboto', 'Montserrat',
                'Playfair Display', 'Courier New', 'Comic Sans MS'
            ],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '28', '32', '36']
        });

        $('#long_description').summernote({
            placeholder: 'Long Description...',
            tabsize: 2,
            height: 250,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear', 'fontname']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            fontNames: [
                'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New',
                'Georgia', 'Impact', 'Lucida Grande', 'Tahoma', 
                'Times New Roman', 'Verdana', 
                'Segoe UI', 'Helvetica Neue', 'Roboto', 'Montserrat',
                'Playfair Display', 'Courier New', 'Comic Sans MS'
            ],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '28', '32', '36']
        });
    });
</script>
