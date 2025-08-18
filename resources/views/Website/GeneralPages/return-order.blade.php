@include('Admin.Includes.header')

@php
    use App\General\UserClass;
@endphp
<style>
    .c-art-item {
        background: white;
        border-radius: 8px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .item-media {
        height: 50px;
        width: 50px;
        border-radius: 6px;
        background-position: center !important;
        background-size: 70% auto !important;
        background-repeat: no-repeat !important;
        margin-right: 6px;
    }

    .remove-return-product {
        border: none;
        outline: none;
        background-color: black;
        color: white;
        padding: 3px;
        font-size: 12px;
        line-height: 110%;
        border-radius: 4px;
    }

    .cart-cells {
        vertical-align: middle !important;
    }

    .cart-product-title {
        line-height: 100%;
    }

    .cart-total-section {
        border: 1px solid #E59041;
    }

    .cart-total-section-header {
        background: #E59041;
        color: white;
        font-size: 20px;
        padding: 12px;
        font-weight: 700;
    }

    .table {
        border: 1px solid #E59041 !important;
    }

    thead {
        background: #E59041;
        color: white;
    }

    tr {
        border: 1px solid #E59041 !important;
    }

    hr {
        background-color: #E59041;
        opacity: 1;
    }

    .border-top-orange {
        border-top: 1px solid #E59041 !important;
    }

    #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    #overlay-content {
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        height: 100vh;
        padding: 20px;
        position: relative;
    }

    #processing-message {
        font-size: 26px;
        color: #333;
        font-weight: bold;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10000;
    }

    @media (max-width: 768px) {
        .cart-cross-icon {
            position: relative;
            top: 2px;
            left: 0px;
        }

        .counter-section {
            width: 90px !important;
        }

        .item-media {
            width: 100px;
        }
    }

    @media (max-width: 425px) {
        .cart-cross-icon {
            position: relative;
            top: 2px;
            left: 0px;
        }

        .counter-section {
            width: 90px;
        }

        .item-media {
            width: 100px;
        }
    }

    @media (max-width: 419px) {
        .cart-cross-icon {
            position: relative;
            top: 2px;
            left: 0px;
        }

        .counter-section {
            width: 90px;
        }

        .item-media {
            width: 100px;
        }
    }
</style>
<div id="overlay">
    <div id="overlay-content">
        <div id="processing-message"> Processing... </div>
    </div>
</div>
<div class="text-center p-2">
    <form action="{{ route('Website.Return.Order.Details') }}" method="post">
        @csrf
        <input type="text" class="form-controle" name="invoice_number" id="order_id" value="<?php echo !empty($order->invoice_number) ? $order->invoice_number : ''; ?>"
            placeholder="Invoice Number"
            style="border: 2px solid #E59041;outline:none; line-height:2; border-radius: 4px;">
        <button type="submit" class="btn"
            style="background: #E59041; color: white; font-weight: bold;">Search</button>
    </form>
</div>

