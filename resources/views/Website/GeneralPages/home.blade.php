@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')
<!-- Section 1 -->
<section>
    <div id="carouselExampleIndicators" class="carousel slide carousel-vertical-down" data-bs-ride="carousel"
        data-bs-interval="3000">
        <!-- Carousel Items -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('WebsiteAssets/assets/img/slider-1.webp') }}" class="d-block w-100 d-none d-md-block" alt="Slide 1" />
                <img src="{{ asset('WebsiteAssets/assets/img/slider-1-1.webp') }}" class="d-block w-100 d-md-none" alt="Slide 1 Mobile" />
                <div class="carousel-caption text-start">
                    <h5 class="small-heading">CUT & CARVE BUTCHER</h5>
                    <div class="main-heading-div">
                        <h1 class="main-heading">THE FINEST MEAT <br />MARKET</h1>
                    </div>
                    <a href="{{ url('/page/about-us') }}" class="Home_Slider_BTN">
                        <span>KNOW ABOUT US</span>
                        <span class="divider"></span>
                        <i class="icon-meat-3"></i>
                    </a>
                </div>
            </div>

            <div class="carousel-item">
                <img src="{{ asset('WebsiteAssets/assets/img/slider-2.webp') }}" class="d-block w-100 d-none d-md-block" alt="Slide 2" />
                <img src="{{ asset('WebsiteAssets/assets/img/slider-2-1.webp') }}" class="d-block w-100 d-md-none" alt="Slide 2 Mobile" />
                <div class="carousel-caption text-start">
                    <h5 class="small-heading">CUT & CARVE BUTCHER</h5>
                    <h1 class="main-heading">QUALITY YOU CAN <br /> TRUST</h1>
                    <a href="{{ url('/page/about-us') }}" class="Home_Slider_BTN">
                        <span>KNOW ABOUT US</span>
                        <span class="divider"></span>
                        <i class="icon-meat-3"></i>
                    </a>

                </div>
            </div>

            <div class="carousel-item">
                <img src="{{ asset('WebsiteAssets/assets/img/slider-3.webp') }}" class="d-block w-100 d-none d-md-block" alt="Slide 3" />
                <img src="{{ asset('WebsiteAssets/assets/img/slider-3-1.webp') }}" class="d-block w-100 d-md-none" alt="Slide 3 Mobile" />
                <div class="carousel-caption text-start">
                    <h5 class="small-heading">CUT & CARVE BUTCHER</h5>
                    <h1 class="main-heading">FRESHLY CUT MEATS</h1>
                    <a href="{{ url('/page/about-us') }}" class="Home_Slider_BTN">
                        <span>KNOW ABOUT US</span>
                        <span class="divider"></span>
                        <i class="icon-meat-3"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Indicators -->
        <div class="carousel-indicators position-absolute translate-middle-y d-flex flex-sm-column align-items-end">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
            <div class="slide-counter">01 / 03</div>
        </div>
    </div>
</section>

<!-- Section 1 End -->

<!-- Section 2 -->
<section data-aos="fade-up">
    <div class="category-slider-container">
        <!-- Previous Button -->
        <button class="arrow arrow-prev" aria-label="Previous">&#10094;</button>

        <div class="category-slider">
            <!-- Categories will be injected here by JavaScript -->
        </div>

        <!-- Next Button -->
        <button class="arrow arrow-next" aria-label="Next">&#10095;</button>
    </div>
</section>


<!-- Section 2 End -->

