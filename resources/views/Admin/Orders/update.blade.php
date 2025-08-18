@include('Admin.Includes.header')
@php
use App\Models\GeneralSettings;

$settings = GeneralSettings::whereIn('option', ['_gst_percentage', '_minimum_weight', '_minimum_items'])->pluck('value', 'option');
$gst_percentage = $settings['_gst_percentage'];
$gst_percentage = $gst_percentage <= 0 || empty($gst_percentage) ? 0 : $gst_percentage;
$minimum_items = $settings['_minimum_items'] ?? '0';
$minimum_weight = $settings['_minimum_weight'] / 1000 ?? '0';
@endphp
<div class="page-content">
    <div class="container">
        <h2>Update Order #{{ $order->id }}</h2>

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th rowspan="2">Product Name</th>
                        <th colspan="2" class="text-center">Original Values</th>
                        <th colspan="3" class="text-center">Updated Values</th>
                    </tr>
                    <tr>
                        <th>Weight (kg)/Qty</th>
                        <th>Price ($)</th>
                        <th>Weight (kg)/Qty</th>
                        <th>Type</th>
                        <th>Price ($)</th>
                        <th>GST ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalGst = 0;
                    $deliveryCharges = 0;
                    @endphp
                    @foreach ($order->orderMeta as $meta)
                    @php
                    $totalGst += $meta->totalGst ?? 0;
                    $deliveryCharges += $meta->delivery_charges ?? 0;

                    $gstMeta = $meta->product->productMeta->firstWhere('option', '_gst');
                    $weight = $meta->product->productMeta->firstWhere('option', '_is_weight');
                    $gstValue = $gstMeta ? $gstMeta->value : 0;
                    $is_weight = $weight ? $weight->value : 0;
                    @endphp
                    <tr data-gst-value="{{ $gstValue }}">
                        <td>{{ $meta->product_title }}</td>
                        <!-- Original Values -->
                        <td>
                            <input type="text" class="form-control original-weight"
                                value="{{ $meta->quantity ?? '' }}" readonly>
                        </td>
                        <td>
                            <input type="text" class="form-control original-price"
                                value="{{ number_format($meta->qp_subtotal, 2, '.', '') }}" readonly>
                        </td>
                        <!-- Updated Values -->
                        <td>
                            <input type="number" class="form-control weight-input"
                                name="weights[{{ $meta->id }}]"
                                data-min-weight="{{ $minimum_weight }}"
                                data-min-items="{{ $minimum_items }}"
                                data-is-weight="{{ $is_weight }}"
                                value="{{ $meta->quantity ?? '' }}"
                                min="{{ $is_weight == 1 ? $minimum_weight : $minimum_items }}"
                                step="{{ $is_weight == 1 ? '0.1' : '1' }}"
                               >
                            <span class="weight-error" style="font-size: 11px; color: #dc3545; display: none;">
                                @if($is_weight == 1)
                                Minimum weight is {{$minimum_weight }}kg
                                @else
                                Minimum item is {{$minimum_items }}
                                @endif
                            </span>
                        </td>

                        <td><span class="text-danger">{{$is_weight==1 ? 'Kg':'Item' }}</span></td>

                        <td>
                            <input type="text" class="form-control price-input"
                                name="prices[{{ $meta->id }}]"
                                value="{{ number_format($meta->qp_subtotal, 2, '.', '') }}" readonly>
                        </td>
                        <td>
                            <input type="text" class="form-control gst-input" name="gst[{{ $meta->id }}]"
                                value="{{ number_format($meta->totalGst, 2, '.', '') }}" readonly>
                        </td>
                    </tr>
                    @endforeach

                    <!-- Summary rows -->
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2" class="text-right"><strong>Subtotal:</strong></td>
                        <td>
                            <input type="text" id="subtotal" class="form-control" value="0.00" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2" class="text-right"><strong>Delivery Charges:</strong></td>
                        <td>
                            <input type="text" id="deliveryCharges" class="form-control"
                                value="{{ number_format($order->delivery_charges, 2, '.', '') }}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2" class="text-right"><strong>Total GST {{ $gst_percentage == 0 ? '' : "({$gst_percentage}%)" }}:</strong></td>
                        <td>
                            <input type="text" id="totalGst" name="total_gst" class="form-control"
                                value="{{ number_format($order->totalGst, 2, '.', '') }}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2" class="text-right"><strong>Grand Total:</strong></td>
                        <td>
                            <input type="text" name="grand_total" id="grandTotal" class="form-control" value="0.00"
                                readonly>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="text-align: end;">
                <button type="submit" class="btn btn-primary">Save Order</button>
            </div>
        </form>
    </div>
