@include('Website.Includes.header', ['meta_Details' => $meta_details])
@php
    use App\General\ProductClass;
@endphp
<!-- End Header -->
<section class="product-detail-section">
    <div class="container">
        <div class="row product-detail-p1-100">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="h1 text-center my-5 text-secondary">Product Not Found!</h1>
                    <div class="h2 text-center my-5 text-secondary">The product You are looking for is no more available
                        on the Website 🙂</h1>
                        <div class="product-detail-cart-btn">
                            <a class="btn btn-lg rounded-5 mt-5" style="background-color: #E59041; color:white;"
                                href="{{ url('/') }}">Back to Shop</a>
                        </div>
                    </div>
                </div>
            </div>
</section>
<!-- Footer-->
@include('Website.Includes.footer')
