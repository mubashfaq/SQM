@php
    use App\Models\GeneralSettings;
    use App\Models\Menus;
    $cartCount = session('totalProducts', 0);

    // Fetch all required options in one query
    $settings = GeneralSettings::whereIn('option', ['_instagram_link', '_facebook_link', '_twitter_link', '_youtube_link','_b_number','_header_logo','_footer_logo','_footer_logo','_b_street_address','_b_email','_b_area_address','_minimum_weight','_minimum_items'])
        ->pluck('value', 'option');
        $menus = Menus::with('menuMeta')->orderBy('number', 'asc')->get();

    $instagramLink = $settings['_instagram_link'] ?? '#';
    $facebookLink = $settings['_facebook_link'] ?? '#';
    $twitterLink = $settings['_twitter_link'] ?? '#';
    $youtubeLink = $settings['_youtube_link'] ?? '#';
    $header_logo = $settings['_header_logo'] ?? '#';
    $footer_logo = $settings['_footer_logo'] ?? '#';

    $b_email = $settings['_b_email'] ?? '#';
    $b_number = $settings['_b_number'] ?? '#';
    $b_street_address = $settings['_b_street_address'] ?? '#';
    $b_area_address = $settings['_b_area_address'] ?? '#';
    $minimum_items = $settings['_minimum_items'] ?? '0';
    $minimum_weight = $settings['_minimum_weight'] ?? '0';
@endphp

@php
function isActive($menuUrl) {    
    // Convert relative URLs to absolute for comparison
    $fullMenuUrl = url($menuUrl);
    $currentUrl = url()->current();
    
    // Special case for home page
    if ($fullMenuUrl === url('/')) {
        // Check if we're on the home page (either exactly '/' or the base URL)
        return ($currentUrl === url('/')) ? 'active' : '';
    } else {
        // For other pages, use the existing logic
        $menuPath = trim(parse_url($fullMenuUrl, PHP_URL_PATH), '/');
        return request()->is($menuPath . '*') ? 'active' : '';
    }
}
@endphp
@php
    $setting = \App\Models\GeneralSettings::all();
    use App\General\GeneralSettingsClass;
    $minimum_weight=GeneralSettingsClass::getRequiredValue('_minimum_weight');
    
@endphp

