@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
    use App\General\InvoiceClass;
@endphp



<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="card shadow-sm mb-4">

            <div class="pageheader pd-t-25 pd-b-35">
                <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <div class="clearfix">
                        <div class="pd-t-5 pd-b-5">
                            <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Invoice List') }}</h1>
                        </div>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                              
                                <li class="breadcrumb-item active" aria-current="page">
                                    Invoice List
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ url('/') }}/admin/invoices/create" class="btn btn-success">
                            <i class="fa-solid fa-plus me-1"></i> Create Invoice
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <!-- end page title -->

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-animate bg-info-subtle border-0 overflow-hidden">
                    <div class="position-absolute end-0 start-0 top-0 z-0">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="250"
                            preserveAspectRatio="none" viewBox="0 0 400 250">
                            <g mask="url(&quot;#SvgjsMask1551&quot;)" fill="none">
                                <path d="M306 65L446 -75" stroke-width="8" stroke="url(#SvgjsLinearGradient1552)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M399 2L315 86" stroke-width="10" stroke="url(#SvgjsLinearGradient1553)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M83 77L256 -96" stroke-width="6" stroke="url(#SvgjsLinearGradient1553)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M281 212L460 33" stroke-width="6" stroke="url(#SvgjsLinearGradient1553)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M257 62L76 243" stroke-width="6" stroke="url(#SvgjsLinearGradient1553)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M305 123L214 214" stroke-width="6" stroke="url(#SvgjsLinearGradient1552)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M327 222L440 109" stroke-width="6" stroke="url(#SvgjsLinearGradient1552)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M287 109L362 34" stroke-width="10" stroke="url(#SvgjsLinearGradient1553)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M259 194L332 121" stroke-width="8" stroke="url(#SvgjsLinearGradient1553)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M376 186L240 322" stroke-width="8" stroke="url(#SvgjsLinearGradient1553)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M308 153L123 338" stroke-width="6" stroke="url(#SvgjsLinearGradient1553)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M218 62L285 -5" stroke-width="8" stroke="url(#SvgjsLinearGradient1552)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                            </g>
                            <defs>
                                <mask id="SvgjsMask1551">
                                    <rect width="400" height="250" fill="#ffffff"></rect>
                                </mask>
                                <linearGradient x1="100%" y1="0%" x2="0%" y2="100%"
                                    id="SvgjsLinearGradient1552">
                                    <stop stop-color="rgba(var(--tb-info-rgb), 0)" offset="0"></stop>
                                    <stop stop-color="rgba(var(--tb-info-rgb), 0.1)" offset="1"></stop>
                                </linearGradient>
                                <linearGradient x1="0%" y1="100%" x2="100%" y2="0%"
                                    id="SvgjsLinearGradient1553">
                                    <stop stop-color="rgba(var(--tb-info-rgb), 0)" offset="0"></stop>
                                    <stop stop-color="rgba(var(--tb-info-rgb), 0.1)" offset="1"></stop>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fs-14 fw-medium text-muted mb-0">Public Invoice</p>
                            </div>

                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-24 fw-semibold mb-4">$<span class="counter-value"
                                        data-target="559.25">{{ $totalPublicAmount }}</span></h4>
                                <span class="badge bg-info me-1">{{ $publicCount }}</span> <span
                                    class="text-muted align-bottom">Public Invoice</span>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title  rounded fs-3">
                                    <i class="ph-file-text"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-animate bg-success-subtle border-0 overflow-hidden">
                    <div class="position-absolute end-0 start-0 top-0 z-0">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="250"
                            preserveAspectRatio="none" viewBox="0 0 400 250">
                            <g mask="url(&quot;#SvgjsMask1608&quot;)" fill="none">
                                <path d="M390 87L269 208" stroke-width="10" stroke="url(#SvgjsLinearGradient1609)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M358 175L273 260" stroke-width="8" stroke="url(#SvgjsLinearGradient1610)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M319 84L189 214" stroke-width="10" stroke="url(#SvgjsLinearGradient1609)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M327 218L216 329" stroke-width="8" stroke="url(#SvgjsLinearGradient1610)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M126 188L8 306" stroke-width="8" stroke="url(#SvgjsLinearGradient1610)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M220 241L155 306" stroke-width="10" stroke="url(#SvgjsLinearGradient1610)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M361 92L427 26" stroke-width="6" stroke="url(#SvgjsLinearGradient1609)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M391 188L275 304" stroke-width="8" stroke="url(#SvgjsLinearGradient1609)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M178 74L248 4" stroke-width="10" stroke="url(#SvgjsLinearGradient1610)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M84 52L-56 192" stroke-width="6" stroke="url(#SvgjsLinearGradient1610)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M183 111L247 47" stroke-width="10" stroke="url(#SvgjsLinearGradient1610)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M46 8L209 -155" stroke-width="6" stroke="url(#SvgjsLinearGradient1609)"
                                    stroke-linecap="round" class="TopRight"></path>
                            </g>
                            <defs>
                                <mask id="SvgjsMask1608">
                                    <rect width="400" height="250" fill="#ffffff"></rect>
                                </mask>
                                <linearGradient x1="0%" y1="100%" x2="100%" y2="0%"
                                    id="SvgjsLinearGradient1609">
                                    <stop stop-color="rgba(var(--tb-success-rgb), 0)" offset="0"></stop>
                                    <stop stop-color="rgba(var(--tb-success-rgb), 0.1)" offset="1"></stop>
                                </linearGradient>
                                <linearGradient x1="100%" y1="0%" x2="0%" y2="100%"
                                    id="SvgjsLinearGradient1610">
                                    <stop stop-color="rgba(var(--tb-success-rgb), 0)" offset="0"></stop>
                                    <stop stop-color="rgba(var(--tb-success-rgb), 0.1)" offset="1"></stop>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fs-14 fw-medium text-muted mb-0">Paid Invoices</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-24 fw-semibold mb-4">$<span class="counter-value"
                                        data-target="409.66">{{ $totalPaidAmount }}</span></h4>
                                <span class="badge bg-info me-1">{{ $paidCount }}</span> <span
                                    class="text-muted">Paid by
                                    clients</span>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title   text-success rounded fs-3">
                                    <i class="ph-check-square-offset"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-animate bg-warning-subtle border-0 overflow-hidden">
                    <div class="position-absolute end-0 start-0 top-0 z-0">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="250"
                            preserveAspectRatio="none" viewBox="0 0 400 250">
                            <g mask="url(&quot;#SvgjsMask1530&quot;)" fill="none">
                                <path d="M209 112L130 191" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M324 10L149 185" stroke-width="8" stroke="url(#SvgjsLinearGradient1532)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M333 35L508 -140" stroke-width="10" stroke="url(#SvgjsLinearGradient1532)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M282 58L131 209" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M290 16L410 -104" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M216 186L328 74" stroke-width="6" stroke="url(#SvgjsLinearGradient1531)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M255 53L176 132" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M339 191L519 11" stroke-width="8" stroke="url(#SvgjsLinearGradient1531)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M95 151L185 61" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M249 16L342 -77" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M129 230L286 73" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M80 216L3 293" stroke-width="6" stroke="url(#SvgjsLinearGradient1531)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                            </g>
                            <defs>
                                <mask id="SvgjsMask1530">
                                    <rect width="400" height="250" fill="#ffffff"></rect>
                                </mask>
                                <linearGradient x1="100%" y1="0%" x2="0%" y2="100%"
                                    id="SvgjsLinearGradient1531">
                                    <stop stop-color="rgba(var(--tb-warning-rgb), 0)" offset="0"></stop>
                                    <stop stop-color="rgba(var(--tb-warning-rgb), 0.1)" offset="1"></stop>
                                </linearGradient>
                                <linearGradient x1="0%" y1="100%" x2="100%" y2="0%"
                                    id="SvgjsLinearGradient1532">
                                    <stop stop-color="rgba(var(--tb-warning-rgb), 0)" offset="0"></stop>
                                    <stop stop-color="rgba(var(--tb-warning-rgb), 0.1)" offset="1"></stop>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fs-14 fw-medium text-muted mb-0">Unpaid Invoices</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-24 fw-semibold mb-4">$<span class="counter-value"
                                        data-target="136.98">{{ $totalUnpaidAmount }}</span></h4>
                                <span class="badge bg-info me-1">{{ $unpaidCount }}</span> <span
                                    class="text-muted">Unpaid by
                                    clients</span>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title   text-warning rounded fs-3">
                                    <i class="ph-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-animate bg-danger-subtle border-0 overflow-hidden">
                    <div class="position-absolute end-0 start-0 top-0 z-0">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="250"
                            preserveAspectRatio="none" viewBox="0 0 400 250">
                            <g mask="url(&quot;#SvgjsMask1560&quot;)" fill="none">
                                <path d="M306 65L446 -75" stroke-width="8" stroke="url(#SvgjsLinearGradient1558)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M399 2L315 86" stroke-width="10" stroke="url(#SvgjsLinearGradient1559)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M83 77L256 -96" stroke-width="6" stroke="url(#SvgjsLinearGradient1559)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M281 212L460 33" stroke-width="6" stroke="url(#SvgjsLinearGradient1559)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M257 62L76 243" stroke-width="6" stroke="url(#SvgjsLinearGradient1559)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M305 123L214 214" stroke-width="6" stroke="url(#SvgjsLinearGradient1558)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M327 222L440 109" stroke-width="6" stroke="url(#SvgjsLinearGradient1558)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                                <path d="M287 109L362 34" stroke-width="10" stroke="url(#SvgjsLinearGradient1559)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M259 194L332 121" stroke-width="8" stroke="url(#SvgjsLinearGradient1559)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M376 186L240 322" stroke-width="8" stroke="url(#SvgjsLinearGradient1559)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M308 153L123 338" stroke-width="6" stroke="url(#SvgjsLinearGradient1559)"
                                    stroke-linecap="round" class="TopRight"></path>
                                <path d="M218 62L285 -5" stroke-width="8" stroke="url(#SvgjsLinearGradient1558)"
                                    stroke-linecap="round" class="BottomLeft"></path>
                            </g>
                            <defs>
                                <mask id="SvgjsMask1560">
                                    <rect width="400" height="250" fill="#ffffff"></rect>
                                </mask>
                                <linearGradient x1="100%" y1="0%" x2="0%" y2="100%"
                                    id="SvgjsLinearGradient1558">
                                    <stop stop-color="rgba(var(--tb-danger-rgb), 0)" offset="0"></stop>
                                    <stop stop-color="rgba(var(--tb-danger-rgb), 0.1)" offset="1"></stop>
                                </linearGradient>
                                <linearGradient x1="0%" y1="100%" x2="100%" y2="0%"
                                    id="SvgjsLinearGradient1559">
                                    <stop stop-color="rgba(var(--tb-danger-rgb), 0)" offset="0"></stop>
                                    <stop stop-color="rgba(var(--tb-danger-rgb), 0.1)" offset="1"></stop>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fs-14 fw-medium text-muted mb-0">Draft Invoices</p>
                            </div>

                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-24 fw-semibold mb-4">$<span class="counter-value"
                                        data-target="84.20">{{ $totalDraftAmount }}</span></h4>
                                <span class="badge bg-info me-1">{{ $draftCount }}</span> <span
                                    class="text-muted">Draft Invoices
                                </span>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title   text-danger rounded fs-3">
                                    <i class="fa-regular fa-bookmark"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div> <!-- end row-->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title mb-0">Invoices Table</h5>
            </div>
            <div class="card-body  ">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data_Table ivoice-list-table" class="table table-borderless  align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Invocie Number</th>
                                    <th>Customer</th>
                                    <th>items</th>
                                    <!-- <th>Detail</th> -->
                                    <th>Payment Status</th>
                                    <th>Payment Method</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Updated Date</th>
                                    @if (Session::has('permissions') &&
                                            (in_array('Product Edit', Session::get('permissions')) ||
                                                in_array('Product Delete', Session::get('permissions')) ||
                                                in_array('Product Change Status', Session::get('permissions'))))
                                        <th>Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if (sizeof($invoices) > 0)
                                    @foreach ($invoices as $invoice)
                                  
                                        <tr class="align-middle">
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>
                                                @php
                                                    if ($invoice->customer_type == 'existing') {
                                                        print InvoiceClass::CreateBy($invoice->customer, $users);
                                                    } else {
                                                        echo $invoice->customer;
                                                    }
                                                @endphp
                                            </td>
                                             <td>{{ count($invoice->invoiceMeta) }}</td>
                                            <!-- <td>
                                                <button type="button" class="btn btn-sm btn-soft-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal-{{ $invoice->invoice_number }}">
                                                    View
                                                </button>
                                            </td> -->
                                            @if ($invoice->payment_status == 'Paid')
                                                <td>
                                                    <div class="badge bg-success-subtle text-success">
                                                        {{ $invoice->payment_status }}
                                                    </div>
                                                </td>
                                            @elseif($invoice->payment_status == 'Un-Paid')
                                                <td>
                                                    <div class="badge bg-danger-subtle text-danger">
                                                        {{ $invoice->payment_status }}
                                                    </div>
                                                </td>
                                            @endif
                                            <td>{{ $invoice->payment_method }}</td>
                                            <td>{{ InvoiceClass::convertDateToDDMMYYYY($invoice->invoice_date) }}</td>
                                            <td>{{ InvoiceClass::convertDateToDDMMYYYY($invoice->due_date) }}</td>
                                            <td>
                                                @if ($invoice->active == 0)
                                                    Draft
                                                @elseif($invoice->active == 1)
                                                    Public
                                                @endif
                                            </td>
                                            <td>@php print(InvoiceClass::CreateBy($invoice->created_by,$users)); @endphp</td>
                                            <td>{{ InvoiceClass::convertDateToDDMMYYYY($invoice->created_at) }}</td>
                                            <td>{{ InvoiceClass::convertDateToDDMMYYYY($invoice->updated_at) }}</td>
                                            <td style="display: block !important;">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm text-dark rounded shadow-sm"
                                                        type="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false" style="background-color: #c2d6e7;">
                                                        &#8942; {{-- vertical three-dot icon --}}
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if (Session::has('permissions') && in_array('Print Invoice', Session::get('permissions')))
                                                            <li><button type="button"
                                                                    class="dropdown-item generate-pdf-button"
                                                                    data-invoice-id="{{ $invoice->id }}"><i
                                                                        class="fa fa-print me-2"></i> Print
                                                                    Invoice</button>
                                                            </li>
                                                        @endif

                                                        @if (Session::has('permissions') && in_array('Invoice Edit', Session::get('permissions')))
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('invoices.edit', $invoice->id) }}">
                                                                    @if ($invoice->active == 1)
                                                                        <i class="fa-regular fa-eye me-2"></i> View
                                                                    @else
                                                                        <i class="fa-solid fa-pen-to-square me-2"></i>
                                                                        Edit
                                                                    @endif
                                                                </a></li>
                                                        @endif

                                                        @if ($invoice->active == 0)
                                                            @if (Session::has('permissions') && in_array('Invoice Delete', Session::get('permissions')))
                                                                <li><button
                                                                        class="dropdown-item text-danger destroy-invoice"
                                                                        action-url="{{ route('invoices.destroyJson', $invoice->id) }}">
                                                                        <i class="fa-solid fa-trash me-2"></i>
                                                                        Delete</button>
                                                                </li>
                                                            @endif
                                                        @endif





                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade zoomIn" id="deleteConfirmModal{{ $invoice->id }}"
                                            tabindex="-1"
                                            aria-labelledby="deleteConfirmModalLabel{{ $invoice->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-md-5">
                                                        <div class="text-center">
                                                            <div class="text-danger">
                                                                <i class="fa-solid fa-trash fs-1"></i>
                                                            </div>
                                                            <div class="mt-4 fs-15">
                                                                <h4 class="mb-1">Are you sure?</h4>
                                                                <p class="text-muted mx-3 fs-16 mb-0">
                                                                    Are you sure you want to delete
                                                                    <strong>{{ $invoice->invoice_number }}</strong>?<br>
                                                                    This action cannot be undone.
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                            <button type="button"
                                                                class="btn w-sm btn-light btn-hover"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <!-- Delete Form -->
                                                            <form
                                                                action="{{ route('invoices.destroy', $invoice->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn w-sm btn-danger btn-hover">Yes, Delete
                                                                    It!</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal for this specific invoice -->
                                        <div class="modal fade" id="modal-{{ $invoice->invoice_number }}"
                                            tabindex="-1"
                                            aria-labelledby="detailLabel-{{ $invoice->invoice_number }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <!-- Modal Header with Gradient Background -->
                                                    <div class="modal-header bg-gradient-primary text-white border-0">
                                                        <h5 class="modal-title"
                                                            id="detailLabel-{{ $invoice->invoice_number }}">
                                                            <i class="fa-solid fa-receipt me-2"></i>Order Summary
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <!-- Modal Body with Order Details -->
                                                    <div class="modal-body p-4">
                                                        <!-- Order ID and Date -->
                                                        <div class="d-flex justify-content-between mb-4">
                                                            <div>
                                                                <span class="text-muted fs-6">Order #</span>
                                                                <h6 class="mb-0">{{ $invoice->invoice_number }}</h6>
                                                            </div>
                                                            <div class="text-end">
                                                                <span class="text-muted fs-6">Date</span>
                                                                <h6 class="mb-0">
                                                                    {{ InvoiceClass::convertDateToDDMMYYYY($invoice->invoice_date) }}
                                                                </h6>
                                                            </div>
                                                        </div>

                                                        <!-- Pricing Breakdown -->
                                                        <div class="card border-0 bg-light mb-4">
                                                            <div class="card-body">
                                                                <h6 class="card-title fw-bold mb-3">Price Breakdown
                                                                </h6>
                                                                <div class="pricing-items">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <span class="text-muted">Sub-Total</span>
                                                                        <span
                                                                            class="fw-medium">${{ $invoice->sub_total }}</span>
                                                                    </div>

                                                                    <!-- Discount -->
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <span class="text-muted">Discount</span>
                                                                        <span
                                                                            class="fw-medium text-danger">{{ $invoice->discount }}%</span>
                                                                    </div>

                                                                    <!-- Membership Discount -->
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <div>
                                                                            <span class="text-muted">Membership
                                                                                Discount</span>
                                                                        </div>
                                                                        <span
                                                                            class="fw-medium text-danger">{{ $invoice->membership_dicount }}$</span>
                                                                    </div>

                                                                    <!-- Discounted Total -->
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <span class="text-muted">Discounted
                                                                            Total</span>
                                                                        <span
                                                                            class="fw-medium">${{ $invoice->discounted_amount }}</span>
                                                                    </div>

                                                                    <!-- GST -->
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <span class="text-muted">GST (10%)</span>
                                                                        <span
                                                                            class="fw-medium">${{ $invoice->gst }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Grand Total -->
                                                        <div class="card border-0 bg-primary text-white">
                                                            <div class="card-body">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <h5 class="mb-0 text-white">Grand Total</h5>
                                                                    <h4 class="mb-0 fw-bold text-white">
                                                                        ${{ $invoice->grand_total }}</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Footer with Actions -->
                                                    <div class="modal-footer border-0 bg-light">
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="fa-solid fa-circle-xmark me-1"></i>Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="17" class="text-center">No Record Found!</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>


    </div>
</div>
<script>
    $(document).ready(function() {
        $('#random-customer-section').hide();
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

        $('.generate-pdf-button').click(function() {
            // Get the invoice ID from the button's data attribute
            var invoiceId = $(this).data('invoice-id');

            // Make the Ajax request
            $.ajax({
                url: '/admin/invoices/download/invoice/' + invoiceId,
                type: 'GET',
                success: function(response) {
                    var printWindow = window.open('', '', 'width=1200,height=720');
                    printWindow.document.open();
                    printWindow.document.title = 'POS INOICE';
                    printWindow.document.write('<html><head><title>Print Invoice - ' +
                        invoiceId +
                        '</title><link href="{{ url('/') }}/AdminAssets/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"></head><body>'
                    );
                    printWindow.document.write('<div class="invoice_Area">' + response
                        .invoice_review + '</div>');
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();

                    printWindow.print();
                },
                error: function(error) {
                    // Handle any errors that occur during the request
                    console.error(error);
                }
            });
        });

       // $('#data_Table').DataTable({
       //     "pagingType": "simple_numbers"
       // });
    });

    function QuickSearch() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("quicksearch");
        filter = input.value.toUpperCase();
        table = document.getElementById("invoce_list_search_Area");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
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
                    type: 'GET',
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
    })
</script>
