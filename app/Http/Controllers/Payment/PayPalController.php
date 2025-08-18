<?php

namespace App\Http\Controllers\Payment;

use App\General\GeneralSettingsClass;
use App\General\StaticFunctionsClass;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Website\EmailController;
use App\Models\MembershipPlan;
use App\Models\NewsLetterSubscription;
use App\Models\order;
use App\Models\order_meta;
use App\Models\ProductMeta;
use App\Models\UserAddress;
use App\Models\GeneralSettings;
use App\Models\GiftCard;
use App\Models\GiftCardPurchases;
use App\Models\GiftCardUsed;
use App\Models\OrderAddressDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use Carbon\Carbon;

class PayPalController extends Controller
{
    protected $apiContext;

    public function __construct()
    {
        $activeEnvironment = GeneralSettings::where('option', '_active_paypal_environment')->first();
        $paypalSandboxPublicKey = GeneralSettings::where('option', '_paypal_sandbox_key')->first();
        $paypalSandboxSecreteKey = GeneralSettings::where('option', '_paypal_sandbox_secrete_key')->first();
        $paypalLivePublicKey = GeneralSettings::where('option', '_paypal_live_key')->first();
        $paypalLiveSecreteKey = GeneralSettings::where('option', '_paypal_live_secrete_key')->first();

        if ($activeEnvironment) {
            $paypalEnvironment = $activeEnvironment->value;
            if ($paypalEnvironment === 'live' && $paypalLivePublicKey && $paypalLiveSecreteKey) {
                $this->apiContext = new ApiContext(
                    new OAuthTokenCredential(
                        $paypalLivePublicKey->value,
                        $paypalLiveSecreteKey->value
                    )
                );
                $this->apiContext->setConfig([
                    'mode' => $paypalEnvironment,
                ]);
                $this->apiContext->setConfig(config('services.paypal.settings'));
            } else if ($paypalEnvironment === 'sandbox' && $paypalSandboxPublicKey && $paypalSandboxSecreteKey) {
                $this->apiContext = new ApiContext(
                    new OAuthTokenCredential(
                        $paypalSandboxPublicKey->value,
                        $paypalSandboxSecreteKey->value
                    )
                );
                $this->apiContext->setConfig([
                    'mode' => 'sandbox',
                ]);
                $this->apiContext->setConfig(config('services.paypal.settings'));
            } else {
                $paypal_configuration = config('services.paypal');
                $this->apiContext = new ApiContext(
                    new OAuthTokenCredential(config('services.paypal.client_id'), config('services.paypal.client_secret'))
                );
                $this->apiContext->setConfig($paypal_configuration['settings']);
            }
        } else {
            $paypal_configuration = config('services.paypal');
            $this->apiContext = new ApiContext(
                new OAuthTokenCredential(config('services.paypal.client_id'), config('services.paypal.client_secret'))
            );
            $this->apiContext->setConfig($paypal_configuration['settings']);
        }
    }