<!-- Section 3 -->
<section data-aos="fade-up" class="section_3_Padding position-relative">
    <img src="{{ asset('WebsiteAssets/assets/img/hero-bg2.png') }}" alt="BG2" class="background-image bg2" />
    <div class="container position-relative py-5 Section3_Main_Div">
        <div class="px-xxl-4 pb-md-5 d-flex flex-column flex-lg-row align-items-center gap-5">
            <!-- Left Side with Image -->
            <div class="position-relative w-100 w-lg-50 d-flex justify-content-start justify-content-lg-center">
                <!-- Bouncing Border -->
                <div class="position-absolute bouncing-border"></div>

                <!-- Main Image -->
                <div class="position-relative Main_Img_Div">
                    <img src="{{ asset('WebsiteAssets/assets/img/hero-1.jpg') }}" alt="Professional butcher" width="400"
                        height="100" class="img-fluid position-relative z-index-10" />

                    <!-- SINCE 1996 Text -->
                    <div class="position-absolute since1996-text">
                        <span class="fs-1 fw-bold tracking-wide since1990">SINCE 1996</span>
                    </div>
                </div>


                <!-- Additional Bouncing Image -->
                <div class="additional-bouncing-image">
                    <img src="{{ asset('WebsiteAssets/assets/img/about-shape-1.png') }}" alt="Beef" class="img-fluid" />
                </div>
            </div>

            <!-- Right Side Content -->
            <div class="w-100 w-lg-50 px-sm-4 px-lg-0">
                <div class="mb-3">
                    <img src="{{ asset('WebsiteAssets/assets/img/logo-dark.png') }}" alt="logo"
                        style="height: auto; width: 140px" />
                    <h5 class="text_color_red fw-semibold tracking-wider text-uppercase">
                        ABOUT Sydney Quality Meats SHOP
                    </h5>
                    <h1 class="display-4 fw-bold text-dark">WE PROVIDE BEST MEAT</h1>
                </div>

                <p class="text-muted">
                    We are trusted by many clients from all over the country. We take pride in offering the finest cuts of meat, sourced from trusted farms that prioritize quality, sustainability, and ethical practices. Whether you're looking for succulent steaks, fresh poultry, handcrafted sausages, or specialty cuts, we've got you covered.
                </p>

                <div class="mb-4">
                    <h3 class="fs-5 fw-semibold text_color_red mb-3">
                        RAW MEAT PRODUCTION BY LEADING FARM
                    </h3>

                    <div class="row">
                        <div class="col-12 col-sm-6 d-flex align-items-center mb-4 mb-sm-3">
                            <i class="fa-regular fa-circle-check text_color_red me-2"></i>
                            <span class="fw-medium">PREMIUM CUTS</span>
                        </div>
                        <div class="col-12 col-sm-6 d-flex align-items-center mb-4 mb-sm-3">
                            <i class="fa-regular fa-circle-check text_color_red me-2"></i>
                            <span class="fw-medium">QUALITY SOURCING</span>
                        </div>
                        <div class="col-12 col-sm-6 d-flex align-items-center mb-4 mb-sm-3">
                            <i class="fa-regular fa-circle-check text_color_red me-2"></i>
                            <span class="fw-medium">CUSTOM BUTCHERING</span>
                        </div>
                        <div class="col-12 col-sm-6 d-flex align-items-center mb-4 mb-sm-3">
                            <i class="fa-regular fa-circle-check text_color_red me-2"></i>
                            <span class="fw-medium">FRESHNESS</span>
                        </div>
                        <div class="col-12 col-sm-6 d-flex align-items-center mb-4 mb-sm-3">
                            <i class="fa-regular fa-circle-check text_color_red me-2"></i>
                            <span class="fw-medium">BEST REVIEWS</span>
                        </div>
                        <div class="col-12 col-sm-6 d-flex align-items-center mb-4 mb-sm-3">
                            <i class="fa-regular fa-circle-check text_color_red me-2"></i>
                            <span class="fw-medium">100% ORGANIC</span>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex flex-column flex-sm-row gap-3 pt-3 align-items-start">
                    <div>
                        <a href="{{ url('/page/about-us') }}" class="home-btn-7-sec3">
                            <span>KNOW ABOUT US</span>
                            <span class="divider"></span>
                            <i class="icon-meat-3"></i>
                        </a>
                    </div>

                    <!-- Call for Delivery -->
                    <div class="d-flex align-items-center gap-3 call-wrapper">
                        <div
                            class="w-5 h-5 rounded-circle bg-light d-flex align-items-center justify-content-center position-relative overflow-hidden call-icon-container">
                            <span class="call-icon-overlay"></span>
                            <span class="icon-telephone"></span>
                        </div>
                        @php
                        use App\Models\GeneralSettings;
                        // Fetch all required options in one query
                        $settings = GeneralSettings::whereIn('option', ['_minimum_weight','_minimum_items','_instagram_link', '_facebook_link', '_twitter_link', '_youtube_link','_b_number','_header_logo','_footer_logo','_footer_logo','_b_street_address','_b_email','_b_area_address'])
                        ->pluck('value', 'option');
                        $b_number = $settings['_b_number'] ?? '#';
                        $minimum_items = $settings['_minimum_items'] ?? '0';
                        $minimum_weight = $settings['_minimum_weight'] ?? '0';
                        @endphp
                        <div class="d-flex flex-column" style="align-items: normal !important;">
                            <span class="small text-muted">CALL FOR DELIVERY</span>
                            <span class="text-sm plus-jakarta-sans">
                                <a href="tel:{{$b_number}}"
                                    class="text_color_red fw-semibold text-decoration-none underline-animation">{{$b_number}}</a>
                            </span>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <!-- Background Images -->
        <img src="{{ asset('WebsiteAssets/assets/img/hero-bg1.png') }}" alt="BG1" class="background-image bg1" />
    </div>


