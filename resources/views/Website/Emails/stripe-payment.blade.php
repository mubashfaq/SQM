<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Confirmation</title>
    <style>
        .flexClassBetween {
            width: 100% !important;
            display: flex !important;
            justify-content: space-between !important;
        }

        .width70 {
            width: 70% !important;
        }

        .width30 {
            width: 30% !important;
        }

        .width100 {
            width: 100% !important;
        }
    </style>
</head>

<body
    style="
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    ">
    <table align="center" cellpadding="0" cellspacing="0" width="600"
        style="
        border-collapse: collapse;
        margin: 30px auto;
        background-color: #ffffff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.863);
      ">
        <tr>
            <td
                style="
            padding: 20px;
            text-align: center;
            background-color: #E59041;
            color: #fff;
          ">
                <img src="{{ $url . '/' . $logo }}" alt="Sydney Quality Meat" style="width: 35%;">
                <h1 style="font-size: 28px; color: white;">Order Confirmation</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px">
                <span class="name"
                    style="display: block; color: black; text-align: center; font-weight: 600; font-size: 1.4rem; margin-bottom: 4px;">
                    @if (isset($name) && !empty($name))
                        Dear, {{ $name }}
                    @endif
                </span>
                <p style="font-size: 15px; color: slategrey; margin-top: 15px; text-align: start; line-height: 25px;">
                    Thank you for placing an order with us. Your order details are as
                    follows:
                </p>
                <p>Invoice Number: #{{ $orderAddress->invoice_number }}</p>
                <div
                    style="margin-bottom: 2rem; border: 2px dashed gray; width: 100% !important; display: flex; justify-content: space-between;">
                    <div style="padding: 1rem; width: 48% !important;">
                        <div style="font-size: 20px; margin-bottom: 1rem; color: #E59041 !important;"
                            class="theme-color">Billing Details</div>
                        <div style="font-size: 12px;">Person Name:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">
                            {{ $orderAddress->orderAddressDetail->f_name ?? $orderAddress->f_name }} &nbsp;
                            {{ $orderAddress->orderAddressDetail->l_name ?? $orderAddress->l_name }}
                        </div>
                        <div style="font-size: 12px;">Email:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">
                            {{ $orderAddress->orderAddressDetail->email ?? $orderAddress->email }}</div>
                        <div style="font-size: 12px;">Phone:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">
                            {{ $orderAddress->orderAddressDetail->phone ?? $orderAddress->phone }}</div>
                        <div style="font-size: 12px;">Billing Address:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">
                            {{ $orderAddress->orderAddressDetail->street_address ?? $orderAddress->street_address }}
                        </div>
                    </div>
                    <div style="padding: 1rem; width: 48% !important;">
                        <div style="font-size: 20px; margin-bottom: 1rem; color: #E59041 !important;"
                            class="theme-color">Shipping Details</div>
                        <div style="font-size: 12px;">Receiver Name:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">
                            {{ $orderAddress->f_name }}&nbsp;{{ $orderAddress->l_name }}</div>
                        <div style="font-size: 12px;">Email:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">{{ $orderAddress->email }}</div>
                        <div style="font-size: 12px;">Phone:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">{{ $orderAddress->phone }}</div>
                        <div style="font-size: 12px;">Address:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">{{ $orderAddress->street_address }}</div>
                    </div>
                </div>
                <table style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 8px;">Product</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Price</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Quantity</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px;display:flex !important;">
                                    <img src="{{ $product['featuredImage'] }}" alt="Product Image"
                                        style="width: 50px; height: 50px; display: inline-block; vertical-align: middle; margin-right: 10px;" />
                                    <div style="display: inline-block; vertical-align: middle;">
                                        <div style="font-weight: bold;">{{ $product['title'] }}</div>
                                    </div>
                                </td>
                                <td style="border: 1px solid #ddd; padding: 8px;">${{ $product['amount'] }}</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">{{ $product['quantity'] }}</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">
                                    ${{ $product['amount'] * $product['quantity'] }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 10px;">
                    <div style="float: left; width: 50%;">
                        <h5>SubTotal:</h5>
                    </div>
                    <div style="float: right; width: 50%; text-align: right;">
                        <p>${{ $totalAmount }}</p>
                    </div>
                    <div style="clear: both;"></div>
                    <div style="float: left; width: 50%;">
                        <h5>Total Amount:</h5>
                    </div>
                    <div style="float: right; width: 50%; text-align: right;">
                        <p>${{ $totalAmount }}</p>
                    </div>
                    <div style="clear: both;"></div>
                    <div style="float: left; width: 50%;">
                        <h5>Registration Discount:</h5>
                    </div>
                    <div style="float: right; width: 50%; text-align: right;">
                        <p>${{ $auth_discount }}</p>
                    </div>
                    <div style="clear: both;"></div>
                    <div style="float: left; width: 50%;">
                        <h5>Delivery Charges:</h5>
                    </div>
                    <div style="float: right; width: 50%; text-align: right;">
                        <p>${{ $delivery_fee }}</p>
                    </div>
                    <div style="clear: both;"></div>
                    <div style="float: left; width: 50%;">
                        <h5>Grand Total:</h5>
                    </div>
                    <div style="float: right; width: 50%; text-align: right;">
                        <p>${{ $grand_total }}</p>
                    </div>
                    <div style="clear: both;"></div>
                </div>
                <p>
                    Your items will be shipped to you as soon as possible. You can track
                    the status of your order using the invoice number by <a href="{{ $url }}/track-my-order"
                        target="_blank"> clicking here </a>.
                </p>
                <p style="font-size: 15px; color: slategrey; margin-top: 15px; text-align: start; line-height: 25px;">
                    Thank you for choosing us for your shopping needs. We look forward
                    to serving you again in the future.
                    <br><br>
                    If you have any questions, kindly reach out to our team on &nbsp;<a
                        href="mailto:{{ $admin_email }}"><b>{{ $admin_email }}</b></a>
                    <br><br>
                    Have an AWESOME day!
                </p>
                <div style="text-align: center; padding: 2rem;">
                    <p><a href="{{ $url }}" class="button"
                            style="padding: 10px 25px; font: inherit; background: linear-gradient(to right, #E59041, #E59041); border-radius: 50px; border: 0; color: #fff; margin: auto; box-shadow: 0 5px 10px rgba(0, 0, 0, .1); text-decoration: none;">
                            Explore
                        </a>
                    </p>
                </div>
                <div class="footor-social-icons-section">
                    <p style="text-align: center;">FOLLOW US ON</p>
                    <div style="display: flex; justify-content: center;">
                        <a href="{{ $instagram_link }}" style="margin: 0 15px;" target="_blank"
                            class="textdecor-anker">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-instagram"
                                    style="color: #E59041; height: 30px; width: 30px;" viewBox="0 0 16 16">
                                    <path
                                        d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
                                </svg>
                            </span>
                        </a>
                        <a href="{{ $facebook_link }}" style="margin: 0 15px;" target="_blank" class="textdecor-anker">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-facebook"
                                    style="color: #E59041; height: 30px; width: 30px;" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                </svg>
                            </span>
                        </a>
                        <a href="{{ $twitter_link }}" style="margin: 0 15px;" target="_blank"
                            class="textdecor-anker">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-twitter"
                                    style="color: #E59041; height: 30px; width: 30px;" viewBox="0 0 16 16">
                                    <path
                                        d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                                </svg>
                            </span>
                        </a>
                        <a href="{{ $youtube_link }}" style="margin: 0 15px;" target="_blank"
                            class="textdecor-anker">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-youtube"
                                    style="color: #E59041; height: 30px; width: 30px;" viewBox="0 0 16 16">
                                    <path
                                        d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z" />
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td
                style="
            padding: 20px;
            background-color: #E59041;
            color: #fff;
            text-align: center;
          ">
                <p>&copy; 2025 {{ config('app.website_name') }}. All Rights Reserved.</p>
            </td>
        </tr>
    </table>
</body>

</html>
