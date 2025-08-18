@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')

    <!-- Login Section 1 -->
    <section class="about-sec1">
        <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
        <div class="container">
            <div class="text-center">
                <h2>Order History</h2>
                <p><span>Sydney Quality Meats:</span> Order History</p>
            </div>
        </div>
    </section>

    <!-- Order History Section 2 -->

    <section class="Register-page">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="Register-page__wrap login-page__main-tab-box">
                    <div class="login-page__wrap__bg"
                        style="background-image: url('{{ asset('WebsiteAssets/assets/img/login-bg-1.png') }}');">
                    </div>

                    <div class="Order_History_Sec2">
                        <div class="d-flex justify-content-between Order_History_Sec2_title align-items-center mb-3">
                            <h2>Order History</h2>
                            <form action="" method="get">
                                <div class="input-group" style="max-width: 300px;">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search..." style="padding: 10px;">
                                    <button class="btn" type="submit" style="background: #a41e22; color: white;">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                    <a href="{{ url()->current() }}" class="btn btn-secondary outline_NOME" style="border-radius: 0px; margin-left: 6px; line-height: 30px;">Clear</a>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="Order_History table">
                                <thead>
                                    <tr>
                                        <th class="text-capitalize text-nowrap">Order ID</th>
                                        <th class="text-capitalize text-nowrap">Date</th>
                                        <th class="text-capitalize text-nowrap">Ordered Price</th>
                                        <th class="text-capitalize text-nowrap">Delivered Price</th>
                                        <th class="text-capitalize text-nowrap">Payment</th>
                                        <th class="text-capitalize text-nowrap">Payment Status</th>
                                        <th class="text-capitalize text-nowrap">Delivery Method</th>
                                        <th class="text-capitalize text-nowrap">Order Status</th>
                                        <th class="text-capitalize text-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->invoice_number }}</td>
                                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                            <td>${{ number_format($order->grand_total, 2) }}</td>
                                            <td>${{ number_format($order->delivered_price, 2) }}</td>
                                            <td class="text-capitalize">{{ $order->payment_method }}</td>
                                            <td class="text-capitalize">{{ $order->payment_status }}</td>
                                            <td class="text-capitalize">{{ $order->delivery_method }}</td>
                                            <td class="text-capitalize">{{ $order->status }}</td>
                                            <td>
                                                <button class="btn btn-danger add-to-cart-btn btn-view">
                                                    <a href="{{ url('/order-detail/' . $order->id) }}" style="text-decoration: none; color: white;">
                                                        View Order
                                                    </a>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination Links -->
                        <div class="pagination-wrapper">
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



    <style>
        .pagination-wrapper .pagination {
            justify-content: center;
        }

        .pagination .page-link {
            border-radius: 50%;
        }

        .pagination .page-item.active .page-link {
            background-color: #A42125;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            opacity: 0.75;
        }

        .pagination .page-link {
            color: #000;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }
    </style>



    <!-- WOW.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>




@endsection