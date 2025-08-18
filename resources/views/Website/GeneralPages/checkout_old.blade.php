<style>
    .navbar-second {
        display: none;
    }

    .topbar-section {
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
</style> @include('Website.Includes.header', ['meta_Details' => $meta_details])
<!-- MULTITAB SECTION -->
<div id="overlay">
    <div id="overlay-content">
        <div id="processing-message"> Processing... </div>
    </div>
</div>
<div class="">
    <div class="container-fluid checkout-background">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-sm-12 checkout-background-white">
                <div class="container checkout-section ">
                    <div class="row justify-content-end">
                        <div class="col-lg-9 col-md-12 col-sm-12">
                            <div class="checkout-logo">
                                <a href="{{ url('/') }}">
                                    <img src="{{ url('/') }}/WebsiteAssets/tooltrade.png" alt="Logo">
                                </a>
                            </div>
                            <div class="checkout-form">
                                <form>
                                    <label for="" class="checkout-contact-title">Contact Information</label>
                                    @if ($userType == 'authenticated')
                                        <input type="text" id="user-email" class="form-control"
                                            value="{{ $user->email }}" disabled readonly placeholder="Email">
                                    @else
                                        <input type="text" id="user-email" class="form-control" value=""
                                            placeholder="Email" required>
                                    @endif
                                    <div class="form-check pt-2">
                                        <input class="form-check-input newsletterCheck" type="checkbox"
                                            name="newsletterCheck" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault"> Email me with news and
                                            offers </label>
                                    </div>
                                    @if ($userType == 'guest')
                                        <div class="mt-3 px-3 py-2 d-flex justify-content-between align-items-center"
                                            style="background-color: #aefbae;">
                                            <p class="mb-0" style="font-size: 15px; text-align:left;">
                                                <b>Avail 10% more discount by signning up or logging in.</b>
                                            </p>
                                            <a href="{{ route('Website.Login') }}" class="bg-warning py-1 px-2 rounded"
                                                style="color: black; text-decoration: none; border: 1px solid black;"><b>Login/Signup</b></a>
                                        </div>
                                    @endif
                                    <div id="addressError"></div>
                                    <div class="form-group pt-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="" class="checkout-contact-title">Billing Address</label>
                                            @if ($userType == 'authenticated')
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-warning"
                                                        onclick="updateAddress(0)">+
                                                        Add Address</button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            @foreach ($addresses as $address)
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="card-body profile-password-change-section mb-3"
                                                        onclick="handleRadioClick({{ $address->id }})">
                                                        <div class="use_this_address">
                                                            <input type="radio" name="selected_address"
                                                                id="radio_{{ $address->id }}"
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
                                            <div id="validationError"></div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <input type="text" class="form-control" name="f_name"
                                                        id="f_name" placeholder="First Name"
                                                        value="{{ old('f_name') }}" required>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <input type="text" class="form-control" name="l_name"
                                                        id="l_name" placeholder="Last Name"
                                                        value="{{ old('l_name') }}" required>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-12 pt-3">
                                                    <input type="text" class="form-control" name="company"
                                                        id="company" value="{{ old('company') }}"
                                                        placeholder="Company (Required for business Address)" required>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-12 pt-3">
                                                    <input type="number" class="form-control" name="phone"
                                                        id="phone" placeholder="Phone" value="{{ old('phone') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="form-group pt-3">
                                                <input type="text" class="form-control" name="street_address"
                                                    id="street_address" value="{{ old('street_address') }}"
                                                    placeholder="Street Address" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12 pt-3">
                                                    <input type="text" class="form-control" name="suburb"
                                                        id="suburb" placeholder="Suburb"
                                                        value="{{ old('suburb') }}" required>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 pt-3">
                                                    <input type="text" class="form-control" name="city"
                                                        id="city" placeholder="City"
                                                        value="{{ old('city') }}" required>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                                                    <input type="text" name="state" id="state"
                                                        class="form-control" placeholder="State"
                                                        value="{{ old('state') }}" required>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                                                    <input type="text" class="form-control" name="post_code"
                                                        id="post_code" placeholder="PostCode"
                                                        value="{{ old('post_code') }}" required>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="different_shipping_address"
                                            class="different_shipping_address" id="different_shipping_address">
                                        <label for="different_shipping_address">Do You want to choose different
                                            Shipping Address?</label>
                                        <div id="shipping_address mt-3"></div>
                                    </div>
                                    <div class="form-group pt-3" id="shipping_address"></div>
                                    <div id="errorDiv"></div>
                                    <div class="checkout-express-section mt-3">
                                        <span>Payment Methods</span>
                                        <div class="payments-btns row">
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <button type="button" class="btn paypal-btn w-100">
                                                    <input type="radio" name="payment_method" value="paypal">
                                                    <img src="{{ url('/') }}/WebsiteAssets/images/paypal_PNG3.png"
                                                        class="paypal-img" alt="">
                                                </button>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <button type="button" class="btn stripe-btn w-100">
                                                    <input type="radio" name="payment_method" value="stripe">
                                                    <b>Stripe</b>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group pt-3" id="show_cardnumber" style="display:none;">
                                        <input type="text" class="form-control card-number" name="card_number"
                                            id="card_number" placeholder="Card Number">
                                    </div>
                                    <div class="row" id="show_cvc" style="display:none;">
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <div class="form-group pt-3">
                                                <input type="text" class="form-control" size='4'
                                                    name="cvc" id="cvc" placeholder="CVC">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <div class="form-group pt-3">
                                                <input type="month" class="form-control card-expiry-month"
                                                    name="card_expiry_year" id="expiry" placeholder='MM'
                                                    size='2'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="back-to-cart">
                                            <a class="btn" href="{{ route('Website.Cart_Details') }}">Back To
                                                Cart</a>
                                        </div>
                                        <div class="checkout-shipping-btn">
                                            <button class="btn" type="button" id="payment-from">Proceed To
                                                Pay</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 checkout-ptop-50px checkout-right-col">
                <div class="container checkout-section ">
                    <div class="row justify-content-start">
                        <div class="col-lg-10 col-md-12 col-sm-12">
                            @foreach ($cartData as $data)
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <div class="checkout-produt-image">
                                            <img src="{{ $data['featuredImage'] }}" class="img-fluid"
                                                alt="Product Image">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="checkout-produt-desc">
                                            <p>{{ $data['title'] }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="mb-0">${{ $data['amount'] }} * {{ $data['quantity'] }}</p>
                                            <span>=</span>
                                            <p class="mb-0 r-product-amount">${{ $data['amount'] * $data['quantity'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="subtotoal-details">
                                        <p>Subtotal</p>
                                        <p class="r-product-amount">${{ $subTotal - $totalGst }}</p>
                                        <input type="hidden" value="{{ $subTotal - $totalGst }}" id="subTotal">
                                        <input type="hidden" value="{{ $totalGst }}" id="totalGst">
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <p class="checkout-desc">**Expedited orders cannot be shipped or delivered on
                                    weekends/holidays nor can they be shipped to a POBox.</p>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="total-detailed-section">
                                        <p class="total-text">Total</p>
                                        <p class="total-amount">
                                            ${{ $totalAmount }}
                                        </p>
                                        <input type="hidden" value="{{ $totalAmount }}" id="total_amount">
                                    </div>
                                </div>
                                @foreach ($plans as $plan)
                                    <div
                                        class="col-md-12 my-2 py-2 d-flex justify-content-between align-items-center discountLable discountPlansCol">
                                        <div class="planRadio">
                                            <input type="radio" name="discountSelect"
                                                id="discountSelect{{ $plan->id }}" class="planRadioBtn"
                                                data-plan-id="{{ $plan->id }}"
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
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row mt-3 total-discounted-section">
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
@include('Website.Includes.footer')
<script>
    var shippingAddress = 0;
    var billingAddress = 0;
    var discountPlanId = 0;
    $("#errorDiv").hide();
    $("#errorDiv").empty();
    $("#addressError").hide();
    $("#addressError").empty();
    $("#validationError").hide();
    $("#validationError").empty();

    function handleRadioClick(addressId) {
        billingAddress = parseInt(addressId);
        var radio = document.getElementById('radio_' + addressId);
        radio.checked = true; // Call the function to fetch address data
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
        $('.different_shipping_address').change(function() {
            if (this.checked) {
                $.ajax({
                    url: '/change-shipping-address',
                    method: 'GET',
                    success: function(response) {
                        $('#shipping_address').append(response);
                        $("#validationError").hide();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                })
            } else {
                $('#shipping_address').html('');
            }
        });
        $('#payment-from').on('click', function(event) {
            if ($('#user-email').val() == "") {
                toastr.error('Provide the email so we can send you Order Information.');
                return;
            }
            var selectedPaymentMethod = $('input[name="payment_method"]:checked').val();
            var totalamount = $('#total_amount').val();
            // var userEmail = $('#user-email').val();
            var userEmail = 'nauman@g-techsolutions.com.au';
            var newsletterCheck = $('.newsletterCheck').val();
            var data = {};
            data = {
                'f_name': $('#f_name').val(),
                'l_name': $('#l_name').val(),
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
                        'email': userEmail,
                        'newsletterCheck': newsletterCheck,
                        'discountPlanId': discountPlanId,
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#overlay').hide();
                        if (response.status) {
                            // Redirect user to PayPal for payment approval
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
                        'email': userEmail,
                        'newsletterCheck': newsletterCheck,
                        'card_number': card_number,
                        'cvc': cvc,
                        'card_expiry_year': expiry,
                        'discountPlanId': discountPlanId
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

            } else if (!billingAddress) {
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
        });
    });
    // Affaq Bhai's Code
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
