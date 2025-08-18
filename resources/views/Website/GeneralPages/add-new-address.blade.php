@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')

    <!-- Login Section 1 -->
    <section class="about-sec1">
        <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
        <div class="container">
            <div class="text-center">
                <h2>Add New Address</h2>
                <p><span>Sydney Quality Meats:</span> Add New Address</p>
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
                        <div class="login-page__wrap__top pb-5">
                            <div class="login-page__wrap__content">
                                <h3 class="login-page__title">Address</h3>
                            </div>
                        </div>
                        <div class="tabs-content">
                            <div class="tab fadeInUp animated active-tab" data-wow-delay="200ms" id="login"
                                style="display: block;">
                                <span class="login-page__tab-title">Add your New Address
                                </span>
                                <form class="login-page__form container" method="post" action="{{url('/add-address')}}"
                                    id="boskery-login">
                                    @csrf
                                    <div class="row">
                                        <!-- First Name and Last Name -->
                                        <div class="col-md-6 mb-3">
                                            <input type="text" class="form-control UAInputs" name="first_name"
                                                placeholder="First Name*">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <input type="text" class="form-control UAInputs" name="last_name"
                                                placeholder="Last Name*">
                                        </div>

                                        <!-- Email and Phone -->
                                        <div class="col-md-6 mb-3">
                                            <input type="email" class="form-control UAInputs" name="email"
                                                placeholder="Email*">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <input type="tel" class="form-control UAInputs" name="phone"
                                                placeholder="Phone*">
                                        </div>

                                        <!-- City, State, and Suburb -->
                                        <div class="col-md-4 mb-3">
                                            <input type="text" class="form-control UAInputs" name="city" placeholder="City">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input type="text" class="form-control UAInputs" name="state"
                                                placeholder="State">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input type="text" class="form-control UAInputs" name="suburb"
                                                placeholder="Suburb">
                                        </div>

                                        <!-- Post Code, Street Address -->
                                        <div class="col-md-6 mb-3">
                                            <input type="text" class="form-control UAInputs" name="post_code"
                                                placeholder="Post Code">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <input type="text" class="form-control UAInputs" name="street_address"
                                                placeholder="Street Address">
                                        </div>

                                        <!-- Address Type and Company -->
                                        <div class="col-md-6 mb-3">
                                            <input type="text" class="form-control UAInputs" name="address_type"
                                                placeholder="Address Type">
                                        </div>
                                        <!-- <div class="col-md-6 mb-3">
                                            <input type="text" class="form-control UAInputs" name="company"
                                                placeholder="Company">
                                        </div> -->

                                        <!-- Submit Button -->
                                        <div class="col-12 text-center mt-4">
                                            <button type="submit"
                                                class="btn rounded-0 btn-danger add-to-cart-btn p-3 width__Buton" style="
                                                        background-color: #a41e22;
                                                        color: white;
                                                    ">
                                                Add Address
                                            </button>
                                        </div>
                                    </div>
                                </form>


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

    <style>
        .width__Buton {
            width: 25%;
        }

        @media (max-width: 520px) {
            .width__Buton {
                width: 50%;
            }
        }
    </style>


@endsection