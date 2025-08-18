@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\ProductClass;
    use App\General\UserClass;
    $logged_in_user = Auth::user();
    $GST_PERCENTAGE = config('app.PERCENTAGE') ?? 0;
@endphp

<div class="page-content bg-light">
    <div class="container-fluid py-4">
        <div class="card shadow-sm mb-4">
            <div class="pageheader pd-t-25 pd-b-35">
                <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <div class="clearfix">
                        <div class="pd-t-5 pd-b-5">
                            <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Create Return Invoice') }}</h1>
                        </div>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/admin/invoices') }}" class="link-primary text-decoration-none">
                                        Invoices
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Create Return Invoice
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ url('/') }}/admin/invoices" class="btn btn-primary">
                            <i class="fa-solid fa-list-ul me-1"></i> Invoice List
                        </a>
                        <a href="{{ url('/') }}/admin/invoices_return" class="btn btn-primary">
                            <i class="fa-solid fa-list-ul me-1"></i> Returned Invoices List
                        </a>

                    </div>
                </div>
            </div>
        </div>


        <div class="card-body">
            <div class="row">
                <div class="col-lg-7">
                    <form id="return_invoice_form">
                        @csrf
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="fa fa-search me-2"></i>Find Invoice
                                </h5>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="invoiceNumberSearch"
                                        name="invoice_number" oninput="getInvoices(this)">
                                    <label for="invoiceNumberSearch">Invoice Number</label>
                                </div>
                                <div class="searched_invoices rounded bg-light p-2"
                                    style="max-height:200px; overflow-y:auto;">
                                    <!-- Search results will appear here -->
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="fa fa-shopping-cart me-2"></i>Return Items
                                </h5>

                                <div class="table-responsive">
                                    <table class="table table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 30%">Product</th>
                                                <th style="width: 10%">Unit Price</th>
                                                <th style="width: 10%">Total Units</th>
                                                <th style="width: 10%">Discount</th>
                                                <th style="width: 15%">Additional</th>
                                                <th style="width: 15%">Membership</th>
                                                <th style="width: 10%">Return Units</th>
                                            </tr>
                                        </thead>
                                        <tbody class="invoice-section">
                                            <!-- Invoice items will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>

                                <div class="invoice-details mt-4">
                                    <!-- Invoice details will be loaded here -->
                                </div>

                                <div class="text-end mt-4">
                                    @if (Session::has('permissions') && in_array('Save Return', Session::get('permissions')))
                                        <button type="submit" class="btn btn-success"
                                            id="return_invoice_submit_button">
                                            <i class="fa fa-save me-2"></i>Save Changes
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 20px;z-index: 3;">
                        <div class="card-header  ">
                            <h5 class="mb-0">
                                <i class="fa fa-file-invoice me-2"></i>Invoice Preview
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="invoice_Area   p-3" style="zoom:70%">
                                <!-- Preview will be shown here -->
                            </div>
                        </div>
                        <div class="card-footer   text-end" id="print_create_invoice_return" style="display:none">
                            @if (Session::has('permissions') && in_array('Print Return', Session::get('permissions')))
                                <button type="button" class="btn btn-outline-primary" onclick="printInvoice();">
                                    <i class="fa fa-print me-2"></i>Print Invoice
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let isFetchingInvoiceDetails = false; // Add a flag to track fetching state

    function getInvoices(obj) {
        let invoice_number = $(obj).val();
        const resultsSection = document.querySelector('.searched_invoices');
        if (invoice_number === '') {
            resultsSection.innerHTML = ''; // Clear results
            return;
        }

        fetch(`/admin/search/invoices?query=${invoice_number}`)
            .then(response => response.json())
            .then(data => {
                const invoices = data.invoices;
                resultsSection.innerHTML = ''; // Clear previous results

                if (invoices.length > 0) {
                    const table = document.createElement('table');
                    table.className = 'table table-hover table-sm';

                    const thead = document.createElement('thead');
                    thead.className = 'table-light';
                    thead.innerHTML = `
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Created By</th>
                        </tr>
                    `;
                    table.appendChild(thead);

                    const tbody = document.createElement('tbody');
                    invoices.forEach(invoice => {
                        const tr = document.createElement('tr');
                        tr.className = 'cursor-pointer';
                        tr.style.cursor = 'pointer';
                        tr.onclick = function() {
                            getInvoiceDetail(invoice.id);
                        };
                        tr.innerHTML = `
                            <td>${invoice.invoice_number}</td>
                            <td>${invoice.customer}</td>
                            <td>${invoice.invoice_date}</td>
                            <td>${invoice.created_by}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                    table.appendChild(tbody);
                    resultsSection.appendChild(table);
                } else {
                    resultsSection.innerHTML =
                        '<div class="alert alert-warning">Invoice not found or invoice not public.</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
                resultsSection.innerHTML =
                    '<div class="alert alert-danger">Error fetching invoices. Please try again later.</div>';
            });
    }

    function getInvoiceDetail(invoice_id) {
        if (isFetchingInvoiceDetails) return; // Prevent multiple fetch requests
        isFetchingInvoiceDetails = true; // Set the flag to true

        $('.invoice-section').html(''); // Clear previous details
        fetch(`/admin/search/invoices/select?invoice_id=${invoice_id}`)
            .then(response => response.json())
            .then(data => {
                const invoiceSection = $('.invoice-section');

                // Add invoice items to the section
                for (let i = 0; i < data.meta.length; i++) {
                    const meta = data.meta[i];
                    console.log(data, ' dfas');
                    if (meta.units > 0) {
                        invoiceSection.append(`
                        <tr class="invoice-item">
                            <td>
                                <input type="hidden" name="invoice_id" value="${invoice_id}" id="invoice_id">
                                <input type="hidden" name="product_id[]" value="${meta.product_id}">
                                <input type="text" name="product_name[]" class="form-control form-control-sm" value="${meta.product_name}" readonly>
                            </td>
                            <td>
                                <input type="number" name="unit_price[]" value="${meta.unit_price}" min="1" readonly class="form-control form-control-sm return_products_unit_price" id="return_invoice_units_price_${meta.product_id}">
                            </td>
                            <td>
                                <input type="number" name="units[]" value="${meta.units}" min="1" readonly class="form-control form-control-sm" id="return_invoice_total_units_${meta.product_id}">
                            </td>
                            <td>
                                <input type="number" name="discount[]" value="${data.discount}" min="0" readonly class="form-control form-control-sm" id="return_invoice_discount_${meta.product_id}">
                            </td>
                            <td>
                                <input type="number" name="additional_discount[]" value="${data.additional_discount}" min="0" readonly class="form-control form-control-sm" id="return_invoice_additional_discount_${meta.product_id}">
                            </td>
                            <td>
                                <input type="number" name="membership_discount[]" value="${data.membership_discount}" min="0" readonly class="form-control form-control-sm" id="return_invoice_membership_discount_${meta.product_id}">
                            </td>
                            <td>
                                <div class="item_total_section">
                                    <input type="number" name="return_units[]" min="1" class="form-control form-control-sm product_return_items" oninput="validateUnits(this)" data-id="${meta.product_id}">
                                    <div class="invalid-feedback" id="error_message_return_units_${meta.product_id}"></div>
                                </div>
                            </td>
                        </tr>
                        `);
                    }
                }

                // Invoice details
                const invoiceDetails = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Invoice Date</label>
                                    <input type="date" readonly name="invoice_date" id="invoice_date" value="${data.invoice.invoice_date}" class="form-control">
                                </div>
                                <div>
                                    <label class="form-label">Active Status</label>
                                    <select name="status" class="form-select" id="active_check" required>
                                        <option value="0" ${(data.invoice.active == 0) ? 'selected' : ''}>Draft</option>
                                        <option value="1" ${(data.invoice.active == 1) ? 'selected' : ''}>Public</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Total Return Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-money-bill"></i></span>
                                        <input type="number" readonly name="total_return_price" id="total_return_price" value="0" class="form-control form-control-lg fw-bold">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;

                // Add the invoice details section
                $('.invoice-details').html(invoiceDetails);

                // Update invoice preview
                $('.invoice_Area').html(data.invoice_review);
                $('#print_create_invoice_return').show();
            })
            .catch(error => {
                console.error('Error fetching invoice details:', error);
                $('.invoice-section').html(
                    '<div class="alert alert-danger">Error fetching invoice details. Please try again later.</div>'
                );
            })
            .finally(() => isFetchingInvoiceDetails = false); // Reset the flag
    }

    function validateUnits(obj) {
        let return_units = $(obj).val();
        product_id = $(obj).attr('data-id');
        let total_units = $('#return_invoice_total_units_' + product_id).val();

        if (Number(total_units) < Number(return_units)) {
            $('#error_message_return_units_' + product_id).text('Returning units must be less than the total units.');
            $(obj).addClass('is-invalid');
            $('#return_invoice_submit_button').prop("disabled", true);
        } else {
            $('#error_message_return_units_' + product_id).text('');
            $(obj).removeClass('is-invalid');
            calculate_sub_total(return_units, product_id);
            $('#return_invoice_submit_button').prop("disabled", false);
        }
    }

    function calculate_sub_total(return_units, id) {
        var sum = 0;
        console.log('calculate_sub_total')
        $('.product_return_items').each(function() {
            var value = parseFloat($(this).val());
            if (!isNaN(value)) {
                var product_id = $(this).attr('data-id')
                var unit_price = $('#return_invoice_units_price_' + product_id).val();
                sum += value * unit_price;
            }
        });
        $('#total_return_price').val(sum);
    }

    $('#return_invoice_form').submit(function(event) {
        event.preventDefault();
        if ($('.invoice-item').length == 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Invoice Selected',
                text: 'Please select an invoice to save changes.'
            });
        } else {
            var formData = $(this).serialize();
            var invoiceId = $('#invoice_id').val();

            if ($("#active_check").val() == 1) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Making this invoice public will update store stock and you cannot make changes anymore.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, make it public!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitInvoiceReturn(formData, invoiceId);
                    }
                });
            } else {
                submitInvoiceReturn(formData, invoiceId);
            }
        }
    });

    function submitInvoiceReturn(formData, invoiceId) {
        // Show loading spinner
        $('#return_invoice_submit_button').html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $('#return_invoice_submit_button').prop('disabled', true);

        $.ajax({
            url: '{{ route('invoices_return.update', ['invoices_return' => ':invoiceId']) }}'.replace(
                ':invoiceId', invoiceId),
            type: 'PUT',
            data: formData,
            success: function(response) {
                if (response.status) {
                    $('.invoice_Area').html(response.invoice_review);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error saving invoice: ' + errorThrown
                });
            },
            complete: function() {
                // Reset button state
                $('#return_invoice_submit_button').html('<i class="fa fa-save me-2"></i>Save Changes');
                $('#return_invoice_submit_button').prop('disabled', false);
            }
        });
    }

    function printInvoice() {
        var invoiceArea = document.querySelector('.invoice_Area');
        var printWindow = window.open('', '', 'width=1200,height=720');
        var invoice_number = $('#id_invoice_number').text();
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print Invoice - ' + invoice_number +
            '</title><link href="{{ url('/') }}/AdminAssets/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"></head><body>'
        );
        printWindow.document.write('<div class="invoice_Area">' + invoiceArea.innerHTML + '</div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        printWindow.print();
    }

    function play_beep() {
        var beepSound = document.getElementById("beepSound");
        beepSound.play();
    }
</script>


<!-- Audio for beep sound -->
<audio id="beepSound" preload="auto">
    <source src="{{ url('/') }}/sounds/beep.wav" type="audio/mpeg">
</audio>