<!-- HEADER / NAVBAR -->
<header class="container-fluid px-0">
    <!-- Top-level Row -->
    <div class="row g-0 Nav_Container_1__1">
        <!-- Logo Column -->
        <div class="Nav_Container_1 d-none d-sm-flex align-items-center justify-content-center bg-white py-0">
            <a href="{{ url('/') }}" class="text-decoration-none LOGGOO">
                <img src="{{ asset($header_logo) ?? '#' }}" alt="Logo" class="Logo" loading="lazy" srcset="{{ asset($header_logo) }} 1x, {{ asset($header_logo) }} 2x" />
            </a>
        </div>

        <div class="Nav_Container_2">
            <!-- Top Bar -->
            <div class="top-bar d-none d-sm-block plus-jakarta-sans">
                    <div class="row align-items-center">
                        <div class="col-md-8 d-none d-xl-flex align-items-center text-sm">
                            <div class="me-4 ms-3 d-flex align-items-center">
                                <i class="fa-solid fa-paper-plane me-2"></i>
                                <a href="mailto:{{$b_email}}" class="text-decoration-none position-relative Text-size animated-link">
                                <span class="Text-size">
                                   {{$b_email}}</span>
                                </a>
                            </div>
                            <div class="d-none d-md-flex align-items-center">
                                <i class="fa-solid fa-location-dot me-2"></i>

                                <a href="{{$b_street_address}}"
                                    target="_blank" class="text-decoration-none position-relative Text-size animated-link">
                                    <span class="Text-size">
                                    {{ $b_area_address}}</span>
                                </a>

                            </div>
                        </div>

                        <div
                            class="col-12 col-xl-4 d-flex justify-content-between justify-content-xl-end align-items-center">
                            <div class="d-sm-flex align-items-center d-none">
                                <div class="position-relative me-2">
                                    <a href="{{ url('contact-us') }}"
                                        class="text-decoration-none animated-link1 contact_link_hover Text-size">Contact</a>
                                </div>
                            </div>
                            <div class="social-icons ms-5 d-flex">
                            <a href="https://{{ltrim($facebookLink, 'http://')}}" class="text-white links_hover" target="_blank">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://{{ltrim($twitterLink, 'http://')}}" class="text-white links_hover ms-3" target="_blank">
                                <i class="fa-brands fa-twitter"></i>
                            </a>
                            <a href="https://{{ltrim($youtubeLink, 'http://')}}" class="text-white links_hover ms-3" target="_blank">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                            <a href="https://{{ltrim($instagramLink, 'http://')}}" class="text-white links_hover ms-3" target="_blank">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        </div>
                        </div>
                    </div>
            </div>
            <!-- End Top Bar -->

            <!-- Main Nav -->
            <div class="bg-white">
                <div
                    class="d-flex align-items-center justify-content-between justify-content-sm-end justify-content-xl-between py-3 py-sm-0 ps-1 pe-3 ps-sm-0 pe-sm-0">
                    <div class="d-block d-sm-none">
                        <a href="{{ url('/') }}" class="text-decoration-none">
                            <img src="{{ asset($header_logo) }}" alt="logo" loading="lazy" srcset="{{ asset($header_logo) }} 1x, {{ asset($header_logo) }} 2x"
                                style="width: 185px;" />
                        </a>

                    </div>

                    <nav class="d-none d-xl-flex navbar-main-links align-items-center py-2">
                        @forelse ($menus as $menu)
                            @if ($menu->menuMeta->isEmpty())
                                <!-- Single Link Menu -->
                                <div>
                                <a href="{{ url($menu->url) }}" class="main-nav-link {{ isActive($menu->url) }}">
                                    {{ strtoupper($menu->title) }}
                                </a>
                                </div>
                            @else
                                <!-- Dropdown Menu -->
                                <!-- You can uncomment and implement dropdown logic here if needed -->
                            @endif
                        @empty
                            <!-- Optional: Fallback content if there are no menus -->
                        @endforelse
                    </nav>

                    <!-- Right Section: Phone, Cart, "Order Now" & Mobile Menu -->
                    <div class="d-flex align-items-center gap-3 gap-xl-4">
                        <!-- Phone -->
                        <div class="d-none d-xl-flex align-items-center shake-hover-wrapper">
                            <div class="shake-hover">
                                <i class="main-header__call__icon  icon-mobile fs-2 text_color_red"></i>
                            </div>
                            <div class="ms-2 d-none d-md-block">
                                <div class="text-muted" style="font-size: 0.75rem">
                                    CALL ANYTIME
                                </div>
                                <a href="tel:{{$b_number}}" class="text-decoration-none FW_BOLD_A">
                                    <div class="fw-bold">{{$b_number}}</div>
                                </a>
                            </div>
                        </div>

                        <!-- Mobile Canvas -->
                        <div>
                            <button class="btn Toggle_button d-block d-xl-none" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                                <i class="fa-solid fa-bars"></i>
                            </button>

                            <div class="offcanvas offcanvas-start offcanvas_background_color" tabindex="-1"
                                id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                                <div class="offcanvas-header">
                                    <a href="{{ url('/') }}" class="text-decoration-none">
                                        <img src="{{ asset($footer_logo) }}" class="Logo" loading="lazy" srcset="{{ asset($footer_logo) }} 1x, {{ asset($footer_logo) }} 2x"
                                            alt="Logo" />
                                    </a>

                                    <button type="button" class="btn close_btn" data-bs-dismiss="offcanvas"
                                        aria-label="Close">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                                <div class="offcanvas-body text-white">
                                    <nav class="sidebar">
                                        <div class="d-flex flex-column">
                                            <!-- Menu Items -->
                                            <div class="flex-grow-1 w-100">
                                            @foreach($menus as $menu)
                                                <div class="menu-item">
                                                    <button class="menu-button" data-menu="{{ strtoupper($menu->title) }}">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <a href="{{ url($menu->url) }}" class="text-decoration-none">
                                                                <span>{{ strtoupper($menu->title) }}</span>
                                                            </a>
                                                        </div>
                                                        @if($menu->menuMeta->isNotEmpty())
                                                            <div class="dropdown-icon-bg">
                                                                <i class="fa fa-angle-down dropdown-icon"></i>
                                                            </div>
                                                        @endif
                                                    </button>
                                                    @if($menu->menuMeta->isNotEmpty())
                                                        <div class="submenu" id="{{ strtoupper($menu->title) }}-submenu">
                                                            @foreach($menu->menuMeta as $meta)
                                                                <button class="submenu-item">
                                                                    <a href="{{ url($meta->url) }}" class="text-decoration-none">
                                                                        <span>{{ $meta->title }}</span>
                                                                    </a>
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                            <!-- Contact Information -->
                                            <div class="header_contact-info">
                                                <div class="contact-item">
                                                    <div class="contact__info">
                                                        <i class="fa-solid fa-envelope"></i>
                                                    </div>
                                                    <a href="mailto:{{$b_email}}" class="text-decoration-none position-relative text-white Text-size animated-link">
                                                    {{$b_email}}
                                                    </a>
                                                </div>
                                                <div class="contact-item">
                                                    <div class="contact__info">
                                                        <i class="fa-solid fa-phone"></i>
                                                    </div>
                                                    <a href="{{$b_street_address}}"
                                                        target="_blank" class="text-white text-decoration-none position-relative text-white Text-size animated-link">
                                                        <span class="Text-size">
                                                        {{ $b_area_address}}</span>
                                                    </a>

                                                </div>

                                                <!-- Social Media Icons -->
                                                <div class="social-icons pt-2 d-flex">
                                                    <a href="https://{{ltrim($facebookLink, 'http://')}}" class="text-white off_links_hover" target="_blank">
                                                        <i class="fa-brands fa-facebook-f"></i>
                                                    </a>
                                                    <a href="https://{{ltrim($twitterLink, 'http://')}}" class="text-white off_links_hover ms-3"  target="_blank">
                                                        <i class="fa-brands fa-twitter"></i>
                                                    </a>
                                                    <a href="https://{{ltrim($youtubeLink, 'http://')}}" class="text-white off_links_hover ms-3"  target="_blank">
                                                        <i class="fa-brands fa-linkedin-in"></i>
                                                    </a>
                                                    <a href="https://{{ltrim($instagramLink, 'http://')}}" class="text-white off_links_hover ms-3"  target="_blank">
                                                        <i class="fa-brands fa-instagram"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>

                        <!-- Vertical Divider (Desktop) -->
                        <div class="vr d-none d-md-block"></div>

                        <!-- Search & Cart -->
                        <div class="d-flex align-items-center gap-3 gap-sm-4">
                            <!-- Existing Cart Button -->
                            <div class="position-relative">
                                <button class="btn p-0 text-dark Cart_BTN" id="openCartModal">
                                    <i class="fa-solid fa-cart-shopping Icons__Hover_effect fs-5"></i>
                                    <span class="cart-badge Icons__Hover_effect">{{ $cartCount }}</span>
                                </button>
                            </div>

                            @if (auth()->check())
                                <div class="Navbar_Profile_ position-relative">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle Navbar_Profile__toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">

                                            <i class="fa-solid fa-user Icons__Hover_effect"></i>
                                            <div class="nav_profile_name">{{auth()->user()->name}}</div>

                                        </button>
                                        <ul class="dropdown-menu Navbar_Profile__menu">
                                            <li><a class="dropdown-item Navbar_Profile__item"
                                                    href="{{url('my-account')}}">My
                                                    Account</a></li>
                                            <li><a class="dropdown-item Navbar_Profile__item"
                                                    href="{{url('order-history')}}">Order
                                                    History</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <form method="POST" class="m-0" action="{{ route('Website.Logout') }}">
                                                @csrf
                                                <button type="submit" class="btn w-100 sign_out ">Sign
                                                    Out</button>
                                            </form>
                                        </ul>
                                    </div>
                                </div>
                            @else
                                <div class="position-relative">
                                    <a href="/sign-in">
                                        <button class="btn p-0 text-dark Cart_BTN" aria-label="Sign in">
                                            <i class="fa-solid fa-user Icons__Hover_effect"></i>
                                        </button>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Order Now -->
                        <a href="{{ url('/shop') }}" class="btn-7 d-none d-md-block">
                            <span>Order Now</span>
                            <span class="divider"></span>
                            <i class="icon-meat-3"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- End Main Nav -->
        </div>
    </div>
