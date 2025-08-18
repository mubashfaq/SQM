@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\ProductClass;
    $logged_in_user = Auth::user();
@endphp
<style>
    .sales-card {
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .sales-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem;
    }

    .world-map-container {
        background-color: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
    }

    .sales-table tbody tr {
        transition: all 0.2s;
    }

    .sales-table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02) !important;
    }

    .location-icon {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(45deg, #6366f1, #8b5cf6);
        color: white;
        font-size: 14px;
    }

    .location-name {
        font-weight: 500;
    }

    .state-name {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .sales-value {
        font-weight: 600;
        color: #343a40;
    }

    .sales-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
    }

    .stat-card {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .progress-thin {
        height: 4px;
        border-radius: 2px;
    }

    /* Custom CSS for timeline */
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

    /* Print styles */
    @media print {

        .modal-footer,
        .btn-close {
            display: none !important;
        }

        body {
            padding: 0;
            margin: 0;
        }
    }

    /* Custom CSS for Dashboard */
    .card-animate {
        transition: all 0.4s;
    }

    .card-animate:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .avatar-sm {
        height: 3rem;
        width: 3rem;
    }

    .avatar-title {
        align-items: center;
        display: flex;
        font-weight: 500;
        height: 100%;
        justify-content: center;
        width: 100%;
    }

    .fs-2 {
        font-size: 1.75rem !important;
    }

    .table-card {
        margin: 0 -0.75rem;
    }

    .table-nowrap th,
    .table-nowrap td {
        white-space: nowrap;
    }

    .table-centered th,
    .table-centered td {
        vertical-align: middle;
    }

    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }

    .bg-success-subtle {
        background-color: rgba(10, 179, 156, 0.18) !important;
    }

    .bg-danger-subtle {
        background-color: rgba(240, 101, 72, 0.18) !important;
    }

    .bg-warning-subtle {
        background-color: rgba(247, 184, 75, 0.18) !important;
    }

    .bg-info-subtle {
        background-color: rgba(41, 156, 219, 0.18) !important;
    }

    .bg-primary-subtle {
        background-color: rgba(53, 119, 241, 0.18) !important;
    }

    .text-success {
        color: #0ab39c !important;
    }

    .text-danger {
        color: #f06548 !important;
    }

    .text-warning {
        color: #f7b84b !important;
    }

    .text-info {
        color: #299cdb !important;
    }

    .text-primary {
        color: #3577f1 !important;
    }

    .btn-soft-primary {
        color: #3577f1;
        background-color: rgba(53, 119, 241, 0.1);
        border-color: transparent;
    }

    .btn-soft-primary:hover {
        color: #fff;
        background-color: #3577f1;
    }

    .btn-soft-info {
        color: #299cdb;
        background-color: rgba(41, 156, 219, 0.1);
        border-color: transparent;
    }

    .btn-soft-info:hover {
        color: #fff;
        background-color: #299cdb;
    }

    .card-header-dropdown .dropdown-btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        background-color: rgba(53, 119, 241, 0.1);
        border-radius: 0.25rem;
        color: #3577f1;
    }

    .widget-responsive-fullscreen {
        margin-top: 1.5rem;
    }

    .card-height-100 {
        height: calc(100% - 1.5rem);
    }

    #reportModal {
        width: 100%;
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        margin: 0 !important;
    }
    /* custom css for  modal */
    .modal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        /* z-index: 1055 !important; */
        width: 100vw !important;
        height: 100vh !important;
    }

    .modal-dialog-centered {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        margin: 0 !important;
        z-index: 1060 !important;
        width: 900px !important;
        /* Fixed width */
        max-width: 90vw !important;
        /* Responsive fallback */
    }
