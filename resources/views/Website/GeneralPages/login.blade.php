@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')

<!-- Login Section 1 -->
<section class="about-sec1">
    <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
    <div class="container">
        <div class="text-center">
            <h2>Login</h2>
            <p><span>Sydney Quality Meats:</span> Login</p>
        </div>
    </div>
</section>

<!-- Login Section 2 -->

<section class="login-page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="login-page__inner">
                    <div class="login-page__image wow fadeInLeft animated" data-wow-duration="1500ms"
                        style="visibility: visible; animation-duration: 1500ms;">
                        <img src="{{ asset('WebsiteAssets/assets/img/login-1-1-1.jpg') }}" alt="login-1-1"
                            title="login-1-1">
                    </div>
                    <div class="login-page__wrap login-page__main-tab-box tabs-box wow fadeInRight animated"
                        data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms;">
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
                                        <span class="Login_sec2ActiveLogin_Button">
                                            <span>Sign In</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/sign-up" style="text-decoration: none;">
                                        <span class="Login_sec2Register_Button">
                                            <span>Register</span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tabs-content">
                            <div class="tab fadeInUp animated active-tab" data-wow-delay="200ms" id="login"
                                style="display: block;">
                                <span class="login-page__tab-title">Sign in Sydney Quality Meats account</span>
                                <form class="login-page__form" id="boskery-login" action="{{ route('Website.Login') }}" method="POST">
                                    @csrf
                                    <div class="login-result text-center"></div>
                                    <input type="hidden" id="security" name="security" value="d88e9e6dfb">
                                    <input type="hidden" name="_wp_http_referer" value="/boskerywp/login/">
                                    <div class="login-page__form__input-box">
                                        <input type="text" name="email" placeholder="Username or Email Address*">
                                        <span class="icon-email"></span>
                                    </div>
                                    <div class="login-page__form__input-box">
                                        <input type="password" name="password" placeholder="Password*" class="login-page__password">
                                        <span class="icon-padlock"></span>
                                        <!-- Toggle eye icon -->
                                        <i class="toggle-password fa-regular fa-eye-slash"></i>
                                    </div>

                                    <div class="login-page__form__input-box login-page__form__input-box--forgot">
                                        <a class="login-page__form__forgot"
                                            href="/forget-password">Forgot
                                            your
                                            Password?</a>
                                    </div>
                                    <div class="login-page__form__input-box login-page__form__input-box--button">
                                        <button type="submit" class="login-page__form__btn">Login</button>
                                    </div>
                                    <div class="login-page__form__input-box login-page__form__input-box--checkbox">
                                        <div class="login-page__form__checked-box">
                                            <input type="checkbox" name="remember" id="remember">
                                            <label for="remember"><span></span>Remember Me?</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<script>
    $(document).ready(function() {
        @if(session('status'))
            toastr.success("{{ session('message') }}");
        @endif
    });
</script>
<!-- WOW.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script>
new WOW().init();
</script>
<script>
    $('form').on('submit', function() {
        $('#submitButton').prop('disabled', true).text('Loading...');
    });
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