@include('Website.Includes.header', ['meta_Details' => $meta_details])

@php
    use App\General\UserClass;
@endphp
<style>
    .order-details-container {
        font-style: normal;
        font-weight: 600;
        line-height: normal;
        color: #000;
        font-family: Inter;
        border-radius: 10px;
        border: 1px dashed #ABABAB;
        border-width: 2px 2px;
    }

    .small-label {
        font-size: 12px;
    }

    .big-label {
        font-size: 20px;
    }

    .theme-color {
        color: #E59041;
    }

    .section-label {
        font-size: 14px;
        font-weight: 600;
    }

    .border-left {
        border-left: 1px dashed #ABABAB;
        border-width: 2px 2px;
    }

    .track-order-products {
        font-size: 12px;
        color: #ABABAB;
        border-bottom: 1px solid #ABABAB;
    }

    .border-track {
        border-bottom: 2px solid black;
    }

    .product-thumbnail-img {
        width: 52px;
        height: 52px;
        background-position: center !important;
        background-size: 45px auto !important;
        background-repeat: no-repeat !important;
        border-radius: 10px;
        margin-right: 6px;
    }

    .product-info-detail {
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .text-right {
        text-align: right !important;
    }

    .total-value {
        display: flex;
        justify-content: space-between;
    }

    .prin-btn {
        display: flex;
        justify-content: end;
    }

    .order-detail-btn {
        background-color: #E59041;
        color: white;
        outline: none;
        border: 1px solid #E59041;
    }

    .order-detail-btn:hover {
        border: 1px solid #E59041;
        color: #E59041;
    }

    @media (max-width: 320px) {
        .order-detail-btn {
            overflow: auto;
        }

        .lable-m-b {
            margin-bottom: 12px;
        }

        .border-left {
            border-left: 0px;
        }
    }

    @media (max-width: 375px) {
        .order-details-container {
            overflow: auto;
        }

        .lable-m-b {
            margin-bottom: 12px;
        }

        .border-left {
            border-left: 0px;
        }
    }

    @media (max-width: 425px) {
        .order-details-container {
            overflow: auto;
        }

        .lable-m-b {
            margin-bottom: 12px;
        }

        .border-left {
            border-left: 0px;
        }
    }

    @media (max-width: 1024) {
        .sb-totoal-section {
            padding: 0px !important;
            margin-right: 5px !important;
        }
    }



    @media print {
        body * {
            visibility: hidden;
        }

        #print-container,
        #print-container * {
            visibility: visible;
        }

        /* Define print-specific styles */
        .order-details-container {
            font-style: normal;
            font-weight: 600;
            line-height: normal;
            color: #000;
            font-family: Inter;
            border-radius: 10px;
            border: 1px dashed #ABABAB;
            border-width: 2px 2px;
        }

        .prin-btn {
            display: none;
        }

        .container {
            width: auto;
        }
    }
</style>
<div class="text-center p-2">
    <form action="{{ route('Website.guest_order_details') }}" method="post">
        @csrf
        <input type="text" class="form-controle" name="order_id" id="order_id" value="<?php echo !empty($orderdetails->invoice_number) ? $orderdetails->invoice_number : ''; ?>"
            placeholder="Invoice Number"
            style="border: 2px solid #E59041;outline:none; line-height:2; border-radius: 4px;">
        <button type="submit" class="btn"
            style="background: #E59041; color: white; font-weight: bold;">Search</button>
    </form>
</div>

@if (!empty($orderdetails))
    <div class="container prin-btn w-100 my-3 d-flex justify-content-end">
        <button type="button" class="btn order-detail-btn" onclick="printInvoice({{ $orderdetails->id }})">Print
            Invoice</button>
    </div>
    <div class="container" id="print-container">
        {!! $Invoice_review !!}
    </div>
@else
    @if ($message != "")
        <div class="text-center text-danger mb-5"><b>{{ $message }}</b></div>
    @else
        <div class="text-center text-danger mb-5">Enter Your Order's Invoice Number written on your invoice.</div>
    @endif
@endif
@include('Website.Includes.footer')
<script>
    function printInvoice(id) {
        $.ajax({
            url: '/print-guest-order-invoice/' + id,
            type: 'GET',
            success: function(response) {
                var printWindow = window.open('', '', 'width=1200,height=720');
                printWindow.document.open();
                printWindow.document.title = 'POS INOICE';
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
</script>
