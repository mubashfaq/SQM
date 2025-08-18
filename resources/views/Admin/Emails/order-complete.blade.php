<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordered Complete</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: "Montserrat", sans-serif;
        }

        .email-container {
            width: 600px;
            margin: auto;
            box-shadow: 1px 1px 10px #00000033 !important;
            padding: 30px;
            border-radius: 10px !important;
        }

        .product-detail-tbody tr:last-child td {
            border-bottom: 1px solid #000;
        }
    </style>
</head>

<body>
    <div class="email-container">

        <div style="text-align: center;">
            <img src="https://sydneyqualitymeats.com.au/WebsiteAssets/assets/img/logo-dark.png" alt="Website Logo"
                width="35%">
            @if ($order->status == 'completed')
                <h2 style="margin-top: 10px; font-weight: 700; font-size: 26px; color: white; background-color: #A42125;">
                    Order Completed</h2>
            @elseif ($order->status == 'cancelled')
                <h2 style="margin-top: 10px; font-weight: 700; font-size: 26px; color: white; background-color: #A42125;">
                    Your Order has been Cancelled</h2>
            @elseif ($order->status == 'inProcess')
                <h2 style="margin-top: 10px; font-weight: 700; font-size: 26px; color: white; background-color: #A42125;">
                    Your Order Is Being Processed</h2>
            @else
                <h2 style="margin-top: 10px; font-weight: 700; font-size: 26px; color: white; background-color: #A42125;">
                    Your Order has been Placed</h2>
            @endif
        </div>

        <table width="100%" style="border-collapse: collapse;margin-top: 20px;">
            <tr>
                <td width="50%"
                    style="border: 1px solid #e2e2e2; padding: 10px 20px;vertical-align: top;background: #f8f8f8;">
                    <h3 style="font-size: 16px;color: #A42125;font-weight: 600;">Summary</h3>
                    <p style="font-size: 14px;font-weight:700;margin: 10px 0px 5px;display: flex;">
                        Order No.: <span style="font-weight:500;margin-left: auto;">{{ $order->id }}</span></p>
                    <p style="font-size: 14px;color: black; font-weight:700;margin-bottom: 5px;display: flex;">
                        Order Date: <span
                            style="font-weight:500;margin-left: auto; color: black;">{{ $order->created_at->format('d/m/Y') }}</span>
                    </p>
                    <p style="font-size: 14px;font-weight:700;color:black; display: flex; margin-bottom: 5px">
                        Order Total: <span
                            style="font-weight:500;margin-left: auto;color: black;">${{ number_format($order->grand_total, 2) }}</span>
                    </p>
                    @if(isset($status))
                                        @php
                                            $statusColor = '';
                                            switch (strtolower($status)) {
                                                case 'inprocess':
                                                    $statusColor = 'background-color: #f0ad4e; color: white;'; // Yellow-ish color for InProcess
                                                    $status = "In Process";
                                                    break;
                                                case 'completed':
                                                    $statusColor = 'background-color: #5cb85c; color: white;'; // Green color for Completed
                                                    $status = "Completed";
                                                    break;
                                                case 'cancelled':
                                                    $statusColor = 'background-color: #d9534f; color: white;'; // Red color for Cancelled
                                                    $status = "Cancelled";
                                                    break;
                                                case 'pending':
                                                    $statusColor = 'background-color:rgb(44, 87, 180); color: white;'; // Red color for Cancelled
                                                    $status = "Pending";
                                                    break;
                                            }
                                        @endphp
                                        <p style="font-size: 14px;font-weight: 700; display: flex; align-items: center;">
                                            Order Status:
                                            <span
                                                style="font-weight: 500; margin-left: auto; padding: 5px 10px; text-transform: capitalize; border-radius: 5px; {{ $statusColor }}">
                                                {{ $status }}
                                            </span>
                                        </p>
                    @endif

                </td>
                <td width="50%"
                    style="border: 1px solid #e2e2e2; padding: 10px 20px;vertical-align: top;background: #f8f8f8;">
                    <h3 style="font-size: 16px;color: #A42125;font-weight: 600;">Shipping Address</h3>
                    <p style="text-transform: capitalize;font-size: 14px;font-weight:600;margin:10px 0px 3px;">Street
                        Address: <span style="font-weight:500;margin-left: auto;">{{ $order->street_address }}</span>
                    </p>
                    <p style="text-transform: capitalize;font-size: 14px;font-weight:600;margin-bottom: 3px;">City:
                        <span style="font-weight:500;margin-left: auto;">{{ $order->city }}</span></p>
                    <p style="text-transform: capitalize;font-size: 14px;font-weight:600;margin-bottom: 3px;"> State:
                        <span style="font-weight:500;margin-left: auto;">{{ $order->state }}</span></p>
                    <p style="text-transform: capitalize;font-size: 14px;font-weight:600;margin-bottom: 3px;"> Post
                        Code: <span style="font-weight:500;margin-left: auto;">{{ $order->post_code }}</span></p>
                </td>
            </tr>
        </table>
        <table width="100%" style="margin-top: 20px;border-collapse: collapse;">
            <thead>
                <tr>
                    <th width="35%" style="text-align: start;font-size: 16px;font-weight: 600;padding-bottom: 10px;">
                        Item
                    </th>
                    <th width="25%" style="text-align: start;font-size: 16px;font-weight: 600;padding-bottom: 10px;">
                        Ordered Value
                    </th>
                    <th width="25%" style="text-align: start;font-size: 16px;font-weight: 600;padding-bottom: 10px;">
                        Delivered Value
                    </th>
                    <th width="15%" style="text-align: start;font-size: 16px;font-weight: 600;padding-bottom: 10px;">
                        Price
                    </th>
                </tr>
            </thead>
            <tbody class="product-detail-tbody">
                @foreach ($order->orderMeta as $product)
                @php
                $is_weightMeta = $product->product->productMeta->firstWhere('option', '_is_weight');
                $is_weight = $is_weightMeta ? $is_weightMeta->value : '';
                @endphp

                    <tr>
                        <td width="35%" style="padding: 10px 0px;border-top: 1px solid #000;">
                            <div style="display: flex;align-items: center !important;">
                                <img src="{{ url(' $product->product->featured_image ')}}" alt=""
                                    width="20%" style="object-fit: contain;">
                                <h3 style="margin:auto 20px;font-size: 14px;font-weight: 500;">{{ $product->product_title }}
                                </h3>
                            </div>
                        </td>
                        <td width="25%"
                            style="padding: 10px 0px;font-size: 16px;font-weight: 500;border-top: 1px solid #000;padding-left: 10px;">
                            {{ $product->quantity }} {{$is_weight==1?'(Kg)':'(Item)' }}
                        </td>
                        <td width="25%"
                            style="padding: 10px 0px;font-size: 16px;font-weight: 500;border-top: 1px solid #000;padding-left: 10px;">
                            {{ $product->delivered_weight }} {{$is_weight==1?'(Kg)':'(Item)' }}
                        </td>
                        <td width="15%"
                            style="padding: 10px 0px;font-size: 16px;font-weight: 500;border-top: 1px solid #000;">
                            ${{ number_format($product->qp_subtotal, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="width: 250px;margin-left: auto;margin-top: 20px;">
            <p style="font-size: 16px; color: black; font-weight:700;margin-bottom: 8px;display: flex;">
                Subtotal: <span
                    style=" color: black; font-weight:500;margin-left: auto;">${{ number_format($order->subTotal, 2) }}</span>
            </p>
            <p style="font-size: 16px; color: black; font-weight:700;margin-bottom: 8px;display: flex;">
                Shipping: <span
                    style="color: black; font-weight:500;margin-left: auto;">${{ number_format($order->delivery_charges, 2) }}</span>
            </p>
            <!-- <p style="font-size: 16px; color: black; font-weight:700;margin-bottom: 8px;display: flex;">
            Discount Amount: <span style=" color: black; font-weight:500;margin-left: auto;">${{ number_format($order->discountedAmount, 2) }}</span></p> -->
            <p style="font-size: 16px; color: black; font-weight:700;margin-bottom: 8px;display: flex;">
                Total GST: <span
                    style=" color: black; font-weight:500;margin-left: auto;">${{ number_format($order->totalGst, 2) }}</span>
            </p>
            <p style="font-size: 16px; color: black; font-weight:700;display: flex;">
                Total: <span
                    style=" color: black; font-weight:500;margin-left: auto;">${{ number_format($order->delivered_price, 2) }}</span>
            </p>
        </div>
        <div style="padding: 15px;background-color: #A42125;margin-top: 20px;">
            <p style="text-align: center; color: #fff;font-weight: 500;font-size: 16px;">© 2025 Sydney Quality Meats.
                All Rights Reserved.</p>
        </div>
    </div>
</body>

</html>