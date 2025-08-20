@extends('Website.Includes.header', ['meta_Details' => $meta_details])
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@php
    $setting = \App\Models\GeneralSettings::all();
    use App\General\GeneralSettingsClass;
    $minimum_weight=GeneralSettingsClass::getRequiredValue('_minimum_weight');
    $minimum_items=GeneralSettingsClass::getRequiredValue('_minimum_items');
    
@endphp

<style>
    .contact__info{
        padding: 8px !important ;
    }
</style>

@section('content')
    <!-- Section 1 -->
    <section class="about-sec1">
        <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
        <div class="container text-center">
            <h2>Cart</h2>
            <p><span>Sydney Quality Meats :</span> Cart</p>
        </div>
    </section>
    <!-- Section 1 End -->

    <!-- Section 2 -->
    <section>
        <div class="container BC_Sec2">
            <div class="row">
                <!-- Cart Table -->
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table" id="cart-table">
                            <thead>
                                <tr class="table_heading">
                                    <th class="text-uppercase">ITEM</th>
                                    <th class="text-uppercase">Original value</th>
                                    <th class="text-uppercase">PRICE</th>
                                    <th class="text-uppercase">Custom value</th>
                                    <th class="text-uppercase">Type</th>
                                    <th class="text-center text-uppercase">SUBTOTAL</th>
                                    <th class="text-center text-uppercase">REMOVE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($cartData) > 0)
                                    @foreach ($cartData as $item)
                                    
                                    @php
                                        $is_weight=$item['is_weight'];
                                    @endphp
                                        <tr data-pid="{{ $item['pId'] }}">
                                            <td class="table_row-pad" style="width: 200px;">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item['featuredImage'] ?? 'https://via.placeholder.com/50' }}"
                                                        width="50" class="me-3" alt="{{ $item['title'] }}" />
                                                    <span class="Cart_Title_Break">{{ strlen($item['title']) > 24 ? substr($item['title'], 0, 24) . '...' : $item['title'] }}</span>
                                                </div>
                                            </td>

                                            <td class="table_row-pad">
                                                {{ $item['original_weight'] }}
                                            </td>
                                            <td class="table_row-pad">
                                                ${{ number_format($item['original_price'],2) }}
                                            </td>
                                            <!-- Editable Weight Field -->
                                                <td class="table_row-pad"> 
                                                    <input type="number" 
                                                        class="weight-input custom_values form-control text-center mx-1"
                                                        style="width: 65px; padding: 3px 5px !important; border: 1px solid #A42125;"
                                                        data-pid="{{ $item['pId'] }}"
                                                        data-is_weight="{{ $is_weight }}"
                                                        value="{{ $item['weight'] }}"
                                                        data-min="{{ $is_weight == 1 ? $minimum_weight/1000 : $minimum_items }}"
                                                        placeholder="{{ $is_weight == 1 ? '0.0' : '0' }}"
                                                        pattern="{{ $is_weight == 1 ? '[0-9]+(\.[0-9]+)?' : '[0-9]+' }}"
                                                        min="{{ $is_weight == 1 ? '0.1' : '1' }}"
                                                        step="{{ $is_weight == 1 ? '0.1' : '1' }}"
                                                        title="Enter a valid {{ $is_weight == 1 ? 'decimal' : 'number' }} value">
                                                </td>
                                                <td class="table_row-pad"> 
                                                    <span>{{ $is_weight ? 'Kg' : 'Item' }}</span>
                                                </td> 
                                            <td class="table_row-pad item-subtotal">
                                                ${{ number_format($item['amount'] * $item['quantity'], 2) }}
                                            </td>
                                            <td class="text-start table_row-pad">
                                                <button class="btnRemove btn btn-link text-decoration-none text-dark p-0"
                                                    data-pid="{{ $item['pId'] }}">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="mb-0">Your cart is empty</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Optional: Coupon row, etc. -->
                    <div class="row mt-4">
                        <!--
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control Coupon_Code_Input" placeholder="Coupon code" />
                            </div>
                        </div>
                        -->
                        <div class="col-12 text-end BC_Table_Sec2_Buttons">
                            <!--
                            <button class="btn btn-danger BC_Table_Sec2_Button1" type="button">
                                APPLY COUPON
                            </button>
                            -->
                            <button class="btn btn-danger btnClearCart BC_Table_Sec2_Button2 ms-xl-4" type="button">
                                CLEAR CART
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cart Totals -->
                @php
                
                    $subTotal = session('subTotal');
                    $totalGst = session('totalGst');
                    $totalAmount = session('totalAmount');
                @endphp
                <div class="col-lg-4 BC_Sec2_Part2">
                    <div class="card card_border">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">CART TOTALS</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span>SUBTOTAL</span>
                                <span class="cart-subtotal">${{ number_format($subTotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <span>TOTAL</span>
                                <span class="cart-total">${{ number_format($subTotal, 2) }}</span>
                            </div>
                            <span style="font-size: 12px; color: #A42125;" class="text-capitalize">
                                * Charges may vary at check out
                            </span>
                         <a href="/checkout" class="checkout-link">
                            <button class="btn checkout-btn btn-secondary w-100">CHECKOUT</button>
                        </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmClearCartModal" tabindex="-1" aria-labelledby="confirmClearCartModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmClearCartModalLabel">Clear Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to clear your cart?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary Cancel_Cart_Btn_color rounded-0" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger Clear_Cart_Btn_color rounded-0" id="confirmClearCart">
                        Yes, Clear Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Section 2 End -->

@endsection

<style>
/* Optional: Add some visual styling for the disabled state */
    .checkout-link.disabled .checkout-btn {
        opacity: 0.6;
        cursor: not-allowed !important;
    }
</style>
@push('scripts')
<script>
    $(document).ready(function () {

        // Ensure we have our CSRF token set:
        var csrfToken = $('meta[name="csrf-token"]').attr('content') ||
                $('input[name="_token"]').val() ||
                '{{ csrf_token() }}';
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': csrfToken }
        });

        // Add this function at the start
       function updateCheckoutButtonState() {
            if ($('#cart-table tbody tr').length === 0 || $('#cart-table tbody tr td[colspan="6"]').length > 0) {
                $('.checkout-btn').css('cursor', 'not-allowed');
                $('.checkout-link').addClass('disabled').css('pointer-events', 'auto').removeAttr('href');
            } else {
                $('.checkout-btn').css('cursor', 'pointer');
                $('.checkout-link').removeClass('disabled').css('pointer-events', 'auto').attr('href', '/checkout');
            }
        }

        // Call it on page load
        updateCheckoutButtonState();

        // Add to existing checkCartEmpty function
        function checkCartEmpty() {
            if ($('#cart-table tbody tr').length === 0) {
                $('#cart-table tbody').html(
                    '<tr><td colspan="6" class="text-center py-4"><p class="mb-0">Your cart is empty</p></td></tr>'
                );
                updateCheckoutButtonState();
            }
        }

        /**
         * Prevent decimal input for non-weight items (is_weight != 1)
         */
        $(document).on('keypress', '.weight-input', function(e) {
            let $input = $(this);
            let is_weight = $input.data('is_weight');

            // If it's not a weight item (is_weight != 1), only allow digits
            if (is_weight != 1) {
                // Allow: backspace, delete, tab, escape, enter
                if ($.inArray(e.keyCode, [8, 9, 27, 13]) !== -1 ||
                    // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode === 67 && e.ctrlKey === true) ||
                    (e.keyCode === 86 && e.ctrlKey === true) ||
                    (e.keyCode === 88 && e.ctrlKey === true)) {
                    return;
                }
                // Only allow digits (0-9) - prevent everything else including decimal points
                if (e.keyCode < 48 || e.keyCode > 57) {
                    e.preventDefault();
                }
            } else {
        
            }
        });

        /**
         * Additional validation on paste event
         */
        $(document).on('paste', '.weight-input', function(e) {
            let $input = $(this);
            let is_weight = $input.data('is_weight');

            setTimeout(function() {
                let pastedValue = $input.val();

                if (is_weight != 1) {
                    // For non-weight items, remove any non-digit characters (including decimal points)
                    // This will turn "3.4" into "34"
                    let cleanedValue = pastedValue.replace(/[^\d]/g, '');
                    $input.val(cleanedValue);
                } else {
                    // For weight items, ensure only one decimal point
                    let parts = pastedValue.split('.');
                    if (parts.length > 2) {
                        $input.val(parts[0] + '.' + parts.slice(1).join(''));
                    }
                }
            }, 10);
        });
        

        /**
         * On input: Clean out bad characters but do NOT force 0 if empty.
         */
       $(document).on('input', '.weight-input', function () {
            let $input = $(this);
            let rawVal = $input.val();
            
            // Don't modify the input value here - let user type freely
            // Only validate and update calculations

            // Try to parse as float
            let newWeight = parseFloat(rawVal);

            // If valid (including 0), recalc row subtotal
            if (!isNaN(newWeight) && newWeight >= 0) {
                let $row = $input.closest('tr');
                let currentPrice = parseFloat(
                    $row.find('td:nth-child(3)').text().replace('$', '').replace(',', '')
                );
                let originalWeight = parseFloat(
                    $row.find('td:nth-child(2)').text().replace(',', '')
                );

                let pricePerKg = currentPrice / originalWeight;
                let newSubtotal = pricePerKg * newWeight;

                $row.find('.item-subtotal').text('$' + newSubtotal.toFixed(2));
                updateCartTotals();

                // (Optional) Live server update:
                // updateWeightOnServer($input.data('pid'), newWeight);
            } else if (rawVal === '' || rawVal === '.') {
                // Handle empty input or just a decimal point
                let $row = $input.closest('tr');
                $row.find('.item-subtotal').text('$0.00');
                updateCartTotals();
            } else {
                // Invalid input - show $0.00
                let $row = $input.closest('tr');
                $row.find('.item-subtotal').text('$0.00');
                updateCartTotals();
            }
        });

        /**
         * On blur: If empty or invalid, force it to "0". Then update server.
         */
       $(document).on('blur', '.weight-input', function() {
        let $input = $(this);
        let rawVal = $input.val().trim();
        let minimumWeight = parseFloat('{{ $minimum_weight }}') / 1000; // Converted to kg
        let minimumItem = parseFloat('{{ $minimum_items }}');
        let is_weight = $input.data('is_weight');

        let newWeight = parseFloat(rawVal);

        // If empty, invalid, or less than 0 => set to minimum weight
        if (rawVal === '' || isNaN(newWeight) || newWeight < 0) {
            if (is_weight == 1) {
                newWeight = minimumWeight;
                $input.val(minimumWeight.toFixed(1));
            } else {
                newWeight = minimumItem;
                $input.val(minimumItem.toString());
            }
        } else if (is_weight == 1 && newWeight < minimumWeight) {
            // If weight is less than minimum weight, set to minimum
            // But allow values that are very close to minimum (within 0.001 tolerance)
            if (Math.abs(newWeight - minimumWeight) > 0.001) {
                newWeight = minimumWeight;
                $input.val(minimumWeight.toFixed(1));
            } else {
                // Value is close enough to minimum, keep it as is
                $input.val(newWeight.toFixed(1));
            }
        } else if (is_weight != 1 && newWeight < minimumItem) {
            // If item count is less than minimum items, set to minimum
            newWeight = minimumItem;
            $input.val(minimumItem.toString());
        } else {
            // Valid weight, format it properly for weight products
            if (is_weight == 1) {
                $input.val(newWeight.toFixed(1));
            } else {
                $input.val(newWeight.toString());
            }
        }
        
        // Calculate new subtotal
        let $row = $input.closest('tr');
        let currentPrice = parseFloat(
            $row.find('td:nth-child(3)').text().replace('$', '').replace(',', '')
        );
        let originalWeight = parseFloat(
            $row.find('td:nth-child(2)').text().replace(',', '')
        );

        let pricePerKg = currentPrice / originalWeight;
        let newSubtotal = pricePerKg * newWeight;

        $row.find('.item-subtotal').text('$' + newSubtotal.toFixed(2));
        updateCartTotals();

        // Update weight on server
        updateWeightOnServer($input.data('pid'), newWeight);
    })

        /**
         * Helper: Send AJAX to update weight in the backend.
         */
        function updateWeightOnServer(productId, weightValue) {
            $.ajax({
                url: '{{ url('update-weight') }}',
                method: 'POST',
                data: {
                    pId: productId,
                    weight: weightValue
                },
                success: function (response) {
                    if (response.status === true) {
                        // Update the overall cart totals from server response
                        $('.cart-subtotal').text('$' + parseFloat(response.subTotal).toFixed(2));
                        $('.cart-total').text('$' + parseFloat(response.subTotal).toFixed(2));
                    } else {
                        alert(response.message || 'Could not update weight.');
                    }
                },
                error: function () {
                    console.error('Error calling update-weight');
                }
            });
        }

        /**
         * Function to update overall cart totals on the page
         */
        function updateCartTotals() {
            let subtotal = 0;
            $('#cart-table tbody tr').each(function () {
                let itemSubtotal = parseFloat(
                    $(this).find('.item-subtotal').text().replace('$', '').replace(',', '')
                );
                if (!isNaN(itemSubtotal)) {
                    subtotal += itemSubtotal;
                }
            });
            $('.cart-subtotal').text('$' + subtotal.toFixed(2));
            $('.cart-total').text('$' + subtotal.toFixed(2));
        }

        /**
         * Clear Cart - show confirmation modal, then clear
         */
        $(document).on('click', '.btnClearCart', function (e) {
            e.preventDefault();
            // Show the Bootstrap modal
            $('#confirmClearCartModal').modal('show');
        });

        // When user confirms "Yes, Clear Cart"
        $(document).on('click', '#confirmClearCart', function () {
            $('#confirmClearCartModal').modal('hide');
            $.ajax({
                url: '{{ url('clear-cart') }}',
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        // Remove all rows & reset totals
                        $('#cart-table tbody').empty();
                        $('.cart-subtotal').text('$0.00');
                        $('.cart-total').text('$0.00');
                        $('.cart-badge').text(response.totalProducts);
                        checkCartEmpty();
                        updateCheckoutButtonState();
                    } else {
                        alert(response.message || 'Error clearing cart.');
                    }
                },
                error: function (err) {
                    console.error(err);
                    alert('Error clearing cart.');
                }
            });
        });

        /**
         * Remove individual item from cart
         */
       $('.btnRemove').on('click', function (e) {
    e.preventDefault();

    
    let pId = $(this).data('pid');
    $.ajax({
        url: '{{ url('remove-from-cart') }}',
        method: 'POST',
        data: {
            pIdToRemove: pId,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            if (response.status === true) {
                // Remove that row
                $('tr[data-pid="' + pId + '"]').remove();
                // Update displayed totals
                $('.cart-subtotal').text('$' + parseFloat(response.subTotal).toFixed(2));
                $('.cart-total').text('$' + parseFloat(response.totalAmount).toFixed(2));
                // Update navbar cart count
                $('.cart-badge').text(response.totalProducts);
                checkCartEmpty();
                updateCheckoutButtonState();
            } else {
                alert(response.message || 'Could not remove item.');
            }
        },
        error: function () {
            alert('Error removing item.');
        }
    });
});

    });
</script>
@endpush