@if (!empty($order))
    <div class="container" id="print-container">
        <input type="hidden" name="order_id" id="order_id" value="{{ $order->invoice_number }}">
        <div class="row justify-content-between">
            <h5>Order Details</h5>
            <div class="col-md-4 d-flex">
                <p> Placed On: </p>&nbsp;&nbsp;
                <p><b>{{ $order->created_at }}</b></p>
            </div>
            <div class="col-md-4 d-flex">
                <p> Status: </p>&nbsp;&nbsp;
                @if ($order->status == 'pending')
                    <p style="color: blue;"><b>{{ ucwords($order->status) }}</b></p>
                @elseif ($order->status == 'inProcess')
                    <p style="color: orange;"><b>{{ ucwords($order->status) }}</b></p>
                @else
                    <p style="color: green;"><b>{{ ucwords($order->status) }}</b></p>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-8" style="overflow:auto;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="cart-total-section">
                            <div class="cart-total-section-header">Billing Address</div>
                            <div class="row m-3">
                                <div class="col-3">
                                    Name
                                </div>
                                <div class="col-9">
                                    {{ $order->f_name }} {{ $order->l_name }}
                                </div>
                                <hr>
                                <div class="col-3">
                                    Email
                                </div>
                                <div class="col-9">
                                    {{ $order->email }}
                                </div>
                                <hr>
                                <div class="col-3">
                                    Phone
                                </div>
                                <div class="col-9">
                                    {{ $order->phone }}
                                </div>
                                <hr>
                                <div class="col-3">
                                    Address
                                </div>
                                <div class="col-9">
                                    {{ $order->street_address }}, {{ $order->suburb }}, {{ $order->state }}
                                </div>
                                <hr>
                                <div class="col-3">
                                    Post Code
                                </div>
                                <div class="col-9">
                                    {{ $order->post_code }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="cart-total-section">
                            <div class="cart-total-section-header">Shipping Address</div>
                            <div class="row m-3">
                                <div class="col-3">
                                    Name
                                </div>
                                <div class="col-9">
                                    {{ $order->orderAddressDetail->f_name ?? $order->f_name }}
                                    {{ $order->orderAddressDetail->l_name ?? $order->l_name }}
                                </div>
                                <hr>
                                <div class="col-3">
                                    Email
                                </div>
                                <div class="col-9">
                                    {{ $order->orderAddressDetail->email ?? $order->email }}
                                </div>
                                <hr>
                                <div class="col-3">
                                    Phone
                                </div>
                                <div class="col-9">
                                    {{ $order->orderAddressDetail->phone ?? $order->phone }}
                                </div>
                                <hr>
                                <div class="col-3">
                                    Address
                                </div>
                                <div class="col-9">
                                    {{ $order->orderAddressDetail->street_address ?? $order->street_address }},
                                    {{ $order->orderAddressDetail->suburb ?? $order->suburb }},
                                    {{ $order->orderAddressDetail->state ?? $order->state }}
                                </div>
                                <hr>
                                <div class="col-3">
                                    Post Code
                                </div>
                                <div class="col-9">
                                    {{ $order->orderAddressDetail->post_code ?? $order->post_code }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Return</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderMeta as $data)
                            <tr data-product-id="{{ $data->product_id }}">
                                <td>
                                    <div class="c-art-item">
                                        <button class="remove-return-product mx-2"
                                            data-product-id="{{ $data->product_id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-x-circle">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="15" y1="9" x2="9" y2="15">
                                                </line>
                                                <line x1="9" y1="9" x2="15" y2="15">
                                                </line>
                                            </svg>
                                        </button>

                                        <div class="item-media"
                                            style="background-image: url('{{ $data->product->featured_image }}')">
                                        </div>
                                        <div class="item-info">
                                            <div class="cart-product-title"><strong>{{ $data->product_title }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="cart-cells" id="priceDisplay">
                                    ${{ $data->price }}
                                </td>
                                <td class="cart-cells" id="quantityDisplay">{{ $data->quantity }}</td>
                                <td class="cart-cells">
                                    <div class="input-group quantity-control">
                                        <button
                                            class="quantity-decrement btn btn-outline-secondary input-group-append btn-sm">-</button>
                                        <input id="demoInput" type="number"
                                            class="form-control form-control-sm text-center quantity-input" readonly
                                            value="{{ $data->quantity }}" min="0" max="{{ $data->quantity }}"
                                            oninput="handleInput(this)" disabled>
                                        <button
                                            class="quantity-increment btn btn-outline-secondary input-group-append btn-sm">+</button>
                                    </div>
                                    <div class="quantityError text-danger" style="font-size: xx-small;"></div>
                                </td>
                                <td class="cart-cells shippingPage-product-total" id="subTotalDisplay">
                                    ${{ $data->price * $data->quantity }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <div class="cart-total-section">
                    <div class="cart-total-section-header">Order Summery</div>
                    <div class="row m-3">
                        <div class="col-7">
                            Subtotal
                        </div>
                        <div class="col-5">
                            ${{ $order->subTotal ?? 0 }}
                        </div>
                    </div>
                    <div class="row border-top-orange py-3 mx-3">
                        <div class="col-7">
                            Registration Discount
                        </div>
                        <div class="col-5">
                            ${{ $order->auth_discount ?? 0 }}
                        </div>
                    </div>
                    @if ($order->amount_paid_by_coupon)
                        <div class="row border-top-orange py-3 mx-3">
                            <div class="col-7">
                                Amount Paid By Coupon
                            </div>
                            <div class="col-5">
                                ${{ $order->amount_paid_by_coupon ?? 0 }}
                            </div>
                        </div>
                    @endif
                    @if ($order->delivery_charges)
                        <div class="row border-top-orange py-3 mx-3">
                            <div class="col-7">
                                Amount Paid By Coupon
                            </div>
                            <div class="col-5">
                                ${{ $order->delivery_charges ?? 0 }}
                            </div>
                        </div>
                    @endif
                    <div class="row border-top-orange m-3 pt-3">
                        <div class="col-7">
                            Total Paid
                        </div>
                        <div class="col-5 shoppingPage-total_amount">
                            ${{ $order->grand_total ?? 0 }}
                        </div>
                    </div>
                </div>
                <div class="cart-total-section mt-3">
                    <div class="cart-total-section-header">Total Refundable</div>
                    <div class="row m-3">
                        <div class="col-7">
                            Subtotal
                        </div>
                        <div class="col-5">
                            <div class="refundable-grand-total">
                                <input class="w-100 grandTotalToReturn" type="text"
                                    value="${{ $order->grand_total }}" disabled
                                    style="border: none; outline: none; color: black;">
                            </div>
                        </div>
                    </div>
                    @if ($order->status != 'refund')
                        <div class="cart-total-section-header">Select Payment Method</div>
                        <div class="card-body">
                            @if ($order->payment_method == 'Paypal')
                                <div>
                                    <input type="radio" name="payment-method" id="paypal" value="paypal">
                                    <label for="paypal">Paypal</label>
                                </div>
                            @elseif ($order->payment_method == 'Stripe')
                                <div>
                                    <input type="radio" name="payment-method" id="stripe" value="stripe">
                                    <label for="stripe">Stripe</label>
                                </div>
                            @endif
                            <div>
                                <input type="radio" name="payment-method" id="gift-card" value="gift-card">
                                <label for="gift-card">Gift Card</label>
                            </div>
                            <div class="text-end w-100 mt-3">
                                <button class="btn btn-dark w-25" onclick="submitReturnOrder()">Return</button>
                            </div>
                            <div class="errorDiv">
                            </div>
                        </div>
                    @else
                        <div class="text-danger text-center"><b>This Payment is already Refunded.</b></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
<div id="statusToast"></div>
@include('Admin.Includes.footer')
<script>
    var grandTotalToReturn = 0;
    var updatedData = [];
    $(document).ready(function() {
        var grandTotalInput = $('.grandTotalToReturn').val();
        grandTotalToReturn = parseFloat(grandTotalInput.replace(/[^\d.]/g, ''));
    });

    function updateData(productId, newQuantity, newSubtotal) {
        updatedData[productId] = {
            quantity: newQuantity,
            subtotal: newSubtotal
        };
    }

    $('.quantity-control').on('click', '.quantity-increment', function() {
        var inputField = $(this).siblings('.quantity-input');
        increaseQuantity(inputField);
    });

    $('.quantity-control').on('click', '.quantity-decrement', function() {
        var inputField = $(this).siblings('.quantity-input');
        decreaseQuantity(inputField);
    });

    function increaseQuantity(inputField) {
        var currentValue = parseInt(inputField.val(), 10);
        if (!isNaN(currentValue)) {
            inputField.val(currentValue + 1);
            handleInput(inputField[0]);
        }
    }

    function decreaseQuantity(inputField) {
        var currentValue = parseInt(inputField.val(), 10);
        if (!isNaN(currentValue) && currentValue > 0) {
            inputField.val(currentValue - 1);
            handleInput(inputField[0]);
        }
    }

    function handleInput(inputField) {
        var event = window.event || arguments.callee.caller.arguments[0];
        var numericValue = parseFloat(inputField.value);
        if (isNaN(numericValue)) {
            inputField.value = inputField.getAttribute('data-previous-value') || 1;
        } else {
            inputField.setAttribute('data-previous-value', numericValue);
            handleReturnQuantity(inputField);
        }
    }

    function handleReturnQuantity(inputField) {
        var row = $(inputField).closest('tr');
        var quantityCell = row.find('#quantityDisplay');
        var productQuantity = parseInt(quantityCell.text(), 10);
        var returnQuantity = parseInt($(inputField).val(), 10);
        var productPriceText = row.find('#priceDisplay').text();
        var subTotalDisplay = row.find('#subTotalDisplay');
        var productId = row.data('product-id');

        if (returnQuantity < 0) {
            inputField.value = 0;
        }

        var productPriceMatches = productPriceText.match(/\d+(\.\d{1,2})?/);
        var productPrice = productPriceMatches ? parseFloat(productPriceMatches[0]) : 0;

        var oldSubTotalText = subTotalDisplay.text();
        var oldSubTotalMatches = oldSubTotalText.match(/\d+(\.\d{1,2})?/);
        var oldSubTotal = oldSubTotalMatches ? parseFloat(oldSubTotalMatches[0]) : 0;

        var errorContainer = row.find('.quantityError');

        if (returnQuantity > productQuantity) {
            errorContainer.text('Return quantity cannot exceed the available quantity');
            inputField.value = productQuantity;
        } else {
            errorContainer.empty();
            if (!isNaN(productPrice) && !isNaN(oldSubTotal)) {
                var newSubTotal = productPrice * returnQuantity;
                subTotalDisplay.text('$' + newSubTotal.toFixed(2));

                var subTotalDifference = oldSubTotal - newSubTotal;
                var refundableGrandTotal = parseFloat($('.refundable-grand-total input').val().match(
                    /\d+(\.\d{1,2})?/));
                var newRefundableGrandTotal = refundableGrandTotal - subTotalDifference;
                grandTotalToReturn = newRefundableGrandTotal.toFixed(2);

                var updatedRowData = {
                    productId: productId,
                    newQuantity: returnQuantity,
                    newSubTotal: newSubTotal,
                };
                var method = 'quantity';
                updateReturnOrder(productId, returnQuantity, newSubTotal, method, newRefundableGrandTotal.toFixed(2));

                $('.refundable-grand-total input').val('$' + newRefundableGrandTotal.toFixed(2));
            } else {
                console.log('Invalid product price or subTotal:', productPriceText);
                subTotalDisplay.text('$0.00');
            }
        }
    }

    $('table').on('click', '.remove-return-product', function() {
        var row = $(this).closest('tr');
        var productId = row.data('product-id');
        var quantity = 0;
        var method = 'remove';
        var productSubTotal = 0;
        var subTotalDisplay = row.find('.cart-cells.shippingPage-product-total').text();
        var subTotal = parseFloat(subTotalDisplay.replace(/[^\d.]/g, ''));

        row.remove();

        var returnableGrandTotalField = $('.refundable-grand-total input');
        var returnableGrandTotal = parseFloat(returnableGrandTotalField.val().replace(/[^\d.]/g, ''));
        var newReturnableGrandTotal = returnableGrandTotal - subTotal;

        grandTotalToReturn = newReturnableGrandTotal.toFixed(2);
        returnableGrandTotalField.val('$' + newReturnableGrandTotal.toFixed(2));
        updateReturnOrder(productId, quantity, productSubTotal, method, newReturnableGrandTotal.toFixed(2));
    });

    function updateReturnOrder(pId, quantity, productSubTotal, method, returnable) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{ route('Website.Return.Order.Update') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            data: {
                'pId': pId,
                'quantity': quantity,
                'productSubTotal': productSubTotal,
                'method': method,
                'returnable': returnable
            },
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                console.log(error + '======== error');
            }
        })
    }

    function submitReturnOrder() {
        $('#overlay').show();
        var total = grandTotalToReturn;
        if (total > 0) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var selectedMethod = $("input[name='payment-method']:checked").val();
            var invoiceNumber = $('#order_id').val();
            if (selectedMethod == 'paypal') {
                $.ajax({
                    url: '{{ route('Paypal_Refund') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: {
                        'total': total,
                        'invoiceNumber': invoiceNumber,
                        'updatedData': updatedData
                    },
                    success: function(response) {
                        $('#overlay').hide();
                        toastr.success(response.message);
                    },
                    error: function(error) {
                        $('#overlay').hide();
                        toastr.info('Note: Paypal not offer partial refund.');
                        toastr.error('Paypal is not accepting the refund of this payment.');
                    }
                })
            } else if (selectedMethod == 'stripe') {
                $.ajax({
                    url: '{{ route('Stripe_Refund') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: {
                        'total': total,
                        'invoiceNumber': invoiceNumber,
                        'updatedData': updatedData
                    },
                    success: function(response) {
                        $('#overlay').hide();
                        if (response.status) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(error) {
                        $('#overlay').hide();
                        toastr.error(error.message);
                    }
                })
            } else if (selectedMethod == 'gift-card') {
                $.ajax({
                    url: '{{ route('Gift_Card_Refund') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: {
                        'total': total,
                        'invoiceNumber': invoiceNumber,
                        'updatedData': updatedData
                    },
                    success: function(response) {
                        $('#overlay').hide();
                        if (response.status) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(error) {
                        $('#overlay').hide();
                        toastr.error(error.message);
                    }
                })
            } else {
                $('#overlay').hide();
                $('.errorDiv').append(`
                <div class="p-2 mt-3" style="background-color: rgb(255, 167, 167);"><b>Select a payment method first.</b></div>
                `);
                setTimeout(() => {
                    $('.errorDiv').empty();
                }, 3000);
            }
        }
    }
</script>
