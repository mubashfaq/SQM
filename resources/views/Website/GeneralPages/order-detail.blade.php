@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')
    <!-- Login Section 1 -->
    <section class="about-sec1">
        <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
        <div class="container">
            <div class="text-center">
                <h2>Order Detail</h2>
                <p><span>Sydney Quality Meats:</span> Order Detail</p>
            </div>
        </div>
    </section>

    <!-- Login Section 2 -->
    <section class="Register-page">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="Register-page__wrap login-page__main-tab-box">
                        <div class="login-page__wrap__bg"
                            style="background-image: url('{{ asset('WebsiteAssets/assets/img/login-bg-1.png') }}');">
                        </div>

                        <div class="Order_History_Sec2">
                            <h2>Order Details</h2>

                            <!-- Invoice Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card_OH">
                                        <div class="card-body">
                                            <h5 class="card-title">Invoice Details</h5>
                                            <p><strong>Invoice</strong>: {{ $order->invoice_number }}</p>
                                            <p class="text-capitalize"><strong>Delivery Method</strong>:
                                                {{ $order->delivery_method }}</p>
                                            <p><strong>Order Status</strong>:
                                                <span
                                                    class="badge 
                                                    @if ($order->status == 'pending') bg-warning
                                                    @elseif($order->status == 'inProcess') bg-info
                                                    @elseif($order->status == 'completed') bg-success
                                                    @elseif($order->status == 'cancelled') bg-danger @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </p>
                                            <p><strong>Date</strong>: {{ $order->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer Details -->
                                <div class="col-md-6">
                                    <div class="card card_OH">
                                        <div class="card-body">
                                            <h5 class="card-title">Customer Details</h5>
                                            <p class="text-capitalize"><strong>Name</strong>:
                                                {{ $order->f_name . ' ' . $order->l_name }}</p>
                                            <p class=""><strong>Email</strong>: {{ $order->email }}</p>
                                            <p><strong>Phone</strong>: {{ $order->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Shipping Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card_OH">
                                        <div class="card-body">
                                            <h5 class="card-title text-capitalize">Shipping Details</h5>
                                            <p class="text-capitalize"><strong>Address</strong>:
                                                {{ $order->street_address . ',' . $order->state }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Details -->
                                <div class="col-md-6">
                                    <div class="card card_OH">

                                        <div class="card-body">
                                            <h5 class="card-title">Payment Details</h5>
                                            <p class="text-capitalize"><strong>Payment Method</strong>:
                                                {{ $order->payment_method }}</p>
                                            <p><strong>Payment Status</strong>:
                                                <span
                                                    class="badge 
                                                    @if ($order->payment_status == 'pending') bg-warning
                                                    @elseif($order->payment_status == 'inProcess') bg-info
                                                    @elseif($order->payment_status == 'completed') bg-success
                                                    @elseif($order->payment_status == 'cancelled') bg-danger @endif">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card_OH">
                                        <div class="card-body">
                                            <h5 class="card-title">Order Items</h5>
                                            <div class="table-responsive">
                                                <table class="table Order_History">
                                                    <thead>
                                                        <tr>
                                                            <th>Product Name</th>
                                                            <th>Price</th>
                                                            <th>Ordered value</th>
                                                            <th>Delivered value</th>
                                                            <th>Type</th>


                                                            <th>Total</th>
                                                        </tr>

                                                    </thead>
                                                    <tbody>
                                                        @foreach ($order->orderMeta as $item)
                                                            <tr>
                                                                <td class="text-capitalize">  {{ strlen( $item->product_title ) > 40 ? substr( $item->product_title , 0, 30) . '...' :  $item->product_title  }}</td>
                                                                @php
                                                                    $discount = $item->product->discountData;
                                                                     $is_weightMeta = $item->product->productMeta->firstWhere('option', '_is_weight');
                                                                     $is_weight = $is_weightMeta ? $is_weightMeta->value : '';
                                                                @endphp
                                                                @if ($discount)
                                                                    <td>${{ number_format($discount['discounted_price'], 2) }}
                                                                    </td>
                                                                @else
                                                                    <td>${{ number_format($item->price, 2) }}</td>
                                                                @endif
                                                                <td> {{ $item->quantity }} <span class="text-danger"></span></td>
                                                                <td>{{ $item->delivered_weight == null ? 0 : $item->delivered_weight }} 
                                                                </td>
                                                                <td >{{$is_weight ? 'Kg':'Item' }}</td>
                                                                <td>${{ number_format($item->qp_subtotal, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Amount -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card_OH">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Amount</h5>
                                            <p><strong>Subtotal</strong>: ${{ number_format($order->subTotal, 2) }}</p>
                                            <p><strong>Shipping</strong>: ${{ number_format($order->delivery_charges, 2) }}
                                            </p>
                                            <p><strong>GST</strong>: ${{ number_format($order->totalGst, 2) }}</p>
                                            @if ($order->status == 'completed')
                                                <p><strong>Total Amount</strong>:
                                                    ${{ number_format($order->delivered_price, 2) }}
                                                @else
                                                <p><strong>Total Amount</strong>:
                                                    ${{ number_format($order->grand_total, 2) }}
                                            @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
    </section>

    <!-- WOW.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
@endsection
