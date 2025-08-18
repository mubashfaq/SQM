<?php

namespace App\Http\Controllers\Website;

use Carbon\Carbon;
use App\Models\GiftCard;
use App\Models\ProductMeta;
use App\Models\UserAddress;
use App\Models\GiftCardUsed;
use Illuminate\Http\Request;
use App\Models\GeneralSettings;
use App\Models\GiftCardPurchases;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\General\GeneralSettingsClass;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function removeFromCart(Request $request)
{
    $cartData = session('cartData', []);
    $pId = $request->input('pIdToRemove');

    // Filter out the product we want to remove
    $cartData = array_filter($cartData, function($item) use ($pId) {
        return $item['pId'] != $pId;
    });

    // Reindex array
    $cartData = array_values($cartData);

    $subTotal      = 0;
    $totalProducts = count($cartData);
    $totalGst      = 0;
    $totalWeight   = 0; // Initialize total weight

    // You can pull this from config if needed (for default GST percentage if needed)
    $defaultPercentage = config('app.PERCENTAGE', 0);
    // GST percentage to be used when applicable (you may set this in config)
    $gstPercentage = config('app.GST_PERCENTAGE', $defaultPercentage);

    // Recalculate the subtotal, GST, and weight for each item in the cart
    foreach ($cartData as &$item) {
        $lineAmount = $item['amount'] * $item['quantity'];
        $subTotal  += $lineAmount;

        // Fetch product GST status from ProductMeta
        $productGstStatus = ProductMeta::where('product_id', $item['pId'])
            ->where('option', '_gst')
            ->first();
        $isGstApplicable = $productGstStatus && $productGstStatus->value == 1;

        // Calculate GST based on whether it is applicable
        if ($isGstApplicable) {
            $item['gst'] = 1;
            $item['productGst'] = ((int)$item['amount'] * (int)$gstPercentage) / 100;
        } else {
            $item['gst'] = 0;
            $item['productGst'] = 0;
        }

        // Accumulate total GST
        $totalGst += ($item['productGst'] * $item['quantity']);

        // Accumulate total weight if available
        if (isset($item['weight'])) {
            $totalWeight += $item['weight'];
        }
    }
    unset($item);  // Good practice to unset references after a loop by reference

    // Calculate the subtotal + GST
    $totalAmount = $subTotal;

    // Retrieve deliveryCharge from config, or wherever you define it
    $deliveryCharge = config('app.DELIVERY_CHARGE', 0);

    // If there are no products left, set the delivery charge and total weight to 0
    if ($totalProducts == 0) {
        $deliveryCharge = 0;
        $totalWeight = 0;
    }

    // If you want totalAmount to include deliveryCharge, do it here:
    $totalAmount += $deliveryCharge;

    // Save all updated values to the session
    session([
        'cartData'      => $cartData,
        'subTotal'      => $subTotal,
        'totalAmount'   => $totalAmount,
        'totalGst'      => $totalGst,
        'totalProducts' => $totalProducts,
        'deliveryCharge'=> $deliveryCharge,
        'totalWeight'   => $totalWeight,
    ]);

    // If AJAX request, return JSON response
    if ($request->ajax()) {
        return response()->json([
            'status'        => true,
            'message'       => 'Item removed!',
            'cartData'      => $cartData,
            'subTotal'      => $subTotal,
            'totalAmount'   => $totalAmount,
            'totalGst'      => $totalGst,
            'totalProducts' => $totalProducts,
            'deliveryCharge'=> $deliveryCharge,
            'totalWeight'   => $totalWeight,
        ]);
    }

    // Otherwise, redirect back
    return redirect()->back();
}


    public function updateWeight(Request $request)
    {
        // Validate request
        $request->validate([
            'pId' => 'required',
            'weight' => 'required|numeric|min:0.001',
        ]);

        $pId = $request->pId;
        $newWeight = $request->weight;
        
        // Get current cart data from session
        $cartData = session('cartData', []);
        
        // Find the product in cart array
        $productIndex = null;
        foreach ($cartData as $index => $item) {
            if ($item['pId'] == $pId) {
                $productIndex = $index;
                break;
            }
        }
        
        // Check if product exists in cart
        if ($productIndex === null) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found in cart.'
            ]);
        }
        
        // Store old weight for price calculation
        $oldWeight = $cartData[$productIndex]['weight'];
        $price = $cartData[$productIndex]['amount'];
        
        // Calculate price per kg
        $pricePerKg = $price / $oldWeight;
        
        // Update weight
        $cartData[$productIndex]['weight'] = $newWeight;
        
        // Update amount based on new weight
        $newAmount = $pricePerKg * $newWeight;
        $cartData[$productIndex]['amount'] = $newAmount;
        
        // Update GST if needed
        if (isset($cartData[$productIndex]['productGst'])) {
            $gstPercentage = $cartData[$productIndex]['productGst'] / ($price / 100);
            $cartData[$productIndex]['productGst'] = ($newAmount / 100) * $gstPercentage;
        }
        
        // Save updated cart back to session
        session(['cartData' => $cartData]);
        
        // Recalculate cart totals
        $subTotal = 0;
        $totalGst = 0;
        $totalProducts = 0;
        
        foreach ($cartData as $item) {
            $subTotal += $item['amount'] * $item['quantity'];
            if (isset($item['productGst'])) {
                $totalGst += $item['productGst'] * $item['quantity'];
            }
            $totalProducts += $item['quantity'];
        }
        
        $totalAmount = $subTotal + $totalGst;
        
        // Update session with new totals
        session([
            'subTotal' => $subTotal,
            'totalGst' => $totalGst,
            'totalAmount' => $totalAmount,
            'totalProducts' => $totalProducts
        ]);
        
        // Return success response with updated data
        return response()->json([
            'status' => true,
            'message' => 'Weight updated successfully',
            'cartData' => $cartData,
            'subTotal' => $subTotal,
            'totalGst' => $totalGst,
            'totalAmount' => $totalAmount,
            'totalProducts' => $totalProducts
        ]);
    }
    


    public function cart_details()
    {
        $percentage = config('app.PERCENTAGE');
        $meta_details = [
            'title' => 'Cart | Sydney Quality Meat',
            'keywords' => 'donate, donation, charity, Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat. Together, we can make a difference in the lives of those in need.',
            'author' => 'Sydney Quality Meat Org',
        ];
        $cartData = $updatedCartData = session('cartData', []);
        $totalAmount = session('totalAmount', 0);
        $totalItems = session('total_items', 0);
        $subTotal = session('subTotal', 0);
        $totalGst = session('totalGst', 0);
        return view('Website.GeneralPages.shopping-cart', get_defined_vars());
    }

    public function checkout_page()
    {
        $couponArray = session()->get('coupon_array');
        if ($couponArray) {
            $totalAmount = $couponArray['old_totalAmount'];
            session(['totalAmount' => $totalAmount]);
        }
        Session::forget('coupon_array');
        
        $meta_details = [
            'title' => 'Checkout | Sydney Quality Meat',
            'keywords' => 'donate, donation, charity, Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat. Together, we can make a difference in the lives of those in need.',
            'author' => 'Sydney Quality Meat Org',
        ];
        $cartData = session('cartData', []);
        $totalAmount = session('totalAmount', 0);
        $totalItems = session('total_items', 0);
        $subTotal = session('subTotal', 0);
        $totalGst = session('totalGst', 0);
        $userType = "guest";

        $user = Auth::user();
        $addresses = UserAddress::where('user_id', Auth::id())->where('address_type', '!=', 'default shipping')->get();
        $plans = DB::table('membership_subscriptions')->where('customer_id', Auth::id())->where('expired', 0)
            ->join('membership_plans as plan', 'membership_subscriptions.plan_id', '=', 'plan.id')
            ->get();
        if ($user) {
            $userType = "authenticated";
            $auth_discount_per = GeneralSettingsClass::getRequiredValue('_auth_discount_percentage');
            $auth_discount = round($auth_discount_per, 2) / 100;
            $discount_from_amount = round($totalAmount, 2) - round($totalGst, 2);
            $apply_discount = $discount_from_amount * $auth_discount;
            $discounted_amount = round($discount_from_amount, 2) - round($apply_discount, 2);
            session(['authDisount' => $apply_discount]);
            session(['authDiscountPer' => $auth_discount_per]);
        } else {
            $apply_discount = 0;
            $authDiscountPer = 0;
            $discounted_amount = $totalAmount;
            session(['authDisount' => $apply_discount]);
            session(['authDiscountPer' => $authDiscountPer]);
            $userType = "guest";
        }
        return view('Website.GeneralPages.new-checkout', get_defined_vars());
    }

    public function fetch_shipping_address($id)
    {
        $address = UserAddress::where('id', $id)->first();
        return response()->json($data = $address);
    }

    
    public function apply_coupon(Request $request)
    {
        $giftcard = GiftCard::where('coupon_code', $request->code)->first();
        $giftCardPurchases = GiftCardPurchases::where('coupon_code', $request->code)->with('card')->first();
        $today = Carbon::today();
        if ($giftcard) {
            $issue_date = $giftcard->start_date;
            $expiry_date = $giftcard->end_date;
            $is_expirable = $giftcard->is_expirable;
            $is_expired = $giftcard->is_expired;
            $is_deleted = $giftcard->is_deleted;
            $usage = $giftcard->usage;

            if ($usage == 'one_time') {
                $used_card = GiftCardUsed::where('card_id', $giftcard->id)->first();
                if (!$user_card) {
                    if ($is_expired == 0) {
                        if ($is_deleted == 0) {
                            if ($today->greaterThanOrEqualTo($issue_date) && $today->lessThanOrEqualTo($expiry_date)) {
                                $discount = $giftcard->discount;
                                $couponRemaining = 0;
                                $amountPaidByCoupon = 0;
                                $totalAmount = $request->session()->get('totalAmount');
                                $discountedAmount = $totalAmount - $discount;
                                if ($discountedAmount < 0) {
                                    $discountedAmount = 0;
                                    $amountPaidByCoupon = $totalAmount;
                                    $couponRemaining = $discount - $totalAmount;
                                } else {
                                    $couponRemaining = 0;
                                    $amountPaidByCoupon = $totalAmount - $discountedAmount;
                                }

                                $couponArray = ['old_totalAmount' => $totalAmount, 'couponRemaining' => $couponRemaining, 'cardId' => $giftcard->id, 'card_amount' => $giftcard->discount, 'amountPaidByCoupon' => $amountPaidByCoupon];
                                session(['totalAmount' => $discountedAmount]);
                                session(['coupon_array' => $couponArray]);


                                return response(['status' => true, 'code' => 200, 'message' => 'Valid Coupon', 'data' => $discount, 'grandTotal' => $discountedAmount, 'couponRemaining' => $couponRemaining]);
                            } elseif ($today->lessThan($issue_date)) {
                                return response(['status' => false, 'code' => 302, 'message' => 'This coupon is no yet available.', 'start_date' => $issue_date]);
                            } else {
                                return response(['status' => false, 'code' => 302, 'message' => 'This coupon is no longer available.']);
                            }
                        } else {
                            return response(['status' => false, 'code' => 302, 'message' => 'This coupon is no longer available.']);
                        }
                    } else {
                        return response(['status' => false, 'code' => 302, 'message' => 'This Coupon is expired.']);
                    }
                } else {
                    return response(['status' => false, 'code' => 302, 'message' => 'This Coupon is expired.']);
                }
            } elseif ($usage == 'one_time_per_user') {
                $user = Auth::user();
                if ($user) {
                    $used_card = GiftCardUsed::where('user_id', $user->id)->first();
                    if (!$used_card) {
                        if ($is_expired == 0) {
                            if ($is_deleted == 0) {
                                if ($today->greaterThanOrEqualTo($issue_date) && $today->lessThanOrEqualTo($expiry_date)) {
                                    $discount = $giftcard->discount;
                                    $couponRemaining = 0;
                                    $amountPaidByCoupon = 0;
                                    $totalAmount = $request->session()->get('totalAmount');
                                    $discountedAmount = $totalAmount - $discount;
                                    if ($discountedAmount < 0) {
                                        $discountedAmount = 0;
                                        $amountPaidByCoupon = $totalAmount;
                                        $couponRemaining = $discount - $totalAmount;
                                    } else {
                                        $couponRemaining = 0;
                                        $amountPaidByCoupon = $totalAmount - $discountedAmount;
                                    }

                                    $couponArray = ['old_totalAmount' => $totalAmount, 'couponRemaining' => $couponRemaining, 'cardId' => $giftcard->id, 'card_amount' => $giftcard->discount, 'amountPaidByCoupon' => $amountPaidByCoupon];
                                    session(['totalAmount' => $discountedAmount]);
                                    session(['coupon_array' => $couponArray]);


                                    return response(['status' => true, 'code' => 200, 'message' => 'Valid Coupon', 'data' => $discount, 'grandTotal' => $discountedAmount, 'couponRemaining' => $couponRemaining]);
                                } elseif ($today->lessThan($issue_date)) {
                                    return response(['status' => false, 'code' => 302, 'message' => 'This coupon is no yet available.', 'start_date' => $issue_date]);
                                } else {
                                    return response(['status' => false, 'code' => 302, 'message' => 'This coupon is no longer available.']);
                                }
                            } else {
                                return response(['status' => false, 'code' => 302, 'message' => 'This coupon is no longer available.']);
                            }
                        } else {
                            return response(['status' => false, 'code' => 302, 'message' => 'This Coupon is expired.']);
                        }
                    } else {
                        return response(['status' => false, 'code' => 302, 'message' => 'This Coupon is expired.']);
                    }
                } else {
                    return response(['status' => false, 'code' => 302, 'message' => 'You have to login first to use this coupon.']);
                }
            } else {
                if ($is_expired == 0) {
                    if ($is_deleted == 0) {
                        if ($today->greaterThanOrEqualTo($issue_date) && $today->lessThanOrEqualTo($expiry_date)) {
                            $discount = $giftcard->discount;
                            $couponRemaining = 0;
                            $amountPaidByCoupon = 0;
                            $totalAmount = $request->session()->get('totalAmount');
                            $discountedAmount = $totalAmount - $discount;
                            if ($discountedAmount < 0) {
                                $discountedAmount = 0;
                                $amountPaidByCoupon = $totalAmount;
                                $couponRemaining = $discount - $totalAmount;
                            } else {
                                $couponRemaining = 0;
                                $amountPaidByCoupon = $totalAmount - $discountedAmount;
                            }

                            $couponArray = ['old_totalAmount' => $totalAmount, 'couponRemaining' => $couponRemaining, 'cardId' => $giftcard->id, 'card_amount' => $giftcard->discount, 'amountPaidByCoupon' => $amountPaidByCoupon];
                            session(['totalAmount' => $discountedAmount]);
                            session(['coupon_array' => $couponArray]);


                            return response(['status' => true, 'code' => 200, 'message' => 'Valid Coupon', 'data' => $discount, 'grandTotal' => $discountedAmount, 'couponRemaining' => $couponRemaining]);
                        } elseif ($today->lessThan($issue_date)) {
                            return response(['status' => false, 'code' => 302, 'message' => 'This coupon is no yet available.', 'start_date' => $issue_date]);
                        } else {
                            return response(['status' => false, 'code' => 302, 'message' => 'This coupon is no longer available.']);
                        }
                    } else {
                        return response(['status' => false, 'code' => 302, 'message' => 'This coupon is no longer available.']);
                    }
                } else {
                    return response(['status' => false, 'code' => 302, 'message' => 'This Coupon is expired.']);
                }
            }
        } elseif ($giftCardPurchases) {
            $expiry_date = $giftCardPurchases->expiry_date;
            $is_expired = $giftCardPurchases->is_expired;
            $is_used = $giftCardPurchases->is_used;
            $usage = $giftCardPurchases->card->usage;
            if ($giftCardPurchases->payment_method != 'Return Order') {
                if ($usage == "one_time" || $usage == "one_time_per_user") {
                    if ($is_used == 0) {
                        if ($is_expired == 0) {
                            if ($today->lessThanOrEqualTo($expiry_date)) {
                                $discount = $giftCardPurchases->total_amount;
                                $couponRemaining = 0;
                                $amountPaidByCoupon = 0;
                                $totalAmount = $request->session()->get('totalAmount');
                                $discountedAmount = $totalAmount - $discount;
                                if ($discountedAmount < 0) {
                                    $discountedAmount = 0;
                                    $amountPaidByCoupon = $totalAmount;
                                    $couponRemaining = $discount - $totalAmount;
                                } else {
                                    $couponRemaining = 0;
                                    $amountPaidByCoupon = $totalAmount - $discountedAmount;
                                }

                                $couponArray = ['old_totalAmount' => $totalAmount, 'couponRemaining' => $couponRemaining, 'cardId' => $giftCardPurchases->id, 'card_amount' => $giftCardPurchases->total_amount, 'amountPaidByCoupon' => $amountPaidByCoupon];
                                session(['totalAmount' => $discountedAmount]);
                                session(['coupon_array' => $couponArray]);

                                return response(['status' => true, 'code' => 200, 'message' => 'Valid Coupon', 'data' => $discount, 'grandTotal' => $discountedAmount, 'couponRemaining' => $couponRemaining]);
                            } else {
                                return response(['status' => false, 'code' => 302, 'message' => 'This Coupon is expired.']);
                            }
                        } else {
                            return response(['status' => false, 'code' => 302, 'message' => 'This Coupon is expired.']);
                        }
                    } else {
                        return response(['status' => false, 'code' => 302, 'message' => 'You have already used this coupon code.']);
                    }
                } else {
                    if ($today->lessThanOrEqualTo($expiry_date)) {
                        $discount = $giftCardPurchases->remaining_amount;
                        if ($discount != 0) {
                            $couponRemaining = 0;
                            $amountPaidByCoupon = 0;
                            $totalAmount = $request->session()->get('totalAmount');
                            $discountedAmount = $totalAmount - $discount;
                            if ($discountedAmount < 0) {
                                $discountedAmount = 0;
                                $amountPaidByCoupon = $totalAmount;
                                $couponRemaining = $discount - $totalAmount;
                            } else {
                                $couponRemaining = 0;
                                $amountPaidByCoupon = $totalAmount - $discountedAmount;
                            }

                            $couponArray = ['old_totalAmount' => $totalAmount, 'couponRemaining' => $couponRemaining, 'cardId' => $giftCardPurchases->id, 'card_amount' => $giftCardPurchases->remaining_amount, 'amountPaidByCoupon' => $amountPaidByCoupon];

                            session(['totalAmount' => $discountedAmount]);
                            session(['coupon_array' => $couponArray]);
                            return response(['status' => true, 'code' => 200, 'message' => 'Valid Coupon', 'data' => $discount, 'grandTotal' => $discountedAmount, 'couponRemaining' => $couponRemaining]);
                        } else {
                            return response(['status' => false, 'code' => 302, 'message' => 'This Coupon is out of balance.']);
                        }
                    } else {
                        return response(['status' => false, 'code' => 302, 'message' => 'This Coupon is expired.']);
                    }
                }
            } else {
                $discount = $giftCardPurchases->remaining_amount;
                if ($discount != 0) {
                    $couponRemaining = 0;
                    $amountPaidByCoupon = 0;
                    $totalAmount = $request->session()->get('totalAmount');
                    $discountedAmount = $totalAmount - $discount;
                    if ($discountedAmount < 0) {
                        $discountedAmount = 0;
                        $amountPaidByCoupon = $totalAmount;
                        $couponRemaining = $discount - $totalAmount;
                    } else {
                        $couponRemaining = 0;
                        $amountPaidByCoupon = $totalAmount - $discountedAmount;
                    }

                    $couponArray = ['old_totalAmount' => $totalAmount, 'couponRemaining' => $couponRemaining, 'cardId' => $giftCardPurchases->id, 'card_amount' => $giftCardPurchases->remaining_amount, 'amountPaidByCoupon' => $amountPaidByCoupon];

                    session(['totalAmount' => $discountedAmount]);
                    session(['coupon_array' => $couponArray]);
                    return response(['status' => true, 'code' => 200, 'message' => 'Valid Coupon', 'data' => $discount, 'grandTotal' => $discountedAmount, 'couponRemaining' => $couponRemaining]);
                } else {
                    return response(['status' => false, 'code' => 302, 'message' => 'This Coupon is out of balance.']);
                }
            }
        } else {
            return response(['status' => false, 'code' => 302, 'message' => 'Invalid Coupon Code.']);
        }
    }

    public function restore_grand_total()
    {
        $couponArray = session()->get('coupon_array');
        if ($couponArray) {
            $totalAmount = $couponArray['old_totalAmount'];

            session(['totalAmount' => $totalAmount]);
            Session::forget('coupon_array');
            return response(['status' => true, 'message' => 'session removed successfully']);
        }
    }
}
