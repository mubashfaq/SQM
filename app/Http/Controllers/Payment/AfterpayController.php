<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\GeneralSettings;
use App\Models\OrderAddressDetails;
use App\Models\MembershipPlan;
use DB;
use GuzzleHttp\Client;

class AfterpayController extends Controller
{
    public function initiatePayment(Request $request)
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


      
        
        // $payment_data = [
        //     'token' => '005.2fkv5o963b132z8ppa34srlh60987y87d55re237wsg9tr4q2',
        //     'expires' => '2023-08-29T00:00:00.000Z',
        //     'redirectCheckoutUrl' => 'https://global-api.afterpay.com/us/checkout/?token=005.2fkv5o963b132z8ppa34srlh60987y87d55re237wsg9tr4q2',
        //     'amount' => [
        //         'amount' => '100.00',
        //         'currency' => 'AUD',
        //     ],
        //     'consumer' => [
        //         'email' => 'test@example.com',
        //         'givenNames' => 'Joe',
        //         'surname' => 'Consumer',
        //         'phoneNumber' => '0400 000 000',
        //     ],

        // ]; 
        // // Initialize cURL session
        // $ch = curl_init($endpoint);
        
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment_data));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     'Content-Type: application/json',
        //     'Authorization: Basic ' . base64_encode($api_secret),
        // ]);
        
        // $response = curl_exec($ch);
        // // Check for errors or process the response as needed
        // if ($response === false) {
        //     echo 'cURL Error: ' . curl_error($ch);
        // } else {
        //     $response_data = json_decode($response, true);
        //     print_r($response_data);
        // }
        
        // // Close cURL session
        // curl_close($ch);
        
        
        
        
        $api_secret = 'ae6394b3889febbf05ae8da981a7a9351abdbd00525173536555d62086d89f8e243c7d2a646aa88ab7cf0ed4974e41cd33ddbcbb0279f9b4dec5c4977b016900';
        $merchant_id = '642138';

        // $base_url = 'https://global-api.afterpay.com/payments';
        $base_url = 'https://global-api.afterpay.com/v2';
       
        // Set the payment details
        $payment_data = [
            'amount' =>  $calculatedAmount,
            'currency' => 'AUD',
            'description' => 'Sydney Quality Meats Payment',
            'payment_method' => 'afterpay',
            'confirm' => true,
            'merchant_id' => $merchant_id,
            'redirectUrl' => 'https://example.com/afterpay/callback',
        ];
        $client = new Client();
        $response = $client->post($base_url, [
            'json' => $payment_data,
            'headers' => [
            'Authorization' => 'Basic ' . base64_encode($api_secret),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            ],
        ]);

        $response_data = json_decode($response->getBody(), true);
             
        if ($response->getStatusCode() == 201) {
            // Payment request was successful
            $payment_url = $response_data['paymentUrl'];
            return redirect()->away($payment_url);
        } else {
            // Handle errors
            return response('Payment request failed. Error: ' . $response_data['error'], 400);
        }
        
    }
}
