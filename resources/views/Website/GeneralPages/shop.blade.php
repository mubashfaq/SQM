@php
use App\Models\GeneralSettings;
$settings = GeneralSettings::whereIn('option', ['_minimum_weight','_minimum_items'])
->pluck('value', 'option');
$minimum_items = $settings['_minimum_items'] ?? '0';
$minimum_weight = $settings['_minimum_weight'] ?? '0';
@endphp
@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')
<section class="about-sec1">
    <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
<div class="container page-heading-center" >
    <div>
        <h2>Shop</h2>
    </div>
    <div>
        <p><span>Sydney Quality Meats:</span> Shop</p>
    </div>
</div>
</section>
<!-- Section 1 End -->
<div class="container pt-5 pb-4">
    <div class="results-container d-lg-none">

        <!-- Sort Form (Hidden) -->
        <form id="sortForm" action="{{ url('/shop') }}" method="GET">
            <input type="hidden" name="sort" id="sortInput">
            @if (request('category_id'))
            <input type="hidden" name="category_id" value="{{ request('category_id') }}">
            @endif
            @if (request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if (request('min_price'))
            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
            @endif
            @if (request('max_price'))
            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
            @endif
        </form>

        <div class="d-flex gap-3 align-items-center">
            <!-- All Products Button -->
            <div class="All_Products_Button">
                <button type="button" onclick="window.location.href='{{ url('shop') }}'">
                    Clear Filter
                </button>
            </div>

        </div>
    </div>
</div>

<!-- About Section 2 -->
<section data-aos="fade-up" class="about-sec2 position-relative">
    <div class="container">
        <div class="row about-sec2-row about-sec2-row232">
            <div class="col-12 col-lg-8 col-xl-9 order-2 order-lg-1">
                <section class="Sec7">
                    <div class="Shop_Sec2_Main_Div">
                        <!-- Header Section -->
                        <div class="text-center mb-8">
                            <div class="results-container Display___none">
                                <!-- Sort Form (Hidden) -->
                                <form id="sortForm" action="{{ url('/shop') }}" method="GET">
                                    <input type="hidden" name="sort" id="sortInput">
                                    @if (request('category_id'))
                                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                                    @endif
                                    @if (request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                    @if (request('min_price'))
                                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                                    @endif
                                    @if (request('max_price'))
                                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                                    @endif
                                </form>
                            </div>
                            <!-- Product Grid -->
                            <div class="row justify-content-start">
                                @if ($featuredproducts->isEmpty())
                                <div class="col-12">
                                    <span class="mt-4" style="color: #666; font-size: 14px;">No products found </span>
                                </div>
                                @else
                                @foreach ($featuredproducts as $product)
                                @php
                                // Grab price from productMeta
                                $priceMeta = $product->productMeta->firstWhere('option', '_price');
                                $stockMeta = $product->productMeta->firstWhere('option', '_stock_status');
                                $weightMeta = $product->productMeta->firstWhere('option', '_weight');
                                $is_weightMeta = $product->productMeta->firstWhere('option', '_is_weight');
                                $price = $priceMeta ? $priceMeta->value : '0';
                                $stock = $stockMeta ? $stockMeta->value : '';
                                $weight = $weightMeta ? $weightMeta->value : 1;
                                $is_weight = $is_weightMeta ? $is_weightMeta->value : '';

                                // Calculate per kg price
                                $perKgPrice = $weight > 0 ? $price / $weight : $price;

                                $discountData = $product->discount_data ?? null;
                                $finalPrice = $discountData ? $discountData['discounted_price'] : $price;
                                $perKgFinalPrice = $weight > 0 ? $finalPrice / $weight : $finalPrice;
                                @endphp

                                <div class="col-12 col-sm-6 col-xl-4 mb-4">
                                    <div class="card product-card">
                                        <div class="position-relative Sec7_product-image">
                                            <!-- Product Image -->
                                            <img src="{{ asset($product->featured_image ?: 'default/default.jpg') }}"
                                                alt="{{ $product->title }}" class="card-img-top" />

                                            <!-- Show "Out of Stock" Badge -->
                                            @php
                                            $stock = $product->productMeta->firstWhere('option', '_stock_status')->value ?? 'in_stock';
                                            @endphp
                                            @if ($stock != 'in_stock')
                                            <span class="badge bg-danger position-absolute rounded-0"
                                                style="top: 10px; left: 10px;">
                                                Out of Stock
                                            </span>
                                            @endif
                                        </div>
                                        <div class="card-body text-center py-2 px-0">
                                            <!-- Product Name -->
                                            <div>
                                                <a class="Product_Links"
                                                    href="/product-detail/{{ $product->permalink }}">
                                                    <h5 class="Product_card-title mb-0">{{ strlen($product->title) > 24 ? substr($product->title, 0, 24) . '...' : $product->title }}</h5>
                                                </a>
                                            </div>



                                            <!-- Weight Input -->


                                            <!-- Dynamic Price Display -->
                                            <div class="price-display my-3 Respon_sive_ness" id="price-display-{{ $product->id }}">
                                                @if ($discountData)
                                                <div class="row">
                                                    <div class="col-md-5 centeredd" style="text-align: left; font-size: 14px;">Price</div>
                                                    <div class="col-md-7 ">
                                                        <p class="card-text text-muted mb-2 mt-0 centeredd" style="text-align: left;">
                                                            <span class="theme-color"><del class="original-price ">${{ $price }}</del></span>
                                                            <span class="discounted-price text-success">${{ $finalPrice }}</span>
                                                        </p>
                                                    </div>
                                                </div>

                                                @else
                                                <div class="row">
                                                    <div class="col-md-5 centeredd" style="text-align: left; font-size: 14px;">Price</div>
                                                    <div class="col-md-7">
                                                        <p class="card-text text-muted mb-2 mt-0 centeredd" style="text-align: left;">
                                                            <span class="current-price theme-color">${{ $finalPrice }}</span>
                                                        </p>
                                                    </div>
                                                </div>

                                                @endif
                                                <div class="row mb-3">
                                                    <div class="col-md-5 centeredd" style="text-align: left;"> <label class="form-label small">@if($is_weight==1) Weight (KG) @else Item @endif</label></div>
                                                    <div class="col-md-4 Inputs_Centered_div">
                                                    <input type="number"
                                                                        class="form-control Inputs_Centered form-control-sm weight-input"
                                                                        data-product-id="{{ $product->id }}"
                                                                        data-per-kg-price="{{ $perKgFinalPrice }}"
                                                                        data-original-per-kg-price="{{ $perKgPrice }}"
                                                                        data-has-discount="{{ $discountData ? 'true' : 'false' }}"
                                                                        data-is-weight="{{ $is_weight }}"
                                                                        data-minimum-weight="{{ $minimum_weight / 1000 }}"
                                                                        data-minimum-items="{{ $minimum_items }}"
                                                                        value="{{ $weight }}"
                                                                        min="{{ $is_weight == 1 ? $minimum_weight / 1000 : $minimum_items }}"
                                                                        step="{{ $is_weight == 1 ? '0.1' : '1' }}">

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Show "Out of Stock" Message Instead of Add to Cart -->
                                            @if ($stock == 'in_stock')
                                            <form action="{{ route('cart.store') }}" method="POST" class="cart-form">
                                                @csrf
                                                <input type="hidden" name="pId" value="{{ $product->id }}">
                                                <input type="hidden" name="title" value="{{ $product->title }}">
                                                <input type="hidden" name="amount" class="dynamic-amount" value="{{ $finalPrice }}">
                                                <input type="hidden" name="featuredImage" value="{{ $product->featured_image }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <input type="hidden" name="is_weight" value="{{ $is_weight }}">
                                                <input type="hidden" name="weight" class="dynamic-weight" value="{{ $weight }}">
                                                <input type="hidden" name="original_weight" value="{{ $weight }}">
                                                <input type="hidden" name="original_price" value="{{ $finalPrice }}">
                                                <button class="btn btn-danger w-50 add_custom_button add-to-cart-btn" type="submit">
                                                    ADD TO CART
                                                </button>
                                            </form>
                                            @else
                                            <button class="btn btn-dark out-of-stock-btn  w-50" style="font-size: 15px; line-height:20px;">
                                                OUT OF STOCK
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                </section>
            </div>

            <!-- Sidebar -->
            <div class="col-12 col-lg-4 col-xl-3 order-1 order-lg-2 mb-5 mb-lg-0">
                <!-- Searcorm -->
                <div class="search-container_Div">
                    <form action="{{ url('/shop') }}" method="GET" class="search-container">
                        <input type="text" class="form-control" name="search" placeholder="Search product"
                            value="{{ request('search') }}" />
                        <button type="submit" class="icon-button">
                            <svg class="search-icon" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 
                                                                                                                 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85
                                                                                                                 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007
                                                                                                                 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1
                                                                                                                 1-11 0 5.5 5.5 0 0 1 11 0z" />
                            </svg>
                        </button>

                        <!-- Preserve sort & category if present -->
                        @if (request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        @if (request('category_id'))
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                        @endif
                        @if (request('min_price'))
                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        @endif
                        @if (request('max_price'))
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        @endif
                    </form>
                </div>

                <!-- Price Range Slider -->
               <div class="price-slider-container mt-4">
                        <h4>Price</h4>
                        <div class="price-range-slider">
                            <form action="{{ url('/shop') }}" method="GET">
                                @if (request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if (request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif
                                @if (request('category_id'))
                                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                                @endif

                                <input type="hidden" name="min_price" id="min_price" value="{{ request('min_price', 10) }}">
                                <input type="hidden" name="max_price" id="max_price"
                                    value="{{ request('max_price', 1000) }}">

                                <div id="slider-range" class="range-bar"></div>
                                <p class="range-value">
                                    <input type="text" id="amount" readonly />
                                    <button type="submit">Apply</button>
                                </p>
                            </form>
                        </div>
                    </div>

                <!-- Categories List -->
                <div class="search-container_Div mt-4">
                    <h2 class="search-container_Header">Categories</h2>
                    <div class="wp-widget-group__inner-blocks">
                        <ul style="text-decoration: none; list-style: none; padding: 0;">
                            @foreach($categories as $category)
                            {{-- Assign "active-category" class to the <div> if this category is selected --}}
                            <div class="Shop_Cat_List @if(request('category_id') == $category->id) active-category @endif"
                                data-url="{{ url('/shop') }}?{{ http_build_query(array_merge(request()->except('page', 'category_id'), ['category_id' => $category->id])) }}">
                                <li>
                                    <a href="{{ url('/shop') }}?{{ http_build_query(array_merge(request()->except('page', 'category_id'), ['category_id' => $category->id])) }}"
                                        class="text-capitalize category-link" style="text-decoration: none;">
                                        {{ $category->title }}
                                    </a>
                                </li>
                            </div>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- All Products Button -->
                <div class="All_Products_Button d-none d-lg-block">
                    <button type="button" onclick="window.location.href='{{ url('shop') }}'">
                        Clear Filter
                    </button>
                </div>

            </div>
        </div>
        @if ($featuredproducts->total() > 0)
        <p class="results-text py-3 ps-sm-5 ps-lg-4" id="results-counter">
            Showing {{ $featuredproducts->firstItem() }} - {{ $featuredproducts->lastItem() }}
            of {{ $featuredproducts->total() }} Results
        </p>
        @else
        <p class="results-text py-3 ps-sm-5 ps-lg-4" id="results-counter">
            Showing 0 - 0 of 0 results
        </p>
        @endif
    </div>
</section>

<!-- Custom Pagination -->
<section class="mb-4">
    <div class="container ">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center pb-5 gap-1">
                {{-- Previous Page Link --}}
                @if ($featuredproducts->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">‹</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link"
                        href="{{ $featuredproducts->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}"
                        rel="prev" aria-label="@lang('pagination.previous')">‹</a>
                </li>
                @endif

                {{-- Page Number Links --}}
                @for ($i = 1; $i <= $featuredproducts->lastPage(); $i++)
                    @if ($i == $featuredproducts->currentPage())
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">{{ $i }}</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link"
                            href="{{ $featuredproducts->url($i) . '&' . http_build_query(request()->except('page')) }}">{{ $i }}</a>
                    </li>
                    @endif
                    @endfor

                    {{-- Next Page Link --}}
                    @if ($featuredproducts->hasMorePages())
                    <li class="page-item">
                        <a class="page-link"
                            href="{{ $featuredproducts->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}"
                            rel="next" aria-label="@lang('pagination.next')">›</a>
                    </li>
                    @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span class="page-link" aria-hidden="true">›</span>
                    </li>
                    @endif
            </ul>
        </nav>
    </div>
</section>


<style>
    @media (max-width: 576px) {
        .Respon_sive_ness {
            display: block;
        }

        .Respon_sive_ness .centeredd {
            text-align: center !important;
        }

        /* Center the weight input in mobile view */
        .Respon_sive_ness .Inputs_Centered_div {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .Respon_sive_ness .Inputs_Centered_div .Inputs_Centered {
            margin-left: auto;
            margin-right: auto;
            display: block;
        }
    }

    /* Additional styles for price slider */
    .price-slider-container {
        background-color: #faf7f2;
        padding: 35px 30px;
        max-width: 100%;
        margin: 20px auto;
        position: relative;
    }

    .price-slider-container h4 {
        text-transform: uppercase;
        font-size: 20px;
        padding-bottom: 15px;
        font-weight: 600;
    }

    .price-range-slider {
        width: 100%;
    }

    .price-range-slider .range-value {
        margin: 0;
        display: flex;
        justify-content: space-between;
        padding: 20px 0 20px 0;
    }

    .price-range-slider .range-value input {
        width: 100%;
        background: none;
        color: #7a7373;
        font-size: 15px;
        font-weight: initial;
        box-shadow: none;
        border: none;
    }

    .price-range-slider .range-value input:focus-visible {
        border: none !important;
        outline: none !important;
    }

    .price-range-slider .range-value button {
        padding: 3px 10px;
        background-color: white;
        color: black;
        border: none;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
    }

    .price-range-slider .range-bar {
        border: none;
        background: #ddd9d4;
        height: 3px;
        width: 96%;
        margin-left: 8px;
    }

    .price-range-slider .range-bar .ui-slider-range {
        background: #7a7373;
    }

    .price-range-slider .range-bar .ui-slider-handle {
        border-radius: 25px;
        background: #a42125;
        border: 2px solid #a42125 !important;
        height: 17px;
        width: 17px;
        top: -0.42em;
        cursor: pointer;
    }

    .price-range-slider .range-bar .ui-slider-handle:focus,
    .price-range-slider .range-bar .ui-slider-handle:active {
        outline: none !important;
        border: 2px solid #a42125 !important;
        box-shadow: none !important;
    }

    .price-range-slider .range-bar .ui-slider-handle+span {
        background: #a42125;
        border: 2px solid #a42125 !important;
    }
</style>

<script>
    
    document.addEventListener('DOMContentLoaded', function() {
    const weightInputs = document.querySelectorAll('.weight-input');

        weightInputs.forEach(function(input) {
            input.addEventListener('keypress', function(e) {
                const isWeight = parseInt(this.dataset.isWeight);

                // If not weight-based (items), prevent decimal point entry
                if (isWeight !== 1) {
                    if (e.key === '.' || e.key === ',') {
                        e.preventDefault();
                    }
                }
            });

            input.addEventListener('input', function() {
                const isWeight = parseInt(this.dataset.isWeight);
                const value = this.value;

                // For items, validate no decimal values
                if (isWeight !== 1 && (value.includes('.') || value.includes(','))) {
                    // Remove decimal part
                    this.value = Math.floor(parseFloat(value)) || '';
                }
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // only if you want to prevent default first
                    this.form.submit();
                }
            });
        }
    });
</script>
<!-- jQuery UI Slider Initialization -->
<script>
    function toggleDropdown() {
        document.querySelector('.custom-dropdown').classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener("click", function(event) {
        let dropdown = document.querySelector(".custom-dropdown");
        if (!dropdown.contains(event.target)) {
            dropdown.classList.remove("active");
        }
    });

    // Listen for click on sorting options:
    document.querySelectorAll('.dropdown-option').forEach(option => {
        option.addEventListener('click', function() {
            // Get the chosen sort value and set it in hidden input
            const sortValue = this.getAttribute('data-value');
            document.getElementById('sortInput').value = sortValue;
            // Submit the form
            document.getElementById('sortForm').submit();
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".Shop_Cat_List").forEach(function(categoryDiv) {
            categoryDiv.addEventListener("click", function() {
                let url = categoryDiv.getAttribute("data-url");
                if (url) {
                    window.location.href = url;
                }
            });
        });
    });
</script>

   @push('scripts')
    <script>
        $(document).ready(function () {
            // Ensure jQuery UI is loaded before initializing slider
            if (typeof $.fn.slider === 'undefined') {
                // Load jQuery UI if not already loaded
                if (typeof window.loadLibrary === 'function') {
                    window.loadLibrary('jquery-ui').then(function() {
                        initializeSlider();
                    }).catch(function() {
                        // Fallback if library loading fails
                        loadJQueryUIFallback();
                    });
                } else {
                    // Fallback: load jQuery UI directly
                    loadJQueryUIFallback();
                }
            } else {
                initializeSlider();
            }
            
            function loadJQueryUIFallback() {
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css';
                document.head.appendChild(link);
                
                var script = document.createElement('script');
                script.src = 'https://code.jquery.com/ui/1.13.2/jquery-ui.min.js';
                script.onload = function() {
                    // Add a small delay to ensure jQuery UI is fully loaded
                    setTimeout(initializeSlider, 100);
                };
                document.head.appendChild(script);
            }
            
            function initializeSlider() {
                // Check if slider function is available
                if (typeof $.fn.slider === 'undefined') {
                    console.warn('jQuery UI Slider not available, retrying in 500ms...');
                    setTimeout(initializeSlider, 500);
                    return;
                }
                
                let currentMin = {{ request('min_price', 1) }};
                let currentMax = {{ request('max_price', 1000) }};

                $("#slider-range").slider({
                    range: true,
                    min: 1,
                    max: 5000,
                    values: [currentMin, currentMax],
                    slide: function (event, ui) {
                        // Update the amount display and hidden input values when the slider is moved
                        $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                        $("#min_price").val(ui.values[0]);
                        $("#max_price").val(ui.values[1]);
                    }
                });

                // Manually handle touch events
                $("#slider-range").on('touchstart', function (event) {
                    var touch = event.originalEvent.touches[0];
                    var simulatedEvent = document.createEvent('MouseEvent');
                    simulatedEvent.initMouseEvent({
                        type: 'mousedown',
                        clientX: touch.clientX,
                        clientY: touch.clientY
                    });
                    touch.target.dispatchEvent(simulatedEvent);
                    event.preventDefault();
                });

                $("#slider-range").on('touchmove', function (event) {
                    var touch = event.originalEvent.touches[0];
                    var simulatedEvent = document.createEvent('MouseEvent');
                    simulatedEvent.initMouseEvent({
                        type: 'mousemove',
                        clientX: touch.clientX,
                        clientY: touch.clientY
                    });
                    touch.target.dispatchEvent(simulatedEvent);
                    event.preventDefault();
                });

                $("#slider-range").on('touchend', function (event) {
                    var touch = event.originalEvent.changedTouches[0];
                    var simulatedEvent = document.createEvent('MouseEvent');
                    simulatedEvent.initMouseEvent({
                        type: 'mouseup',
                        clientX: touch.clientX,
                        clientY: touch.clientY
                    });
                    touch.target.dispatchEvent(simulatedEvent);
                    event.preventDefault();
                });

                $("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
            }
        });
    </script>
    @endpush
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle weight input changes
        document.querySelectorAll('.weight-input').forEach(function(input) {
            input.addEventListener('input', function() {
                const productId = this.dataset.productId;
                const perKgPrice = parseFloat(this.dataset.perKgPrice);
                const originalPerKgPrice = parseFloat(this.dataset.originalPerKgPrice);
                const hasDiscount = this.dataset.hasDiscount === 'true';
                const newWeight = parseFloat(this.value) || 0;
                const isWeight = this.dataset.isWeight === '1'; // Check if the product type is weight
                const minimumWeight = parseFloat(this.dataset.minimumWeight); // Minimum weight from backend
                const minimumItems = parseInt(this.dataset.minimumItems); // Minimum items from backend

                // Calculate new prices
                const newPrice = (newWeight * perKgPrice).toFixed(2);
                const newOriginalPrice = (newWeight * originalPerKgPrice).toFixed(2);

                // Update price display
                const priceDisplay = document.getElementById(`price-display-${productId}`);
                if (hasDiscount) {
                    const originalPriceElement = priceDisplay.querySelector('.original-price');
                    const discountedPriceElement = priceDisplay.querySelector('.discounted-price');

                    if (originalPriceElement) originalPriceElement.textContent = `$${newOriginalPrice}`;
                    if (discountedPriceElement) discountedPriceElement.textContent = `$${newPrice}`;
                } else {
                    const currentPriceElement = priceDisplay.querySelector('.current-price');
                    if (currentPriceElement) currentPriceElement.textContent = `$${newPrice}`;
                }

                // Find the correct form - look for the form within the same product card
                const productCard = this.closest('.col-12');
                const form = productCard.querySelector('.cart-form');
                const addToCartBtn = form.querySelector('.add-to-cart-btn');

                if (form) {
                    // Update hidden form inputs
                    const amountInput = form.querySelector('.dynamic-amount');
                    const weightInput = form.querySelector('.dynamic-weight');

                    if (amountInput) amountInput.value = newPrice;
                    if (weightInput) weightInput.value = newWeight;

                    // Disable/enable Add to Cart button based on weight and item rules
                    if (isWeight) {
                        // If product is weight type, check against minimum weight
                        if (newWeight < minimumWeight) {
                            addToCartBtn.disabled = true;
                        } else {
                            addToCartBtn.disabled = false;
                        }
                    } else {
                        // If product is item type, check against minimum items
                        const newQuantity = parseInt(this.value) || 0;
                        if (newQuantity < minimumItems) {
                            addToCartBtn.disabled = true;
                        } else {
                            addToCartBtn.disabled = false;
                        }
                    }

                    // Debug: Log to console to verify updates
                    console.log(`Product ${productId}: Weight=${newWeight}kg, Price=$${newPrice}, Add to Cart Disabled=${addToCartBtn.disabled}`);
                } else {
                    console.warn(`Form not found for product ${productId}`);
                }
            });

            // Trigger initial calculation
            input.dispatchEvent(new Event('input'));
        });
    });

    const style = document.createElement('style');
    style.textContent = `
        .weight-input:focus {
            text-align:center;
            border-color: #A42125;
             font-size:16px;
            border: 1px solid #A42125;
            // box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        .weight-input {
            width:53px;
            text-align:center;
            font-size:16px;
            border-color: #A42125;
            // box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        .price-display {
            min-height: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .card-text {
            margin: 0.25rem 0;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection