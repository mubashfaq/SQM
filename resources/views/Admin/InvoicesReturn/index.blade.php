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
                            <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Return Invoice List') }}</h1>
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
                                    Return Invoice List
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ url('/') }}/admin/invoices_return/create" class="btn btn-success">
                            <i class="fa-solid fa-plus me-1"></i> Create Invoice
                        </a>

                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title mb-0">Invoices Table</h5>
            </div>
            <div class="card-body  ">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data_Table" class="table table-borderless  align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Invocie Number</th>
                                    <th>Customer</th>
                                    <th>items</th>
                                    <th>Detail</th>
                                    <th>Payment Status</th>
                                    <th>Payment Method</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
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
                                            <td>{{ $invoice->total_units }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-soft-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal-{{ $invoice->invoice_number }}">
                                                    View
                                                </button>
                                            </td>
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
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm text-dark rounded shadow-sm"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                                        style="background-color: #c2d6e7;">
                                                        &#8942; {{-- vertical three-dot icon --}}
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if (Session::has('permissions') && in_array('Print Invoice', Session::get('permissions')))
                                                            <li>
                                                                <button type="button"
                                                                    class="dropdown-item generate-pdf-button"
                                                                    data-invoice-id="{{ $invoice->id }}">
                                                                    <i class="fa fa-print me-2"></i> Print Invoice
                                                                </button>
                                                            </li>
                                                        @endif

                                                        @if (Session::has('permissions') && in_array('Invoice Edit', Session::get('permissions')))
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('invoices_return.edit', $invoice->id) }}">
                                                                    @if ($invoice->active == 1)
                                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endif

                                                        @if ($invoice->active == 0)
                                                            @if (Session::has('permissions') && in_array('Invoice Delete', Session::get('permissions')))
                                                                <li>
                                                                    <button
                                                                        class="dropdown-item text-danger destroy-invoice"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#deleteConfirmModal{{ $invoice->id }}"
                                                                        data-url="{{ route('invoice_return.is_delete', $invoice->id) }}">
                                                                        <i class="bi bi-trash me-2"></i> Delete
                                                                    </button>
                                                                </li>
                                                            @endif
                                                        @endif
                                                    </ul>

                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade zoomIn" id="deleteConfirmModal{{ $invoice->id }}"
                                            tabindex="-1" aria-labelledby="deleteConfirmModalLabel{{ $invoice->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-md-5">
                                                        <div class="text-center">
                                                            <div class="text-danger">
                                                                <i class="bi bi-trash display-4"></i>
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
                                                            <button type="button" class="btn w-sm btn-light btn-hover"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <!-- Delete Form -->
                                                            <form
                                                                action="{{ route('invoice_return.is_delete', $invoice->id) }}"
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
                                            tabindex="-1" aria-labelledby="detailLabel-{{ $invoice->invoice_number }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <!-- Modal Header with Gradient Background -->
                                                    <div class="modal-header bg-gradient-primary text-white border-0">
                                                        <h5 class="modal-title"
                                                            id="detailLabel-{{ $invoice->invoice_number }}">
                                                            <i class="bi bi-receipt me-2"></i>Order Summary
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
                                                            <i class="bi bi-x-circle me-1"></i>Close
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
</script>
