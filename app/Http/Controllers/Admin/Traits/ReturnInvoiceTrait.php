<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\InvoiceReturn;
use Illuminate\Http\Request;
use App\Models\Invoices;
use App\Models\GeneralSettings;
use App\General\GeneralSettingsClass;

use DB;

trait ReturnInvoiceTrait
{
    public function ReturnReviewInvoice($id, $invoice_return)
    {
        $GST = config('app.PERCENTAGE') ?? 0;
        $settings = GeneralSettings::get();
        $pos_invoice_note = GeneralSettingsClass::getOptionValue('_pos_invoice_note', $settings);

        # Company Details
        $company_logo = url('/') . '/'.GeneralSettingsClass::getRequiredValue('_header_logo');
        $company = GeneralSettingsClass::getRequiredValue('_b_name');
        $company_address = GeneralSettingsClass::getRequiredValue('_b_street_address');
        $company_state_country = GeneralSettingsClass::getRequiredValue('_b_area_address');
        $company_email = GeneralSettingsClass::getRequiredValue('_b_email');
        $company_phone = GeneralSettingsClass::getRequiredValue('_b_number');

        $invoice = Invoices::find($id);
        if ($invoice) {
            $amount_to_return = 0;
            if ($invoice_return) {
                $invoice_return_meta = $invoice_return->invoice_return_meta->toArray();
            }

            $customer_details = "";
            if ($invoice->customer_type == "existing") {

                $customer_details = '<strong>Customer Name, LLC.</strong><br>
                                    1355 Market St, Suite 900<br>
                                    San Francisco, CA 94103<br>
                                    <abbr title="Email">E:</abbr> info@customer-llc.com
                                    <br>
                                    <abbr title="Phone">P:</abbr> (123) 456-7890';
            } else if ($invoice->customer_type == "random") {
                $customer_details = "<strong>" . $invoice->customer . "</strong>";
            }

            $invoice_items = "";
            $i = 1;
            $sub_total = 0;
            foreach ($invoice->invoiceMeta as $invoice_item) {
                if ($invoice_return) {
                    $return_key = array_search($invoice_item->product_id, array_column($invoice_return_meta, 'product_id'));
                    $return_units = $invoice_return_meta[$return_key]['return_units'];
                } else {
                    $return_units = 0;
                }

                $total_sale_price = $invoice_item->unit_price * $return_units;
                $invoice_items = $invoice_items . '<tr>
                                    <td>' . $i . '</td>
                                    <td>' . $invoice_item->product_name . '</td>
                                    <td>' . $invoice_item->units . '</td>
                                    <td>' . $return_units . '</td>
                                    <td>$' . $invoice_item->unit_price . '</td>
                                    <td class="text-right">$' . $total_sale_price . '</td>
                                </tr>';
                $i++;

                $sub_total += $total_sale_price;
            }
            $return_sub_total = $invoice->discounted_amount - $invoice_return->grand_total;
            $invoice_template = <<<INVOICE
                <h5 class="card-title bd-b pd-y-20">Invoice <a href="" class="tx-dark" id="id_invoice_number">#$invoice->invoice_number</a></h5>
                <div class="card-body pd-0 printing-area">
                    <img src="$company_logo" style="width:200px" class="img-fluid" alt="logo"><br><br>
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="text-dark">From:</h4>
                            <address>
                                <strong>$company</strong><br>
                                $company_address<br>
                                $company_state_country<br>
                                <abbr title="Email">E:</abbr> $company_email
                                <br>
                                <abbr title="Phone">P:</abbr> $company_phone
                            </address>
                        </div>
                        <div class="col-md-4 ml-md-auto text-md-right">
                            <h4 class="text-dark">To:</h4>
                            <address>
                                $customer_details
                            </address>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-4">
                            <span><strong>Payment Method:</strong> $invoice->payment_method</span>
                            <br>
                            <span><strong>Payment Status:</strong> $invoice->payment_status</span>
                        </div>
                        <div class="col-md-4 ml-md-auto text-md-right">
                            <span><strong>Invoice Date:</strong> $invoice_return->return_date</span>
                            <br>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Return Items</th>
                                <th>Unit Cost</th>
                                <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                $invoice_items
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Notes</h5>
                            <p>$invoice->payment_id</p>
                            <p>$pos_invoice_note</p>
                        </div>
                        <div class="col-md-4 ml-md-auto text-right">
                            <span class="d-inline-block">
                            Sub - Total amount: $$return_sub_total<br>
                            Discount: $invoice->discount%<br>
                            Discounted Total: $$invoice_return->general_discounted_amount<br>
                            Membership: $invoice->membership_discount%<br>
                            Membership Discounted Total: $$invoice_return->membership_discount_amount<br>
                            <p class="tx-gray-500 tx-12">GST ($GST%): $$invoice_return->gst</p>
                            </span>
                            <br><br>
                            <p class="tx-bold">Amount Return: <span class="tx-20 tx-gray-900">$$invoice_return->grand_total </span></p>
                            <br>
                        </div>
                    </div>
                </div>
INVOICE;
            return $invoice_template;
        }
    }

