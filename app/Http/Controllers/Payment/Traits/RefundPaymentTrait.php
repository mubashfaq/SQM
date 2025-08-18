<?php

namespace App\Http\Controllers\Payment\Traits;

use Illuminate\Http\Request;
use App\General\StaticFunctionsClass;
use App\General\GeneralSettingsClass;
use App\Http\Controllers\Website\EmailController;
use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\GeneralSettings;
use App\Models\GiftCardPurchases;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderMeta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use PayPal\Api\Payment;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Sale;
use PayPal\Api\Refund;
use PayPal\Exception\PayPalConnectionException;
use Stripe\Stripe;
use Stripe\Refund as StripeRefund;

trait RefundPaymentTrait
{
    function paypalPaymentRefund($request, $flag = 0)
    {
        $order = order::where('invoice_number', $request->invoiceNumber)->with('orderMeta')->first();
        if ($order) {
            $product_array = session('product_array');
            $returnable = session('returnable');

            $activeEnvironment = GeneralSettings::where('option', '_active_paypal_environment')->first();
            $paypalSandboxPublicKey = GeneralSettings::where('option', '_paypal_sandbox_key')->first();
            $paypalSandboxSecreteKey = GeneralSettings::where('option', '_paypal_sandbox_secrete_key')->first();
            $paypalLivePublicKey = GeneralSettings::where('option', '_paypal_live_key')->first();
            $paypalLiveSecreteKey = GeneralSettings::where('option', '_paypal_live_secrete_key')->first();

            $apiContext = null;

            if ($activeEnvironment) {
                $paypalEnvironment = $activeEnvironment->value;
                if ($paypalEnvironment === 'live' && $paypalLivePublicKey && $paypalLiveSecreteKey) {
                    $apiContext = new ApiContext(new OAuthTokenCredential(
                        $paypalLivePublicKey->value,
                        $paypalLiveSecreteKey->value
                    ));
                    $apiContext->setConfig([
                        'mode' => 'live',
                    ]);
                } else if ($paypalEnvironment === 'sandbox' && $paypalSandboxPublicKey && $paypalSandboxSecreteKey) {
                    $apiContext = new ApiContext(new OAuthTokenCredential(
                        $paypalSandboxPublicKey->value,
                        $paypalSandboxSecreteKey->value
                    ));
                    $apiContext->setConfig([
                        'mode' => 'sandbox',
                    ]);
                }

                $saleId = $order->orderMeta[0]->payment_intent_id;

                $refund = new Refund();
                $refund->setAmount([
                    'total' => $request->total,
                    'currency' => 'AUD'
                ]);
                try {
                    $payment = Payment::get($saleId, $apiContext);
                    if ($payment->getState() === 'approved') {

                        $sale = $payment->getTransactions()[0]->getRelatedResources()[0]->getSale();
                        $refund = $sale->refund($refund, $apiContext);

                        $order->update([
                            'status' => 'refund'
                        ]);
                        $returnOrder = ReturnOrder::create([
                            'order_id' => $order->id,
                            'user_id' => Auth::id() ?? 0,
                            'gift_card_id' => 0,
                            'user_email' => $order->email,
                            'grandTotal' => $returnable,
                            'return_type' => 'manually',
                            'return_via' => 'Paypal',
                        ]);

                        foreach ($product_array as $product) {
                            $returnMeta = ReturnOrderMeta::create([
                                'return_id' => $returnOrder->id,
                                'product_id' => $product['product_id'],
                                'quantity' => $product['quantity'],
                                'price' => $product['price'],
                                'subTotal' => $product['subTotal'],
                            ]);
                        }

                        $details = ReturnOrderMeta::where('return_id', $returnOrder->id)->with('product')->get();
                        if($flag == 1){
                            $this->sendConfirmationMail($details, $order, $returnOrder);
                        }

                        Session::forget('product_array');
                        Session::forget('returnable');
                        return response(['status' => true, 'message' => 'Order Returned Successfully you will shortly receive an email.']);
                    } else {
                        return response()->json(['error' => 'Payment is not approved. Refund cannot be initiated.'], 400);
                    }
                } catch (PayPalConnectionException $e) {
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            }
        }
    }

    function stripePaymentRefund($request, $flag = 0)
    {
        $order = order::where('invoice_number', $request->invoiceNumber)->with('orderMeta')->first();
        if ($order) {
            $product_array = session('product_array');
            $returnable = session('returnable');

            $activeStripeEnvironment = GeneralSettings::where('option', '_active_stripe_environment')->first();
            $stripeKeyActive = '';
            if ($activeStripeEnvironment) {
                $stripeLiveKey = GeneralSettings::where('option', '_stripe_live_key')->first();
                $stripeSandboxkey = GeneralSettings::where('option', '_stripe_sandbox_key')->first();
                if ($activeStripeEnvironment->value === "live" && $stripeLiveKey) {
                    $stripeKeyActive = $stripeLiveKey->value;
                } else if ($activeStripeEnvironment->value === "sandbox" && $stripeLiveKey) {
                    $stripeKeyActive = $stripeSandboxkey->value;
                }
            }

            Stripe::setApiKey($stripeKeyActive);

            try {
                $chargeId = $order->orderMeta[0]->payment_intent_id;

                // $refundAmountInCents = (int) ($request->total * 100);
                $refund = StripeRefund::create([
                    'payment_intent' => $chargeId,
                    'amount' => intval($request->total),
                ]);
                if ($refund->status == 'succeeded') {
                    $order->update([
                        'status' => 'refund'
                    ]);
                    $returnOrder = ReturnOrder::create([
                        'order_id' => $order->id,
                        'user_id' => Auth::id() ?? 0,
                        'gift_card_id' => 0,
                        'user_email' => $order->email,
                        'grandTotal' => $returnable,
                        'return_type' => 'manually',
                        'return_via' => 'Stripe',
                    ]);

                    foreach ($product_array as $product) {
                        $returnMeta = ReturnOrderMeta::create([
                            'return_id' => $returnOrder->id,
                            'product_id' => $product['product_id'],
                            'quantity' => $product['quantity'],
                            'price' => $product['price'],
                            'subTotal' => $product['subTotal'],
                        ]);
                    }
                    $details = ReturnOrderMeta::where('return_id', $returnOrder->id)->with('product')->get();
                    if($flag == 1){
                        // $this->sendConfirmationMail($details, $order, $returnOrder);
                    }

                    Session::forget('product_array');
                    Session::forget('returnable');
                    return response(['status' => true, 'message' => 'Order Returned Successfully you will shortly receive an email.']);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }

    function giftCardPaymentRefund($request)
    {
        $product_array = session('product_array');
        $returnable = session('returnable');

        if ($request->total == $returnable) {
            $order = order::where('invoice_number', $request->invoiceNumber)->first();
            $currentDate = Carbon::now();
            $coupon_code = StaticFunctionsClass::generateUniqueCouponCode();

            $giftCard = new GiftCardPurchases();
            $giftCard->user_id = Auth::id() ?? 0;
            $giftCard->card_id = 0;
            $giftCard->coupon_code = $coupon_code;
            $giftCard->customer_name = $order->f_name . " " . $order->l_name;
            $giftCard->customer_email = $order->email;
            $giftCard->customer_phone = $order->phone;
            $giftCard->business_name = $order->company;
            $giftCard->street_address = $order->street_address;
            $giftCard->suburb = $order->suburb;
            $giftCard->city = $order->city;
            $giftCard->state = $order->state;
            $giftCard->post_code = $order->post_code;
            $giftCard->payment_status = 'N/A';
            $giftCard->payment_method = 'Return Order';
            $giftCard->payment_intent = 'N/A';
            $giftCard->channel = 'Online store';
            $giftCard->total_amount = $returnable;
            $giftCard->purchasing_date = $currentDate;
            $giftCard->remaining_amount = $returnable;
            $giftCard->expiry_date = null;
            $giftCard->is_used = '0';
            $giftCard->is_expired = '0';
            $giftCard->used_times = 0;
            $giftCard->save();

            $order->update([
                'status' => 'refund'
            ]);

            $returnOrder = ReturnOrder::create([
                'order_id' => $order->id,
                'user_id' => Auth::id() ?? 0,
                'gift_card_id' => $giftCard->id,
                'user_email' => $order->email,
                'return_type' => 'manually',
                'return_via' => 'Gift Card',
                'grandTotal' => $returnable
            ]);

            foreach ($product_array as $product) {
                $returnMeta = ReturnOrderMeta::create([
                    'return_id' => $returnOrder->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'subTotal' => $product['subTotal'],
                ]);
            }

            $card = GiftCardPurchases::where('id', $giftCard->id)->first();
            $details = ReturnOrderMeta::where('return_id', $returnOrder->id)->with('product')->get();
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
                'to' => $card->customer_email,
                'blade' => 'return-order-via-card',
                'data' => [
                    'name' => $card->customer_name,
                    'returnOrder' => $returnOrder,
                    'details' => $details,
                    'card' => $card,
                    'invoice_number' => $order->invoice_number,
                    'url' => url('/'),
                    'logo' => $logo,
                    'instagram_link' => $instagram_link,
                    'facebook_link' => $facebook_link,
                    'twitter_link' => $twitter_link,
                    'youtube_link' => $youtube_link,
                    'admin_email' => $admin_email,
                ],
            ];

            $emailController = new EmailController();
            $emailSent = $emailController->SendInstantEmail($params);

            Session::forget('product_array');
            Session::forget('returnable');
            return response(['status' => true, 'message' => 'Order Returned Successfully you will shortly receive an email.']);
        } else {
            return response(['status' => false, 'message' => 'Oops! Something went wrong. Please refresh the page and try refreshing the page.']);
        }
    }
}
