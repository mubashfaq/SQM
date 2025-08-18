{{-- resources/views/Admin/Invoices/edit.blade.php --}}
@include('Admin.Includes.header')

@php
    use App\Models\Product;
    use Illuminate\Support\Facades\Auth;
    use App\General\ProductClass;
    use App\General\UserClass;
    $logged_in_user = Auth::user();
    $settings = \App\Models\GeneralSettings::where('option', '_gst_percentage')->first();
    $GST_PERCENTAGE = $settings ? (float) $settings->value : 0;
    $GST_PERCENTAGE = $GST_PERCENTAGE ?? 0;
@endphp

<style>
    /* Modern styling */
    .page-inner {
        border-radius: 0.35rem;
        box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15);
        margin-bottom: 2rem;
    }

    .invoice-header {
        background: linear-gradient(to right, #4e73df, #224abe);
        color: white;
        padding: 1.2rem;
        border-radius: 0.35rem 0.35rem 0 0;
        margin-bottom: 1.5rem;
    }

    /* Form elements styling */
    .form-control-sm {
        border-radius: 0.25rem;
        border: 1px solid #d1d3e2;
    }

    .form-control-sm:focus {
        border-color: #bac8f3;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    /* Section styling */
    .section-heading {
        color: #5a5c69;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e3e6f0;
    }

    html[data-bs-theme="dark"] .section-heading {
        color: #fff;
    }

    html[data-bs-theme="dark"] .total-sub-unit {
        color: #fff;
    }

    html[data-bs-theme="dark"] .fw-semibold {
        color: #fff;
    }

    /* Form groups */
    .form-group {
        margin-bottom: 1rem;
    }

    /* Custom card styling */
    .card {
        border: none;
        box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15);
    }

    /* Invoice table styling */
    .product-table {
        border-radius: 0.35rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15);
    }

    html[data-bs-theme="dark"] label {
        color: #fff;
    }

    /* Product section */
    .product-section {
        border-radius: 0.35rem;
        padding: 0.5rem;
        margin-bottom: 1rem;
    }

    /* Total sections styling */
    .totals-section {
        padding: 1rem;
        margin-top: 1rem;
    }

    /* Custom button styling */
    .btn-invoice-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
    }

    .btn-invoice-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
        color: white;
    }

    /* Preview area */
    .invoice_Area {
        box-shadow: 0 .15rem 0.75rem 0 rgba(58, 59, 69, 0.32);
        background-color: white;
    }

    /* Membership widget enhancements */
    .membership-widget {
        border: 1px solid #d1d3e2;
        padding: 8px;
        margin-bottom: 10px;
        border-radius: 0.35rem;
        background-color: #ffffff;
    }

    .membership-info {
        display: flex;
        align-items: center;
        margin-bottom: 0 !important;
    }

    .product-divider {
        border: 1px solid #e3e6f0;
        margin: 15px 0;
    }

    .membership-title {
        flex-grow: 1;
        margin-left: 14px;
        font-weight: 500;
    }

    .invoice-preview-col {
        padding: 50px;
    }

    /* Custom styling for radio buttons */
    .custom-radio label {
        display: inline-flex;
        align-items: center;
        margin-right: 1rem;
        cursor: pointer;
    }

    /* Custom styling for product selection */
    .product-detail-cell {
        display: flex;
        align-items: center;
        cursor: pointer;
        margin-bottom: 6px;
    }

    .custom-icon {
        cursor: pointer;
        width: 20px;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .page-inner {
            padding: 0.5rem !important;
        }
    }
