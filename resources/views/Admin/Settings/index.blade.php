@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\GeneralSettingsClass;
@endphp

<div class="page-content">
    <div class="container-fluid">
        <!-- Page Inner Start -->
        <div class="page-inner">
            <!-- Breadcrumb -->
            <div class="card shadow-sm mb-4">

                <div class="  p-3 mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="fs-4 fw-bold h3 mb-1">{{ __('General Settings') }}</h1>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/') }}/admin" class=" link-primary text-decoration-none">
                                        <i class="fa-solid fa-home me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    General Settings
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- /Breadcrumb -->

            <!-- Content Area -->
            <div class="col-md-12 col-lg-12 px-0">
                <!-- Alerts Section -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            <li><i class="fas fa-info-circle me-1"></i>If you have already selected logo Images, please
                                select them again!</li>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Main Form -->
                <form id="general-settings-form" method="POST" action="{{ route('store-general-setting') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST');
                    <div class="row">
                        <!-- Left Column - Main Settings -->
                        <div class="col-lg-8">
                            <!-- Website View Settings -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header   py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-title fw-bold mb-0"><i
                                                class="fas fa-desktop me-2"></i>{{ __('Website View Settings') }}</h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-medium">Tagline</label>
                                        <input class="form-control" type="text" name="_tagline"
                                            placeholder="Free shipping across Australia for orders over $4999"
                                            value="{{ GeneralSettingsClass::getOptionValue('_tagline', $settings) }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Header Logo</label>
                                                <input type="file" class="form-control" name="_header_logo"
                                                    id="header_logo_input"
                                                    data-default-file="{{ url('/') . '/' . GeneralSettingsClass::getOptionValue('_header_logo', $settings) }}" />
                                            </div>
                                            <div id="header_image_preview" class="text-center mb-4 p-2 rounded bg-light"
                                                style="height: 5rem; {{ is_null($headerLogo) ? 'display: none;' : '' }}">
                                                <img id="preview_img_header" src="{{ asset($headerLogo) ?? '#' }}"
                                                    class="h-100" alt="Header Logo Preview" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            @php
                                                $favIcon = GeneralSettingsClass::getOptionValue('_fav_icon', $settings);
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Fav Icon</label>
                                                <input type="file" class="form-control" name="_fav_icon"
                                                    id="fav_icon_input"
                                                    data-default-file="{{ url('/') . '/' . $favIcon }}" />
                                            </div>
                                            <div id="fav_icon_preview" class="text-center mb-4 p-2 rounded bg-light"
                                                style="height: 5rem; {{ is_null($favIcon) ? 'display: none;' : '' }}">
                                                <img id="fav_icon_img" src="{{ asset($favIcon) ?? '#' }}"
                                                    class="h-100" alt="Fav Icon Preview" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Header Logo Dark</label>
                                                <input type="file" class="form-control" name="_header_logo_dark"
                                                    id="header_logo_input_dark"
                                                    data-default-file="{{ url('/') . '/' . GeneralSettingsClass::getOptionValue('_header_logo_dark', $settings) }}" />
                                            </div>
                                            <div id="header_image_preview_dark"
                                                class="text-center mb-4 p-2 rounded bg-light"
                                                style="height: 5rem; {{ is_null($headerLogoDark) ? 'display: none;' : '' }}">
                                                <img id="preview_img_header_dark"
                                                    src="{{ asset($headerLogoDark) ?? '#' }}" class="h-100"
                                                    alt="Header Logo Dark Preview" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Footer Logo</label>
                                                <input type="file" class="form-control" name="_footer_logo"
                                                    id="_footer_logo"
                                                    data-default-file="{{ url('/') . '/' . GeneralSettingsClass::getOptionValue('_footer_logo', $settings) }}" />
                                            </div>
                                            <div id="header_image_preview_dark"
                                                class="text-center mb-4 p-2 rounded bg-light"
                                                style="height: 5rem; {{ is_null($footerLogo) ? 'display: none;' : '' }}">
                                                <img id="preview_img_header_dark"
                                                    src="{{ asset($footerLogo) ?? '#' }}" class="h-100"
                                                    alt="Header Logo Dark Preview" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">GST Percentage</label>
                                                <div class="input-group">
                                                    <input class="form-control" 
                                                        type="number" 
                                                        name="_gst_percentage" id="gst-percentage-input"  step="1"  min="0" placeholder="10"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_gst_percentage', $settings) ? GeneralSettingsClass::getOptionValue('_gst_percentage', $settings) : 0 }}">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Delivery Charges</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input class="form-control" type="number"
                                                        name="_delivery_charges" placeholder="10"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_delivery_charges', $settings) ? GeneralSettingsClass::getOptionValue('_delivery_charges', $settings) : '0' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Original Minimum Weight Field -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Minimum Weight</label>
                                                <div class="input-group">
                                                    <input class="form-control" type="number" name="_minimum_weight"
                                                        placeholder="100g" min="100"
                                                        value="{{ $minimumWeightKg }}" id="minimum_weight">
                                                    <span class="input-group-text">gm</span>
                                                </div>
                                            </div>
                                            <span class="weight-error"
                                                style="font-size: 11px; color: #dc3545; display: none;">
                                                Minimum weight should be 0.1 kg
                                            </span>
                                        </div>

                                        <!-- New Minimum Items Field -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Minimum Items</label>
                                                <div class="input-group">
                                                    <input class="form-control" type="number" name="_minimum_items"
                                                        placeholder="1" value="{{ $minimum_items }}"
                                                        id="minimum_items" min="1">
                                                    <span class="input-group-text">items</span>
                                                </div>
                                            </div>
                                            <span class="items-error"
                                                style="font-size: 11px; color: #dc3545; display: none;">
                                                Minimum items should be 1
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" card shadow-sm mb-4">
                                <div class="card-header   py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-title fw-bold mb-0"><i
                                                class="fas fa-credit-card me-2"></i>{{ __('Payment Gateway Settings') }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- PayPal Settings -->
                                    <div class="mb-4 d-none">
                                        <h6 class="fw-bold mb-3 text-primary"><i class="fab fa-paypal me-2"></i>PayPal
                                            Settings</h6>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-medium">PayPal Public Key</label>
                                                    <input class="form-control" type="text"
                                                        name="_paypal_live_key" placeholder="Enter PayPal public key"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_paypal_live_key', $settings) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-medium">Environment</label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="text"
                                                            name="_paypal_live_environment" value="Live" disabled>
                                                        <div class="input-group-text">
                                                            <div class="form-check mb-0">
                                                                <input type="radio" class="form-check-input"
                                                                    name="_active_paypal_environment" id="paypal_live"
                                                                    value="live"
                                                                    {{ GeneralSettingsClass::getOptionValue('_active_paypal_environment', $settings) == 'live' ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="paypal_live">Active</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-medium">PayPal Secret Key</label>
                                                    <input class="form-control" type="text"
                                                        name="_paypal_live_secrete_key"
                                                        placeholder="Enter PayPal secret key"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_paypal_live_secrete_key', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-9">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-medium">PayPal Sandbox Public
                                                        Key</label>
                                                    <input class="form-control" type="text"
                                                        name="_paypal_sandbox_key"
                                                        placeholder="Enter PayPal sandbox public key"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_paypal_sandbox_key', $settings) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-medium">Environment</label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="text"
                                                            name="_paypal_sandbox_environment" value="Sandbox"
                                                            disabled>
                                                        <div class="input-group-text">
                                                            <div class="form-check mb-0">
                                                                <input type="radio" class="form-check-input"
                                                                    name="_active_paypal_environment"
                                                                    id="paypal_sandbox" value="sandbox"
                                                                    {{ GeneralSettingsClass::getOptionValue('_active_paypal_environment', $settings) == 'sandbox' ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="paypal_sandbox">Active</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-medium">PayPal Sandbox Secret
                                                        Key</label>
                                                    <input class="form-control" type="text"
                                                        name="_paypal_sandbox_secrete_key"
                                                        placeholder="Enter PayPal sandbox secret key"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_paypal_sandbox_secrete_key', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Stripe Settings -->
                                    <div class="mt-4">
                                        <h6 class="fw-bold mb-3 text-primary"><i
                                                class="fab fa-stripe-s me-2"></i>Stripe Settings</h6>
                                        <div class="row">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check mb-0">
                                                            <input class="form-control" type="hidden"
                                                                name="_stripe_live_environment" value="Live"
                                                                disabled>
                                                            <input type="radio" class="form-check-input"
                                                                name="_active_stripe_environment" id="stripe_live"
                                                                value="live"
                                                                {{ GeneralSettingsClass::getOptionValue('_active_stripe_environment', $settings) == 'live' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="stripe_live">Live</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input class="form-control" type="hidden"
                                                            name="_stripe_sandbox_environment" value="Sandbox"
                                                            disabled>
                                                        <div class="form-check mb-0">
                                                            <input type="radio" class="form-check-input"
                                                                name="_active_stripe_environment" id="stripe_sandbox"
                                                                value="sandbox"
                                                                {{ GeneralSettingsClass::getOptionValue('_active_stripe_environment', $settings) == 'sandbox' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="stripe_sandbox">Sandbox</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-medium">Stripe Pulish Key</label>
                                                    <input class="form-control" type="text"
                                                        name="_stripe_publish_key"
                                                        placeholder="Enter Stripe public key"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_stripe_publish_key', $settings) }}">
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-medium">Environment</label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="text"
                                                            name="_stripe_live_environment" value="Live" disabled>
                                                        <div class="input-group-text">
                                                            <div class="form-check mb-0">
                                                                <input type="radio" class="form-check-input"
                                                                    name="_active_stripe_environment" id="stripe_live"
                                                                    value="live"
                                                                    {{ GeneralSettingsClass::getOptionValue('_active_stripe_environment', $settings) == 'live' ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="stripe_live">Active</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-medium">Stripe Secrit Key</label>
                                                    <input class="form-control" type="text"
                                                        name="_stripe_secrit_key"
                                                        placeholder="Enter Stripe sandbox key"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_stripe_secrit_key', $settings) }}">
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-medium">Environment</label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="text"
                                                            name="_stripe_sandbox_environment" value="Sandbox"
                                                            disabled>
                                                        <div class="input-group-text">
                                                            <div class="form-check mb-0">
                                                                <input type="radio" class="form-check-input"
                                                                    name="_active_stripe_environment"
                                                                    id="stripe_sandbox" value="sandbox"
                                                                    {{ GeneralSettingsClass::getOptionValue('_active_stripe_environment', $settings) == 'sandbox' ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="stripe_sandbox">Active</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SMTP Settings -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header   py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-title fw-bold mb-0"><i
                                                class="fas fa-envelope me-2"></i>{{ __('SMTP Credentials Settings') }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Mail Mailer</label>
                                                <input class="form-control" type="text" name="_mail_mailer"
                                                    placeholder="smtp"
                                                    value="{{ GeneralSettingsClass::getOptionValue('_mail_mailer', $settings) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Mail Host</label>
                                                <input class="form-control" type="text" name="_mail_host"
                                                    placeholder="smtp.example.com"
                                                    value="{{ GeneralSettingsClass::getOptionValue('_mail_host', $settings) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Mail Port</label>
                                                <input class="form-control" type="text" name="_mail_port"
                                                    placeholder="587"
                                                    value="{{ GeneralSettingsClass::getOptionValue('_mail_port', $settings) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Mail Username</label>
                                                <input class="form-control" type="text" name="_mail_username"
                                                    placeholder="user@example.com"
                                                    value="{{ GeneralSettingsClass::getOptionValue('_mail_username', $settings) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Mail Encryption</label>
                                                <input class="form-control" type="text" name="_mail_encrption"
                                                    placeholder="tls"
                                                    value="{{ GeneralSettingsClass::getOptionValue('_mail_encrption', $settings) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Mail Password</label>
                                                <div class="input-group">
                                                    <input class="form-control" type="password" name="_mail_password"
                                                        placeholder="********" id="password"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_mail_password', $settings) }}">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        id="togglePassword">
                                                        <i class="far fa-eye" id="togglePasswordIcon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Mail From Name</label>
                                                <input class="form-control" type="text" name="_mail_from_name"
                                                    placeholder="Your Company Name"
                                                    value="{{ GeneralSettingsClass::getOptionValue('_mail_from_name', $settings) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Mail From Address</label>
                                                <input class="form-control" type="text" name="_mail_from_address"
                                                    placeholder="no-reply@example.com"
                                                    value="{{ GeneralSettingsClass::getOptionValue('_mail_from_address', $settings) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery Charges Settings -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header   py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-title fw-bold mb-0"><i
                                                class="fas fa-truck me-2"></i>{{ __('Delivery Charges Settings') }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">API Key</label>
                                                <input class="form-control" type="text" name="_delivery_api_key"
                                                    placeholder="Enter delivery API key"
                                                    value="{{ GeneralSettingsClass::getOptionValue('_delivery_api_key', $settings) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Status</label>
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="_delivery_active" id="delivery_active" value="1"
                                                        {{ GeneralSettingsClass::getOptionValue('_delivery_active', $settings) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="delivery_active">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Post Code</label>
                                                <input type="text" class="form-control"
                                                    name="_delivery_from_postcode" placeholder="Enter post code"
                                                    value="{{ GeneralSettingsClass::getOptionValue('_delivery_from_postcode', $settings) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Google Analytics -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header   py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-title fw-bold mb-0"><i
                                                class="fas fa-chart-line me-2"></i>{{ __('Google Analytics') }}</h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Measurement ID</label>
                                                <input class="form-control" type="text"
                                                    name="_analytics_measurement_id" placeholder="G-XXXXXXXXXX"
                                                    value="{{ GeneralSettingsClass::getOptionValue('_analytics_measurement_id', $settings) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Status</label>
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="_measurement_active" id="measurement_active"
                                                        value="1"
                                                        {{ GeneralSettingsClass::getOptionValue('_measurement_active', $settings) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="measurement_active">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Other Settings -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header   py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-title fw-bold mb-0"><i
                                                class="fas fa-cog me-2"></i>{{ __('Other Settings') }}</h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">POS Invoice Note</label>
                                                <textarea class="form-control" rows="5" name="_pos_invoice_note"
                                                    placeholder="Add an additional note for POS Invoice">{{ GeneralSettingsClass::getOptionValue('_pos_invoice_note', $settings) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Online Store Order Note</label>
                                                <textarea class="form-control" rows="5" name="_store_order_note"
                                                    placeholder="Add a note for online store customers">{{ GeneralSettingsClass::getOptionValue('_store_order_note', $settings) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Online Store Return Order
                                                    Note</label>
                                                <textarea class="form-control" rows="5" name="_store_return_order_note"
                                                    placeholder="Add a note for customers returning orders">{{ GeneralSettingsClass::getOptionValue('_store_return_order_note', $settings) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Footer Note</label>
                                                <textarea class="form-control" rows="5" name="_footer_note"
                                                    placeholder="Add a note for the website footer (Note: For line break use <br>)">{{ GeneralSettingsClass::getOptionValue('_footer_note', $settings) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Sidebar Settings -->
                        <div class="col-lg-4">
                            <!-- Color Settings -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header   py-3">
                                    <h6 class="card-title fw-bold mb-0"><i class="fas fa-palette me-2"></i>Color
                                        Settings</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="_layout_color" class="form-label fw-medium">Layout
                                                    Color</label>
                                                <div class="input-group">
                                                    <span class="input-group-text p-1">
                                                        <div class="color-preview"
                                                            style="width: 25px; height: 25px; background-color: {{ GeneralSettingsClass::getOptionValue('_layout_color', $settings) }}">
                                                        </div>
                                                    </span>
                                                    <input type="color" class="form-control form-control-color"
                                                        name="_layout_color" id="_layout_color"
                                                        title="Choose layout color"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_layout_color', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="_button_color" class="form-label fw-medium">Button
                                                    Color</label>
                                                <div class="input-group">
                                                    <span class="input-group-text p-1">
                                                        <div class="color-preview"
                                                            style="width: 25px; height: 25px; background-color: {{ GeneralSettingsClass::getOptionValue('_button_color', $settings) }}">
                                                        </div>
                                                    </span>
                                                    <input type="color" class="form-control form-control-color"
                                                        name="_button_color" id="_button_color"
                                                        title="Choose button color"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_button_color', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-sm rounded-lg mb-4">
                                <div class="card-header bg-gradient-primary text-white py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="font-weight-bold mb-0">{{ __('Business Details') }}</h5>
                                    </div>
                                </div>
                                <div class="card-body  ">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Business Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-building"></i></span>
                                                    <input class="form-control" type="text" name="_b_name"
                                                        placeholder="Enter your business name"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_b_name', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Email Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-envelope"></i></span>
                                                    <input class="form-control" type="email" name="_b_email"
                                                        placeholder="Business email for notifications"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_b_email', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Phone Number</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <input class="form-control" type="text" name="_b_number"
                                                        placeholder="Business contact number"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_b_number', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">ABN</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-id-card"></i></span>
                                                    <input class="form-control" type="text" name="_abn"
                                                        placeholder="Australian Business Number"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_abn', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <h6 class="text-primary mb-3">Banking Information</h6>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Bank Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-university"></i></span>
                                                    <input class="form-control" type="text" name="_bank_name"
                                                        placeholder="Enter bank name"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_bank_name', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Bank Details</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-money-check"></i></span>
                                                    <input class="form-control" type="text" name="_bank_details"
                                                        placeholder="Additional bank details"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_bank_details', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">BSB</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-hashtag"></i></span>
                                                    <input class="form-control" type="text" name="_bsb"
                                                        placeholder="BSB number"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_bsb', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Account Number</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-credit-card"></i></span>
                                                    <input class="form-control" type="text" name="_acc"
                                                        placeholder="Account number"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_acc', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <h6 class="text-primary mb-3">Location Information</h6>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Area Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                    <input class="form-control" type="text" name="_b_area_address"
                                                        placeholder="Suburb, City, State"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_b_area_address', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Street Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-map-marker-alt"></i></span>
                                                    <input class="form-control" type="text"
                                                        name="_b_street_address" placeholder="Complete street address"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_b_street_address', $settings) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Map Link</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-map-marker-alt"></i></span>
                                                    <input class="form-control" type="text" name="_b_map_link"
                                                        placeholder="Complete street address"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_b_map_link', $settings) }}">
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm rounded-lg mb-4">
                                <div class="card-header bg-gradient-info text-white py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="font-weight-bold mb-0">{{ __('Social Media Profiles') }}</h5>
                                    </div>
                                </div>
                                <div class="card-body  ">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Instagram</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-instagram text-balck"><i
                                                            class="fab fa-instagram"></i></span>
                                                    <input class="form-control" type="text" name="_instagram_link"
                                                        placeholder="Instagram profile URL"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_instagram_link', $settings) ? GeneralSettingsClass::getOptionValue('_instagram_link', $settings) : '#' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Facebook</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-facebook text-balck"><i
                                                            class="fab fa-facebook-f"></i></span>
                                                    <input class="form-control" type="text" name="_facebook_link"
                                                        placeholder="Facebook page URL"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_facebook_link', $settings) ? GeneralSettingsClass::getOptionValue('_facebook_link', $settings) : '#' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">Twitter</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-twitter text-black"><i
                                                            class="fab fa-x-twitter"></i></span>
                                                    <input class="form-control" type="text" name="_twitter_link"
                                                        placeholder="Twitter profile URL"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_twitter_link', $settings) ? GeneralSettingsClass::getOptionValue('_twitter_link', $settings) : '#' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">LinkedIn</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-youtube text-black"><i
                                                            class="fab fa-linkedin-in"></i></span>
                                                    <input class="form-control" type="text" name="_youtube_link"
                                                        placeholder="YouTube channel URL"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_youtube_link', $settings) ? GeneralSettingsClass::getOptionValue('_youtube_link', $settings) : '#' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-medium">LinkedIn</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-linkedin text-black"><i
                                                            class="fab fa-linkedin-in"></i></span>
                                                    <input class="form-control" type="text" name="_linke_din"
                                                        placeholder="LinkedIn profile URL"
                                                        value="{{ GeneralSettingsClass::getOptionValue('_linke_din', $settings) ? GeneralSettingsClass::getOptionValue('_linke_din', $settings) : '#' }}">
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>

                            @if (Session::has('permissions') && in_array('Save General Settings', Session::get('permissions')))
                                <div class="text-end mb-md-0 mb-4">
                                    <button type="submit"  class="btn btn-success px-4 py-2" id="submit-button">
                                        <i class="fas fa-save me-2"></i> Save Changes
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <!--/  Order Active dataTable End -->
            <!--================================-->
        </div>
        <!--/ Page Inner End -->




    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const minimumItemsInput = document.getElementById('minimum_items');
        const itemsError = document.querySelector('.items-error');
        const minimumItemsCount = 1; // Minimum 1 item

        // Items validation function
        function validateItems() {
            const itemsValue = parseInt(minimumItemsInput.value);

            if (isNaN(itemsValue) || itemsValue < minimumItemsCount) {
                itemsError.style.display = 'block';
                minimumItemsInput.classList.add('is-invalid');
                return false;
            } else {
                itemsError.style.display = 'none';
                minimumItemsInput.classList.remove('is-invalid');
                return true;
            }
        }
        // GST Percentage validation script
        (function() {
            const gstInput = document.getElementById('gst-percentage-input');
            const submitButton = document.getElementById('submit-button');
            const errorMessage = document.getElementById('gst-error-message');

            function validateGstPercentage() {
                const value = parseFloat(gstInput.value);
                const isEmpty = gstInput.value.trim() === '';
                const isValid = !isEmpty && !isNaN(value) && value >= 0;

                if (isValid) {
                    // Valid input
                    submitButton.disabled = false;
                    submitButton.classList.remove('btn-secondary');
                    submitButton.classList.add('btn-primary');
                    errorMessage.style.display = 'none';
                    gstInput.classList.remove('is-invalid');
                } else {
                    // Invalid input
                    submitButton.disabled = true;
                    submitButton.classList.remove('btn-primary');
                    submitButton.classList.add('btn-secondary');
                    errorMessage.style.display = 'block';
                    gstInput.classList.add('is-invalid');
                }
            }

            // Event listeners for real-time validation
            gstInput.addEventListener('input', validateGstPercentage);
            gstInput.addEventListener('change', validateGstPercentage);
            gstInput.addEventListener('blur', validateGstPercentage);

            // Initial validation on page load
            validateGstPercentage();
        })();
        // Auto-correct items to minimum value
        function autoCorrectItems() {
            const value = parseInt(minimumItemsInput.value);
            if (isNaN(value) || value < minimumItemsCount) {
                minimumItemsInput.value = minimumItemsCount;
            }
        }

        // Prevent negative values and decimals
        function preventInvalidValues() {
            let value = parseInt(minimumItemsInput.value);
            if (isNaN(value) || value < 0) {
                minimumItemsInput.value = '';
            } else if (value < minimumItemsCount) {
                minimumItemsInput.value = value; // Keep the value but show validation error
            }
        }

        // Event listeners
        minimumItemsInput.addEventListener('input', function() {
            preventInvalidValues();
            validateItems();
        });

        minimumItemsInput.addEventListener('blur', function() {
            autoCorrectItems();
            validateItems();
        });

        // Prevent decimal input for items (only whole numbers)
        minimumItemsInput.addEventListener('keypress', function(e) {
            // Allow only numbers, backspace, delete, tab, escape, enter
            if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode >
                    105)) {
                e.preventDefault();
            }
        });

        // Optional: Form submission validation
        function validateForm() {
            return validateItems();
        }

        // Example usage for form submission
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    alert('Please fix the validation errors before submitting.');
                }
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const minimumWeightInput = document.getElementById('minimum_weight');
        const weightError = document.querySelector('.weight-error');
        const minimumWeightInGrams = 100; // 0.1 kg = 100 grams

        // Weight validation function
        function validateWeight() {
            const weightValue = parseFloat(minimumWeightInput.value);

            if (isNaN(weightValue) || weightValue < minimumWeightInGrams) {
                weightError.style.display = 'block';
                minimumWeightInput.classList.add('is-invalid');
                return false;
            } else {
                weightError.style.display = 'none';
                minimumWeightInput.classList.remove('is-invalid');
                return true;
            }
        }

        // Auto-correct weight to minimum value
        function autoCorrectWeight() {
            const value = parseFloat(minimumWeightInput.value);
            if (isNaN(value) || value < minimumWeightInGrams) {
                minimumWeightInput.value = minimumWeightInGrams;
            }
        }

        // Prevent negative values
        function preventNegativeValues() {
            if (parseFloat(minimumWeightInput.value) < 0) {
                minimumWeightInput.value = 0;
            }
        }

        // Event listeners
        minimumWeightInput.addEventListener('input', function() {
            preventNegativeValues();
            validateWeight();
        });

        minimumWeightInput.addEventListener('blur', function() {
            autoCorrectWeight();
            validateWeight();
        });

        // Optional: Form submission validation
        function validateForm() {
            return validateWeight();
        }

        // Example usage for form submission
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    alert('Please fix the validation errors before submitting.');
                }
            });
        }
    });
</script>

<style>
    .is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
</style>
<script>
    $(document).ready(function() {
        $("#togglePasswordIcon").click(function() {
            var passwordField = $("#password");
            var fieldType = passwordField.attr("type");

            if (fieldType === "password") {
                passwordField.attr("type", "text");
                $('#togglePasswordIcon').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr("type", "password");
                $('#togglePasswordIcon').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#footer_logo_input").change(function() {
            readURL(this);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $("#preview_img").attr("src", e.target.result);
                    $("#image_preview").show();
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#header_logo_input").change(function() {
            readHeaderLogoURL(this);
        });

        function readHeaderLogoURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $("#preview_img_header").attr("src", e.target.result);
                    $("#header_image_preview").show();
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    });
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            // Use Bootstrap's fade out
            alert.classList.remove('show');
            alert.classList.add('fade');
            // Completely remove from DOM after transition
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000)
</script>
