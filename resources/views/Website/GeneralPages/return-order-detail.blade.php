@include('Website.Includes.header', ['meta_Details' => $meta_details])

@php
    use App\General\UserClass;
    $currentPage = session('current_page', 1);
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
        @import url('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');

        @page {
            size: A3;
        }

        body * {
            visibility: hidden;
        }

        #print-container,
        #print-container * {
            visibility: visible;
        }

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

        .border-left {
            border-left: 0px !important;
        }

        .prin-btn {
            display: none !important;
        }

        .container {
            width: auto;
        }

        .page-break {
            page-break-after: always;
        }

        .custom-height {
            margin-top: 2rem !important;
        }

        .custom-flex {
            width: 100% !important;
            display: flex !important;
            justify-content: space-between !important;
        }
    }
</style>

<div class="container">
    <div class="prin-btn w-100 my-3 d-flex justify-content-between">
        <a href="/return-orders?page={{ $currentPage }}" style="text-decoration: none;">
            <button type="button" class="btn order-detail-btn"><i data-feather="arrow-left" class="wd-16 mr-2"></i> Back to
                List </button>
        </a>
        <button type="button" class="btn order-detail-btn" onclick="printInvoice({{ $return->id }})">Print
            Invoice</button>
    </div>
</div>
<div class="container" id="print-container">
    {!! $return_invoice_review !!}
</div>
@include('Website.Includes.footer')

<link rel="stylesheet" type="text/css" href="path/to/print-styles.css">
<script>
    function printInvoice(id) {
        $.ajax({
            url: '/print-return-order-invoice/' + id,
            type: 'GET',
            success: function(response) {
                var printWindow = window.open('', '', 'width=1200,height=720');
                printWindow.document.open();
                printWindow.document.title = 'OS INOICE';
                printWindow.document.write('<html><head><title>Print Invoice - ' +
                    id +
                    '</title><link href="{{ url('/') }}/AdminAssets/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"></head><body>'
                );
                printWindow.document.write('<style type="text/css" media="print">' +
                    '@page { size: A3; }' +
                    '.invoice_Area { font-size: 12px; margin: 10px; padding: 5px; }' +
                    '</style>'
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
