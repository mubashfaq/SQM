<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\order_meta;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Traits\InvoiceTrait;
use App\Http\Controllers\Website\Traits\OSInvoiceTrait;
use App\Models\Invoices;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderMeta;

class UserAddressController extends Controller
{
    use InvoiceTrait;
    use OSInvoiceTrait;
    public function update($id)
    {
        $shipping = UserAddress::where('id', $id)->first();
        return response()->json($data = $shipping);
    }

    public function store(Request $request)
    {
        $logged_in_user = Auth::user();
        $request->validate([
            'f_name' => 'required|string',
            'l_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'street_address' => 'required|string',
            'suburb' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'post_code' => 'required|numeric',
            'address_type' => 'required|string',
        ]);
        if ($request->id) {
            UserAddress::where('id', $request->id)->update([
                'user_id' => $logged_in_user->id,
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'email' => $request->email,
                'company' => $request->company,
                'phone' => $request->phone,
                'street_address' => $request->street_address,
                'city' => $request->city,
                'post_code' => $request->post_code,
                'suburb' => $request->suburb,
                'state' => $request->state,
                'address_type' => $request->address_type,
            ]);
        } else {
            $shipping = UserAddress::create([
                'user_id' => $logged_in_user->id,
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'email' => $request->email,
                'company' => $request->company,
                'phone' => $request->phone,
                'street_address' => $request->street_address,
                'city' => $request->city,
                'post_code' => $request->post_code,
                'suburb' => $request->suburb,
                'state' => $request->state,
                'address_type' => $request->address_type,
            ]);
        }
        return redirect()->back()->with(['message' => 'Shipping Address is added successfully']);
    }

    public function delete_address($id)
    {
        UserAddress::where('id', $id)->delete();
        return redirect()->back()->with(['message' => 'Address Deleted Successfully']);
    }

    public function order_details(Request $request)
    {
        $meta_details = [
            'title' => 'Order Details | Sydney Quality Meat',
            'keywords' => 'donate, donation, charity, Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat. Together, we can make a difference in the lives of those in need.',
            'author' => 'Sydney Quality Meat Org',
        ];

        $orderdetails = order::where('id', base64_decode($request->id))->with('UserAddress')
            ->first();
        $orders = order_meta::with('product')
            ->where('order_id', base64_decode($request->id))
            ->get();
        $Invoice_review = $this->ReviewOSInvoice(base64_decode($request->id));
        return view('Website.GeneralPages.orderdetail', compact('meta_details', 'orders', 'orderdetails', 'Invoice_review'));
    }
    
    public function return_order_details(Request $request){
        $meta_details = [
            'title' => 'Return Order Detail | Sydney Quality Meat',
            'keywords' => 'donate, donation, charity, Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat. Together, we can make a difference in the lives of those in need.',
            'author' => 'Sydney Quality Meat Org',
        ];

        $return = ReturnOrder::where('id', base64_decode($request->id))->with('order')->first();
        $returnMeta = ReturnOrderMeta::with('product')
            ->where('return_id', base64_decode($request->id))
            ->get();
        $return_invoice_review = $this->ReviewOSReturnInvoice(base64_decode($request->id));
        return view('Website.GeneralPages.return-order-detail', compact('meta_details', 'return', 'returnMeta', 'return_invoice_review'));
    }
    
    public function pos_order_detail($id)
    {
        $meta_details = [
            'title' => 'Profile | Sydney Quality Meat',
            'keywords' => 'donate, donation, charity, Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat. Together, we can make a difference in the lives of those in need.',
            'author' => 'Sydney Quality Meat Org',
        ];
        $invoices = Invoices::where('is_deleted', 0)->where('id', $id)->where('active', 1)->first();
        $Invoice_review = $this->ReviewInvoice($id);
        return view('Website.GeneralPages.pos-order-detail', compact('meta_details', 'Invoice_review', 'invoices'));
    }

    public function guest_order_details(Request $request)
    {
        $meta_details = [
            'title' => 'Profile | Sydney Quality Meat',
            'keywords' => 'donate, donation, charity, Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat. Together, we can make a difference in the lives of those in need.',
            'author' => 'Sydney Quality Meat Org',
        ];

        if ($request->order_id) {
            $orderdetails = order::where('invoice_number', $request->order_id)->with('orderAddressDetail')->first();
            if ($orderdetails) {
                $orders = order_meta::with('product')->where('order_id', $orderdetails->id)->get();
                $message = "";
                $Invoice_review = $this->ReviewOSInvoice($orderdetails->id);
                return view('Website.GeneralPages.guest-order-detail', compact('meta_details', 'orders', 'orderdetails', 'Invoice_review', 'message'));
            } else {
                $message = "Sorry! No Order found aggainst this invoice number";
                return view('Website.GeneralPages.guest-order-detail', compact('meta_details', 'orderdetails', 'message'));
            }
        } else {
            $message = "";
            return view('Website.GeneralPages.guest-order-detail', compact('meta_details', 'message'));
        }
    }

    public function print_os_invoice($id)
    {
        $Invoice_review = $this->ReviewOSInvoice($id);
        return response()->json(['message' => 'Invoice updated successfully', 'invoice_review' => $Invoice_review], 200);
    }
    
    public function print_return_invoice($id){
        $return_invoice_review = $this->ReviewOSReturnInvoice($id);
        return response()->json(['message' => 'Invoice updated successfully', 'invoice_review' => $return_invoice_review], 200);
    }

    public function change_shipping_address()
    {
        $user = Auth::user();
        if ($user) {
            $shipping = UserAddress::where('user_id', Auth::id())->where('address_type', 'default shipping')->get();
            if (!$shipping->isEmpty()) {
                return view('Website.GeneralPages.ajax.shipping-address', compact('shipping'))->render();
            } else {
                return view('Website.GeneralPages.ajax.extra-shipping-address')->render();
            }
        } else {
            return view('Website.GeneralPages.ajax.extra-shipping-address')->render();
        }
    }
}
