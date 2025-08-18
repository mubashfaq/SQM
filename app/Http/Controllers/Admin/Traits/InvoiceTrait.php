<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Invoices;
use DB;
use App\Models\UserMeta;
use App\Models\GeneralSettings;
use App\General\UserClass;
use App\General\GeneralSettingsClass;
use App\Models\User;

trait InvoiceTrait
{
    public function ReviewInvoice($id)
    {
        $settings = GeneralSettings::get();
        # Company Details
        $company_logo = url('/') . "/" . GeneralSettingsClass::getOptionValue('_header_logo', $settings);
        $company = GeneralSettingsClass::getOptionValue('_b_name', $settings);
        $company_address = GeneralSettingsClass::getOptionValue('_b_street_address', $settings);
        $company_state_country = GeneralSettingsClass::getOptionValue('_b_area_address', $settings);
        $company_email = GeneralSettingsClass::getOptionValue('_b_email', $settings);
        $company_phone = GeneralSettingsClass::getOptionValue('_b_number', $settings);
        $abn = GeneralSettingsClass::getOptionValue('_abn', $settings);
        $bankDetails = GeneralSettingsClass::getOptionValue('_bank_details', $settings);
        $bsb = GeneralSettingsClass::getOptionValue('_bsb', $settings);
        $acc = GeneralSettingsClass::getOptionValue('_acc', $settings);
        $bankName = GeneralSettingsClass::getOptionValue('_bank_name', $settings);
        $company_fax = "(123) 456-0987";

        $GST = config('app.PERCENTAGE') ?? 0;
        $pos_invoice_note = GeneralSettingsClass::getOptionValue('_pos_invoice_note', $settings);

        $invoice = Invoices::find($id);
        if ($invoice) {
            $customer_details = '';

            if ($invoice->customer_type == "existing") {
                // Get all user meta for the customer
                $UserMeta = UserMeta::where('user_id', $invoice->customer)->get()->keyBy('option');
                $user = User::find($invoice->customer);
            
                // Get values from user meta or fallback to user table
                $b_name = $user->name ?? 'N/A';
                $street_address = $UserMeta['business_street_address']->value ?? 'N/A';
                $area_address = $UserMeta['area_address']->value ?? 'N/A';
                $email = $user->email ?? 'N/A';
                $phone =  $user->phone ?? 'N/A';
                
            
                // Build the HTML block
                $customer_details = '<strong>' . $b_name . '</strong><br>' .
                                    $street_address . '<br>' .
                                    $area_address . '<br>' .
                                    '<abbr title="Email">E:</abbr> ' . $email . '<br>' .
                                    '<abbr title="Phone">P:</abbr> ' . $phone;
            } 
            else if ($invoice->customer_type == "random") {
                $customer_details = "<strong>" . $invoice->customer . "</strong>";
            }
            
            $statusColor = ($invoice->payment_status == 'Paid') ? '#28a745' : '#ffc107';
            $statusBackground = ($invoice->payment_status == 'Paid') ? '#e7f4eb' : '#fff8e5';
           $invoice_items = ''; // Initialize the string
            $i = 1; // Initialize counter

            foreach ($invoice->invoiceMeta as $invoice_item) {
                
                $product = Product::find($invoice_item->product_id); 
                $is_weight = 0; // Default value
                
                if ($product) {
                    $is_weightMeta = $product->productMeta->firstWhere('option', '_is_weight');
                    $is_weight = $is_weightMeta ? $is_weightMeta->value : 0; // Handle null case
                    $product_sku = $product->sku;
                } else {
                    $product_sku = 'N/A';
                }
                
                $rowStyle = ($i % 2 == 0) ? 'background-color: #f8f9fa;' : 'background-color: #ffffff;';
                
                $invoice_items .= '<tr style="' . $rowStyle . '">
                        <td style="padding: 12px; border-bottom: 1px solid #e9ecef; text-align: center;">' . $i . '</td>
                        <td style="padding: 12px; border-bottom: 1px solid #e9ecef;">
                            <div style="font-weight: 600;">' . htmlspecialchars($invoice_item->product_name) . '</div>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e9ecef;">' . htmlspecialchars($product_sku) . '</td>
                        <td style="padding: 12px; border-bottom: 1px solid #e9ecef; text-align: center;">' . $invoice_item->units . '</td>
                        <td style="padding: 12px; border-bottom: 1px solid #e9ecef; text-align: center;">' . ($is_weight == 1 ? 'Kg' : 'Item') . '</td>
                        <td style="padding: 12px; border-bottom: 1px solid #e9ecef;">$' . number_format($invoice_item->unit_price, 2) . '</td>
                        <td style="padding: 12px; border-bottom: 1px solid #e9ecef;">' . (!empty($bankName) ? htmlspecialchars($bankName) : 'N/A') . '</td>
                        <td style="padding: 12px; border-bottom: 1px solid #e9ecef; text-align: right; font-weight: 600;">$' . number_format($invoice_item->unit_total_price, 2) . '</td>
                    </tr>';
                $i++;
            }

            $invoice_template = <<<INVOICE
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Invoice #$invoice->invoice_number</title>
                <style>
                    body {
                        font-family: 'Helvetica Neue', Arial, sans-serif;
                        color: #333;
                        margin: 0;
                        padding: 0;
                    }
                    
                    @media print {
                        body {
                            margin: 0;
                            padding: 0;
                            background-color: #fff;
                            -webkit-print-color-adjust: exact !important;
                            print-color-adjust: exact !important;
                            color-adjust: exact !important;
                        }
                        .no-print {
                            display: none !important;
                        }
                        .invoice-container {
                            box-shadow: none !important;
                            border: none !important;
                        }
                        table {
                            width: 100%;
                        }
                        
                        /* Ensure background colors print */
                        * {
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                    }
                    
                    /* Responsive adjustments */
                    @media screen and (max-width: 768px) {
                        .flex-container > div {
                            flex: 0 0 100%;
                        }
                    }
                </style>
            </head>
            <body>
                <div id="invoice-container" class="invoice-container" style="max-width: 1000px; margin: 0 auto; font-family: 'Helvetica Neue', Arial, sans-serif; color: #333; background-color: #fff; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
                    <!-- Header -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 25px; background-color: #fff; border-bottom: 1px solid #e9ecef;">
                        <h2 style="margin: 0; font-size: 20px; display: flex; align-items: center;">
                            <span style="background-color: #4361ee; color: #fff; padding: 5px 15px; border-radius: 30px; font-size: 14px; margin-right: 10px;">Invoice</span>
                            <span style="font-weight: 700; color: #333;">#$invoice->invoice_number</span>
                        </h2>
                    </div>
                    
                    <!-- Invoice Content -->
                    <div style="padding: 30px;" class="printing-area">
                        <!-- Logo -->
                        <div style="text-align: center; margin-bottom: 30px;">
                            <img src="$company_logo" style="max-height: 80px;" alt="Company Logo">
                        </div>
                        
                        <!-- From and To -->
                        <div style="display: flex; flex-wrap: wrap; margin: 0 -15px 30px -15px;">
                            <!-- From -->
                            <div style="flex: 1; min-width: 300px; padding: 0 15px; margin-bottom: 20px;">
                                <div style="background-color: #f8f9fa; border-radius: 6px; padding: 20px; height: 100%;">
                                    <h3 style="color: #6c757d; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-top: 0; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #dee2e6;">From</h3>
                                    <div>
                                        <h4 style="font-size: 18px; margin-top: 0; margin-bottom: 10px; color: #212529;">$company</h4>
                                       
                                        <p style="margin-bottom: 5px; font-size: 14px; color: #495057;"><abbr title="Email">E:</abbr> $company_email</p>
                                        <p style="margin-bottom: 5px; font-size: 14px; color: #495057;"><abbr title="Phone">P:</abbr> $company_phone</p>
                                        <p style="margin-bottom: 5px; font-size: 14px; color: #495057;"><abbr title="Fax">F:</abbr> $company_fax</p>
                                        <p style="margin-bottom: 5px; font-size: 14px; color: #495057;"><abbr title="ABN">ABN:</abbr> $abn</p>
                                        <p style="margin-bottom: 5px; font-size: 14px; color: #495057;"><abbr title="Bank Details">Bank Details:</abbr> $bankDetails</p>
                                        <p style="margin-bottom: 5px; font-size: 14px; color: #495057;"><abbr title="Bank Name">Bank Name:</abbr> $bankName</p>
                                        <p style="margin-bottom: 5px; font-size: 14px; color: #495057;"><abbr title="BSB">BSB:</abbr> $bsb</p>
                                        <p style="margin-bottom: 0; font-size: 14px; color: #495057;"><abbr title="Account">Acc:</abbr> $acc</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- To -->
                            <div style="flex: 1; min-width: 300px; padding: 0 15px; margin-bottom: 20px;">
                                <div style="background-color: #f8f9fa; border-radius: 6px; padding: 20px; height: 100%;">
                                    <h3 style="color: #6c757d; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-top: 0; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #dee2e6;">To</h3>
                                    <div style="font-size: 14px; color: #495057;">
                                        $customer_details
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Info and Invoice Details -->
                        <div style="display: flex; flex-wrap: wrap; margin: 0 -15px 30px -15px;">
                            <!-- Payment Info -->
                            <div style="flex: 1; min-width: 300px; padding: 0 15px; margin-bottom: 20px;">
                                <div style="border: 1px solid #dee2e6; border-radius: 6px; padding: 20px;">
                                    <h3 style="color: #6c757d; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-top: 0; margin-bottom: 15px;">Payment Info</h3>
                                    <p style="margin-bottom: 10px; font-size: 14px;"><strong>Method:</strong> 
                                        <span style="background-color: #e7f5ff; color: #0d6efd; padding: 3px 10px; border-radius: 30px; font-size: 12px;">
                                            $invoice->payment_method
                                        </span>
                                    </p>
                                    <p style="margin-bottom: 0; font-size: 14px;"><strong>Status:</strong> 
                                        <span style="background-color: $statusBackground; color: $statusColor; padding: 3px 10px; border-radius: 30px; font-size: 12px;">
                                            $invoice->payment_status
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Invoice Details -->
                            <div style="flex: 1; min-width: 300px; padding: 0 15px; margin-bottom: 20px;">
                                <div style="border: 1px solid #dee2e6; border-radius: 6px; padding: 20px;">
                                    <h3 style="color: #6c757d; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-top: 0; margin-bottom: 15px;">Invoice Details</h3>
                                    <p style="margin-bottom: 10px; font-size: 14px;"><strong>Invoice:</strong> #$invoice->invoice_number</p>
                                    <p style="margin-bottom: 10px; font-size: 14px;"><strong>Date:</strong> $invoice->invoice_date</p>
                                    <p style="margin-bottom: 0; font-size: 14px;"><strong>Due:</strong> $invoice->due_date</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Invoice Items Table -->
                        <div style="margin-bottom: 30px; overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                                <thead>
                                    <tr style="background-color: #343a40; color: #fff;">
                                        <th style="padding: 12px; text-align: center;">#</th>
                                        <th style="padding: 12px; text-align: left;">Description</th>
                                        <th style="padding: 12px; text-align: left;">SKU</th>
                                        <th style="padding: 12px; text-align: center;">Weight / Qty</th>
                                        <th style="padding: 12px; text-align: center;">Type</th>
                                        <th style="padding: 12px; text-align: left;">Unit Cost</th>
                                        <th style="padding: 12px; text-align: left;">Bank Name</th>
                                        <th style="padding: 12px; text-align: right;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    $invoice_items
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Notes and Summary -->
                        <div style="display: flex; flex-wrap: wrap; margin: 0 -15px;">
                            <!-- Notes and Bank Details -->
                            <div style="flex: 1; min-width: 300px; padding: 0 15px; margin-bottom: 20px;">
                                <div style="border: 1px solid #dee2e6; border-radius: 6px; padding: 20px; height: 100%;">
                                    <h3 style="color: #6c757d; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-top: 0; margin-bottom: 15px;">Notes</h3>
                                    <p style="margin-bottom: 10px; font-size: 14px;">$invoice->payment_id</p>
                                    <p style="margin-bottom: 20px; font-size: 14px; color: #6c757d;">$pos_invoice_note</p>
                                </div>
                            </div>
                            
                            <!-- Summary -->
                            <div style="flex: 1; min-width: 300px; padding: 0 15px; margin-bottom: 20px;">
                                <div style="background-color: #f8f9fa; border-radius: 6px; padding: 20px;">
                                    <h3 style="color: #6c757d; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-top: 0; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #dee2e6;">Summary</h3>
                                    
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                        <span style="font-size: 14px;">Subtotal:</span>
                                        <span style="font-size: 14px; font-weight: 600;">$$invoice->sub_total</span>
                                    </div>
                                    
                                   
                                    
                                 
                                    
                                 
                                    
                                    
                                    
                                    
                                    
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                                        <span style="font-size: 14px; color: #6c757d;">GST:</span>
                                        <span style="font-size: 14px; color: #6c757d;">+$$invoice->gst</span>
                                    </div>
                                    
                                    <div style="border-top: 1px solid #dee2e6; padding-top: 15px; margin-top: 15px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-size: 16px; font-weight: 600;">Grand Total:</span>
                                            <span style="font-size: 24px; font-weight: 700; color: #4361ee;">$$invoice->grand_total</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="text-align: right; margin-top: 20px;">
                                    <p style="color: #6c757d; font-size: 14px; margin: 0;">Thank you for your business!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div style="background-color: #fff; padding: 15px; text-align: center; border-top: 1px solid #e9ecef;">
                        <small style="color: #6c757d;">This invoice was automatically generated by $company © {$invoice->invoice_date}</small>
                    </div>
                </div>
                
            </body>
            </html>
INVOICE;
            return $invoice_template;
        }
    }
}