@include('Admin.Includes.header')

<div class="page-content bg-light">
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="card shadow-sm mb-4">

            <div class="row">
                <div class="col-12">
                    <div class="  p-3 page-title-box">
                        <h2 class="fs-4">{{ __('Return Order List') }}</h2>

                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fas fa-home me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.orders') }}" class="link-primary text-decoration-none">
                                        Order
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Search
                                </li>
                            </ol>
                        </nav>
                    </div>

                </div>
            </div>
        </div>

        <div class="errorDiv mt-3"></div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
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

        <!-- Search Form Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-search me-2"></i>{{ __('Search Order Invoice') }}</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('Website.Return.Order.Details') }}" method="post" class="mb-0">
                            @csrf
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control border-end-0" name="invoice_number"
                                    id="invoice_number"
                                    value="{{ !empty($order->invoice_number) ? $order->invoice_number : '' }}"
                                    placeholder="Enter Order Invoice Number" required>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-search me-2"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details Section (displays only when order is found) -->
        @if (!empty($order))
            <!-- Alert Messages -->
            <div class="alert alert-info alert-dismissible fade show processing-div mb-4" role="alert"
                style="display: none;">
                <i class="fas fa-spinner fa-spin me-2"></i> Processing...
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header  ">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-file-invoice me-2"></i>Order Details</h5>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <span class="text-muted me-2">Placed On:</span>
                            <span
                                class="fw-bold">{{ $order->created_at ? $order->created_at->format('M d, Y H:i') : 'N/A' }}</span>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <span class="text-muted me-2">Status:</span>
                            @if ($order->status == 'pending')
                                <span class="badge bg-info text-white">{{ ucwords($order->status) }}</span>
                            @elseif ($order->status == 'inProcess')
                                <span class="badge bg-warning text-dark">{{ ucwords($order->status) }}</span>
                            @elseif ($order->status == 'completed')
                                <span class="badge bg-success">{{ ucwords($order->status) }}</span>
                            @else
                                <span class="badge bg-danger">{{ ucwords($order->status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <input type="hidden" id="auth_discount_percentage" value="{{ $order->auth_discount_per }}">
                    <input type="hidden" name="order_id" id="order_id" value="{{ $order->invoice_number }}">

                    <div class="row">
                        <!-- Address Information -->
                        <div class="col-md-8">
                            <div class="row mb-4">
                                <!-- Billing Address -->
                                <div class="col-md-6">
                                    <div class="card h-100 border">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Billing Address
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-muted" width="30%">Name</td>
                                                        <td>{{ $order->f_name ?? 'N/A' }} {{ $order->l_name ?? '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Email</td>
                                                        <td>{{ $order->email ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Phone</td>
                                                        <td>{{ $order->phone ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Address</td>
                                                        <td>
                                                            @if ($order->street_address || $order->suburb || $order->state)
                                                                {{ $order->street_address ?? '' }}{{ $order->street_address ? ', ' : '' }}
                                                                {{ $order->suburb ?? '' }}{{ $order->suburb ? ', ' : '' }}
                                                                {{ $order->state ?? '' }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Post Code</td>
                                                        <td>{{ $order->post_code ?? 'N/A' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Shipping Address -->
                                <div class="col-md-6 mt-md-0 mt-4">
                                    <div class="card h-100 border">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Shipping
                                                Address</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-muted" width="30%">Name</td>
                                                        <td>{{ $order->orderAddressDetail->f_name ?? ($order->f_name ?? 'N/A') }}
                                                            {{ $order->orderAddressDetail->l_name ?? ($order->l_name ?? '') }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Email</td>
                                                        <td>{{ $order->orderAddressDetail->email ?? ($order->email ?? 'N/A') }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Phone</td>
                                                        <td>{{ $order->orderAddressDetail->phone ?? ($order->phone ?? 'N/A') }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Address</td>
                                                        <td>
                                                            @php
                                                                $shippingStreet =
                                                                    $order->orderAddressDetail->street_address ??
                                                                    ($order->street_address ?? '');
                                                                $shippingSuburb =
                                                                    $order->orderAddressDetail->suburb ??
                                                                    ($order->suburb ?? '');
                                                                $shippingState =
                                                                    $order->orderAddressDetail->state ??
                                                                    ($order->state ?? '');
                                                            @endphp

                                                            @if ($shippingStreet || $shippingSuburb || $shippingState)
                                                                {{ $shippingStreet }}{{ $shippingStreet ? ', ' : '' }}
                                                                {{ $shippingSuburb }}{{ $shippingSuburb ? ', ' : '' }}
                                                                {{ $shippingState }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Post Code</td>
                                                        <td>{{ $order->orderAddressDetail->post_code ?? ($order->post_code ?? 'N/A') }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items Table -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Order Items</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    @if ($order->status != 'refund')
                                                        <th>Return</th>
                                                    @endif
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($order->orderMeta && $order->orderMeta->count() > 0)
                                                    @foreach ($order->orderMeta as $data)
                                                        <tr data-product-id="{{ $data->product_id }}">
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    @if ($order->status != 'refund')
                                                                        <button
                                                                            class="btn btn-sm btn-outline-danger rounded-circle me-2 remove-return-product"
                                                                            data-product-id="{{ $data->product_id }}">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    @endif

                                                                    @if (!empty($data->product) && !empty($data->product->featured_image))
                                                                        <div class="me-3"
                                                                            style="width: 60px; height: 60px;">
                                                                            <img src="{{ asset($data->product->featured_image) }}"
                                                                                class="img-fluid rounded"
                                                                                alt="{{ $data->product_title ?? 'Product' }}"
                                                                                onerror="this.onerror=null;this.src='https://via.placeholder.com/60?text=No+Image';">
                                                                        </div>
                                                                    @else
                                                                        <div class="me-3"
                                                                            style="width: 60px; height: 60px;">
                                                                            <img src="https://via.placeholder.com/60?text=No+Image"
                                                                                class="img-fluid rounded"
                                                                                alt="No Image">
                                                                        </div>
                                                                    @endif

                                                                    <div>
                                                                        <strong>{{ $data->product_title ?? 'Unnamed Product' }}</strong>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td id="priceDisplay">
                                                                ${{ number_format($data->price ?? 0, 2) }}</td>
                                                            <td id="quantityDisplay">{{ $data->quantity ?? 0 }}</td>
                                                            @if ($order->status != 'refund')
                                                                <td>
                                                                    <div class="input-group input-group-sm quantity-control"
                                                                        style="width: 120px;">
                                                                        <button
                                                                            class="btn btn-outline-secondary quantity-decrement">
                                                                            <i class="fas fa-minus"></i>
                                                                        </button>
                                                                        <input type="number"
                                                                            class="form-control text-center quantity-input"
                                                                            readonly
                                                                            value="{{ $data->quantity ?? 0 }}"
                                                                            min="0"
                                                                            max="{{ $data->quantity ?? 0 }}"
                                                                            oninput="handleInput(this)">
                                                                        <button
                                                                            class="btn btn-outline-secondary quantity-increment">
                                                                            <i class="fas fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="quantityError text-danger small mt-1">
                                                                    </div>
                                                                </td>
                                                            @endif
                                                            <td id="subTotalDisplay" class="fw-bold">
                                                                ${{ number_format(($data->price ?? 0) * ($data->quantity ?? 0), 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="{{ $order->status != 'refund' ? 5 : 4 }}"
                                                            class="text-center">
                                                            No products found in this order
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="col-md-4">
                            <!-- Order Summary Card -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Order Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Subtotal</span>
                                        <span>${{ number_format($order->subTotal ?? 0, 2) }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between py-2 border-top">
                                        <span>Registration Discount</span>
                                        <span>${{ number_format($order->auth_discount ?? 0, 2) }}</span>
                                    </div>

                                    @if (!empty($order->amount_paid_by_coupon))
                                        <div class="d-flex justify-content-between py-2 border-top">
                                            <span>Amount Paid By Coupon</span>
                                            <span>${{ number_format($order->amount_paid_by_coupon ?? 0, 2) }}</span>
                                        </div>
                                    @endif

                                    @if (!empty($order->delivery_charges))
                                        <div class="d-flex justify-content-between py-2 border-top">
                                            <span>Delivery Charges</span>
                                            <span>${{ number_format($order->delivery_charges ?? 0, 2) }}</span>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between pt-2 mt-2 border-top">
                                        <h5 class="mb-0">Total Paid</h5>
                                        <h5 class="mb-0">${{ number_format($order->grand_total ?? 0, 2) }}</h5>
                                    </div>
                                </div>
                            </div>

                            <!-- Refundable Card -->
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-undo-alt me-2"></i>Total Refundable</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Subtotal</span>
                                        <div class="refundable-grand-total">
                                            @php
                                                $grandTotal = 0;
                                                if (!empty($order)) {
                                                    if ($order->status == 'completed') {
                                                        $grandTotal =
                                                            $order->grand_total - ($order->delivery_charges ?? 0);
                                                    } else {
                                                        $grandTotal = $order->grand_total ?? 0;
                                                    }
                                                }
                                            @endphp
                                            <input class="form-control-plaintext text-end fw-bold grandTotalToReturn"
                                                type="text" value="${{ number_format($grandTotal, 2) }}" readonly>
                                        </div>
                                    </div>

                                    @if ($order->status != 'refund')
                                        <div class="text-center mt-3">
                                            <button class="btn btn-primary w-100" id="submitReturnBtn">
                                                <span class="btn-icon"><i
                                                        class="fas fa-undo-alt me-2"></i></span>Process Return
                                            </button>
                                        </div>


                                        @if (strtolower($order->payment_method ?? '') == 'paypal')
                                            <div class="alert alert-warning mt-3 small">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>Important:</strong> Paypal doesn't accept Partial Refunds. If
                                                you want to remove some products,
                                                Paypal won't accept the refund, so you'll need to refund using Gift
                                                Card.
                                                For completed orders, delivery charges will be deducted from the
                                                refundable subtotal,
                                                which makes it a partial refund not possible with Paypal.
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-danger text-center mt-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>This payment has already been refunded.</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(isset($noResults) && $noResults)
            <!-- No Results Found -->
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-search fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">No Invoice Found</h4>
                    <p class="text-muted mb-4">Please check the invoice number and try again.</p>
                    <a href="{{ url('/') }}/admin" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Return to Dashboard
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        let grandTotalToReturn = 0;
        let updatedData = {};
        let isIncreasingQuantity = false;

        // Initialize grand total value
        const grandTotalInput = document.querySelector('.grandTotalToReturn');
        if (grandTotalInput) {
            grandTotalToReturn = parseFloat(grandTotalInput.value.replace(/[^\d.]/g, ''));
        }

        // Setup quantity increment buttons
        document.querySelectorAll('.quantity-increment').forEach(button => {
            button.addEventListener('click', function() {
                isIncreasingQuantity = true;
                const inputField = this.parentElement.querySelector('.quantity-input');
                increaseQuantity(inputField);
            });
        });

        // Setup quantity decrement buttons
        document.querySelectorAll('.quantity-decrement').forEach(button => {
            button.addEventListener('click', function() {
                isIncreasingQuantity = false;
                const inputField = this.parentElement.querySelector('.quantity-input');
                decreaseQuantity(inputField);
            });
        });

        // Setup remove product buttons
        document.querySelectorAll('.remove-return-product').forEach(button => {
            button.addEventListener('click', function() {
                removeProduct(this);
            });
        });

        // Setup return order button
        const submitReturnBtn = document.getElementById('submitReturnBtn');
        if (submitReturnBtn) {
            submitReturnBtn.addEventListener('click', submitReturnOrder);
        }

        /**
         * Update the return order data
         */
        function updateData(productId, newQuantity, newSubtotal) {
            updatedData[productId] = {
                quantity: newQuantity,
                subtotal: newSubtotal
            };
        }

        /**
         * Increase product quantity
         */
        function increaseQuantity(inputField) {
            const currentValue = parseInt(inputField.value, 10);
            const maxValue = parseInt(inputField.getAttribute('max'), 10);

            if (!isNaN(currentValue) && currentValue < maxValue) {
                inputField.value = currentValue + 1;
                handleQuantityChange(inputField);
            }
        }

        /**
         * Decrease product quantity
         */
        function decreaseQuantity(inputField) {
            const currentValue = parseInt(inputField.value, 10);

            if (!isNaN(currentValue) && currentValue > 0) {
                inputField.value = currentValue - 1;
                handleQuantityChange(inputField);
            }
        }

        /**
         * Handle quantity input changes
         */
        function handleQuantityChange(inputField) {
            const numericValue = parseInt(inputField.value, 10);

            if (isNaN(numericValue)) {
                inputField.value = inputField.getAttribute('data-previous-value') || 0;
            } else {
                inputField.setAttribute('data-previous-value', numericValue);
                handleReturnQuantity(inputField);
            }
        }

        /**
         * Process return quantity changes
         */
        function handleReturnQuantity(inputField) {
            const row = inputField.closest('tr');
            const quantityCell = row.querySelector('#quantityDisplay');
            const productQuantity = parseInt(quantityCell.textContent.trim(), 10);
            const returnQuantity = parseInt(inputField.value, 10);
            const productPriceText = row.querySelector('#priceDisplay').textContent.trim();
            const subTotalDisplay = row.querySelector('#subTotalDisplay');
            const productId = row.dataset.productId;
            const authDiscountPercentage = parseFloat(document.getElementById('auth_discount_percentage')
                .value || 0);
            const errorContainer = row.querySelector('.quantityError');

            // Validate input
            if (returnQuantity < 0) {
                inputField.value = 0;
                return;
            }

            // Extract price values
            const productPrice = parseFloat(productPriceText.replace(/[^\d.]/g, ''));
            const oldSubTotalText = subTotalDisplay.textContent.trim();
            const oldSubTotalValue = parseFloat(oldSubTotalText.replace(/[^\d.]/g, ''));

            // Calculate old subtotal with discount
            let oldSubTotal = isIncreasingQuantity ?
                oldSubTotalValue :
                oldSubTotalValue - ((oldSubTotalValue * authDiscountPercentage) / 100);

            // Validate return quantity
            if (returnQuantity > productQuantity) {
                errorContainer.textContent = 'Return quantity cannot exceed the available quantity';
                inputField.value = productQuantity;
                return;
            } else {
                errorContainer.textContent = '';
            }

            // Calculate new values
            if (!isNaN(productPrice) && !isNaN(oldSubTotal)) {
                const newSubTotalValue = productPrice * returnQuantity;
                const newSubTotal = newSubTotalValue - ((newSubTotalValue * authDiscountPercentage) / 100);

                // Update subtotal display
                subTotalDisplay.textContent = '$' + newSubTotalValue.toFixed(2);

                // Calculate difference and update grand total
                const subTotalDifference = oldSubTotal - newSubTotal;
                const refundableGrandTotal = parseFloat(document.querySelector('.grandTotalToReturn').value
                    .replace(/[^\d.]/g, ''));
                const newRefundableGrandTotal = refundableGrandTotal - subTotalDifference;

                grandTotalToReturn = newRefundableGrandTotal.toFixed(2);
                document.querySelector('.grandTotalToReturn').value = '$' + newRefundableGrandTotal.toFixed(2);

                // Update return order data
                updateReturnOrder(productId, returnQuantity, newSubTotal, 'quantity', newRefundableGrandTotal
                    .toFixed(2));
            }
        }

        /**
         * Remove a product from the return
         */
        function removeProduct(button) {
            const row = button.closest('tr');
            const productId = row.dataset.productId;
            const authDiscountPercentage = parseFloat(document.getElementById('auth_discount_percentage')
                .value || 0);

            // Extract subtotal value
            const subTotalDisplay = row.querySelector('#subTotalDisplay').textContent.trim();
            const subTotalValue = parseFloat(subTotalDisplay.replace(/[^\d.]/g, ''));
            const subTotal = subTotalValue - ((subTotalValue * authDiscountPercentage) / 100);

            // Remove row from table
            row.remove();

            // Update grand total
            const returnableGrandTotalField = document.querySelector('.grandTotalToReturn');
            const returnableGrandTotal = parseFloat(returnableGrandTotalField.value.replace(/[^\d.]/g, ''));
            const newReturnableGrandTotal = returnableGrandTotal - subTotal;

            grandTotalToReturn = newReturnableGrandTotal.toFixed(2);
            returnableGrandTotalField.value = '$' + newReturnableGrandTotal.toFixed(2);

            // Update return order data
            updateReturnOrder(productId, 0, 0, 'remove', newReturnableGrandTotal.toFixed(2));
        }

        /**
         * Send AJAX request to update the return order
         */
        function updateReturnOrder(pId, quantity, productSubTotal, method, returnable) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route('Website.Return.Order.Update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        pId: pId,
                        quantity: quantity,
                        productSubTotal: productSubTotal,
                        method: method,
                        returnable: returnable
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Update successful:', data);
                })
                .catch(error => {
                    console.error('Error updating return order:', error);
                });
        }

        /**
         * Submit the return order
         */
        function submitReturnOrder() {
            if (grandTotalToReturn <= 0) {
                showError('Cannot process a refund with zero or negative amount.');
                return;
            }

            showProcessingOverlay(true);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const invoiceNumber = document.getElementById('order_id').value;
            const paymentMethod = '{{ !empty($order) ? $order->payment_method : '' }}';

            let endpoint;

            // Determine endpoint based on payment method
            if (paymentMethod.toLowerCase() === 'paypal') {
                endpoint = '{{ route('Paypal_Refund') }}';
            } else if (paymentMethod.toLowerCase() === 'stripe') {
                endpoint = '{{ route('Stripe_Refund') }}';
            } else {
                endpoint = '{{ route('Gift_Card_Refund') }}';
            }

            // Process refund
            fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        total: grandTotalToReturn,
                        invoiceNumber: invoiceNumber,
                        updatedData: updatedData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    showProcessingOverlay(false);

                    if (data.status) {
                        showToast('success', data.message);
                        setTimeout(() => {
                            window.location.href = '{{ route('Admin.Return.Orders') }}';
                        }, 2000);
                    } else {
                        showToast('error', data.message || 'An error occurred while processing the refund');
                    }
                })
                .catch(error => {
                    showProcessingOverlay(false);

                    if (paymentMethod.toLowerCase() === 'paypal') {
                        showToast('info', 'Note: PayPal does not offer partial refunds');
                        showToast('error', 'Sorry! PayPal is not accepting the refund of this payment');
                    } else {
                        showToast('error', 'An error occurred while processing the refund');
                    }

                    console.error('Refund error:', error);
                });
        }

        /**
         * Show or hide the processing overlay
         */
        function showProcessingOverlay(show) {
            const overlay = document.querySelector('.processing-overlay');
            if (overlay) {
                if (show) {
                    overlay.classList.remove('d-none');
                } else {
                    overlay.classList.add('d-none');
                }
            }
        }

        /**
         * Show toast notification
         */
        function showToast(type, message) {
            // Get the error container
            const errorDiv = document.querySelector('.errorDiv');

            // Create the alert element
            const alert = document.createElement('div');

            // Map toast types to Bootstrap alert classes
            const alertType = type === 'error' ? 'danger' : type;
            alert.className = `alert alert-${alertType} alert-dismissible fade show`;
            alert.role = 'alert';

            // Set the alert content
            alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

            // Clear any existing alerts
            errorDiv.innerHTML = '';

            // Add to container
            errorDiv.appendChild(alert);

            // Auto remove after 3 seconds
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        }

        /**
         * Show error message
         */
        function showError(message) {
            const errorDiv = document.querySelector('.errorDiv');
            if (errorDiv) {
                errorDiv.innerHTML = `<div class="alert alert-danger">${message}</div>`;

                // Auto remove after 3 seconds
                setTimeout(() => {
                    errorDiv.innerHTML = '';
                }, 3000);
            }
        }
    });
</script>

<script>
    document.getElementById('submitReturnBtn').addEventListener('click', function() {
        const btn = this;
        const iconSpan = btn.querySelector('.btn-icon');

        // Replace icon with a spinner
        iconSpan.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>`;

        // Optionally disable the button to prevent double-clicks
        btn.disabled = true;

        // Simulate processing (e.g., AJAX request)
        setTimeout(() => {
            // For example purposes, reset the button after 3 seconds
            iconSpan.innerHTML = `<i class="fas fa-undo-alt me-2"></i>`;
            btn.disabled = false;
        }, 3000);
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
