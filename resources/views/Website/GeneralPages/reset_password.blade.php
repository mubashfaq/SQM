@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')
    <!-- MULTITAB SECTION -->
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
                                <span class="login-page__tab-title">Recover your Sydney Quality Meats account
                                </span>
                                <form action="{{ route('Website.UserPasswordUpdate') }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        {{-- <span class="input-group-text" id="basic-addon1">
                                        <img src="{{ url('/') }}/WebsiteAssets/images/Group 76.png"
                                            alt="">
                                    </span> --}}
                                        <input type="hidden" name="token" class="form-control login-inp"
                                            value="{{ $token }}">
                                    </div>
                                    @error('token')
                                        <span class="text-danger" style="font-size: x-small;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="login-page__form__input-box">
                                        <input type="password" name="password" id="password" placeholder="Password*" class="login-page__password form-control login-inp password-field">
                                        <span class="icon-padlock"></span>
                                        <!-- Toggle eye icon -->
                                        <i class="toggle-password fa-regular fa-eye"></i>
                                    </div>
                                    <!-- @error('password')
                                        <span class="text-danger" style="font-size: x-small">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror -->
                                    <!-- <div class="input-group pt-3">
                                        <span class="input-group-text" id="basic-addon1">
                                            <img src="{{ url('/') }}/WebsiteAssets/images/Vector (5).png"
                                                alt="">
                                        </span>
                                        <input type="password" id="" name="password_confirmation"
                                            id="password_confirmation" class="form-control login-inp password-field"
                                            placeholder="Confirm Password">
                                    </div> -->
                                    <div class="login-page__form__input-box ">
                                        <input type="password" name="password_confirmation" id="confirmPassword" placeholder="Confirm Password*" class="login-page__password form-control login-inp password-field">
                                        <span class="icon-padlock"></span>
                                        <!-- Toggle eye icon -->
                                        <i class="toggle-password fa-regular fa-eye"></i>
                                    </div>
                                    <!-- @error('password')
                                        <span class="text-danger" style="font-size: x-small">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror -->
                                    <div class="login-page__form__input-box login-page__form__input-box--button">
                                        <button type="submit" id="submitButton" class="login-page__form__btn">Update
                                        Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
<script>
    $('form').on('submit', function() {
        $('#submitButton').prop('disabled', true).text('Loading...');
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".toggle-password").forEach(function (icon) {
        icon.addEventListener("click", function () {
            const passwordInput = this.closest(".login-page__form__input-box").querySelector(".password-field");
            if (passwordInput) {
                passwordInput.type = passwordInput.type === "password" ? "text" : "password";
                this.classList.toggle("fa-eye-slash"); // Toggle icon
                this.classList.toggle("fa-eye");
            }
        });
    });
});


</script>