    public function IndividualReturnReviewInvoice($id, $invoice_return, $return_sub_total, $discounted_amount, $membership_discounted_amount, $gst, $grand_total)
    {
        $GST = config('app.PERCENTAGE') ?? 0;
        $settings = GeneralSettings::get();
        $pos_invoice_note = GeneralSettingsClass::getOptionValue('_pos_invoice_note', $settings);

        # Company Details
        $company_logo = url('/') . GeneralSettingsClass::getRequiredValue('_header_logo');
        $company = GeneralSettingsClass::getRequiredValue('_b_name');
        $company_address = GeneralSettingsClass::getRequiredValue('_b_street_address');
        $company_state_country = GeneralSettingsClass::getRequiredValue('_b_area_address');
        $company_email = GeneralSettingsClass::getRequiredValue('_b_email');
        $company_phone = GeneralSettingsClass::getRequiredValue('_b_number');

        $invoice = Invoices::find($id);
        if ($invoice) {
            $amount_to_return = 0;
            if ($invoice_return) {
                $invoice_return_meta = $invoice_return->invoice_return_meta->toArray();
            }

            $customer_details = "";
            if ($invoice->customer_type == "existing") {

                $customer_details = '<strong>Customer Name, LLC.</strong><br>
                                    1355 Market St, Suite 900<br>
                                    San Francisco, CA 94103<br>
                                    <abbr title="Email">E:</abbr> info@customer-llc.com
                                    <br>
                                    <abbr title="Phone">P:</abbr> (123) 456-7890';
            } else if ($invoice->customer_type == "random") {
                $customer_details = "<strong>" . $invoice->customer . "</strong>";
            }

            $invoice_items = "";
            $i = 1;
            $sub_total = 0;
            foreach ($invoice->invoiceMeta as $invoice_item) {
                if ($invoice_return) {
                    $return_key = array_search($invoice_item->product_id, array_column($invoice_return_meta, 'product_id'));
                    $return_units = $invoice_return_meta[$return_key]['return_units'];
                } else {
                    $return_units = 0;
                }

                $total_sale_price = $invoice_item->unit_price * $return_units;
                $invoice_items = $invoice_items . '<tr>
                                    <td>' . $i . '</td>
                                    <td>' . $invoice_item->product_name . '</td>
                                    <td>' . $invoice_item->units . '</td>
                                    <td>' . $return_units . '</td>
                                    <td>$' . $invoice_item->unit_price . '</td>
                                    <td class="text-right">$' . $total_sale_price . '</td>
                                </tr>';
                $i++;

                $sub_total += $total_sale_price;
            }
            $invoice_template = <<<INVOICE
                <h5 class="card-title bd-b pd-y-20">Invoice <a href="" class="tx-dark" id="id_invoice_number">#$invoice->invoice_number</a></h5>
                <div class="card-body pd-0 printing-area">
                    <img src="$company_logo" style="filter: invert(1); !important;width:200px" class="img-fluid" alt="logo"><br><br>
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="text-dark">From:</h4>
                            <address>
                                <strong>$company</strong><br>
                                $company_address<br>
                                $company_state_country<br>
                                <abbr title="Email">E:</abbr> $company_email
                                <br>
                                <abbr title="Phone">P:</abbr> $company_phone
                            </address>
                        </div>
                        <div class="col-md-4 ml-md-auto text-md-right">
                            <h4 class="text-dark">To:</h4>
                            <address>
                                $customer_details
                            </address>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-4">
                            <span><strong>Payment Method:</strong> $invoice->payment_method</span>
                            <br>
                            <span><strong>Payment Status:</strong> $invoice->payment_status</span>
                        </div>
                        <div class="col-md-4 ml-md-auto text-md-right">
                            <span><strong>Invoice Date:</strong> $invoice_return->return_date</span>
                            <br>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Return Items</th>
                                <th>Unit Cost</th>
                                <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                $invoice_items
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Notes</h5>
                            <p>$invoice->payment_id</p>
                            <p>$pos_invoice_note</p>
                        </div>
                        <div class="col-md-4 ml-md-auto text-right">
                            <span class="d-inline-block">
                            Sub - Total amount: $$return_sub_total<br>
                            Discount: $invoice->discount%<br>
                            Discounted Total: $$discounted_amount<br>
                            Membership Discount: $invoice->membership_discount%<br>
                            Membership Discount Total: $$membership_discounted_amount<br>
                            <p class="tx-gray-500 tx-12">GST ($GST%): $$gst</p>
                            </span>
                            <br><br>
                            <p class="tx-bold">Amount Return: <span class="tx-20 tx-gray-900">$$grand_total </span></p>
                            <br>
                        </div>
                    </div>
                </div>
INVOICE;
            return $invoice_template;
        }
    }
}
