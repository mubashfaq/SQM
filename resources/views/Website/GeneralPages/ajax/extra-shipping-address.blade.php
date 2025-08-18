<label for="" class="checkout-contact-title">Shipping Address</label>
<div id="shipping_address_form">
    <div class="row mt-3">
        <div class="col-md-6 form-group">
            <input type="text" class="form-control" id="shipping_f_name" name="shipping_f_name"
                value="{{ old('shipping_f_name') }}" required>
            <label class="form-label" for="shipping_f_name">Receiver First Name</label>
        </div>
        <div class="col-md-6 form-group">
            <input type="text" class="form-control" name="shipping_l_name" id="shipping_l_name"
                value="{{ old('shipping_l_name') }}" required>
            <label class="form-label" for="shipping_l_name">Receiver Last Name</label>
        </div>
        <div class="col-md-6 form-group">
            <input type="email" class="form-control" id="shipping_email" name="shipping_email"
                value="{{ old('shipping_email') }}" required>
            <label class="form-label" for="shipping_email">Email</label>
        </div>
        <div class="col-md-6 form-group">
            <input type="tel" class="form-control" id="shipping_phone" name="shipping_phone"
                value="{{ old('shipping_phone') }}" required>
            <label class="form-label" for="shipping_phone">Phone Number</label>
        </div>
        <div class="col-md-12 form-group">
            <input type="text" class="form-control" id="shipping_company" name="shipping_company"
                value="{{ old('shipping_company') }}" required>
            <label class="form-label" for="shipping_company">Company or Business Name</label>
        </div>
        <div class="col-md-12 form-group">
            <input type="text" class="form-control address-input" id="shipping_street_address" name="shipping_street_address"
                value="{{ old('shipping_street_address') }}" required>
            <label class="form-label" for="shipping_street_address">Address</label>
            <div class="suggestions" style="background:white;"></div>
             <div class="details"></div>
        </div>
        <div class="col-md-6 form-group">
            <input type="text" class="form-control" id="shipping_suburb" name="shipping_suburb"
                value="{{ old('shipping_suburb') }}" required>
            <label class="form-label" for="shipping_suburb">Suburb</label>
        </div>
        <div class="col-md-6 form-group">
            <input type="text" class="form-control" id="shipping_city" name="shipping_city"
                value="{{ old('shipping_city') }}" required>
            <label class="form-label" for="shipping_city">City</label>
        </div>
        <div class="col-md-6 form-group">
            <input type="text" class="form-control" id="shipping_state" name="shipping_state"
                value="{{ old('shipping_state') }}" required>
            <label class="form-label" for="shipping_state">State</label>
        </div>
        <div class="col-md-6 form-group">
            <input type="text" class="form-control" id="shipping_post_code" name="shipping_post_code"
                value="{{ old('shipping_post_code') }}" required>
            <label class="form-label" for="shipping_post_code">Postcode</label>
        </div>
    </div>
</div>
