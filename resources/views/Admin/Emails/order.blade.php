<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Status Update</title>
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
                @php
                    $logoUrl = asset('logos/1696498070.png');
                @endphp
                <img src="{{$logoUrl}}" alt="Sydney Quality Meat" style="width: 10%;">
                <h1 style="font-size: 28px; color: white;">Order Status Update</h1>
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

                    @if($status == "completed")
                    <p style="color: green;">We are delighted to inform you that your order no. {{$orderdetail->invoice_number}} has been successfully completed and processed. Your satisfaction is our top priority, and we appreciate your trust in Sydney Quality Meat</p>
                    @else
                    <p style="color: red;">We are sorry to inform you that your order no. {{$orderdetail->invoice_number}} has been cancelled.</p>
                    @endif

                <p>Invoice Number: #{{ $orderdetail->invoice_number }}</p>
                <div
                    style="margin-bottom: 2rem; border: 2px dashed gray; width: 100% !important; display: flex; justify-content: space-between;">
                    <div style="padding: 1rem; width: 48% !important;">
                        <div style="font-size: 20px; margin-bottom: 1rem; color: #E59041 !important;" class="theme-color">Billing Details</div>
                        <div style="font-size: 12px;">Person Name:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">
                            {{ $orderdetail->orderAddressDetail->f_name ?? $orderdetail->f_name }} &nbsp;
                            {{ $orderdetail->orderAddressDetail->l_name ?? $orderdetail->l_name }}
                        </div>
                        <div style="font-size: 12px;">Email:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">
                            {{ $orderdetail->orderAddressDetail->email ?? $orderdetail->email }}</div>
                        <div style="font-size: 12px;">Phone:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">
                            {{ $orderdetail->orderAddressDetail->phone ?? $orderdetail->phone }}</div>
                        <div style="font-size: 12px;">Billing Address:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">
                            {{ $orderdetail->orderAddressDetail->street_address ?? $orderdetail->street_address }}
                        </div>
                    </div>
                    <div style="padding: 1rem; width: 48% !important;">
                        <div style="font-size: 20px; margin-bottom: 1rem; color: #E59041 !important;" class="theme-color">Shipping Details</div>
                        <div style="font-size: 12px;">Receiver Name:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">
                            {{ $orderdetail->f_name }}&nbsp;{{ $orderdetail->l_name }}</div>
                        <div style="font-size: 12px;">Email:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">{{ $orderdetail->email }}</div>
                        <div style="font-size: 12px;">Phone:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">{{ $orderdetail->phone }}</div>
                        <div style="font-size: 12px;">Address:</div>
                        <div style="font-size: 20px; margin-bottom: 1.5rem;">{{ $orderdetail->street_address }}</div>
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
                        @foreach ($orderMeta as $product)
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px;display:flex !important;">
                                    <img src="{{$product?->product?->featured_image}}" alt="Product Image"
                                        style="width: 50px; height: 50px; display: inline-block; vertical-align: middle; margin-right: 10px;" />
                                    <div style="display: inline-block; vertical-align: middle;">
                                        <div style="font-weight: bold;">{{ $product?->product_title ?? "" }}</div>
                                    </div>
                                </td>
                                <td style="border: 1px solid #ddd; padding: 8px;">${{ $product->price ?? "" }}</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">{{ $product->quantity ?? ""}}</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">
                                    ${{ $product->qp_subtotal ?? "" }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 10px;">
                    <div style="float: left; width: 50%;">
                        <h5>SubTotal:</h5>
                    </div>
                    <div style="float: right; width: 50%; text-align: right;">
                        <p>${{ $orderdetail->subTotal ?? "" }}</p>
                    </div>
                    <div style="clear: both;"></div>
                    <div style="float: left; width: 50%;">
                        <h5>Total Amount:</h5>
                    </div>
                    <div style="float: right; width: 50%; text-align: right;">
                        <p>${{ $orderdetail->total_amount ?? "" }}</p>
                    </div>
                    <div style="clear: both;"></div>
                </div>
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
                    <a href="{{ $url }}" target="_blank"
                        style="text-decoration: none; cursor: pointer !important;">
                        <button class="explore"
                            style="padding: 10px 25px; font: inherit; background: linear-gradient(to right, #E59041, #E59041); border-radius: 50px; border: 0; color: #fff; margin: auto; display: inline-block; transition: all .2s ease-in-out; cursor: pointer; box-shadow: 0 5px 10px rgba(0, 0, 0, .1);">Explore</button>
                    </a>
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
