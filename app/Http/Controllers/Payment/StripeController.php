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
use App\Models\OrderAddressDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\PaymentMethod;
use GuzzleHttp\Client;
use DB;

class StripeController extends Controller
{
    public function stripe_payment_method(Request $request)
    {
        try {
            $delivery_fee = $request->deliveryFee ?? 0;
            if ($request->selected_address != 0 && is_numeric($request->selected_address)) {
                $validated_data = $request->validate([
                    'selected_address' => 'required',
                    'card_number' => 'required',
                    'cvc' => 'required',
                    'card_expiry_year' => 'required',
                ]);
                if ($validated_data) {
                    $selected_address = UserAddress::where('id', $request->selected_address)->first();
                    session(['selectedAddress' => $selected_address]);
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
                    return response()->json(['error', $validated_data]);
                }
            } else {
                $validated_data = $request->validate([
                    'selected_address.f_name' => 'required|string',
                    'selected_address.l_name' => 'required|string',
                    'selected_address.email' => 'required|email',
                    'selected_address.phone' => 'required|max:15',
                    'selected_address.street_address' => 'required|string',
                    'selected_address.city' => 'required|string',
                    'selected_address.suburb' => 'required|string',
                    'selected_address.state' => 'required|string',
                    'selected_address.post_code' => 'required',
                    'card_number' => 'required',
                    'cvc' => 'required',
                    'card_expiry_year' => 'required',
                ]);
                if ($validated_data) {
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
                    $selectedAddress = [
                        'f_name' => $request->selected_address['f_name'],
                        'l_name' => $request->selected_address['l_name'],
                        'email' => $request->selected_address['email'],
                        'company' => $request->selected_address['company'],
                        'street_address' => $request->selected_address['street_address'],
                        'suburb' => $request->selected_address['suburb'],
                        'city' => $request->selected_address['city'],
                        'state' => $request->selected_address['state'],
                        'post_code' => $request->selected_address['post_code'],
                        'phone' => $request->selected_address['phone'],
                    ];
                    $jsonStringSelected = json_encode($selectedAddress);
                    $myObjectSelected = json_decode($jsonStringSelected);
                    session(['selectedAddress' => $myObjectSelected]);
                } else {
                    return response()->json(['error', $validated_data]);
                }
            }

            $cartData = session('cartData', []);
            $totalAmount = session('totalAmount', 0);
            $totalItems = session('total_items', 0);
            $subTotal = session('subTotal', 0);
            $totalGst = session('totalGst', 0);
            $auth_discount = session('authDisount', 0);
            $authDiscountPer = $request->session()->get('authDiscountPer');

            $calculatedAmount = 0;
            $subTotalWithoutGST = floatval($subTotal) - floatval($totalGst);
            $email = $request->session()->get('selectedAddress');
            if ($request->session()->get('billing_address') != null) {
                $email = $request->session()->get('billing_address');
            }

            if ($request->discountPlanId) {
                $plan = MembershipPlan::where('id', $request->discountPlanId)->first();
                $discount = floatval($subTotalWithoutGST) * (floatval($plan->discount_percentage) / 100);
                $discountedAmount = floatval($subTotalWithoutGST) - floatval($discount);
                $calculatedAmount = floatval($discountedAmount) + floatval($totalGst);
                $discountedAmount = $calculatedAmount;
                $discountPlan = $plan->plan_name;
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
                        'to_postcode' => $request->selected_address['post_code'] ?? $selected_address->post_code,
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
                    $calculatedAmount = $calculatedAmount - floatval($auth_discount);
                }
            }
            //Postage api

            $activeStripeEnvironment = GeneralSettings::where('option', '_active_stripe_environment')->first();
            if ($activeStripeEnvironment) {
                $stripeLiveKey = GeneralSettings::where('option', '_stripe_live_key')->first();
                $stripeSandboxkey = GeneralSettings::where('option', '_stripe_sandbox_key')->first();
                if ($activeStripeEnvironment->value === "live" && $stripeLiveKey) {
                    Stripe::setApiKey($stripeLiveKey->value);
                    $year_month = explode('-', $validated_data['card_expiry_year']);
                    $card_number = $validated_data['card_number'];
                    $exp_month = $year_month[1];
                    $exp_year = $year_month[0];
                    $cvc = $validated_data['cvc'];

                    $paymentMethod = PaymentMethod::create([
                        'type' => 'card',
                        'card' => [
                            'number' => $card_number,
                            'exp_month' => $exp_month,
                            'exp_year' => $exp_year,
                            'cvc' =>  $cvc,
                        ],
                    ]);

                    $paymentMethodId = $paymentMethod->id;

                    $intent = PaymentIntent::create([
                        'amount' => $totalAmount * 100,
                        'currency' => 'USD',
                        'description' => 'Sydney Quality Meats Payment',
                        'payment_method' => $paymentMethodId,
                        'confirm' => true,
                        'receipt_email' => $email->email,
                        'return_url' => 'https://example.com/success',
                    ]);
                } elseif ($activeStripeEnvironment->value === "sandbox" && $stripeSandboxkey) {
                    Stripe::setApiKey($stripeSandboxkey->value);
                } else {
                    Stripe::setApiKey('sk_test_51MloaOCEOflnZDwq9gOeusGZkLMfen0irIipH9FEaubo7GJqb7RBLUGyKJB7cBFN5ltSTMgdtXylixnV2YNBm9gN00NPCJie21');
                }
            } else {
                Stripe::setApiKey('sk_test_51MloaOCEOflnZDwq9gOeusGZkLMfen0irIipH9FEaubo7GJqb7RBLUGyKJB7cBFN5ltSTMgdtXylixnV2YNBm9gN00NPCJie21');
            }

            $intent = PaymentIntent::create([
                'amount' => $calculatedAmount * 100,
                'currency' => 'USD',
                'description' => 'Sydney Quality Meats Payment',
                'payment_method' => 'pm_card_visa',
                'confirm' => true,
                'receipt_email' => $email->email,
                'return_url' => 'https://example.com/success',
            ]);

            $paymentIntentId = $intent->id;
            $orderDetails = $request->session()->get('selectedAddress');
            $billingDetails = $request->session()->get('billing_address');
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
            $order->street_address = $orderDetails->street_address;
            $order->subTotal = round($subTotal, 2) - round($totalGst, 2);
            $order->totalGst = $totalGst;
            $order->status = 'pending';
            $order->total_amount = $totalAmount;
            $order->auth_discount_per = $authDiscountPer;
            $order->auth_discount = $auth_discount;
            $order->grand_total = $calculatedAmount;
            $order->discountedAmount = $discountedAmount ?? 0;
            $order->discountPlan = $discountPlan ?? null;
            $order->delivery_charges = $delivery_fee;
            $order->payment_status = 'paid';
            $order->channel = 'Online store';
            $order->payment_method = 'Stripe';
            if (Auth::user()) {
                $order->user_type = 'authenticated';
            } else {
                $order->user_type = 'guest';
            }
            $order->save();

            if ($billingDetails !== null) {
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
            }

            foreach ($cartData as $data) {
                $subtotal = $data['amount'] * $data['quantity'];
                order_meta::create([
                    'order_id' => $order->id,
                    'product_id' => $data['pId'],
                    'product_title' => $data['title'],
                    'quantity' => $data['quantity'],
                    'price' => $data['amount'],
                    'qp_subtotal' => $subtotal,
                    'payment_intent_id' => $paymentIntentId,
                ]);
                $stockMeta = ProductMeta::where('product_id', $data['pId'])
                    ->where('option', '_stock')
                    ->first();

                $stockvalue = $stockMeta->value - $data['quantity'];
                $stockMeta->update(['value' => $stockvalue]);
            }
            $orderAddress = order::where('id', $order->id)->with('orderAddressDetail')->first();
            $grandTotal = floatval($totalAmount) - floatval($auth_discount);
            
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
                'to' => $email->email,
                'blade' => 'stripe-payment',
                'data' => [
                    'name' => $orderDetails->f_name . " " . $orderDetails->l_name,
                    'products' => $cartData,
                    'orderAddress' => $orderAddress,
                    'totalAmount' => $totalAmount,
                    'auth_discount' => $auth_discount,
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
            Session::forget('billing_address');
            Session::forget('cartData');
            Session::forget('totalAmount');
            Session::forget('total_items');
            Session::forget('subTotal');
            Session::forget('totalGst');
            Session::forget('total_items');
            Session::forget('totalProducts');
            Session::forget('authDiscountPer');
            Session::forget('authDisount');

            if ($intent->status === 'requires_action') {

                return response()->json([
                    'success' => false,
                    'message' => 'Payment requires further authentication.',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully.',
                'order_id' => $order->id,
                'payment_intent_id' => $paymentIntentId,
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing order: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());

            // Return a more detailed error response
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the order: ' . $e->getMessage(),
            ]);
        }
    }