</section>
<!-- Section 3 End -->

<!-- Section 4 -->
<section data-aos="fade-up" class="statistics-section d-flex justify-content-center align-items-center">
    <!-- Dark Overlay -->
    <div class="overlay"></div>

    <!-- Statistics Content -->
    <div class="statistics-content">
        <div class="row justify-content-center SC_Main_SEC align-items-center text-center ">
            <!-- Statistic Item -->
            <div
                class="col-12 col-sm-6 Circle_SEC position-relative justify-content-center align-items-center text-center col-lg-3">
                <div class="stat-item position-relative text-white text-center">
                    <div class="background-animation"></div>
                    <h1 class="stat-number" data-count="30">0</h1>
                    <p class="stat-label">YEAR OF EXPERIENCE</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 Circle_SEC col-lg-3">
                <div class="stat-item position-relative text-white text-center">
                    <div class="background-animation"></div>
                    <h1 class="stat-number" data-count="45000">0K</h1>
                    <p class="stat-label">TONS OF MEAT</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 Circle_SEC col-lg-3">
                <div class="stat-item position-relative text-white text-center">
                    <div class="background-animation"></div>
                    <h1 class="stat-number" data-count="35000">0K</h1>
                    <p class="stat-label">HAPPY CUSTOMERS</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 Circle_SEC col-lg-3">
                <div class="stat-item position-relative text-white text-center">
                    <div class="background-animation"></div>
                    <h1 class="stat-number" data-count="32">0</h1>
                    <p class="stat-label">MEAT PRODUCTS</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 4 End -->

