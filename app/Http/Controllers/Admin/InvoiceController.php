<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Invoices;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Http\Controllers\Admin\Traits\InvoiceTrait;
use App\Models\GeneralSettings;
use App\General\GeneralSettingsClass;
use PDF;

class InvoiceController extends Controller
{
    use InvoiceTrait;

    public function index(Request $request)
    {
        // Fetch basic data
        $users = User::all();
        $roles = DB::table('roles')->get();

        // Set default limit and override if provided in request
        $limit = $request->get('records_limit') ? intval($request->get('records_limit')) : 50;

        // Create a fresh query builder instance
        $invoicesQuery = new Invoices();
        $invoicesQuery = $invoicesQuery->newQuery()->where('is_deleted', 0);
        
        // Apply filters from request parameters
        if ($request->filled('customer_name')) {
            $customerName = $request->get('customer_name');
            $customers = User::where('name', 'like', '%' . $customerName . '%')->get();
            if ($customers->isNotEmpty()) {
                $customerIds = $customers->pluck('id')->toArray();
                $invoicesQuery->whereIn('customer', $customerIds);
            }
        }

        if ($request->filled('payment_status')) {
            $invoicesQuery->where('payment_status', $request->get('payment_status'));
        }
        
        if ($request->filled('payment_method')) {
            $invoicesQuery->where('payment_method', $request->get('payment_method'));
        }
        
        // Date range filter
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $invoicesQuery->whereBetween('created_at', [
                Carbon::parse($request->get('date_from'))->startOfDay(),
                Carbon::parse($request->get('date_to'))->endOfDay()
            ]);
        }
        
        // Force fresh results by using a randomized comment in the query
        // This tricks the query cache in some database systems
        $timestamp = time();
        $invoicesQuery->whereRaw("1=1 /* fresh_query_{$timestamp} */");
        
        // Log the query for debugging
        \Log::info('Invoices query: ' . $invoicesQuery->toSql());
        
        // Execute the query with proper sorting to get the latest data
        $invoices = $invoicesQuery
            ->orderBy('id', 'DESC')          // Finally by ID
            ->limit($limit)
            ->get();

        // Calculate statistics with fresh query instances
        $statistics = [
            'totalPaidAmount' => Invoices::where('is_deleted', 0)->where('payment_status', 'Paid')->sum('grand_total'),
            'totalUnpaidAmount' => Invoices::where('is_deleted', 0)->where('payment_status', 'Un-Paid')->sum('grand_total'),
            'totalDraftAmount' => Invoices::where('is_deleted', 0)->where('active', 0)->sum('grand_total'),
            'totalPublicAmount' => Invoices::where('is_deleted', 0)->where('active', 1)->sum('grand_total'),
            'paidCount' => Invoices::where('is_deleted', 0)->where('payment_status', 'Paid')->count(),
            'unpaidCount' => Invoices::where('is_deleted', 0)->where('payment_status', 'Un-Paid')->count(),
            'draftCount' => Invoices::where('is_deleted', 0)->where('active', 0)->count(),
            'publicCount' => Invoices::where('is_deleted', 0)->where('active', 1)->count(),
        ];

