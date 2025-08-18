@include('Website.Includes.header', ['meta_Details' => $meta_details])
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\ProductClass;
    use App\General\DiscountClass;
    $logged_in_user = Auth::user();
@endphp
<style>
    .pagination .page-item.active .page-link {
        background-color: #E59041;
        color: white !important;
    }
</style>
<!-- MULTITAB SECTION -->
<section class="mtop-50">
    <div class="container pt-3">
        <!-- LATEAST TOOL STORE -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="collection-view-top-section">
                    <h1 class="collection-view-title"> All Featured Products </h1>
                    <p class="mb-0"><a href="#" class="collection-view-linked-desc">Home - Products</a></p>
                </div>
            </div>
        </div>
        <div class="row dewat-ptop-30px">
            @foreach ($featuredproducts as $featuredproduct)
                <div class="col-lg-4 col-md-6 col-sm-12 latest-product-col-b">
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
                    <div class="latest-tool-section">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="latest-product-image">
                                    <img src="{{ url('/') . '/' . $featuredproduct->featured_image }}" alt="">
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12">
                                <div class="latest-product-details">
                                    <p class="latest-product-title"><a
                                            href="{{ url('/') }}/product/{{ $featuredproduct->permalink }}"
                                            class="latest-product-TextDecor texttracate">{{ $featuredproduct->title }}</a>
                                    </p>
                                    <p class="latest-product-amount">
                                        <?php
                                        $discountedPriceHtml = null;
                                        $actualPrice = null;
                                        if (isset($featuredproduct->discount)) {
                                            if ($featuredproduct->discount->active == 1) {
                                                $currentDate = time();
                                                $fromDate = strtotime($featuredproduct->discount->from_date);
                                                $toDate = strtotime($featuredproduct->discount->end_date);
                                                if ($featuredproduct->discount->limited == 1) {
                                                    if ($currentDate >= $fromDate && $currentDate <= $toDate) {
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
                                                $currentDate = time();
                                                $fromDate = strtotime($collection->from_date);
                                                $toDate = strtotime($collection->end_date);
                                                if ($collection->limited == 1) {
                                                    if ($currentDate >= $fromDate && $currentDate <= $toDate) {
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
                                        <small class="product-detail-discount"><del>{{ $actualPrice }}</del></small>
                                    </p>
                                    <div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                                                @php
                                                    $disable = false;
                                                    $stock = $featuredproduct->productMeta->where('option', '_stock')->first()->value;
                                                    if ($stock == 0) {
                                                        $disable = true;
                                                        $buttonText = 'Out of Stock';
                                                    } else {
                                                        $disable = false;
                                                        $buttonText = 'Add to Cart';
                                                    }
                                                @endphp
                                                <div>
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
            @endforeach
        </div>

        <!-- <div class="pagination-section">
                <a href="{{ $featuredproducts->onEachSide(1)->links('pagination::bootstrap-4') }}"></a>
            </div> -->


        @if ($featuredproducts->total() > $perPage)
            <div class="pagination-section">
                <nav aria-label="Page navigation ms-auto">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="{{ $featuredproducts->previousPageUrl() }}"
                                aria-label="Previous">
                                <span aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                    </svg>
                                </span>
                            </a>
                        </li>


                        @if ($featuredproducts->currentPage() > 3)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif

                        @foreach (range(max($featuredproducts->currentPage() - 2, 1), min($featuredproducts->lastPage() - 1, $featuredproducts->currentPage() + 2)) as $page)
                            @if ($page == $featuredproducts->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $featuredproducts->url($page) }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        @if ($featuredproducts->currentPage() < $featuredproducts->lastPage() - 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif

                        @if ($featuredproducts->lastPage() > 1)
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $featuredproducts->url($featuredproducts->lastPage()) }}">{{ $featuredproducts->lastPage() }}</a>
                            </li>
                        @endif

                        <li class="page-item">
                            <a class="page-link" href="{{ $featuredproducts->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif
    </div>
</section>
@include('Website.Includes.footer')
