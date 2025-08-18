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
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Update Return Invoice</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Invoices</a></li>
                            <li class="breadcrumb-item active">Update Return Invoice</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-7">
                    <form id="return_invoice_edit_form">
                        @csrf
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="fa fa-file-invoice me-2"></i>Invoice Details
                                </h5>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="invoiceNumberSearch"
                                        name="invoice_number" value="{{ $invoice_return->invoice_number }}" readonly>
                                    <label for="invoiceNumberSearch">Invoice Number</label>
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
                                                <th style="width: 40%">Product</th>
                                                <th style="width: 20%">Unit Price</th>
                                                <th style="width: 20%">Total Units</th>
                                                <th style="width: 20%">Return Units</th>
                                            </tr>
                                        </thead>
                                        <tbody class="invoice-section">
                                            @foreach($invoice_return->invoice_return_meta as $key=>$value)
                                                <tr class="invoice-item">
                                                    <td>
                                                        <input type="hidden" name="invoice_id" value="{{$invoice_return->invoice->id}}" id="invoice_id">
                                                        <input type="hidden" name="product_id[]" value="{{$value->product_id}}">
                                                        <input type="hidden" name="type" value="edit">
                                                        <input type="text" name="product_name[]" class="form-control form-control-sm" value="{{$value->products->title ?? 'N/A'}}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="unit_price[]" value="{{$value->unit_price ?? 0}}" min="1" readonly class="form-control form-control-sm">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="units[]" value="{{$value->total_units}}" min="1" readonly class="form-control form-control-sm" id="return_invoice_edit_total_units_{{$value->product_id}}">
                                                    </td>
                                                    <td>
                                                        <div class="item_total_section">
                                                            <input type="number" name="return_units[]" class="form-control form-control-sm product_return_items" value="{{$value->return_units}}" oninput="validateUnitsEdit(this)" data-id="{{$value->product_id}}">
                                                            <div class="invalid-feedback" id="error_message_edit_return_units_{{$value->product_id}}"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row g-3 mt-4">
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Return Date</label>
                                                    <input type="date" name="invoice_date" id="invoice_date" value="{{$invoice_return->return_date}}" class="form-control" {{ $invoice_return->status == 1 ? 'readonly' : '' }}>
                                                </div>
                                                <div>
                                                    <label class="form-label">Active Status</label>
                                                    <select name="status" class="form-select" id="active_check" {{ $invoice_return->status == 1 ? 'disabled' : '' }}>
                                                        <option value="0" {{ $invoice_return->status == 0 ? 'selected' : '' }}>Draft</option>
                                                        <option value="1" {{ $invoice_return->status == 1 ? 'selected' : '' }}>Public</option>
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

                                <div class="text-end mt-4">
                                    @if (Session::has('permissions') && in_array('Save Return', Session::get('permissions')) && $invoice_return->status != 1)
                                        <button type="submit" class="btn btn-success" id="submit_button">
                                            <i class="fa fa-save me-2"></i>Save Changes
                                        </button>
                                    @endif
                                    @if ($invoice_return->status == 1)
                                        <div class="alert alert-warning">
                                            This Return Invoice is Public. You cannot make changes to the Invoice but you can print it.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-header  ">
                            <h5 class="mb-0">
                                <i class="fa fa-file-invoice me-2"></i>Invoice Preview
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="invoice_Area   p-3" style="zoom:70%">
                                @php print($Invoice_review); @endphp
                            </div>
                        </div>
                        <div class="card-footer   text-end">
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
    function validateUnitsEdit(obj) {
        let return_units = $(obj).val();
        product_id = $(obj).attr('data-id');
        let total_units = $('#return_invoice_edit_total_units_' + product_id).val();

        if (Number(total_units) < Number(return_units)) {
            $('#error_message_edit_return_units_' + product_id).text('Returning units must be less than the total units.');
            $(obj).addClass('is-invalid');
            $('#submit_button').prop("disabled", true);
        } else {
            $('#error_message_edit_return_units_' + product_id).text('');
            $(obj).removeClass('is-invalid');
            calculate_sub_total();
            $('#submit_button').prop("disabled", false);
        }
    }

    function calculate_sub_total() {
        var sum = 0;
        $('.product_return_items').each(function() {
            var value = parseFloat($(this).val());
            if (!isNaN(value)) {
                var product_id = $(this).attr('data-id');
                var unit_price = $(this).closest('tr').find('input[name="unit_price[]"]').val();
                sum += value * unit_price;
            }
        });
        $('#total_return_price').val(sum);
    }

    // Calculate initial total on page load
    $(document).ready(function() {
        calculate_sub_total();
        
        @if($invoice_return->status == 1)
            $("#return_invoice_edit_form :input").prop("disabled", true);
        @endif
    });

    $('#return_invoice_edit_form').submit(function(event) {
        event.preventDefault();
        
        if ($('.invoice-item').length == 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Invoice Items',
                text: 'Please select an invoice to save changes.'
            });
            return;
        }

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
    });

    function submitInvoiceReturn(formData, invoiceId) {
        // Show loading spinner
        $('#submit_button').html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $('#submit_button').prop('disabled', true);

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
                    
                    if ($("#active_check").val() == 1) {
                        $("#return_invoice_edit_form :input").prop("disabled", true);
                    }
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
                $('#submit_button').html('<i class="fa fa-save me-2"></i>Save Changes');
                $('#submit_button').prop('disabled', false);
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
</script>

<!-- SweetAlert CDN -->

<!-- Audio for beep sound -->
<audio id="beepSound" preload="auto">
    <source src="{{ url('/') }}/sounds/beep.wav" type="audio/mpeg">
</audio>