</div>
<style>
    .table thead th {
        text-align: center;
        vertical-align: middle;
    }

    .form-control {
        min-width: 100px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const submitButton = document.querySelector('button[type="submit"]');
    const GST_PERCENTAGE = {{ $gst_percentage }};  // This value comes from the backend

    // Function to validate min weight/item for each input
    function validateMinWeight(input) {
        const minWeight = parseFloat(input.getAttribute('data-min-weight')) || 0.5;
        const minItems = parseInt(input.getAttribute('data-min-items')) || 1;
        const isWeight = input.getAttribute('data-is-weight') == '1'; // Check if the product is weight-based
        const value = parseFloat(input.value) || 0;
        const errorSpan = input.parentElement.querySelector('.weight-error');

        if (isWeight) {
            // For weight products
            if (value < minWeight || isNaN(value)) {
                input.style.borderColor = '#dc3545';
                input.classList.add('invalid-weight');
                if (errorSpan) {
                    errorSpan.style.display = 'block';
                    errorSpan.textContent = `Minimum weight is ${minWeight}kg`;
                }
            } else {
                input.style.borderColor = '';
                input.classList.remove('invalid-weight');
                if (errorSpan) {
                    errorSpan.style.display = 'none';
                }
            }
        } else {
            // For item-based products
            if (value < minItems || isNaN(value)) {
                input.style.borderColor = '#dc3545';
                input.classList.add('invalid-weight');
                if (errorSpan) {
                    errorSpan.style.display = 'block';
                    errorSpan.textContent = `Minimum item quantity is ${minItems}`;
                }
            } else {
                input.style.borderColor = '';
                input.classList.remove('invalid-weight');
                if (errorSpan) {
                    errorSpan.style.display = 'none';
                }
            }
        }

        // Check if any weight or item input has invalid value
        checkSubmitButtonState();
    }

    // Function to check the submit button state (enabled/disabled)
    function checkSubmitButtonState() {
        let hasInvalidInput = false;

        document.querySelectorAll('.weight-input').forEach(function(input) {
            const minWeight = parseFloat(input.getAttribute('data-min-weight')) || 0.5;
            const minItems = parseInt(input.getAttribute('data-min-items')) || 1;
            const isWeight = input.getAttribute('data-is-weight') == '1'; // Check if the product is weight-based
            const value = parseFloat(input.value) || 0;

            if (isWeight) {
                if (value < minWeight || isNaN(value)) {
                    hasInvalidInput = true;
                }
            } else {
                if (value < minItems || isNaN(value)) {
                    hasInvalidInput = true;
                }
            }
        });

        if (hasInvalidInput) {
            submitButton.disabled = true;
            submitButton.style.opacity = '0.5';
            submitButton.style.cursor = 'not-allowed';
        } else {
            submitButton.disabled = false;
            submitButton.style.opacity = '1';
            submitButton.style.cursor = 'pointer';
        }
    }

    // Function to update the totals (subtotal, gst, grand total)
    function updateTotals() {
        let subtotal = 0;
        let totalGst = 0;
        let totalDeliveryCharges = parseFloat(document.querySelector('#deliveryCharges').value) || 0;

        // Loop through each price input and calculate the subtotal and gst
        document.querySelectorAll('.price-input').forEach(function(input) {
            const price = parseFloat(input.value) || 0;
            const gstValue = parseInt(input.closest('tr').getAttribute('data-gst-value')) || 0;

            let gst = 0;
            if (gstValue === 1) {
                gst = (price * GST_PERCENTAGE) / 100;
            }

            totalGst += gst;
            subtotal += price;

            input.closest('tr').querySelector('.gst-input').value = gst.toFixed(2);
        });

        // Calculate GST on delivery charges
        let deliveryChargesGst = (totalDeliveryCharges * GST_PERCENTAGE) / 100;
        totalGst += deliveryChargesGst;

        document.querySelector('#subtotal').value = subtotal.toFixed(2);
        document.querySelector('#totalGst').value = totalGst.toFixed(2);
        document.querySelector('#grandTotal').value = (subtotal + totalGst + totalDeliveryCharges).toFixed(2);
    }

    // Function to calculate GST based on price and GST percentage
    function calculateGst(price) {
        return (price * GST_PERCENTAGE) / 100;
    }

    // Function to calculate price based on weight and original price per unit
    function calculatePriceFromWeight(input) {
        const weight = parseFloat(input.value) || 0;
        const originalWeight = parseFloat(input.closest('tr').querySelector('.original-weight').value) || 0;
        const originalPrice = parseFloat(input.closest('tr').querySelector('.original-price').value) || 0;
        
        if (originalWeight > 0) {
            const pricePerUnit = originalPrice / originalWeight;
            const newPrice = weight * pricePerUnit;
            return newPrice;
        }
        return 0;
    }

    // Function to update price and GST for a specific row
    function updateRowPrice(input) {
        const newPrice = calculatePriceFromWeight(input);
        const priceInput = input.closest('tr').querySelector('.price-input');
        const gstInput = input.closest('tr').querySelector('.gst-input');
        const gstValue = parseInt(input.closest('tr').getAttribute('data-gst-value')) || 0;
        
        priceInput.value = newPrice.toFixed(2);
        
        let gst = 0;
        if (gstValue === 1) {
            gst = (newPrice * GST_PERCENTAGE) / 100;
        }
        gstInput.value = gst.toFixed(2);
        
        // Update totals after price change
        updateTotals();
    }

    // Add event listeners for input and blur for weight input fields
    document.querySelectorAll('.weight-input').forEach(function(input) {
        // Validate input on typing
        input.addEventListener('input', function() {
            validateMinWeight(input);
            updateRowPrice(input);
        });

        // Validate input when the input field loses focus
        input.addEventListener('blur', function() {
            validateMinWeight(input);
            updateRowPrice(input);
        });
    });

    // Initialize all totals on page load
    updateTotals();

    // Prevent form submission if there are invalid inputs
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        let hasError = false;
        document.querySelectorAll('.weight-input').forEach(function(input) {
            const minWeight = parseFloat(input.getAttribute('data-min-weight')) || 0.5;
            const minItems = parseInt(input.getAttribute('data-min-items')) || 1;
            const isWeight = input.getAttribute('data-is-weight') == '1'; // Check if the product is weight-based
            const value = parseFloat(input.value) || 0;

            if (isWeight) {
                if (value < minWeight || isNaN(value)) {
                    validateMinWeight(input);
                    hasError = true;
                }
            } else {
                if (value < minItems || isNaN(value)) {
                    validateMinWeight(input);
                    hasError = true;
                }
            }
        });

        if (hasError) {
            e.preventDefault();
            alert("Please ensure all product quantities/weights meet the minimum requirement.");
        }
    });
});
 

document.addEventListener('DOMContentLoaded', function() {
    const weightInputs = document.querySelectorAll('.weight-input');

        weightInputs.forEach(function(input) {
            input.addEventListener('keypress', function(e) {
                const isWeight = parseInt(this.dataset.isWeight);

                // If not weight-based (items), prevent decimal point entry
                if (isWeight !== 1) {
                    if (e.key === '.' || e.key === ',') {
                        e.preventDefault();
                    }
                }
            });

            input.addEventListener('input', function() {
                const isWeight = parseInt(this.dataset.isWeight);
                const value = this.value;

                // For items, validate no decimal values
                if (isWeight !== 1 && (value.includes('.') || value.includes(','))) {
                    // Remove decimal part
                    this.value = Math.floor(parseFloat(value)) || '';
                }
                
                // Update price when weight changes
                updateRowPrice(this);
            });
        });
    });


</script>
