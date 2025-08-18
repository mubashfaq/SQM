@include('Website.Includes.header', ['meta_Details' => $meta_details])

@php
    use App\General\UserClass;
    $currentPage = request()->input('page', 1);
    $posCurrentPage = request()->input('pos_page', 1);
    session(['current_page' => $currentPage]);
    session(['pos_current_page' => $posCurrentPage]);
@endphp
<style>
    .page-item.active .page-link {
        background-color: var(--color-orange) !important;
        color: white !important;
    }
    @media (max-width: 320px) {
      .profile-tab-content {
        width: 224px !important;
      }
      .shipping_addres{
        margin-right: 4px;
      }
    }
</style>
<!-- MULTITAB SECTION -->
<div class="profile-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="profile-main-title">
                    <h2>Profile Settings</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class=" align-items-start">
                    <div class="nav flex-column profile-tab nav-pills me-3" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <button
                            class="nav-link profile-tab-btn tab-link {{ request()->is('all-orders') ? 'active' : '' }}"
                            id="v-pills-home-tab" data-bs-toggle="pill" data-route="all-orders"
                            data-bs-target="#v-pills-profile" type="button" role="tab"
                            aria-controls="v-pills-profile" aria-selected="false">My Orders</button>
                        <button
                            class="nav-link profile-tab-btn tab-link {{ request()->is('pos-orders') ? 'active' : '' }}"
                            id="v-pills-home-tab" data-bs-toggle="pill" data-route="pos-orders"
                            data-bs-target="#v-pills-pos" type="button" role="tab" aria-controls="v-pills-profile"
                            aria-selected="false">Pos Order</button>
                        <button
                            class="nav-link profile-tab-btn tab-link {{ request()->is('return-orders') ? 'active' : '' }}"
                            id="v-pills-return-tab" data-bs-toggle="pill" data-route="return-orders"
                            data-bs-target="#v-pills-return" type="button" role="tab"
                            aria-controls="v-pills-profile" aria-selected="false">Return Order</button>
                        <button class="nav-link profile-tab-btn tab-link {{ request()->is('profile') ? 'active' : '' }}"
                            id="v-pills-profile-tab" data-bs-toggle="pill" data-route="profile"
                            data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home"
                            aria-selected="true">Profile</button>

                        <button
                            class="nav-link profile-tab-btn tab-link {{ request()->is('business-details') ? 'active' : '' }}"
                            id="v-pills-messages-tab" data-route="business-details" data-bs-toggle="pill"
                            data-bs-target="#v-pills-messages" type="button" role="tab"
                            aria-controls="v-pills-messages" aria-selected="false">Business Details</button>

                        <button
                            class="nav-link profile-tab-btn tab-link {{ request()->is('shipping-addresses') ? 'active' : '' }}"
                            id="v-pills-settings-tab" data-bs-toggle="pill" data-route="shipping-addresses"
                            data-bs-target="#v-pills-settings" type="button" role="tab"
                            aria-controls="v-pills-settings" aria-selected="false">Shipping Addresses</button>
                    </div>
                    <div class="tab-content profile-tab-content" id="v-pills-tabContent">

                        {{-- Profile Tab Content Starts --}}
                        <div class="tab-pane fade profile-tab-py {{ request()->is('profile') ? 'show active' : '' }}"
                            id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="card shadow">
                                <div class="card-body">
                                    <form action="{{ route('Website.ProfileImage') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row profile-reverse-row">
                                            <div class="col-lg-8 col-md-8 col-sm-12">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        @if ($user->name)
                                                            @php
                                                                $splitName = explode(' ', $user->name);
                                                                $firstName = $splitName[0];
                                                                $lastName = count($splitName) > 1 ? implode(' ', array_slice($splitName, 1)) : '';
                                                            @endphp
                                                        @endif
                                                        <div class="form-group pt-3">
                                                            <label for="">First Name:</label>
                                                            <input type="text" class="form-control" name="first_name"
                                                                placeholder="First Name" value="{{ $firstName ?? '' }}">
                                                        </div>
                                                        @error('first_name')
                                                            <span class="text-danger font-class-small">
                                                                <strong>
                                                                    {{ $message }}
                                                                </strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <div class="form-group pt-3">
                                                            <label for="">Last Name:</label>
                                                            <input type="text" class="form-control" name="last_name"
                                                                placeholder="Last Name" value="{{ $lastName ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <div class="form-group pt-3">
                                                            <label for="">Email:</label>
                                                            <input type="text" class="form-control" readonly disabled
                                                                placeholder="Doe@gmail.com"
                                                                value="{{ $user->email ?? '' }}">
                                                        </div>
                                                        @error('email')
                                                            <span class="text-danger font-class-small">
                                                                <strong>
                                                                    {{ $message }}
                                                                </strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <div class="form-group pt-3">
                                                            <label for="">Phone:</label>
                                                            <input type="text" class="form-control" name="phone"
                                                                placeholder="Phone" value="{{ $user->phone ?? '' }}">
                                                        </div>
                                                        @error('phone')
                                                            <span class="text-danger font-class-small">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="form-group pt-3" style="position: relative;">
                                                            <label for="">Address:</label>
                                                            <!--<input type="text" class="form-control"-->
                                                            <!--    name="_address" placeholder="Address"-->
                                                            <!--    value="{{ UserClass::getOptionValue('_address', $user->userMeta) }}">-->
                                                             <input type="text" name="_address" class="form-control address-input"
                                                                value="{{ UserClass::getOptionValue('_address', $user->userMeta) }}" placeholder="Address">
                                                            <div class="suggestions" style="position: absolute;
                                                                background: white;
                                                                z-index: 200;
                                                                top: 76px;
                                                                border: 1px solid #ccc;"></div>
                                                            <div class="details"></div>
                                                        </div>
                                                        @error('address')
                                                            <span class="text-danger font-class-small">
                                                                <strong>
                                                                    {{ $message }}
                                                                </strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="profile-image">
                                                            <div
                                                                class="media align-items-center justify-content-center mb-3 d-flex">
                                                                <div class="avatar-upload">
                                                                    <div class="avatar-edit">
                                                                        <input type="file" id="imageUpload"
                                                                            name="profile_image"
                                                                            accept=".png, .jpg, .jpeg" hidden />
                                                                        <label for="imageUpload" class="pencil-lable">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="15" height="15"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="feather feather-camera">
                                                                                <path
                                                                                    d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z">
                                                                                </path>
                                                                                <circle cx="12" cy="13"
                                                                                    r="4">
                                                                                </circle>
                                                                            </svg>
                                                                        </label>
                                                                    </div>
                                                                    <div class="avatar-preview">
                                                                        <div id="imagePreview"
                                                                            style="background-image: url({{ $user->profile_image ?? '' }});">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="profile-save-changes">
                                            <button class="btn px-5">Save Changes</button>
                                        </div>
                                    </form>
                                    <hr>
                                    @if (session('success_status'))
                                        <div class="message w-100 mb-3"
                                            style="background-color: #88f56b!important; padding: 5px 10px;">
                                            <span><strong>{{ session('message') }}</strong></span>
                                        </div>
                                    @elseif (session('error_status'))
                                        <div class="message w-100 mb-3"
                                            style="background-color: #fa6a78!important; padding: 5px 10px;">
                                            <span><strong>{{ session('message') }}</strong></span>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="profile-password-change-section">
                                                <div class="profile-password-change">
                                                    <p>Password</p>
                                                    <button class="btn profile-chnage-passord-btn"
                                                        id="passwordChangeToggle">Change</button>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-12 col-sm-12">
                                                        <div class="profile-password-change-desc">
                                                            <p>You can Reset or Change your passowrd by checking
                                                                name</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="changePasswordDiv px-3 pb-2"
                                                style="border: 1px solid rgb(144, 144, 144); border-radius: 8px; border-top: none;">
                                                <form action="{{ route('Website.LoggedUpdatePassword') }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="form-group pt-3">
                                                        <label for="">Old Password:</label>
                                                        <input type="text" class="form-control password-field"
                                                            name="old_password" placeholder="*********">
                                                    </div>
                                                    <input type="hidden" name="userid"
                                                        value="{{ $user->id }}">
                                                    @error('old_password')
                                                        <span class="text-danger font-class-small">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="form-group pt-3">
                                                        <label for="">New Password:</label>
                                                        <input type="text" class="form-control password-field" name="password"
                                                            placeholder="*********">
                                                    </div>
                                                    @error('password')
                                                        <span class="text-danger font-class-small">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="form-group pt-3">
                                                        <label for="">Confirm Password:</label>
                                                        <input type="text" class="form-control password-field"
                                                            name="password_confirmation" placeholder="*********">
                                                    </div>
                                                    @error('password_confirmation')
                                                        <span class="text-danger font-class-small">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div style="text-align: end;">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="showpassword">
                                                        <label for="showpassword" class="form-check-label">Show
                                                            Password</label>
                                                    </div>
                                                    <div class="profile-save-changes">
                                                        <button id="submitButton" class="btn px-4">Save
                                                            Password</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- General Orders Tab Content Starts --}}
                        <div class="tab-pane fade {{ request()->is('all-orders') ? 'show active' : '' }}"
                            id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                            <div class="card shadow">
                                <div class="card-body" style="overflow:auto">
                                    <table class="table profile-order-detail" style="border:none !important;">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Invoice #</th>
                                                <th scope="col">Placed On</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($orders->isNotEmpty())
                                                @foreach ($orders as $order)
                                                    <tr>
                                                        <td>{{ $order->id }}</td>
                                                        <td>{{ $order->invoice_number }}</td>
                                                        <td>{{ $order->created_at }}</td>
                                                        <td>${{ $order->grand_total }}</td>
                                                        <td>{{ ucwords($order->status) }}</td>
                                                        <td>
                                                            <a class="text-dark" style="text-decoration: none;"
                                                                href="{{ route('Website.order_details', base64_encode($order->id)) }}">
                                                                <button class="btn py-0"
                                                                    style="background-color:#E59041; color:white;">View
                                                                    Detail</button>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <td colspan="6" class="text-center">No orders found.</td>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="w-100 px-5 d-flex justify-content-end p-2">
                                        <div class="d-flex">
                                            {!! $orders->appends(['page' => session('current_page')])->links('pagination::bootstrap-default') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- POS Orders Tab Content Starts --}}
                        <div class="tab-pane fade {{ request()->is('pos-orders') ? 'show active' : '' }}"
                            id="v-pills-pos" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                            <div class="card shadow">
                                <div class="card-body" style="overflow:auto">
                                    <table class="table profile-order-detail" style="border:none !important; ">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Invoice #</th>
                                                <th scope="col">Placed On</th>
                                                <th scope="col">Payment Status</th>
                                                <th scope="col">Payment Method</th>
                                                <th scope="col">SubTotal</th>
                                                <th scope="col">Discount</th>
                                                <th scope="col">TotalAmount</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($invoices->isNotEmpty())
                                                @foreach ($invoices as $invoice)
                                                    <tr>
                                                        <td>{{ $invoice->id }}</td>
                                                        <td>{{ $invoice->invoice_number }}</td>
                                                        <td>{{ $invoice->updated_at->format('d-m-Y') }}</td>
                                                        <td>{{ $invoice->payment_status }}</td>
                                                        <td>{{ $invoice->payment_method }}</td>
                                                        <td>{{ $invoice->sub_total }}</td>
                                                        <td>{{ $invoice->discount }}</td>
                                                        <td>{{ $invoice->discounted_amount }}</td>
                                                        <td>
                                                            <a type="button" class="text-dark"
                                                                href="{{ route('Website.pos_order_detail', $invoice->id) }}">
                                                                <button class="btn py-0"
                                                                    style="background-color:#E59041; color:white;">
                                                                    View Detail
                                                                </button>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <td colspan="9" class="text-center">No orders found.</td>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="w-100 px-5 d-flex justify-content-end p-2">
                                        <div class="d-flex">
                                            {!! $invoices->appends(['pos_page' => session('pos_current_page')])->links('pagination::bootstrap-default') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Return Orders Tab Content Starts --}}
                        <div class="tab-pane fade {{ request()->is('return-orders') ? 'show active' : '' }}"
                            id="v-pills-return" role="tabpanel" aria-labelledby="v-pills-return-tab">
                            <div class="card shadow">
                                <div class="card-body" style="overflow:auto">
                                    <table class="table profile-order-detail" style="border:none !important;">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Invoice #</th>
                                                <th scope="col">Return Type</th>
                                                <th scope="col">Return Using</th>
                                                <th scope="col">Grand Total</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($returns->isNotEmpty())
                                                @foreach ($returns as $return)
                                                    <tr>
                                                        <td>{{ $return->id }}</td>
                                                        <td>{{ $return->order->invoice_number }}</td>
                                                        <td>{{ ucwords($return->return_type) }}</td>
                                                        <td>{{ ucwords($return->return_via) }}</td>
                                                        <td>${{ $return->grandTotal }}</td>
                                                        <td>
                                                            <a class="text-dark" style="text-decoration: none;"
                                                                href="{{ route('Website.return_order_details', base64_encode($return->id)) }}">
                                                                <button class="btn py-0"
                                                                    style="background-color:#E59041; color:white;">View
                                                                    Detail</button>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <td colspan="6" class="text-center">No orders found.</td>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="w-100 px-5 d-flex justify-content-end p-2">
                                        <div class="d-flex">
                                            {!! $returns->appends(['page' => session('current_page')])->links('pagination::bootstrap-default') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Business Details Tab Content Starts --}}
                        <div class="tab-pane fade {{ request()->is('business-details') ? 'show active' : '' }}"
                            id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                            <div class="card shadow">
                                <div class="card-body">
                                    <form action="{{ route('Website.GeneralPages.Update-Business-Details') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <h4>Business Info</h4>
                                        <div class="row mg-b-25">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-control-label active">Business Name:</label>
                                                    <input class="form-control" type="text"
                                                        value="{{ UserClass::getOptionValue('_b_name', $user->userMeta) }}"
                                                        name="_b_name" maxlength="20" placeholder="WareHub Inc">
                                                </div>
                                            </div>
                                            <!-- col-4 -->
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-control-label active">Business Email:</label>
                                                    <input class="form-control" type="email"
                                                        value="{{ UserClass::getOptionValue('_b_email', $user->userMeta) }}"
                                                        name="_b_email" placeholder="stock@business.com.au">
                                                </div>
                                            </div>
                                            <!-- col-4 -->
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">Business Phone:</label>
                                                    <input class="form-control" type="tel"
                                                        value="{{ UserClass::getOptionValue('_b_number', $user->userMeta) }}"
                                                        name="_b_number" placeholder="6123456789">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-control-label">Business Street Address: <span
                                                            class="tx-danger"></span></label>
                                                    <input class="form-control" type="address"
                                                        name="_b_street_address"
                                                        value="{{ UserClass::getOptionValue('_b_street_address', $user->userMeta) }}"
                                                        placeholder="123 Main Street, Sydney, NSW 2000">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label">Business Area: <span
                                                            class="tx-danger"></span></label>
                                                    <!--<input class="form-control" type="address" name="_b_area_address"-->
                                                    <!--    value="{{ UserClass::getOptionValue('_b_area_address', $user->userMeta) }}"-->
                                                    <!--    placeholder="Sydney, Australia">-->
                                                    <input type="text" name="_b_area_address" class="form-control address-input"
                                                        value="{{ UserClass::getOptionValue('_b_area_address', $user->userMeta) }}" placeholder="Business Address">
                                                    <div class="suggestions" style="position: absolute;
                                                        background: white;
                                                        z-index: 200;
                                                        top: 60px;
                                                        border: 1px solid #ccc;"></div>
                                                    <div class="details"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label">Business Type: <span
                                                            class="tx-danger"></span></label>
                                                    <select name="_b_type" id="_b_type" class="mt-0 form-control">
                                                        <option
                                                            value="{{ UserClass::getOptionValue('_b_type', $user->userMeta) }}"
                                                            selected>
                                                            {{ UserClass::getOptionValue('_b_type', $user->userMeta) == '' ? 'Select Business Type' : UserClass::getOptionValue('_b_type', $user->userMeta) }}
                                                        </option>
                                                        <option value="others">Others</option>
                                                        <option value="Accounting">Accounting</option>
                                                        <option value="Advertising">Advertising</option>
                                                        <option value="Agriculture">Agriculture</option>
                                                        <option value="Automotive">Automotive</option>
                                                        <option value="Beauty and Spa">Beauty and Spa</option>
                                                        <option value="Catering">Catering</option>
                                                        <option value="Consulting">Consulting</option>
                                                        <option value="Design">Design</option>
                                                        <option value="Education">Education</option>
                                                        <option value="Engineering">Engineering</option>
                                                        <option value="Fashion">Fashion</option>
                                                        <option value="Fitness">Fitness</option>
                                                        <option value="Food Services">Food Services</option>
                                                        <option value="Healthcare">Healthcare</option>
                                                        <option value="Hospitality">Hospitality</option>
                                                        <option value="Information Technology">Information Technology
                                                        </option>
                                                        <option value="Insurance">Insurance</option>
                                                        <option value="Legal Services">Legal Services</option>
                                                        <option value="Manufacturing">Manufacturing</option>
                                                        <option value="Marketing">Marketing</option>
                                                        <option value="Media and Entertainment">Media and Entertainment
                                                        </option>
                                                        <option value="Non-profit">Non-profit</option>
                                                        <option value="Real Estate">Real Estate</option>
                                                        <option value="Retail">Retail</option>
                                                        <option value="Transportation">Transportation</option>
                                                        <option value="Travel and Tourism">Travel and Tourism</option>
                                                        <option value="Wholesale">Wholesale</option>
                                                        <option value="Architecture">Architecture</option>
                                                        <option value="Bakery">Bakery</option>
                                                        <option value="Childcare">Childcare</option>
                                                        <option value="Dentistry">Dentistry</option>
                                                        <option value="E-commerce">E-commerce</option>
                                                        <option value="Event Planning">Event Planning</option>
                                                        <option value="Graphic Design">Graphic Design</option>
                                                        <option value="Interior Design">Interior Design</option>
                                                        <option value="Landscaping">Landscaping</option>
                                                        <option value="Photography">Photography</option>
                                                        <option value="Public Relations">Public Relations</option>
                                                        <option value="Renewable Energy">Renewable Energy</option>
                                                        <option value="Software Development">Software Development
                                                        </option>
                                                        <option value="Sports and Recreation">Sports and Recreation
                                                        </option>
                                                        <option value="Telecommunications">Telecommunications</option>
                                                        <option value="Travel Agency">Travel Agency</option>
                                                        <option value="Veterinary">Veterinary</option>
                                                        <option value="Waste Management">Waste Management</option>
                                                        <option value="Web Hosting">Web Hosting</option>
                                                        <option value="Wedding Planning">Wedding Planning</option>
                                                    </select>
                                                </div>
                                            </div>
                                            @if (UserClass::getOptionValue('_b_type', $user->userMeta) == 'others')
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Other Business Type: <span
                                                                class="tx-danger"></span></label>
                                                        <input class="form-control" name="_other_b_type"
                                                            value="{{ UserClass::getOptionValue('_other_b_type', $user->userMeta) }}"
                                                            placeholder="Other Business Type" required>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="profile-save-changes">
                                            <button class="btn px-5">Save Changes</button>
                                        </div>
                                    </form>
                                    <hr>
                                    @if (session('success_status'))
                                        <div class="message w-100 mb-3"
                                            style="background-color: #88f56b!important; padding: 5px 10px;">
                                            <span><strong>{{ session('message') }}</strong></span>
                                        </div>
                                    @elseif (session('error_status'))
                                        <div class="message w-100 mb-3"
                                            style="background-color: #fa6a78!important; padding: 5px 10px;">
                                            <span><strong>{{ session('message') }}</strong></span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Shipping Addresses Tab Content Starts --}}
                        <div class="tab-pane fade {{ request()->is('shipping-addresses') ? 'show active' : '' }}"
                            id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0 shipping_addres">Shipping Addresses</h3>
                                            <button class="btn btn-success" id="showModalBtn">+ Add
                                                Address</button>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        @foreach ($addresses as $address)
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="card-body profile-password-change-section mb-3">
                                                    <h5 class="card-title">
                                                        {{ $address->f_name . ' ' . $address->l_name }}
                                                    </h5>
                                                    <div class="d-flex justify-content-between">
                                                        <h6 class="card-subtitle mb-2">
                                                            {{ $address->company }}
                                                        </h6>
                                                        <h6 class="card-subtitle mb-2"
                                                            style="text-transform:capitalize; background-color:">
                                                            {{ $address->address_type == 'both' ? 'Billing & Shipping' : $address->address_type }}
                                                        </h6>
                                                    </div>
                                                    <p class="mb-0"><b>Email:</b> {{ $address->email }}</p>
                                                    <p class="mb-0"><b>Phone:</b> {{ $address->phone }}</p>
                                                    <p class="card-text mb-0">
                                                        {{ $address->street_address }}</p>
                                                    <p>{{ $address->city }}, {{ $address->suburb }},
                                                        {{ $address->state }},
                                                        {{ $address->post_code }}</p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <button class="btn btn-warning" type="button"
                                                            onclick="updateAddress({{ $address->id }})">Update</button>
                                                        <button class="btn btn-danger" type="button"
                                                            onclick="deleteAddress({{ $address->id }})">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Website.Includes.footer')