    public function calculatePostage(Request $request)
    {
        $deliverySetting = GeneralSettings::where('option', '_delivery_active')->first();
        if ($deliverySetting && $deliverySetting->value == 1) {

            $apiKey = '862c8642-bc0a-4498-a4ca-db8abac4f73d';
            $client = new \GuzzleHttp\Client();

            $urlPrefix = 'digitalapi.auspost.com.au';
            $sizeEndpoint = 'https://' . $urlPrefix . '/postage/parcel/domestic/size.json';

            $postcode = $request->input('postcode');
            $weight = $request->input('weight');

            if ($weight > 22) {
                return response()->json(['error' => 'The weight of the parcel exceeds the maximum limit of 22 kg.']);
            }

            // Make a request to get parcel size information
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
            $serviceCode = 'AUS_PARCEL_REGULAR';
            // Extract dimensions (length, width, height) from the value
            list($length, $width, $height) = explode('x', $parcelDimensions);

            $cartData = session('cartData', []);
            $totalAmount = session('totalAmount', 0);
            $totalItems = session('total_items', 0);
            $subTotal = session('subTotal', 0);
            $totalGst = session('totalGst', 0);
            $auth_discount = session('authDisount', 0);

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
            $calculateEndpoint = 'https://' . $urlPrefix . '/postage/parcel/domestic/calculate.json';
            $response = $client->get($calculateEndpoint, [
                'query' => [
                    'from_postcode' => '2000',
                    'to_postcode' => $request->post_code,
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
            // return response()->json(['postage_cost' => $postageData]);
            if ($auth_discount) {
                $calculatedAmount = $calculatedAmount - floatval($auth_discount);
            }
            $responseData = [
                'postage_cost' => $postageData,
                'calculatedAmount' => $calculatedAmount,
            ];

            return response()->json($responseData);
        } else {
            return response()->json(['res' => 0]);
        }
    }
    
    
    //  Gift Card Purchase Starts
    public function gift_card_payment(Request $request)
    {
        try {
            $validated_data = $request->validate([
                'card_number' => 'required',
                'cvc' => 'required',
                'card_expiry_year' => 'required',
                'shipping_address.f_name' => 'required|string',
                'shipping_address.l_name' => 'required|string',
                'shipping_address.email' => 'required|email',
                'shipping_address.phone' => 'required|max:15',
                'shipping_address.street_address' => 'required|string',
                'shipping_address.city' => 'required|string',
                'shipping_address.post_code' => 'required|numeric',
                'shipping_address.suburb' => 'required|string',
                'shipping_address.state' => 'required|string',
                'gift_card_id' => 'required|numeric'
            ]);
            if ($validated_data) {
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
                    'card_id' => $request->gift_card_id
                ];
                $jsonString = json_encode($data);
                $myObject = json_decode($jsonString);
                session(['selectedAddress' => $myObject]);
            }
            $card = GiftCard::where('id', $request->gift_card_id)->first();
            $email = $request->shipping_address['email'];
            $activeStripeEnvironment = GeneralSettings::where('option', '_active_stripe_environment')->first();
            if ($activeStripeEnvironment) {
                $stripeLiveKey = GeneralSettings::where('option', '_stripe_live_key')->first();
                $stripeSandboxkey = GeneralSettings::where('option', '_stripe_sandbox_key')->first();
                if ($activeStripeEnvironment->value === "live" && $stripeLiveKey) {

                    Stripe::setApiKey($stripeLiveKey->value);
                    $year_month = explode('-', $validated_data['card_expiry_year']);
                    $card_number = $validated_data['card_number'];
                    $exp_month = $year_month[1];
                    $exp_year = $year_month[0];
                    $cvc = $validated_data['cvc'];

                    $paymentMethod = PaymentMethod::create([
                        'type' => 'card',
                        'card' => [
                            'number' => $card_number,
                            'exp_month' => $exp_month,
                            'exp_year' => $exp_year,
                            'cvc' =>  $cvc,
                        ],
                    ]);

                    $paymentMethodId = $paymentMethod->id;

                    $intent = PaymentIntent::create([
                        'amount' => $card->price * 100,
                        'currency' => 'USD',
                        'description' => 'Sydney Quality Meats Payment',
                        'payment_method' => $paymentMethodId,
                        'confirm' => true,
                        'receipt_email' => $email,
                        'return_url' => 'https://example.com/success',
                    ]);
                } elseif ($activeStripeEnvironment->value === "sandbox" && $stripeSandboxkey) {
                    Stripe::setApiKey($stripeSandboxkey->value);
                } else {
                    Stripe::setApiKey('sk_test_51MloaOCEOflnZDwq9gOeusGZkLMfen0irIipH9FEaubo7GJqb7RBLUGyKJB7cBFN5ltSTMgdtXylixnV2YNBm9gN00NPCJie21');
                }
            } else {
                Stripe::setApiKey('sk_test_51MloaOCEOflnZDwq9gOeusGZkLMfen0irIipH9FEaubo7GJqb7RBLUGyKJB7cBFN5ltSTMgdtXylixnV2YNBm9gN00NPCJie21');
            }

            $intent = PaymentIntent::create([
                'amount' => $card->price * 100,
                'currency' => 'USD',
                'description' => 'Sydney Quality Meats Payment',
                'payment_method' => 'pm_card_visa',
                'confirm' => true,
                'receipt_email' => $email,
                'return_url' => 'https://example.com/success',
            ]);

            $paymentIntentId = $intent->id;
            $orderDetails = $request->session()->get('selectedAddress');
            $uniqueCouponCode = StaticFunctionsClass::generateUniqueCouponCode();
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
            $order->payment_method = 'Stripe';
            $order->payment_intent = $paymentIntentId;
            $order->channel = 'Online store';
            $order->total_amount = $card->price;
            $order->purchasing_date = $currentDate;
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

            if ($intent->status === 'requires_action') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment requires further authentication.',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully.',
                'order_id' => $order->id,
                'payment_intent_id' => $paymentIntentId,
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing order: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the order: ' . $e->getMessage(),
            ]);
        }
    }
}
