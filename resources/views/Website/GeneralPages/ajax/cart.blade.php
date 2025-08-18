<style>
    /* loader */
    .custom-loader {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background:
            radial-gradient(farthest-side, #FFA500 94%, #0000) top/8px 8px no-repeat,
            conic-gradient(#0000 30%, #FFA500);
        -webkit-mask: radial-gradient(farthest-side, #0000 calc(100% - 8px), #000 0);
        animation: s3 1s infinite linear;
        display: none;
    }


    @keyframes s3 {
        100% {
            transform: rotate(1turn)
        }
    }

    .cart-overflow {
        position: relative;
    }

    .loader-wrapper {
        position: absolute;
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 999;
    }

    /* loader */
        @media (max-width: 375px)
    {
        .cart-cross-icon{
            left: 281px;
            margin-right: 5px;
        }
    }
    @media (max-width: 425px)
    {
         .remove-item{
            right: 0px;
            margin-right: 3px;
        }
    }
    @media (max-width: 320px)
    {
        .cart-cross-icon{
            left: 234px;
        }
    }
</style>
<div>
    @if (!empty($cartData))
        @foreach ($cartData as $product)
            <div class="cart-item">
                <div class="item-media" style="background-image: url('{{ $product['featuredImage'] }}')"></div>
                <div class="item-info">
                    <div class="h6 item-title">{{ $product['title'] }}</div>
                    @if (isset($product['amount']) && isset($product['quantity']))
                        @php
                            $subtotal = $product['amount'] * $product['quantity'];
                        @endphp
                        <div><small>${{ $product['amount'] }} x {{ $product['quantity'] }} =
                                ${{ $subtotal }}</small></div>
                    @endif
                </div>
                <button class="remove-item cart-cross-icon"
                    data-product-id="{{ $product['pId'] }}">Remove</button>
            </div>
        @endforeach
        <div class="loader-wrapper">
            <div class="custom-loader"></div>
        </div>
    @else
        <div class="text-center">Your cart is empty</div>
        <div class="svg-cart-icon-responsive  text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-cart2" width="30" viewBox="0 0 16 16">
                <path
                    d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z" />
            </svg>
        </div>
    @endif

</div>
<div class="sidecart-footer">
    <div class="text-center mb-2">
        <div class="h5">Subtotal: ${{ $totalIncludingGST }}</div>
    </div>

    <div class="button-area">
        <a href="{{ route('Website.Cart_Details') }}">
            <button class="btn btn-dark w-100 mb-1">View Shopping Cart</button></a>
        <button class="btn btn-dark w-100 mb-1" onclick="window.location.href='{{ url('/') }}'" class="close-cart"
            data-bs-dismiss="offcanvas">Continue Shopping</button>
        <a href="{{ route('Website.CheckOut') }}">
            <button class="btn btn-dark w-100 mb-1">Proceed to Checkout</button>
        </a>
    </div>
</div>
</div>