<script>
    const showPasswordCheckbox = document.getElementById("showpassword");
    const passwordFields = document.querySelectorAll(".password-field");
    showPasswordCheckbox.addEventListener("change", function() {
        const fieldType = showPasswordCheckbox.checked ? "text" : "password";
        passwordFields.forEach((field) => {
            field.type = fieldType;
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('form').submit(function() {
            $('#submitButton').prop('disabled', true).text('Loading...');
        });

        $('input[name="last_name"], input[name="first_name"]').on('input', function() {
            var inputValue = $(this).val();
            console.log(inputValue);
            var sanitizedValue = inputValue.replace(/[^A-Za-z ]/g, '');
            $(this).val(sanitizedValue);
        });
    });
</script>
<!-- SCRIPT -->
<script>
    $(document).ready(function() {
        var baseRouteUrl = '{{ url('/') }}';
        $('.tab-link').click(function(e) {
            e.preventDefault();

            var tabId = $(this).attr('href');
            var routeName = $(this).data('route');
            var tabUrl = baseRouteUrl + '/' + routeName;

            history.pushState(null, null, tabId);
            $.ajax({
                url: tabUrl,
                method: 'GET',
                success: function(data) {
                    $(tabId).html(data);
                    history.pushState(null, null, tabUrl);
                    if(routeName == "all-orders" || routeName == "pos-orders" || routeName == 'return-orders'){
                        location.reload();
                    }
                },
                error: function(error) {
                    console.log(JSON.stringify(error));
                    console.error(error);
                }
            });
        });

        $('#showModalBtn').click(function() {
            $('#id').val('');
            $('#user_id').val('');
            $('#f_name').val('');
            $('#l_name').val('');
            $('#company').val('');
            $('#phone').val('');
            $('#street_address').val('');
            $('#post_code').val('');
            $('#city').val('');
            $('#suburb').val('');
            $('#state').val('');
            $('#address_type').val('Select Address Type');
            $('.addressModal').modal('show');
        });
    })

    function deleteAddress(id) {
        alert("Are you sure you want to delete this address");
        $.ajax({
            url: '/destroy-address/' + id,
            type: 'GET', // Use DELETE method
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('Error occurred while deleting the product: ' + xhr.responseText);
            }
        });
    }
</script>
<script>
    $('.changePasswordDiv').hide();
    $(document).ready(function() {
        $('#passwordChangeToggle').click(function() {
            $('.changePasswordDiv').toggle();
            $('html, body').animate({
                scrollTop: $('#passwordChangeToggle').offset().top
            }, 800);
        });

        @if ($errors->has('old_password') || $errors->has('password') || $errors->has('password_confirmation'))
            $('.changePasswordDiv').show();
            $('html, body').animate({
                scrollTop: $('#passwordChangeToggle').offset().top
            }, 800);
        @endif
    });

    setTimeout(() => {
        $('.message').fadeOut();
    }, 5000);
    $('html, body').animate({
        scrollTop: $('.message').offset().top
    }, 1000);
</script>