</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="page-inner pd-0-force mg-0-force">
            <div class="row">
                <div class="col-md-7 px-0 pt-2">
                    <form style="margin-left:30px" id="invoice_form">
                        @csrf
                        <!-- Invoice Header -->
                        <div class="invoice-header bg-gradient">
                            <div class="h4 row">
                                <div class="col-6">Update Invoice</div>
                                <div class="col-6 text-right">#{{ $invoice->invoice_number }}</div>
                            </div>
                        </div>

                        <!-- Customer Section -->
                        <div class="customer-section mb-4">
                            <div class="section-heading">Customer Information</div>

                            <div class="form-group">
                                <label><i class="fas fa-user-tag me-2"></i>Select Customer Type</label>
                                <div class="custom-radio mt-2">
                                    <label><input type="radio" name="customer_type" value="existing"
                                            @if ($invoice->customer_type == 'existing' || $invoice->customer_type == '') checked @endif required>
                                        <span class="ml-2">Existing Customer</span></label>
                                    <label class="d-none"><input type="radio" name="customer_type" value="random"
                                            @if ($invoice->customer_type == 'random') checked @endif required>
                                        <span class="ml-2">Random Customer</span></label>
                                </div>
                            </div>

                            <div class="form-group" id="existing-customer-section">
                                <label for="existingCustomerSelect"><i class="fas fa-users me-2"></i>Select
                                    Customer</label>
                                <select class="form-control form-control-sm select2-demo" name="customer"
                                    @if ($invoice->customer_type == 'random') disabled @endif id="existingCustomerSelect"
                                    required>
                                    @if (count($users) > 0)
                                        <option>Select Customer</option>
                                        @foreach ($users as $user)
                                            @if (UserClass::check_role($user->role_id, $roles) == 'Customer')
                                                <option value="{{ $user->id }}"
                                                    @if ($invoice->customer_type == 'existing' && $invoice->customer == $user->id) selected @endif>
                                                    {{ $user->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @else
                                        <option>No Customer Found</option>
                                    @endif
                                </select>
                                @if ($invoice->active == 0)
                                    <div class="text-right mt-3">
                                        @if (Session::has('permissions') && in_array('Add New Customer', Session::get('permissions')))
                                            <button class="btn btn-success btn-sm rounded-pill px-3 py-1 shadow-sm"
                                                data-bs-toggle="modal" data-bs-target="#add_customer_model"
                                                type="button">
                                                <i class="fas fa-user-plus me-1"></i> Add New Customer
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="form-group" id="random-customer-section">
                                <label for="randomCustomerName"><i class="fas fa-user me-2"></i>Customer Name</label>
                                <input type="text" class="form-control form-control-sm" name="customer"
                                    value="{{ $invoice->customer }}" @if ($invoice->customer_type == 'existing' || $invoice->customer_type == '') disabled @endif
                                    id="randomCustomerName">
                            </div>
                        </div>

                        <!-- Product Selection Section -->
                        <div class="section-heading">Product Information</div>

                        <div class="form-group">
                            <label><i class="fas fa-barcode me-2"></i>Product Selection Method</label>
                            <div class="custom-radio mt-2">
                                <label><input type="radio" name="product_scanning_type" value="barcode">
                                    <span class="ml-2">Barcode Scanner</span></label>
                                <label><input type="radio" name="product_scanning_type" value="manual" checked>
                                    <span class="ml-2">Manual</span></label>
                            </div>
                        </div>

                        <div class="form-group manual_product_search">
                            <label for="manual_product_details"><i class="fas fa-search me-2"></i>Search
                                Products</label>
                            <input type="search" class="form-control form-control-sm" id="manual_product_details"
                                placeholder="Type product name or code...">
                            <div class="my-2 searched_product_Section"
                                style="max-height:150px !important;overflow-y:scroll;border-radius:0.25rem;">
                            </div>
                        </div>

                        <!-- Product Table -->
                        <div class="product-table">
                            <div class="row mx-0 px-0">
                                <div class="col-4 px-0">
                                    <label><i class="fas fa-box me-2"></i>Product</label>
                                </div>
                                <div class="col-2 px-1">
                                    <label><i class="fas fa-weight me-1"></i>Weight / Qty</label>
                                </div>
                                <div class="col-1 px-1">
                                    <label><i class="fas  me-1"></i>Type</label>
                                </div>
                                <div class="col-2 px-1">
                                    <label><i class="fas fa-dollar-sign me-1"></i>Price</label>
                                </div>
                                <div class="col-2 px-1">
                                    <label><i class="fas fa-calculator me-1"></i>Total</label>
                                </div>
                                <div class="col-2 px-1"></div>
                            </div>

                            <div class="product-section">
                                @foreach ($invoice->invoiceMeta as $invoice_item)
                                    @php
                                        $product = Product::find($invoice_item->product_id);
                                        $is_weightMeta = $product->productMeta->firstWhere('option', '_is_weight');
                                        $is_weight = $is_weightMeta ? $is_weightMeta->value : 0; // Handle null case

                                    @endphp
                                    <div class="row mb-1 mx-0 invoice-item" remove_it="{{ $invoice_item->product_id }}"
                                        data-gst="{{ isset($invoice_item->gst_status) ? $invoice_item->gst_status : 'true' }}">
                                        <div class="col-4 px-0">
                                            <input type="hidden" name="product_id[]"
                                                value="{{ $invoice_item->product_id }}">
                                            <input type="hidden" class="product_default_weight"
                                                value="{{ $invoice_item->default_weight ?? ($invoice_item->default_weight ?? 1) }}">
                                            <input type="text" name="product_name[]"
                                                class="form-control form-control-sm"
                                                value="{{ $invoice_item->product_name }}" readonly>
                                        </div>
                                        <div class="col-2 px-1">
                                            <input type="number" min="{{ $is_weight == 1 ? '0.1' : '1' }}"
                                                step="{{ $is_weight == 1 ? '0.1' : '1' }}" name="weight[]"
                                                value="{{ $invoice_item->units }}"
                                                class="form-control form-control-sm product_weight_input"
                                                data-is-weight="{{ $is_weight }}"
                                                oninput="updateWeightPricing(this)">
                                        </div>
                                      
                                        <div class="col-1 px-1 price_section">
                                            <span>{{ $is_weight == 1 ? 'Kg' : 'Item' }}</span>
                                        </div>
                                        <div class="col-2 px-1 price_section">
                                            <input type="number" name="price_for_entire_weight[]" readonly
                                                class="form-control form-control-sm product_price_input"
                                                value="{{ $invoice_item->unit_price }}">
                                        </div>
                                        <div class="col-2 px-1 item_total_section">
                                            <input type="number" name="total_price[]"
                                                value="{{ $invoice_item->unit_total_price }}" readonly
                                                class="form-control form-control-sm product_item_total">
                                        </div>
                                        <div class="col-1 px-1 text-right">
                                            <button class="btn btn-sm btn-danger" type="button"
                                                onclick="remove_invoice_item(this)">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="totals-section p-4 bg-light rounded shadow-sm">
                            <!-- Total Units and Sub Total -->
                            <div class="row mb-3 mx-0 align-items-center">
                                <div class="col-6 px-0 py-1 text-right">
                                    <span class="fw-bold darrk_edit_total fs-5">Sub Total($): &nbsp;</span>
                                </div>
                                <div class="col-6 px-0">
                                    <input type="number" value="{{ $invoice->sub_total ?? 0 }}" name="sub_total"
                                        readonly class="form-control form-control-sm rounded-0 invoice_sub_total">
                                </div>
                            </div>

                            <!-- GST and Grand Total Section -->
                            <div class="row mb-2 mx-0">
                                <div class="col-6 px-0 py-1 text-right">
                                    <span class="fw-bold darrk_edit_total fs-5">GST:
                                        &nbsp;</span>
                                </div>
                                <div class="col-6 px-0">
                                    <input type="number" value="{{ $invoice->gst ?? 0 }}" name="gst"
                                        min="0" step="0.01"
                                        class="form-control form-control-sm rounded-0 gst_input" readonly>
                                </div>
                            </div>

                            <div class="row mx-0">
                                <div class="col-6 px-0 py-1 text-right">
                                    <span class="fw-bold darrk_edit_total fs-5">Grand Total ($): &nbsp;</span>
                                </div>
                                <div class="col-6 px-0">
                                    <input type="number" value="{{ $invoice->grand_total ?? 0 }}"
                                        name="grand_total" min="0" step="0.01"
                                        class="form-control form-control-sm rounded-0 grand_total_input fw-bold text-primary"
                                        readonly>
                                </div>
                            </div>


                        </div>

                        <!-- Invoice Details Section -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-gradient bg-light">
                                <h5 class="section-heading mt-2 mb-2 fw-bold light:text-dark dark:text-white"><i
                                        class="fas fa-file-invoice me-2"></i>Invoice Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row my-3 mx-0">
                                    <div class="col-6 col-md-4">
                                        <div class="form-group mb-3">
                                            <label class="light:text-dark dark:text-whitemb-2"><i
                                                    class="far fa-calendar-alt me-2 light:text-dark dark:text-white"></i>Invoice
                                                Date:</label>
                                            <input type="date" readonly name="invoice_date"
                                                value="{{ $invoice->invoice_date }}" id="invoice_date"
                                                class="form-control form-control-sm border-start-0 border-top-0 border-end-0 rounded-0">
                                        </div>

                                        <div class="form-group">
                                            <label class="light:text-dark dark:text-white mb-2 "><i
                                                    class=""></i>Active Status:</label>
                                            <select name="active" class="form-select form-select-sm rounded-0"
                                                id="active_check" required>
                                                <option value="0"
                                                    @if ($invoice->active == 0) selected @endif>Draft</option>
                                                <option value="1"
                                                    @if ($invoice->active == 1) selected @endif>Public</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-4">
                                        <div class="form-group">
                                            <label class="light:text-dark dark:text-white mb-2"><i
                                                    class="far fa-calendar-check me-2 light:text-dark dark:text-white"></i>Invoice
                                                Due Date:</label>
                                            <input type="date" name="due_date" value="{{ $invoice->due_date }}"
                                                id="due_date"
                                                class="form-control form-control-sm border-start-0 border-top-0 border-end-0 rounded-0">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="card bg-light border-0 mb-3">
                                            <div class="card-body py-2">
                                                <div class="text-right mb-2">
                                                    <label class="btn btn-danger btn-sm me-2 mb-3 md:mb-0">
                                                        <input type="radio" name="payment_status" value="Un-Paid"
                                                            @if ($invoice->payment_status == 'Un-Paid') checked @endif>
                                                        <span class="ms-1">Un-Paid</span>
                                                    </label>
                                                    <label class="btn btn-success btn-sm" style=" margin-top: -15px;">
                                                        <input type="radio" name="payment_status" value="Paid"
                                                            @if ($invoice->payment_status == 'Paid') checked @endif>
                                                        <span class="ms-1">Paid</span>
                                                    </label>
                                                </div>

                                                <div class="payment-details">
                                                    <label class="light:text-dark dark:text-white mb-2"><i
                                                            class="fas fa-money-check-alt me-2 light:text-dark dark:text-white"></i>Payment
                                                        Method: </label>
                                                    <div class="text-right mb-3">
                                                        <label class="btn btn-secondary btn-sm me-1 mb-3 md:mb-0">
                                                            <input type="radio" name="payment_method"
                                                                value="Bank Transfer"
                                                                @if ($invoice->payment_method == 'Bank Transfer') checked @endif>
                                                            <span class="ms-1">Bank Transfer</span>
                                                        </label>
                                                        <label class="btn btn-secondary btn-sm me-1 mb-3 md:mb-0">
                                                            <input type="radio" name="payment_method"
                                                                value="Cash"
                                                                @if ($invoice->payment_method == 'Cash') checked @endif>
                                                            <span class="ms-1">Cash</span>
                                                        </label>
                                                        <label class="btn btn-secondary btn-sm mt-3">
                                                            <input type="radio" name="payment_method"
                                                                value="Credit Card"
                                                                @if ($invoice->payment_method == 'Credit Card') checked @endif>
                                                            <span class="ms-1 ">Credit Card</span>
                                                        </label>
                                                    </div>

                                                    <label class="light:text-dark dark:text-white mb-2"><i
                                                            class="fas fa-sticky-note me-2 light:text-dark dark:text-white"></i>Payment
                                                        Details or Note: </label>
                                                    <input type="text"
                                                        class="form-control form-control-sm border-start-0 border-top-0 border-end-0 rounded-0"
                                                        name="payment_id" value="{{ $invoice->payment_id }}"
                                                        placeholder="Reference: #NBS-123SDNA231">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="text-right mg-y-20 py-2">
                                    @if ($invoice->active == 0)
                                        @if (Session::has('permissions') && in_array('Cancel Invoice', Session::get('permissions')))
                                            <button type="button" class="btn btn-danger mg-t-5 destroy-invoice"
                                                action-url="{{ route('invoices.destroyJson', ['id' => $invoice->id]) }}">
                                                <i class="fas fa-times-circle me-1"></i>Cancel
                                            </button>
                                        @endif
                                        @if (Session::has('permissions') && in_array('Save Invoice', Session::get('permissions')))
                                            <button type="submit" class="btn btn-success mg-t-5" id="submit_button">
                                                <i class="fas fa-save me-1"></i>Save Changes
                                            </button>
                                        @endif
                                    @endif
                                    @if ($invoice->active == 1)
                                        <div class="alert alert-warning mt-3 mb-0" id="public_note">
                                            <i class="fas fa-exclamation-triangle me-1"></i> This Invoice is Public,
                                            you cannot make changes to the Invoice but you can print Invoice ðŸ˜ƒ,
                                            <a href="{{ url('/') }}/admin/invoices/create"
                                                class="alert-link">Create New Invoice</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Invoice Preview Section -->
                <div class="col-md-5 invoice-preview-col">
                    <div class="card pd-10 wd-100p m-auto invoice_Area p-3" style="zoom:70%">
                        @php print($Invoice_review); @endphp
                    </div>

                    <div class="text-right mt-4 mx-2">
                        @if (Session::has('permissions') && in_array('Print Invoice', Session::get('permissions')))
                            <button type="button" class="btn btn-warning mg-t-5" onclick="printInvoice();">
                                <i class="fa fa-print me-1"></i> Print Invoice
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Customer Modal -->
        <div class="modal fade" id="add_customer_model" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="add_customer_modelLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="add_customer_modelpLabel">Add New Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="create_user_account">
                            @csrf
                            <input type="hidden" name="created_by" value="{{ $logged_in_user->id }}">
                            <div class="form-layout form-layout-1">
                                <h4>Account Info</h4>
                                <div class="row mg-b-25">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label active">User Name: <span
                                                    class="tx-danger">*</span></label>
                                            <input class="form-control" type="text" name="name"
                                                placeholder="Enter User Name" required>
                                        </div>
                                    </div>
                                    <!-- col-4 -->
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label active">User Email: <span
                                                    class="tx-danger">*</span></label>
                                            <input class="form-control" type="email" name="email"
                                                placeholder="Enter email address" required>
                                        </div>
                                    </div>
                                    <!-- col-4 -->
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">User Password: <span
                                                    class="tx-danger">*</span></label>
                                            <div class="position-relative">
                                                <input class="form-control" id="password" type="password"
                                                    name="password" placeholder="Enter Password" required>
                                                <i class="bi bi-eye-slash position-absolute" id="toggle-password"
                                                    style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">User Phone: <span
                                                    class="tx-danger">*</span></label>
                                            <input class="form-control" type="tel" name="phone"
                                                placeholder="Enter Phone Number" required>
                                        </div>
                                    </div>
                                    <!-- col-4 -->
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">User Role: <span
                                                    class="tx-danger">*</span></label>
                                            <select class="form-control" required name="role_id"
                                                data-placeholder="Select User Role" tabindex="-1"
                                                aria-hidden="true">
                                                <option label="Choose User Role" value=""></option>
                                                @foreach ($roles as $role)
                                                    @if ($role->id == 1)
                                                        @if ($logged_in_user->role_id == 1)
                                                            <option value="{{ $role->id }}"
                                                                @if ($role->title == 'Customer') selected @endif>
                                                                {{ $role->title }}</option>
                                                        @endif
                                                    @elseif($role->id != 1)
                                                        <option value="{{ $role->id }}"
                                                            @if ($role->title == 'Customer') selected @endif>
                                                            {{ $role->title }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- col-4 -->
                                </div>
                                <hr>
                                <h4>Business Info</h4>
                                <div class="row mg-b-25">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label active">Business Name:</label>
                                            <input class="form-control" type="text" name="business_name"
                                                placeholder="WareHub Inc">
                                        </div>
                                    </div>
                                    <!-- col-4 -->
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label active">Business Email:</label>
                                            <input class="form-control" type="email" name="business_email"
                                                placeholder="stock@business.com.au">
                                        </div>
                                    </div>
                                    <!-- col-4 -->
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Business Phone:</label>
                                            <input class="form-control" type="tel" name="business_phone"
                                                placeholder="6123456789">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Business Street Address: <span
                                                    class="tx-danger"></span></label>
                                            <input class="form-control address-input" type="address"
                                                name="business_street_address"
                                                placeholder="123 Main Street, Sydney, NSW 2000">
                                            <div class="suggestions" style="background:white;"></div>
                                            <div class="details"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Business Area: <span
                                                    class="tx-danger"></span></label>
                                            <input class="form-control address-input" type="address"
                                                name="area_address" placeholder="Sydney, Australia">
                                            <div class="suggestions" style="background:white;"></div>
                                            <div class="details"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- row -->
                                <div class="form-layout-footer">
                                    <hr>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                            name="confirmation_email_sending" id="customCheck2">
                                        <label class="custom-control-label" for="customCheck2"> Would you like to send
                                            login details email to user?</label>
                                    </div>
                                    <hr>
                                    <button class="btn btn-success" type="submit">Save</button>
                                    <div class="response_section"></div>
                                </div>
                                <!-- form-layout-footer -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('Admin.Includes.footer')

<script>
    document.getElementById('toggle-password').addEventListener('click', function() {
        var passwordField = document.getElementById('password');
        var icon = document.getElementById('toggle-password');

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            passwordField.type = "password";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    });

    $('#create_user_account').on('submit', function(e) {
        e.preventDefault();
        $('.response_section').html(`<div class="alert alert-info mt-1">Processing...</div>`);
        var formData = $(this).serialize();
        $.ajax({
            url: "{{ route('users.store') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                switch (response.status) {
                    case 200:
                        $('.response_section').html(
                            `<div class="alert alert-success mt-1">${response.message}</div>`);
                        $('input[name=password], input[name=name], input[name=phone], input[name=_b_name], input[name=_b_email], input[name=_b_number], input[name=_b_street_address], input[name=_b_area_address]')
                            .val('');
                        var customerSelect = $('#existingCustomerSelect');
                        customerSelect.empty();
                        $.each(response.customers, function(index, customer) {
                            var option = $('<option>', {
                                value: customer.id,
                                text: customer.name
                            });
                            customerSelect.append(option);
                        });
                        setTimeout(function() {
                            $('#add_customer_model').modal('hide');
                        }, 5000);
                        break;
                    case 500:
                        $('.response_section').html(
                            `<div class="alert alert-danger mt-1">${response.message}</div>`);
                        break;
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status == 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorList = '<ul>';
                    for (let field in errors) {
                        errorList += '<li>' + errors[field][0] + '</li>';
                    }
                    errorList += '</ul>';
                    $('.response_section').html('<div class="alert alert-danger mt-1">' +
                        errorList + '</div>');
                } else {
                    $('.response_section').html(
                        '<div class="alert alert-danger mt-1">Server Error, Try Again Later!</div>'
                    );
                }
            }
        });
    });

    $(document).ready(function() {
        $('input[name="payment_status"]').change(function() {
            if ($(this).val() === 'Paid') {
                $('.defualt_hide').slideDown();
            } else {
                $('.defualt_hide').slideUp();
            }
        });
    });

    $(document).ready(function() {
        var currentDate = new Date().toISOString().split('T')[0];
        document.getElementById('invoice_date').value = currentDate;
        document.getElementById('due_date').value = currentDate;

        $('#random-customer-section').hide();
        $('.manual_product_search').show();
        $('input[name="customer_type"]').change(function() {
            if ($(this).val() === 'existing') {
                $('#random-customer-section').hide();
                $('#randomCustomerName').prop('disabled', true);
                $('#existingCustomerSelect').prop('disabled', false);
                $('#existing-customer-section').fadeIn();
            } else if ($(this).val() === 'random') {
                $('#existing-customer-section').hide();
                $('#existingCustomerSelect').prop('disabled', true);
                $('#randomCustomerName').prop('disabled', false);
                $('#random-customer-section').fadeIn();
            }
        });
        $('input[name="product_scanning_type"]').change(function() {
            if ($(this).val() === 'barcode') {
                $('.manual_product_search').hide();
                $('#manual_product_details').prop('disabled', true);
            } else if ($(this).val() === 'manual') {
                $('.manual_product_search').fadeIn();
                $('#manual_product_details').prop('disabled', false);
            }
        });

        $('#existingCustomerSelect').change(function() {
            $('.plan_id').val(0);
            $('.membership_discount').val(0);
            var discounted_amount = $('.discounted_total_input').val();
            $('.membership_discounted_value').val(discounted_amount);
            // GST_Calculate();

            var customer_id = $(this).val();
            $('.memberships_discount_section').html('');
            $('.memberships_discount_section').hide();
            $.ajax({
                url: '/admin/memberships/plans/subscriber?customer_id=' + customer_id,
                type: 'GET',
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.plans.length > 0) {
                        $('.memberships_discount_section').show();
                        for (var i = 0; i < response.plans.length; i++) {
                            $('.memberships_discount_section').append(`
                                <div class="membership-widget" onclick="calculate_member_ship_discount(${response.plans[i].discount_percentage})">
                                    <label class="membership-info">
                                        <input type="radio" name="membership_discount_percentage" plan_id="${response.plans[i].plan_id}" class="member_check" value="${response.plans[i].discount_percentage}">
                                        <div class="membership-title">
                                            <div class="my-0" style="line-height: 100%;"><strong class="my-0">${response.plans[i].plan_name}</strong>
                                            </div>
                                        </div>
                                        <div class="membership-percentage">
                                            <span>${response.plans[i].discount_percentage}%</span>
                                        </div>
                                    </label>
                                </div>
                            `);
                        }
                        $('.memberships_discount_section').append(`
                                <div class="text-end mt-2">
                                    <button id="clear-membership-btn" class="btn btn-outline-danger btn-sm" onclick="clear_membership()">
                                        <i class="fas fa-times-circle"></i> Clear Discount
                                    </button>
                                </div>
                        `);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert('Error Fetching Plans: ' + errorThrown);
                }
            });
        });

        // Add event listeners for real-time updates
        $('.member_check').on('change', function() {
            calculate_member_ship_discount($(this).val());
        });
    });

    $(function() {
        $('.select2-demo').each(function() {
            $(this)
                .wrap('<div class="position-relative"></div>')
                .select2({
                    placeholder: 'Select value',
                    dropdownParent: $(this).parent()
                });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('manual_product_details');
        const resultsSection = document.querySelector('.searched_product_Section');

        searchInput.addEventListener('input', function() {
            const search_query = searchInput.value.trim();
            if (search_query === '') {
                resultsSection.innerHTML = '';
                return;
            }

            fetch(`/admin/invoices/products/search?query=${search_query}`)
                .then(response => response.json())
                .then(data => {
                    const products = data.products;
                    console.log(products);
                    resultsSection.innerHTML = '';

                    products.forEach(product => {
                        const productHtml = `
                           <div class="product-detail-cell row">
                                <div class="col-2 d-flex align-items-center justify-content-center">
                                    <div class="back_image_icon" style="background: url({{ url('/') }}/${product.featured_image}); background-size: contain; background-position: center; height: 35px; width: 35px;"></div>
                                </div>
                                <div class="col-3 d-flex align-items-center">
                                    <span class="product-title">${product.title}</span>
                                </div>
                                <div class="col-3 d-flex align-items-center">
                                    <span class="product-title sku">${product.sku}</span>
                                </div>
                                
                                <div class="col-4 d-flex justify-content-center">
                                    <img src="/AdminAssets/assets/images/plus-icon.png" class="custom-icon add-product-icon h1" onclick="add_to_invoice_method(${product.id})" />
                                </div>
                               
                            </div>
                            <hr class="product-divider">
                        `;
                        resultsSection.insertAdjacentHTML('beforeend', productHtml);
                    });
                })
                .catch(error => console.error('Error fetching search results:', error));
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const weightInputs = document.querySelectorAll('.product_weight_input');
        weightInputs.forEach(function(input) {
            applyWeightInputValidation(input);
        });
    });

    function add_to_invoice_method(product_id) {
        $(`[remove_it="${product_id}"]`).remove();

        fetch(`/admin/invoices/products/select?product_id=${product_id}`)
            .then(response => response.json())
            .then(data => {
                console.log("Fetched product data:", data);

                let defaultPrice = data.meta ? parseFloat(data.meta.value) : 0;
                let discountData = data.discount_data;
                let discountedPrice = defaultPrice;
                if (discountData && discountData.discounted_price) {
                    discountedPrice = parseFloat(discountData.discounted_price);
                }

                let defaultWeight = data.weight ? parseFloat(data.weight.value) : 1;
                let productTitle = data.product.title;
                let hasGST = data.gst;
                let per_kg_price = defaultPrice / defaultWeight;
                let is_weight = data.is_weight ? parseFloat(data.is_weight.value) : 1;
                let weightMin = is_weight == 1 ? '0.1' : '1';
                let weightStep = is_weight == 1 ? 'any' : '1';
                let type = is_weight == 1 ? 'Kg' : 'Item';

                $('.product-section').append(`
                <div class="invoice-item-wrapper" remove_it="${product_id}">
                    <div class="row mb-1 mx-0 invoice-item" data-gst="${hasGST}">
                        <input type="hidden" name="product_id[]" value="${product_id}">
                        <input type="hidden" name="default_weight[]" class="product_default_weight" value="${defaultWeight}">
                        <input type="hidden" name="gst_status[]" value="${hasGST}">
                        <input type="hidden" name="per_kg_price[]" class="per_kg_price" value="${per_kg_price}">

                        <div class="col-4 px-0">
                            <input type="text" name="product_name[]" class="form-control form-control-sm" value="${productTitle}" readonly>
                        </div>

                        <div class="col-2 px-1">
                            <input type="number" min="${weightMin}" step="${weightStep}" name="weight[]" 
                                   class="form-control form-control-sm product_weight_input" 
                                   data-is-weight="${is_weight}"
                                   oninput="updateWeightPricing(this)" value="${defaultWeight}">
                        </div>
                            
                        <div class="col-1 px-1">
                        <span>${type}</span>
                        </div>
                        <div class="col-2 px-1">
                            <input type="number" step="0.01" name="price_for_entire_weight[]" class="form-control form-control-sm product_price_input" oninput="updateWeightPricing(this)" value="${discountedPrice}" readonly>
                        </div>

                        <div class="col-2 px-1">
                            <input type="number" step="0.01" name="total_price[]" class="form-control form-control-sm product_item_total" value="0" readonly>
                        </div>

                             <div class="col-1 px-1 text-right">
                            <button class="btn btn-sm btn-danger" type="button" onclick="remove_invoice_item(this)">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            `);

                updateWeightPricing($('.product_weight_input').last()[0]);

                // Apply validation to the newly added input
                applyWeightInputValidation($('.product_weight_input').last()[0]);
            })
            .catch(error => console.error('Error fetching product data:', error));
    }

    function updateWeightPricing(elem) {
        let row = $(elem).closest('.invoice-item');
        let userWeight = parseFloat(row.find('.product_weight_input').val()) || 0;
        let priceForDefaultWgt = parseFloat(row.find('.product_price_input').val()) || 0;
        let productDefWgt = parseFloat(row.find('.product_default_weight').val()) || 1;

        if (productDefWgt <= 0) {
            alert("Invalid product default weight!");
            return;
        }
        let pricePerUnitWeight = priceForDefaultWgt / productDefWgt;
        let totalPrice = userWeight * pricePerUnitWeight;
        row.find('.product_item_total').val(totalPrice.toFixed(2));

        updateInvoiceTotals();
    }

    function remove_invoice_item(button) {
        $(button).closest('.invoice-item').remove();
        updateInvoiceTotals();
    }

    function updateInvoiceTotals() {
        let taxableSubtotal = 0;
        let nonTaxableSubtotal = 0;
        let gstRate = parseFloat('{{ $GST_PERCENTAGE }}') || 0;

        $('.invoice-item').each(function() {
            let productTotal = parseFloat($(this).find('.product_item_total').val()) || 0;
            let productHasGST = $(this).data('gst');
            productHasGST = (productHasGST === true || productHasGST === "true");

            if (productHasGST) {
                taxableSubtotal += productTotal;
            } else {
                nonTaxableSubtotal += productTotal;
            }
        });

        let subtotal = taxableSubtotal + nonTaxableSubtotal;
        $('.invoice_sub_total').val(subtotal.toFixed(2));

        let gstAmount = 0;
        if (gstRate > 0) {
            gstAmount = (taxableSubtotal * gstRate) / 100;
        }
        $('.gst_input').val(gstAmount.toFixed(2));

        let grandTotal = subtotal + gstAmount;
        $('.grand_total_input').val(grandTotal.toFixed(2));

        // Membership discount calculation
        calculate_member_ship_discount();
    }

    // Function to apply validation to weight inputs
    function applyWeightInputValidation(input) {
        // Keypress event to prevent decimal point entry for items
        input.addEventListener('keypress', function(e) {
            const isWeight = parseInt(this.dataset.isWeight);
            // If not weight-based (items), prevent decimal point entry
            if (isWeight !== 1) {
                if (e.key === '.' || e.key === ',') {
                    e.preventDefault();
                }
            }
        });

        // Input event to remove decimal values for items
        input.addEventListener('input', function() {
            const isWeight = parseInt(this.dataset.isWeight);
            const value = this.value;
            // For items, validate no decimal values
            if (isWeight !== 1 && (value.includes('.') || value.includes(','))) {
                // Remove decimal part
                this.value = Math.floor(parseFloat(value)) || '';
            }
        });
    }

    function calculate_member_ship_discount(discount_value) {
        var discounted_amount = parseFloat($('.invoice_sub_total').val()) || 0;
        var member_ship_discount = (discounted_amount * parseFloat(discount_value) / 100);

        $('.membership_discount').val(member_ship_discount.toFixed(2));
        var membership_discounted_value = discounted_amount - member_ship_discount;
        $('.membership_discounted_value').val(membership_discounted_value.toFixed(2));
    }

    function clear_membership() {
        $('.member_check').prop('checked', false);
        $('.membership_discount').val('0.00');
        $('.membership_discounted_value').val($('.discounted_total_input').val());

        $('#clear-membership-btn').html('<i class="fas fa-check-circle"></i> Cleared').addClass('btn-success')
            .removeClass('btn-outline-danger');
        setTimeout(function() {
            $('#clear-membership-btn').html('<i class="fas fa-times-circle"></i> Clear Discount').addClass(
                'btn-outline-danger').removeClass('btn-success');
        }, 1500);
    }

    $('.destroy-invoice').click(function() {
        let action_url = $(this).attr('action-url');
        console.log(action_url, 'action_url');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the invoice.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: action_url,
                    type: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Invoice deleted successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '/admin/invoices';
                        });
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error deleting invoice: ' + errorThrown
                        });
                    }
                });
            }
        });
    });

    $('#invoice_form').submit(function(event) {
        event.preventDefault();

        if ($('.invoice-item').length == 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Product Added',
                text: 'Please add a product in Invoice to save changes.',
                confirmButtonText: 'OK'
            });
        } else {
            var formData = $(this).serialize();
            var invoiceId = '{{ $invoice->id }}';

            if ($("#active_check").val() == 1) {
                Swal.fire({
                    title: 'Make Invoice Public?',
                    text: "It will update store stock and you cannot make changes anymore!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, make it public',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('invoices.update', ['invoice' => ':invoiceId']) }}'
                                .replace(':invoiceId', invoiceId),
                            type: 'PUT',
                            data: formData,
                            success: function(response) {
                                $('.invoice_Area').html(response.invoice_review);
                                $("#invoice_form :input").prop("disabled", true);
                                $('.destroy-invoice, #submit_button').fadeOut();
                                $('#public_note').removeClass('d-none');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Invoice saved successfully!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error saving invoice',
                                    text: errorThrown
                                });
                            }
                        });
                    }
                });
            } else {
                $.ajax({
                    url: '{{ route('invoices.update', ['invoice' => ':invoiceId']) }}'.replace(
                        ':invoiceId', invoiceId),
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('.invoice_Area').html(response.invoice_review);
                        $('.destroy-invoice').fadeOut();
                        Swal.fire({
                            icon: 'success',
                            title: 'Invoice saved successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error saving invoice',
                            text: errorThrown
                        });
                    }
                });
            }
        }
    });

    function printInvoice() {
        var invoiceArea = document.querySelector('.invoice_Area');
        var printWindow = window.open('', '', 'width=1200,height=720');

        printWindow.document.open();
        printWindow.document.write(
            '<html><head><title>Print Invoice - {{ $invoice->invoice_number }}</title><link href="{{ url('/') }}/AdminAssets/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"></head><body>'
        );
        printWindow.document.write('<div class="invoice_Area">' + invoiceArea.innerHTML + '</div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        printWindow.print();
    }
</script>

<audio id="beepSound" preload="auto">
    <source src="{{ url('/') }}/sounds/beep.wav" type="audio/mpeg">
</audio>