<!-- Section 5 -->
<!-- Section 5 -->
<section data-aos="fade-up">
    <div class="container my-5 py-5">
        <!-- Header -->
        <div class="text-center mb-5">
            <div class="position-relative d-flex flex-column align-items-center">
                <img src="{{ asset('WebsiteAssets/assets/img/logo-dark.png') }}" alt="Professional butcher" width="140"
                    class="object-contain" />
                <h3 class="theme-color text-uppercase Sec5_Header_h3 mb-2">
                    MEAT ASSORTMENT
                </h3>
                <h1 class="display-4 fw-bold">PRODUCTS PRICE</h1>
            </div>
        </div>

        <!-- Categories Section -->
        <ul class="nav nav-tabs justify-content-center gap-3 pb-5" id="categoryTabs" role="tablist">
            @foreach ($categories as $index => $category)
            <li class="nav-item text-center" role="presentation">
                <!-- Image button -->
                <button class="nav-link d-flex flex-column align-items-center category-btn {{ $loop->first ? 'active' : '' }}"
                    id="tab-{{ $index }}" data-bs-toggle="tab" data-bs-target="#content-{{ $index }}"
                    type="button" role="tab" aria-controls="content-{{ $index }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    <div class="category-icon-container">
                        <img src="{{ asset($category->thumbnail_image) }}" class="Category_image" alt="{{ $category->title }}" />
                    </div>
                </button>

                <!-- Label button -->
                <button class="category-label-btn category-btn" data-bs-toggle="tab"
                    data-bs-target="#content-{{ $index }}" type="button">
                    <span class="category-label">{{ strtoupper($category->title) }}</span>
                </button>
            </li>
            @endforeach
        </ul>




        <!-- Products Section -->

        <div class="tab-content" id="categoryTabsContent">
            @foreach ($categories as $index => $category)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="content-{{ $index }}" role="tabpanel"
                aria-labelledby="tab-{{ $index }}">
                <div class="row">
                    @forelse($category->products as $product)
                    @php
                    $discountData = $product->discount_data ?? null;
                    $finalPrice = $discountData ? $discountData['discounted_price'] :0;

                    @endphp
                    <div class="col-12 col-lg-6">
                        <div class="product-item">
                            <div class="Sec5_product-image">
                                <img src="{{ asset($product->featured_image ?: 'default/default.jpg') }}" alt="{{ $product->title }}" />
                            </div>
                            <div class="product-details">
                                <a class="Product_Links" href="/product-detail/{{ $product->permalink }}">
                                    <h5 class="Product_card-title">{{ strlen($product->title) > 24 ? substr($product->title, 0, 24) . '...' : $product->title }}</h5>
                                </a>
                                @php
                                $stoctMeta = $product->productMeta->firstWhere('option', '_stock_status');
                                $stock = $stoctMeta ? $stoctMeta->value : 'N/A';
                                $is_weightMeta = $product->productMeta->firstWhere('option', '_is_weight');
                                $is_weight = $is_weightMeta ? $is_weightMeta->value : '';
                                @endphp
                                <p class="text-secondary mb-0">{{ $stock == 'in_stock' ? 'In Stock' : 'Out Of Stock' }}
                                </p>
                            </div>
                            <div class="product-weight ms-lg-auto">
                                @php
                                $weightMeta = $product->productMeta->firstWhere('option', '_weight');
                                $weight = $weightMeta ? $weightMeta->value : 'N/A';
                                @endphp
                                <p class="mb-0 text-secondary fw-medium">{{ $weight }} {{$is_weight ? 'Kg': 'Item'}} </p>
                            </div>
                            <div class="product-pricesss">
                                @php
                                $priceMeta = $product->productMeta->firstWhere('option', '_price');
                                $price = $priceMeta ? $priceMeta->value : 'N/A';
                                @endphp
                                @if ($discountData)
                                <span class="theme-color"><del class="original-price">${{ $price }}</del></span>
                                <span class="discounted-price text-success">${{ $finalPrice }}</span>
                                @else
                                <p class="product-price mb-0">${{ $price }} </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 mt-4">
                        <span class="mt-4">No Products Available In This Category</span>
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Section 5 End -->

<!-- Section 6 -->
<div data-aos="fade-up" class="faq-section position-relative bg-black pt-8">
    <!-- Background Image -->
    <div class="bg-overlay"></div>

    <!-- FAQ Items Container with h-100 -->
    <div class="d-flex align-items-end justify-content-center">
        <div class="z-3 w-100 position-absolute bottom-0">
            <div class="container">
                <div class="row g-lg-4" id="faqContainer">
                    <!-- FAQ items will be inserted here by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Section 6 End -->

