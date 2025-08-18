@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\ProductClass;
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
            <!--================================-->
            <!-- Breadcrumb Start -->
            <!--================================-->
            <div class="card shadow-sm">
                <div class="  p-3 mb-3 d-flex justify-content-between flex-wrap">
                    <div class="clearfix">
                        <div class="pd-t-5 pd-b-5">
                            <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Edit Product') }}</h1>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0">
                                <li class="breadcrumb-item">
                                    <a class="link-dark link-primary" href="{{ route('Admin.Dashboard') }}">
                                        <i class="fa-solid fa-home"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a class="link-primary" href="{{ route('products.index') }}">
                                        Products
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 gap-2 product-edit-btns">
                        @if (Session::has('permissions') && in_array('Product List', Session::get('permissions')))
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fa-solid fa-list-ul me-1"></i> Product List
                            </a>
                        @endif
                        @if (Session::has('permissions') && in_array('Product Trash List', Session::get('permissions')))
                            <a href="{{ route('Admin.Product.Trash') }}" class="btn btn-danger">
                                <i class="fa-solid fa-trash me-1"></i> Trash List
                            </a>
                        @endif
                        @if (Session::has('permissions') && in_array('Create Product', Session::get('permissions')))
                            <a href="{{ url('/') }}/admin/products/create" class="btn btn-success">
                                <i class="fa-solid fa-circle-plus me-1"></i> Create Product
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
                <form id="product-create-form" method="POST" action="{{ route('products.update', $product->id) }}"
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
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('Product Details (Compulsory)') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-control-label active">Title: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control addTitle" type="text" name="title"
                                            placeholder="Meat Name" value="{{ old('title', $product->title) }}"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label active">Permalink: <span
                                                class="tx-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span
                                                    class="input-group-text input-group-text">{{ url('/') }}/shop/</span>
                                            </div>
                                            <input class="form-control form-control  permalink" id="permalink"
                                                type="text" name="permalink"
                                                value="{{ old('permalink', $product->permalink) }}"
                                                placeholder="meat-name" required>
                                        </div>
                                        <div class="text-danger permalink_error"></div>
                                        <input type="hidden" class="permalink_status" name="permalink_status"
                                            value="{{ old('permalink_status', 1) }}" required>
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
                                    <div class="form-group">
                                        <label class="form-control-label active">Short Description: </label>
                                        <textarea class="form-control" id="short_description" name="short_description" placeholder="Short Description...">{{ old('short_description', $product->short_description) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label active">Long Description: </label>
                                        <textarea class="form-control" id="long_description" name="long_description" placeholder="Long Description..."> {{ old('long_description', $product->long_description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card mg-b-30">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-header-title tx-13 mb-0">{{ __('Gallery Media (Optional)') }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body gallery-media">
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" id="imageInput" name="gallery_media[]" multiple accept="image/*" placeholder="Select Images">
                                    <button type="button" class="btn btn-priamry" onclick="uploadImages()">Upload</button>
                                </div>

                                <div class="row mt-3" id="imagePreviewContainer">
                                    @if($product->gallery_media != '')
                                        @php
                                        $imagePaths = explode(',', $product->gallery_media);
                                        @endphp
                                        @foreach($imagePaths as $imagePath)
                                        <div class="col-md-3 image-item position-relative">
                                            <img class="m-1 img-fluid" src="{{ url('/').'/'.$imagePath }}" alt="Gallery Image">
                                            <!-- Cross button overlay -->
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm px-2 py-0 delete-btn position-absolute" 
                                                    style="top:5px; right:5px;" 
                                                    data-image="{{ $imagePath }}"
                                                    data-product-id="{{ $product->id }}">
                                                &times;
                                            </button>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="response_section"></div>
                                <small class="text-secondary">Select a gallery image by clicking on it.</small>
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
                                    <div class="form-group">
                                        <label class="form-control-label active">SEO Title: </label>
                                        <input type="text" class="form-control" name="_seo_title"
                                            value="{{ old('_seo_title', ProductClass::getOptionValue('_seo_title', $product->productMeta)) }}"
                                            placeholder="SEO Title...">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label active mt-2">SEO Tags: </label>
                                        <input type="text" name="_seo_tags"
                                            value="{{ old('_seo_tags', ProductClass::getOptionValue('_seo_tags', $product->productMeta)) }}"
                                            class="form-control rounded-0" id="seo_tags">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label active mt-2">SEO Description: </label>
                                        <textarea class="form-control" name="_seo_description" placeholder="SEO Description...">{{ old('_seo_description', ProductClass::getOptionValue('_seo_description', $product->productMeta)) }}</textarea>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="form-control-label active mt-2">Canonical URL: </label>
                                        <input type="url" class="form-control" name="_canonical_url"
                                            value="{{ old('_canonical_url', ProductClass::getOptionValue('_canonical_url', $product->productMeta)) }}"
                                            placeholder="Canonical URL..." pattern="https?://.*"
                                            title="Enter a valid URL (e.g., https://example.com)">
                                    </div> -->

                                    <!-- Schema Markup Field -->
                                    <!-- <div class="form-group">
                                        <label class="form-control-label active mt-2">Schema Markup (JSON-LD): </label>
                                        <textarea class="form-control" name="_schema_markup" placeholder="Enter JSON-LD Schema Markup for the page">{{ old('_schema_markup', ProductClass::getOptionValue('_schema_markup', $product->productMeta)) }}</textarea>
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
                                    @php
                                        $selectedAreaIds = explode(',', $product->area_group_id ?? '');
                                    @endphp

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
                                                                    value="0" data-label="All Countries"
                                                                    {{ in_array('0', $selectedAreaIds) ? 'checked' : '' }}>
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
                                                                            data-label="{{ $groupmeta->title }}"
                                                                            {{ in_array($groupmeta->postcode, $selectedAreaIds) ? 'checked' : '' }}>
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
                                                {{ __('More Details (Optional)') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mt-2">
                                        <label class="form-control-label active">Brand:</label>
                                        <select class="form-select" name="brand_id" style="width: 100%">
                                            <option value="0" selected>Select Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    @if ($product->brand_id == $brand->id) selected @endif>
                                                    {{ $brand->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label class="form-control-label active">Discount:</label>
                                        <select class="form-select" name="discount_id">
                                            <option value="0" selected>Select Discount On Product</option>
                                            @foreach ($discounts as $discount)
                                                <option value="{{ $discount->id }}"
                                                    @if ($product->discount_id == $discount->id) selected @endif>
                                                    {{ $discount->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                    <label class="form-control-label active">GST Included: </label>
                                    <select class="form-control" id="_gst" name="_gst">
                                        <option value="1" @if(ProductClass::getOptionValue('_gst', $product->productMeta) == 1) selected @endif>True</option>
                                        <option value="0" @if(ProductClass::getOptionValue('_gst', $product->productMeta) == 0) selected @endif>False</option>
                                    </select>
                                </div>
                                    <div class="form-group mt-2">
                                        <label class="form-control-label active">Product Type:</label>
                                        <select class="form-select " name="product_type"
                                            style="width: 100%">
                                            @foreach ($producttypes as $producttype)
                                                <option value="{{ $producttype->id }}"
                                                    @if ($product->product_type == $producttype->id) selected @endif>
                                                    {{ $producttype->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label class="form-control-label active">Product Varient:</label>
                                        <select class="form-select" id="variant" name="variant">
                                            <option value="1" @if (old('variant', $product->variant) == 1) selected @endif>
                                                Individual</option>
                                        </select>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label class="form-control-label active">Product Stock:</label>
                                        <select class="form-select form-select2-demo" name="_stock_status">
                                        <option value="in_stock"
                                            {{ old('_stock_status', ProductClass::getOptionValue('_stock_status', $product->productMeta)) === 'in_stock' ? 'selected' : '' }}>
                                            In Stock
                                        </option>
                                        <option value="out_stock"
                                            {{ old('_stock_status', ProductClass::getOptionValue('_stock_status', $product->productMeta)) === 'out_stock' ? 'selected' : '' }}>
                                            Out of Stock
                                        </option>
                                    </select>
                                    </div>
                                  

                                    <div class="form-group regular_price_Section mt-2">
                                        <label class="form-control-label active">Buy Price:</label>
                                        <input class="form-control" type="number" min="0" step="0.0001"
                                            value="{{ ProductClass::getOptionValue('_buy_price', $product->productMeta) }}" name="_buy_price"
                                            required>
                                    </div>

                                    <div class="form-group regular_price_Section mt-2">
                                        <label class="form-control-label active">Price:</label>
                                        <input class="form-control" type="number" min="0" step="0.0001"
                                            value="{{  ProductClass::getOptionValue('_price', $product->productMeta) }}" name="_price">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label class="form-control-label active">SKU (Required):</label>
                                        <input class="form-control" type="text"
                                            value="{{ old('sku', $product->sku) }}" name="sku" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">Type:</label>
                                        <div class="mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="_is_weight" id="weight" value="1" 
                                                    {{ ProductClass::getOptionValue('_is_weight', $product->productMeta) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="weight">
                                                    Weight
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="_is_weight" id="item" value="0"
                                                    {{ ProductClass::getOptionValue('_is_weight', $product->productMeta) == '0' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="item">
                                                    Item
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-2 d-none">
                                        <label class="form-control-label active">Weight:</label>
                                        <input class="form-control" type="text"
                                            value="1"
                                            name="_weight">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label class="form-control-label active">UPC/Barcode (Required):</label>
                                        <input class="form-control" type="text"
                                            value="{{ old('_upc', ProductClass::getOptionValue('_upc', $product->productMeta)) }}"
                                            name="_upc">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label class="form-control-label active">Active Status:</label>
                                        <select class="form-select" name="active">
                                            <option value="0" @if (old('active', $product->active) == 0) selected @endif>
                                                Draft</option>
                                            <option value="1" @if (old('active', $product->active) == 1) selected @endif>
                                                Public</option>
                                            <option value="2" @if (old('active', $product->active) == 2) selected @endif>
                                                Private</option>
                                            <option value="3" @if (old('active', $product->active) == 3) selected @endif>
                                                Trash</option>
                                        </select>
                                    </div>

                                    <hr class="mt-3">

                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" name="_virtual"
                                            value="1" id="customCheckvirtual"
                                            @if (old('_virtual', ProductClass::getOptionValue('_virtual', $product->productMeta) == 1)) checked @endif>
                                        <label class="custom-control-label" for="customCheckvirtual">Is this a
                                            featured Product?</label>
                                    </div>

                                    <hr class="mt-3">

                                    <input class="btn btn-success float-right" type="submit" value="Update">
                                    <input class="btn btn-secondary float-right mx-1" type="button"
                                        onclick="window.location = '{{ url('/') }}/admin/products'"
                                        value="Cancel">
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
                                    <div class="form-group">
                                        <label class="form-control-label active">Featured Image: </label>
                                        <input class="form-control" name="featured_image" accept="image/*"
                                            type="file" id="featuredimageInput">
                                        <div class="text-secondary"><small>Preferred Dimension: (720px) x
                                                (800px)</small></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label active">Featured Image Alt: </label>
                                        <input class="form-control" name="_alt" type="text"
                                            value="{{ old('_alt', ProductClass::getOptionValue('_alt', $product->productMeta)) }}">
                                    </div>
                                    <div id="featuredimagePreview">
                                        @if ($product->featured_image != '')
                                            <img src="{{ url('/') }}/{{ $product->featured_image }}"
                                                width="100%" height="auto">
                                        @endif
                                    </div>
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
                                    <div class="form-group">
                                        <label class="form-control-label active">Main Category: </label>
                                        <select class="form-control" id="main_category" name="category_id" required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- <div class="form-group mb-3">
                                        <label class="form-control-label active">Child Category: </label>
                                        <select class="form-control" id="child_category" name="child_category">
                                            <option value="">Select Child Category</option>
                                            @foreach ($childcats as $childCat)
                                                <option value="{{ $childCat->id }}"
                                                    {{ old('child_category', $product->child_category) == $childCat->id ? 'selected' : '' }}>
                                                    {{ $childCat->child_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label active">Sub Child Category: </label>
                                        <select class="form-control" id="grand_child_category"
                                            name="grand_child_category">
                                            <option value="">Select Sub Child Category</option>
                                            @foreach ($grandcats as $GrandChildCategory)
                                                <option value="{{ $GrandChildCategory->id }}"
                                                    {{ old('grand_child_category', $product->grand_child_category) == $GrandChildCategory->id ? 'selected' : '' }}>
                                                    {{ $GrandChildCategory->grand_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> -->
                                </div>

                            </div>
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">{{ __('Product Action') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p>Created By: @php print(ProductClass::CreateBy($product->created_by)); @endphp</p>
                                    <p>Created Date: {{ $product->created_at }}</p>
                                    <p>Updated Date: {{ $product->updated_at }}</p>
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

$(document).on('click', '.delete-btn', function(){
   var imagePath = $(this).data('image');
   var productId = $(this).data('product-id'); // Ensure your HTML element has this attribute
   var $btn = $(this);
   
   $.ajax({
      url: '/delete-gallery-image', // Route URL
      type: 'POST',
      data: {
         image: imagePath,
         product_id: productId,
         _token: '{{ csrf_token() }}'
      },
      success: function(response){
         if(response.success){
            // Remove the image container from the DOM
            $btn.closest('.image-item').remove();
         } else {
            alert(response.message);
         }
      },
      error: function(){
         alert('An error occurred. Please try again.');
      }
   });
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('imageInput');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');

        imageInput.addEventListener('change', function(event) {
            const selectedImages = event.target.files;
            const imagesArray = Array.from(selectedImages);
            const existingImages = Array.from(imagePreviewContainer.querySelectorAll('img')).map(img =>
                img.src);
            imagePreviewContainer.querySelectorAll('.preview-wrapper').forEach(wrapper => {
                const img = wrapper.querySelector('img');
                if (!imagesArray.includes(img.src)) {
                    wrapper.remove();
                }
            });
            imagesArray.forEach(image => {
                if (!existingImages.includes(URL.createObjectURL(image))) {
                    const reader = new FileReader();

                    reader.onload = function() {
                        const imgPreview = document.createElement('img');
                        imgPreview.src = reader.result;
                        imgPreview.classList.add('img-thumbnail', 'img-preview');
                        const radioInput = document.createElement('input');
                        radioInput.type = 'radio';
                        radioInput.name = 'featuredImage';
                        radioInput.value = '1';
                        radioInput.classList.add('radio-button');
                        radioInput.addEventListener('click', function() {
                            setFeaturedImage(reader.result);
                        });
                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.classList.add('btn', 'btn-sm', 'btn-danger', 'badge',
                            'btn-remove');
                        removeButton.textContent = 'X';
                        removeButton.addEventListener('click', function() {
                            const previewWrapper = removeButton.parentNode;
                            if (previewWrapper.classList.contains('selected')) {
                                setFeaturedImage(null);
                            }
                            previewWrapper.remove();
                        });
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
            const prevSelected = document.querySelector('.preview-wrapper.selected');
            if (prevSelected) {
                prevSelected.classList.remove('selected');
                const prevRadio = prevSelected.querySelector('.radio-button');
                prevRadio.checked = false;
            }
            if (imageSrc) {
                const selectedWrapper = document.querySelector(`.preview-wrapper [value="${imageSrc}"]`)
                    .parentNode;
                selectedWrapper.classList.add('selected');
                const selectedRadio = selectedWrapper.querySelector('.radio-button');
                selectedRadio.checked = true;
            }
        }
    });


    // $(function() {
    //     $('.select2-demo').each(function() {
    //         $(this)
    //             .wrap('<div class="position-relative"></div>')
    //             .select2({
    //                 placeholder: 'Select value',
    //                 dropdownParent: $(this).parent()
    //             });
    //     })
    // });

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

    const selectedMainCategoryId = {!! json_encode($product->category_id) !!};
    const selectedChildCategoryId = {!! json_encode($product->child_category) !!};
    const selectedGrandChildCategoryId = {!! json_encode($product->grand_child_category) !!};

    if (selectedMainCategoryId) {
        mainCategorySelect.value = selectedMainCategoryId;
        updateChildCategories(selectedMainCategoryId);
    }

    if (selectedChildCategoryId) {
        childCategorySelect.value = selectedChildCategoryId;
        updateGrandChildCategories(selectedChildCategoryId);
    }

    if (selectedGrandChildCategoryId) {
        grandChildCategorySelect.value = selectedGrandChildCategoryId;
    }
</script>
