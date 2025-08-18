<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use App\Models\GiftCardPurchases;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class GiftCardController extends Controller
{
    public function index()
    {
        $gift_cards = GiftCard::where('status', 'active')->get();
        return view('Admin.GiftCards.index', compact('gift_cards'));
    }

    public function create()
    {
        return view('Admin.GiftCards.create');
    }

    public function edit(GiftCard $gift_card)
    {
        if ($gift_card) {
            if ($gift_card->variant == 'gift_card') {
                return view('Admin.GiftCards.giftCardEdit', compact('gift_card'));
            }
            return view('Admin.GiftCards.edit', compact('gift_card'));
        } else {
            return redirect()->back()->withError('Gift Card Not Found');
        }
    }

    public function store(Request $request)
    {
        // Check for existing coupon code
        if ($request->coupon_code) {
            $request->merge(['coupon_code_present' => true]);
            $check = GiftCard::where('coupon_code', $request->coupon_code)->exists();
            if ($check) {
                return redirect()->back()->withErrors('Coupon Code Already Exists');
            }
        } else {
            $request->merge(['coupon_code_present' => false]);
        }
        
        // Set price presence flag
        if ($request->price) {
            $request->merge(['price_present' => true]);
        } else {
            $request->merge(['price_present' => false]);
        }
        
        // Set expire_after_weeks presence flag
        if ($request->expire_after_weeks) {
            $request->merge(['expire_after_weeks_present' => true]);
        } else {
            $request->merge(['expire_after_weeks_present' => false]);
        }
        
        // Base validation rules
        $validation_rules = [
            'title' => 'required|string',
            'coupon_code' => 'required_if:coupon_code_present,true',
            'variant' => 'required|in:gift_card,promotion,coupon',
            'usage' => 'required|in:one_time,multiple_times,one_time_per_user',
            'discount' => 'required|integer',
            'price' => 'nullable',
            'status' => 'required|string',
            'is_expirable' => 'required|integer',
            'expire_after_weeks' => 'required_if:expire_after_weeks_present,true',
        ];
        
        if ($request->variant === 'gift_card') {
            $validation_rules['price'] = 'required|numeric'; // Make price required when variant is gift_card
        }
        
        // Add conditional validation for start_date and end_date
        if ($request->variant !== 'gift_card') {
            $validation_rules['start_date'] = 'required|date';
            $validation_rules['end_date'] = 'required|date';
        } else {
            $validation_rules['start_date'] = 'date|nullable';
            $validation_rules['end_date'] = 'date|nullable';
        }
        
        $validated_data = $request->validate($validation_rules);
    
        $logged_in_user = Auth::user();
        $validated_data['created_by'] = $logged_in_user->id;
    
        $gift_card = GiftCard::create($validated_data);
    
        return redirect()->route('gift-cards.create')->with('success', 'Gift Card created successfully.');
    }

    public function update(Request $request, $id)
    {
        if ($request->coupon_code) {
            $request->merge(['coupon_code_present' => true]);
            $check = GiftCard::where('coupon_code', $request->coupon_code)->first();
            if ($check && $check->id != $id) {
                return redirect()->back()->withErrors('Coupon Code Already Exists');
            }
        } else {
            $request->merge(['coupon_code_present' => false]);
        }
        if ($request->price) {
            $request->merge(['price_present' => true]);
        } else {
            $request->merge(['expire_after_weeks_present' => false]);
        }
        if ($request->expire_after_weeks) {
            $request->merge(['expire_after_weeks_present' => true]);
        } else {
            $request->merge(['expire_after_weeks_present' => false]);
        }
        $validated_data = $request->validate([
            'title' => 'required|string',
            'coupon_code' => 'required_if:coupon_code_present,true',
            'variant' => 'required|in:gift_card,promotion,coupon',
            'usage' => 'required|in:one_time,multiple_times,one_time_per_user',
            'start_date' => 'date|nullable',
            'end_date' => 'date|nullable',
            'discount' => 'required|integer',
            'price' => 'required_if:price_present,true',
            'status' => 'required|string',
            'is_expirable' => 'required|integer',
            'expire_after_weeks' => 'required_if:expire_after_weeks_present,true',
        ]);

        $gift_card = GiftCard::find($id);
        if (!$gift_card) {
            return Redirect::back()->withErrors(['Card not found.']);
        }

        $gift_card->update($validated_data);
        return redirect()->back()->with('success', 'Card updated successfully.');
    }
    public function destroy($id)
    {
        $card = GiftCard::find($id);
        if (!$card) {
            return Redirect::back()->withErrors(['GiftCard not found.']);
        }
        $card->delete();
        return redirect()->back()->with('success', 'Gift Card deleted successfully.');
    }
    // Controller method with proper error handling
    public function updateStatus(Request $request, $id)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'status' => 'required|in:active,expired,deleted',
                'reason' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->with('error', 'Validation failed: ' . $validator->errors()->first())
                    ->withErrors($validator)
                    ->withInput();
            }

            $giftCard = GiftCard::find($id);

            if (!$giftCard) {
                return redirect()->back()->with('error', 'Gift card not found.');
            }

            $giftCard->status = $request->status;
            $giftCard->reason = $request->reason;

            switch ($request->status) {
                case 'expired':
                    $giftCard->expired_by = Auth::id();
                    $giftCard->expired_at = Carbon::now();
                    $statusMessage = 'Gift card has been expired successfully.';
                    break;
                case 'deleted':
                    $giftCard->deleted_by = Auth::id();
                    $giftCard->deleted_at = Carbon::now();
                    $statusMessage = 'Gift card has been moved to trash successfully.';
                    break;
                case 'active':
                    $giftCard->expired_by = null;
                    $giftCard->expired_at = null;
                    $giftCard->deleted_by = null;
                    $giftCard->deleted_at = null;
                    $statusMessage = 'Gift card has been activated successfully.';
                    break;
                default:
                    return redirect()->back()->with('error', 'Invalid status provided.');
            }

            $giftCard->save();

            return redirect()->back()->with('success', $statusMessage);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function deleted_list()
    {
        $gift_cards = GiftCard::where('status', 'deleted')->get();
        return view('Admin.GiftCards.deleted', compact('gift_cards'));
    }

    public function expired_list()
    {
        $gift_cards = GiftCard::where('status', 'expired')->get();
        return view('Admin.GiftCards.expired', compact('gift_cards'));
    }

    public function purchased_list(Request $request)
    {
        if ($request->card_id) {
            $gift_cards = GiftCardPurchases::where('card_id', $request->card_id)->with('card')->get();
            return view('Admin.GiftCards.purchased', compact('gift_cards'));
        } else {
            $gift_cards = GiftCardPurchases::with('card')->where('card_id', '!=', '0')->get();
            return view('Admin.GiftCards.purchased', compact('gift_cards'));
        }
    }
}
