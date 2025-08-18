@extends('Website.Includes.header', ['meta_Details' => $meta_details])

@section('content')
    @php
        $setting = \App\Models\GeneralSettings::all();
        use App\General\GeneralSettingsClass;
        $stripe_key = GeneralSettingsClass::getRequiredValue('_stripe_publish_key');
        $deliveryCharge = GeneralSettingsClass::getRequiredValue('_delivery_charge');
        $gstPercentage = GeneralSettingsClass::getRequiredValue('_gst_percentage');

    @endphp
    <!-- Section 1 -->
    <section class="about-sec1">
        <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
        <div class="container">
            <div class="text-center">
                <h2>Checkout</h2>
                <p><span> Sydney Quality Meats :</span> Checkout</p>
            </div>
        </div>
    </section>
    <!-- Section 1 End -->

    <!-- Main Checkout Form -->
    <form id="contactForm" action="{{ url('/place-order') }}" method="POST">
        @csrf

        <!-- ========== Hidden Fields Required by Your Controller Validation ========== -->
        <input type="hidden" name="status" value="pending" />
        <input type="hidden" name="subTotal" value="{{ number_format(session('subTotal', 0), 2) }}" />
        <input type="hidden" name="totalGst" id="totalGst" value="{{ number_format(session('totalGst', 0), 2) }}" />
        <input type="hidden" name="total_amount" value="{{ number_format($totalIncludingGST, 2) }}" />
        <input type="hidden" name="grand_total"
            value="{{ number_format($totalIncludingGST + session('deliveryCharge', 0), 2) }}" />

        <input type="hidden" name="auth_discount_per" value="0" />
        <input type="hidden" name="auth_discount" value="0" />
        <input type="hidden" name="amount_paid_by_coupon" value="0" />
        <input type="hidden" name="coupon_id" value="" />
        <!-- Notice the id on this hidden field so we can update it dynamically -->
        <input type="hidden" name="delivery_charges" id="delivery_charges" value="{{ session('deliveryCharge') ?? 0 }}" />
        <input type="hidden" name="gst_on_shipping" id="gst_on_shipping" value="0" />
        <input type="hidden" name="channel" value="website" />
        <input type="hidden" name="admin_delete" value="0" />

        <!-- We only have Stripe now, so a single hidden payment_method field -->
        <input type="hidden" name="payment_method" value="card">

        <!-- Pass each product from cart -->
        @foreach ($cartData as $index => $item)
            <input type="hidden" name="products[{{ $index }}][id]" value="{{ $item['pId'] }}" />
            <input type="hidden" name="products[{{ $index }}][quantity]" value="{{ $item['weight'] }}" />
            <input type="hidden" name="products[{{ $index }}][qp_subtotal]"
                value="{{ number_format($item['amount'] * $item['quantity'], 2, '.', '') }}" />
        @endforeach
        <!-- ========== End Hidden Fields ========== -->

        <!-- ========== Section 2: Billing & Additional Info ========== -->
        <div class="container pt-5 pb-0">
            <!-- Coupon Section -->
            <div class="coupon-section d-none">
                Have a coupon?
                <a href="#" class="ms-2" id="toggleCoupon">Click here to enter your code</a>
            </div>

            <div class="coupon-form mb-5" id="couponForm" style="display: none;">
                <p>If you have a coupon code, please apply it below.</p>
                <div class="row g-2">
                    <div class="col-auto">
                        <input type="text" class="Checkout_form-control" placeholder="Coupon Code" name="coupon_input" />
                    </div>
                    <div class="col-auto">
                        <button class="apply-coupon" type="button">Apply Coupon</button>
                    </div>
                </div>
            </div>



            <!-- Billing Details -->
            <div class="row">
                <div class="col-12 col-lg-6 col-xxl-8">
                    <h2 class="Biling_section-title">BILLING DETAILS</h2>

                    <!-- Name -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="Checkout_form-control" placeholder="First name" name="f_name"
                                value="{{ old('f_name', $user_address->f_name ?? '') }}" required />
                            @error('f_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-sm-0">
                            <input type="text" class="Checkout_form-control" placeholder="Last name" name="l_name"
                                value="{{ old('l_name', $user_address->l_name ?? '') }}" required />
                            @error('l_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Company & Country -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="Checkout_form-control" placeholder="Company name (optional)"
                                name="company" value="{{ old('company') }}" />
                            @error('company')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-sm-0">
                            <input type="text" placeholder="Country (optional)" class="Checkout_form-control"
                                name="country" value="{{ old('country') }}" />
                            @error('country')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Street Address -->
                    <div class="mb-3">
                        <input type="text" class="Checkout_form-control" placeholder="House number and street name"
                            name="street_address"
                            value="{{ old('street_address', $user_address->street_address ?? '') }}" required />
                        @error('street_address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Apartment or Unit -->
                    <div class="mb-3">
                        <input type="text" class="Checkout_form-control"
                            placeholder="Apartment, suite, unit, etc. (optional)" name="apartment"
                            value="{{ old('apartment') }}" />
                        @error('apartment')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Suburb & City -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="Checkout_form-control" placeholder="Suburb" name="suburb"
                                value="{{ old('suburb', $user_address->suburb ?? '') }}" required />
                            @error('suburb')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-sm-0">
                            <input type="text" class="Checkout_form-control" placeholder="City" name="city"
                                value="{{ old('city', $user_address->city ?? '') }}" required />
                            @error('city')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- ZIP & State -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="Checkout_form-control" placeholder="ZIP / Post Code"
                                name="post_code" value="{{ old('post_code', $user_address->post_code ?? '') }}"
                                required />
                            @error('post_code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-sm-0">
                            <input type="text" class="Checkout_form-control" placeholder="State" name="state"
                                value="{{ old('state', $user_address->state ?? '') }}" required />
                            @error('state')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone & Email -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="tel" class="Checkout_form-control" placeholder="Phone" name="phone"
                                value="{{ old('phone', $user_address->phone ?? '') }}" required />
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-sm-0">
                            <input type="email" class="Checkout_form-control" placeholder="Email" name="email"
                                value="{{ old('email', $user_address->email ?? '') }}" required />
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="col-12 col-lg-6 col-xxl-4 mt-4 mt-sm-0">
                    <h2 class="Biling_section-title">ADDITIONAL INFORMATION</h2>
                    <textarea class="Checkout_form-control" rows="5"
                        placeholder="Notes about your order, e.g. special notes for delivery." name="order_notes">{{ old('order_notes') }}</textarea>
                    @error('order_notes')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <hr>
            <!-- Delivery Method Section -->
            <div class="delivery-method-section my-4">
                <h2 class="Biling_section-title">DELIVERY METHOD</h2>
                <div class="d-flex gap-5">
                    <div class="radio-container">
                        <input type="radio" id="home_delivery" name="delivery_method" value="Home Delivery" checked />
                        <label for="home_delivery">Home Delivery</label>
                    </div>
                    <div class="radio-container">
                        <input type="radio" id="pickup" name="delivery_method" value="Pickup" />
                        <label for="pickup">Pickup</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Section 2 End -->

        <!-- ========== Section 3: Order Summary & Payment (Stripe Only) ========== -->
        <section>
            <div class="container py-5">
                <div class="row">
                    <!-- Order Summary -->
                    <div class="col-12 col-lg-6 col-xxl-8">
                        <h2 class="mb-4 Biling_section-title">YOUR ORDER</h2>
                        <table class="table table-borderless">
                            <thead>
                                <tr class="Checkout_Sec3_Table_Headings">
                                    <th class="Checkout_Sec3_Table_Headings_th">PRODUCT</th>
                                    <th class="text-end Checkout_Sec3_Table_Headings_th">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="Checkout_Sec3_Table_Body">
                                @foreach ($cartData as $item)
                                    @php
                                        $is_weight = $item['is_weight'];
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ strlen($item['title']) > 24 ? substr($item['title'], 0, 24) . '...' : $item['title'] }}
                                            <strong>x {{ $item['weight'] }}
                                                {{ $is_weight == 1 ? '(KG)' : '(Item)' }}</strong>
                                        </td>
                                        <td class="text-end">
                                            ${{ number_format($item['amount'] * $item['quantity'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end">
                                        ${{ number_format($subTotal, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Shipping Charges</td>
                                    <td class="text-end">
                                        $<span
                                            id="shipping-charges">{{ number_format(session('deliveryCharge', 0), 2) }}</span>
                                    </td>
                                </tr>

                                <tr>
                                    @if ($gstPercentage > 0)
                                        <td>Total GST ({{ $gstPercentage }}%) </td>
                                    @else
                                        <td>Total GST</td>
                                    @endif
                                    <td class="text-end">
                                       


                                        $<span id="total-gst"> {{ number_format(session('totalGst'), 2) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td class="text-end">
                                        @php
                                            $grandTotal =
                                                $subTotal + session('totalGst', 0) + session('deliveryCharge', 0);
                                        @endphp
                                        ${{ number_format($grandTotal, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Payment Method (Stripe Only) -->
                    <div class="col-12 col-lg-6 col-xxl-4">
                        <div class="bg-light-beige mt-5 p-4">
                            <!-- Just a heading for Stripe, no radio buttons -->
                            <h3 class="payment-title">Payment Details</h3>
                            <p class="text-muted Checkout_Payment_Detail_p">
                                Pay securely with your credit card via Stripe.
                            </p>

                            <!-- Stripe Inputs -->
                            <div class="mt-4" id="stripe-fields-container">
                                <label for="card-number-element" class="mb-2" style="font-weight: 600;">
                                    Card information
                                    <!-- Example brand icons (purely cosmetic) -->
                                    <img src="https://img.icons8.com/color/48/visa.png" alt="Visa"
                                        style="height: 24px; margin-left: 5px;" />
                                    <img src="https://img.icons8.com/color/48/mastercard.png" alt="Mastercard"
                                        style="height: 24px;" />
                                    <img src="https://img.icons8.com/color/48/amex.png" alt="Amex"
                                        style="height: 24px;" />
                                    <img src="https://img.icons8.com/color/48/discover.png" alt="Discover"
                                        style="height: 24px;" />
                                </label>

                                <!-- CARD NUMBER -->
                                <div id="card-number-element" class="form-control mb-3"
                                    style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;"></div>

                                <div class="d-flex gap-3">
                                    <!-- EXPIRY -->
                                    <div class="flex-fill">
                                        <div id="card-expiry-element" class="form-control"
                                            style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;"></div>
                                    </div>
                                    <!-- CVC -->
                                    <div class="flex-fill">
                                        <div id="card-cvc-element" class="form-control"
                                            style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;"></div>
                                    </div>
                                </div>

                                <div id="card-errors" style="color: red; margin-top: 8px;"></div>
                            </div>

                            <!-- Privacy Policy (optional text) -->
                            <p class="text-muted mt-3 Ckeckout_Payment_Detail_p">
                                Your personal data will be used to process your order and support
                                your experience on this website.
                            </p>

                            <!-- Submit Button -->
                            <button id="submitOrder" class="btn btn-danger w-50 add-to-cart-btn" type="submit">
                                PLACE YOUR ORDER
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs for Stripe PaymentMethod -->
                <input type="hidden" name="stripePaymentMethodId" id="stripePaymentMethodId">
                <input type="hidden" name="payment_intent_id" id="payment_intent_id" />
            </div>
        </section>
    </form>
    <!-- Main Form End -->
@endsection

@push('styles')
    <style>
        /* Transition for showing/hiding Stripe fields */
        #stripe-fields-container {
            transition: all 0.3s ease-in-out;
        }

        /* Optional: highlight border on focus (Stripe automatically adds these classes) */
        .StripeElement--focus {
            border-color: #007bff !important;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5) !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Toggle coupon form -->
    <script>
        document.getElementById('toggleCoupon').addEventListener('click', function(e) {
            e.preventDefault();
            let couponForm = document.getElementById('couponForm');
            couponForm.style.display =
                (couponForm.style.display === 'none' || couponForm.style.display === '') ?
                'block' :
                'none';
        });
    </script>

    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize Stripe
            console.log('{{ $stripe_key }}', '////////////////////////////////');
            const stripe = Stripe('{{ $stripe_key }}');
            const elements = stripe.elements();

            // Base styling
            const elementStyles = {
                base: {
                    fontSize: "16px",
                    color: "#32325d",
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    "::placeholder": {
                        color: "#aab7c4",
                    },
                },
                invalid: {
                    color: "#fa755a",
                    iconColor: "#fa755a",
                },
            };

            // Create three separate Elements
            const cardNumberElement = elements.create("cardNumber", {
                style: elementStyles
            });
            const cardExpiryElement = elements.create("cardExpiry", {
                style: elementStyles
            });
            const cardCvcElement = elements.create("cardCvc", {
                style: elementStyles
            });

            // Mount each Element into its container
            cardNumberElement.mount("#card-number-element");
            cardExpiryElement.mount("#card-expiry-element");
            cardCvcElement.mount("#card-cvc-element");

            // Handle validation errors
            const cardErrors = document.getElementById("card-errors");
            [cardNumberElement, cardExpiryElement, cardCvcElement].forEach((el) => {
                el.on("change", (event) => {
                    if (event.error) {
                        cardErrors.textContent = event.error.message;
                    } else {
                        cardErrors.textContent = "";
                    }
                });
            });

            // Delivery method radio buttons logic
            const pickupRadio = document.getElementById("pickup");
            const homeDeliveryRadio = document.getElementById("home_delivery");

            // Get the elements for displaying totals
            const shippingChargesElement = document.getElementById("shipping-charges");
            const totalElement = document.querySelector(
                "tbody.Checkout_Sec3_Table_Body tr:last-child td:last-child");

            // Store the base total (without shipping)
            const subTotal = parseFloat('{{ $subTotal }}');
            const totalGst = parseFloat('{{ session('totalGst') }}');
            const gstPercentage = parseFloat('{{ $gstPercentage }}');
            const baseTotal = subTotal + totalGst;

            // Function to update the delivery charges and total
            function updateDeliveryCharges() {
                const deliveryChargeInput = document.getElementById("delivery_charges");
                const totalGstElement = document.getElementById("total-gst");
                let deliveryCharge = 0;
                let gstOnShipping = 0;

                if (pickupRadio.checked) {
                    deliveryCharge = 0; // No delivery charges for pickup
                    gstOnShipping = 0;
                } else if (homeDeliveryRadio.checked) {
                    // Set home delivery charge
                    deliveryCharge = parseFloat("{{ session('deliveryCharge') ?? 10 }}");
                    // Calculate GST on shipping charges (hidden calculation)
                    gstOnShipping = (deliveryCharge * gstPercentage) / 100;
                }

                // Update hidden input value
                deliveryChargeInput.value = deliveryCharge;

                // Update GST on shipping hidden field
                const gstOnShippingInput = document.getElementById("gst_on_shipping");
                if (gstOnShippingInput) {
                    gstOnShippingInput.value = gstOnShipping.toFixed(2);
                }

                // Update total GST (product GST + shipping GST) - this includes GST on shipping
                const totalGstAmount = totalGst + gstOnShipping;
                if (totalGstElement) {
                    totalGstElement.textContent = totalGstAmount.toFixed(2);
                }

                const totalGstInput = document.getElementById("totalGst");
                if (totalGstInput) {
                    totalGstInput.value = totalGstAmount.toFixed(2);
                }

                // Update grand total hidden input
                const grandTotalInput = document.querySelector('input[name="grand_total"]');
                const newGrandTotal = baseTotal + deliveryCharge + gstOnShipping;
                if (grandTotalInput) {
                    grandTotalInput.value = newGrandTotal.toFixed(2);
                }

                // Update shipping display
                shippingChargesElement.textContent = deliveryCharge.toFixed(2);

                // Update total display
                totalElement.innerHTML = '$' + newGrandTotal.toFixed(2);
            }

            // Listen for changes on the radio buttons
            pickupRadio.addEventListener("change", updateDeliveryCharges);
            homeDeliveryRadio.addEventListener("change", updateDeliveryCharges);

            // Initial update on page load
            updateDeliveryCharges();

            // Form submission logic
            const form = document.getElementById("contactForm");
            const submitButton = document.getElementById("submitOrder");

            form.addEventListener("submit", async (event) => {
                event.preventDefault(); // Prevent normal form submission
                submitButton.disabled = true;

                // Check if we already have a payment method ID (in case of form resubmission)
                const existingPaymentMethodId = document.getElementById("stripePaymentMethodId").value;
                if (existingPaymentMethodId) {
                    console.log('Using existing PaymentMethod ID:', existingPaymentMethodId);
                    form.submit();
                    return;
                }

                // Validate required fields before creating payment method
                const firstName = document.querySelector('input[name="f_name"]').value;
                const lastName = document.querySelector('input[name="l_name"]').value;
                const email = document.querySelector('input[name="email"]').value;

                if (!firstName || !lastName || !email) {
                    cardErrors.textContent = "Please fill in all required billing details.";
                    submitButton.disabled = false;
                    return;
                }

                // Validate that Stripe elements are properly loaded
                if (!cardNumberElement || !cardExpiryElement || !cardCvcElement) {
                    cardErrors.textContent =
                        "Payment form is not properly loaded. Please refresh the page and try again.";
                    submitButton.disabled = false;
                    return;
                }

                // Create Stripe PaymentMethod
                const {
                    paymentMethod,
                    error
                } = await stripe.createPaymentMethod({
                    type: "card",
                    card: cardNumberElement,
                    billing_details: {
                        name: firstName + ' ' + lastName,
                        email: email,
                    },
                });

                if (error) {
                    console.error('Stripe PaymentMethod error:', error);
                    cardErrors.textContent = error.message;
                    submitButton.disabled = false;
                } else {
                    console.log('PaymentMethod created successfully:', paymentMethod.id);

                    // Store PaymentMethod in hidden field
                    const stripePaymentMethodIdInput = document.getElementById("stripePaymentMethodId");
                    const paymentIntentIdInput = document.getElementById("payment_intent_id");

                    if (stripePaymentMethodIdInput) {
                        stripePaymentMethodIdInput.value = paymentMethod.id;
                        console.log('Stripe PaymentMethod ID set:', paymentMethod.id);
                    } else {
                        console.error('stripePaymentMethodId input not found');
                    }

                    if (paymentIntentIdInput) {
                        paymentIntentIdInput.value = paymentMethod.id;
                        console.log('Payment Intent ID set:', paymentMethod.id);
                    } else {
                        console.error('payment_intent_id input not found');
                    }

                    // Submit the form now that we have a valid PaymentMethod
                    console.log('Submitting form with PaymentMethod ID:', paymentMethod.id);

                    // Double-check that the hidden fields are set
                    const finalStripePaymentMethodId = document.getElementById("stripePaymentMethodId");
                    const finalPaymentIntentId = document.getElementById("payment_intent_id");

                    console.log('Final stripePaymentMethodId value:', finalStripePaymentMethodId ?
                        finalStripePaymentMethodId.value : 'NOT FOUND');
                    console.log('Final payment_intent_id value:', finalPaymentIntentId ?
                        finalPaymentIntentId.value : 'NOT FOUND');

                    // Small delay to ensure values are set
                    setTimeout(() => {
                        form.submit();
                    }, 100);
                }
            });
        });
    </script>
    <style>
        .radio-container {
            display: flex;
            align-items: center;
            gap: 8px;
            /* Adjust spacing between radio and label */
        }

        input[type="radio"] {
            width: 20px;
            height: 20px;
            accent-color: #A42125;
        }
    </style>
@endpush
