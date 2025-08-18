@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')

<!-- Login Section 1 -->
<section class="about-sec1">
    <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
    <div class="container">
        <div class="text-center">
            <h2>Update Profile</h2>
            <p><span>Sydney Quality Meats:</span> Update Profile</p>
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
                            <h3 class="login-page__title">Profile</h3>
                        </div>
                    </div>
                    <div class="tabs-content">
                        <div class="tab fadeInUp animated active-tab" data-wow-delay="200ms" id="login"
                            style="display: block;">
                            <span class="login-page__tab-title">Update your Profile
                            </span>
                            <form class="login-page__form" action="{{url('update-profile')}}" method="post" id="boskery-login">
                                <div class="login-result text-center"></div>
                                @csrf

                                <!-- Personal Details Section -->
                                <h3 class="mb-3">Personal Details</h3>
                                <div class="login-page__form__input-box">
                                    <input type="text" name="name" value="{{$user->name}}" placeholder="Name*">
                                    <span class="icon-user"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                    <input type="email" name="email" value="{{$user->email}}" placeholder="Email*">
                                    <span class="icon-email"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                    <input type="password" name="password" value="" placeholder="Password*"
                                        class="login-page__password">
                                    <span class="icon-padlock"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                    <input type="tel" name="phone" value="{{$user->phone}}" placeholder="Phone">
                                    <span class="fa-solid fa-phone"></span>
                                </div>

                                <!-- Business Details Section -->
                                <h3 class="mb-4 mt-5">Business Details</h3>
                                <div class="login-page__form__input-box">
                                    <input type="text" name="business_name" value="{{$business['business_name'] ?? '' }}" placeholder="Business Name">
                                    <span class="icon-user"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                    <input type="email" name="business_email" value="{{$business['business_email']  ?? ''}}" placeholder="Business Email">
                                    <span class="icon-email"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                    <input type="tel" name="business_phone" value="{{$business['business_phone'] ?? ''}}" placeholder="Business Phone">
                                    <span class="fa-solid fa-phone"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                    <input type="text" value="{{$business['business_street_address'] ?? ''}}" name="business_street_address"
                                        placeholder="Business Street Address">
                                    <span class="fa-solid fa-location-dot"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                    <input type="text" name="area_address" value="{{$business['area_address'] ?? ''}}" placeholder="Area Address">
                                    <span class="fa-solid fa-location-pin"></span>
                                </div>
                                
                                <div class="login-page__form__input-box login-page__form__input-box--button">
                                    <button type="submit" class=" login-page__form__btn">Update Profile</button>
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




@endsection