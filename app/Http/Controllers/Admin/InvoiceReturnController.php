<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Traits\ReturnInvoiceTrait;
use App\Http\Controllers\Admin\Traits\InvoiceTrait;
use App\Models\InvoiceReturn;
use App\Models\InvoiceReturnsHistory;
use App\Models\InvoiceReturnsMeta;
use App\Models\Invoices;
use App\Models\Product;

class InvoiceReturnController extends Controller
{
    use ReturnInvoiceTrait;
    use InvoiceTrait;
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)

    {
      
        $users = User::all();
        $roles = DB::table('roles')->get();
        $limit = $request->limit ?? 20;
        $order = 'DESC';

        $invoice_return = InvoiceReturn::join('invoices', 'invoice_returns.invoice_number', 'invoices.invoice_number')
            ->select(
                'invoice_returns.*',
                'invoices.customer_type',
                'invoices.customer',
                'invoices.invoice_date',
                'invoices.due_date',
                'invoices.total_units',
                'invoices.sub_total',
                'invoices.discount',
                'invoices.discounted_amount',
                'invoices.gst',
                'invoices.grand_total',
                'invoices.payment_status',
                'invoices.payment_method',
                'invoices.active'
            );

        if (isset($request->invoice_number)) {
            $invoice_return->where('invoice_returns.invoice_number', 'LIKE', "%$request->invoice_number%");
        }
        if (isset($request->customer_name)) {
            $invoice_return->join('users', 'invoices.customer', '=', 'users.id')
                           ->where('users.name', 'LIKE', "%{$request->customer_name}%");
        }
        if (isset($request->from)) {
            $invoice_return->where('invoice_returns.return_date', '>=', $request->from);
        }
        if (isset($request->to)) {
            $invoice_return->where('invoice_returns.return_date', '<=', $request->to);
        }
        if (isset($request->order_amount)) {
            $invoice_return->where('invoices.grand_total', '>=', $request->order_amount);
        }

        $invoices = $invoice_return->latest()->paginate($limit);

        return view('Admin.InvoicesReturn.index', compact('users', 'roles', 'invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return (80/100)*2;
        
        $users = User::all();
        $roles = DB::table('roles')->get();
        $logged_in_user = Auth::user();
        // $Invoice_review = $this->ReviewInvoice($invoice->id);
        return view('Admin.InvoicesReturn.create', compact('users', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $users = User::all();
        $roles = DB::table('roles')->get();
        $invoice_return = InvoiceReturn::where('invoice_returns.id', $id)->with('invoice_return_meta', 'invoice')->first();
        $Invoice_review = $this->ReturnReviewInvoice($invoice_return->invoice->id, $invoice_return);
        return view('Admin.InvoicesReturn.edit', compact('users', 'roles', 'invoice_return', 'Invoice_review'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'invoice_id' => 'required',
            'product_id' => 'required',
            'product_name.*' => 'required',
            'units.*' => 'required',
            'unit_price.*' => 'required',
            'return_units.*' => 'nullable',
            'status' => 'required',
        ]);

        $invoice = Invoices::find($id);
        if ($invoice) {
            $invoice_returns = InvoiceReturn::where('invoice_number', $invoice->invoice_number)->first();
            if (!$invoice_returns) {
                $invoice_returns = new InvoiceReturn();
                $previous_sub_total = $previous_discounted_amount = $previous_gst = $previous_grand_total = $previous_membership_discount = 0;
            } else {
                $previous_sub_total = $invoice_returns->sub_total;
                $previous_discounted_amount = $invoice_returns->general_discounted_amount;
                $previous_membership_discount = $invoice_returns->membership_discounted_amount;
                $previous_gst = $invoice_returns->gst;
                $previous_grand_total = $invoice_returns->grand_total;
            }
            $invoice_returns->invoice_number = $invoice->invoice_number;
            $invoice_returns->created_by = $invoice->created_by;
            $invoice_returns->return_date = date('Y-m-d');
            $invoice_returns->status = $request['status'];
            $invoice_returns->save();

            $return_units_check = $sub_total = 0;
            for ($i = 0; $i < count($request['product_id']); $i++) {
                $invoice_returns_meta = InvoiceReturnsMeta::where('invoice_number', $invoice->invoice_number)
                    ->where('product_id', $request['product_id'][$i])->first();

                $invoices_meta = DB::table('invoices_meta')->where('invoice_id', $invoice->id)->where('product_id', $request['product_id'][$i])->first();

                if (!$invoice_returns_meta) {
                    $invoice_returns_meta = new InvoiceReturnsMeta();
                    $return_units = $request['return_units'][$i];
                } else {
                    if ($invoice_returns_meta->return_units == $invoices_meta->units) {
                        $return_units_check = 1;
                        $message = "Customer already return all items.";
                        break;
                    }

                    if (isset($request->type) && $request->type == 'edit') {
                        $return_units = $request['return_units'][$i];
                    } else {
                        $return_units = $request['return_units'][$i] + $invoice_returns_meta->return_units;
                    }
                }
                if ($return_units > $invoices_meta->units) {
                    $return_units_check = 1;
                    $message = 'Return units are greater than the total units you purchased.';
                    break;
                }
                $total_units = $invoices_meta->units - $return_units;;
                $invoice_returns_meta->invoice_number = $invoice->invoice_number;
                $invoice_returns_meta->invoice_return_id = $invoice_returns->id;
                $invoice_returns_meta->product_id = $request['product_id'][$i];
                $invoice_returns_meta->unit_price = $invoices_meta->unit_price;
                $invoice_returns_meta->total_units = $total_units;
                $invoice_returns_meta->return_units = $return_units;
                $invoice_returns_meta->save();

                if (isset($request->type) && $request->type == 'edit') {
                    $log = 'On ' . date('d-m-Y H:i:s') . ' ' . $request['return_units'][$i] . ' Updated the Units return of ' . $request['product_name'][$i] . '(' . $request['product_id'][$i] . ')' . ', return updated by ' . Auth::user()->name;
                } else {
                    $log = 'On ' . date('d-m-Y H:i:s') . ' ' . $request['return_units'][$i] . ' Units return of ' . $request['product_name'][$i] . '(' . $request['product_id'][$i] . ')' . ', return updated by ' . Auth::user()->name;
                }
                $invoice_returns_history = new InvoiceReturnsHistory();
                $return_history['invoice_return_id'] = $invoice_returns->id;
                $return_history['description'] = $log;
                $return_history['date_time'] =  date('Y-m-d H:i:s');
                $invoice_returns_history->insert($return_history);

                $sub_total += $request['return_units'][$i] * $invoices_meta->unit_price;
            }

            if ($return_units_check) {
                return response()->json(['status' => false, 'message' => $message]);
            }

            if ($invoice->discount > 0) {
                $discounted_amount = $sub_total - ($sub_total / 100) * $invoice->discount;
            } else {
                $discounted_amount = $sub_total;
            }
            if ($invoice->membership_discount > 0) {
                $membership_discounted_amount = $discounted_amount - ($discounted_amount / 100) * $invoice->membership_discount;
            } else {
                $membership_discounted_amount = $discounted_amount;
            }

            $gst = ($membership_discounted_amount / 100) * 10;
            $grand_total = $membership_discounted_amount - $gst;

            $total_return_units = $invoice_returns->units + array_sum($request['return_units']);
            $invoice_returns->units = $total_return_units;
            $invoice_returns->sub_total = $sub_total + $previous_sub_total;
            $invoice_returns->general_discount = $invoice->discount;
            $invoice_returns->general_discounted_amount = $discounted_amount + $previous_discounted_amount;
            $invoice_returns->membership_discount = $invoice->membership_discount;
            $invoice_returns->membership_discounted_amount = $membership_discounted_amount + $previous_membership_discount;
            $invoice_returns->gst = $gst + $previous_gst;
            $invoice_returns->grand_total = $grand_total + $previous_grand_total;
            $invoice_returns->save();

            $invoice_return = InvoiceReturn::where('invoice_returns.invoice_number', $invoice->invoice_number)->with('invoice_return_meta', 'invoice')->first();
            $Invoice_review = $this->IndividualReturnReviewInvoice($id, $invoice_return, $sub_total, $discounted_amount, $membership_discounted_amount, $gst, $grand_total);
            if ($invoice_returns->status == 1) {
                $this->update_quantity($invoice->invoice_number);
            }
            return response()->json(['status' => true, 'message' => 'Units return successfully', 'invoice_review' => $Invoice_review]);
        } else {
            return response()->json(['status' => false, 'message' => 'Invalid data.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function searchInvoices(Request $request)
    {
        
        $search_query = $request->get('query');
        $invoices = Invoices::where('invoices.invoice_number', 'like', "%$search_query%")
        ->where('invoices.active', 1)
        ->leftjoin('users', 'invoices.created_by', 'users.id')
        ->select('invoices.id', 'invoices.invoice_number', 'invoices.customer', 'invoices.invoice_date', 'users.name as created_by')
        ->get();
        if (sizeof($invoices) > 0) {
            return response()->json(['invoices' => $invoices]);
        } else {
            return response()->json(['invoices' => []]);
        }
    }

    public function selectInvoice(Request $request)
    {
       
        $invoice_details = Invoices::find($request->get('invoice_id'));
        $invoice_meta = DB::table('invoices_meta')
            ->where('invoice_id', '=', $request->get('invoice_id'))
            ->get()->toArray();
        $invoice_return_meta = InvoiceReturnsMeta::where('invoice_number', $invoice_details->invoice_number)->get()->toArray();
        if ($invoice_return_meta) {
            foreach ($invoice_meta as $key => $value) {
                $return_key = array_search($value->product_id, array_column($invoice_return_meta, 'product_id'));
                if ($return_key !== false) {
                    $value->units = $value->units - $invoice_return_meta[$return_key]['return_units'];
                }
            }
        }
        $invoice_return = InvoiceReturn::where('invoice_returns.invoice_number', $invoice_details->invoice_number)
            ->with('invoice_return_meta', 'invoice')
            ->first();
        $Invoice_review = $this->ReviewInvoice($request->get('invoice_id'), $invoice_return);
   
        $response = [
            'invoice' => $invoice_details,
            'meta' => $invoice_meta,
            'invoice_review' => $Invoice_review,
            'additional_discount' => $invoice_details->additional_discount ?? 0, // Default to 0 if not set
            'discount' => $invoice_details->discount ?? 0, // Default to 0 if not set
            'membership_discount' => $invoice_details->membership_dicount ?? 0 // Default to 0 if not set
        ];
        return response()->json($response);
    }
    

    public function is_delete($id)
    {
        InvoiceReturnsMeta::where('invoice_return_id', $id)->delete();
        InvoiceReturnsHistory::where('invoice_return_id', $id)->delete();
        InvoiceReturn::where('id', $id)->delete();

        return response()->json(['message' => 'Invoice deleted successfully'], 200);
    }

    public function generatePdf($id)
    {
        
        $invoice_return = InvoiceReturn::where('invoice_returns.id', $id)->with('invoice_return_meta', 'invoice')->first();
        $invoice = Invoices::where('invoice_number', $invoice_return->invoice_number)->first();
        $Invoice_review = $this->ReturnReviewInvoice($invoice->id, $invoice_return);
        return response()->json(['message' => 'Invoice updated successfully', 'invoice_review' => $Invoice_review], 200);
    }
    public function update_quantity($inovice_id)
    {
        $get_invoice_items = DB::table('invoice_returns_meta')->where('invoice_number', $inovice_id)->get();
        foreach ($get_invoice_items as $get_invoice_item) {

            $product_meta = DB::table('products_meta')->where('product_id', $get_invoice_item->product_id)->where('option', '_stock')->first();
            $invoice_quantity = $get_invoice_item->return_units;
            $product_stock = $product_meta->value;
            $updated_stock = $product_stock + $invoice_quantity;
            DB::table('products_meta')->where('product_id', '=', $get_invoice_item->product_id)->where('option', '=', '_stock')->update(['value' => $updated_stock]);
        }
    }
}
