@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')

<!-- Login Section 1 -->
<section class="about-sec1">
    <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
    <div class="container">
        <div class="text-center">
            <h2>Forget Password</h2>
            <p><span>Sydney Quality Meats:</span> Forget Password</p>
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
                            <h3 class="login-page__title">WELCOME</h3>
                        </div>
                    </div>
                    <div class="tabs-content">
                        <div class="tab fadeInUp animated active-tab" data-wow-delay="200ms" id="login"
                            style="display: block;">
                            <span class="login-page__tab-title">Recover your Sydney Quality Meats
                            account
                            </span>
                            <form class="login-page__form" id="boskery-login" action="{{ route('Website.ForgotPasswordRoute') }}" method="POST">
                                @csrf
                                <div class="login-result text-center"></div>
                                <input type="hidden" id="security" name="security" value="d88e9e6dfb">
                                <input type="hidden" name="_wp_http_referer" value="/boskerywp/login/">

                               
                                <div class="login-page__form__input-box">
                                    <input type="email" name="email" placeholder="Email*">
                                    <span class="icon-email"></span>
                                </div>
                                
                                <div class="login-page__form__input-box login-page__form__input-box--button Send_OTp_main_div">
                                    <button type="submit" class="login-page__form__btn Send_OTp">Send</button>
                                    <div class="RTSIgnIn">
                                        <h3>Return To <a href="/sign-in">Sign In</a></h3>
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

<script>
    $(document).ready(function() {
        @if(session('status'))
            var status = {{ session('status') }};
            var message = "{{ session('message') }}";
            
            // Check if status is 200, show success, otherwise show error
            if(status == 200) {
                toastr.success(message);
            } else {
                toastr.error(message);
            }
        @endif
    });
</script>


@endsection