@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')
<section class="about-sec1">
    <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
    <div class="container">
        <div class="text-center">
            <h2>Shop</h2>
            <p class="text-decoration-none"><span class="text-uppercase">Sydney Quality Meats:</span></p>
            <p class="text-decoration-none text-uppercase"><span class="text-uppercase ms-1">Products:</span></p>
            <p class="text-uppercase"><span class="text-uppercase">{{$product->category->title}}:</span></p>
            <p class="text-uppercase ms-1">{{$product->title}}</p>
        </div>
    </div>
</section>

<!-- Modern Product Detail Section -->
<section class="container my-5">
    <div class="product-detail-container">
        <div class="row g-0">
            <!-- Product Images Column -->
            <div class="col-lg-6">
                <div class="Product_Detail__images">
                    <!-- Main Image -->
                    <div class="Product_Detail__mainImageWrapper">
                        <div class="zoom-container">
                            <img id="mainImage"
                                src="{{ asset($product->featured_image ? $product->featured_image : 'WebsiteAssets/assets/img/pricing-product-1-2.png') }}"
                                alt="{{ $product->title }}" class="Product_Detail__mainImage zoom-image" />
                        </div>
                        <!-- Search (Magnify) Button -->
                        <button class="Product_Detail__searchBtn" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="26" height="26" viewBox="0 0 256 256" xml:space="preserve">
                                <defs>
                                </defs>
                                <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                                    <path d="M 90.061 87.939 L 59.696 57.576 c 5.887 -6.407 9.117 -14.664 9.117 -23.419 c 0 -9.257 -3.605 -17.96 -10.151 -24.505 C 52.115 3.105 43.413 -0.5 34.156 -0.5 S 16.196 3.105 9.651 9.651 C 3.105 16.197 -0.5 24.9 -0.5 34.156 c 0 9.257 3.605 17.96 10.151 24.505 c 6.545 6.546 15.249 10.151 24.505 10.151 c 8.755 0 17.011 -3.229 23.419 -9.117 L 87.94 90.06 C 88.233 90.353 88.616 90.5 89 90.5 s 0.768 -0.146 1.061 -0.439 C 90.647 89.474 90.647 88.525 90.061 87.939 z M 11.772 56.541 C 5.793 50.562 2.5 42.613 2.5 34.156 c 0 -8.456 3.293 -16.405 9.272 -22.384 S 25.7 2.5 34.156 2.5 s 16.405 3.293 22.384 9.272 s 9.272 13.929 9.272 22.384 c 0 8.456 -3.293 16.406 -9.272 22.384 c -5.979 5.979 -13.929 9.272 -22.384 9.272 S 17.751 62.52 11.772 56.541 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                    <path d="M 34.387 49.245 c -0.829 0 -1.5 -0.672 -1.5 -1.5 V 19.377 c 0 -0.829 0.671 -1.5 1.5 -1.5 s 1.5 0.671 1.5 1.5 v 28.368 C 35.887 48.573 35.216 49.245 34.387 49.245 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                    <path d="M 48.572 35.061 H 20.203 c -0.829 0 -1.5 -0.671 -1.5 -1.5 s 0.671 -1.5 1.5 -1.5 h 28.368 c 0.828 0 1.5 0.671 1.5 1.5 S 49.4 35.061 48.572 35.061 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                </g>
                            </svg>
                        </button>
                        <!-- Fullscreen Modal for Image -->
                        <div id="imageModal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.9);align-items:center;justify-content:center;flex-direction:column;">
                            <span class="close-modal" onclick="closeModal()">X</span>
                            <img id="modalImage" src="" alt="Full Image" style="max-width:90vw;max-height:90vh;object-fit:contain;box-shadow:0 0 30px #000;" />
                        </div>
                    </div>
                    <!-- Thumbnails -->
                    <div class="Product_Detail__thumbnails" id="thumbnails">
                        <!-- Populated by JavaScript based on the images array -->
                    </div>
                </div>
            </div>

            <!-- Product Info Column -->
            <div class="col-lg-6">
                <div class="product-info-section">
                    @php
                    use App\Models\GeneralSettings;
                    $settings = GeneralSettings::whereIn('option', ['_minimum_weight','_minimum_items'])
                    ->pluck('value', 'option');
                    $minimum_items = $settings['_minimum_items'] ?? '0';
                    $minimum_weight = $settings['_minimum_weight']/1000 ?? '0';
                    $priceMeta = $product->productMeta->firstWhere('option', '_price');
                    $price = $priceMeta ? $priceMeta->value : 0;
                    $stockMeta = $product->productMeta->firstWhere('option', '_stock_status');
                    $stock = $stockMeta ? $stockMeta->value : '';
                    $weightMeta = $product->productMeta->firstWhere('option', '_weight');
                    $is_weightMeta = $product->productMeta->firstWhere('option', '_is_weight');
                    $discountData = $product->discount_data ?? null;
                    $weight = $weightMeta ? $weightMeta->value : 1;
                    $is_weight = $is_weightMeta ? $is_weightMeta->value : 1;

                    // Calculate per kg prices
                    $perKgPrice = $weight > 0 ? $price / $weight : $price;
                    $finalPrice = $discountData ? $discountData['discounted_price'] : $price;
                    $perKgFinalPrice = $weight > 0 ? $finalPrice / $weight : $finalPrice;
                    @endphp

                    <!-- Product Title -->
                    <h1 class="product-title Product_Detail__title">{{$product->title}}</h1>

                    <!-- Short Description -->
                    <div class="product-short-description">
                        @php
                        print ($product->short_description);
                        @endphp
                    </div>



                    <!-- Price & Weight Section -->
                    <div class="price-weight-section">
                        <div class="price-row">
                            <span class="label">Total Price:</span>
                            <div class="price-display" id="priceDisplay">
                                @if ($discountData)
                                <span class="theme-color text-decoration-line-through">${{ $price }}</span>
                                <span class=" text-success" >${{ $finalPrice }}</span>
                                @else
                                <span class="current-price theme-color" >${{ $finalPrice }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="weight-row">
                            <span class="label">{{ $is_weight == 1 ? 'Weight Kg:' : 'Item:' }}</span>
                            <input type="number"
                                class="weight-input"
                                id="weightInput"
                                data-product-id="{{ $product->id }}"
                                data-per-kg-price="{{ $perKgFinalPrice }}"
                                data-original-per-kg-price="{{ $perKgPrice }}"
                                data-has-discount="{{ $discountData ? 'true' : 'false' }}"
                                value="{{ $weight }}"
                                min="{{ $is_weight == 1 ? $minimum_weight : $minimum_items }}"
                                step="{{ $is_weight == 1 ? '0.1' : '1' }}"
                                onchange="updatePrice()">
                        </div>
                        <!-- Error message for minimum validation -->
                        <div id="minimumError" class="minimum-error" style="display: none;">
                            Minimum {{ $is_weight == 1 ? 'weight' : 'items' }}: {{ $is_weight == 1 ? $minimum_weight : $minimum_items }}
                        </div>
                    </div>

                    <!-- Add to Cart Form -->
                    <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <!-- Hidden fields -->
                        <input type="hidden" name="pId" value="{{ $product->id }}">
                        <input type="hidden" name="title" value="{{ $product->title }}">
                        <input type="hidden" name="amount" value="{{ $finalPrice }}" id="hiddenAmount">
                        <input type="hidden" name="featuredImage" value="{{ $product->featured_image }}">
                        <input type="hidden" name="productGst" value="0">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="is_weight" value="{{ $is_weight }}">
                        <input type="hidden" name="weight" value="{{ $weight }}" id="hiddenWeight">
                        <input type="hidden" name="original_price" value="{{ $finalPrice }}">
                        <input type="hidden" name="original_weight" value="{{ $weight }}">

                        @if($stock == 'in_stock')
                        <button type="submit" class="add-button btn-7">
                            <span>Add To Cart</span>
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                        @else
                        <button type="button" style="cursor: not-allowed;" class="add-to-cart-btn" disabled>
                            <span>Out Of Stock</span>
                        </button>
                        @endif
                    </form>

                    <!-- Description Section -->
                    <div class="description-section">
                        <h3 class="description-title">Description</h3>
                        <div class="description-content">
                            @php
                            print ($product->long_description);
                            @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<style>
    .add-button {
        width: 100% !important;
        height: auto !important;
        line-height: 45px !important;
    }


    /* Product Info Section */
    .product-info-section {
        padding: 2rem;
    }

    .product-title {
        font-size: 2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .product-short-description {
        color: #666;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eee;
    }

    .price-weight-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .price-row,
    .weight-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .price-row {
        margin-bottom: 0;
    }

    .label {
        font-weight: 500;
        color: #555;
        font-size: 1rem;
    }

    .price-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .weight-input {
        width: 65px;
        padding: 0.5rem;
        border: 2px solid #A42125;
        border-radius: 6px;
        font-size: 1rem;
        text-align: center;
        transition: border-color 0.3s ease;
    }

    .weight-input:focus {
        outline: none;
        border-color: #A42125;
    }

    .add-to-cart-form {
        margin-bottom: 2rem;
    }

    .add-to-cart-btn {
        width: 100%;
        background: linear-gradient(135deg, #A42125, #A42125);
        color: white;
        border: none;
        padding: 10px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .add-to-cart-btn.disabled {
        background: #6c757d;
        transform: none;
    }

    .description-section {
        padding-top: 1.5rem;
        border-top: 1px solid #eee;
    }

    .description-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .description-content {
        color: #666;
        line-height: 1.6;
    }

    /* Modal Styles */

    .close-modal {
        position: absolute;
        top: 20px;
        right: 35px;
        color: white;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close-modal:hover {
        color: #ff6b47;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .product-title {
            font-size: 1.5rem;
        }

        .current-price {
            font-size: 1.3rem;
        }

        /* .Product_Detail__mainImage {
                height: 300px;
            } */

        .product-info-section,
        .Product_Detail__images {
            padding: 1rem;
        }

        .price-row,
        .weight-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .weight-input {
            width: 100%;
            max-width: 200px;
        }

        .Product_Detail__thumbnailImg {
            width: 100%;
            height: auto;
        }
    }
</style>

<script>
    // Price update function with minimum validation
    function updatePrice() {
        const weightInput = document.getElementById('weightInput');
        const weight = parseFloat(weightInput.value) || 0;
        const perKgPrice = parseFloat(weightInput.dataset.perKgPrice);
        const originalPerKgPrice = parseFloat(weightInput.dataset.originalPerKgPrice);
        const hasDiscount = weightInput.dataset.hasDiscount === 'true';

        // Get minimum values and product type
        const minimumWeight = parseFloat('{{ $minimum_weight }}');
        const minimumItems = parseFloat('{{ $minimum_items }}');
        const isWeight = parseInt('{{ $is_weight }}');

        // Get form elements
        const addToCartBtn = document.querySelector('.add-button');
        const errorMessage = document.getElementById('minimumError');

        let newPrice = '0.00';
        let newOriginalPrice = '0.00';
        let isValidQuantity = false;

        if (weight > 0) {
            newPrice = (perKgPrice * weight).toFixed(2);
            newOriginalPrice = (originalPerKgPrice * weight).toFixed(2);

            // Check minimum validation
            if (isWeight === 1) {
                // Weight-based product
                isValidQuantity = weight >= minimumWeight;
            } else {
                // Item-based product
                isValidQuantity = weight >= minimumItems;
            }
        }

        // Update display prices
        const priceDisplay = document.getElementById('priceDisplay');
        if (hasDiscount) {
            priceDisplay.innerHTML = `
                <span class="text-decoration-line-through theme-color">$${newOriginalPrice}</span>
                <span class="text-success">$${newPrice}</span>
            `;
        } else {
            priceDisplay.innerHTML = `
                <span class="current-price" style="color:#A42125">$${newPrice}</span>
            `;
        }

        // Update hidden form fields
        document.getElementById('hiddenAmount').value = newPrice;
        document.getElementById('hiddenWeight').value = weight;

        // Handle button state and error message
        if (isValidQuantity) {
            // Enable button
            addToCartBtn.disabled = false;
            addToCartBtn.style.opacity = '1';
            addToCartBtn.style.cursor = 'pointer';
            addToCartBtn.style.background = 'linear-gradient(135deg, #A42125, #A42125)';

            // Hide error message
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        } else {
            // Disable button
            addToCartBtn.disabled = true;
            addToCartBtn.style.opacity = '0.6';
            addToCartBtn.style.cursor = 'not-allowed';
            addToCartBtn.style.background = '#6c757d';

            // Show error message
            if (errorMessage) {
                const minValue = isWeight === 1 ? minimumWeight : minimumItems;
                const unit = isWeight === 1 ? 'kg' : 'items';
                errorMessage.textContent = `Minimum ${unit}: ${minValue}`;
                errorMessage.style.display = 'block';
            }
        }
    }

    // Add real-time weight input listener
    document.addEventListener('DOMContentLoaded', function() {
        const weightInput = document.getElementById('weightInput');

        // Add input event listener for real-time updates
        weightInput.addEventListener('input', updatePrice);

        // Initialize price calculation
        updatePrice();
    });

    // Convert comma-separated gallery string to array
    @php
        $galleryImages = [];
        if ($product->gallery_media) {
            $galleryImages = array_map('trim', explode(',', $product->gallery_media));
        }
    @endphp

    const images = [
        "{{ asset($product->featured_image ? $product->featured_image : 'WebsiteAssets/assets/img/pricing-product-1-2.png') }}",
        @foreach($galleryImages as $image)
            "{{ asset($image) }}",
        @endforeach
    ];

    let selectedImageIndex = 0;
    const thumbnailsEl = document.getElementById("thumbnails");
    const mainImageEl = document.getElementById("mainImage");

    // Generate thumbnails from images array
    images.forEach((imgSrc, idx) => {
        const btn = document.createElement("button");
        btn.classList.add("Product_Detail__thumbnailBtn");
        if (idx === selectedImageIndex) {
            btn.classList.add("Product_Detail__thumbnailBtn--selected");
        }
        btn.addEventListener("click", () => {
            selectedImageIndex = idx;
            updateMainImage();
            updateThumbnails();
        });

        const thumbImg = document.createElement("img");
        thumbImg.src = imgSrc;
        thumbImg.alt = `${document.querySelector('.Product_Detail__title').textContent} ${idx + 1}`;
        thumbImg.classList.add("Product_Detail__thumbnailImg");

        btn.appendChild(thumbImg);
        thumbnailsEl.appendChild(btn);
    });

    function updateMainImage() {
        mainImageEl.src = images[selectedImageIndex];
    }

    function updateThumbnails() {
        const thumbBtns = thumbnailsEl.querySelectorAll(".Product_Detail__thumbnailBtn");
        thumbBtns.forEach((btn, idx) => {
            if (idx === selectedImageIndex) {
                btn.classList.add("Product_Detail__thumbnailBtn--selected");
            } else {
                btn.classList.remove("Product_Detail__thumbnailBtn--selected");
            }
        });
    }

    // Zoom functionality
    document.addEventListener("DOMContentLoaded", function() {
        const zoomContainer = document.querySelector(".zoom-container");
        const zoomImage = document.querySelector(".zoom-image");

        if (zoomContainer && zoomImage) {
            zoomContainer.addEventListener("mousemove", function(event) {
                const {
                    left,
                    top,
                    width,
                    height
                } = zoomContainer.getBoundingClientRect();
                const x = ((event.clientX - left) / width) * 100;
                const y = ((event.clientY - top) / height) * 100;

                zoomImage.style.transformOrigin = `${x}% ${y}%`;
                zoomImage.style.transform = "scale(2)";
            });

            zoomContainer.addEventListener("mouseleave", function() {
                zoomImage.style.transform = "scale(1)";
            });
        }
    });

    // Full Screen functionality
    function openModal() {
        const modal = document.getElementById("imageModal");
        const modalImage = document.getElementById("modalImage");
        const mainImage = document.getElementById("mainImage");
        modalImage.src = mainImage.src;
        modal.style.display = "flex";
        // Allow closing modal with Escape key
        document.addEventListener('keydown', escCloseModal);
    }

    function closeModal() {
        const modal = document.getElementById("imageModal");
        modal.style.display = "none";
        document.removeEventListener('keydown', escCloseModal);
    }

    function escCloseModal(e) {
        if (e.key === 'Escape') closeModal();
    }

    // Attach the function to the search button
    document.addEventListener('DOMContentLoaded', function() {
        const searchBtn = document.querySelector(".Product_Detail__searchBtn");
        if (searchBtn) {
            searchBtn.addEventListener("click", openModal);
        }
    });

    // Social share functionality
    function share(platform) {
        // Implement actual share logic or redirect
        console.log(`Sharing on ${platform}`);
    }
</script>
@endsection