</header>


<!-- Cart Modal -->

<div class="cart-modal" id="cartModal">
    <div class="cart-modal-header">
        <h5 class="mb-0">
            <i class="fas fa-shopping-cart me-2 theme-color"></i>
            Shopping Cart (<span id="modalCartCount">{{ $cartCount }}</span>)
        </h5>
        <button class="close-cart-modal" id="closeCartModal" aria-label="Close cart modal">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="cart-modal-body" id="cartItems">
        @if($cartCount > 0 && session('cartData'))
            @php
                $cartData = session('cartData', []);
               
                $totalAmount = 0;
            @endphp
            @foreach($cartData as $item)
                @php
                    $is_weight=$item['is_weight'];
                    $itemTotal = ($item['amount']) * ($item['quantity'] ?? 1);
                    $totalAmount += $itemTotal;
                    $perKgPrice = $item['weight'] > 0 ? $item['amount'] / $item['weight'] : 0;
                @endphp
                <div class="cart-item" data-pid="{{ $item['pId'] }}">
                    <div class="cart-item-image">
                        <img src="{{ asset($item['featuredImage']) }}" alt="{{ $item['title'] }}" loading="lazy" srcset="{{ asset($item['featuredImage']) }} 1x, {{ asset($item['featuredImage']) }} 2x" />
                    </div>
                    <div class="cart-item-details">
                        <h6 class="cart-item-title">{{ strlen($item['title']) > 24 ? substr($item['title'], 0, 24) . '...' : $item['title'] }}</h6>
                        <div class="cart-item-info">
                            <div class="weight-control-group">
                                <label class="weight-label">{{ $is_weight == 1 ? 'Weight (KG)' : 'Item' }}</label>
                                <div class="weight-controls">
                                    <input type="number" 
                                           class="nav-weight-input" 
                                           value="{{ $item['weight'] }}" 
                                          
                                           data-pid="{{ $item['pId'] }}"
                                           data-is_weight="{{ $is_weight }}"
                                           data-original-weight="{{ $item['weight'] }}"
                                           data-original-amount="{{ $item['amount'] }}"
                                            min="{{ $is_weight == 1 ? $minimum_weight/1000 : $minimum_items }}"
                                            step="{{ $is_weight == 1 ? '0.1' : '1' }}"
                                           data-per-kg-price="{{ number_format($perKgPrice, 3, '.', '') }}">
                                    <span class="kg-text"></span>
                                </div>
                            </div>
                            
                            <div class="cart-item-price">
                                <span class="price" id="price-{{ $item['pId'] }}">${{ number_format($itemTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <button class="btnRemove-nav btn btn-link text-decoration-none text-dark p-0" 
                            data-pid="{{ $item['pId'] }}"
                            title="Remove item">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
            @endforeach
        @else 
            <div class="empty-cart text-center">
                <i class="fs-4 fas fa-shopping-cart"></i>
                <h4>Your cart is empty</h4>
            </div>
        @endif
    </div>
    
    <div class="cart-modal-footer" id="cartFooter" style="{{ $cartCount > 0 ? 'display: block;' : 'display: none;' }}">
        <div class="cart-total">
            <span>Total:</span>
            @if($cartCount > 0 && session('cartData'))
                @php
                    $cartData = session('cartData', []);
                    $totalAmount = 0;
                    foreach($cartData as $item) {
                        $itemTotal = ($item['amount']) * ($item['quantity'] ?? 1);
                        $totalAmount += $itemTotal;
                    }
                @endphp
                <span class="theme-color" id="cartTotal">${{ number_format($totalAmount, 2) }}</span>
            @else
                <span class="theme-color" id="cartTotal">$0.00</span>
            @endif
        </div>
        <div class="cart-buttons">
            <a href="{{url('/cart')}}" class="btn-view-cart" style="background-color: #a42125; color: white;">VIEW CART</a>
            <a href="{{url('/checkout')}}" class="btn-checkout" style="background-color: #a42125; color: white;">CHECKOUT</a>
        </div>
    </div>
</div>

<!-- JavaScript for Cart Modal -->
@push('scripts')
 <script>
// Replace the existing weight input event listeners with this improved version

  document.addEventListener('DOMContentLoaded', function() {
    const cartModal = document.getElementById('cartModal');
    const cartOverlay = document.getElementById('cartOverlay');
    const openCartModal = document.getElementById('openCartModal');
    const closeCartModal = document.getElementById('closeCartModal');
    const cartCount = parseInt('{{ $cartCount }}');
   
    // Get the current URL path
    const currentUrl = window.location.pathname;
    
    // Function to open modal
    function openModal() {
        cartModal.classList.add('show');
        cartOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    
    // Function to close modal
    function closeModal() {
        cartModal.classList.remove('show');
        cartOverlay.classList.remove('show');
        document.body.style.overflow = '';
    }
    
    // Auto-open modal if cart has items and the URL is not "/cart"
    // This prevents the modal from opening when user is already on the cart page
    if (cartCount > 0 && currentUrl !== '/cart' && currentUrl !== '/checkout') {
        setTimeout(function() {
            openModal();
        }, 500);
    }
    
    // Event listeners - Only add click event if NOT on cart page
    if (openCartModal && currentUrl !== '/cart' && currentUrl !== '/checkout') {
        openCartModal.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });
    }
    
    // If on cart page, redirect to cart page instead of showing modal
    if (openCartModal && currentUrl === '/cart' && currentUrl !== '/checkout') {
        openCartModal.addEventListener('click', function(e) {
            e.preventDefault();
            // Redirect to cart page or do nothing since already on cart page
            // window.location.href = '/cart';
        });
    }
    
    // Close modal event listeners
    if (closeCartModal) {
        closeCartModal.addEventListener('click', closeModal);
    }
    
    // Close modal when clicking on overlay
    if (cartOverlay) {
        cartOverlay.addEventListener('click', closeModal);
    }
});


// IMPROVED: jQuery version with better input handling
// IMPROVED: jQuery version with better input handling
$(document).ready(function() {



// Input validation for preventing decimal points in item-based products
$(document).on('keypress', '.nav-weight-input', function(e) {
    const is_weight = parseInt($(this).data('is_weight'));

    // If not weight-based (items), prevent decimal point entry
    if (is_weight !== 1) {
        if (e.key === '.' || e.key === ',') {
            e.preventDefault();
        }
    }
});

// Additional validation on input to remove decimals if somehow entered
$(document).on('input', '.nav-weight-input', function() {
    const is_weight = parseInt($(this).data('is_weight'));
    const value = $(this).val();

    // For items, validate no decimal values
    if (is_weight !== 1 && (value.includes('.') || value.includes(','))) {
        // Remove decimal part
        $(this).val(Math.floor(parseFloat(value)) || '');
    }

    // EXISTING CODE CONTINUES HERE...
    let $input = $(this);
    let inputValue = $input.val();
    let productId = $input.data('pid');
    let perKgPrice = parseFloat($input.data('per-kg-price'));

    // If input is empty, show $0.00 but don't change the input value
    if (inputValue === '') {
        $('#price-' + productId).text('$0.00');
        updateModalTotal();
        return;
    }

    // Parse the value
    let newWeight = parseFloat(inputValue);

    // Only update if it's a valid number
    if (!isNaN(newWeight) && newWeight >= 0) {
        let newItemTotal = perKgPrice * newWeight;
        $('#price-' + productId).text('$' + newItemTotal.toFixed(2));
        updateModalTotal();
    }
});

/**
 * Handle weight input changes in real-time (as user types)
 */
$(document).on('input', '.nav-weight-input', function() {
    let $input = $(this);
    let inputValue = $input.val();
    let productId = $input.data('pid');
    let perKgPrice = parseFloat($input.data('per-kg-price'));

    // If input is empty, show $0.00 but don't change the input value
    if (inputValue === '') {
        $('#price-' + productId).text('$0.00');
        updateModalTotal();
        return;
    }

    // Parse the value
    let newWeight = parseFloat(inputValue);

    // Only update if it's a valid number
    if (!isNaN(newWeight) && newWeight >= 0) {
        let newItemTotal = perKgPrice * newWeight;
        $('#price-' + productId).text('$' + newItemTotal.toFixed(2));
        updateModalTotal();
    }
});

/**
 * Handle weight input when user finishes editing (blur event)
 */
$(document).on('blur', '.nav-weight-input', function() {
    let $input = $(this);
    let inputValue = $input.val().trim();
    let productId = $input.data('pid');
    let is_weight = $input.data('is_weight');
    let perKgPrice = parseFloat($input.data('per-kg-price'));
    let minimumWeight = parseFloat('{{ $minimum_weight }}') / 1000;
    let minimumItem = parseFloat('{{ $minimum_items }}');

    let newWeight = parseFloat(inputValue);



    // Check if input is empty, invalid, or less than 0
    if (inputValue === '' || isNaN(newWeight) || newWeight < 0) {
        // Set to minimum value based on is_weight flag
        if (is_weight == 1) {
            $input.val(minimumWeight.toFixed(1));
            inputValue = minimumWeight;
        } else {
            $input.val(minimumItem.toString());
            inputValue = minimumItem;
        }
    } else if (is_weight == 1 && newWeight < minimumWeight) {
        // If weight is less than minimum weight, set to minimum
        // But allow values that are very close to minimum (within 0.001 tolerance)
        if (Math.abs(newWeight - minimumWeight) > 0.001) {
            $input.val(minimumWeight.toFixed(1));
            inputValue = minimumWeight;
        } else {
            // Value is close enough to minimum, keep it as is
            $input.val(newWeight.toFixed(1));
        }
    } else if (is_weight != 1 && newWeight < minimumItem) {
        // If item count is less than minimum items, set to minimum
        $input.val(minimumItem.toString());
        inputValue = minimumItem;
    } else {
        // Valid weight, format it properly for weight products
        if (is_weight == 1) {
            $input.val(newWeight.toFixed(1));
        } else {
            $input.val(newWeight.toString());
        }
    }

    let finalWeight = parseFloat($input.val());
    let newItemTotal = perKgPrice * finalWeight;
    $('#price-' + productId).text('$' + newItemTotal.toFixed(2));
    updateModalTotal();
    updateWeightOnServer(productId, finalWeight, $input);
});

/**
 * Handle Enter key press on weight input
 */
$(document).on('keydown', '.nav-weight-input', function(e) {
    if (e.key === 'Enter') {
        $(this).blur(); // Trigger blur event
    }
});

/**
 * Send AJAX request to update weight in session
 */
function updateWeightOnServer(productId, weightValue, $input) {
    $.ajax({
        url: '{{ url('update-weight') }}',
        method: 'POST',
        data: {
            pId: productId,
            weight: weightValue,
            _token: '{{ csrf_token() }}'
        },
        beforeSend: function() {
            $input.prop('disabled', true);
        },
        success: function(response) {
            if (response.status === true) {
                // Update weight only if significantly different from server
                if (response.newWeight !== undefined) {
                    let returnedWeight = parseFloat(response.newWeight);
                    if (Math.abs(returnedWeight - weightValue) > 0.001) {
                        $input.val(returnedWeight.toString());
                    }
                }

                // Update the per kg price if returned from server
                if (response.perKgPrice) {
                    $input.data('per-kg-price', response.perKgPrice);
                }

                // Update item price
                if (response.newItemTotal !== undefined) {
                    $('#price-' + productId).text('$' + parseFloat(response.newItemTotal)
                        .toFixed(2));
                }

                // Update cart count
                if (response.cartCount !== undefined) {
                    $('#modalCartCount').text(response.cartCount);
                    $('.cart-badge, .cart-count, #cartCount').text(response.cartCount);
                }

                // Update grand total
                if (response.grandTotal !== undefined) {
                    $('#cartTotal').text('$' + parseFloat(response.grandTotal).toFixed(2));
                }

            } else {
                alert(response.message || 'Could not update weight.');
                // Revert to original value
                $input.val($input.data('original-weight'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Error updating weight:', error);
            alert('Error updating weight. Please try again.');
            // Revert to original value
            $input.val($input.data('original-weight'));
        },
        complete: function() {
            $input.prop('disabled', false);
        }
    });
}

/**
 * Update modal total by calculating all item prices
 */
function updateModalTotal() {
    let total = 0;
    $('.cart-item .price').each(function() {
        let priceText = $(this).text().replace('$', '').replace(',', '');
        let price = parseFloat(priceText);
        if (!isNaN(price)) {
            total += price;
        }
    });
    $('#cartTotal').text('$' + total.toFixed(2));
}

/**
 * Handle item removal from cart
 */
$(document).on('click', '.btnRemove-nav', function(e) {
    e.preventDefault();
    let pId = $(this).data('pid');
    let $cartItem = $(this).closest('.cart-item');

        $.ajax({
            url: '{{ url('remove-from-cart') }}',
            method: 'POST',
            data: {
                pIdToRemove: pId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === true) {
                    $cartItem.fadeOut(300, function() {
                        $(this).remove();

                        $('#cartTotal').text('$' + parseFloat(response
                                .totalAmount || response.subTotal || 0)
                            .toFixed(2));
                        $('#modalCartCount').text(response.totalProducts ||
                            0);
                        $('.cart-badge, .cart-count, #cartCount').text(
                            response.totalProducts || 0);

                        if (!response.totalProducts || response
                            .totalProducts == 0) {
                            $('#cartItems').html(`
                            <div class="empty-cart text-center">
                                <i class="fs-4 fas fa-shopping-cart"></i>
                                <h4>Your cart is empty</h4>
                            </div>
                        `);
                            $('#cartFooter').hide();
                        }
                    });
                } else {
                    alert(response.message || 'Could not remove item.');
                }
            },
            error: function() {
                alert('Error removing item.');
            }
        });

});
});
</script>
@endpush
