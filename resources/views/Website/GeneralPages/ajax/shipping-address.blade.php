<label for="" class="checkout-contact-title">Shipping Address</label>
<div class="row">
    @foreach ($shipping as $ship_address)
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card-body profile-password-change-section mb-3"
                onclick="handleShippingAddress({{ $ship_address->id }})">
                <div class="use_this_address">
                    <input type="radio" name="selected_billing_address" id="radio_{{ $ship_address->id }}"
                        value="{{ $ship_address->id }}">
                    <label for="selected_billing_address">Use this Address</label>
                    <hr>
                </div>
                <h5 class="card-title">
                    {{ $ship_address->f_name . ' ' . $ship_address->l_name }}
                </h5>
                <div class="d-flex justify-content-between">
                    <h6 class="card-subtitle mb-2">
                        {{ $ship_address->company }}
                    </h6>
                    <h6 class="card-subtitle mb-2" style="text-transform:capitalize; text-align:end">
                        {{ $ship_address->address_type }}</h6>
                </div>
                <p class="mb-0">{{ $ship_address->email }}</p>
                <p class="mb-0">{{ $ship_address->phone }}</p>
                <p class="card-text mb-0">
                    {{ $ship_address->street_address }}</p>
                <p>{{ $ship_address->suburb }}, {{ $ship_address->city }},
                    {{ $ship_address->state }},
                    {{ $ship_address->post_code }}</p>
                <hr>
                <button class="btn btn-warning" type="button"
                    onclick="updateAddress({{ $ship_address->id }})">Update</button>
            </div>
        </div>
    @endforeach
</div>
@if ($shipping == null)
    <br><span class="text-danger"><b>No Billing Address is
            available.</b></span>
@endif
