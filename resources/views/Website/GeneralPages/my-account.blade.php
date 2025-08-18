@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')

    <!-- Login Section 1 -->
    <section class="about-sec1">
        <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
        <div class="container">
            <div class="text-center">
                <h2>Account</h2>
                <p><span>Sydney Quality Meats:</span> Account</p>
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

                        <div class="container Order_History_Sec2">
                            <h2 class="text-center mb-4">My Account</h2>

                            <!-- Personal Details -->
                            <div class="row mb-4">
                                <div class="col-12 col-md-6">
                                    <div class="card card_OH">
                                        <div class="card-body">
                                            <h5 class="card-title">Personal Details</h5>
                                            <p><strong>Name</strong>: {{$user->name}}</p>
                                            <p><strong>Email</strong>: {{$user->email}}</p>
                                            <p><strong>Phone</strong>: {{$user->phone}}</p>
                                        </div>
                                    </div>
                                </div>


                                <!-- Business Details -->
                                <div class="col-12 col-md-6">
                                    <div class="card card_OH">
                                        <div class="card-body">
                                            <h5 class="card-title">Business Details</h5>
                                            <p><strong>Name</strong>:
                                                {{ $business_details['business_name'] ?? 'Not available' }}
                                            </p>
                                            <p><strong>Email</strong>:
                                                {{ $business_details['business_email'] ?? 'Not available' }}
                                            </p>
                                            <p><strong>Phone</strong>:
                                                {{ $business_details['business_phone'] ?? 'Not available' }}
                                            </p>
                                            <p><strong>Street Address</strong>:
                                                {{ $business_details['business_street_address'] ?? 'Not available' }}
                                            </p>
                                            <p><strong>Area Address</strong>:
                                                {{ $business_details['area_address'] ?? 'Not available' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card card_OH">
                                        <div class="card-body">
                                            <h5 class="card-title pb-4">Address</h5>
                                            @if($user_address)
                                                <div class="mb-3">
                                                    <p><strong>First Name</strong>: {{ $user_address->f_name }}</p>
                                                    <p><strong>Last Name</strong>: {{ $user_address->l_name }}</p>
                                                    <p><strong>Email</strong>: {{ $user_address->email }}</p>
                                                    <p><strong>Phone</strong>: {{ $user_address->phone }}</p>
                                                    <p><strong>City</strong>: {{ $user_address->city }}</p>
                                                    <p><strong>State</strong>: {{ $user_address->state }}</p>
                                                    <p><strong>Suburb</strong>: {{ $user_address->suburb }}</p>
                                                    <p><strong>Post Code</strong>: {{ $user_address->post_code }}</p>
                                                    <p><strong>Street Address</strong>: {{ $user_address->street_address }}</p>
                                                    <p><strong>Address Type</strong>: {{ $user_address->address_type }}</p>
                                                </div>
                                                <div class="d-flex">
                                                    <a href="{{ route('Website.GeneralPages.update-address') }}"
                                                        style="text-decoration: none; color: white;">
                                                        <button class="btn btn-danger  add-to-cart-btn_my_account btn-view">

                                                            Edit Address
                                                        </button>
                                                    </a>

                                                </div>
                                            @else
                                                <a href="/add-new-address" style="text-decoration: none; color: white;">
                                                    <button class="btn btn-danger  add-to-cart-btn_my_account btn-view">
                                                        Add Address
                                                    </button>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit/Add Buttons -->
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <a href="/update-profile" style="text-decoration: none; color: white;">
                                        <button class="btn btn-danger  add-to-cart-btn_my_account btn-view">

                                            Edit Profile
                                        </button>
                                    </a>

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