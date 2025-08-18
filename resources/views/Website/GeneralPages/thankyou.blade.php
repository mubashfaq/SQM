@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')
    <section class="about-sec1">
        <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
        <div class="container">
            <div class="text-center">
                <h2>Thank You</h2>
                <p><span>Sydney Quality Meats:</span>Thank You</p>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="thank-you py-5">
            <div class="thank-you__message">
                <p class="thank-you__text">Thank you. Your order has been received.</p>

                <div class="thank-you__details">
                    <div class="thank-you__detail-row">
                        <span class="thank-you__label">Invoice number:</span>
                        <span class="thank-you__value">{{ $order->invoice_number }}</span>
                    </div>
                    <div class="thank-you__detail-row">
                        <span class="thank-you__label">Date:</span>
                        <span class="thank-you__value"> {{ $order->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="thank-you__detail-row">
                        <span class="thank-you__label">Total:</span>
                        <span class="thank-you__value">${{ number_format($order->grand_total, 2) }}</span>
                    </div>
                    <div class="thank-you__detail-row">
                        <span class="thank-you__label">Payment Method:</span>
                        <span class="thank-you__value">{{ $order->payment_method }}</span>
                    </div>
                    <div class="thank-you__detail-row">
                        <span class="thank-you__label">Delivery Method:</span>
                        <span class="thank-you__value">{{ $order->delivery_method }}</span>
                    </div>
                </div>
            </div>

            <div class="thank-you__order-details">
                <h2 class="thank-you__title">ORDER DETAILS</h2>

                <table class="thank-you__table">
                    <thead>
                        <tr>
                            <th>PRODUCT</th>
                            <th>TOTAL</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderMeta as $item)
                        @php
                            $is_weightMeta = $item->product->productMeta
                            ->where('option', '_is_weight')
                            ->first();
                            $is_weight=$is_weightMeta->value;
                        @endphp
                            <tr>
                                <td>{{ $item->product_title }}<span>× {{ $item->quantity }} {{  ($is_weight==1 ? '( Kg )' : '( Item )')}}</span></td>
                                <td>${{ number_format($item->qp_subtotal, 2) }}</td>
                               
                            </tr>
                        @endforeach

                        <tr class="thank-you__table-footer">
                            <td>Subtotal:</td>
                            <td> ${{ number_format($order->subTotal, 2) }}</td>
                        </tr>
                        <tr class="thank-you__table-payment">
                            <td>Shipping:</td>
                            <td>${{ number_format($order->delivery_charges, 2) }}</td>
                        </tr>
                        <tr class="thank-you__table-payment">
                            <td>GST:</td>
                            <td>${{ number_format($order->totalGst, 2) }}</td>
                        </tr>
                        <tr class="thank-you__table-footer">
                            <td>Total:</td>
                            <td>${{ number_format($order->grand_total, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
