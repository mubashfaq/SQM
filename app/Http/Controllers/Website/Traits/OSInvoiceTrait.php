<?php

namespace App\Http\Controllers\Website\Traits;

use Illuminate\Http\Request;
use App\Models\order;
use App\Models\GeneralSettings;
use App\Models\order_meta;
use App\General\GeneralSettingsClass;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderMeta;

trait OSInvoiceTrait
{
    public function ReviewOSInvoice($id)
{
    // Fetch the order details
    $orderdetails = order::where('id', $id)
        ->with('orderAddressDetail')
        ->first();

    // Fetch the meta for the order (the ordered items)
    $orders = order_meta::with('product')
        ->where('order_id', $id)
        ->get();

    // Grab your settings
    $settings = GeneralSettings::get();

    // Company Details
    $company_logo          = url('/') . "/" . GeneralSettingsClass::getOptionValue('_header_logo', $settings);
    $company               = GeneralSettingsClass::getOptionValue('_b_name', $settings);
    $company_address       = GeneralSettingsClass::getOptionValue('_b_street_address', $settings);
    $company_state_country = GeneralSettingsClass::getOptionValue('_b_area_address', $settings);
    $company_email         = GeneralSettingsClass::getOptionValue('_b_email', $settings);
    $company_phone         = GeneralSettingsClass::getOptionValue('_b_number', $settings);

    // Example: Additional config or notes
    $GST               = config('app.PERCENTAGE') ?? 0;
    $pos_invoice_note  = GeneralSettingsClass::getOptionValue('_store_order_note', $settings);

    if ($orderdetails) {
        // Build "Shipping" / "Billing" address blocks
        $receiver_details = '<strong>' . $orderdetails->f_name . " " . $orderdetails->l_name . '</strong><br>'
            . $orderdetails->street_address . '<br>'
            . $orderdetails->state . " " . $orderdetails->post_code . '<br>'
            . '<abbr title="Email">E:</abbr> ' . $orderdetails->email . '<br>'
            . '<abbr title="Phone">P:</abbr> ' . $orderdetails->phone;

        if ($orderdetails->orderAddressDetail) {
            $customer_details = '<strong>' . $orderdetails->orderAddressDetail->f_name . " " . $orderdetails->orderAddressDetail->l_name . '</strong><br>'
                . $orderdetails->orderAddressDetail->street_address . '<br>'
                . $orderdetails->orderAddressDetail->state . " " . $orderdetails->orderAddressDetail->post_code . '<br>'
                . '<abbr title="Email">E:</abbr> ' . $orderdetails->orderAddressDetail->email . '<br>'
                . '<abbr title="Phone">P:</abbr> ' . $orderdetails->orderAddressDetail->phone;
        } else {
            // Fall back if orderAddressDetail is not present
            $customer_details = '<strong>' . $orderdetails->f_name . " " . $orderdetails->l_name . '</strong><br>'
                . $orderdetails->street_address . '<br>'
                . $orderdetails->state . " " . $orderdetails->post_code . '<br>'
                . '<abbr title="Email">E:</abbr> ' . $orderdetails->email . '<br>'
                . '<abbr title="Phone">P:</abbr> ' . $orderdetails->phone;
        }

        // Format status
        $capitalizedStatus = ucfirst($orderdetails->status);
        if ($orderdetails->status === "completed") {
            $orderStatus = '<h5 class="card-title bd-b pd-y-20">Status: </h5>
                            <h5 class="card-title bd-b pd-y-20 text-success">&nbsp; ' . $capitalizedStatus . '</h5>';
        } else if ($orderdetails->status === "cancelled") {
            $orderStatus = '<h5 class="card-title bd-b pd-y-20">Status: </h5>
                            <h5 class="card-title bd-b pd-y-20 text-danger">&nbsp; ' . $capitalizedStatus . '</h5>';
        } else if ($orderdetails->status === "inProcess") {
            $orderStatus = '<h5 class="card-title bd-b pd-y-20">Status: </h5>
                            <h5 class="card-title bd-b pd-y-20 text-warning">&nbsp; In Process</h5>';
        } else {
            $orderStatus = '<h5 class="card-title bd-b pd-y-20">Status: </h5>
                            <h5 class="card-title bd-b pd-y-20 text-primary">&nbsp; ' . $capitalizedStatus . '</h5>';
        }

        // Build the "order summary" section
        $orderSummery = 'Sub - Total amount: &nbsp;&nbsp;&nbsp;<b>$' . $orderdetails->subTotal . '</b><br><hr>';

        if ($orderdetails->auth_discount) {
            $orderSummery .= 'Registration Discount: &nbsp;&nbsp;&nbsp;<b>$' . $orderdetails->auth_discount . '</b><br><hr>';
            if ($orderdetails->amount_paid_by_coupon) {
                $orderSummery .= 'Coupon Discount: &nbsp;&nbsp;&nbsp;<b>$' . $orderdetails->amount_paid_by_coupon . '</b><br><hr>';
            }
        } elseif ($orderdetails->amount_paid_by_coupon) {
            $orderSummery .= 'Coupon Discount: &nbsp;&nbsp;&nbsp;<b>$' . $orderdetails->amount_paid_by_coupon . '</b><br><hr>';
        }

        $orderSummery .= 'Delivery Charges: &nbsp;&nbsp;&nbsp;<b>$' . $orderdetails->delivery_charges . '</b><br><br>';
        $orderSummery .= 'GST: &nbsp;&nbsp;&nbsp;<b>$' . $orderdetails->totalGst . '</b><br><hr>';

        // Build table rows for the ordered items
        $invoice_items = "";
        $i = 1;
        foreach ($orders as $order) {
            $featuredImage = '#';
            if ($order->product) {
                $featuredImage = $order->product->featured_image;
            }
            $discount=$order->product->discountData;
            $is_weightMeta = $order->product->productMeta->where('option', '_is_weight')->first();
            $is_weight = $is_weightMeta ? $is_weightMeta->value : null;

            if($discount){
                $price=number_format($discount['discounted_price'] ?? 0, 2);
            }else{
                $price=number_format($order->price ?? 0, 2);
            }

            $invoice_items .= '
            <tr>
                <td>' . $i . '</td>
                <td class="col-1">
                    <div><img style="width: 50% !important;" src="' . url('/') . '/' . $featuredImage . '"></div>
                </td>
                <td>' . substr($order->product_title, 0, 24) . (strlen($order->product_title) > 24 ? '...' : '') . '</td>
            
                <td>$' . $price . '</td>
             <td style="text-align:center;">' . $order->quantity . ' <span class="text-danger">' . ($is_weight == 1 ? '(kg)' : '(item)') . '</span></td>
                <td>' .(!empty($order->delivered_weight) ? $order->delivered_weight : '0') .' <span class="text-danger">' . ($is_weight == 1 ? '(kg)' : '(item)') . '</span></td>
                <td class="text-right">$' . $order->qp_subtotal . '</td>
            </tr>';
        $i++;
        }

        // Decide which amount to display, based on $orderdetails->status
        $grandTotalToDisplay = ($orderdetails->status === 'completed')
            ? $orderdetails->delivered_price   // Show delivered price if completed
            : $orderdetails->grand_total;      // Otherwise show grand total

        // Now build up the final invoice HTML
        $invoice_template = <<<INVOICE
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title bd-b pd-y-20">
                    Invoice <a href="" class="tx-dark">$orderdetails->invoice_number</a>
                </h5>
                <div class="d-flex">$orderStatus</div>
            </div>
            <div class="card-body pd-0 printing-area">
                <img src="$company_logo" style=" !important;width:200px" class="img-fluid" alt="logo"><br><br>
                <div class="row justify-content-between mb-5">
                    <div class="col-md-4">
                        <h4 class="text-dark">From:</h4>
                        <address>
                            <strong>$company</strong><br>
                            <abbr title="Email">E:</abbr> $company_email<br>
                            <abbr title="Phone">P:</abbr> $company_phone
                        </address>
                    </div>
                    <div class="col-md-4 ml-md-auto text-md-right">
                        <h4 class="text-dark">Customer Billing Details:</h4>
                        <address>
                            $customer_details
                        </address>
                    </div>
                    <div class="col-md-4 ml-md-auto text-md-right">
                        <h4 class="text-dark">Customer Shipping Details:</h4>
                        <address>
                            $receiver_details
                        </address>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" style="border: 0px !important;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Ordered Value</th>
                                <th>Delivered Value</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            $invoice_items
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="row justify-content-between orderDetailsClass">
                    <div class="col-md-6">
                        <h5>Notes</h5>
                        <p>$pos_invoice_note</p>
                    </div>
                    <div class="col-md-4 ml-md-auto" style="display: flex; flex-direction: column; justify-content: end;">
                        <span class="d-inline-block">
                            $orderSummery
                        </span>
                        <br>
                        <p class="tx-bold">
                            Grand Total:
                            <span class="tx-20 tx-gray-900">
                                &nbsp;&nbsp;&nbsp;<b>\$$grandTotalToDisplay</b>
                            </span>
                        </p>
                        <br>
                    </div>
                </div>
            </div>
        INVOICE;

        // Finally return the invoice HTML
        return $invoice_template;
    }

    // If no order was found, you might want to return some fallback or throw an exception
    return "<p>No order found</p>";
}

    
    
