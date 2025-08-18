@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
@endphp

<div class="page-content">
    <div class="container-fluid">
        <div class="page-inner">
            <div class="card shadow-sm mb-4">
                <div class="pageheader pd-t-25 pd-b-35 ">
                    <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
                        <div class="clearfix">
                            <div class="pd-t-5 pd-b-5">
                                <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Create Gift Card') }}</h1>
                            </div>

                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('Admin.Dashboard') }}"
                                            class="link-primary text-decoration-none">
                                            <i class="fa-solid fa-house me-1"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('/admin/gift-cards') }}"
                                            class="link-primary text-decoration-none">
                                            Gift Cards
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Create
                                    </li>
                                </ol>
                            </nav>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ url('/') }}/admin/gift-cards" class="btn btn-primary">
                                <i class="fa-solid fa-list-ul me-1"></i> Gift Card List
                            </a>
                            <a href="{{ url('/') }}/admin/gift-cards/expired/list" class="btn btn-warning">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i> Expired List
                            </a>

                            <a href="{{ url('/') }}/admin/gift-cards/deleted/list" class="btn btn-danger">
                                <i class="fa-solid fa-list-ul me-1"></i> Trash List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Breadcrumb End -->
            <!--================================-->
            <!--================================-->
            <div class="col-md-12 col-lg-12 px-0">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('gift-cards.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="created_by" value="{{ $logged_in_user->id }}">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('Gift Card Details (Compulsory)') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label class="form-control-label active">Title: <span
                                                        class="tx-danger">*</span></label>
                                                <input class="form-control " type="text" name="title"
                                                    placeholder="Promotion" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-control-label active">Variant: <span
                                                        class="tx-danger">*</span></label>
                                                <select name="variant" id="variant" class="form-select form-select2-demo">
                                                    <option value="" selected disabled>Selected Card Variant
                                                    </option>
                                                    <option value="gift_card"
                                                        @if (old('variant') == 'gift_card') selected @endif>
                                                        Gift Card</option>
                                                    <option value="promotion"
                                                        @if (old('variant') == 'promotion') selected @endif>
                                                        Promotion</option>
                                                    <option value="coupon"
                                                        @if (old('variant') == 'coupon') selected @endif>
                                                        Coupon</option>
                                                </select>
                                            </div>
                                            <div class="message">
                                                <p class="text-danger" style="font-weight: 600">Coupon Code will
                                                    auto-generate
                                                    when user buy this</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 coupon_code">
                                            <div class="form-group">
                                                <label class="form-control-label active">Coupon Code: <span
                                                        class="tx-danger">*</span></label>
                                                <div class="input-group">
                                                    <input class="form-control " type="text" name="coupon_code"
                                                        id="coupon" placeholder="Coupon">
                                                    <button class="btn btn-secondary" type="button"
                                                        onclick="generateCouponCode()">Generate</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label active">Usage: <span
                                                        class="tx-danger">*</span></label>
                                                <select name="usage" id="usage" class="form-select form-select2-demo">
                                                    <option value="" selected disabled>Selected Card Usage
                                                    </option>
                                                    <option value="one_time">One Time</option>
                                                    <option value="multiple_times">Multiple Times</option>
                                                    <option value="one_time_per_user">One Time per user</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">Value (Compulsory)</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-control-label active">Discount: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control " type="number" name="discount"
                                            Placeholder="Discount...." required>
                                    </div>
                                    <div class="form-group gift_card_price">
                                        <label class="form-control-label active">Price: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control" id="card_price" type="number" name="price"
                                            Placeholder="Price">
                                    </div>
                                </div>
                            </div>
                            <!-- test -->
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">Activate Date</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 dates_columms">
                                            <div class="form-group mb-3">
                                                <label class="form-control-label">Start Date: <span
                                                        class="tx-danger">*</span></label>
                                                <input class="form-control " type="date" name="start_date">
                                            </div>
                                        </div>
                                        <div class="col-md-6 dates_columms">
                                            <div class="form-group ">
                                                <label class="form-control-label">End Date: <span
                                                        class="tx-danger">*</span></label>
                                                <input class="form-control " type="date" name="end_date">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-3">
                                                <label class="form-control-label">Is this Expirable? <span
                                                        class="tx-danger">*</span></label>
                                                <br>
                                                <input type="radio" name="is_expirable" id="yes"
                                                    value="1">
                                                <label for="yes">Yes</label>
                                                &nbsp;&nbsp;
                                                <input type="radio" name="is_expirable" id="no"
                                                    value="0">
                                                <label for="no">No</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 expirations_week">
                                            <div class="form-group">
                                                <label class="form-control-label">Expire after weeks of buying<span
                                                        class="tx-danger">*</span></label>
                                                <input class="form-control " type="number"
                                                    name="expire_after_weeks">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('More Details (Required)') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-control-label active">Active Status: </label>
                                        <select class="form-select form-select2-demo" name="status" required>
                                            <option value="active" @if (old('status') == 'active') selected @endif>
                                                Active
                                            </option>
                                            <option value="expired" @if (old('status') == 'expired') selected @endif>
                                                Expire
                                            </option>
                                            <option value="deleted" @if (old('status') == 'deleted') selected @endif>
                                                Delete
                                            </option>
                                        </select>
                                    </div>
                                    <input class="btn btn-success float-right mt-3" type="submit" value="Save">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    $('.message').hide();
    $('.expirations_week').hide();
    $('.gift_card_price').hide();
    $(document).ready(function() {
        document.getElementById("variant").addEventListener("change", function() {
            var selectedVariant = this.value;
            var couponField = document.getElementById("coupon");

            if (selectedVariant === "gift_card") {
                $('#coupon').val('');
                $('.coupon_code').hide();
                $('.message').show();
                $('.gift_card_price').show();
                $('.dates_columms').hide();
                $('.expirations_week').show();
            } else {
                $('.coupon_code').show();
                $('.message').hide();
                $('.gift_card_price').hide();
                $('#card_price').val('');
                $('.dates_columms').show();
                $('.expirations_week').hide();
            }
        });
    });

    function generateCouponCode() {
        const charset =
            "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        let couponCode = "";
        let length = 7;

        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * charset.length);
            couponCode += charset.charAt(randomIndex);
        }
        $('#coupon').val(couponCode);
    }
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            // Use Bootstrap's fade out
            alert.classList.remove('show');
            alert.classList.add('fade');
            // Completely remove from DOM after transition
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>


@if ($errors->any())
    <script>
        $(document).ready(function() {
            // Reset the whole form
            $('#giftCardForm')[0].reset();

            // Also clear manually set values in hidden/select/textarea if needed
            $('#giftCardForm').find('input[type=hidden], input[type=text], input[type=number], textarea, select')
                .val('');
            $('#giftCardForm').find('input[type=radio], input[type=checkbox]').prop('checked', false);
            $('#giftCardForm').find('option:selected').prop('selected', false);

            // Hide specific dynamic sections
            $('.message').hide();
            $('.gift_card_price').hide();
            $('.expirations_week').hide();
            $('.dates_columms').show(); // Optional: show default fields
        });
    </script>
@endif
