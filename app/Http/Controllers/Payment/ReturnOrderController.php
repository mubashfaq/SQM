<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\Traits\RefundPaymentTrait;
use App\Models\GeneralSettings;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReturnOrderController extends Controller
{
    public $website_title = 'Sydney Quality Meats';
    public $returnableAmount = 0;
    use RefundPaymentTrait;
    public function return_order_page(Request $request)
    {
        $meta_details = [
            'title' => 'OS Order Detail | ' . $this->website_title,
            'keywords' => 'tools, genuine, hardware, makita, australia',
            'description' => 'Gift Cards can be a very useful thing to gift someone.',
            'author' => 'Sydney Quality Meats',
        ];
        if ($request->invoice_number) {
            $order = Order::where('invoice_number', $request->invoice_number)
                          ->with('orderMeta', 'orderAddressDetail')
                          ->first();
            if (!$order) {
                // Set an error message if the order is not found
                return redirect()->back()->with('error', 'Order invoice not found');
            }
            // If order is found, return the order details
            $this->return_order_array($order);
            return view('Admin.OrderReturn.create', compact('meta_details', 'order'));
        }
        return view('Admin.OrderReturn.create', compact('meta_details'));
    }
    

    public function return_order_array($order)
    {
        $product_array = null;
        foreach ($order->orderMeta as $key => $product) {
            $product_array[$key] = [
                'product_id' => $product->product_id,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'subTotal' => $product->qp_subtotal,
            ];
        }
        session(['product_array' => $product_array]);
        session(['returnable' => $order->grand_total]);
    }

    public function update_return_array(Request $request)
    {
        $product_array = session('product_array');
        session(['returnable' => $request->returnable]);
        foreach ($product_array as $key => $arr) {
            if ($arr['product_id'] == $request->pId) {
                if ($request->method == 'remove') {
                    unset($product_array[$key]);
                } elseif ($request->method == 'quantity') {
                    $product_array[$key] = [
                        'product_id' => $arr['product_id'],
                        'price' => $arr['price'],
                        'quantity' => $request->quantity,
                        'subTotal' => $request->productSubTotal,
                    ];
                }
            }
        }
        session(['product_array' => $product_array]);
    }

    public function initiatePayPalRefund(Request $request)
    {
        $validation = $request->validate([
            'invoiceNumber' => 'required',
            'total' => 'required',
        ]);
        $flag = 1;
        return $this->paypalPaymentRefund($request, $flag);
    }

    public function initiateStripeRefund(Request $request)
    {
        $validation = $request->validate([
            'invoiceNumber' => 'required',
            'total' => 'required',
        ]);
        $flag = 1;
        return $this->stripePaymentRefund($request, $flag);
    }

    public function initialGiftCardRefund(Request $request)
    {
        $validation = $request->validate([
            'invoiceNumber' => 'required',
            'total' => 'required',
        ]);
        return $this->giftCardPaymentRefund($request);
    }

    public function sendConfirmationMail($details, $order, $returnOrder)
    {
        $data = GeneralSettings::where('option', '_header_logo')->orWhere('option', '_b_email')->orWhere('option', '_instagram_link')->orWhere('option', '_facebook_link')->orWhere('option', '_twitter_link')->orWhere('option', '_youtube_link')->get()->toArray();

        $admin_email_index = array_search('_b_email', array_column($data, 'option'));
        $header_logo_index = array_search('_header_logo', array_column($data, 'option'));
        $insta_link_index = array_search('_instagram_link', array_column($data, 'option'));
        $fb_link_index = array_search('_facebook_link', array_column($data, 'option'));
        $twitter_link_index = array_search('_twitter_link', array_column($data, 'option'));
        $youtube_link_index = array_search('_youtube_link', array_column($data, 'option'));

        $admin_email = $data[$admin_email_index]['value'];
        $logo = $data[$header_logo_index]['value'];
        $instagram_link = $data[$insta_link_index]['value'];
        $facebook_link = $data[$fb_link_index]['value'];
        $twitter_link = $data[$twitter_link_index]['value'];
        $youtube_link = $data[$youtube_link_index]['value'];

        $params = [
            'subject' => 'Return Order Confirmation | ' . config('app.website_name'),
            'to' => $order->email,
            'blade' => 'return-order-via-payment',
            'msg' => view('Admin.Emails.order-conformation', ['name' => $order->name,
                'order' => $order,
                'email' => $order->email,
                'returnOrder' => $returnOrder,
                'details' => $details,
                'invoice_number' => $order->invoice_number,
                'image_url' => url('/'),
                'logo' => $logo,
                'instagram_link' => $instagram_link,
                'facebook_link' => $facebook_link,
                'twitter_link' => $twitter_link,
                'youtube_link' => $youtube_link,
                'admin_email' => $admin_email])->render(),
        ];

        $emailSent = $this->SendInstantEmail($params);
    }
}
