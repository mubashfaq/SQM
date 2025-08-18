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
        height: 100%;
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
</style>
<section class="mtop-50">
    <div class="container">
        <!-- LATEAST TOOL STORE -->
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="collection-view-top-section">
                    <h1 class="collection-view-title"> Gift Cards </h1>
                    <p class="mb-0"><a href="#" class="collection-view-linked-desc">Home - Gift Cards</a></p>
                </div>
            </div>
        </div>

        <div class="row brand-ptop-20px">
            @foreach ($giftCards as $giftCard)
                <div class="col-lg-4 col-md-6 col-sm-6 p-3 my-3">
                    <div class="card text-center card_styling">
                        <div class="card-body">
                            <h4 class="card-title" style="font-weight: 700;">${{ $giftCard->price }}</h4>
                            <p class="card-text" style="font-weight: 500;">{{ $giftCard->title }}</p>
                        </div>
                        <div class="card-footer">
                            <a
                                href="{{ route('Website.GeneralPages.gift-card-checkout', ['id' => base64_encode( $giftCard->id)]) }}">
                                <button class="btn rounded-3 w-50 card-button mb-3 handlePurchaseGiftCard"
                                    data-p_id="{{ $giftCard->id }}">
                                    Proceed To Buy
                                </button>
                            </a>
                            <div class="w-100 logo_div">
                                <img class="w-25" src="{{ asset(url('/WebsiteAssets/images/black_logo.png')) }}"
                                    alt="Sydney Quality Meats">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@include('Website.Includes.footer')