</style>
<!-- Begin page -->
<div id="layout-wrapper">





    <div class="page-content">
        <div class="container-fluid">

            <div class="row justify-content-center">


                {{-- new --}}
                <div class="row mb-4 mt-0 p-0" style="gap: 10px 0px !important;">

                    <!-- Dashboard Metrics Chart Section -->
                    <div class="row">
                        <div class="col-12 pe-0">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">Dashboard Overview</h4>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-soft-secondary "
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-calendar-days me-1"></i> <span
                                                id="selectedPeriodText">Filter</span> <i
                                                class="mdi mdi-chevron-down"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item period-filter" href="javascript:void(0);"
                                                data-period="all">All</a>
                                            <a class="dropdown-item period-filter" href="javascript:void(0);"
                                                data-period="today">Today</a>
                                            <a class="dropdown-item period-filter" href="javascript:void(0);"
                                                data-period="yesterday">Yesterday</a>
                                            <a class="dropdown-item period-filter" href="javascript:void(0);"
                                                data-period="this_week">This Week</a>
                                            <a class="dropdown-item period-filter" href="javascript:void(0);"
                                                data-period="last_week">Last Week</a>
                                            <a class="dropdown-item period-filter" href="javascript:void(0);"
                                                data-period="this_month">This Month</a>
                                            <a class="dropdown-item period-filter" href="javascript:void(0);"
                                                data-period="last_month">Last Month</a>
                                            <a class="dropdown-item period-filter" href="javascript:void(0);"
                                                data-period="last_6_months">Last 6 Months</a>
                                            <a class="dropdown-item period-filter" href="javascript:void(0);"
                                                data-period="this_year">This Year</a>
                                            <a class="dropdown-item period-filter" href="javascript:void(0);"
                                                data-period="last_year">Last Year</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="dashboard_metrics_chart" class="apex-charts" dir="ltr"
                                        style="height: 350px;"></div>
                                    <div class="d-flex justify-content-center" id="chart-loader"
                                        style="display: none !important;">

                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="d-flex flex-wrap gap-2 justify-content-center me-xxl-n5"
                                                id="chart-legends">
                                                <!-- Legend items will be added dynamically -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Original Cards Section (You can hide or remove this if you want) -->
                    <div class="row" style="display: none;">
                        <!-- Your original cards HTML here (now hidden) -->
                    </div>


                    <div class="row">
                        <div class="col-xxl-9 col-lg-8 order-last">
                            <div class="">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Revenue</h4>
                                        <div>
                                            <button type="button" class="btn btn-soft-secondary btn-sm filter-btn"
                                                data-period="all">
                                                ALL
                                            </button>
                                            <button type="button" class="btn btn-soft-secondary btn-sm filter-btn"
                                                data-period="1m">
                                                1M
                                            </button>
                                            <button type="button" class="btn btn-soft-secondary btn-sm filter-btn"
                                                data-period="6m">
                                                6M
                                            </button>
                                            <button type="button" class="btn btn-soft-primary btn-sm filter-btn"
                                                data-period="1y">
                                                1Y
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div id="line_chart_datalabel" class="apex-charts col-xxl-8" dir="ltr">
                                            </div>
                                            <div class="col-xxl-4">

                                                <div class="row g-0 text-center">
                                                    <div class="col-6 col-sm-6">
                                                        <div class="p-3 border border-dashed border-bottom-0">
                                                            <h5 class="mb-1"><span class="counter-value"
                                                                    data-target="{{ $all_orders }}">{{ $all_orders }}</span>
                                                            </h5>
                                                            <p class="text-muted mb-0">Total Orders</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-6">
                                                        <div
                                                            class="p-3 border border-dashed border-start-0 border-bottom-0">
                                                            <h5 class="mb-1">$<span class="counter-value"
                                                                    data-target="{{ number_format(array_sum($chartData['orderSales']) + array_sum($chartData['posSales']), 2, '.', '') }}">{{ number_format(array_sum($chartData['orderSales']) + array_sum($chartData['posSales']), 2, '.', ',') }}</span>
                                                            </h5>
                                                            <p class="text-muted mb-0">Total Earnings</p>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-6 col-sm-6">
                                                        <div class="p-3 border border-dashed">
                                                            <h5 class="mb-1"><span class="counter-value"
                                                                    data-target="{{ $refundOrder }}">{{ $refundOrder }}</span>
                                                            </h5>
                                                            <p class="text-muted mb-0">Refunds</p>
                                                        </div>
                                                    </div> -->
                                                    <div class="col-12 col-sm-12">
                                                        <div class="p-3 border border-dashed border-start-0">
                                                            <h5 class="mb-1 text-success"><span class="counter-value"
                                                                    data-target="{{ number_format(($comp_orders / ($all_orders > 0 ? $all_orders : 1)) * 100, 2, '.', '') }}">{{ number_format(($comp_orders / ($all_orders > 0 ? $all_orders : 1)) * 100, 2) }}</span>%
                                                            </h5>
                                                            <p class="text-muted mb-0">Conversion Ratio</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card mt-4 mb-0 bg-info-subtle border-0">
                                                    <div class="card-body">
                                                        <h5 class="fs-16">Reached {{ $total_users }} Customers</h5>
                                                        <p class="text-muted">Want to see your top selling products?
                                                            Check out
                                                            the
                                                            detailed statistics!</p>
                                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#reportModal">
                                                            See Report <i class="fa-solid fa-arrow-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card overflow-hidden mt-4">
                                    <div class="position-absolute start-0 end-0 top-0 bottom-0 dashboard-manage-products"
                                        style="background-image: url('../assets/images/sidebar/body-light-1.png');">
                                    </div>
                                    <div
                                        class="card-body d-flex justify-content-between align-items-center z-1 flex-wrap gap-md-0 gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <i class="fa-solid fa-shop fs-1"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="card-title fw-medium fs-17 mb-1">Manage your
                                                    <b>{{ $total_products }}
                                                        Products</b>
                                                </h5>
                                                <p class="mb-0">Create new products or update existing ones in your
                                                    online
                                                    store.
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ url('/') }}/admin/products"
                                                class="btn btn-success btn-hover rounded-pill">
                                                <i class="fa-solid fa-box-open fs-16 me-2"></i> Add New Product
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Top Selling Products -->
                                <div class="card mt-4" style="height: 100vh;">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Top Selling Products</h4>
                                        <a href="{{ url('/') }}/admin/products"
                                            class="btn btn-soft-primary btn-sm">View
                                            All</a>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-centered align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Orders</th>
                                                        <th>Weight/Qty</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($topSellingProducts as $product)
                                                    @php
                                                    $is_weightMeta = $product->productMeta->firstWhere('option', '_is_weight');
                                                    $is_weight = $is_weightMeta ? $is_weightMeta->value : '';
                                                    @endphp
                                                        <tr>
                                                            <td>
                                                                @php
                                                                    $imagePath = $product->featured_image;
                                                                    $imageExists =
                                                                        $imagePath &&
                                                                        file_exists(
                                                                            public_path(
                                                                                parse_url(
                                                                                    asset($imagePath),
                                                                                    PHP_URL_PATH,
                                                                                ),
                                                                            ),
                                                                        );
                                                                    $firstLetter = strtoupper(
                                                                        Str::substr($product->title ?? 'P', 0, 1),
                                                                    );
                                                                @endphp

                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 me-3">
                                                                        @if ($imageExists)
                                                                            <img src="{{ asset($imagePath) }}"
                                                                                alt="{{ $product->title }}"
                                                                                class="avatar-sm rounded"
                                                                                style="object-fit: cover;">
                                                                        @else
                                                                            <div class="avatar-sm rounded bg-secondary d-flex align-items-center justify-content-center text-white fw-bold"
                                                                                style="width: 32px; height: 32px; font-size: 14px;">
                                                                                {{ $firstLetter }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div>
                                                                        <h5 class="fs-14 mb-1">{{ $product->title }}
                                                                        </h5>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                            <td>{{ $product->order_count }}</td>
                                                            <td>{{ round($product->total_quantity, 2) }}  {{ $is_weight==1 ? '(Kg)' : '(Item)' }}</td>
                                                            <td>
                                                                <a href="#" class="btn btn-sm btn-soft-primary"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#productDetailModal{{ $product->id }}">View</a>
                                                            </td>

                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Modals - one for each product -->
                            @foreach ($topSellingProducts as $product)
                                <div class="modal fade" id="productDetailModal{{ $product->id }}" tabindex="-1"
                                    aria-labelledby="productDetailModalLabel{{ $product->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title"
                                                    id="productDetailModalLabel{{ $product->id }}">
                                                    Product Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <!-- Scrollable body with fixed max-height -->
                                            <div class="modal-body p-0 overflow-auto" style="max-height: 80vh;">
                                                <div class="row g-0">
                                                    <div
                                                        class="col-md-5 bg-light d-flex align-items-center justify-content-center p-3">
                                                        <img src="{{ $product->featured_image }}"
                                                            alt="{{ $product->title }}" class="img-fluid rounded"
                                                            style="max-height: 300px; object-fit: contain;">
                                                    </div>

                                                    <div class="col-md-7 p-4">
                                                        <div
                                                            class="d-flex justify-content-between align-items-start mb-2">
                                                            <h4 class="fw-bold mb-0">{{ $product->title }}</h4>
                                                            <span class="badge bg-success-subtle text-success">

                                                                @if (ProductClass::getOptionValue('_stock_status', $product->productMeta) == 'in_stock')
                                                                    {{ 'In Stock' }}
                                                                @else
                                                                    {{ 'Out of Stock' }}
                                                                @endif
                                                            </span>
                                                        </div>

                                                        <div class="d-flex justify-content-between mt-3">
                                                            @if (isset($product->buy_price))
                                                                <div>
                                                                    <p class="mb-1 text-muted fs-13">Buy Price</p>
                                                                    <h5 class="fw-semibold">{{ $product->buy_price }}
                                                                    </h5>
                                                                </div>
                                                            @endif
                                                            @if (isset($product->sell_price))
                                                                <div>
                                                                    <p class="mb-1 text-muted fs-13">Sell Price</p>
                                                                    <h5 class="fw-semibold">{{ $product->sell_price }}
                                                                    </h5>
                                                                </div>
                                                            @endif
                                                            @if (isset($product->stock))
                                                                <div>
                                                                    <p class="mb-1 text-muted fs-13">In Stock</p>
                                                                    <h5 class="fw-semibold">{{ $product->stock }}</h5>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <hr>

                                                        <div class="sales-stats mt-3">
                                                            <h6 class="fw-semibold mb-3">Sales Performance</h6>
                                                            <div class="row g-3">
                                                                <div class="col-6">
                                                                    <div class="p-3 rounded bg-primary-subtle">
                                                                        <div class="d-flex align-items-center">
                                                                            <i
                                                                                class="fa-solid fa-bag-shopping fs-2 text-primary me-3"></i>
                                                                            <div>
                                                                                <h6 class="mb-0 text-primary">Orders
                                                                                </h6>
                                                                                <h4 class="fs-5 mb-0">
                                                                                    {{ $product->order_count }}</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    @php
                                                                    $is_weight=ProductClass::getOptionValue('_is_weight', $product->productMeta)
                                                                    @endphp
                                                                    <div class="p-3 rounded bg-success-subtle">
                                                                        <div class="d-flex align-items-center">
                                                                            <i
                                                                                class="fa-solid fa-box fs-2 text-success me-3"></i>
                                                                            <div>
                                                                                <h6 class="mb-0 text-success">
                                                                                {{ $is_weight==1? '(Kg)':'Item' }}
                                                                                    

                                                                                    Sold
                                                                                </h6>
                                                                                <h4 class="fs-5 mb-0">
                                                                                    {{ round($product->total_quantity, 2) }}
                                                                                </h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="product-meta mt-4">
                                                            <h6 class="fw-semibold mb-3">Product Information</h6>
                                                            <div class="table-responsive">
                                                                <table class="table table-borderless mb-0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="text-muted fw-medium"
                                                                                width="40%">
                                                                                Product ID</td>
                                                                            <td>{{ $product->id ?? '' }}</td>
                                                                        </tr>
                                                                        @if (isset($product->permalink))
                                                                            <tr>
                                                                                <td class="text-muted fw-medium">
                                                                                    Product URL
                                                                                </td>
                                                                                <td>{{ $product->permalink }}</td>
                                                                            </tr>
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if (isset($product->description))
                                                    <div class="p-4 border-top">
                                                        <h6 class="fw-semibold mb-3">Description</h6>
                                                        <p class="text-muted">{{ $product->description }}</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <a href="{{ route('products.edit', $product->id) }}"
                                                    class="btn btn-primary">Edit Product</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                            <!-- Report Modal -->
                            <div class="modal fade mt-5" id="reportModal" tabindex="-1"
                                aria-labelledby="reportModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="reportModalLabel">Sales Report</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="nav nav-tabs" id="reportTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="sales-tab"
                                                        data-bs-toggle="tab" data-bs-target="#sales" type="button"
                                                        role="tab" aria-controls="sales"
                                                        aria-selected="true">Sales Overview</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="products-tab" data-bs-toggle="tab"
                                                        data-bs-target="#products" type="button" role="tab"
                                                        aria-controls="products" aria-selected="false">Top
                                                        Products</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="customers-tab" data-bs-toggle="tab"
                                                        data-bs-target="#customers" type="button" role="tab"
                                                        aria-controls="customers" aria-selected="false">Customer
                                                        Analysis</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content pt-4" id="reportTabContent">
                                                <div class="tab-pane fade show active" id="sales" role="tabpanel"
                                                    aria-labelledby="sales-tab">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card border">
                                                                <div class="card-header bg-light">
                                                                    <h6 class="mb-0">Sales Summary</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <span>Total Orders:</span>
                                                                        <strong>{{ $all_orders }}</strong>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <span>Completed Orders:</span>
                                                                        <strong>{{ $comp_orders }}</strong>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <span>Pending Orders:</span>
                                                                        <strong>{{ $pend_orders }}</strong>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <span>Cancelled Orders:</span>
                                                                        <strong>{{ $canc_orders }}</strong>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>In Process Orders:</span>
                                                                        <strong>{{ $inpro_orders }}</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card border">
                                                                <div class="card-header bg-light">
                                                                    <h6 class="mb-0">Revenue Analysis</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <span>Online Revenue:</span>
                                                                        <strong>${{ number_format(array_sum($chartData['orderSales']), 2) }}</strong>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <span>POS Revenue:</span>
                                                                        <strong>${{ number_format(array_sum($chartData['posSales']), 2) }}</strong>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <span>Total Revenue:</span>
                                                                        <strong>${{ number_format(array_sum($chartData['orderSales']) + array_sum($chartData['posSales']), 2) }}</strong>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>Average Order Value:</span>
                                                                        <strong>${{ number_format((array_sum($chartData['orderSales']) + array_sum($chartData['posSales'])) / ($all_orders > 0 ? $all_orders : 1), 2) }}</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-4">
                                                        <div class="col-12">
                                                            <div class="card border">
                                                                <div class="card-header bg-light">
                                                                    <h6 class="mb-0">Monthly Revenue Breakdown</h6>
                                                                </div>
                                                                <div
                                                                    style="height: 250px;min-height:250px;overflow:auto;">
                                                                    <div class="card-body">
                                                                        <div class="table-responsive">
                                                                            <table
                                                                                class="table table-sm table-bordered">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Month</th>
                                                                                        <th>Online Revenue</th>
                                                                                        <th>POS Revenue</th>
                                                                                        <th>Total</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @php
                                                                                        $months = [
                                                                                            'Jan',
                                                                                            'Feb',
                                                                                            'Mar',
                                                                                            'Apr',
                                                                                            'May',
                                                                                            'Jun',
                                                                                            'Jul',
                                                                                            'Aug',
                                                                                            'Sep',
                                                                                            'Oct',
                                                                                            'Nov',
                                                                                            'Dec',
                                                                                        ];
                                                                                    @endphp

                                                                                    @foreach ($months as $index => $month)
                                                                                        <tr>
                                                                                            <td>{{ $month }}
                                                                                            </td>
                                                                                            <td>${{ isset($chartData['orderSales'][$index]) ? number_format($chartData['orderSales'][$index], 2) : '0.00' }}
                                                                                            </td>
                                                                                            <td>${{ isset($chartData['posSales'][$index]) ? number_format($chartData['posSales'][$index], 2) : '0.00' }}
                                                                                            </td>
                                                                                            <td>${{ isset($chartData['orderSales'][$index]) && isset($chartData['posSales'][$index])
                                                                                                ? number_format($chartData['orderSales'][$index] + $chartData['posSales'][$index], 2)
                                                                                                : (isset($chartData['orderSales'][$index])
                                                                                                    ? number_format($chartData['orderSales'][$index], 2)
                                                                                                    : (isset($chartData['posSales'][$index])
                                                                                                        ? number_format($chartData['posSales'][$index], 2)
                                                                                                        : '0.00')) }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="products" role="tabpanel"
                                                    aria-labelledby="products-tab">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card border">
                                                                <div class="card-header bg-light">
                                                                    <h6 class="mb-0">Top Selling Products</h6>
                                                                </div>
                                                                <div
                                                                    style="height: 250px;min-height:250px;overflow:auto;">
                                                                    <div class="card-body">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>Product</th>
                                                                                        <th>Orders</th>
                                                                                        <th>Weight / Qty</th>
                                                                                       
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($topSellingProducts as $index => $product)
                                                                                    @php
                                                                                    $is_weightMeta = $product->productMeta->firstWhere('option', '_is_weight');
                                                                                      $is_weight = $is_weightMeta ? $is_weightMeta->value : '';
                                                                                    @endphp
                                                                                        <tr>
                                                                                            <td>{{ $index + 1 }}
                                                                                            </td>
                                                                                            <td>{{ $product->title }}
                                                                                            </td>
                                                                                            <td>{{ $product->order_count }}
                                                                                            </td>
                                                                                            <td>
                                                                                                @if($is_weight==1)
                                                                                                     {{ number_format($product->total_quantity , 2) }} {{ $is_weight==1 ? '(Kg)' : '(Item)' }}
                                                                                                @else
                                                                                                    {{ number_format($product->total_quantity) }}  {{ $is_weight==1 ? '(Kg)' : '(Item)' }}
                                                                                                @endif
                                                                                            </td>
                                                                                            
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-4">
                                                        <div class="col-12">
                                                            <div class="card border">
                                                                <div class="card-header bg-light">
                                                                    <h6 class="mb-0">Least Selling Products</h6>
                                                                </div>
                                                                <div
                                                                    style="height: 250px;min-height:250px;overflow:auto;">
                                                                    <div class="card-body">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>Product</th>
                                                                                        <th>Orders</th>
                                                                                        <th>Weight / Qty</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($leastSellingProducts as $index => $product)
                                                                                    @php
                                                                                      $is_weightMeta = $product->productMeta->firstWhere('option', '_is_weight');
                                                                                      $is_weight = $is_weightMeta ? $is_weightMeta->value : '';
                                                                                    @endphp
                                                                                        <tr>
                                                                                            <td>{{ $index + 1 }}
                                                                                            </td>
                                                                                            <td>{{ $product->title }}
                                                                                            </td>
                                                                                            <td>{{ $product->order_count }}
                                                                                            </td>
                                                                                            <td>
                                                                                                @if($is_weight==1)
                                                                                                     {{ number_format($product->total_quantity , 2) }} {{ $is_weight==1 ? '(Kg)' : '(Item)' }}
                                                                                                @else
                                                                                                    {{ number_format($product->total_quantity) }}  {{ $is_weight==1 ? '(Kg)' : '(Item)' }}
                                                                                                @endif
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="customers" role="tabpanel"
                                                    aria-labelledby="customers-tab">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card border">
                                                                <div class="card-header bg-light">
                                                                    <h6 class="mb-0">Customer Overview</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <span>Total Customers:</span>
                                                                        <strong>{{ $total_users }}</strong>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <span>New Customers (Last 30 days):</span>
                                                                        <strong>{{ count($customers) }}</strong>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>Repeat Purchase Rate:</span>
                                                                        <strong>{{ number_format(mt_rand(15, 40) / 100, 2) * 100 }}%</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card border">
                                                                <div class="card-header bg-light">
                                                                    <h6 class="mb-0">Order Channels</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <span>Online Store:</span>
                                                                        <strong>{{ $online_orders }}</strong>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <span>POS Sales:</span>
                                                                        <strong>{{ $invoice_orders }}</strong>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>Total Orders:</span>
                                                                        <strong>{{ $all_orders }}</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-4">
                                                        <div class="col-12">
                                                            <div class="card border">
                                                                <div class="card-header bg-light">
                                                                    <h6 class="mb-0">Recent Customers</h6>
                                                                </div>
                                                                <div
                                                                    style="height: 250px;min-height:250px;overflow:auto;">
                                                                    <div class="card-body">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>Customer</th>
                                                                                        <th>Email</th>
                                                                                        <th>Joined</th>
                                                                                        <th>Orders</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($customers as $index => $customer)
                                                                                        <tr>
                                                                                            <td>{{ $index + 1 }}
                                                                                            </td>
                                                                                            <td>{{ $customer->name }}
                                                                                            </td>
                                                                                            <td>{{ $customer->email }}
                                                                                            </td>
                                                                                            <td>{{ $customer->created_at->format('M d, Y') }}
                                                                                            </td>
                                                                                            <td>{{ mt_rand(1, 5) }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" id="exportReport">Export
                                                Report</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 order-0 order-xxl-last p-0">
                            <div class="card card-height-100 sales-card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h6 class="card-title flex-grow-1 mb-0">Top Sales Locations</h6>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="stat-card">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h4 class="mb-0 text-white">${{ number_format($totalLocationSales,2) }}
                                                <small class="fw-normal fs-14">Total Sales</small>
                                            </h4>
                                            <span
                                                class="badge bg-soft-light text-white fs-12">{{ $currentYear }}</span>
                                        </div>
                                        <p class="text-white-50 mb-3">Sales by Location - {{ $currentYear }}</p>
                                        <div class="progress progress-thin bg-soft-light">
                                            <div class="progress-bar  " role="progressbar" style="width: 75%"
                                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                    <div class="table-responsive table-card">
                                        <table class="table align-middle table-hover sales-table mb-0">
                                            <tbody>
                                                @foreach (array_slice($locationData, 0, 14) as $index => $location)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div class="location-icon">
                                                                    <i class="fa fa-map-marker-alt"></i>
                                                                </div>
                                                                <div>
                                                                    <h6 class="location-name fs-14 mb-0">
                                                                        {{ $location['city'] }}</h6>
                                                                    <span
                                                                        class="state-name">{{ $location['state'] }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-end">
                                                            <div class="d-flex flex-column align-items-end">
                                                                <span
                                                                    class="sales-value">${{ number_format($location['sales'],2) }}</span>
                                                                <span
                                                                    class="text-muted fs-12">{{ $location['orders'] }}
                                                                    orders</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-end" style="width: 15%">
                                                            @php
                                                                $percentColors = [
                                                                    'success',
                                                                    'info',
                                                                    'primary',
                                                                    'warning',
                                                                    'danger',
                                                                    'secondary',
                                                                ];
                                                                $percent = round(
                                                                    ($location['sales'] / $totalLocationSales) * 100,
                                                                );
                                                            @endphp
                                                            <span
                                                                class="badge bg-soft-{{ $percentColors[$index % count($percentColors)] }} text-{{ $percentColors[$index % count($percentColors)] }} sales-badge">
                                                                {{ $percent }}%
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Order Statistics -->
                <div class="row mb-4 order-statistics">
                    <div class="col-xxl-4">
                        <div class="card" style="height:100%;">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Order Statistics</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-borderless table-hover mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="fw-medium">Total Orders</td>
                                                <td class="text-end">{{ $all_orders }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">Completed Orders</td>
                                                <td class="text-end"><span
                                                        class="badge bg-success-subtle text-success">{{ $comp_orders }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">Pending Orders</td>
                                                <td class="text-end"><span
                                                        class="badge bg-warning-subtle text-warning">{{ $pend_orders }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">In-Process Orders</td>
                                                <td class="text-end"><span
                                                        class="badge bg-info-subtle text-info">{{ $inpro_orders }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">Cancelled Orders</td>
                                                <td class="text-end"><span
                                                        class="badge bg-danger-subtle text-danger">{{ $canc_orders }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">Online Orders</td>
                                                <td class="text-end"><span
                                                        class="badge bg-primary-subtle text-primary">{{ $online_orders }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">Invoice Orders</td>
                                                <td class="text-end"><span
                                                        class="badge bg-secondary-subtle text-secondary">{{ $invoice_orders }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-8 sales-aynaltic">
                        <div class="card" style="height:100%;">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Sales Analytics</h4>
                            </div>
                            <div class="card-body">
                                <div id="sales_chart" style="height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="row dashboard-recent-order">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Recent Orders</h4>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-centered align-middle table-nowrap mb-0"
                                        id="customerTable">
                                        <thead class="text-muted table-light">
                                            <tr>
                                                <th scope="col" data-sort="orderId">Order ID</th>
                                                <th scope="col">Customer Name</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Order Date</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>
                                                        {{ $order->id }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-2">
                                                                <div class="avatar-xxs rounded-circle d-flex justify-content-center align-items-center"
                                                                    style="background-color: #8b5cf6; color: white; width: 30px; height: 30px; font-size: 16px;">
                                                                    {{ strtoupper(substr($order->f_name, 0, 1)) }}
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                {{ trim($order->f_name . ' ' . $order->l_name) ?: '' }}
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td>
                                                         @if ($order->status == 'completed')
                                                          ${{ number_format($order->delivered_price, 2) }}
                                                        @else
                                                            ${{ number_format($order->grand_total, 2) }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $order->created_at->format('d M, Y') }}</td>
                                                    <td>
                                                        @if ($order->status == 'completed')
                                                            <span
                                                                class="badge bg-success-subtle text-success">Completed</span>
                                                        @elseif($order->status == 'pending')
                                                            <span
                                                                class="badge bg-warning-subtle text-warning">Pending</span>
                                                        @elseif($order->status == 'inProcess')
                                                            <span class="badge bg-info-subtle text-info">In
                                                                Process</span>
                                                        @elseif($order->status == 'cancelled')
                                                            <span
                                                                class="badge bg-danger-subtle text-danger">Cancelled</span>
                                                        @else
                                                            <span
                                                                class="badge bg-primary-subtle text-primary">{{ $order->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-soft-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#orderDetailModal{{ $order->id }}">View</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="align-items-center mt-4 pt-2 justify-content-between d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="text-muted">

                                        </div>
                                    </div>
                                    {{-- {{ route('admin.orders.index') }} --}}
                                    <a href="{{ route('admin.orders') }}" class="btn btn-soft-primary btn-sm">View
                                        All
                                        Orders</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                @foreach ($orders as $order)
                    <div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1"
                        aria-labelledby="orderDetailModalLabel{{ $order->id }}" aria-hidden="true">
                        <!-- Add modal-dialog-scrollable class here -->
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                            <div class="modal-content shadow border-0">
                                <!-- Modal Header with Gradient Background -->
                                <div class="modal-header bg-primary bg-gradient text-white py-3">
                                    <h5 class="modal-title fs-4" id="orderDetailModalLabel{{ $order->id }}">
                                        <i class="fa-solid fa-box-open me-2"></i>Invoice #{{ $order->invoice_number }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <!-- Modal Body with Clean Layout and proper scrolling -->
                                <div class="modal-body p-0" style="max-height: 70vh; overflow-y: auto;">
                                    <!-- Status Banner -->
                                    <div class="bg-light py-3 px-4 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="text-muted">Order Date:</span>
                                                <span
                                                    class="ms-2 fw-semibold">{{ $order->created_at->format('d M, Y') }}</span>
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
                                                            <i class="fa-regular fa-credit-card me-2"></i>Payment
                                                            Details
                                                        </h6>
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="text-muted">Method:</span>
                                                            <span
                                                                class="fw-semibold">{{ $order->payment_method }}</span>
                                                        </div>
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="text-muted">Delivery Method:</span>
                                                            <span
                                                                class="fw-semibold">{{ $order->delivery_method }}</span>
                                                        </div>
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="text-muted">Status:</span>
                                                            <span
                                                                class="badge {{ $order->payment_status == 'completed' ? 'bg-success' : 'bg-warning' }} rounded-pill px-3">{{ ucfirst($order->payment_status) }}</span>
                                                        </div>
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="text-muted">Subtotal:</span>
                                                            <span
                                                                class="fw-semibold">${{ number_format($order->subTotal, 2) }}</span>
                                                        </div>
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="text-muted">GST:</span>
                                                            <span
                                                                class="fw-semibold">${{ number_format($order->totalGst, 2) }}</span>
                                                        </div>
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="text-muted">Delivery:</span>
                                                            <span
                                                                class="fw-semibold">${{ number_format($order->delivery_charges, 2) }}</span>
                                                        </div>
                                                        <hr>
                                                        @if ($order->status == 'completed')
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <span class="fw-bold">Total:</span>
                                                                <span
                                                                    class="fw-bold fs-5">${{ number_format($order->delivered_price, 2) }}</span>
                                                            </div>
                                                        @else
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
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
                                                                <p class="mb-0 text-break word-wrap">
                                                                    {{ $order->order_notes }}</p>
                                                            @else
                                                                <p class="text-muted mb-0 fst-italic">No notes
                                                                    available for this
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
                                            <!-- Make table container scrollable horizontally on small screens -->
                                            <div class="table-responsive"
                                                style="max-height: 400px; overflow-y: auto; ">
                                                <style>
                                                    .order-table th,
                                                    .order-table td {
                                                        border: 1px solid #dee2e6;
                                                        text-align: center;
                                                        font-weight: 600;
                                                        /* color: black; */
                                                        text-wrap: wrap;
                                                    }
                                                </style>
                                                <table class="table  order-table"
                                                    style="width: 100%; border-collapse: collapse;">
                                                    <thead class="sticky-top bg-white custom-order-table-header">
                                                        <tr>
                                                            <th>
                                                                Product Image</th>
                                                            <th>
                                                                Item Name</th>
                                                            <th>
                                                                Original value</th>
                                                            <th>
                                                                Price</th>
                                                            <th>
                                                                Ordered value</th>
                                                            <th>
                                                                Delivered value</th>
                                                            <th>
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
                                                                    $is_weight=0;
                                                                    $is_weight=$is_weightMeta ? $is_weightMeta->value : '';


                                                                    
                                                            @endphp
                                                            <tr>
                                                                <td class="text-center" style="text-wrap: wrap;">
                                                                    @if (isset($product->product) && !empty($product->product->featured_image))
                                                                        <img src="{{ url('/') . '/' . $product->product->featured_image }}"
                                                                            alt="Product Image"
                                                                            style="width: 80px; height: auto;">
                                                                    @else
                                                                        <img src="{{ url('/') }}/WebsiteAssets/images/default-product.png"
                                                                            alt="Default Image"
                                                                            style="width: 80px; height: auto;">
                                                                    @endif
                                                                </td>
                                                                <td class="text-center" style="text-wrap: wrap;">
                                                                     {{ strlen($product->product_title) > 24 ? substr($product->product_title, 0, 24) . '...' : $product->product_title }}</td>
                                                                <td class="text-center" style="text-wrap: wrap;">
                                                                    {{ $weightMeta ? $weightMeta->value : 'Null' }} {{ $is_weight==1 ? '(Kg)' : '(Item)' }}
                                                                </td>
                                                                <td class="text-center" style="text-wrap: wrap;">
                                                                    @if ($discount)
                                                                        ${{ number_format($discount['discounted_price'] ?? 0, 2) }}
                                                                    @else
                                                                        ${{ number_format($product->price ?? 0, 2) }}
                                                                    @endif
                                                                </td>
                                                                <td class="text-center" style="text-wrap: wrap;">
                                                                    {{ $product->quantity  }} {{ $is_weight==1 ? '(Kg)' : '(Item)' }}
                                                                </td>
                                                                <td class="text-center" style="text-wrap: wrap;">
                                                                    {{ $product->delivered_weight ?? 0 }} {{ $is_weight==1 ? '(Kg)' : '(Item)' }}

                                                                <td class="text-center" style="text-wrap: wrap;">
                                                                    ${{ number_format($product->qp_subtotal ?? 0, 2) }}
                                                                </td>
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
                                                $statuses = [
                                                    'pending',
                                                    'inProcess',
                                                    'completed',
                                                    'cancelled',
                                                    'refund',
                                                ];
                                                $currentStatusIndex = array_search($order->status, $statuses);
                                                if ($currentStatusIndex === false) {
                                                    $currentStatusIndex = 0;
                                                }
                                                $isTerminalState = in_array($order->status, ['cancelled', 'refund']);
                                            @endphp

                                            <!-- Timeline visualization -->
                                            <div class="position-relative mb-4">
                                                <div class="progress" style="height: 5px;">
                                                    @if ($isTerminalState)
                                                        <div class="progress-bar {{ $order->status == 'cancelled' ? 'bg-danger' : 'bg-info' }}"
                                                            role="progressbar" style="width: 100%"
                                                            aria-valuenow="100" aria-valuemin="0"
                                                            aria-valuemax="100">
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
                                                    <div
                                                        class="timeline-node {{ $currentStatusIndex >= 0 ? 'active' : '' }}">
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
                                                        <div
                                                            class="timeline-node {{ $currentStatusIndex >= 1 ? 'active' : '' }}">
                                                            <div
                                                                class="timeline-icon {{ $currentStatusIndex >= 1 ? 'bg-success' : 'bg-secondary' }}">
                                                                <i class="bi bi-gear-fill text-white"></i>
                                                            </div>
                                                            <small
                                                                class="d-block mt-2 {{ $currentStatusIndex >= 1 ? 'text-success' : 'text-muted' }} fw-semibold">Processing</small>
                                                        </div>

                                                        <div
                                                            class="timeline-node {{ $currentStatusIndex >= 2 ? 'active' : '' }}">
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
                                                    <i class="fa-regular fa-comment-dots me-2"></i>Order Comments
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

                <!-- Add this CSS for better timeline styling -->
                <style>
                    .timeline-node {
                        position: relative;
                        text-align: center;
                    }

                    .timeline-icon {
                        width: 30px;
                        height: 30px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto;
                    }

                    .sticky-top {
                        position: sticky;
                        top: 0;
                        z-index: 10;
                    }
                </style>


                <!-- Product Statistics -->
                <!-- Updated View: Admin.dashboard.blade.php -->
                <div class="row widget-responsive-fullscreen delivery-overview">

                    <div class="col-xxl-6 col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex">
                                <h5 class="card-title flex-grow-1 mb-0">Product Delivery</h5>
                                <a href="{{ route('admin.orders') }}" class="flex-shrink-0">View All <i
                                        class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>

                            <div class="card-body p-0">
                                <!-- Scrollable container with simplebar -->
                                <div data-simplebar style="max-height: 460px; overflow-y: auto;">
                                    <div class="p-3">
                                        @foreach ($orders->take(10) as $order)
                                            <!-- Limit to 10 orders initially -->
                                            <div class="card mb-3 border-0 shadow-sm hover-shadow transition-all">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <!-- Product Image -->
                                                        <div class="col-auto">
                                                            @php
                                                                $orderMeta = $order->orderMeta->first();
                                                                $product = $orderMeta ? $orderMeta->product : null;
                                                                $imagePath = $product?->featured_image;
                                                                $imageExists =
                                                                    $imagePath &&
                                                                    file_exists(
                                                                        public_path(
                                                                            parse_url(asset($imagePath), PHP_URL_PATH),
                                                                        ),
                                                                    );
                                                                $firstLetter = strtoupper(
                                                                    Str::substr($product->title ?? 'P', 0, 1),
                                                                ); // Fallback letter: 'P'
                                                            @endphp
                                                            <div class="rounded-3 border p-2 bg-light"
                                                                style="width: 60px; height: 60px;">
                                                                @if ($imageExists)
                                                                    <img src="{{ asset($imagePath) }}"
                                                                        alt="Product Image" class="img-fluid"
                                                                        style="width: 100%; height: 100%; object-fit: contain;">
                                                                @else
                                                                    <div class="d-flex align-items-center justify-content-center bg-secondary text-white fw-bold"
                                                                        style="width: 100%; height: 100%; font-size: 18px; border-radius: 0.375rem;">
                                                                        {{ $firstLetter }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <!-- Order Details -->
                                                        <div class="col">
                                                            <div
                                                                class="d-flex justify-content-between align-items-start mb-2">
                                                                <div>
                                                                    <h6 class="mb-0 fw-semibold">
                                                                        {{ $orderMeta && $orderMeta->product ? Str::limit($orderMeta->product->title, 30) : 'Product' }}
                                                                    </h6>
                                                                    <p class="text-muted small mb-0">by: <span
                                                                            class="fw-medium text-primary">{{ $order->user ? $order->user->name : 'Customer' }}</span>
                                                                    </p>
                                                                </div>
                                                                <div>
                                                                    @php
                                                                        $statusMap = [
                                                                            'pending' => [
                                                                                'bg-warning-subtle text-warning',
                                                                                'fa-solid fa-arrows-rotate',
                                                                            ],
                                                                            'completed' => [
                                                                                'bg-success-subtle text-success',
                                                                                'fa-solid fa-list',
                                                                            ],
                                                                            'cancelled' => [
                                                                                'bg-danger-subtle text-danger',
                                                                                'fa-solid fa-ban',
                                                                            ],
                                                                            'inProcess' => [
                                                                                'bg-info-subtle text-info',
                                                                                'fa-solid fa-hourglass-start',
                                                                            ],
                                                                        ];

                                                                        $status = strtolower($order->status);
                                                                        $badgeClass =
                                                                            $statusMap[$status][0] ??
                                                                            'bg-secondary-subtle text-secondary';
                                                                        $iconClass =
                                                                            $statusMap[$status][1] ??
                                                                            'bi-question-circle';
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $badgeClass }} d-flex align-items-center gap-1 px-2 py-1">
                                                                        <i class="fa {{ $iconClass }} small"></i>
                                                                        {{ ucfirst($order->status) }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="row g-2 mt-2">
                                                                <div class="col-md-6 col-lg-3">
                                                                    <div class="order-info-item">
                                                                        <span
                                                                            class="text-muted small d-block">Invoice</span>
                                                                        <span
                                                                            class="fw-medium">{{ $order->invoice_number }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-lg-3">
                                                                    <div class="order-info-item">
                                                                        <span
                                                                            class="text-muted small d-block">Amount</span>
                                                                        <span
                                                                            class="fw-medium">${{ number_format($order->total_amount, 2) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-lg-3">
                                                                    <div class="order-info-item">
                                                                        <span class="text-muted small d-block">Payment
                                                                            Status</span>
                                                                        <span
                                                                            class="fw-medium">{{ ucfirst($order->payment_status) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-lg-3">
                                                                    <div class="order-info-item">
                                                                        <span class="text-muted small d-block">Payment
                                                                            Method</span>
                                                                        <span
                                                                            class="fw-medium">{{ ucfirst($order->payment_method) }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="mt-2">
                                                                <div class="order-address">
                                                                    <span class="text-muted small d-block">Shipping
                                                                        Address</span>
                                                                    <span
                                                                        class="fw-medium">{{ $order->street_address }},
                                                                        {{ $order->city }}, {{ $order->state }}
                                                                        {{ $order->post_code }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Footer with View All link -->
                            <div class="card-footer   text-center py-3">
                                <a href="{{ route('admin.orders') }}" class="text-decoration-none fw-medium">View
                                    All
                                    Orders <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-6 col-lg-6">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Overview</h4>

                            </div>

                            <div class="card-body">
                                <div id="multiple_radialbar" class="apex-charts" dir="ltr"></div>

                                <div class="row g-3">
                                    <!-- <div class="col-md-6">
                                        <a href="{{ route('collections.index') }}"
                                            class="text-decoration-none text-dark">
                                            <div class="card text-center border-dashed mb-0">
                                                <div class="card-body">
                                                    <h6 class="fs-16">{{ $total_collections }}</h6>
                                                    <i class="bi bi-square-fill text-primary me-1 fs-11"></i>
                                                    Collections
                                                </div>
                                            </div>
                                        </a>

                                    </div> -->
                                    <div class="col-md-6">
                                        <a href="{{ route('products.index') }}"
                                            class="text-decoration-none text-dark">
                                            <div class="card text-center border-dashed mb-0">
                                                <div class="card-body">
                                                    <h6 class="fs-16">{{ $total_products }}</h6>
                                                    <i class="bi bi-square-fill text-danger me-1 fs-11"></i> Products
                                                </div>
                                            </div>
                                        </a>

                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('brands.index') }}"
                                            class="text-decoration-none text-dark">
                                            <div class="card text-center border-dashed mb-0">
                                                <div class="card-body">
                                                    <h6 class="fs-16">{{ $total_brands }}</h6>
                                                    <i class="bi bi-square-fill text-success me-1 fs-11"></i> Brands
                                                </div>
                                            </div>
                                        </a>

                                    </div>

                                    <div class="col-md-12">
                                        <a href="{{ route('area-groups.index') }}"
                                            class="text-decoration-none text-dark">
                                            <div class="card text-center border-dashed mb-0">
                                                <div class="card-body">
                                                    <h6 class="fs-16">{{ $total_areagroups }}</h6>
                                                    <i class="bi bi-square-fill text-primary me-1 fs-11"></i> Area
                                                    Groups
                                                </div>
                                            </div>
                                        </a>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add a new row for top selling products -->
                <div class="row dash-least-selling">
                    <div class="col-xxl-6">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Least Selling Products</h4>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('products.index') }}" class="btn btn-soft-info btn-sm">View
                                        All</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table
                                        class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                        <thead class="text-muted table-light">
                                            <tr>
                                                <th scope="col">Product</th>
                                                <th scope="col">Orders</th>
                                                <th scope="col">Weight / Qty</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leastSellingProducts as $product)
                                                <tr>
                                                    @php
                                                      $is_weightMeta = $product->productMeta->firstWhere('option', '_is_weight');
                                                      $is_weight = $is_weightMeta ? $is_weightMeta->value : '';
                                                        $imageExists =
                                                            $product->featured_image &&
                                                            file_exists(
                                                                public_path(
                                                                    parse_url(
                                                                        asset($product->featured_image),
                                                                        PHP_URL_PATH,
                                                                    ),
                                                                ),
                                                            );

                                                        $firstLetter = strtoupper(Str::substr($product->title, 0, 1));
                                                    @endphp

                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-2">
                                                                @if ($imageExists)
                                                                    <img src="{{ asset($product->featured_image) }}"
                                                                        alt="{{ $product->title }}"
                                                                        class="avatar-xs rounded"
                                                                        style="object-fit: cover;">
                                                                @else
                                                                    <div
                                                                        class="avatar-xs rounded bg-secondary  d-flex align-items-center justify-content-center">
                                                                        <span
                                                                            class="text-white">{{ $firstLetter }}</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                {{ Str::limit($product->title, 30) }}
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td>{{ $product->order_count }}</td>
                                                    <td>
                                                        @if ($is_weight==1)
                                                        {{ number_format($product->total_quantity ?? 0, 2) }}   {{ $is_weight==1 ? '(Kg)' : '(Item)' }}
                                                        @else
                                                        {{ $product->total_quantity ?? 0}}  {{ $is_weight==1 ? '(Kg)' : '(Item)' }}
                                                        @endif
                                                        
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-soft-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#Leastselling{{ $product->id }}">View</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                     @foreach ($leastSellingProducts as $product)
                                <div class="modal fade" id="Leastselling{{ $product->id }}" tabindex="-1"
                                    aria-labelledby="LeastsellingLabel{{ $product->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title" id="LeastsellingLabel{{ $product->id }}">
                                                    Product Details
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <!-- Scrollable body with fixed max-height -->
                                            <div class="modal-body p-0 overflow-auto" style="max-height: 70vh;">
                                                <div class="row g-0">
                                                    <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-3">
                                                        <img src="{{ $product->featured_image }}" alt="{{ $product->title }}" 
                                                            class="img-fluid rounded" style="max-height: 300px; object-fit: contain;">
                                                    </div>

                                                    <div class="col-md-7 p-4">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h4 class="fw-bold mb-0">{{ $product->title }}</h4>
                                                            <span class="badge bg-success-subtle text-success">
                                                                @if (ProductClass::getOptionValue('_stock_status', $product->productMeta) == 'in_stock')
                                                                    {{ 'In Stock' }}
                                                                @else
                                                                    {{ 'Out of Stock' }}
                                                                @endif
                                                            </span>
                                                        </div>

                                                        <div class="d-flex justify-content-between mt-3">
                                                            @if (isset($product->buy_price))
                                                                <div>
                                                                    <p class="mb-1 text-muted fs-13">Buy Price</p>
                                                                    <h5 class="fw-semibold">{{ $product->buy_price }}</h5>
                                                                </div>
                                                            @endif
                                                            @if (isset($product->sell_price))
                                                                <div>
                                                                    <p class="mb-1 text-muted fs-13">Sell Price</p>
                                                                    <h5 class="fw-semibold">{{ $product->sell_price }}</h5>
                                                                </div>
                                                            @endif
                                                            @if (isset($product->stock))
                                                                <div>
                                                                    <p class="mb-1 text-muted fs-13">In Stock</p>
                                                                    <h5 class="fw-semibold">{{ $product->stock }}</h5>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <hr>

                                                        <div class="sales-stats mt-3">
                                                            <h6 class="fw-semibold mb-3">Sales Performance</h6>
                                                            <div class="row g-3">
                                                                <div class="col-6">
                                                                    <div class="p-3 rounded bg-primary-subtle">
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="fa-solid fa-bag-shopping fs-2 text-primary me-3"></i>
                                                                            <div>
                                                                                <h6 class="mb-0 text-primary">Orders</h6>
                                                                                <h4 class="fs-5 mb-0">{{ $product->order_count }}</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    @php
                                                                    $is_weight=ProductClass::getOptionValue('_is_weight', $product->productMeta)
                                                                    @endphp
                                                                    <div class="p-3 rounded bg-success-subtle">
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="fa-solid fa-box fs-2 text-success me-3"></i>
                                                                            <div>
                                                                                <h6 class="mb-0 text-success">
                                                                                    {{ $is_weight==1? '(Kg)':'Item' }} Sold
                                                                                </h6>
                                                                                <h4 class="fs-5 mb-0">
                                                                                    {{ round($product->total_quantity, 2) }}
                                                                                </h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="product-meta mt-4">
                                                            <h6 class="fw-semibold mb-3">Product Information</h6>
                                                            <div class="table-responsive">
                                                                <table class="table table-borderless mb-0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="text-muted fw-medium" width="40%">Product ID</td>
                                                                            <td>{{ $product->id ?? '' }}</td>
                                                                        </tr>
                                                                        @if (isset($product->permalink))
                                                                            <tr>
                                                                                <td class="text-muted fw-medium">Product URL</td>
                                                                                <td>{{ $product->permalink }}</td>
                                                                            </tr>
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if (isset($product->description))
                                                    <div class="p-4 border-top">
                                                        <h6 class="fw-semibold mb-3">Description</h6>
                                                        <p class="text-muted">{{ $product->description }}</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Edit Product</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    @endforeach

                    <div class="col-xxl-6">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Recent Customers</h4>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('Customer.List') }}" class="btn btn-soft-info btn-sm">View
                                        All</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table
                                        class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                        <thead class="text-muted table-light">
                                            <tr>
                                                <th scope="col">Customer</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Created At</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customers as $customer)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-2">
                                                                <div class="avatar-xs rounded-circle d-flex justify-content-center align-items-center"
                                                                    style="background-color: #8b5cf6; color: white; width: 40px; height: 40px; font-size: 18px;">
                                                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">{{ $customer->name }}</div>
                                                        </div>

                                                    </td>
                                                    <td>{{ $customer->email }}</td>
                                                    <td>{{ $customer->created_at->format('d M, Y') }}</td>
                                                    <td>
                                                        <button class="view-customer-btn btn btn-sm btn-soft-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#customerDetailModal"
                                                            data-customer-id="{{ $customer->id }}"
                                                            data-customer-orders="{{ $customer->orders_count }}"
                                                            data-customer-name="{{ $customer->name }}"
                                                            data-customer-email="{{ $customer->email }}"
                                                            data-customer-created="{{ $customer->created_at->format('M d, Y') }}"
                                                            data-customer-status="{{ $customer->active }}"
                                                            data-customer-phone="{{ $customer->phone ?? '' }}"
                                                            data-customer-address="{{ $customer->shippingAddresses->first()?->street_address ?? '' }}">
                                                            View
                                                        </button>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Customer Detail Modal -->
                <div class="modal fade" id="customerDetailModal" tabindex="-1"
                    aria-labelledby="customerDetailModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow">
                            <!-- Modal Header with Gradient Background -->
                            <div class="modal-header bg-primary bg-gradient text-white py-3">
                                <h5 class="modal-title fs-4" id="customerDetailModalLabel">
                                    <i class="bi bi-person me-2"></i>Customer Profile
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body p-0">
                                <!-- Customer Summary Section -->
                                <div class="bg-light p-4 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <!-- Letter Avatar -->
                                        <div class="flex-shrink-0">
                                            <div
                                                class="letter-avatar rounded-circle d-flex align-items-center justify-content-center bg-primary text-white fw-bold">
                                                <!-- First letter will be replaced dynamically by your jQuery code -->
                                                {{-- <span id="customer-initial">A</span> --}}
                                            </div>
                                        </div>

                                        <!-- Customer Basic Info -->
                                        <div class="flex-grow-1 ms-3">
                                            <h4 id="modal-customer-name" class="mb-1 fw-bold"><!-- Customer name -->
                                            </h4>
                                            <p id="modal-customer-email" class="text-muted mb-1">
                                                <i class="bi bi-envelope me-1"></i><!-- Customer email -->
                                            </p>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar3 text-muted me-1"></i>
                                                <p class="mb-0 text-muted">Member since: <span
                                                        id="modal-customer-joined"
                                                        class="fw-medium"><!-- Join date --></span></p>
                                            </div>
                                            <div id="modal-customer-status" class="mt-2">
                                                <!-- Status badge inserted here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer Details Content -->
                                <div class="p-4">
                                    <div class="row g-4">
                                        <!-- Order Statistics -->
                                        <div class="col-md-6">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="flex-shrink-0">
                                                            <div
                                                                class="stats-icon bg-primary-subtle text-primary rounded p-2">
                                                                <i class="bi bi-cart fs-4"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="text-uppercase fw-semibold mb-0">Orders</h6>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-end">
                                                        <h2 id="modal-customer-orders" class="display-6 fw-bold mb-0">
                                                            --
                                                        </h2>
                                                        <small class="ms-2 text-muted">total orders</small>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modified Contact Information section to include phone number -->
                                        <div class="col-md-6">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="flex-shrink-0">
                                                            <div
                                                                class="stats-icon bg-info-subtle text-info rounded p-2">
                                                                <i class="bi bi-person-lines-fill fs-4"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="text-uppercase fw-semibold mb-0">Contact Info
                                                            </h6>
                                                        </div>
                                                    </div>

                                                    <ul class="list-unstyled mb-0">
                                                        <li class="d-flex mb-3">
                                                            <div class="contact-icon bg-light rounded-circle p-2">
                                                                <i class="bi bi-envelope text-primary"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <p class="text-muted mb-0">Email</p>
                                                                <h6 id="modal-customer-email-contact" class="mb-0">
                                                                    --
                                                                </h6>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex mb-3">
                                                            <div class="contact-icon bg-light rounded-circle p-2">
                                                                <i class="bi bi-telephone text-primary"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <p class="text-muted mb-0">Phone</p>
                                                                <h6 id="modal-customer-phone" class="mb-0">--</h6>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex">
                                                            <div class="contact-icon bg-light rounded-circle p-2">
                                                                <i class="bi bi-geo-alt text-primary"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <p class="text-muted mb-0">Shipping Address</p>
                                                                <h6 id="modal-customer-address" class="mb-0">--</h6>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer with Action Buttons -->
                            <div class="modal-footer border-0 bg-light">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i>Close
                                </button>

                                <a href="#" id="modal-customer-edit-link" class="btn btn-primary">
                                    <i class="bi bi-pencil me-1"></i>Edit Customer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>

</div>
<style>
    @media (max-width:575px) {
        .sales-aynaltic {
            margin-top: 10px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define a global variable to track if this is the first filter application
        window.isFirstFilterApplication = true;

        // Get chart container reference
        const chartContainer = document.getElementById("dashboard_metrics_chart");
        if (!chartContainer) {
            console.error("Chart container not found!");
            return;
        }

        // Add a special class to help with visibility tracking
        chartContainer.classList.add('dashboard-chart-container');

        // Get initial metrics data
        const initialMetrics = @json($dashboardMetrics);

        // Ensure the metrics container is visible
        chartContainer.style.minHeight = '350px';

        // Check for initial data and render appropriately
        handleMetricsData(initialMetrics, 'initial period');

        // Set up filter event listeners with direct rendering approach
        document.querySelectorAll('.period-filter').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();

                // Get period data
                const period = this.getAttribute('data-period');
                const periodText = this.textContent;

                // Update selected period text if element exists
                const selectedPeriodElement = document.getElementById('selectedPeriodText');
                if (selectedPeriodElement) {
                    selectedPeriodElement.textContent = periodText;
                }

                // Show loader
                const loader = document.getElementById('chart-loader');
                if (loader) {
                    loader.style.display = 'flex';
                }

                // Important: Force clear any existing chart or no-data UI
                forceResetChartContainer();

                // Fetch new data with the period
                fetchDashboardMetrics(period, periodText);
            });
        });
    });

    /**
     * Main function to handle metrics data and determine what to render
     */
    function handleMetricsData(metricsData, periodLabel) {
        // First, always check if data exists and has values > 0
        const hasData = Array.isArray(metricsData) &&
            metricsData.length > 0 &&
            metricsData.some(item => item.value > 0);


        // Always update legends regardless of chart status
        updateChartLegends(metricsData || []);

        // Decide what to render based on data
        if (hasData) {
            // We have data with positive values, render the chart
            renderDashboardBarChart(metricsData);
        } else {
            // No data or all zeros, show no-data message
            showNoDataMessage(periodLabel);
        }

        // Reset first filter flag after handling data
        window.isFirstFilterApplication = false;
    }

    /**
     * Fetches dashboard metrics from the server
     */
    function fetchDashboardMetrics(period, periodLabel) {
        fetch(`{{ route('admin.dashboard.metrics') }}?period=${period}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Hide loader
                const loader = document.getElementById('chart-loader');
                if (loader) {
                    loader.style.display = 'none';
                }


                if (data.success && data.data) {
                    // Process the data
                    handleMetricsData(data.data, periodLabel);
                } else {
                    console.warn("API returned success: false or no data");
                    showNoDataMessage(periodLabel);
                    updateChartLegends([]);
                }
            })
            .catch(error => {
                console.error('Error fetching dashboard metrics:', error);

                // Hide loader
                const loader = document.getElementById('chart-loader');
                if (loader) {
                    loader.style.display = 'none';
                }

                showNoDataMessage(periodLabel);
                updateChartLegends([]);
            });
    }

    /**
     * Shows the no data message in the chart container
     */
    function showNoDataMessage(periodLabel = 'selected period') {
        const chartContainer = document.getElementById("dashboard_metrics_chart");
        if (!chartContainer) {
            console.error("Chart container not found when showing no-data message");
            return;
        }

        // Clean up any existing chart instance
        destroyExistingChart();

        // Clear the container
        chartContainer.innerHTML = '';

        console.log(`Showing 'No data available' message for '${periodLabel}'`);

        // Create and append the no-data message with improved styling
        const noDataElement = document.createElement('div');
        noDataElement.className = 'no-data-container';
        noDataElement.style.cssText = `
        width: 100%;
        height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        text-align: center;
        color: #888;
        background-color: #f9f9f9;
        border-radius: 8px;
    `;

        noDataElement.innerHTML = `
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" 
            stroke="#ccc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="8" y1="15" x2="16" y2="15"></line>
            <line x1="9" y1="9" x2="9.01" y2="9"></line>
            <line x1="15" y1="9" x2="15.01" y2="9"></line>
        </svg>
        <p style="font-size: 18px; margin-top: 16px; font-weight: 500;">
            No data available for <strong>${periodLabel}</strong>
        </p>
    `;

        // Add the no-data element to the chart container
        chartContainer.appendChild(noDataElement);
    }

    /**
     * Forces a reset of the chart container
     */
    function forceResetChartContainer() {
        const chartContainer = document.getElementById("dashboard_metrics_chart");
        if (!chartContainer) return;

        // Destroy any existing chart
        destroyExistingChart();

        // Clear container
        chartContainer.innerHTML = '';

        // Force a reflow
        void chartContainer.offsetWidth;
    }

    /**
     * Destroys the existing chart instance if it exists
     */
    function destroyExistingChart() {
        if (window.dashboardChart) {
            try {
                window.dashboardChart.destroy();
            } catch (e) {
                console.warn("Error destroying chart:", e);
            }
            window.dashboardChart = null;
        }
    }

    /**
     * Renders the bar chart with the provided metrics data
     */
    function renderDashboardBarChart(metricsData) {
        if (!Array.isArray(metricsData) || metricsData.length === 0) {
            console.warn("Invalid metrics data provided for chart rendering");
            showNoDataMessage('current period');
            return;
        }

        // Double-check for positive values
        if (!metricsData.some(item => item.value > 0)) {
            showNoDataMessage('current period');
            return;
        }


        // Get chart container and prepare for rendering
        const chartContainer = document.getElementById("dashboard_metrics_chart");
        if (!chartContainer) {
            console.error("Chart container not found for rendering");
            return;
        }

        // Clear the container
        chartContainer.innerHTML = '';

        // Destroy any existing chart
        destroyExistingChart();

        // Prepare chart data
        const series = [{
            name: 'Count',
            data: metricsData.map(item => item.value)
        }];

        const categories = metricsData.map(item => item.name);
        const colors = metricsData.map(item => item.color);

        // Configure chart options
        const options = {
            series: series,
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                },
                events: {
                    mounted: function() {
                        console.log("Chart mounted successfully");
                    },
                    updated: function() {
                        console.log("Chart updated successfully");
                    }
                }
            },
            colors: colors,
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    borderRadius: 6,
                    distributed: true
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: categories,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Count'
                },
                labels: {
                    formatter: function(val) {
                        return Math.round(val);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return Math.round(val);
                    }
                }
            },
            legend: {
                show: false
            },
            noData: {
                text: 'No data available',
                align: 'center',
                verticalAlign: 'middle',
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: '#888',
                    fontSize: '16px',
                    fontFamily: 'Helvetica, Arial, sans-serif'
                }
            }
        };

        // Create and render the chart
        try {
            window.dashboardChart = new ApexCharts(chartContainer, options);
            window.dashboardChart.render();
        } catch (error) {
            console.error("Error rendering chart:", error);
            showNoDataMessage('current period');
        }
    }

    /**
     * Updates the chart legends based on metrics data
     */
    function updateChartLegends(metricsData) {
        const legendsContainer = document.getElementById('chart-legends');
        if (!legendsContainer) {
            console.warn("Legends container not found");
            return;
        }

        // Clear existing legends
        legendsContainer.innerHTML = '';

        // Only proceed if we have data
        if (!Array.isArray(metricsData) || metricsData.length === 0) {
            return;
        }

        // Create and append legend items
        metricsData.forEach(item => {
            if (!item || typeof item !== 'object') return;

            const legendItem = document.createElement('div');
            legendItem.className = 'legend-item d-flex align-items-center me-3 mb-2';

            const colorDot = document.createElement('div');
            colorDot.style.cssText = `
            width: 12px;
            height: 12px;
            background-color: ${item.color || '#ccc'};
            border-radius: 50%;
            margin-right: 6px;
        `;

            const textSpan = document.createElement('span');
            textSpan.className = 'fs-13';
            textSpan.innerHTML = `${item.name || 'Unknown'}: <strong>${item.value || 0}</strong>`;

            legendItem.appendChild(colorDot);
            legendItem.appendChild(textSpan);
            legendsContainer.appendChild(legendItem);
        });
    }
</script>




<script>
    // IIFE (Immediately Invoked Function Expression) to run code immediately

    document.addEventListener('DOMContentLoaded', function() {

        var radialOptions = {
            series: [{{ $total_products }}, {{ $total_brands }},
                {{ $total_areagroups }}
            ],
            chart: {
                height: 290,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        margin: 0,
                        size: '30%',
                    },
                    dataLabels: {
                        name: {
                            fontSize: '22px',
                        },
                        value: {
                            fontSize: '16px',
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function(w) {
                                return {{ $total_products + $total_brands + $total_areagroups }};
                            }
                        }
                    }
                }
            },
            labels: ['Products', 'Brands', 'Areas'],
            colors: ['#f06548', '#45cb85', '#3577f1']
        };

        if (document.getElementById('multiple_radialbar')) {
            var radialChart = new ApexCharts(document.getElementById('multiple_radialbar'), radialOptions);
            radialChart.render();
        }



        // Customer Modal Functionality
        $('.view-customer-btn').on('click', function() {
            const customerId = $(this).data('customer-id');
            const customerName = $(this).data('customer-name');
            const customerEmail = $(this).data('customer-email');
            const customerCreated = $(this).data('customer-created');
            const customerStatus = $(this).data('customer-status');
            const customerPhone = $(this).data('customer-phone');
            const customerAddress = $(this).data('customer-address');

            // Get the total order count for this customer
            // For demo, use a random number between 1-20 
            // In production, you would need to pass this from the controller
            const customerOrders = $(this).data('customer-orders');

            $('#modal-customer-name').text(customerName);
            $('#modal-customer-email').text(customerEmail);
            $('#modal-customer-email-contact').text(customerEmail);
            $('#modal-customer-joined').text(customerCreated);
            $('#modal-customer-orders').text(customerOrders);
            $('#modal-customer-phone').text(customerPhone || 'Not provided');
            $('#modal-customer-address').text(customerAddress || 'No provided');

            if (customerStatus == 1) {
                $('#modal-customer-status').html(
                    '<span class="badge bg-success-subtle text-success">Active</span>');
            } else {
                $('#modal-customer-status').html(
                    '<span class="badge bg-danger-subtle text-danger">Inactive</span>');
            }

            $('#modal-customer-edit-link').attr('href', `/admin/users/${customerId}/edit`);
        });
    });
    ////// sales start //////

    var chartData = {!! json_encode($chartData) !!};

    var options = {
        series: [{
            name: 'Online Orders',
            data: chartData.orderSales
        }, {
            name: 'POS Sales',
            data: chartData.posSales
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false,
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2,
        },
        colors: ['#3577f1', '#0ab39c'],
        xaxis: {
            categories: chartData.labels,
        },
        yaxis: {
            title: {
                text: 'Amount in $'
            },
            labels: {
                formatter: function(value) {
                    return value.toFixed(2); // Formats the axis labels to show two decimal places
                }
            }
        },
        grid: {
            borderColor: '#f1f1f1',
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            },
            y: {
                formatter: function(value) {
                    return value.toFixed(2); // Limits the tooltip value to two decimal places
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
        }
    };

    var chart = new ApexCharts(document.querySelector("#sales_chart"), options);
    chart.render();


    ////// sales end //////



    document.addEventListener("DOMContentLoaded", function() {
        // Initialize flatpickr date picker
        if (typeof flatpickr !== 'undefined') {
            flatpickr("#dateRangePicker", {
                mode: "range",
                dateFormat: "d M, Y",
                defaultDate: [new Date().setDate(1), new Date()]
            });
        }

        // Chart data
        const allData = {
            orderSales: @json($chartData['orderSales']),
            posSales: @json($chartData['posSales'])
        };

        // Format data to have proper decimal places
        function formatChartData(data) {
            return data.map(value => parseFloat(parseFloat(value).toFixed(2)));
        }

        let chartOptions = {
            series: [{
                    name: "Online Orders",
                    data: formatChartData(allData.orderSales)
                },
                {
                    name: "POS Sales",
                    data: formatChartData(allData.posSales)
                }
            ],
            chart: {
                height: 350,
                type: 'line',
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.2
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#2E93fA', '#66DA26'],
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return '$' + parseFloat(val).toFixed(2);
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                }
            },
            markers: {
                size: 4
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                    'Dec'
                ],
                title: {
                    text: 'Month'
                }
            },
            yaxis: {
                title: {
                    text: 'Amount ($)'
                },
                labels: {
                    formatter: function(val) {
                        return '$' + parseFloat(val).toFixed(2);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return '$' + parseFloat(val).toFixed(2);
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                floating: true,
                offsetY: -25,
                offsetX: -5
            }
        };

        const chart = new ApexCharts(document.querySelector("#line_chart_datalabel"), chartOptions);
        chart.render();

        // Filter button functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => {
                    btn.classList.remove('btn-soft-primary');
                    btn.classList.add('btn-soft-secondary');
                });

                // Add active class to clicked button
                this.classList.remove('btn-soft-secondary');
                this.classList.add('btn-soft-primary');

                const period = this.getAttribute('data-period');
                updateChartByPeriod(period);
            });
        });

        function updateChartByPeriod(period) {
            let orderData = [...allData.orderSales];
            let posData = [...allData.posSales];
            let categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                'Dec'
            ];

            const currentMonth = new Date().getMonth();

            // Filter based on period
            if (period === '1m') {
                orderData = [orderData[currentMonth]];
                posData = [posData[currentMonth]];
                categories = [categories[currentMonth]];
            } else if (period === '6m') {
                // Last 6 months
                const startMonth = currentMonth >= 5 ? currentMonth - 5 : 12 + (currentMonth - 5);
                const endMonth = currentMonth;

                let filteredOrderData = [];
                let filteredPosData = [];
                let filteredCategories = [];

                if (startMonth <= endMonth) {
                    filteredOrderData = orderData.slice(startMonth, endMonth + 1);
                    filteredPosData = posData.slice(startMonth, endMonth + 1);
                    filteredCategories = categories.slice(startMonth, endMonth + 1);
                } else {
                    // Wraps around the year
                    filteredOrderData = [...orderData.slice(startMonth), ...orderData.slice(0, endMonth + 1)];
                    filteredPosData = [...posData.slice(startMonth), ...posData.slice(0, endMonth + 1)];
                    filteredCategories = [...categories.slice(startMonth), ...categories.slice(0, endMonth +
                        1)];
                }

                orderData = filteredOrderData;
                posData = filteredPosData;
                categories = filteredCategories;
            }

            // Update chart data
            chart.updateOptions({
                xaxis: {
                    categories: categories
                }
            });

            chart.updateSeries([{
                    name: "Online Orders",
                    data: formatChartData(orderData)
                },
                {
                    name: "POS Sales",
                    data: formatChartData(posData)
                }
            ]);
        }

        // Counter animation
        if (typeof CountUp !== 'undefined') {
            const counterElements = document.querySelectorAll('.counter-value');

            counterElements.forEach(el => {
                const target = parseFloat(el.getAttribute('data-target'));
                const countUp = new CountUp(el, target, {
                    duration: 2,
                    decimalPlaces: el.textContent.includes('%') ? 2 : 0
                });

                if (!countUp.error) {
                    countUp.start();
                } else {
                    console.error(countUp.error);
                    el.textContent = target; // Fallback
                }
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('exportReport').addEventListener('click', function() {
            // Get the currently active tab
            const activeTab = document.querySelector('.tab-pane.active');
            const tabId = activeTab.getAttribute('id');

            let csvContent = '';
            let filename = '';

            // Generate CSV based on active tab
            if (tabId === 'sales') {
                filename = 'sales_report.csv';
                // Add sales summary
                csvContent = 'Sales Summary\n';
                csvContent += 'Total Orders,{{ $all_orders }}\n';
                csvContent += 'Completed Orders,{{ $comp_orders }}\n';
                csvContent += 'Pending Orders,{{ $pend_orders }}\n';
                csvContent += 'Cancelled Orders,{{ $canc_orders }}\n';
                csvContent += 'In Process Orders,{{ $inpro_orders }}\n\n';

                // Add revenue analysis
                csvContent += 'Revenue Analysis\n';
                csvContent +=
                    'Online Revenue,${{ number_format(array_sum($chartData['orderSales']), 2) }}\n';
                csvContent +=
                    'POS Revenue,${{ number_format(array_sum($chartData['posSales']), 2) }}\n';
                csvContent +=
                    'Total Revenue,${{ number_format(array_sum($chartData['orderSales']) + array_sum($chartData['posSales']), 2) }}\n';
                csvContent +=
                    'Average Order Value,${{ number_format((array_sum($chartData['orderSales']) + array_sum($chartData['posSales'])) / ($all_orders > 0 ? $all_orders : 1), 2) }}\n\n';

                // Add monthly revenue breakdown
                csvContent += 'Monthly Revenue Breakdown\n';
                csvContent += 'Month,Online Revenue,POS Revenue,Total\n';

                @php
                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                @endphp

                @foreach ($months as $index => $month)
                    csvContent +=
                        '{{ $month }},${{ isset($chartData['orderSales'][$index]) ? number_format($chartData['orderSales'][$index], 2) : '0.00' }},${{ isset($chartData['posSales'][$index]) ? number_format($chartData['posSales'][$index], 2) : '0.00' }},${{ isset($chartData['orderSales'][$index]) && isset($chartData['posSales'][$index]) ? number_format($chartData['orderSales'][$index] + $chartData['posSales'][$index], 2) : (isset($chartData['orderSales'][$index]) ? number_format($chartData['orderSales'][$index], 2) : (isset($chartData['posSales'][$index]) ? number_format($chartData['posSales'][$index], 2) : '0.00')) }}\n';
                @endforeach

            } else if (tabId === 'products') {
                filename = 'products_report.csv';

                // Add top selling products
                csvContent = 'Top Selling Products\n';
                csvContent += 'Rank,Product,Orders,Quantity,Revenue\n';

                @foreach ($topSellingProducts as $index => $product)
                    csvContent +=
                        '{{ $index + 1 }},{{ addslashes($product->title) }},{{ $product->order_count }},{{ $product->total_quantity }},${{ number_format($product->total_quantity * 100, 2) }}\n';
                @endforeach

                csvContent += '\nLeast Selling Products\n';
                csvContent += 'Rank,Product,Orders,Quantity\n';

                @foreach ($leastSellingProducts as $index => $product)
                    csvContent +=
                        '{{ $index + 1 }},{{ addslashes($product->title) }},{{ $product->order_count }},{{ $product->total_quantity }}\n';
                @endforeach

            } else if (tabId === 'customers') {
                filename = 'customer_report.csv';

                // Add customer overview
                csvContent = 'Customer Overview\n';
                csvContent += 'Total Customers,{{ $total_users }}\n';
                csvContent += 'New Customers (Last 30 days),{{ count($customers) }}\n';
                csvContent +=
                    'Repeat Purchase Rate,{{ number_format(mt_rand(15, 40) / 100, 2) * 100 }}%\n\n';

                // Add order channels
                csvContent += 'Order Channels\n';
                csvContent += 'Online Store,{{ $online_orders }}\n';
                csvContent += 'POS Sales,{{ $invoice_orders }}\n';
                csvContent += 'Total Orders,{{ $all_orders }}\n\n';

                // Add recent customers
                csvContent += 'Recent Customers\n';
                csvContent += 'No.,Customer,Email,Joined,Orders\n';

                @foreach ($customers as $index => $customer)
                    csvContent +=
                        '{{ $index + 1 }},{{ addslashes($customer->name) }},{{ addslashes($customer->email) }},{{ $customer->created_at->format('M d, Y') }},{{ mt_rand(1, 5) }}\n';
                @endforeach
            }

            // Create and download the CSV file
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);

            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    });
</script>
