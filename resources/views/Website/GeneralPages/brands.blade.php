@include('Website.Includes.header', ['meta_Details' => $meta_details])
    <!-- MULTITAB SECTION -->
    <section class="mtop-50">
        <div class="container">
            <!-- LATEAST TOOL STORE -->
            <div class="row ">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="collection-view-top-section">
                        <h1 class="collection-view-title"> Brands </h1>
                        <p class="mb-0"><a href="#" class="collection-view-linked-desc">Home - Brands</a></p>
                    </div>
                </div>
            </div>

        <div class="row  brand-ptop-20px">
            @foreach($brands as $brand)  
            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ url('/tool-brands/' . $brand->permalink) }}">
                    <div class="brand-view-img">
                        <img src="{{ url('/') . '/' . $brand->logo_image}}" alt="">
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        </div>
    </section>
@include('Website.Includes.footer')
