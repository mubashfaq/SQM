@include('Admin.Includes.header')
@php
    use Carbon\Carbon;
    use App\General\InvoiceClass;
@endphp
<style>
    .timeline-node {
        text-align: center;
        z-index: 1;
    }

    .timeline-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .timeline-node.active .timeline-icon {
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
    }

    @media only screen and (max-width: 600px) {
        .index-order-btns {
            flex-wrap: wrap;

        }

        .index-order-btns a:first-child {
            width: 48%;
        }

        .index-order-btns a:nth-child(2) {
            width: 48%;
        }

        .index-order-btns a:last-child {
            width: 100%;
        }
    }
</style>

<div class="page-content">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="status_msgDiv"></div>

    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div class="clearfix">
                    <div class="pd-t-5 pd-b-5">
                        <h4 class="pd-0 mg-0 tx-20 tx-dark">{{ __('Order List') }}</h4>
                    </div>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/admin') }}" class="link-primary text-decoration-none">
                                    <i class="fa-solid fa-house me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Order List
                            </li>
                        </ol>
                    </nav>
                </div>

                <div class="d-flex gap-2 mt-3 mt-md-0 index-order-btns">
                    @if (in_array('Create Product', Session::get('permissions')))
                        <a href="{{ route('Admin.orders.cancelled') }}" class="btn btn-primary" style="">
                            <i class="fas fa-ban me-1"></i> Cancel Order List
                        </a>

                        <!-- <a href="{{ route('Admin.Return.Orders') }}" class="btn btn-danger" style="">
                            <i class="fas fa-undo-alt me-1"></i> Return Order List
                        </a>

                        <a href="{{ route('admin.Return.Order') }}" class="btn btn-success" style="">
                            <i class="fas fa-plus-circle me-1"></i> Create Return Order
                        </a> -->
                    @endif
                </div>

            </div>

        </div>
    </div>



    <div class="row row-cols-xxl-4 row-cols-lg-4 row-cols-md-4 row-cols-1 mt-3">

        <div class="col">
            <div class="card shadow-sm border-0 overflow-hidden card-animate">
                <div class="position-absolute end-0 start-0 top-0 z-0">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="400" height="250" preserveAspectRatio="none" viewBox="0 0 400 250">
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
                                <stop stop-color="rgba(var(--tb-warning-rgb), 0)" offset="0"></stop>
                                <stop stop-color="rgba(var(--tb-warning-rgb), 0.1)" offset="1"></stop>
                            </linearGradient>
                            <linearGradient x1="100%" y1="0%" x2="0%" y2="100%"
                                id="SvgjsLinearGradient1610">
                                <stop stop-color="rgba(var(--tb-warning-rgb), 0)" offset="0"></stop>
                                <stop stop-color="rgba(var(--tb-warning-rgb), 0.1)" offset="1"></stop>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <div class="card-body p-4 z-1 position-relative">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-warning-subtle text-warning fs-3 rounded">
                                <i class="ph-clock-clockwise"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="fs-22 fw-semibold mb-1"><span class="counter-value"
                                    data-target="35.21">{{ $pendingOrders }}</span></h4>
                            <p class="mb-0 fw-medium text-uppercase fs-14">Pending Orders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end col-->
        <div class="col">
            <div class="card shadow-sm border-0 overflow-hidden card-animate">
                <div class="position-absolute end-0 start-0 bottom-0 z-0">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="400" height="250" preserveAspectRatio="none" viewBox="0 0 400 250">
                        <g mask="url(&quot;#SvgjsMask1561&quot;)" fill="none">
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
                            <mask id="SvgjsMask1561">
                                <rect width="400" height="250" fill="#ffffff"></rect>
                            </mask>
                            <linearGradient x1="100%" y1="0%" x2="0%" y2="100%"
                                id="SvgjsLinearGradient1552">
                                <stop stop-color="rgba(var(--tb-success-rgb), 0)" offset="0"></stop>
                                <stop stop-color="rgba(var(--tb-success-rgb), 0.1)" offset="1"></stop>
                            </linearGradient>
                            <linearGradient x1="0%" y1="100%" x2="100%" y2="0%"
                                id="SvgjsLinearGradient1553">
                                <stop stop-color="rgba(var(--tb-success-rgb), 0)" offset="0"></stop>
                                <stop stop-color="rgba(var(--tb-success-rgb), 0.1)" offset="1"></stop>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <div class="card-body p-4 z-1 position-relative">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-success-subtle text-success fs-3 rounded">
                                <i class="ph-truck"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="fs-22 fw-semibold mb-1"><span class="counter-value"
                                    data-target="647.32">{{ $completedOrders }}</span></h4>
                            <p class="mb-0 fw-medium text-uppercase fs-14">Completed Orders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end col-->
        <div class="col">
            <div class="card shadow-sm border-0 overflow-hidden card-animate">
                <div class="position-absolute end-0 start-0 top-0 z-0">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="400" height="250" preserveAspectRatio="none" viewBox="0 0 400 250">
                        <g mask="url(&quot;#SvgjsMask1571&quot;)" fill="none">
                            <path d="M306 65L446 -75" stroke-width="8" stroke="url(#SvgjsLinearGradient1561)"
                                stroke-linecap="round" class="BottomLeft"></path>
                            <path d="M399 2L315 86" stroke-width="10" stroke="url(#SvgjsLinearGradient1562)"
                                stroke-linecap="round" class="TopRight"></path>
                            <path d="M83 77L256 -96" stroke-width="6" stroke="url(#SvgjsLinearGradient1562)"
                                stroke-linecap="round" class="TopRight"></path>
                            <path d="M281 212L460 33" stroke-width="6" stroke="url(#SvgjsLinearGradient1562)"
                                stroke-linecap="round" class="TopRight"></path>
                            <path d="M257 62L76 243" stroke-width="6" stroke="url(#SvgjsLinearGradient1562)"
                                stroke-linecap="round" class="TopRight"></path>
                            <path d="M305 123L214 214" stroke-width="6" stroke="url(#SvgjsLinearGradient1561)"
                                stroke-linecap="round" class="BottomLeft"></path>
                            <path d="M327 222L440 109" stroke-width="6" stroke="url(#SvgjsLinearGradient1561)"
                                stroke-linecap="round" class="BottomLeft"></path>
                            <path d="M287 109L362 34" stroke-width="10" stroke="url(#SvgjsLinearGradient1562)"
                                stroke-linecap="round" class="TopRight"></path>
                            <path d="M259 194L332 121" stroke-width="8" stroke="url(#SvgjsLinearGradient1562)"
                                stroke-linecap="round" class="TopRight"></path>
                            <path d="M376 186L240 322" stroke-width="8" stroke="url(#SvgjsLinearGradient1562)"
                                stroke-linecap="round" class="TopRight"></path>
                            <path d="M308 153L123 338" stroke-width="6" stroke="url(#SvgjsLinearGradient1562)"
                                stroke-linecap="round" class="TopRight"></path>
                            <path d="M218 62L285 -5" stroke-width="8" stroke="url(#SvgjsLinearGradient1561)"
                                stroke-linecap="round" class="BottomLeft"></path>
                        </g>
                        <defs>
                            <mask id="SvgjsMask1571">
                                <rect width="400" height="250" fill="#ffffff"></rect>
                            </mask>
                            <linearGradient x1="100%" y1="0%" x2="0%" y2="100%"
                                id="SvgjsLinearGradient1561">
                                <stop stop-color="rgba(var(--tb-secondary-rgb), 0)" offset="0"></stop>
                                <stop stop-color="rgba(var(--tb-secondary-rgb), 0.1)" offset="1"></stop>
                            </linearGradient>
                            <linearGradient x1="0%" y1="100%" x2="100%" y2="0%"
                                id="SvgjsLinearGradient1562">
                                <stop stop-color="rgba(var(--tb-secondary-rgb), 0)" offset="0"></stop>
                                <stop stop-color="rgba(var(--tb-secondary-rgb), 0.1)" offset="1"></stop>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <div class="card-body p-4 z-1 position-relative">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-secondary-subtle text-secondary fs-3 rounded">
                                <i class="ph-cube"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="fs-22 fw-semibold mb-1"><span class="counter-value"
                                    data-target="215.36">{{ $inProcessOrders }}</span></h4>
                            <p class="mb-0 fw-medium text-uppercase fs-14">InProgress Orders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end col-->
        <div class="col">
            <div class="card shadow-sm border-0 overflow-hidden card-animate">
                <div class="position-absolute end-0 start-0 bottom-0 z-0">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="400" height="250" preserveAspectRatio="none" viewBox="0 0 400 250">
                        <g mask="url(&quot;#SvgjsMask1551&quot;)" fill="none">
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
                            <mask id="SvgjsMask1551">
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
                <div class="card-body p-4 z-1 position-relative">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-danger-subtle text-danger fs-3 rounded">
                                <i class="ph-trash"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="fs-22 fw-semibold mb-1"><span class="counter-value"
                                    data-target="8649">{{ $cancelledOrders }}</span></h4>
                            <p class="mb-0 fw-medium text-uppercase fs-14">Cancelled Orders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end col-->
    </div>

    <div class="  p-3 filter-section card">

        <form id="orders-filter-form" method="GET" action="{{ route('admin.orders') }}">
            <div class="row align-items-end">
                <div class="col-md-2 mb-3">
                    <label for="days" class="form-label">Quick Date</label>
                    <select class="form-select" id="days" name="days">
                        <option value="">All Time</option>
                        <option value="7" {{ request('days') == '7' ? 'selected' : '' }}>Last 7 days</option>
                        <option value="30" {{ request('days') == '30' ? 'selected' : '' }}>Last 30 days</option>
                        <option value="90" {{ request('days') == '90' ? 'selected' : '' }}>Last 90 days</option>
                        <option value="365" {{ request('days') == '365' ? 'selected' : '' }}>Last year</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="search_order" class="form-label">Order Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                        <input type="text" class="form-control" id="search_order" name="search_order"
                            placeholder="Search by order #" value="{{ request('search_order') }}">
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="search_customer" class="form-label">Customer</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="search_customer" name="search_customer"
                            placeholder="Customer name" value="{{ request('search_customer') }}">
                    </div>
                </div>

                <div class="col-md-2 mb-3">
                    <label for="search_status" class="form-label">Status</label>
                    <select class="form-select" id="search_status" name="search_status">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('search_status') == 'pending' ? 'selected' : '' }}>Pending
                        </option>
                        <option value="inProcess" {{ request('search_status') == 'inProcess' ? 'selected' : '' }}>In
                            Process</option>
                        <option value="completed" {{ request('search_status') == 'completed' ? 'selected' : '' }}>
                            Completed</option>
                        <option value="cancelled" {{ request('search_status') == 'cancelled' ? 'selected' : '' }}>
                            Cancelled</option>
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        <a href="{{ route('admin.orders') }}" class="btn btn-secondary flex-grow-1">
                            <i class="fas fa-times me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button id="bulk-delete-btn" class="btn btn-danger" style="display: none;">
                <i class="fa-solid fa-trash me-1"></i> Delete Selected (<span id="selected-count">0</span>)
            </button>
        </div>
    </div>

    <!-- Add this modal at the end of your file -->
    <div class="modal fade zoomIn" id="bulkDeleteConfirmModal" tabindex="-1"
        aria-labelledby="bulkDeleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-md-5">
                    <div class="text-center">
                        <div class="text-danger">
                            <i class="fa-solid fa-trash fs-1"></i>
                        </div>
                        <div class="mt-4 fs-15">
                            <h4 class="mb-1">Are you sure?</h4>
                            <p class="text-muted mx-3 fs-16 mb-0">
                                Are you sure you want to delete <span id="modal-selected-count">0</span> selected
                                orders?<br>
                                This action cannot be undone.
                            </p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light btn-hover"
                            data-bs-dismiss="modal">Cancel</button>
                        <form id="bulk-delete-form" action="{{ route('Admin.orders.bulk-delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="ids" id="selected-ids">
                            <button type="submit" class="btn w-sm btn-danger btn-hover">Yes, Delete Selected</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="card   p-3 mb-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle order-list-table-uni">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="all_checked" name="checkall" value=""></th>
                        <th>Order</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th class="">Delivery Method</th>
                        <th class="">Ordered Total</th>
                        <th class="">Delivered Total</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Fulfillment Status</th>
                        <th>Items</th>
                        <th>Delivery</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="myTableBody" id="myTable">
                    @if ($orders->isNotEmpty())
                        @foreach ($orders as $order)
                            <tr>
                                <td><input type="checkbox" data-id="{{ $order->id }}" name="check_it"
                                        class="checkbox"></td>
                                <td>{{ $order->invoice_number }}</td>
                                <td>
                                    @php
                                        $carbonDate = Carbon::parse($order->created_at);
                                    @endphp
                                    {{ InvoiceClass::convertDateToDDMMYYYY($carbonDate->toDateString()) }}
                                </td>
                                <td>{{ $order->f_name . ' ' . $order->l_name ?? '' }}</td>
                                <td>{{ $order->delivery_method }}</td>
                                <td>
                                    @if ($order->grand_total < 0)
                                        $0
                                    @else
                                        ${{ $order->grand_total }}
                                    @endif
                                </td>
                                <td>
                                    @if ($order->delivered_price == null)
                                        $0
                                    @else
                                        ${{ $order->delivered_price }}
                                    @endif
                                </td>
                                <td>{{ $order->payment_method }}</td>
                                <td>
                                    @if ($order->payment_status == 'pending')
                                        <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                            Pending
                                        </span>
                                    @elseif ($order->payment_status == 'completed')
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                            Completed
                                        </span>
                                    @elseif ($order->payment_status == 'cancelled')
                                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                            Cancelled
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
                                            {{ ucwords($order->payment_status) ?? '' }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if (Session::has('permissions') && in_array('Fulfillment Status', Session::get('permissions')))
                                        <select class="form-select form-select-sm order_status_select"
                                            name="order_status" id="order_status"
                                            data-order-id="{{ $order->id }}"
                                            data-current-value="{{ $order->status }}"
                                            @if ($order->status == 'completed' || $order->status == 'cancelled') disabled @endif>
                                            <option value="pending"
                                                {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="inProcess"
                                                {{ $order->status == 'inProcess' ? 'selected' : '' }}>In Process
                                            </option>
                                            <option value="completed"
                                                {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                                            </option>
                                            <option value="cancelled"
                                                {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                            </option>
                                        </select>
                                    @else
                                        <span class="badge status-badge status-{{ $order->status }}">
                                            {{ ucwords($order->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge bg-secondary-subtle text-secondary">{{ count($order->orderMeta) }}</span>
                                    item(s)
                                </td>
                                <td>${{ $order->delivery_charges ?? 0 }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm text-dark rounded shadow-sm" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false"
                                            style="background-color: #c2d6e7;">
                                            &#8942;
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if (Session::has('permissions') && in_array('Print Order Invoice', Session::get('permissions')))
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                        onclick="printInvoice({{ $order->id }})">
                                                        <i class="fa fa-print me-2"></i> Print Invoice
                                                    </a>
                                                </li>
                                            @endif
                                            @if (Session::has('permissions') && in_array('Orders Detail', Session::get('permissions')))
                                                <li>
                                                    <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#orderDetailModal{{ $order->id }}">
                                                        <i class="fa-regular fa-eye"></i> View
                                                    </a>
                                                </li>
                                            @endif
                                            <!-- @if (Session::has('permissions') && in_array('Orders Delete', Session::get('permissions')))
<li>
                                                    <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                                        data-bs-target="#deleteConfirmModal{{ $order->id }}">
                                                        <i class="fa-solid fa-trash me-2"></i> Delete
                                                    </button>
                                                </li>
@endif -->
                                        </ul>

                                        {{-- Delete Modal --}}
                                        <div class="modal fade zoomIn" id="deleteConfirmModal{{ $order->id }}"
                                            tabindex="-1"
                                            aria-labelledby="deleteConfirmModalLabel{{ $order->id }}"
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
                                                                    <strong>{{ $order->invoice_number }}</strong>?<br>
                                                                    This action cannot be undone.
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                            <button type="button"
                                                                class="btn w-sm btn-light btn-hover"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <form
                                                                action="{{ route('admin.order.delete', $order->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn w-sm btn-danger btn-hover">Yes, Delete
                                                                    It!
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="12" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <h5>No orders found</h5>
                                    <p class="text-muted">Try adjusting your filters or search criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
            @if ($orders->hasPages())
                <div class="">
                    {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif

        @endif
    </div>

    {{-- /// order detail model /// --}}

    @foreach ($orders as $order)
        <div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1"
            aria-labelledby="orderDetailModalLabel{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content shadow border-0">
                    <!-- Modal Header with Gradient Background -->
                    <div class="modal-header bg-primary bg-gradient text-white py-3">
                        <h5 class="modal-title fs-4" id="orderDetailModalLabel{{ $order->id }}">
                            <i class="fa-solid fa-box-open me-2"></i>Invoice #{{ $order->invoice_number }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <!-- Modal Body with Clean Layout -->
                    <div class="modal-body p-0">
                        <!-- Status Banner -->
                        <div class="bg-light py-3 px-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">Order Date:</span>
                                    <span class="ms-2 fw-semibold">{{ $order->created_at->format('d M, Y') }}</span>
                                </div>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-warning',
                                        'cancelled' => 'bg-danger',
                                        'refund' => 'bg-info',
                                        'completed' => 'bg-success',
                                        'inProcess' => 'bg-primary',
                                    ];
                                    $statusColor = $statusColors[$order->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge rounded-pill {{ $statusColor }} px-3 py-2">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Order Information -->
                        <div class="p-4">
                            <div class="row g-4">
                                <!-- Customer Info -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-3 text-primary">
                                                <i class="fa-solid fa-user-plus me-2"></i>Customer
                                            </h6>
                                            <h5 class="card-title fw-bold">
                                                {{ trim($order->f_name . ' ' . $order->l_name) }}</h5>
                                            <div class="mt-3">
                                                <p class="mb-1"><i
                                                        class="fa-solid fa-envelope me-2 text-muted"></i>{{ $order->email }}
                                                </p>
                                                <p class="mb-1"><i
                                                        class="fa-solid fa-phone me-2 text-muted"></i>{{ $order->phone }}
                                                </p>
                                                <p class="mb-0"><i
                                                        class="fa-solid fa-map-location-dot me-2 text-muted"></i>{{ $order->street_address }},
                                                    {{ $order->city }}, {{ $order->state }}
                                                    {{ $order->post_code }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Info -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-3 text-primary">
                                                <i class="fa-regular fa-credit-card me-2"></i>Payment Details
                                            </h6>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">Method:</span>
                                                <span class="fw-semibold">{{ $order->payment_method }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">Delivery Method:</span>
                                                <span class="fw-semibold">{{ $order->delivery_method }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">Status:</span>
                                                <span
                                                    class="badge {{ $order->payment_status == 'completed' ? 'bg-success' : 'bg-warning' }} rounded-pill px-3">{{ ucfirst($order->payment_status) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">Subtotal:</span>
                                                <span
                                                    class="fw-semibold">${{ number_format($order->subTotal, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">GST:</span>
                                                <span
                                                    class="fw-semibold">${{ number_format($order->totalGst, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">Delivery:</span>
                                                <span
                                                    class="fw-semibold">${{ number_format($order->delivery_charges, 2) }}</span>
                                            </div>
                                            <hr>

                                            @if ($order->status == 'completed')
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">Total:</span>
                                                    <span
                                                        class="fw-bold fs-5">${{ number_format($order->delivered_price, 2) }}</span>
                                                </div>
                                            @else
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">Total:</span>
                                                    <span
                                                        class="fw-bold fs-5">${{ number_format($order->grand_total, 2) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="text-primary mb-3">
                                    <i class="fa-solid fa-note-sticky me-2"></i>Order Notes
                                </h6>
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <i
                                                class="fa-solid fa-clipboard-list text-primary fs-5 me-3 mt-1 flex-shrink-0"></i>
                                            <div class="flex-grow-1 min-width-0">
                                                @if (isset($order->order_notes) && !empty($order->order_notes))
                                                    <p class="mb-0 text-break word-wrap">{{ $order->order_notes }}</p>
                                                @else
                                                    <p class="text-muted mb-0 fst-italic">No notes available for this
                                                        order.</p>
                                                @endif

                                                <!-- Add Note Form -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Order Items -->
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="text-primary mb-3">
                                    <i class="fa-solid fa-cart-shopping me-2"></i>Order Items
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered ORDER_Table">
                                        <caption class="Order_detail_card_header ORDER_cAPTION">Product Details:
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th
                                                    >
                                                    Product Image</th>
                                                <th
                                                    >
                                                    Item Name</th>
                                                <th
                                                    >
                                                    Original value</th>
                                                <th
                                                    >
                                                    Price</th>
                                                <th
                                                    >
                                                    Ordered Value</th>
                                                <th
                                                    >
                                                    Delivered Value</th>
                                                <th
                                                    >
                                                    Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->orderMetas as $product)
                                                @php
                                                    $discount = $product->product->discountData;
                                                    $weightMeta = $product->product->productMeta
                                                        ->where('option', '_weight')
                                                        ->first();
                                                         $is_weightMeta = $product->product->productMeta
                                                        ->where('option', '_is_weight')
                                                        ->first();
                                                        $is_weight=$is_weightMeta->value;
                                                @endphp
                                                <tr>
                                                    <td class="text-center" style="text-wrap: wrap;">
                                                        @if (isset($product->product) && !empty($product->product->featured_image))
                                                            <img src="{{ url('/') . '/' . $product->product->featured_image }}"
                                                                alt="Product Image"
                                                                style="width: 100px; height: auto;">
                                                        @else
                                                            <img src="{{ url('/') }}/WebsiteAssets/images/default-product.png"
                                                                alt="Default Image"
                                                                style="width: 100px; height: auto;">
                                                        @endif
                                                    </td>
                                                    <td class="text-center" style="text-wrap: wrap;">
                                                        {{ $product->product_title ?? 'Null' }}</td>
                                                    <td class="text-center" style="text-wrap: wrap;">
                                                        {{ $weightMeta ? $weightMeta->value : 'Null' }} <span class="text-danger">{{$is_weight ==1 ? '(kg)':'(item)'}}</span></td>
                                                    <td class="text-center" style="text-wrap: wrap;">
                                                        @if ($discount)
                                                            ${{ number_format($discount['discounted_price'] ?? 0, 2) }}
                                                        @else
                                                            ${{ number_format($product->price ?? 0, 2) }}
                                                        @endif
                                                    </td>
                                                    <td class="text-center" style="text-wrap: wrap;">
                                                        {{ $product->quantity ?? '' }} <span class="text-danger">{{$is_weight ==1 ? '(kg)':'(item)'}}</span></td>
                                                    <td class="text-center" style="text-wrap: wrap;">
                                                        {!! $product->delivered_weight ? $product->delivered_weight.' <span class="text-danger">'.($is_weight == 1 ? '(kg)' : '(item)').'</span>' : 0 !!}</td>
                                                    <td class="text-center" style="text-wrap: wrap;">
                                                        ${{ number_format($product->qp_subtotal ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Dynamic Order Timeline -->
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="text-primary mb-4">
                                    <i class="fa-solid fa-clock-rotate-left me-2"></i>Order Timeline
                                </h6>
                                @php
                                    $statuses = ['pending', 'inProcess', 'completed', 'cancelled', 'refund'];
                                    $currentStatusIndex = array_search($order->status, $statuses);

                                    // Handle the case where status might not be found
                                    if ($currentStatusIndex === false) {
                                        $currentStatusIndex = 0;
                                    }

                                    // Special case for cancelled and refund which are terminal states
                                    $isTerminalState = in_array($order->status, ['cancelled', 'refund']);
                                @endphp

                                <!-- Timeline visualization -->
                                <div class="position-relative mb-4">
                                    <div class="progress" style="height: 5px;">
                                        @if ($isTerminalState)
                                            <div class="progress-bar {{ $order->status == 'cancelled' ? 'bg-danger' : 'bg-info' }}"
                                                role="progressbar" style="width: 100%" aria-valuenow="100"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        @else
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ min(($currentStatusIndex + 1) * 33, 100) }}%"
                                                aria-valuenow="{{ min(($currentStatusIndex + 1) * 33, 100) }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Timeline nodes -->
                                    <div class="d-flex justify-content-between position-relative"
                                        style="margin-top: -10px;">
                                        <div class="timeline-node {{ $currentStatusIndex >= 0 ? 'active' : '' }}">
                                            <div
                                                class="timeline-icon {{ $currentStatusIndex >= 0 ? 'bg-success' : 'bg-secondary' }}">
                                                <i class="fa-solid fa-circle-check text-white"></i>
                                            </div>
                                            <small
                                                class="d-block mt-2 {{ $currentStatusIndex >= 0 ? 'text-success' : 'text-muted' }} fw-semibold">Ordered</small>
                                        </div>

                                        @if ($isTerminalState)
                                            <div class="timeline-node active">
                                                <div
                                                    class="timeline-icon {{ $order->status == 'cancelled' ? 'bg-danger' : 'bg-info' }}">
                                                    <i
                                                        class="bi {{ $order->status == 'cancelled' ? 'bi-x-circle-fill' : 'bi-arrow-counterclockwise' }} text-white"></i>
                                                </div>
                                                <small
                                                    class="d-block mt-2 {{ $order->status == 'cancelled' ? 'text-danger' : 'text-info' }} fw-semibold">
                                                    {{ $order->status == 'cancelled' ? 'Cancelled' : 'Refunded' }}
                                                </small>
                                            </div>
                                        @else
                                            <div class="timeline-node {{ $currentStatusIndex >= 1 ? 'active' : '' }}">
                                                <div
                                                    class="timeline-icon {{ $currentStatusIndex >= 1 ? 'bg-success' : 'bg-secondary' }}">
                                                    <i class="bi bi-gear-fill text-white"></i>
                                                </div>
                                                <small
                                                    class="d-block mt-2 {{ $currentStatusIndex >= 1 ? 'text-success' : 'text-muted' }} fw-semibold">Processing</small>
                                            </div>

                                            <div class="timeline-node {{ $currentStatusIndex >= 2 ? 'active' : '' }}">
                                                <div
                                                    class="timeline-icon {{ $currentStatusIndex >= 2 ? 'bg-success' : 'bg-secondary' }}">
                                                    <i class="fa-solid fa-circle-check text-white"></i>
                                                </div>
                                                <small
                                                    class="d-block mt-2 {{ $currentStatusIndex >= 2 ? 'text-success' : 'text-muted' }} fw-semibold">Completed</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            @if ($order->comment)
                                <div class="mt-4 pt-3 border-top">
                                    <h6 class="text-primary mb-3">
                                        <i class="fa-regular fa-comment-dots text-white me-2"></i>Order Comments
                                    </h6>
                                    <div class="p-3 bg-light rounded">
                                        {{ $order->comment }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Modal Footer with Attractive Buttons -->
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-circle-xmark me-1"></i>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>
</div>

</div>
</div>
<style>
    /* Additional CSS to ensure proper text wrapping */
    .word-wrap {
        word-wrap: break-word;
        word-break: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
    }

    .min-width-0 {
        min-width: 0;
    }

    /* Ensure the card doesn't exceed container width */
    .card {
        max-width: 100%;
        overflow: hidden;
    }

    /* Alternative: if you want to add scrolling for very long text */
    .scrollable-text {
        max-height: 200px;
        overflow-y: auto;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allCheckbox = document.getElementById('all_checked');
        const checkboxes = document.querySelectorAll('.checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const selectedCountSpan = document.getElementById('selected-count');
        const modalSelectedCountSpan = document.getElementById('modal-selected-count');
        const selectedIdsInput = document.getElementById('selected-ids');

        // Function to update delete button state
        function updateBulkDeleteButtonState() {
            const checkedBoxes = document.querySelectorAll('.checkbox:checked');
            const checkedCount = checkedBoxes.length;

            // Show/hide button based on selections
            if (checkedCount > 0) {
                bulkDeleteBtn.style.display = 'inline-flex';
                selectedCountSpan.textContent = checkedCount;
            } else {
                bulkDeleteBtn.style.display = 'none';
            }
        }

        // Select all checkboxes
        allCheckbox.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = allCheckbox.checked;
            });
            updateBulkDeleteButtonState();
        });

        // Individual checkbox change
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkDeleteButtonState();

                // Update the "select all" checkbox state
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                allCheckbox.checked = allChecked;
            });
        });

        // Open bulk delete modal
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.checkbox:checked');
            const selectedIds = Array.from(checkedBoxes).map(cb => cb.getAttribute('data-id'));

            selectedIdsInput.value = selectedIds.join(',');
            modalSelectedCountSpan.textContent = checkedBoxes.length;

            // Show the modal
            const bulkDeleteModal = new bootstrap.Modal(document.getElementById(
                'bulkDeleteConfirmModal'));
            bulkDeleteModal.show();
        });
    });

    function printInvoice(id) {
        $.ajax({
            url: '/print-guest-order-invoice/' + id,
            type: 'GET',
            success: function(response) {
                var printWindow = window.open('', '', 'width=1200,height=720');
                printWindow.document.open();
                printWindow.document.title = 'OS INOICE';
                printWindow.document.write('<html><head><title>Print Invoice - ' +
                    id +
                    '</title><link href="{{ url('/') }}/AdminAssets/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"></head><body>'
                );
                printWindow.document.write('<div class="invoice_Area">' + response
                    .invoice_review + '</div>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();

                printWindow.print();
            },
            error: function(error) {
                console.log(JSON.stringify(error) + "========== error");
            }
        });
    }


    $(document).ready(function() {
        let filterDaysSelectValue = 7;
        let currentPage = '1';
        $(document).on('change', '.order_status_select', function() {
            let status = $(this).val();
            let orderId = $(this).data('order-id');
            let currentValue = $(this).data('current-value');
            let selectField = $(this);
            $('.status_msgDiv').empty().show();

            if (status === 'completed' || status === 'cancelled') {
                selectField.prop('disabled', true);
            } else {
                selectField.prop('disabled', false);
            }
            $.ajax({
                url: "/change-order-status",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: {
                    'orderId': orderId,
                    'status': status
                },
                success: function(response) {
                    if (response.redirect) {
                        // Redirect if response contains a redirect URL
                        window.location.href = response.redirect;
                        return;
                    } else if (response.status) {
                        console.log(response);
                        $('.status_msgDiv').append(
                            `<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>${response.message}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                        );
                        setTimeout(function() {
                            $('.status_msgDiv').fadeOut();
                        }, 3000);
                        location.reload();
                    } else {
                        // Restore the original value if there's an error
                        $('.order_status_select').val(currentValue);
                        $('.status_msgDiv').append(
                            `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>${response.error}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                        );
                        setTimeout(function() {
                            $('.status_msgDiv').fadeOut();
                        }, 3000);
                    }
                },
                error: function(error) {
                    console.log(error);
                    $('.order_status_select').val(currentValue);
                    $('.status_msgDiv').append(
                        `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>An error occurred while processing your request.</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`
                    );
                    setTimeout(function() {
                        $('.status_msgDiv').fadeOut();
                    }, 3000);
                }
            });
        });

        $('.filterDays').on('change', function() {
            let days = $(this).val();
            filterDaysSelectValue = days;
        });

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
    }, 5000);
</script>
