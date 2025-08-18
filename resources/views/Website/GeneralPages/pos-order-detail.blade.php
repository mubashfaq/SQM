@include('Website.Includes.header', ['meta_Details' => $meta_details])

@php
    use App\General\UserClass;
    $currentPage = session('pos_current_page', 1);
@endphp
<style>
    .orderDetailsClass {
        justify-content: space-between;
    }

    .invoiceDetailInner {
        display: flex !important;
        justify-content: end !important;
        flex-direction: column !important;
    }
</style>
<div class="container mt-3">
    <div class="d-flex justify-content-between w-100 my-3">
        <a href="/pos-orders?page={{ $currentPage }}" style="text-decoration: none;">
            <button type="button" class="btn" style="background-color: #E59041; color: white;"><- Back to List
                    </button>
        </a>
        <button type="button" class="btn btn-warning generate-pdf-button text-white"
            data-invoice-id="{{ $invoices->id }}">Print
            Invoice
        </button>
    </div>

    <div>{!! $Invoice_review !!}</div>
</div>

@include('Website.Includes.footer')

<script>
    $(document).ready(function() {
        $('.generate-pdf-button').click(function() {
            // Get the invoice ID from the button's data attribute
            var invoiceId = $(this).data('invoice-id');

            $.ajax({
                url: '/invoicedetail' + invoiceId,
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
</script>
