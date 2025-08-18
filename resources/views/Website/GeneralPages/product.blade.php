@include('Website.Includes.header', ['meta_Details' => $meta_details])
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\ProductClass;
    use App\General\DiscountClass;
    $logged_in_user = Auth::user();
@endphp
<style>
    ::-webkit-scrollbar {
        width: 5px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 5px grey;
        border-radius: 10px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #E59041;
        border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #E59041;
    }


    /* toaster */

    .toast {
        background-color: #333;
        color: #fff;
    }

    .toast-success {
        background-color: #28a745 !important;
    }

    .toast-error {
        background-color: #dc3545 !important;
    }

    .toast-info {
        background-color: #17a2b8 !important;
    }

    .toast-warning {
        background-color: #ffc107 !important;
    }
</style>

<!-- MULTITAB SECTION -->
<section class="mtop-50">
    <div class="container pt-3">
        <!-- LATEAST TOOL STORE -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="collection-view-top-section">
                    <div class="d-flex justify-content-between">
                        <h1 class="product-view-topimg-title">{{ $product->title }}</h1>
                        @if (isset($brandlogo->logo_image) && !empty($brandlogo->logo_image))
                            <img src="{{ url('/') . '/' . $brandlogo->logo_image ?? '' }}" width="100px" alt="">
                        @endif
                    </div>
                    <p class="mb-0 d-flex">
                        Home -
                        @php
                            $categoryTitles = ProductClass::getCategoryTitlesinfo($categories, $product->category_id);
                            $chilcateogories = ProductClass::getChildCategoryTitles($childcats, $product->child_category);
                            $grandchildcategories = ProductClass::getgrandCategoryTitles($grandcats, $product->grand_child_category);
                        @endphp
                        @foreach ($categoryTitles as $category)
                            <a href="{{ url('/') }}/category/{{ $category['sku'] }}"
                                class="collection-view-linked-desc texttracate">
                                {{ $category['title'] }}
                            </a>
                        @endforeach
                        @foreach ($chilcateogories as $chilcateogorie)
                            <a href="{{ url('/') }}/child-category/{{ $chilcateogorie['child_sku'] }}"
                                class="collection-view-linked-desc texttracate">
                                - {{ $chilcateogorie['child_title'] }}
                            </a>
                        @endforeach
                        @foreach ($grandchildcategories as $grandchildcategorie)
                            <a href="{{ url('/') }}/grand-child-category/{{ $grandchildcategorie['grand_sku'] }}"
                                class="collection-view-linked-desc texttracate">
                                - {{ $grandchildcategorie['grand_title'] }}
                            </a>
                        @endforeach
                        - {{ $product->title }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row ptop-30">
            <div class="col-lg-7 col-md-7 col-sm-12">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-12" style="max-height:550px; overflow:auto">
                        @php
                            $imagePaths = explode(',', $product->gallery_media);
                        @endphp
                        @foreach ($imagePaths as $imagePath)
                            <a href="#" class="product-view-textDecor image-link"
                                data-image="{{ asset($imagePath) }}">
                                <div class="product-view-images-item">
                                    <img src="{{ asset($imagePath) }}" alt="" class="img-fluid">
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-12">
                        <div class="product-view-main-image"></div>
                        <div class="product-view-imges" id="main-product-image">
                            <img src="{{ asset($product->featured_image) }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12">
                <div class="row">
                    @php
                        $discountedPrice = 0;
                        if (isset($disocuntonproduct)) {
                            $currentDate = time();
                            $fromDate = strtotime($disocuntonproduct->from_date);
                            $toDate = strtotime($disocuntonproduct->end_date);
                            if ($disocuntonproduct->active == 1) {
                                if ($disocuntonproduct->limited == 1) {
                                    if ($currentDate >= $fromDate && $currentDate <= $toDate) {
                                        $discountValue = $disocuntonproduct->discount_value;
                                        $actualPrice = ProductClass::getOptionValue('_price', $product->productMeta);
                                        if ($disocuntonproduct->discount_type == 1) {
                                            $discountedPrice = $actualPrice - $discountValue;
                                        } else {
                                            $discountAmount = ($discountValue / 100) * $actualPrice;
                                            $discountedPrice = $actualPrice - $discountAmount;
                                        }
                                    } else {
                                        $discountedPrice = ProductClass::getOptionValue('_price', $product->productMeta);
                                    }
                                } else {
                                    $discountValue = $disocuntonproduct->discount_value;
                                    $actualPrice = ProductClass::getOptionValue('_price', $product->productMeta);
                                    if ($disocuntonproduct->discount_type == 1) {
                                        $discountedPrice = $actualPrice - $discountValue;
                                    } else {
                                        $discountAmount = ($discountValue / 100) * $actualPrice;
                                        $discountedPrice = $actualPrice - $discountAmount;
                                    }
                                }
                            } else {
                                $discountedPrice = ProductClass::getOptionValue('_price', $product->productMeta);
                            }
                        } elseif ($collectionDiscount) {
                            $currentDate = time();
                            $fromDate = strtotime($collectionDiscount->from_date);
                            $toDate = strtotime($collectionDiscount->end_date);
                            if ($collectionDiscount->active == 1) {
                                if ($collectionDiscount->limited == 1) {
                                    if ($currentDate >= $fromDate && $currentDate <= $toDate) {
                                        $discountValue = $collectionDiscount->discount_value;
                                        $actualPrice = ProductClass::getOptionValue('_price', $product->productMeta);
                                        if ($collectionDiscount->discount_type == 1) {
                                            $discountedPrice = $actualPrice - $discountValue;
                                        } else {
                                            $discountAmount = ($discountValue / 100) * $actualPrice;
                                            $discountedPrice = $actualPrice - $discountAmount;
                                        }
                                    } else {
                                        $discountedPrice = ProductClass::getOptionValue('_price', $product->productMeta);
                                    }
                                } else {
                                    $discountValue = $collectionDiscount->discount_value;
                                    $actualPrice = ProductClass::getOptionValue('_price', $product->productMeta);
                                    if ($collectionDiscount->discount_type == 1) {
                                        $discountedPrice = $actualPrice - $discountValue;
                                    } else {
                                        $discountAmount = ($discountValue / 100) * $actualPrice;
                                        $discountedPrice = $actualPrice - $discountAmount;
                                    }
                                }
                            } else {
                                $discountedPrice = ProductClass::getOptionValue('_price', $product->productMeta);
                            }
                        } else {
                            $discountedPrice = ProductClass::getOptionValue('_price', $product->productMeta);
                        }
                    @endphp
                    @if (isset($disocuntonproduct) ||
                            (isset($collectionDiscount) &&
                                $discountedPrice !== ProductClass::getOptionValue('_price', $product->productMeta)))
                        @if (
                            ($disocuntonproduct && $disocuntonproduct->limited == 1) ||
                                ($collectionDiscount && $collectionDiscount->limited == 1))
                            @if ($currentDate >= $fromDate && $currentDate <= $toDate)
                                @if ($disocuntonproduct && $disocuntonproduct->active == 1)
                                    <div class="col-lg-5 col-md-6 col-sm-12">
                                        <p class="product-view-offer-desc">
                                            {{ $disocuntonproduct->title ?? $collectionDiscount->title }}</p>
                                        <h1 class="product-view-offer-amount-l">
                                            <del>${{ ProductClass::getOptionValue('_price', $product->productMeta) }}</del>
                                        </h1>
                                    </div>
                                @elseif ($collectionDiscount && $collectionDiscount->active == 1)
                                    <div class="col-lg-5 col-md-6 col-sm-12">
                                        <p class="product-view-offer-desc">
                                            {{ $disocuntonproduct->title ?? $collectionDiscount->title }}</p>
                                        <h1 class="product-view-offer-amount-l">
                                            <del>${{ ProductClass::getOptionValue('_price', $product->productMeta) }}</del>
                                        </h1>
                                    </div>
                                @else
                                    <div class="col-lg-5 col-md-6 col-sm-12 d-flex align-items-end">
                                        <div>
                                            @if ($product->productMeta->where('option', '_gst')->first()->value ?? 0)
                                                <p class="d-none product-view-offer-desc">GST Included</p>
                                            @endif
                                            <h1 class="product-view-offer-amount-r" id="product_price">
                                                @if (isset($discountedPrice))
                                                    ${{ $discountedPrice }}
                                                @else
                                                    ${{ ProductClass::getOptionValue('_price', $product->productMeta) }}
                                                @endif
                                            </h1>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @else
                            @if ($disocuntonproduct && $disocuntonproduct->active == 1)
                                <div class="col-lg-5 col-md-6 col-sm-12">
                                    <p class="product-view-offer-desc">
                                        {{ $disocuntonproduct->title ?? $collectionDiscount->title }}</p>
                                    <h1 class="product-view-offer-amount-l">
                                        <del>${{ ProductClass::getOptionValue('_price', $product->productMeta) }}</del>
                                    </h1>
                                </div>
                            @elseif ($collectionDiscount && $collectionDiscount->active == 1)
                                <div class="col-lg-5 col-md-6 col-sm-12">
                                    <p class="product-view-offer-desc">
                                        {{ $disocuntonproduct->title ?? $collectionDiscount->title }}</p>
                                    <h1 class="product-view-offer-amount-l">
                                        <del>${{ ProductClass::getOptionValue('_price', $product->productMeta) }}</del>
                                    </h1>
                                </div>
                            @else
                                <div class="col-lg-5 col-md-6 col-sm-12 d-flex align-items-end">
                                    <div>
                                        @if ($product->productMeta->where('option', '_gst')->first()->value ?? 0)
                                            <p class="d-none product-view-offer-desc">GST Included</p>
                                        @endif
                                        <h1 class="product-view-offer-amount-r" id="product_price">
                                            @if (isset($discountedPrice))
                                                ${{ $discountedPrice }}
                                            @else
                                                ${{ ProductClass::getOptionValue('_price', $product->productMeta) }}
                                            @endif
                                        </h1>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif

                    <div class="col-lg-5 col-md-6 col-sm-12 d-flex align-items-end">
                        <div>
                            @if ($product->productMeta->where('option', '_gst')->first()->value ?? 0)
                                <p class="d-none product-view-offer-desc">GST Included</p>
                            @endif
                            <h1 class="product-view-offer-amount-r" id="product_price">
                                @if (isset($discountedPrice))
                                    ${{ $discountedPrice }}
                                @else
                                    ${{ ProductClass::getOptionValue('_price', $product->productMeta) }}
                                @endif
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="row" style="display: none;">
                    <div class="col-lg-10 col-md-12 col-sm-12">
                        <div class="productview-postcode-section">
                            <p class="productview-postcode-title"> <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 3H1V16H16V3Z" stroke="#E69041" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M16 8H20L23 11V16H16V8Z" stroke="#E69041" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M5.5 21C6.88071 21 8 19.8807 8 18.5C8 17.1193 6.88071 16 5.5 16C4.11929 16 3 17.1193 3 18.5C3 19.8807 4.11929 21 5.5 21Z"
                                            stroke="#E69041" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M18.5 21C19.8807 21 21 19.8807 21 18.5C21 17.1193 19.8807 16 18.5 16C17.1193 16 16 17.1193 16 18.5C16 19.8807 17.1193 21 18.5 21Z"
                                            stroke="#E69041" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>

                                </span>Where do you want it?</p>
                            <input type="text" class="form-control POSTCODE-INP" name="post_code" id="post_code"
                                placeholder="Postcode">
                            <div id="messageContainer" class="m-1" style="color:red;font-size: x-small;"></div>
                            <input type="hidden" id="product_unique_id" name="p_id" value="{{ $product->id }}">
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-5 col-md-12 col-sm-12 ">
                        <div class="prtoduct-vieww-counter prtoduct-vieww-counter-top-r quantity_btn">
                            <button class="prtoduct-vieww-counter-btn-plus decreameant-btn"
                                data-product-id="{{ $product->id }}">-</button>
                            <input id=demoInput type=number
                                class="prtoduct-vieww-counter-inp demoInput{{ $product->id }}" name="item_count"
                                value="1" min=1 max=100>
                            <button class="prtoduct-vieww-counter-btn-minus increment-btn"
                                data-product-id="{{ $product->id }}">+</button>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12 col-sm-12 ">
                        @php
                            $disable = false;
                            $stock = $product->productMeta->where('option', '_stock')->first()->value;
                            if ($stock == 0) {
                                $disable = true;
                                $buttonText = 'Out of Stock';
                            } else {
                                $disable = false;
                                $buttonText = 'Add to Cart';
                            }
                        @endphp
                        <div>
                            <button
                                @if ($disable) disabled @endif
                                class="prtoduct-vieww-add-tocart-btn futured-product-add-tocart-btn"
                                data-gst="{{ $product->productMeta->where('option', '_gst')->first()->value ?? 0 }}"
                                data-title="{{ $product->title }}" data-amount="{{ $discountedPrice }}"
                                data-p_id="{{ $product->id }}"
                                data-featured_image="{{ url('/') . '/' . $product->featured_image }}">
                                @if ($disable)
                                    Out of Stock
                                @else
                                    Add to Cart
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 ptop-30">
                        <div class="product-kit-padding-top-20">
                            <h1 class="product-detail-main-title-kit"><img src="images/Group 2 (1).png"
                                    class="me-2" alt=""> Key Features</h1>
                        </div>
                        <div>
                            <div class="individual-key-feature">{!! $product->short_description !!}</div>
                            <!-- <ul class="individual-key-feature">
                                    <li>{!! $product->short_description !!}</li>
                                </ul> -->
                        </div>
                        @if (isset($disocuntonproduct))
                            <div class="product-individual-detail-tags d-flex">
                                @php
                                    $textPaths = explode(',', $disocuntonproduct->badge_text);
                                    $imagePaths = explode(',', $disocuntonproduct->badge_image);
                                @endphp
                                @foreach ($textPaths as $textPath)
                                    <div class="rounded d-flex align-items-center px-2 me-1"
                                        style="border:2px solid {{ $disocuntonproduct->badge_color }}; color: {{ $disocuntonproduct->badge_color }}">
                                        {{ $textPath }}</div>
                                @endforeach
                                @foreach ($imagePaths as $imagePath)
                                    <div>
                                        <img src="{{ asset($imagePath) }}" alt="" class="img-fluid">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Description -->
        <div class="row pt-4">
            <div class="col-lg-8 col-md-7 col-sm-12 individual-border-r mt-3">
                <div class="collection-view-top-section ">
                    <h1 class="product-detail-main-title"> Description </h1>

                </div>
                <div>
                    <p class="individual-product-sub-title">{{ $product->title }}</p>
                    <p class="individual-product-sub-desc">{!! $product->long_description !!}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-sm-12 mt-3">
                <div class="collection-view-top-section ">
                    <h1 class="product-detail-main-title"> Technical Specifications </h1>
                </div>
                @if (isset($brandlogo))
                    <div>
                        <p class="individual-product-sub-title">Brand</p>
                        <p class="individual-product-sub-desc">{{ $brandlogo->title }}</p>
                    </div>
                @endif

                @if (isset($categoryTitles))
                    <div>
                        <p class="individual-product-sub-title">Category</p>
                        <p class="individual-product-sub-desc">
                            @foreach ($categoryTitles as $category)
                                {{ $category['title'] }},
                            @endforeach

                            @foreach ($chilcateogories as $chilcateogorie)
                                {{ $chilcateogorie['child_title'] }},
                            @endforeach

                            @foreach ($grandchildcategories as $grandchildcategorie)
                                {{ $grandchildcategorie['grand_title'] }}
                            @endforeach
                        </p>
                    </div>
                @endif

                @if (isset($product))
                    <div>
                        <p class="individual-product-sub-title">SKU</p>
                        <p class="individual-product-sub-desc">{{ $product->sku }}</p>
                    </div>
                @endif
                @if (isset($product->type))
                    <div>
                        <p class="individual-product-sub-title">Type</p>
                        <p class="individual-product-sub-desc">{{ $product->type->title }}</p>
                    </div>
                @endif
                @if (ProductClass::getOptionValue('_weight', $product->productMeta) != null)
                    <div>
                        <p class="individual-product-sub-title">Weight</p>
                        <p class="individual-product-sub-desc">
                            {{ ProductClass::getOptionValue('_weight', $product->productMeta) }}</p>
                    </div>
                @endif

                <div class="collection-view-top-section mt-2">
                    <h1 class="product-detail-main-title"> Ask an Expert
                        <span class="individual-review-add2" data-bs-toggle="modal" value="{{ $product->id }}"
                            data-bs-target="#experqueryModal">Ask</span>
                    </h1>
                </div>
            </div>
        </div>

        @if ($featuredproducts)
            <div class="row mtop-50 pt-4">
                <div class="col-lg-12 col-md-12 col-sm12">
                    <p class="home-tool-categories-title">Featured Products</p>
                </div>
            </div>
            <section id="demos" class="ptop-30">
                <div class="row">
                    <div class="large-12 columns">
                        <div class="owl-carousel owl-carousel-futured-product owl-theme ">
                            @foreach ($featuredproducts as $featuredproduct)
                                <div class="item">
                                    <div class="futured-product-col-b">
                                        @if (isset($featuredproduct->discount))
                                            <?php
                                            $currentDate = null;
                                            $fromDate = null;
                                            $toDate = null;
                                            $currentDate = time();
                                            $fromDate = strtotime($featuredproduct->discount->from_date);
                                            $toDate = strtotime($featuredproduct->discount->end_date);
                                            ?>
                                            @if ($featuredproduct->discount->active == 1)
                                                @if ($featuredproduct->discount->limited == 1)
                                                    @if ($currentDate >= $fromDate && $currentDate <= $toDate)
                                                        <p class="featured-product-discount">
                                                            @if ($featuredproduct->discount->discount_type == 0)
                                                                Save {{ $featuredproduct->discount->discount_value }}%
                                                            @else
                                                                Save {{ $featuredproduct->discount->discount_value }}$
                                                            @endif
                                                        </p>
                                                    @endif
                                                @else
                                                    <p class="featured-product-discount">
                                                        @if ($featuredproduct->discount->discount_type == 0)
                                                            Save {{ $featuredproduct->discount->discount_value }}%
                                                        @else
                                                            Save {{ $featuredproduct->discount->discount_value }}$
                                                        @endif
                                                    </p>
                                                @endif
                                            @endif
                                        @elseif (DiscountClass::check_collection_discount($featuredproduct->id))
                                            <?php
                                            $collection = DiscountClass::check_collection_discount($featuredproduct->id);
                                            $currentDate = null;
                                            $fromDate = null;
                                            $toDate = null;
                                            $currentDate = time();
                                            $fromDate = strtotime($collection->from_date);
                                            $toDate = strtotime($collection->end_date);
                                            ?>
                                            @if ($collection->active == 1)
                                                @if ($collection->limited == 1)
                                                    @if ($currentDate >= $fromDate && $currentDate <= $toDate)
                                                        <p class="featured-product-discount">
                                                            @if ($collection->discount_type == 0)
                                                                Save {{ $collection->discount_value }}%
                                                            @else
                                                                Save {{ $collection->discount_value }}$
                                                            @endif
                                                        </p>
                                                    @endif
                                                @else
                                                    <p class="featured-product-discount">
                                                        @if ($collection->discount_type == 0)
                                                            Save {{ $collection->discount_value }}%
                                                        @else
                                                            Save {{ $collection->discount_value }}$
                                                        @endif
                                                    </p>
                                                @endif
                                            @endif
                                        @endif
                                        <div class="futured-tool-section">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-12">
                                                    <div class="futured-product-image">
                                                        <img src="{{ url('/') . '/' . $featuredproduct->featured_image }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-12">
                                                    <div class="futured-product-details">
                                                        <p class="futured-product-title"><a
                                                                href="{{ url('/') }}/product/{{ $featuredproduct->permalink }}"
                                                                class="futured-product-TextDecor texttracate">{{ $featuredproduct->title }}</a>
                                                        </p>
                                                        <p class="latest-product-amount">
                                                            <?php
                                                            $discountedPriceHtml = null;
                                                            $actualPrice = null;
                                                            if (isset($featuredproduct->discount)) {
                                                                if ($featuredproduct->discount->active == 1) {
                                                                    if ($featuredproduct->discount->limited == 1) {
                                                                        if ($currentDate >= $fromDate && $currentDate <= $toDate) {
                                                                            $currentDate = time();
                                                                            $fromDate = strtotime($featuredproduct->discount->from_date);
                                                                            $toDate = strtotime($featuredproduct->discount->end_date);

                                                                            if ($featuredproduct->discount->discount_type == 0) {
                                                                                $discountedPriceHtml = number_format($featuredproduct->productMeta->where('option', '_price')->first()->value - ($featuredproduct->productMeta->where('option', '_price')->first()->value * ($featuredproduct->discount->discount_value ?? 0)) / 100, 2);
                                                                                $actualPrice = '$' . $featuredproduct->productMeta->where('option', '_price')->first()->value;
                                                                            } else {
                                                                                $discountedPriceHtml = intval($featuredproduct->productMeta->where('option', '_price')->first()->value - ($featuredproduct->discount->discount_value ?? 0));
                                                                                $actualPrice = '$' . $featuredproduct->productMeta->where('option', '_price')->first()->value;
                                                                            }
                                                                        } else {
                                                                            $actualPrice = null;
                                                                        }
                                                                    } else {
                                                                        if ($featuredproduct->discount->discount_type == 0) {
                                                                            $discountedPriceHtml = number_format($featuredproduct->productMeta->where('option', '_price')->first()->value - ($featuredproduct->productMeta->where('option', '_price')->first()->value * ($featuredproduct->discount->discount_value ?? 0)) / 100, 2);
                                                                            $actualPrice = '$' . $featuredproduct->productMeta->where('option', '_price')->first()->value;
                                                                        } else {
                                                                            $discountedPriceHtml = intval($featuredproduct->productMeta->where('option', '_price')->first()->value - ($featuredproduct->discount->discount_value ?? 0));
                                                                            $actualPrice = '$' . $featuredproduct->productMeta->where('option', '_price')->first()->value;
                                                                        }
                                                                    }
                                                                }
                                                            } elseif (DiscountClass::check_collection_discount($featuredproduct->id)) {
                                                                if ($collection->active == 1) {
                                                                    if ($collection->limited == 1) {
                                                                        if ($currentDate >= $fromDate && $currentDate <= $toDate) {
                                                                            $currentDate = time();
                                                                            $fromDate = strtotime($collection->from_date);
                                                                            $toDate = strtotime($collection->end_date);

                                                                            if ($collection->discount_type == 0) {
                                                                                $discountedPriceHtml = number_format($featuredproduct->productMeta->where('option', '_price')->first()->value - ($featuredproduct->productMeta->where('option', '_price')->first()->value * ($collection->discount_value ?? 0)) / 100, 2);
                                                                                $actualPrice = '$' . $featuredproduct->productMeta->where('option', '_price')->first()->value;
                                                                            } else {
                                                                                $discountedPriceHtml = intval($featuredproduct->productMeta->where('option', '_price')->first()->value - ($collection->discount_value ?? 0));
                                                                                $actualPrice = '$' . $featuredproduct->productMeta->where('option', '_price')->first()->value;
                                                                            }
                                                                        } else {
                                                                            $actualPrice = null;
                                                                        }
                                                                    } else {
                                                                        if ($collection->discount_type == 0) {
                                                                            $discountedPriceHtml = number_format($featuredproduct->productMeta->where('option', '_price')->first()->value - ($featuredproduct->productMeta->where('option', '_price')->first()->value * ($collection->discount_value ?? 0)) / 100, 2);
                                                                            $actualPrice = '$' . $featuredproduct->productMeta->where('option', '_price')->first()->value;
                                                                        } else {
                                                                            $discountedPriceHtml = intval($featuredproduct->productMeta->where('option', '_price')->first()->value - ($collection->discount_value ?? 0));
                                                                            $actualPrice = '$' . $featuredproduct->productMeta->where('option', '_price')->first()->value;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            ${{ $discountedPriceHtml ?? $featuredproduct->productMeta->where('option', '_price')->first()->value }}
                                                            <small
                                                                class="product-detail-discount"><del>{{ $actualPrice }}</del></small>
                                                        </p>
                                                        <div>
                                                            <div class="row">

                                                                <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                                                                    <div>
                                                                        @php
                                                                            $disable = false;
                                                                            $buttonText = null;
                                                                            $stock = $featuredproduct->productMeta->where('option', '_stock')->first()->value;
                                                                            if ($stock == 0) {
                                                                                $disable = true;
                                                                                $buttonText = 'Out of Stock';
                                                                            } else {
                                                                                $disable = false;
                                                                                $buttonText = 'Add to Cart';
                                                                            }
                                                                        @endphp
                                                                        <button class="futured-product-add-tocart-btn"
                                                                            @if ($disable) disabled @endif
                                                                            data-gst="{{ $featuredproduct->productMeta->where('option', '_gst')->first()->value ?? 0 }}"
                                                                            data-title="{{ $featuredproduct->title }}"
                                                                            data-amount="{{ $discountedPriceHtml ?? $featuredproduct->productMeta->where('option', '_price')->first()->value }}"
                                                                            data-p_id="{{ $featuredproduct->id }}"
                                                                            data-featured_image="{{ url('/') . '/' . $featuredproduct->featured_image }}">
                                                                            @if ($disable)
                                                                                Out of Stock
                                                                            @else
                                                                                Add to Cart
                                                                            @endif
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('.owl-carousel-futured-product').owlCarousel({
                                    loop: true,
                                    margin: 10,
                                    nav: true,
                                    navText: [
                                        "<div class='nav-button owl-prev'><img src='{{ url('/') }}/WebsiteAssets/images/Group 5.png' alt=''></div>",
                                        "<div class='nav-button owl-next'><img src='{{ url('/') }}/WebsiteAssets/images/Group 4.png' alt=''></div>"
                                    ],
                                    responsiveClass: true,
                                    responsive: {
                                        0: {
                                            items: 1,
                                            nav: true
                                        },
                                        600: {
                                            items: 1,
                                            nav: true
                                        },
                                        1000: {
                                            items: 3,
                                            nav: true,
                                            loop: false,
                                            margin: 20
                                        }
                                    }
                                })
                            })
                        </script>
                    </div>
                </div>

            </section>
            <div class="pt-3" style="margin-top:30px !important;">
                <p class="view-text-align-r"><a href="/all-featured-products" class="view-category-text5"
                        style="color:#E59041;">View
                        All
                    </a></p>
            </div>
        @endif
        <!--  -->
        @if ($categoryproducts->isNotEmpty())
            <div class="row productview-mt-30px">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h1 class="productview-heading"><img
                            src="{{ url('/') }}/WebsiteAssets/images/Group 2 (1).png" alt="">
                        Other products with similar category
                    </h1>
                </div>
            </div>
            <div class="row ptop-30">
                @foreach ($categoryproducts as $categoryproduct)
                    <div class="col-lg-4 col-md-6 col-sm-12 latest-product-col-b">
                        @if (isset($categoryproduct->discount))
                            <?php
                            $currentDate = null;
                            $fromDate = null;
                            $toDate = null;
                            $currentDate = time();
                            $fromDate = strtotime($categoryproduct->discount->from_date);
                            $toDate = strtotime($categoryproduct->discount->end_date);
                            ?>
                            @if ($categoryproduct->discount->active == 1)
                                @if ($categoryproduct->discount->limited == 1)
                                    @if ($currentDate >= $fromDate && $currentDate <= $toDate)
                                        <p class="featured-product-discount">
                                            @if ($categoryproduct->discount->discount_type == 0)
                                                Save {{ $categoryproduct->discount->discount_value }}%
                                            @else
                                                Save {{ $categoryproduct->discount->discount_value }}$
                                            @endif
                                        </p>
                                    @endif
                                @else
                                    <p class="featured-product-discount">
                                        @if ($categoryproduct->discount->discount_type == 0)
                                            Save {{ $categoryproduct->discount->discount_value }}%
                                        @else
                                            Save {{ $categoryproduct->discount->discount_value }}$
                                        @endif
                                    </p>
                                @endif
                            @endif
                        @elseif (DiscountClass::check_collection_discount($categoryproduct->id))
                            <?php
                            $collection = DiscountClass::check_collection_discount($categoryproduct->id);
                            $currentDate = null;
                            $fromDate = null;
                            $toDate = null;
                            $currentDate = time();
                            $fromDate = strtotime($collection->from_date);
                            $toDate = strtotime($collection->end_date);
                            ?>
                            @if ($collection->active == 1)
                                @if ($collection->limited == 1)
                                    @if ($currentDate >= $fromDate && $currentDate <= $toDate)
                                        <p class="featured-product-discount">
                                            @if ($collection->discount_type == 0)
                                                Save {{ $collection->discount_value }}%
                                            @else
                                                Save {{ $collection->discount_value }}$
                                            @endif
                                        </p>
                                    @endif
                                @else
                                    <p class="featured-product-discount">
                                        @if ($collection->discount_type == 0)
                                            Save {{ $collection->discount_value }}%
                                        @else
                                            Save {{ $collection->discount_value }}$
                                        @endif
                                    </p>
                                @endif
                            @endif
                        @endif
                        <div class="latest-tool-section">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="latest-product-image">
                                        <img src="{{ url('/') . '/' . $categoryproduct->featured_image }}"
                                            alt="">
                                    </div>
                                </div>
                                @php
                                    $disable = false;
                                    $stock = ProductClass::getOptionValue('_stock', $categoryproduct->productMeta);
                                    if ($stock == 0) {
                                        $disable = true;
                                    } else {
                                        $disable = false;
                                    }
                                @endphp
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="latest-product-details">
                                        <p class="latest-product-title texttracate"><a
                                                href="{{ url('/') }}/product/{{ $categoryproduct->permalink }}"
                                                class="latest-product-TextDecor">{{ $categoryproduct->title }}</a>
                                        </p>
                                        <p class="latest-product-amount">
                                            <?php
                                            $discountedPriceHtml = null;
                                            $actualPrice = null;
                                            if (isset($categoryproduct->discount)) {
                                                if ($categoryproduct->discount->active == 1) {
                                                    $currentDate = time();
                                                    $fromDate = strtotime($categoryproduct->discount->from_date);
                                                    $toDate = strtotime($categoryproduct->discount->end_date);
                                                    if ($categoryproduct->discount->limited == 1) {
                                                        if ($currentDate >= $fromDate && $currentDate <= $toDate) {
                                                            if ($categoryproduct->discount->discount_type == 0) {
                                                                $discountedPriceHtml = number_format(ProductClass::getOptionValue('_price', $categoryproduct->productMeta) - (ProductClass::getOptionValue('_price', $categoryproduct->productMeta) * ($categoryproduct->discount->discount_value ?? 0)) / 100, 2);
                                                                $actualPrice = '$' . ProductClass::getOptionValue('_price', $categoryproduct->productMeta);
                                                            } else {
                                                                $discountedPriceHtml = intval(ProductClass::getOptionValue('_price', $categoryproduct->productMeta) - ($categoryproduct->discount->discount_value ?? 0));
                                                                $actualPrice = '$' . ProductClass::getOptionValue('_price', $categoryproduct->productMeta);
                                                            }
                                                        } else {
                                                            $actualPrice = null;
                                                        }
                                                    } else {
                                                        if ($categoryproduct->discount->discount_type == 0) {
                                                            $discountedPriceHtml = number_format(ProductClass::getOptionValue('_price', $categoryproduct->productMeta) - (ProductClass::getOptionValue('_price', $categoryproduct->productMeta) * ($categoryproduct->discount->discount_value ?? 0)) / 100, 2);
                                                            $actualPrice = '$' . ProductClass::getOptionValue('_price', $categoryproduct->productMeta);
                                                        } else {
                                                            $discountedPriceHtml = intval(ProductClass::getOptionValue('_price', $categoryproduct->productMeta) - ($categoryproduct->discount->discount_value ?? 0));
                                                            $actualPrice = '$' . ProductClass::getOptionValue('_price', $categoryproduct->productMeta);
                                                        }
                                                    }
                                                }
                                            } elseif (DiscountClass::check_collection_discount($categoryproduct->id)) {
                                                $collection = DiscountClass::check_collection_discount($categoryproduct->id);
                                                if ($collection->active == 1) {
                                                    $currentDate = time();
                                                    $fromDate = strtotime($collection->from_date);
                                                    $toDate = strtotime($collection->end_date);
                                                    if ($collection->limited == 1) {
                                                        if ($currentDate >= $fromDate && $currentDate <= $toDate) {
                                                            if ($collection->discount_type == 0) {
                                                                $discountedPriceHtml = number_format(ProductClass::getOptionValue('_price', $categoryproduct->productMeta) - (ProductClass::getOptionValue('_price', $categoryproduct->productMeta) * ($collection->discount_value ?? 0)) / 100, 2);
                                                                $actualPrice = '$' . ProductClass::getOptionValue('_price', $categoryproduct->productMeta);
                                                            } else {
                                                                $discountedPriceHtml = intval(ProductClass::getOptionValue('_price', $categoryproduct->productMeta) - ($collection->discount_value ?? 0));
                                                                $actualPrice = '$' . ProductClass::getOptionValue('_price', $categoryproduct->productMeta);
                                                            }
                                                        } else {
                                                            $actualPrice = null;
                                                        }
                                                    } else {
                                                        if ($collection->discount_type == 0) {
                                                            $discountedPriceHtml = number_format(ProductClass::getOptionValue('_price', $categoryproduct->productMeta) - (ProductClass::getOptionValue('_price', $categoryproduct->productMeta) * ($collection->discount_value ?? 0)) / 100, 2);
                                                            $actualPrice = '$' . ProductClass::getOptionValue('_price', $categoryproduct->productMeta);
                                                        } else {
                                                            $discountedPriceHtml = intval(ProductClass::getOptionValue('_price', $categoryproduct->productMeta) - ($collection->discount_value ?? 0));
                                                            $actualPrice = '$' . ProductClass::getOptionValue('_price', $categoryproduct->productMeta);
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            ${{ $discountedPriceHtml ?? ProductClass::getOptionValue('_price', $categoryproduct->productMeta) }}
                                            <small
                                                class="product-detail-discount"><del>{{ $actualPrice }}</del></small>
                                        </p>
                                        <div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                                                    <div>
                                                        <button class="futured-product-add-tocart-btn"
                                                            @if ($disable) disabled @endif
                                                            data-gst="{{ ProductClass::getOptionValue('_gst', $categoryproduct->productMeta) }}"
                                                            data-title="{{ $categoryproduct->title }}"
                                                            data-amount="{{ $discountedPriceHtml ?? ProductClass::getOptionValue('_price', $categoryproduct->productMeta) }}"
                                                            data-p_id="{{ $categoryproduct->id }}"
                                                            data-featured_image="{{ url('/') . '/' . $categoryproduct->featured_image }}">
                                                            @if ($disable)
                                                                Out of Stock
                                                            @else
                                                                Add to Cart
                                                            @endif
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
        @endif
    </div>
    <div class="pt-1">
        @foreach ($categoryTitles as $category)
            <p><a href="{{ url('/') }}/category/{{ $category['sku'] }}" class="view-category-text6">View
                    All Similar Category Products</a></p>
        @endforeach
    </div>
</section>


<div class="modal fade" id="experqueryModal" tabindex="-1" aria-labelledby="experqueryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="experqueryLabel">Ask an Expert </h5>
                <button type="button" class="btn-close" id="close_btn1" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="c_query_form" method="POST">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group pt-3">
                                        <label for="">First Name:</label>
                                        <input type="text" class="form-control" name="f_name" id="f_name"
                                            placeholder="First Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group pt-3">
                                        <label for="">Last Name:</label>
                                        <input type="text" class="form-control" name="l_name" id="l_name"
                                            placeholder="Last Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group pt-3">
                                        <label for="">Email:</label>
                                        <input type="email" class="form-control" name="c_email" id="c_email"
                                            placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group pt-3">
                                        <label for="">Phone:</label>
                                        <input type="number" class="form-control" name="c_phone" id="c_phone"
                                            placeholder="Phone" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group pt-3">
                                        <label for="state">State:</label>
                                        <input type="text" class="form-control" name="c_state" id="c_state"
                                            placeholder="State" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group pt-3">
                                        <label for="state">Message:</label>
                                        <textarea class="form-control" name="c_message" id="c_message" placeholder="Message...." required></textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="product_id" id="p_id" value="{{ $product->id }}">
                                <div class="col-lg-6 col-md-6 col-sm-12  m-2" id="q_product">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="checkout-produt-image">
                                                <img src="{{ asset($product->featured_image) }}" class="img-fluid"
                                                    alt="Product Image">
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="checkout-produt-desc">
                                                <p>{{ $product['title'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="close_btn2"
                            data-bs-dismiss="modal">Close</button>
                         <button type="submit" class="btn" id="btn_sbmt"
                            style="background-color:#E59041; color:white" aria-label="Send message">Send Message</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@include('Website.Includes.footer')

<script>
    $(document).ready(function() {
        $('#c_query_form').submit(function(event) {
            event.preventDefault();
            $('#btn_sbmt').prop('disabled', true).text('Sending...');

            var formData = $(this).serialize();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: 'POST',
                url: '{{ route('store.customer') }}',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    switch (response.status) {
                        case 200:
                            toastr.success(response.message);
                            $('#experqueryModal').modal('hide');
                            $('#f_name, #l_name, #c_email, #c_phone, #c_message, #c_state')
                                .attr('disabled', true);
                            break;
                        case 500:
                            toastr.error(response.message);
                            break;
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    toastr.error(xhr.responseText);
                },
                complete: function() {
                    $('#btn_sbmt').prop('disabled', false).text('Send Message');
                    $('#f_name, #l_name, #c_email, #c_phone, #c_message, #c_state').val('');
                    $('#f_name, #l_name, #c_email, #c_phone, #c_message, #c_state').prop(
                        'disabled', false);
                }
            });
        });
    });



    $(document).ready(function() {
        $('#close_btn2').click(function() {
            $('#c_query_form')[0].reset();
        });
        $('#close_btn1').click(function() {
            $('#c_query_form')[0].reset();
        });
    });
</script>




<script>
    const imageLinks = document.querySelectorAll('.image-link');
    const mainImage = document.getElementById('main-product-image').querySelector('img');

    imageLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const clickedImage = this.getAttribute('data-image');
            mainImage.src = clickedImage;
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calculatePriceButton = document.getElementById('calculatePriceButton');

        calculatePriceButton.addEventListener('click', function() {
            const postCodeInput = document.querySelector('.POSTCODE-INP');
            const itemCountInput = document.querySelector('.prtoduct-vieww-counter-inp');
            const productElement = document.getElementById('product_price');



            const postCode = postCodeInput.value;
            const productPrice = productElement.getAttribute('data-price');
            const itemCount = itemCountInput.value;

            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            // Perform AJAX request
            fetch('/calculate-price', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken, // Add your CSRF token here
                    },
                    body: JSON.stringify({
                        post_code: postCode,
                        item_count: itemCount,
                        product_price: productPrice
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const calculatedPrice = (parseFloat(data.price) * itemCount).toFixed(2);
                        console.log('Calculated Price: $' + calculatedPrice);
                        console.log('Total Price: $' + data.price.toFixed(2));
                        console.log('PaymentIntent ID: ' + data.payment_intent_id);
                    } else {
                        console.error('Error calculating price');
                    }
                })
                .catch(error => {
                    console.error('An error occurred:', error);
                });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.POSTCODE-INP').on('input', function() {
            var postCode = $(this).val();
            var productId = $('#product_unique_id').val();


            if (postCode.trim() === '') {
                $('#calculatePriceButton, .quantity_btn').css('display', 'none');
                $('#messageContainer').text('');
                return; // Exit the function early if the input is empty
            }

            $.ajax({
                url: '/check-postcode',
                method: 'GET',
                data: {
                    post_code: postCode,
                    product_id: productId
                },
                success: function(response) {
                    if (response.area_group_exit) {

                        $('#calculatePriceButton').css('display', 'block');
                        $('.quantity_btn').css('display', 'block');
                        $('#messageContainer').text('');
                    } else {
                        console.log('No matching area_group_id found.');
                        $('#calculatePriceButton').css('display', 'none');
                        $('.quantity_btn').css('display', 'none');
                        $('#messageContainer').text(
                            'This Product Not Lie In Your Given Area.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>