<!-- Section 7 -->
<section data-aos="fade-up" class="Sec7">
    <img src="{{ asset('WebsiteAssets/assets/img/sec7-btm-img.png') }}" class="Sec7_bottom_img" alt="Img" />
    <div class="container mx-auto px-xxl-4 Sec7_Main_Div">
        <div class="text-center mb-8">
            <div class="position-relative d-flex flex-column align-items-center mb-5">
                <img src="{{ asset('WebsiteAssets/assets/img/logo-dark.png') }}" alt="Professional butcher" width="140"
                    height="auto" class="object-contain" />
                <span class="Sec5_Header_h3 text-uppercase tracking-wide d-block">
                    Prime Meats
                </span>
                <h1 class="display-4 fw-bold text-black">FEATURED PRODUCTS</h1>
            </div>
        </div>

        <div class="position-relative product-slider-container">
            <!-- Navigation Arrows -->
            <button class="slider-nav-btn slider-nav-prev swiper-button-prev">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="swiper swiper_Hoome product-swiper">
                <div class="swiper-wrapper">
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

                    <div class="swiper-slide col-12 col-sm-6 col-xl-4 mb-4">
                        <div class="card product-card" >
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
                            <div class="card-body text-center p-2">
                                <!-- Product Name -->
                                <div>
                                    <a class="Product_Links"
                                        href="/product-detail/{{ $product->permalink }}">
                                        <h5 class="Product_card-title mb-0">{{ strlen($product->title) > 24 ? substr($product->title, 0, 24) . '...' : $product->title }}</h5>
                                    </a>
                                </div>



                                <!-- Weight Input -->


                                <!-- Dynamic Price Display -->
                                <div class="price-display Respon_sive_ness my-3" id="price-display-{{ $product->id }}">
                                    @if ($discountData)
                                    <div class="row">
                                        <div class="col-md-5 centeredd" style="text-align: left; font-size: 14px;">Price</div>
                                        <div class="col-md-7">
                                            <p class="card-text text-muted centeredd mb-2 mt-0" style="text-align: left;">
                                                <span class="theme-color"><del class="original-price">${{ $price }}</del></span>
                                                <span class="discounted-price text-success">${{ $finalPrice }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    @else
                                    <div class="row">
                                        <div class="col-md-5 centeredd" style="text-align: left; font-size: 14px;">Price</div>
                                        <div class="col-md-7">
                                            <p class="card-text text-muted centeredd mb-2 mt-0" style="text-align: left;">
                                                <span class="current-price theme-color">${{ $finalPrice }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    @endif
                                    <div class="row mb-3">
                                        <div class="col-md-5 centeredd" style="text-align: left;"> <label class="form-label small">@if($is_weight==1) Weight (KG) @else Item @endif</label></div>
                                        <div class="col-md-4 Inputs_Centered_div">
                                            <input type="number"
                                                class="form-control form-control-sm weight-input Inputs_Centered"
                                                data-product-id="{{ $product->id }}"
                                                data-per-kg-price="{{ $perKgFinalPrice }}"
                                                data-original-per-kg-price="{{ $perKgPrice }}"
                                                data-has-discount="{{ $discountData ? 'true' : 'false' }}"
                                                data-is-weight="{{ $is_weight }}"
                                                data-minimum-weight="{{ $minimum_weight/1000 }}"
                                                data-minimum-items="{{ $minimum_items }}"
                                                value="{{ $weight }}"
                                                min="{{ $is_weight == 1 ? '0.1' : '1' }}"
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
                                    <input type="hidden" name="original_price" value="{{ $finalPrice }}">
                                    <input type="hidden" name="original_weight" value="{{ $weight }}">
                                    <button class="btn btn-danger w-50 add_custom_button add-to-cart-btn" type="submit">
                                        ADD TO CART
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-dark out-of-stock-btn " style="font-size: 15px; line-height:20px;">
                                    OUT OF STOCK
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <button class="slider-nav-btn slider-nav-next swiper-button-next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>
<!-- Section 7 End -->

<!-- Section 10 -->
<section data-aos="fade-up" class="SECTION__10" style=" position: relative !important;">
    <img src="/WebsiteAssets/assets/img/footer-bg.webp" alt="" class="footer-bg" style="
            position: absolute !important;
            inset: 0 !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            pointer-events: none !important;
            opacity: 0.2 !important;
        ">
    <div class="container mx-auto px-xxl-4 Sec10_Main_Div">
        <div class="row">
            <div class="col-sm-1 col-xl-3"></div>
            <div class="col-sm-10 col-xl-6">
                <div class="Sec10_img">
                    <a href="{{ url('/shop') }}">
                        <img
                            src="{{ asset('/WebsiteAssets/assets/img/Sec10-1.png')}}"
                            alt="" />
                    </a>
                </div>
                <div id="gap0" class="Sec10_gap-element"></div>
                <h3 class="Sec10_h3">
                    We offer both home delivery and a convenient click & collect option.
                </h3>
                <div id="gap1" class="Sec10_gap-element"></div>
                <div class="Sec10_img">
                    <a href="{{ url('/shop') }}">
                        <img
                            src="{{ asset('/WebsiteAssets/assets/img/Sec10-2.png')}}"
                            alt="" />
                    </a>
                </div>
            </div>
            <div class="col-sm-1 col-xl-3"></div>
        </div>
    </div>
</section>
<!-- Section 10 End -->

<!-- Section 8 -->
<section data-aos="fade-up" class="why-choose-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="position-relative d-flex flex-column align-items-center">
                <img src="{{ asset('WebsiteAssets/assets/img/logo-dark.png') }}" alt="Professional butcher" width="140"
                    class="object-contain mx-auto" />
                <h3 class="text-uppercase Sec5_Header_h3 mb-2">WHY CHOOSE US</h3>
                <h1 class="display-4 fw-bold">
                    WE PROVIDE BEST MEAT <br />
                    FROM OUR FARM
                </h1>
            </div>
        </div>

        <div class="row justify-content-center text-center">
            <div class="col-12">
                <div class="row align-items-center justify-content-center">

                    <!-- Left Features -->
                    <div class="col-12 col-lg-4 d-flex flex-column align-items-center">
                        <div class="feature-item text-center">
                            <div class="sec8_logo_width d-flex justify-content-center">
                                <div class="feature-item-image">
                                    <span class="icon-grass"></span>
                                </div>
                            </div>
                            <div class="sec8_details_width">
                                <h4 class="feature-title">100% GRASS-FED</h4>
                                <p class="feature-text">
                                    A meat shop is a retail establishment that specializes in
                                </p>
                            </div>
                        </div>

                        <div class="feature-item text-center">
                            <div class="sec8_logo_width d-flex justify-content-center">
                                <div class="feature-item-image">
                                    <span class="icon-fast-delivery"></span>
                                </div>
                            </div>
                            <div class="sec8_details_width">
                                <h4 class="feature-title">FREE HOME DELIVERY</h4>
                                <p class="feature-text">
                                    A meat shop is a retail establishment that specializes in
                                </p>
                            </div>
                        </div>

                        <div class="feature-item text-center">
                            <div class="sec8_logo_width d-flex justify-content-center">
                                <div class="feature-item-image">
                                    <span class="icon-achievement"></span>
                                </div>
                            </div>
                            <div class="sec8_details_width">
                                <h4 class="feature-title">QUALITY CONTROL</h4>
                                <p class="feature-text">
                                    A meat shop is a retail establishment that specializes in
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Center Image -->
                    <div class="col-12 col-lg-4 text-center">
                        <img src="{{ asset('WebsiteAssets/assets/img/section8-img.png') }}" class="meat-image mx-auto"
                            alt="Premium Meat Cuts" />
                    </div>

                    <!-- Right Features -->
                    <div class="col-12 col-lg-4 d-flex flex-column align-items-center">
                        <div class="feature-item text-center">
                            <div class="sec8_logo_width d-flex justify-content-center">
                                <div class="feature-item-image">
                                    <span class="icon-no-preservatives"></span>
                                </div>
                            </div>
                            <div class="sec8_details_width">
                                <h4 class="feature-title">NO CHEMICAL USE</h4>
                                <p class="feature-text">
                                    A meat shop is a retail establishment that specializes in
                                </p>
                            </div>
                        </div>

                        <div class="feature-item text-center">
                            <div class="sec8_logo_width d-flex justify-content-center">
                                <div class="feature-item-image">
                                    <span class="icon-healthcare-1"></span>
                                </div>
                            </div>
                            <div class="sec8_details_width">
                                <h4 class="feature-title">HEALTH AND SAFETY</h4>
                                <p class="feature-text">
                                    A meat shop is a retail establishment that specializes in
                                </p>
                            </div>
                        </div>

                        <div class="feature-item text-center">
                            <div class="sec8_logo_width d-flex justify-content-center">
                                <div class="feature-item-image">
                                    <span class="icon-meat-5"></span>
                                </div>
                            </div>
                            <div class="sec8_details_width">
                                <h4 class="feature-title">100% FRESH PRODUCE</h4>
                                <p class="feature-text">
                                    A meat shop is a retail establishment that specializes in
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 8 End -->
<!-- Section 9 -->
<div style="background-color: #f6f2ec">
    <section class="container pt-5 pb-5 Slider_section">
        <div class="row mt-2 rounded-5 counter-sec justify-content-around">
            <div class="col-12 user-images position-relative">
                <div class="slider">
                    @foreach($brands as $brand)
                    <div class="w-card-4">
                        <img class="card-img-top" src="{{ asset($brand->logo_image) }}" alt="Card image cap" />
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Section 9 End -->
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Statistics Animation
        const statNumbers = document.querySelectorAll('.stat-number');

        const animateCount = (element, finalValue) => {
            let startValue = 0;
            const duration = 5000; // 2 seconds for the animation
            const increment = finalValue / (duration / 100); // How much to increment every 100ms
            let frame;

            const step = () => {
                startValue += increment;
                if (startValue >= finalValue) {
                    startValue = finalValue; // Ensure we stop at the final value
                    cancelAnimationFrame(frame); // Stop the animation once the target is reached
                }

                // Format the number with "K" if it's over 1000 during the animation
                const formattedValue = formatNumberWithK(startValue);
                element.textContent = formattedValue; // Update the number displayed
                frame = requestAnimationFrame(step); // Repeat the animation frame
            };

            frame = requestAnimationFrame(step); // Start the animation
        };

        // Function to format the number with "K"
        const formatNumberWithK = (value) => {
            if (value >= 1000) {
                // If the number is a whole number, do not show decimal points
                const valueInK = value / 1000;
                return valueInK % 1 === 0 ? `${valueInK}K` : `${valueInK.toFixed(1)}K`; // Check if the value is whole
            }
            return Math.floor(value); // Otherwise just return the number
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Start animating the number when it comes into view
                    const statNumber = entry.target;
                    const finalValue = parseInt(statNumber.getAttribute('data-count'));
                    animateCount(statNumber, finalValue);

                    observer.unobserve(statNumber); // Stop observing this stat after animation starts
                }
            });
        }, {
            threshold: 0.5
        });

        // Observe each stat-number element
        statNumbers.forEach(statNumber => {
            observer.observe(statNumber);
        });
    });

   

    document.addEventListener('DOMContentLoaded', function() {
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
    });


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
</script>
@endpush