    public function create_order(Request $request)
    {
        session(['delivery_fee' => $request->deliveryFee ?? 0]);
        if ($request->shipping_address != 0 && is_numeric($request->shipping_address)) {
            $orderDetails = UserAddress::where('id', $request->shipping_address)->first();
            session(['shipping_address' => $orderDetails]);

            if ($request->billing_address) {
                if ($request->billing_address != 0 && is_numeric($request->billing_address)) {
                    $orderDetails = UserAddress::where('id', $request->billing_address)->first();
                    session(['billing_address' => $orderDetails]);
                } else {
                    $billingData = [
                        'f_name' => $request->billing_address['f_name'],
                        'l_name' => $request->billing_address['l_name'],
                        'email' => $request->billing_address['email'],
                        'company' => $request->billing_address['company'],
                        'street_address' => $request->billing_address['street_address'],
                        'suburb' => $request->billing_address['suburb'],
                        'city' => $request->billing_address['city'],
                        'state' => $request->billing_address['state'],
                        'post_code' => $request->billing_address['post_code'],
                        'phone' => $request->billing_address['phone'],
                    ];
                    $jsonString = json_encode($billingData);
                    $myObject = json_decode($jsonString);
                    session(['billing_address' => $myObject]);
                }
            }
        } else {
            $validation = $request->validate([
                'shipping_address.f_name' => 'required|string',
                'shipping_address.l_name' => 'required|string',
                'shipping_address.email' => 'required|email',
                'shipping_address.phone' => 'required|max:15',
                'shipping_address.street_address' => 'required|string',
                'shipping_address.city' => 'required|string',
                'shipping_address.post_code' => 'required|numeric',
                'shipping_address.suburb' => 'required|string',
                'shipping_address.state' => 'required|string',
            ]);
            if ($validation) {
                if ($request->billing_address) {
                    if ($request->billing_address != 0 && is_numeric($request->billing_address)) {
                        $orderDetails = UserAddress::where('id', $request->billing_address)->first();
                        session(['billing_address' => $orderDetails]);
                    } else {
                        $billingData = [
                            'f_name' => $request->billing_address['f_name'],
                            'l_name' => $request->billing_address['l_name'],
                            'email' => $request->billing_address['email'],
                            'company' => $request->billing_address['company'],
                            'street_address' => $request->billing_address['street_address'],
                            'suburb' => $request->billing_address['suburb'],
                            'city' => $request->billing_address['city'],
                            'state' => $request->billing_address['state'],
                            'post_code' => $request->billing_address['post_code'],
                            'phone' => $request->billing_address['phone'],
                        ];
                        $jsonString = json_encode($billingData);
                        $myObject = json_decode($jsonString);
                        session(['billing_address' => $myObject]);
                    }
                }
                $data = [
                    'f_name' => $request->shipping_address['f_name'],
                    'l_name' => $request->shipping_address['l_name'],
                    'email' => $request->shipping_address['email'],
                    'company' => $request->shipping_address['company'],
                    'street_address' => $request->shipping_address['street_address'],
                    'suburb' => $request->shipping_address['suburb'],
                    'city' => $request->shipping_address['city'],
                    'state' => $request->shipping_address['state'],
                    'post_code' => $request->shipping_address['post_code'],
                    'phone' => $request->shipping_address['phone'],
                ];
                $jsonString = json_encode($data);
                $myObject = json_decode($jsonString);
                session(['shipping_address' => $myObject]);
            } else {
                return response()->json(['error', $validation]);
            }
        }

        $calculatedAmount = 0;
        $cartData = session('cartData', []);
        $totalAmount = session('totalAmount', 0);
        $subTotal = session('subTotal', 0);
        $totalGst = session('totalGst', 0);
        $auth_discount = session('authDisount', 0);
        $shipping_address = $request->session()->get('shipping_address');
        $subTotalWithoutGST = floatval($subTotal) - floatval($totalGst);

        if ($request->discountPlanId) {
            $plan = MembershipPlan::where('id', $request->discountPlanId)->first();
            $discount = floatval($subTotalWithoutGST) * (floatval($plan->discount_percentage) / 100);
            $discountedAmount = floatval($subTotalWithoutGST) - floatval($discount);
            $calculatedAmount = floatval($discountedAmount) + floatval($totalGst);
            session(['discountedAmount' => $calculatedAmount]);
            session(['discountPlan' => $plan->plan_name ?? "N/A"]);
        } else if ($auth_discount) {
            $calculatedAmount = $totalAmount - floatval($auth_discount);
        } else {
            $calculatedAmount = $totalAmount;
        }

        //Postage api
        $deliverySetting = GeneralSettings::where('option', '_delivery_active')->first();
        if ($deliverySetting && $deliverySetting->value == 1) {

            $deliverykey = GeneralSettings::where('option', '_delivery_api_key')->first();
            $deliverypost = GeneralSettings::where('option', '_delivery_from_postcode')->first();
            $apiKey = $deliverykey->value;
            $client = new \GuzzleHttp\Client();
            $urlPrefix = 'digitalapi.auspost.com.au';
            $sizeEndpoint = 'https://' . $urlPrefix . '/postage/parcel/domestic/size.json';
            $sizeResponse = $client->get($sizeEndpoint, [
                'headers' => [
                    'AUTH-KEY' => $apiKey,
                ],
            ]);
            $sizeData = json_decode($sizeResponse->getBody(), true);
            // Choose a parcel size (e.g., 'AUS_PARCEL_TYPE_BOXED_BC')
            $selectedParcelSize = 'AUS_PARCEL_TYPE_BOXED_BM';
            $parcelDimensions = '';
            foreach ($sizeData['sizes']['size'] as $size) {
                if ($size['code'] === $selectedParcelSize) {
                    $parcelDimensions = $size['value'];
                    break;
                }
            }
            $weightSum = 0;
            foreach ($cartData as $product) {
                $pid = $product['pId'];
                $quantity = $product['quantity'];
                $weight = DB::table('products_meta')
                    ->where('product_id', $pid)
                    ->where('option', '_weight')
                    ->select('value')
                    ->first();
                if ($weight) {
                    $weightValue = preg_replace('/[^0-9.]/', '', $weight->value);
                    $weightSum += (float)$weightValue * $quantity;
                }
            }
            $serviceCode = 'AUS_PARCEL_REGULAR';
            // Extract dimensions (length, width, height) from the value
            list($length, $width, $height) = explode('x', $parcelDimensions);
            // Make a request to calculate postage with the selected size and dimensions
            $calculateEndpoint = 'https://' . $urlPrefix . '/postage/parcel/domestic/calculate.json';
            $response = $client->get($calculateEndpoint, [
                'query' => [
                    'from_postcode' => $deliverypost->value,
                    'to_postcode' =>  $shipping_address->post_code,
                    'length' => 0,
                    'width' => 0,
                    'height' => 0,
                    'weight' => $weightSum,
                    'service_code' => $serviceCode,
                ],
                'headers' => [
                    'AUTH-KEY' => $apiKey,
                ],
            ]);


            $postageData = json_decode($response->getBody(), true);
            $totalCost = $postageData['postage_result']['total_cost'];
            $calculatedAmount = $totalAmount + (float)$totalCost;
            if ($auth_discount) {
                $calculatedAmount = $calculatedAmount - floatval($auth_discount);
            }
        } else {
            $calculatedAmount = $totalAmount;
            if ($auth_discount) {
                $calculatedAmount = floatval($calculatedAmount) - floatval($auth_discount);
            }
        }
        //Postage api

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setCurrency('AUD')
            ->setTotal($calculatedAmount);

        $transaction = new Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.success'))
            ->setCancelUrl(route('paypal.cancel'));

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($this->apiContext);
            return response()->json(['status' => true, 'approval_link' => $payment->getApprovalLink()]);
        } catch (\PayPal\Exception\PayPalConnectionException $exp) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection TImeout');
                return response()->json(['status' => false, 'error' => 'Connection Timeout! Try Again.']);
            } else {
                \Session::put('error', 'Something went wrong please try again.');
                return response()->json(['status' => false, 'error' => 'Something went wrong. Try Again.']);
            }
        }
    }

    public function handlePaymentResponse(Request $request)
    {
        $paymentId = $request->paymentId;
        $payerId = $request->PayerID;

        $payment = Payment::get($paymentId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        // Execute the payment
        try {
            $result = $payment->execute($execution, $this->apiContext);
        } catch (\PayPal\Exception\PayPalConnectionException $exp) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection Timeout');
                return redirect()->route('Website.CheckOut')->withErrors('Something went wrong please try again');
            } else {
                \Session::put('error', 'Something went wrong please try again.');
                return redirect()->route('Website.CheckOut')->withErrors('Something went wrong please try again');
            }
        }
        if ($result->getState() === 'approved') {
            $orderDetails = $request->session()->get('shipping_address');
            $billingDetails = $request->session()->get('billing_address');
            $totalAmount = $request->session()->get('totalAmount');
            $cartData = $request->session()->get('cartData');
            $subTotal = $request->session()->get('subTotal');
            $totalGst = $request->session()->get('totalGst');
            $discountedAmount = $request->session()->get('discountedAmount');
            $discountPlan = $request->session()->get('discountPlan');
            $authDisount = $request->session()->get('authDisount');
            $delivery_fee = $request->session()->get('delivery_fee');
            $authDiscountPer = $request->session()->get('authDiscountPer');
            $email = $orderDetails->email;

            $order = new order();
            $order->user_id = Auth::id() ?? 0;
            $order->f_name = $orderDetails->f_name;
            $order->l_name = $orderDetails->l_name;
            $order->email = $orderDetails->email;
            $order->company = $orderDetails->company;
            $order->phone = $orderDetails->phone;
            $order->street_address = $orderDetails->street_address;
            $order->post_code = $orderDetails->post_code;
            $order->suburb = $orderDetails->suburb;
            $order->state = $orderDetails->state;
            $order->city = $orderDetails->city;
            $order->subTotal = round($subTotal, 2) - round($totalGst, 2);
            $order->totalGst = $totalGst;
            $order->status = 'pending';
            $order->total_amount = $totalAmount;
            $order->auth_discount_per = $authDiscountPer;
            $order->auth_discount = $authDisount;
            $order->grand_total = (round($totalAmount, 2) - round($authDisount, 2)) + round($delivery_fee, 2);
            $order->discountedAmount = $discountedAmount ?? 0;
            $order->discountPlan = $discountPlan ?? null;
            $order->delivery_charges = $delivery_fee;
            $order->payment_status = 'paid';
            $order->channel = 'Online store';
            $order->payment_method = 'Paypal';
            if (Auth::user()) {
                $order->user_type = 'authenticated';
            } else {
                $order->user_type = 'guest';
            }
            $order->save();

            if ($billingDetails !== null) {
                $orderAddress = new OrderAddressDetails();
                $orderAddress = new OrderAddressDetails();
                $orderAddress->order_id = $order->id;
                $orderAddress->default_billing_id = $billingDetails->id ?? null;
                $orderAddress->f_name = $billingDetails->f_name ?? null;
                $orderAddress->l_name = $billingDetails->l_name ?? null;
                $orderAddress->email = $billingDetails->email ?? null;
                $orderAddress->company = $billingDetails->company ?? null;
                $orderAddress->phone = $billingDetails->phone ?? null;
                $orderAddress->street_address = $billingDetails->street_address ?? null;
                $orderAddress->post_code = $billingDetails->post_code ?? null;
                $orderAddress->suburb = $billingDetails->suburb ?? null;
                $orderAddress->state = $billingDetails->state ?? null;
                $orderAddress->city = $billingDetails->city ?? null;
                $orderAddress->address_type = 'billing_address';
                $orderAddress->save();

                $email = $billingDetails->email;
            }

            $OrderMeta = [];
            foreach ($cartData as $data) {
                $OrderMeta[] = [
                    'order_id' => $order->id,
                    'product_id' => $data['pId'],
                    'product_title' => $data['title'],
                    'quantity' => $data['quantity'],
                    'price' => $data['amount'],
                    'qp_subtotal' => $data['amount'] * $data['quantity'],
                    'payment_intent_id' => $result->id,
                ];
                $stockMeta = ProductMeta::where('product_id', $data['pId'])
                    ->where('option', '_stock')
                    ->first();

                $stockvalue = $stockMeta->value - $data['quantity'];
                $stockMeta->update(['value' => $stockvalue]);
            }
            DB::table('order_metas')->insert($OrderMeta);

            $orderAddress = order::where('id', $order->id)->with('orderAddressDetail')->first();
            $grandTotal = (floatval($totalAmount) - floatval($authDisount)) + $delivery_fee;
            
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
                'subject' => 'Order Confirmation | ' . config('app.website_name'),
                'to' => $email,
                'blade' => 'stripe-payment',
                'data' => [
                    'name' => $orderDetails->f_name . " " . $orderDetails->l_name,
                    'products' => $cartData,
                    'orderAddress' => $orderAddress,
                    'totalAmount' => $totalAmount,
                    'auth_discount' => $authDisount,
                    'delivery_fee' => $delivery_fee,
                    'grand_total' => $grandTotal,
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

            Session::forget('shipping_address');
            Session::forget('cartData');
            Session::forget('totalAmount');
            Session::forget('total_items');
            Session::forget('subTotal');
            Session::forget('totalGst');
            Session::forget('total_items');
            Session::forget('totalProducts');
            Session::forget('totalProducts');
            Session::forget('authDiscountPer');
            Session::forget('authDisount');

            return redirect()->route('Website.ThankYou_Page');
        } else {
            return redirect()->route('Website.CheckOut')->withErrors('Your Payment is rejected by Paypal. Try Again.');
        }
    }

    public function cancel(Request $request)
    {
        return redirect()->route('Website.CheckOut')->withErrors('Something went wrong! Please check your details and try again.');
    }
    
    // Gift Card Buying Module Starts
    public function gift_card_order(Request $request)
    {
        $validation = $request->validate([
            'shipping_address.f_name' => 'required|string',
            'shipping_address.l_name' => 'required|string',
            'shipping_address.email' => 'required|email',
            'shipping_address.phone' => 'required|max:15',
            'shipping_address.street_address' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.post_code' => 'required|numeric',
            'shipping_address.suburb' => 'required|string',
            'shipping_address.state' => 'required|string',
        ]);
        if ($validation) {
            $card = GiftCard::where('id', $request->gift_card_id)->first();
            if ($card->status == 'active') {
                $data = [
                    'f_name' => $request->shipping_address['f_name'],
                    'l_name' => $request->shipping_address['l_name'],
                    'email' => $request->shipping_address['email'],
                    'company' => $request->shipping_address['company'],
                    'street_address' => $request->shipping_address['street_address'],
                    'suburb' => $request->shipping_address['suburb'],
                    'city' => $request->shipping_address['city'],
                    'state' => $request->shipping_address['state'],
                    'post_code' => $request->shipping_address['post_code'],
                    'phone' => $request->shipping_address['phone'],
                    'card_id' => $card->id
                ];
                $jsonString = json_encode($data);
                $myObject = json_decode($jsonString);
                session(['shipping_address' => $myObject]);

                $payer = new Payer();
                $payer->setPaymentMethod('paypal');

                $amount = new Amount();
                $amount->setCurrency('AUD')
                    ->setTotal($card->price);

                $transaction = new Transaction();
                $transaction->setAmount($amount);

                $redirectUrls = new RedirectUrls();
                $redirectUrls->setReturnUrl(route('GiftCard.Payment.Success'))
                    ->setCancelUrl(route('GiftCard.Payment.Cancel'));

                $payment = new Payment();
                $payment->setIntent('sale')
                    ->setPayer($payer)
                    ->setTransactions([$transaction])
                    ->setRedirectUrls($redirectUrls);

                try {
                    $payment->create($this->apiContext);
                    return response()->json(['status' => true, 'approval_link' => $payment->getApprovalLink()]);
                } catch (\PayPal\Exception\PayPalConnectionException $exp) {
                    if (\Config::get('app.debug')) {
                        \Session::put('error', 'Connection TImeout');
                        return response()->json(['status' => false, 'error' => 'Connection Timeout! Try Again.']);
                    } else {
                        \Session::put('error', 'Something went wrong please try again.');
                        return response()->json(['status' => false, 'error' => 'Something went wrong. Try Again.']);
                    }
                }
            } else {
                return response()->json(['error', 'Sorry the card you are looking for is Expired or Deleted.']);
            }
        } else {
            return response()->json(['error', $validation]);
        }
    }

    public function handle_giftCard_payment_response(Request $request)
    {
        $paymentId = $request->paymentId;
        $payerId = $request->PayerID;

        $payment = Payment::get($paymentId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            $result = $payment->execute($execution, $this->apiContext);
        } catch (\PayPal\Exception\PayPalConnectionException $exp) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection Timeout');
                return redirect()->route('Website.CheckOut')->withErrors('Something went wrong please try again');
            } else {
                \Session::put('error', 'Something went wrong please try again.');
                return redirect()->route('Website.CheckOut')->withErrors('Something went wrong please try again');
            }
        }
        if ($result->getState() === 'approved') {
            $uniqueCouponCode = StaticFunctionsClass::generateUniqueCouponCode();
            $orderDetails = $request->session()->get('shipping_address');
            $card = GiftCard::where('id', $orderDetails->card_id)->first();
            $email = $orderDetails->email;

            $daysToAdd = $card->expire_after_weeks * 7;
            $currentDate = Carbon::now();
            $newDate = StaticFunctionsClass::gift_card_expiry($currentDate, $daysToAdd);

            $order = new GiftCardPurchases();
            $order->user_id = Auth::id() ?? 0;
            $order->card_id = $orderDetails->card_id;
            $order->coupon_code = $uniqueCouponCode;
            $order->customer_name = $orderDetails->f_name . " " . $orderDetails->l_name;
            $order->customer_email = $orderDetails->email;
            $order->customer_phone = $orderDetails->phone;
            $order->business_name = $orderDetails->company;
            $order->street_address = $orderDetails->street_address;
            $order->suburb = $orderDetails->suburb;
            $order->city = $orderDetails->city;
            $order->state = $orderDetails->state;
            $order->post_code = $orderDetails->post_code;
            $order->payment_status = 'paid';
            $order->payment_method = 'Paypal';
            $order->payment_intent = $result->id;
            $order->channel = 'Online store';
            $order->total_amount = $card->price;
            $order->purchasing_date = Carbon::now();
            $order->remaining_amount = $card->price;
            $order->expiry_date = $newDate;
            $order->is_used = '0';
            $order->is_expired = '0';
            $order->used_times = 0;
            if (Auth::user()) {
                $order->customer_type = 'authenticated';
            } else {
                $order->customer_type = 'guest';
            }
            $order->save();

            $details = GiftCardPurchases::where('id', $order->id)->first();

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
                'subject' => 'Gift Card Order Confirmation | ' . config('app.website_name'),
                'to' => $email,
                'blade' => 'gift-card-payment',
                'data' => [
                    'name' => $order->customer_name,
                    'card' => $card,
                    'details' => $details,
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

            return redirect()->route('Website.ThankYou_Page');
        } else {
            return redirect()->route('Website.CheckOut')->withErrors('Your Payment is rejected by Paypal. Try Again.');
        }
    }
    public function gift_pay_cancel(Request $request)
    {
        return redirect()->route('Website.GeneralPages.gift-card-checkout')->withErrors('Something went wrong! Please check your details and try again.');
    }
}
