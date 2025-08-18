@include('Website.Includes.header', ['meta_Details' => $meta_details])
<!-- MULTITAB SECTION -->
@php
    use App\General\GeneralSettingsClass;
@endphp
<style>
    .card_styling {
        background-image: url('/WebsiteAssets/images/GiftCardBg.png');
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        background-color: #FFCF3D;
        border: none;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: -5px 0 10px rgba(0, 0, 0, 0.2), 0 5px 10px rgba(0, 0, 0, 0.2);
        padding: 2rem 0;
    }

    .card-button {
        border: 2px solid black;
        color: black;
        font-weight: 600;
    }

    .card-footer {
        background-color: transparent;
        border: none;
    }

    .card-button:hover {
        border: 2px solid black;
        background-color: black;
        color: white;
        font-weight: 600;
    }

    .logo_div {
        position: absolute;
        bottom: 5px;
        left: 15px;
        text-align: start;
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
</style>
<div id="overlay">
    <div id="overlay-content">
        <div id="processing-message"> Processing... </div>
    </div>
</div>
<section class="mtop-50">
    <div class="container">
        <!-- LATEAST TOOL STORE -->
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="collection-view-top-section">
                    <h1 class="collection-view-title"> Gift Card Checkout </h1>
                    <p class="mb-0"><a href="#" class="collection-view-linked-desc">Home - Gift Cards -
                            Checkout</a></p>
                </div>
            </div>
        </div>

        <div class="row brand-ptop-20px">
            <div class="col-lg-8 col-md-7 col-sm-12">
                <p style="font-size: 18px; font-weight: 700;">Fill up the details so we can send your E-Gift Card.</p>
                <div class="row">
                    <input type="hidden" name="card_id" id="card_id" value="{{ $gift_card->id }}">
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
                        <input type="text" class="form-control" id="street_address" name="street_address"
                            value="{{ old('street_address') }}" required>
                        <label class="form-label" for="street_address">Streat Address</label>
                    </div>
                    <div class="col-md-6 form-group">
                        <input type="text" class="form-control" id="suburb" name="suburb" id="suburb"
                            value="{{ old('suburb') }}" required>
                        <label class="form-label" for="suburb">Suburb</label>
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
                        <input type="text" class="form-control" id="post_code" name="post_code" required>
                        <label class="form-label" for="post_code">Postcode</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-sm-6 my-3">
                <div class="card text-center card_styling">
                    <div class="card-body">
                        <h4 class="card-title" style="font-weight: 700;">${{ $gift_card->price }}</h4>
                        <p class="card-text" style="font-weight: 500;">{{ $gift_card->title }}</p>
                    </div>
                    <div class="card-footer">
                        <div class="w-100 logo_div">
                            <img class="w-25" src="{{ asset(url('/WebsiteAssets/images/black_logo.png')) }}"
                                alt="Sydney Quality Meats">
                        </div>
                    </div>
                </div>
                <div class="row m-2 pt-2">
                    <div class="row my-2 pt-3">
                        <div class="col-5">
                            Total Amount
                        </div>
                        <div class="col-7">
                            <b>${{ $gift_card->price }}</b>
                        </div>
                    </div>
                    <div id="errorDiv"></div>
                    <div class="col-12 border-top">
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
                    <div class="border-top pt-3">
                        <button class="btn btn-dark btn-lg w-100" type="button" id="payment-from">Proceed to
                            Pay</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('Website.Includes.footer')
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

        $('#payment-from').on('click', function(event) {
            var selectedPaymentMethod = $('input[name="payment_method"]:checked').val();
            let cardId = $('#card_id').val();
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
            if (selectedPaymentMethod == "paypal") {
                $('#overlay').show();
                $("#errorDiv").hide();
                $("#addressError").hide();
                $.ajax({
                    url: '/create-gift-card-order',
                    method: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    data: {
                        'shipping_address': data,
                        'gift_card_id': cardId
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
                    url: '{{ route('gift-card-payment') }}',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: {
                        'shipping_address': data,
                        'gift_card_id': cardId,
                        'card_number': card_number,
                        'cvc': cvc,
                        'card_expiry_year': expiry
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
</script>