    //  Return Order Invoice
    public function ReviewOSReturnInvoice($id)
    {
        $return = ReturnOrder::where('order_id', $id)->with('order')->first();
        $returnMeta = ReturnOrderMeta::with('product')
            ->where('return_id', $id)
            ->get();
        $settings = GeneralSettings::get();
        # Company Details
        $company_logo = url('/') . "/" . GeneralSettingsClass::getOptionValue('_header_logo', $settings);
        $company = GeneralSettingsClass::getOptionValue('_b_name', $settings);
        $company_address = GeneralSettingsClass::getOptionValue('_b_street_address', $settings);
        $company_state_country = GeneralSettingsClass::getOptionValue('_b_area_address', $settings);
        $company_email = GeneralSettingsClass::getOptionValue('_b_email', $settings);
        $company_phone = GeneralSettingsClass::getOptionValue('_b_number', $settings);

        $GST = config('app.PERCENTAGE') ?? 0;
        $pos_invoice_note = GeneralSettingsClass::getOptionValue('_store_return_order_note', $settings);

        if ($return->order) {
            $receiver_details = '<strong>' . $return->order->f_name . " " . $return->order->l_name . '</strong><br>
                                    ' . $return->order->street_address . '<br>
                                    ' . $return->order->state . " " . $return->order->post_code . '<br>
                                    <abbr title="Email">E:</abbr> ' . $return->order->email . '
                                    <br>
                                    <abbr title="Phone">P:</abbr> ' . $return->order->phone . '';

            $capitalizedStatus = ucfirst($return->order->status);
            $invoice_number = $return->order->invoice_number;
            $orderStatus = null;
            if ($return->order->status == "refund") {
                $orderStatus = '<h5 class="card-title bd-b pd-y-20">Status: </h5>
                    <h5 class="card-title bd-b pd-y-20 text-danger">&nbsp; ' . $capitalizedStatus . '</h5>';
            }
            $route = '/order-details/' . base64_encode($return->order->id);
            $invoice_items = "";
            $i = 1;
            foreach ($returnMeta as $order) {
                $featuredImage = '#';
                if ($order->product) {
                    $featuredImage = $order->product->featured_image;
                } else {
                    $featuredImage = '#';
                }
                $invoice_items = $invoice_items . '<tr>
                                    <td>' . $i . '</td>
                                    <td class="col-1"><div><img style="width: 50% !important;" src=' . url('/') . "/" . $featuredImage . '></div></td>
                                    <td>' . $order->product->title . '</td>
                                    <td>$' . $order->price . '</td>
                                    <td>' . $order->quantity . '</td>
                                    <td class="text-right">$' . $order->subTotal . '</td>
                                </tr>';
                $i++;
            }
            $invoice_template = <<<INVOICE
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title bd-b pd-y-20">Returned Order Invoice Number <a href="$route" class="tx-dark">$invoice_number</a></h5>
                    <div class="d-flex">$orderStatus</div>
                </div>
                <div class="card-body pd-0 printing-area">
                    <img src="$company_logo" style="filter: invert(1); !important;width:200px" class="img-fluid" alt="logo"><br><br>
                    <div class="row justify-content-between mb-5">
                        <div class="col-md-4">
                            <h4 class="text-dark">From:</h4>
                            <address>
                                <strong>$company</strong><br>
                                <abbr title="Email">E:</abbr> $company_email
                                <br>
                                <abbr title="Phone">P:</abbr> $company_phone
                            </address>
                        </div>
                        <div class="col-md-4 ml-md-auto text-md-right">
                            <h4 class="text-dark">Customer Details:</h4>
                                <address>
                                    $receiver_details
                                </address>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" style="border: 0px !important;">
                            <thead>
                                <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Description</th>
                                <th>Unit Cost</th>
                                <th>Quantity</th>
                                <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                $invoice_items
                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-between orderDetailsClass">
                        <div class="col-md-6">
                            <h5>Notes</h5>
                            <p>$pos_invoice_note</p>
                        </div>
                        <div class="col-md-4 ml-md-auto">
                            <span class="d-inline-block">
                            <p class="tx-bold">Grand Total: <span class="tx-20 tx-gray-900">&nbsp;&nbsp;&nbsp;<b>$$return->grandTotal</b>
                            </p>
                            </span>
                            <br>
                        </div>
                    </div>
                </div>
INVOICE;
            return $invoice_template;
        }
    }
}
