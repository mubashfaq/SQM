@include('Website.Includes.header', ['meta_Details' => $meta_details])
<style>
    .navCartItem {
        display: none;
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

    .c-art-item {
        background: white;
        border-radius: 8px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        border: 1px solid gray;
        padding: 5px;
        border-radius: 4px;
        margin: 5px;
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

    .remove-item-cart {
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
        border: 1px solid lightgray;
    }

    .cart-total-section-header {
        background: whitesmoke;
        color: black;
        font-size: 28px;
        padding: 12px;
        font-weight: 700;
    }

    .checkout-input-group {
        margin-bottom: 0px;
    }

    .checkout-card-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }

    .checout-card-number {
        display: flex;
        justify-content: space-between;
        border: 1px solid rgb(216, 216, 216);
        border-radius: 6px;
        padding: 8px 8px;
        background-color: white;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .checout-card-number input {
        border: 0;
        outline: 0;
        font-size: 14px;
        color: gray;
        background: transparent;
        width: 250px;
    }

    .checkout-card-details {
        background: white;
        border: 1px solid rgb(216, 216, 216);
        border-radius: 6px;
        border-top: 0;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .checkout-card-expiry input {
        font-size: 14px;
        color: gray;
        border: 0;
        outline: 0;
        background: transparent;
        padding: 10px 8px;
        width: 100%;
    }

    .checkout-card-csv {
        border-left: 1px solid rgb(216, 216, 216);
    }

    .checkout-card-csv input {
        font-size: 14px;
        color: gray;
        border: 0;
        outline: 0;
        background: transparent;
        padding: 10px 8px;
    }

    .card-information-group {
        margin-bottom: 15px;
        margin-top: 5px;
    }

    .form-group {
        position: relative;
        margin-bottom: 20px;
    }

    .form-label {
        position: absolute;
        pointer-events: none;
        top: 10px;
        left: 15px;
        transition: 0.2s ease all;
    }

    .form-control {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-control:focus+.form-label,
    .form-control:not(:placeholder-shown)+.form-label {
        top: -12px;
        font-size: 12px;
        background-color: white;
        padding: 0 5px;
    }

    .discountPlansCol {
        border: 2px solid gray;
        border-radius: 5px;
    }

    .discountPlansColSelected {
        border: 2px solid green;
        border-radius: 5px;
    }

    .discountPlansColLabel {
        font-weight: 600;
    }

    .selected-color {
        color: green;
    }

    .planRadio .planRadioBtn {
        accent-color: green;
    }

    .calculation-text {
        font-size: 18px;
        font-weight: 500;
    }

    .hide_content {
        display: none;
    }

    .loader {
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #3498db;
        width: 12px;
        height: 12px;
        -webkit-animation: spin 2s linear infinite;
        /* Safari */
        animation: spin 2s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 425px) {
        .different_shipping_address {
            margin-bottom: 24px;
            margin-right: 5px;
        }
    }

    .different_shipping_address {
        margin-right: 5px;
    }
</style>
<div id="overlay">
    <div id="overlay-content">
        <div id="processing-message"> Processing... </div>
    </div>
</div>
<div class="container-xl checkout-container">
    <div class="row w-100">
        <div class="col-md-8">
            <div class="checkout-logo">
                <a href="{{ url('/') }}">
                    <img src="{{ url('/') }}/WebsiteAssets/tooltrade.png" alt="Logo">
                </a>
            </div>
            <div class="checkout-form">
                <form>
                    @if ($userType == 'guest')
                        <div class="mt-3 px-3 py-2 d-flex align-items-center" style="background-color: #aefbae;">
                            <p class="mb-0" style="font-size: 15px; text-align:left;">
                                <b>Avail 10% more discount by &nbsp;</b>
                                <a href="{{ route('Website.Login') }}"><b>Login/Signup</b></a>
                            </p>
                        </div>
                    @endif
                    <div id="addressError"></div>
                    <div class="form-group mb-0 pt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="" class="checkout-contact-title">Billing Address</label>
                            @if ($userType == 'authenticated')
                                <div class="form-group">
                                    <button type="button" class="btn btn-warning" onclick="updateAddress(0)">+
                                        Add Address</button>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div id="validationError"></div>
                            @foreach ($addresses as $address)
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="card-body profile-password-change-section mb-3"
                                        onclick="handleRadioClick({{ $address->id }})">
                                        <input type="hidden" class="get_postcode" name="get_postcode"
                                            value="{{ $address->post_code }}">
                                        <div class="use_this_address">
                                            <input type="radio" name="selected_address" id="radio_{{ $address->id }}"
                                                value="{{ $address->id }}">
                                            <label for="selected_address">Use this Address</label>
                                            <hr>
                                        </div>
                                        <h5 class="card-title">
                                            {{ $address->f_name . ' ' . $address->l_name }}
                                        </h5>
                                        <div class="d-flex justify-content-between">
                                            <h6 class="card-subtitle mb-2">
                                                {{ $address->company }}
                                            </h6>
                                            <h6 class="card-subtitle mb-2"
                                                style="text-transform:capitalize; text-align:end">
                                                {{ $address->address_type == 'both' ? 'Billing & Shipping' : $address->address_type }}
                                            </h6>
                                        </div>
                                        <p class="mb-0">{{ $address->email }}</p>
                                        <p class="mb-0">{{ $address->phone }}</p>
                                        <p class="card-text mb-0">
                                            {{ $address->street_address }}
                                        </p>
                                        <p>{{ $address->suburb }}, {{ $address->city }},
                                            {{ $address->state }}, {{ $address->post_code }}</p>
                                        <hr>
                                        <button class="btn btn-warning" type="button"
                                            onclick="updateAddress({{ $address->id }})">Update</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($addresses->isEmpty())
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control" id="f_name" name="f_name"
                                        value="{{ old('f_name') }}" required>
                                    <label class="form-label" for="f_name">First Name</label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control" name="l_name" id="l_name"
                                        value="{{ old('l_name') }}" required>
                                    <label class="form-label" for="l_name">Last Name</label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" required>
                                    <label class="form-label" for="email">Email</label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="{{ old('phone') }}" required>
                                    <label class="form-label" for="phone">Phone Number</label>
                                </div>
                                <div class="col-md-12 form-group">
                                    <input type="text" class="form-control" id="company" name="company"
                                        value="{{ old('company') }}" required>
                                    <label class="form-label" for="company">Company or Business Name</label>
                                </div>
                                <div class="col-md-12 form-group">
                                    <input type="text" class="form-control address-input" id="street_address" name="street_address"
                                        value="{{ old('street_address') }}" required>
                                    <label class="form-label" for="street_address">Address</label>
                                     <div class="suggestions" style="background:white;"></div>
                                             <div class="details"></div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control address-input" id="suburb" name="suburb"
                                        id="suburb" value="{{ old('suburb') }}" required>
                                    <label class="form-label" for="suburb">Suburb</label>
                                    <div class="suggestions" style="background:white;"></div>
                                             <div class="details"></div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control" id="city" name="city"
                                        value="{{ old('city') }}" required>
                                    <label class="form-label" for="city">City</label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control" id="state" name="state"
                                        value="{{ old('state') }}" required>
                                    <label class="form-label" for="state">State</label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control" oninput="calculatePostageOnBilling(this)" id="post_code" name="post_code"
                                        required>
                                    <label class="form-label" for="post_code">Postcode</label>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="d-flex">
                            <input type="checkbox" name="different_shipping_address"
                                class="different_shipping_address" id="different_shipping_address">
                            <label for="different_shipping_address">Do You want to choose different
                                Shipping Address?</label>
                        </div>
                             <div id="shipping_address" style="display: none;">
                            <label for="" class="checkout-contact-title mt-3">Shipping Address</label>
                            <div id="shipping_address_form">
                                <div class="row mt-3">
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" id="shipping_f_name" name="shipping_f_name"
                                            value="{{ old('shipping_f_name') }}" required>
                                        <label class="form-label" for="shipping_f_name">Receiver First Name</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" name="shipping_l_name" id="shipping_l_name"
                                            value="{{ old('shipping_l_name') }}" required>
                                        <label class="form-label" for="shipping_l_name">Receiver Last Name</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="email" class="form-control" id="shipping_email" name="shipping_email"
                                            value="{{ old('shipping_email') }}" required>
                                        <label class="form-label" for="shipping_email">Email</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="tel" class="form-control" id="shipping_phone" name="shipping_phone"
                                            value="{{ old('shipping_phone') }}" required>
                                        <label class="form-label" for="shipping_phone">Phone Number</label>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <input type="text" class="form-control" id="shipping_company" name="shipping_company"
                                            value="{{ old('shipping_company') }}" required>
                                        <label class="form-label" for="shipping_company">Company or Business Name</label>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <input type="text" class="form-control address-input" id="shipping_street_address" name="shipping_street_address"
                                            value="{{ old('shipping_street_address') }}" required>
                                        <label class="form-label" for="shipping_street_address">Address</label>
                                        <div class="suggestions" style="background:white;"></div>
                                        <div class="details"></div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control address-input" id="shipping_suburb" name="shipping_suburb"
                                            value="{{ old('shipping_suburb') }}" required>
                                        <label class="form-label" for="shipping_suburb">Suburb</label>
                                        <div class="suggestions" style="background:white;"></div>
                                        <div class="details"></div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" id="shipping_city" name="shipping_city"
                                            value="{{ old('shipping_city') }}" required>
                                        <label class="form-label" for="shipping_city">City</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" id="shipping_state" name="shipping_state"
                                            value="{{ old('shipping_state') }}" required>
                                        <label class="form-label" for="shipping_state">State</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" id="shipping_post_code" name="shipping_post_code"
                                            value="{{ old('shipping_post_code') }}" required>
                                        <label class="form-label" for="shipping_post_code">Postcode</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    <div class="form-group" id="shipping_address"></div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="cart-total-section">
                <div class="cart-total-section-header">Cart Details</div>
                <div class="">
                    @foreach ($cartData as $data)
                        <div class="c-art-item my-1">
                            <div class="item-media w-25" style="background: url({{ $data['featuredImage'] }});">
                            </div>
                            <div class="item-info w-75">
                                <div class="cart-product-title"><strong>{{ $data['title'] }}</strong></div>
                                <div class=""><small>${{ $data['amount'] }} * {{ $data['quantity'] }} =
                                        <b>${{ $data['amount'] * $data['quantity'] }}</b></small></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row border-top m-2 pt-3">
                    <div class="col-5">
                        Subtotal
                    </div>
                    <div class="col-7">
                        <b>${{ $subTotal - $totalGst }}</b>
                        <input type="hidden" value="{{ $subTotal - $totalGst }}" id="subTotal">
                        <input type="hidden" value="{{ $totalGst }}" id="totalGst">
                    </div>
                </div>
                <div class="row border-top m-2 pt-3">
                    <div class="col-5">
                        Registration Discount
                    </div>
                    <div class="col-7">
                        <b>${{ $apply_discount }}</b>
                    </div>
                </div>
                <div class="row border-top m-2 pt-3">
                    <div class="col-5">
                        Total
                    </div>
                    @php
                        $total = $totalAmount - $apply_discount;
                    @endphp
                    <div class="col-7 totalAmountDiv">
                        <b>${{ $total }}</b>
                        <input type="hidden" value="{{ $totalAmount }}" id="total_amount">
                    </div>
                </div>
                <div class="row border-top m-2 py-3">
                    <div class="col-12">
                        Got a coupon code?
                    </div>
                    <div class="col-12">
                        <input type="hidden" name="grand_total" id="grand_total" value="{{ $discounted_amount }}">
                        <div class="input-group">
                            <input type="text" class="form-control" name="coupon_code" id="coupon_code"
                                placeholder="Coupon Code">
                            <button class="applyCoupon btn btn-warning">Apply</button>
                        </div>
                        <div class="coupon_code_error" style="color: red; font-weight: 500;"></div>
                    </div>
                </div>
                <div class="couponCalculations"></div>
                <div class="row border-top m-2 py-3">
                    <div class="col-5">
                        Grand Total
                    </div>
                    <div class="col-7 grandTotalAfterCoupon">
                        <input type="hidden" id="greatDiscountedTotal" value="{{ $discounted_amount }}">
                        <b>${{ $discounted_amount }}</b>
                    </div>
                </div>
                <div class="remainingCouponBalance"></div>
                @foreach ($plans as $plan)
                    <div style="display: none !important;"
                        class="m-2 p-2 d-flex justify-content-between align-items-center discountLable discountPlansCol">
                        <div class="planRadio">
                            <input type="radio" name="discountSelect" id="discountSelect{{ $plan->id }}"
                                class="planRadioBtn" data-plan-id="{{ $plan->id }}"
                                data-plan-discount="{{ $plan->discount_percentage }}">
                            <label class="discountPlansColLabel"
                                for="discountSelect{{ $plan->id }}">{{ $plan->plan_name }}</label>
                        </div>
                        <div class="discountAmount">
                            <h5 class="mb-0 discountPercentage">
                                {{ $plan->discount_percentage }}%</h5>
                        </div>
                    </div>
                @endforeach
                <div class="row border-top m-2 pt-3 hide_content postageCaculator">
                    {{-- <div>
                    <input type="checkbox" name="postcheck" value="1" id="checkboxchecked">
                    <label for="checkboxchecked">Want To Use Auspost</label>
                </div> --}}
                    <div class="loader_image">
                        <img src="{{ url('/') }}/WebsiteAssets/images/loader.gif"
                            style="width:27px; height:27px;" alt="" id="togglePassword">
                    </div>
                    <div id="service"></div>
                    <div id="postageResult"></div>
                    <div id="delivery_time"></div>
                    <div id="postageError" style="color: red; font-weight: 500;"></div>
                    <div class="hide_class hide_content">Total Amount:<b id="calculatedAmount"></b></div>
                </div>
                <div class="row border-top m-2 pt-3">
                    <div id="errorDiv"></div>
                    <div class="col-12">
                        Payment Method
                        <div class="form-group">
                            <div class="mb-1">
                                <label for="paypal-payments">
                                    <input type="radio" id="paypal-payments" name="payment_method"
                                        value="paypal">&nbsp;Paypal
                                </label>
                            </div>
                            <div class="mb-1">
                                <label for="stripe-payments">
                                    <input type="radio" id="stripe-payments" name="payment_method" value="stripe">
                                    &nbsp;Stripe
                                </label>
                            </div>
                            <div class="stripe-checkout-form" id="show_cardnumber" style="display:none;">
                                <div class="checkout-input-group">
                                    <label for="cardPayment" class="checkout-input-label">Card Information</label><br>
                                    <div class="card-information-group" id="cardPayment">
                                        <div class="checout-card-number">
                                            <input type="text" name="card_number" id="card_number"
                                                placeholder="1234 5678 9101 1234">
                                            <img width="35%" src="{{ url('/') . '/images/paymentcards.png' }}">
                                        </div>
                                        <div class="checkout-card-details">
                                            <div class="checkout-card-expiry">
                                                <input type="number" name="cvc" id="cvc"
                                                    placeholder="CSV">
                                            </div>
                                            <div class="checkout-card-csv">
                                                <input type="month" class="w-100" id="expiry" name="expiry"
                                                    placeholder="Expiry">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="border-top m-3 pt-3">
                    <a class="btn btn-dark btn-lg w-100 mb-2" href="{{ route('Website.Cart_Details') }}">Back To
                        Cart</a>
                    <button class="btn btn-dark btn-lg w-100" type="button" id="payment-from">Proceed to
                        Pay</button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Website.Includes.footer')
<script>
$(document).ready(function() {
  $("#different_shipping_address").change(function() {
    if (this.checked) {
      $("#shipping_address").show();
    } else {
      $("#shipping_address").hide();
    }
  });
});
    </script>
<script>
    document.getElementById("card_number").addEventListener("input", function() {
        this.value = this.value.replace(/[^0-9 ]/g, "");
        this.value = this.value.slice(0, 19);
    });

    document.getElementById("card_number").addEventListener("change", function() {
        this.value = this.value.replace(/ +/g, " ").trim();
        if (this.value.length !== 19) {
            alert("Card number must have exactly 16 digits and 3 spaces.");
            this.value = "";
        }
    });
</script>
<script>
    var shippingAddress = 0;
    var billingAddress = 0;
    var discountPlanId = 0;
    var deliveryFee = 0;
    $("#errorDiv").hide();
    $("#errorDiv").append("");
    $("#addressError").hide();
    $("#addressError").append("");
    $("#validationError").hide();
    $("#validationError").append("");

    function handleRadioClick(addressId) {
        billingAddress = parseInt(addressId);
        var radio = document.getElementById('radio_' + addressId);
        radio.checked = true;

        var getpost = $('#radio_' + addressId).closest('.card-body').find('.get_postcode').val();

        calculatePostageForShippingAddress(getpost);
        console.log('n1');

    }

    function handleShippingAddress(addressId) {
        shippingAddress = parseInt(addressId);
        var radio = document.getElementById('radio_' + addressId);
        radio.checked = true;
    }

    function handleCalculateDiscount(planId) {
        $('.total-discounted-section').empty();
        var subTotal = $('#subTotal').val();
        var totalGst = $('#totalGst').val();
        var discount = parseInt(subTotal) * (parseInt(planId) / 100);
        var discountedPrice = parseInt(subTotal) - parseInt(discount);
        var grandTotal = parseInt(discountedPrice) + parseInt(totalGst);
        $('.total-discounted-section').append(`
            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between">
                <p class="calculation-text mb-0">After Discount</p>
                <p class="calculation-text mb-0">$` + discountedPrice + `</p>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between">
                <p class="calculation-text mb-0">Total GST</p>
                <p class="calculation-text mb-0">$` + totalGst + `</p>
            </div><hr>
            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between">
                <p class="total-text mb-0">Grand Total</p>
                <p class="total-amount mb-0">$` + grandTotal + `</p>
            </div
            `);
    }
    function handleRestoreGrandTotal() {
        var coupon_code = $('#coupon_code').val("");
        $.ajax({
            type: 'GET',
            url: '/restore-grand-total',
            success: function(response) {
                if (response.status) {
                    console.log("session value restored");
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    var couponId = 0;
    var couponRemaining = 0;
    var grandTotalCal = 0;
    var count = 0;
    $(document).ready(function() {
        $('.applyCoupon').on('click', function() {

            $('.coupon_code_error').empty();
            var grand_total1 = $('#grand_total').val();
            var coupon_code = $('#coupon_code').val();

            if (coupon_code === '' || coupon_code === null) {
                count = 0;
                var $div = $('.grandTotalAfterCoupon');
                var $bTag = $div.find("b");
                $bTag.text('$' + grand_total1);
                $('.remainingCouponBalance').empty();
                $('.couponCalculations').empty();
                handleRestoreGrandTotal();
                location.reload();
                return;
            }
            if (count == 0) {
                count = 1;
                var csrfToken = $('input[name="_token"]').val();
                var grand_total = $('#grand_total').val();
                $.ajax({
                    type: 'GET',
                    url: '/apply-coupon-code',
                    data: {
                        code: coupon_code,
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        console.log(JSON.stringify(response));
                        if (response.status) {
                            $('.coupon_code_error').empty();
                            var billingRadio = document.getElementById('radio_' +
                                billingAddress);
                            var shippingRadio = document.getElementById('radio_' +
                                shippingAddress);

                            if (billingRadio) {
                                billingRadio.checked = false;
                            }
                            if (shippingRadio) {
                                shippingRadio.checked = false;
                            }

                            $('.postageCaculator').addClass('hide_content');
                            $('#payment-from').addClass('disabled');

                            var amountFromResponse = response.data;
                            var $div = $('.grandTotalAfterCoupon');
                            var $bTag = $div.find("b");
                            var currentValue = parseFloat($bTag.text().trim().replace('$',
                                ''));
                            var remainingAmount = currentValue - amountFromResponse;

                            if (remainingAmount < 0) {
                                remainingAmount = 0;
                            }
                            $bTag.text('$' + remainingAmount);
                            $('.couponCalculations').append(`
                                <div class="row border-top m-2 py-3">
                                    <div class="col-5">
                                        Coupon Amount
                                    </div>
                                    <div class="col-7">
                                        <b>$${amountFromResponse}</b>
                                    </div>
                                </div>
                            `);
                        } else {
                            count = 0;
                            $('.coupon_code_error').append(response.message);
                        }
                    },
                    error: function(error) {
                        count = 0;
                        console.log(error);
                    }
                });
            } else {
                return;
            }
        });
    });

    $(document).ready(function() {
        let shipping_address_id = 0;
        const radioLabels = document.querySelectorAll('.paypal-btn');
        radioLabels.forEach(label => {
            label.addEventListener('click', () => {
                const radioButton = label.querySelector('input[type="radio"]');
                radioButton.checked = true;
                $('#show_cvc').hide();
                $('#show_cardnumber').hide();
            });
        });
        const stripeLable = document.querySelectorAll('.stripe-btn');
        stripeLable.forEach(label => {
            label.addEventListener('click', () => {
                const radioButton = label.querySelector('input[type="radio"]');
                radioButton.checked = true;
                $('#show_cvc').show();
                $('#show_cardnumber').show();
            });
        });
        const discountLable = document.querySelectorAll('.discountLable');
        discountLable.forEach(label => {
            label.addEventListener('click', () => {
                const radioButton = label.querySelector('input[type="radio"]');
                radioButton.checked = true;
                const h5Element = label.querySelector('.discountPercentage');

                const planId = radioButton.getAttribute('data-plan-id');
                discountPlanId = planId;
                const plandiscount = radioButton.getAttribute('data-plan-discount');
                handleCalculateDiscount(plandiscount);

                // Remove the 'selected-column' class from all columns
                discountLable.forEach(label => {
                    label.classList.remove('discountPlansColSelected');
                    label.classList.add('discountPlansCol');
                });
                discountLable.forEach(label => {
                    label.querySelector('.discountPercentage').classList.remove(
                        'selected-color');
                });

                h5Element.classList.add('selected-color');
                label.classList.remove('discountPlansCol')
                label.classList.add('discountPlansColSelected');
            });
        });
        // $('.different_shipping_address').change(function() {
        //     if (this.checked) {
        //         $.ajax({
        //             url: '/change-shipping-address',
        //             method: 'GET',
        //             success: function(response) {
        //                 $('#shipping_address').append(response);
        //                 $("#validationError").hide();
        //             },
        //             error: function(error) {
        //                 console.log(error);
        //             }
        //         })
        //     } else {
        //         $('#shipping_address').html('');
        //     }
        // });
        $('#payment-from').on('click', function(event) {
            var selectedPaymentMethod = $('input[name="payment_method"]:checked').val();
            var differentShippingAddressVal = $('input[name="different_shipping_address"]:checked')
                .val();
            var postcheck = $('input[name="postcheck"]:checked').val();
            var totalamount = $('#total_amount').val();
            var newsletterCheck = $('.newsletterCheck').val();
            var data = {};
            data = {
                'f_name': $('#f_name').val(),
                'l_name': $('#l_name').val(),
                'email': $('#email').val(),
                'company': $('#company').val(),
                'street_address': $('#street_address').val(),
                'suburb': $('#suburb').val(),
                'city': $('#city').val(),
                'state': $('#state').val(),
                'post_code': $('#post_code').val(),
                'phone': $('#phone').val(),
            }
            if ($('input[name="different_shipping_address"]:checked').length > 0) {
                data = {
                    'f_name': $('#shipping_f_name').val(),
                    'l_name': $('#shipping_l_name').val(),
                    'email': $('#shipping_email').val(),
                    'company': $('#shipping_company').val(),
                    'street_address': $('#shipping_street_address').val(),
                    'suburb': $('#shipping_suburb').val(),
                    'city': $('#shipping_city').val(),
                    'state': $('#shipping_state').val(),
                    'post_code': $('#shipping_post_code').val(),
                    'phone': $('#shipping_phone').val(),
                }
            }
            if (selectedPaymentMethod == "paypal") {
                $('#overlay').show();
                $("#errorDiv").hide();
                $("#addressError").hide();
                $.ajax({
                    url: '/create-paypal-order',
                    method: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    data: {
                        'shipping_address': shippingAddress == 0 ? data.f_name ? data :
                            billingAddress : shippingAddress,
                        'discountPlanId': discountPlanId,
                        'deliveryFee' : deliveryFee
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#overlay').hide();
                        if (response.status) {
                            window.location.href = response.approval_link;
                        } else {
                            toastr.error('Something Went Wrong please try again');
                        }
                    },
                    error: function(response) {
                        $('#overlay').hide();
                        if (response.status == 422) {
                            $("#validationError").show();
                            $('#validationError').append(`
                            <div class="px-3 py-2 my-3"
                                style="background: rgb(242, 126, 126); border-radius: 5px;">
                                <p class="mb-0 text-start">
                                    <b>Please Fill all the fields properly.</b>
                                </p>
                            </div>`);
                            $("html, body").animate({
                                scrollTop: $("#validationError").offset().top
                            }, "slow");
                        }
                    }
                });

            } else if (selectedPaymentMethod == "stripe") {
                $('#overlay').show();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                let card_number = $('#card_number').val();
                let cvc = $('#cvc').val();
                let expiry = $('#expiry').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('stripe-payment') }}',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: {
                        'selected_address': shippingAddress == 0 ? data.f_name ? data :
                            billingAddress : shippingAddress,
                        'postcheck': postcheck,
                        'card_number': card_number,
                        'cvc': cvc,
                        'card_expiry_year': expiry,
                        'discountPlanId': discountPlanId,
                        'deliveryFee' : deliveryFee
                    },
                    success: function(response) {
                        $('#overlay').hide();
                        if (response.success) {
                            window.location.href = '/thank-you';
                        } else {
                            toastr.error('Something Went Wrong please try again');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#overlay').hide();
                        if (response.status == 422) {
                            $("#validationError").show();
                            $("#validationError").append(`
                            <div class="px-3 py-2 mb-3"
                                style="background: rgb(242, 126, 126); border-radius: 5px;">
                                <p class="mb-0 text-start">
                                    <b>Please Fill all the fields properly.</b>
                                </p>
                            </div>`)
                            $("html, body").animate({
                                scrollTop: $("#validationError").offset().top
                            }, "slow");
                        }
                    }
                });

            }else if(selectedPaymentMethod == "afterpay"){
                console.log('afterpay successed');;
                $.ajax({
                    url: '/initiate-payment',
                    method: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    data: {
                        'shipping_address': shippingAddress == 0 ? data.f_name ? data :
                            billingAddress : shippingAddress,
                        'discountPlanId': discountPlanId,
                        'deliveryFee' : deliveryFee
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#overlay').hide();
                        if (response.status) {
                            console.log('success')
                            // window.location.href = response.approval_link;
                        } else {
                            toastr.error('Something Went Wrong please try again');
                        }
                    },
                    error: function(response) {
                        $('#overlay').hide();
                        if (response.status == 422) {
                            $("#validationError").show();
                            $('#validationError').append(`
                            <div class="px-3 py-2 my-3"
                                style="background: rgb(242, 126, 126); border-radius: 5px;">
                                <p class="mb-0 text-start">
                                    <b>Please Fill all the fields properly.</b>
                                </p>
                            </div>`);
                            $("html, body").animate({
                                scrollTop: $("#validationError").offset().top
                            }, "slow");
                        }
                    }
                });
            } 
            else if (!billingAddress) {
                let email = $('#email').val();
                if (email == "") {
                    $('#addressError').show();
                    $("#addressError").append(`
                <div class="px-3 py-2 mt-3"
                    style="background: rgb(242, 126, 126); border-radius: 5px;">
                    <p class="mb-0 text-start">
                        <b>Address is required to move on.</b>
                    </p>
                </div>`);
                    $("#errorDiv").hide();
                    $("html, body").animate({
                        scrollTop: $("#addressError").offset().top
                    }, "slow");
                } else if (!selectedPaymentMethod) {
                    $("#errorDiv").show();
                    $("#errorDiv").append(`
                <div class="px-3 py-2 my-3"
                   style="background: rgb(242, 126, 126); border-radius: 5px;">
                    <p class="mb-0 text-start">
                        <b>Please select a payment method first.</b>
                    </p>
                </div>`);
                    $("#addressError").hide();
                    $("html, body").animate({
                        scrollTop: $("#errorDiv").offset().top
                    }, "slow");
                }
            } else if (!selectedPaymentMethod) {
                $("#errorDiv").show();
                $("#errorDiv").append(`
                <div class="px-3 py-2 my-3"
                   style="background: rgb(242, 126, 126); border-radius: 5px;">
                    <p class="mb-0 text-start">
                        <b>Please select a payment method first.</b>
                    </p>
                </div>`);
                $("#addressError").hide();
                $("html, body").animate({
                    scrollTop: $("#errorDiv").offset().top
                }, "slow");
            }
        });
    });
    $(document).ready(function() {
        $('input[name="payment_method"]').change(function() {
            var selectedPaymentMethod = $(this).val();
            if (selectedPaymentMethod === "stripe") {
                $('#show_cvc').show();
                $('#show_cardnumber').show();
            } else {
                $('#show_cvc').hide();
                $('#show_cardnumber').hide();
            }
        });
    });
</script>


<script>

    // afaq post
        function calculatePostageOnBilling(obj) {

            if ($('#different_shipping_address').is(':checked')) {
                return;
            }
            var postCode = $(obj).val();
            if (postCode.length > 3) {
                if (postCode.trim() !== '') {
                    $('.postageCaculator').removeClass('hide_content');
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    var formData = {
                        post_code: postCode,
                    };
                    $('#payment-from').addClass('disabled');
                    $.ajax({
                        type: 'POST',
                        url: '/calculate-postage',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            if (response?.res == 0) {
                                $('.postageCaculator').addClass('hide_content');
                                $('#payment-from').removeClass('disabled');
                                return 0;
                            }
                            var totalCost = response.postage_cost.postage_result.total_cost;
                            deliveryFee = response.postage_cost.postage_result.total_cost;
                            var delivery_time = response.postage_cost.postage_result
                                .delivery_time;
                            var service = response.postage_cost.postage_result.service;
                            $('#postageResult').text('Postage Cost: $' + totalCost);
                            $('#delivery_time').text('Delivery Time:' + delivery_time);
                            $('#service').text('Service: ' + service);
                            $('#calculatedAmount').text(response.calculatedAmount);
                            $('.hide_class').removeClass('hide_content');
                            $('.loader_image').addClass('hide_content');
                            $('#postageError').text("");
                            $('#payment-from').removeClass('disabled');
                        },
                        error: function(xhr, status, error) {
                            $('.loader_image').addClass('hide_content');
                            $('#postageError').text("Invalid Post Code");
                        }
                    });
    
                } else {
                    $('.postageCaculator').addClass('hide_content');
                    $('#postageResult').text('');
                    $('#delivery_time').text('');
                    $('#service').text('');
                    $('#calculatedAmount').text('');
                    $('#payment-from').removeClass('disabled');
                }
            }
        }



    function calculatePostageForShippingAddress(postalCodeInput) {
        var postCode ="";

        if (typeof postalCodeInput === 'number') {
            postCode = postalCodeInput.toString();
        } else if (typeof postalCodeInput === 'string') {
            postCode = postalCodeInput;
        } else if (typeof postalCodeInput === 'object' && postalCodeInput instanceof jQuery) {
            postCode = postalCodeInput.val();
        }else {
            console.log('Invalid input: postalCodeInput must be a numeric value, string, or a jQuery input element.');
            return;
        }


        if (postCode?.trim() !== '') {
            $('.postageCaculator').removeClass('hide_content');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var formData = {
                post_code: postCode,
            };
            $('#payment-from').addClass('disabled');
            $.ajax({
                type: 'POST',
                url: '/calculate-postage',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);
                    if (response?.res == 0) {
                        $('.postageCaculator').addClass('hide_content');
                        $('#payment-from').removeClass('disabled');
                        return 0;
                    }
                    var totalCost = response.postage_cost.postage_result.total_cost;
                    deliveryFee = response.postage_cost.postage_result.total_cost;
                    var delivery_time = response.postage_cost.postage_result.delivery_time;
                    var service = response.postage_cost.postage_result.service;
                    $('#postageResult').text('Postage Cost: $' + totalCost);
                    $('#delivery_time').text('Delivery Time: ' + delivery_time);
                    $('#service').text('Service: ' + service);
                    $('#calculatedAmount').text(' $' + response.calculatedAmount);
                    $('.hide_class').removeClass('hide_content');
                    $('.loader_image').addClass('hide_content');
                    $('.postageCaculator').removeClass('hide_content');
                    $('#postageError').text("");
                    $('#payment-from').removeClass('disabled');
                },
                error: function(xhr, status, error) {

                    $('.loader_image').addClass('hide_content');
                    $('#postageError').text("Invalid Post Code");
                    $('.hide_class').addClass('hide_content');

                    $('#postageResult').text('');
                    $('#delivery_time').text('');
                    $('#service').text('');
                    $('#calculatedAmount').text('');
                    console.log('Error: ' + error);
                }
            });
        } else {
            $('.postageCaculator').addClass('hide_content');
            $('#postageResult').text('');
            $('#delivery_time').text('');
            $('#service').text('');
            $('#calculatedAmount').text('');
            $('#postageError').text('');
            $('#payment-from').removeClass('disabled');
        }
    }
    $('body').on('input', '#shipping_post_code', function() {
        calculatePostageForShippingAddress($(this));
          console.log('n2')
    });
    $('body').on('change', '#different_shipping_address', function() {
        if ($(this).is(':checked')) {
            // Find the corresponding postal code input
            var postalCodeInput = $('#shipping_post_code');
            calculatePostageForShippingAddress(postalCodeInput);
              console.log('n3');
        }
    });
</script>
