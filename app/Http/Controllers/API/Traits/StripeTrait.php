<?php

namespace App\Http\Controllers\API\Traits;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use App\Models\GeneralSettings;
use Stripe\Exception\CardException;
use App\Http\Controllers\Website\Traits\EmailTrait;


trait StripeTrait
{
    use JsonResponseTrait ,EmailTrait ;

    public function initializeStripe()
    {
        $stripeSecret = GeneralSettings::where('option', '_stripe_secrit_key')->first();
        if (!$stripeSecret) {
            return  $this->error('Stripe API key not found in configuration.', 422);
        }
        $activeStripeEnvironment = GeneralSettings::where('option', '_active_stripe_environment')->first();
        if (!$activeStripeEnvironment) {
            return $this->error('Stripe environment setting not found in configuration.', 422);
        }
        $stripeApiKey = $activeStripeEnvironment->value === 'sandbox' ? '_stripe_sandbox_key' : '_strsipe_live_key';

        Stripe::setApiKey($stripeSecret->value);
    }

    public function createStripeCustomer($email, $name)
    {
        return Customer::create([
            'email' => $email,
            'name' => $name,
        ]);
    }

    public function createStripePaymentMethod($cardDetails, $billingDetails)
    {
        return PaymentMethod::create([
            'type' => 'card',
            'card' => [
                'number' => $cardDetails['number'],
                'exp_month' => $cardDetails['exp_month'],
                'exp_year' => $cardDetails['exp_year'],
                'cvc' => $cardDetails['cvc'],
            ],
            'billing_details' => [
                'email' => $billingDetails['email'],
                'name' => $billingDetails['name'],
            ],
        ]);
    }

    public function attachPaymentMethodToCustomer($paymentMethodId, $customerId)
    {
        try {
            $paymentMethod = PaymentMethod::retrieve($paymentMethodId);

            if ($paymentMethod->customer && $paymentMethod->customer !== $customerId) {
                \Log::error('Payment method already attached to another customer: ' . $paymentMethod->customer);
                return response()->json(['message' => 'Payment method already attached to another customer'], 400);
            }

            $paymentMethod->attach(['customer' => $customerId]);

            \Log::info('Payment method successfully attached to customer: ' . $customerId);
            return $paymentMethod;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to process payment method. Please try again.'], 400);
        } catch (\Exception $e) {
            \Log::error('General Error: ' . $e->getMessage());
            return response()->json(['message' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }

    public function createPaymentIntent($amount, $currency, $customerId, $paymentMethodId, $metadata = [])
    {
        return PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'customer' => $customerId,
            'payment_method' => $paymentMethodId,
            'off_session' => true,
            'confirm' => true,
            'metadata' => $metadata,
        ]);
    }
    public function createCustomerAndPaymentMethod($request, $validatorData)
    {
        $customer = $this->createStripeCustomer(
            $validatorData['email'],
            $validatorData['f_name'] . ' ' . $validatorData['l_name']
        );

        $paymentMethodId = $request->input('stripePaymentMethodId');
        if (!$paymentMethodId) {
            return $this->error('Payment method ID is required', 400);
        }

        try {
            $paymentMethod = $this->attachPaymentMethodToCustomer($paymentMethodId, $customer->id);
            if ($paymentMethod instanceof \Illuminate\Http\JsonResponse) {
                return $paymentMethod;
            }

            return [$customer, $paymentMethod];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe API Error: ' . $e->getMessage());
            return $this->error('Failed to process payment method. Please try again.', 400);
        } catch (\Exception $e) {
            \Log::error('General Error: ' . $e->getMessage());
            return $this->error('An unexpected error occurred. Please try again.', 500);
        }
    }

    private function handleValidationFailure($validator)
    {
        $errors = $validator->errors()->toArray();
        $message = reset($errors)[0];
        return $this->error($message, 422);
    }

    private function handleSelectedAddress($request, &$validatorData)
    {
        if ($request->has('selected_address')) {
            $selectedAddress = $request->input('selected_address')[0];
            $validatorData = array_merge($validatorData, $selectedAddress);
        }
    }

    private function processStripePayment($request, $validatorData)
    {
        try {
            $this->initializeStripe();
            $result = $this->createCustomerAndPaymentMethod($request, $validatorData);
            if ($result instanceof \Illuminate\Http\JsonResponse) {
                return $result;
            }
            list($customer, $paymentMethod) = $result;
            
            if(auth()->check()){
                $validatorData['user_id'] = auth()->user()->id;
            }
            
            $orderData = array_merge($validatorData, [
                'payment_status'    => 'pending',
                'stripe_payment_method_id' => $paymentMethod->id,
                'stripe_customer_id' => $customer->id,
            ]);
            // Create Order
            // dd($orderData);
            $order = $this->createOrder($orderData);

            if (!$order) {
                return $this->error('Something went wrong while creating the order.');
            }

            // Create Order Meta
            $orderMeta = $this->createOrderMeta($order, $validatorData['products']);

            $order->load('orderMeta.product');

           
            
            $params = [
                'subject' => 'Order confirmation' . ' | ' . env("APP_NAME"),
                'to' => $order->email,
                'template' => 'Admin.Emails.order-conformation',
                'data' => [
                    'order' => $order,
                    'status' => $order->status,
                    'orderMeta' => $orderMeta,
                    'image_url' => $orderMeta,
                ]
            ];
            $emailSent = $this->sendEmail($params);

            // Clear Cart Session
            session()->forget('cartData');
            session()->forget('totalAmount');
            session()->forget('totalProducts');
            session()->forget('totalGst');
            session()->forget('subTotal');
            session()->flash('success', 'Order created successfully (payment pending)');


            $meta_details = [
                'title' => 'Thanks, Order Placed | Sydney Quality Meat',
                'keywords' => ' Sydney Quality Meat',
                'description' => ' Sydney Quality Meat.',
                'author' => 'Sydney Quality Meat Org',
            ];

            return redirect()->route('thankyou', ['order' => $order->id]);

        } catch (\Exception $e) {
            return $this->error('Error processing Stripe payment: ' . $e->getMessage(), 500);
        }
    }

    private function processPaypalPayment($request, $validatorData)
    {
        try {
            foreach ($validatorData['products'] as &$product) {
                $product['payment_intent_id'] = $request->payment_intent_id;
            }
            $orderData = array_merge($validatorData, ['payment_status' => $request->payment_status]);
            $order = $this->createOrder($orderData);
            $orderMeta = $this->createOrderMeta($order, $validatorData['products']);
            if (!$order && !$orderMeta) {
                return $this->error('Something went wrong while creating the order or order details.');
            }
            $this->orderConformationEmail($order);
            return $this->message('order created successfully');
        } catch (\Exception $e) {
            return $this->error('Error processing PayPal payment: ' . $e->getMessage(), 500);
        }
    }

    public function getEncryptedPayPalKeys()
    {
        $liveKey = GeneralSettings::where('option', '_paypal_live_key')->value('value');
        $sandboxKey = GeneralSettings::where('option', '_paypal_sandbox_key')->value('value');
        if ($liveKey) {
            return response()->json([
                'key' => base64_encode($liveKey),
            ]);
        } else {
            return response()->json([
                'key' => base64_encode($sandboxKey),
            ]);
        }
    }
}
