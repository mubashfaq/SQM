<?php

namespace App\Http\Controllers\Website\ClassModules;

use DB;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductMeta;
use App\General\ProductClass;

trait GeneralPagesTrait {

# Merge Summery

    public function checkPostcode(Request $request)
    {
        $postCode = $request->input('post_code');
        $productId = $request->input('product_id');

        $product = Product::find($productId);

        if ($product->area_group_id == "0") {
            return response()->json(['area_group_exit' => true]);
        }

        if ($product && $product->area_group_id) {
            $areaGroupIds = explode(',', $product->area_group_id);

            if (in_array($postCode, $areaGroupIds)) {
                return response()->json(['area_group_exit' => true]);
            }
        }

        return response()->json(['area_group_exit' => false]);
    }

    public function calculatePrice(Request $request)
    {
        try {
            $postCode = $request->post_code;
            $itemCount = $request->item_count;

            $price = $request->product_price; // Replace with your actual calculation logic
            // $price = ProductClass::getOptionValue('_price', $product->productMeta);

            $totalPrice = $price * $itemCount;

            // Set your Stripe secret key
            Stripe::setApiKey('sk_test_51NI2AWJshOcTSI7SVjGt3SOAmWVGftCmQUNVcgK2qfvwPk2mQWA0o2H1gxR45yMMIe5qSAQkUML7jOioKg1WrEl700bwaQ9VSU');

            // Create a PaymentIntent in Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => $totalPrice * 100, // Stripe requires amount in cents
                'currency' => 'usd', // Replace with your desired currency
            ]);

            // Store the amount and PaymentIntent ID in your stripe_amount_table
            DB::table('payment_intent_id')->insert([
                'amount' => $totalPrice,
                'payment_intent_id' => $paymentIntent->id,
                'postcode' => $postCode,
            ]);

            return response()->json([
                'success' => true,
                'price' => $totalPrice,
                'payment_intent_id' => $paymentIntent->id,
                'postcode' => $postCode,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating PaymentIntent: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while creating PaymentIntent.',
            ]);
        }
    }

    public function updateCart(Request $request)
    {
        $updatedCartData = session('cartData', []);
        $pId = $request->input('pId');
        $title = $request->input('title');
        $amount = $request->input('amount');
        $featuredImage = $request->input('featuredImage');
        $gst = $request->input('gst');
        $pIdToRemove = $request->input('pIdToRemove');
        $quantity = $request->input('quantity');

        if ($pIdToRemove !== null) {
            $updatedCartData = array_values(array_filter($updatedCartData, function ($product) use ($pIdToRemove) {
                return $product['pId'] !== $pIdToRemove;
            }));
        }

        $existingProductIndex = -1;

        if (isset($updatedCartData) && !empty($updatedCartData)) {
            foreach ($updatedCartData as $index => $product) {
                if ($product['pId'] === $pId) {
                    $existingProductIndex = $index;
                    break;
                }
            }
        }

        if ($existingProductIndex !== -1) {
            $updatedCartData[$existingProductIndex]['quantity']++;
        } else {
            $cartData = [
                'pId' => $pId,
                'title' => $title,
                'amount' => $amount,
                'featuredImage' => $featuredImage,
                'quantity' => $quantity,
            ];
            if ($gst !== null) {
                $cartData['gst'] = $gst;
            }

            if ($cartData['pId'] !== null) {
                $updatedCartData[] = $cartData;
            }
        }
        session(['cartData' => $updatedCartData]);

        $totalAmount = 0;
        $totalProducts = count($updatedCartData);

        // Get the percentage value from the environment configuration
        $percentage = env('PERCENTAGE', 0);

        foreach ($updatedCartData as $product) {
            $amount = $product['amount'];
            $gst = isset($product['gst']) ? $product['gst'] : null;

            if ($gst == 1) {
                $amount += ($amount * $percentage / 100);
            }

            $totalAmount += $amount * $product['quantity'];
        }
        session(['totalAmount' => $totalAmount]);
        session(['totalProducts' => $totalProducts]);

        return response()->json(['message' => 'Session data updated.', 'data' => $updatedCartData]);
    }

    public function getCartItemNumbers()
    {
        $cartData = session('cartData', []);
        $total_items = count($cartData);
        return response()->json($total_items);
    }

    public function showCartdata()
    {
        $cartData = session('cartData', []);
        $totalAmount = session('totalAmount', 0); // Retrieve the total amount from the session
        $subTotal = 0;

        if (!empty($cartData)) {
            foreach ($cartData as $product) {
                $amount = $product['amount'];
                $quantity = $product['quantity'];
                $subtotal = $amount * $quantity;

                // Update sub total
                $subTotal += $subtotal;
            }
        }
        $totalIncludingGST = $totalAmount;
        $percentage = env('PERCENTAGE', 0);
        $html = '
        <div class="cart-overflow">
            <div class="cart_product">';

        if (!empty($cartData)) {
            foreach ($cartData as $product) {
                $html .= '<div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="cart-img">';
                if (isset($product['featuredImage'])) {
                    $html .= '<img src="' . $product['featuredImage'] . '" alt="">';
                }
                $html .= '</div>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12">
                    <div class="cart-detail">';
                if (isset($product['title'])) {
                    $html .= '<p class="cart-title">' . $product['title'] . '</p>';
                    $html .= '<p class="d-none cart-pId">' . $product['pId'] . '</p>';
                }
                if (isset($product['amount']) && isset($product['quantity'])) {
                    $subtotal = $product['amount'] * $product['quantity'];
                    $html .= '<p class="cart-amount">$' . $product['amount'] . ' x ' . $product['quantity'] . ' = $' . $subtotal . '</p>';
                }
                $html .= '</div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <span style="cursor: pointer;" class="remove_product_cart">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-x-circle cart-cross-icon" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path
                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </span>
                </div>
            </div>';
            }
        }
        $totalGST = $totalIncludingGST - $subTotal;

        $html .= '</div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="cart-table">
                    <table class="table">
                        <thead>
                            <tr class="cart-table-row">
                                <th> Sub Total:</th>
                                <th class="cart-text-right toatlamount sub_tatol">$' . $subTotal . '</th>
                            </tr>
                            <tr class="">
                                <th> GST:</th>
                                <th class="cart-text-right gstshow">$' . $totalGST . '</th>
                            </tr>
                            <tr class="">
                                <th>Total:</th>
                                <th class="cart-text-right grandtoatl">$' . $totalIncludingGST . '</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="cart-btn">
                    <button class="btn">Proceed to Cart</button>
                </div>
                <div class="cart-checkout-btn">
                    <button class="btn">Proceed to Cart</button>
                </div>
            </div>
        </div>
    </div>
';
        return response()->json(['html' => $html]);

    }
}