        return view('Admin.Invoices.index', array_merge(
            compact('users', 'roles', 'invoices'),
            $statistics
        ));
    }

    public function create()
    {

        $users = User::where('active', 1)->get();
        $roles = DB::table('roles')->get();

        $logged_in_user = Auth::user();

        $invoice = new Invoices();
        $invoice->created_by = $logged_in_user->id;
        $invoice->save();
        $Invoice_review = $this->ReviewInvoice($invoice->id);
        return view('Admin.Invoices.create', compact('users', 'roles', 'invoice', 'Invoice_review'));
    }
    public function edit(Invoices $invoice)
    {

        $users = User::where('active', 1)->get();
        $roles = DB::table('roles')->get();
        $plans_list = [];
        if ($invoice->customer_type == 'existing') {
            $customer_id = $invoice->customer;
            $plans_list = DB::table('membership_subscriptions')
                ->join('membership_plans', 'membership_subscriptions.plan_id', '=', 'membership_plans.id')
                ->where('membership_subscriptions.customer_id', $customer_id)
                ->where('membership_subscriptions.expired', 0)
                ->get();
        }
        $Invoice_review = $this->ReviewInvoice($invoice->id);
        return view('Admin.Invoices.edit', compact('users', 'roles', 'invoice', 'Invoice_review', 'plans_list'));
    }
    public function destroy_new($id)
    {
        $invoice = Invoices::find($id);
        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
        try {
            $invoice->delete();
            DB::table('invoices_meta')->where('invoice_id', $invoice->id)->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete the invoice'], 500);
        }
        return response()->json(['message' => 'Invoice deleted successfully']);
    }

    public function is_delete($id)
    {
        $invoice = Invoices::findOrFail($id);
        $invoice->is_deleted = 1;
        $invoice->save();
        return response()->json(['message' => 'Invoice deleted successfully'], 200);
    }

    public function searchProducts(Request $request)
    {
        $search_query = $request->get('query');
    
        // First, check in products by title or SKU where active = 1
        $products = Product::where('active', 1)
            ->where(function ($q) use ($search_query) {
                $q->where('title', 'like', "%{$search_query}%")
                  ->orWhere('sku', 'like', "%{$search_query}%");
            })
            ->limit(15)
            ->get();
    
        if ($products->isNotEmpty()) {
            return response()->json(['products' => $products]);
        } else {
            // If no results by title or SKU, check by UPC from 'products_meta' table
            $check_upc = DB::table('products_meta')
                ->where('option', '=', '_upc')
                ->where('value', 'like', "%{$search_query}%")
                ->limit(15)
                ->pluck('product_id');
            
            if ($check_upc->isNotEmpty()) {
                // Filter only active products among these IDs
                $activeProductsByUPC = Product::where('active', 1)
                    ->whereIn('id', $check_upc)
                    ->get();
    
                return response()->json(['products' => $activeProductsByUPC]);
            } else {
                return response()->json(['products' => []]);
            }
        }
    }
    

    public function selectProduct(Request $request)
    {
        $product_id      = $request->get('product_id');
        $product_details = Product::find($product_id);
    
        // Fetch _price, _weight, and _gst from products_meta
        // (assuming 'value' holds '0' or '1' for _gst)
        $product_meta = DB::table('products_meta')
            ->where('product_id', $product_id)
            ->whereIn('option', ['_price', '_weight', '_gst','_is_weight'])
            ->get()
            ->keyBy('option');
    
        // We'll only set gst = true if _gst == '1'
        $gstApplicable = false;
        if (isset($product_meta['_gst']) && $product_meta['_gst']->value === '1') {
            $gstApplicable = true;
        }
    
        // Fetch discount data from the product if available
        $discountData = $product_details->discount_data ?? null;
    
        return response()->json([
            'product'       => $product_details,                // entire product row
            'meta'          => $product_meta['_price'] ?? null,   // row for _price
            'weight'        => $product_meta['_weight'] ?? null,  // row for _weight
            'is_weight'        => $product_meta['_is_weight'] ?? null,  // row for _weight
            'gst'           => $gstApplicable,                    // boolean: true/false
            'discount_data' => $discountData                      // discount data if available
        ]);
    }
    
    


    public function update(Request $requestData, $id)
{
    DB::beginTransaction();
    try {
        // Find the invoice or throw a 404 error
        $invoice = Invoices::findOrFail($id);

        // Update invoice main data
        $invoice->customer_type                   = $requestData->input('customer_type'); 
        $invoice->customer                        = $requestData->input('customer');
        $invoice->total_units                     = $requestData->input('total_units');
        $invoice->sub_total                       = $requestData->input('sub_total');
        $invoice->discount                        = $requestData->input('discount');
        $invoice->additional_discount             = $requestData->input('additional_discount', 0);
        $invoice->discounted_amount               = $requestData->input('discounted_amount');
        $invoice->gst                             = $requestData->input('gst');
        $invoice->grand_total                     = $requestData->input('grand_total');
        $invoice->invoice_date                    = $requestData->input('invoice_date');
        $invoice->due_date                        = $requestData->input('due_date');
        $invoice->active                          = $requestData->input('active');
        $invoice->payment_status                  = $requestData->input('payment_status');
        $invoice->payment_method                  = $requestData->input('payment_method');
        $invoice->payment_id                      = $requestData->input('payment_id');
        $invoice->plan_id                         = $requestData->input('plan_id');
        $invoice->membership_dicount              = $requestData->input('membership_dicount');
        $invoice->membership_dicounted_total      = $requestData->input('membership_dicounted_total');
        $invoice->membership_discount_percentage  = $requestData->input('membership_discount_percentage');

        $invoice->save();
        
        // Delete existing invoice meta records
        $deleted = DB::table('invoices_meta')->where('invoice_id', $id)->delete();
        \Log::info("Deleted {$deleted} records from invoices_meta for invoice ID: {$id}");

        // Get product data arrays
        $product_ids       = $requestData->input('product_id', []);
        $product_names     = $requestData->input('product_name', []);
        $weights           = $requestData->input('weight', []);
        $unit_prices       = $requestData->input('price_for_entire_weight', []);
        $total_prices      = $requestData->input('total_price', []);
        $per_kg_price      = $requestData->input('per_kg_price', []);
        $default_weight    = $requestData->input('default_weight', []);
        $gst_status        = $requestData->input('gst_status', []);
        
        // Check if we have products to add
        \Log::info("Number of products to add: " . count($product_ids));
        
        // Batch insert for better performance
        $items_to_insert = [];
        
        for ($i = 0; $i < count($product_ids); $i++) {
            $items_to_insert[] = [
                'invoice_id'        => $id,
                'invoice_number'    => $invoice->invoice_number,
                'product_id'        => isset($product_ids[$i]) ? $product_ids[$i] : null,
                'product_name'      => isset($product_names[$i]) ? $product_names[$i] : null,
                'units'             => isset($weights[$i]) ? $weights[$i] : 0,
                'unit_price'        => isset($unit_prices[$i]) ? $unit_prices[$i] : 0,
                'unit_total_price'  => isset($total_prices[$i]) ? $total_prices[$i] : 0,
                'per_kg_price'      => isset($per_kg_price[$i]) ? $per_kg_price[$i] : 0,
                'default_weight'    => isset($default_weight[$i]) ? $default_weight[$i] : 0,
                'gst_status'        => isset($gst_status[$i]) ? $gst_status[$i] : 0,
                'created_at'        => now(),
                'updated_at'        => now()
            ];
        }
        
        if (!empty($items_to_insert)) {
            $inserted = DB::table('invoices_meta')->insert($items_to_insert);
            \Log::info("Invoice meta insertion result: " . ($inserted ? 'success' : 'failed'));
            
            if (!$inserted) {
                throw new \Exception("Failed to insert invoice meta data");
            }
        } else {
            \Log::warning("No products to insert for invoice ID: {$id}");
        }

        // Review the invoice (assuming this is a validation or summary method)
        $Invoice_review = $this->ReviewInvoice($id);
        
        // Commit the transaction
        DB::commit();

        return response()->json([
            'message'        => 'Invoice updated successfully',
            'invoice_review' => $Invoice_review
        ], 200);

    } catch (\Exception $e) {
        // Rollback transaction on error
        DB::rollBack();
        
        \Log::error("Error updating invoice {$id}: " . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'error' => 'Error updating invoice: ' . $e->getMessage()
        ], 500);
    }
}
    

    public function generatePdf($id)
    {
        $Invoice_review = $this->ReviewInvoice($id);
        return response()->json(['message' => 'Invoice updated successfully', 'invoice_review' => $Invoice_review], 200);
    }
    public function update_quantity($inovice_id)
    {
        $get_invoice_items = DB::table('invoices_meta')->where('invoice_id', $inovice_id)->get();
        foreach ($get_invoice_items as $get_invoice_item) {

            $product_meta = DB::table('products_meta')->where('product_id', $get_invoice_item->product_id)->where('option', '_stock')->first();
            $invoice_quantity = $get_invoice_item->units;
            $product_stock = $product_meta->value;
            $updated_stock = $product_stock - $invoice_quantity;
            DB::table('products_meta')->where('product_id', '=', $get_invoice_item->product_id)->where('option', '=', '_stock')->update(['value' => $updated_stock]);
        }
    }
}
