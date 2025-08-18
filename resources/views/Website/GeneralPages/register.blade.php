@extends('Website.Includes.header', ['meta_Details' => $meta_details])

@section('content')

<!-- Login Section 1 -->
<section class="about-sec1">
    <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
    <div class="container">
        <div class="text-center">
            <h2>Register</h2>
            <p><span>Sydney Quality Meats:</span> Register</p>
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
                    <div class="login-page__wrap__top">
                        <div class="login-page__wrap__content">
                            <h3 class="login-page__title">WELCOME</h3>
                        </div>
                        <ul class="tab-buttons">
                            <li>
                                <a href="/sign-in" style="text-decoration: none;">
                                    <span class="Login_sec2Register_Button">
                                        <span>Sign In</span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="/sign-up" style="text-decoration: none;">
                                    <span class="Login_sec2ActiveLogin_Button">
                                        <span>Register</span>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tabs-content">
                        <div class="tab fadeInUp animated active-tab" data-wow-delay="200ms" id="login"
                            style="display: block;">
                            <span class="login-page__tab-title">Sign up your Sydney Quality Meats account
                            </span>
                            <form class="login-page__form" id="boskery-login" action="{{ route('Website.RegisterUser') }}" method="POST">
                                @csrf
                                <div class="login-result text-center"></div>
                                <input type="hidden" id="security" name="security" value="d88e9e6dfb">
                                <input type="hidden" name="_wp_http_referer" value="/boskerywp/login/">

                                <!-- Personal Details Section -->
                                <h3 class="mb-3">Personal Details</h3>
                                <div class="login-page__form__input-box">
                                    <input type="text" name="name" placeholder="Name*" value="{{ old('name') }}">
                                    <span class="icon-user"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                    <input type="email" name="email" placeholder="Email*" value="{{ old('email') }}">
                                    <span class="icon-email"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                        <input type="password" name="password" placeholder="Password*" class="login-page__password">
                                        <span class="icon-padlock"></span>
                                        <!-- Toggle eye icon -->
                                        <i class="toggle-password fa-regular fa-eye-slash"></i>
                                    </div>
                                <div class="login-page__form__input-box">
                                    <input type="tel" name="phone" placeholder="Phone" value="{{ old('phone') }}">
                                    <span class="fa-solid fa-phone"></span>
                                </div>

                                <!-- Business Details Section -->
                                <h3 class="mb-4 mt-5">Business Details (Optional)</h3>
                                <div class="login-page__form__input-box">
                                    <input type="text" name="business_name" placeholder="Business Name" value="{{ old('business_name') }}">
                                    <span class="icon-user"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                    <input type="email" name="business_email" placeholder="Business Email" value="{{ old('business_email') }}">
                                    <span class="icon-email"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                    <input type="tel" name="business_phone" placeholder="Business Phone" value="{{ old('business_phone') }}">
                                    <span class="fa-solid fa-phone"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                    <input type="text" name="business_street_address"
                                        placeholder="Business Street Address" value="{{ old('business_street_address') }}">
                                    <span class="fa-solid fa-location-dot"></span>
                                </div>
                                <div class="login-page__form__input-box">
                                    <input type="text" name="area_address" placeholder="Area Address" value="{{ old('area_address') }}">
                                    <span class="fa-solid fa-location-pin"></span>
                                </div>

                                <!-- Terms and Signup Button -->
                                <div class="login-page__form__input-box login-page__form__input-box--checkbox">
                                    <div class="login-page__form__checked-box my-4">
                                        <input type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }}>
                                        <label for="terms"><span></span>I agree to the <a href="/terms" class="login-page__form__forgot text__sizee">Terms of Service and
                                                Privacy Policy</a></label>
                                    </div>
                                </div>
                                <div class="login-page__form__input-box login-page__form__input-box--button">
                                    <button type="submit" class="login-page__form__btn">Sign Up</button>
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

<script>
    document.querySelector('.toggle-password').addEventListener('click', function (e) {
        const passwordInput = e.target.parentNode.querySelector('input[name="password"]');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        // Toggle the icon class here between 'fa-eye' and 'fa-eye-slash'
        const icon = e.target;
        if (icon.classList.contains('fa-eye')) {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>


@endsection
