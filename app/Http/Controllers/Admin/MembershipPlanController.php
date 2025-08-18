<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MembershipPlan;
use App\Models\User;
use Carbon\Carbon;
use DB;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $membershipPlans = MembershipPlan::latest()->get(); // Assuming you have a MembershipPlan model
        return view('Admin.Memberships.index', compact('membershipPlans'));
    }
    public function create()
    {
        return view('Admin.Memberships.create');
    }

    public function store(Request $request)
    {
        $logged_in_user = Auth::user();

        // Validate the form data
        $request->validate([
            'membershipName' => 'required|string',
            'discountPercentage' => 'required|integer',
            'duration' => 'required|string',
            'price' => 'required|numeric',
            'badge' => 'nullable|string',
            'membership_type' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Create a new MembershipPlan instance
        MembershipPlan::create([
            'plan_name' => $request->input('membershipName'),
            'discount_percentage' => $request->input('discountPercentage'),
            'duration' => $request->input('duration'),
            'price' => $request->input('price'),
            'badge_text' => $request->input('badge'),
            'membership_type' => $request->input('membership_type'),
            'description' => $request->input('description'),
            'created_by' => $logged_in_user->id,
            'active' => 1
        ]);

        // Redirect to a success page or perform any other action
        return redirect()->route('memberships.create')->with('success', 'Membership plan created successfully');
    }
    public function destroy(Request $request, MembershipPlan $membership)
    {
        try {
            $membership->delete();
            return redirect()->route('memberships.index')->with('success', 'Membership plan deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('memberships.index')->with('error', 'Failed to delete membership plan');
        }
    }

    public function edit(MembershipPlan $membership)
    {
        return view('Admin.Memberships.edit', compact('membership'));
    }

    public function update(Request $request, MembershipPlan $membership)
    {
        $request->validate([
            'membershipName' => 'required|string',
            'discountPercentage' => 'required|integer',
            'duration' => 'required|string',
            'price' => 'required|numeric',
            'badge' => 'nullable|string',
            'membership_type' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        try {
            $membership->update([
                'plan_name' => $request->input('membershipName'),
                'discount_percentage' => $request->input('discountPercentage'),
                'duration' => $request->input('duration'),
                'price' => $request->input('price'),
                'badge_text' => $request->input('badge'),
                'membership_type' => $request->input('membership_type'),
                'description' => $request->input('description'),
            ]);

            return redirect()->route('memberships.edit', ['membership' => $membership->id])->with('success', 'Membership plan updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('memberships.edit', ['membership' => $membership->id])->with('error', 'Failed to update membership plan');
        }
    }
    public function search_customer(Request $request)
    {
        $search_query = $request->get('customer-search');
        $customers = User::where(function ($query) use ($search_query) {
            $query->where('name', 'like', "%$search_query%")
                ->orWhere('email', 'like', "%$search_query%");
        })
            ->where('role_id', '=', 5)
            ->where('active', 1)
            ->get();
        return response()->json(['customers' => $customers]);
    }
    public function search_subscribers(Request $request)
    {
        $search_query = $request->get('plan_id');
        $subscribers = DB::table('membership_subscriptions')
            ->join('users', 'membership_subscriptions.customer_id', '=', 'users.id')
            ->where('plan_id', $search_query)
            ->orderBy('membership_subscriptions.id', 'DESC')
            ->get();
        return response()->json(['subscribers' => $subscribers]);
    }
    
    public function add_subscribers(Request $request)
    {
        $logged_in_user = Auth::user();
        $plan_id = $request->get('plan_id');
        $customer_ids = explode(',', $request->get('subscriber_ids'));
        foreach ($customer_ids as $customer_id) {
            $existing = DB::table('membership_subscriptions')->where('customer_id', $customer_id)->where('plan_id', $plan_id)->where('expired', 0)->count();
            if ($existing == 0) {
                $plan_days = MembershipPlan::select('duration')->where('id', $plan_id)->first();
                $exiration = '';
                if ($plan_days->duration == 'Un-Limited') {
                    $exiration = $this->addDaysToCurrentDate(999999);
                } else {
                    $exiration = $this->addDaysToCurrentDate($plan_days->duration);
                }
                $currentDate = date('d-m-Y H:i:s');
                DB::table('membership_subscriptions')->insert([
                    'plan_id' => $plan_id,
                    'customer_id' => $customer_id,
                    'created_by' => $logged_in_user->id,
                    'starting_date' => $currentDate,
                    'ending_date' => $exiration,
                    'expired' => 0
                ]);
            }
        }
        return response()->json(['message' => 'Changes have been saved successfully.']);
    }
    function addDaysToCurrentDate($numberOfDays)
    {
        $currentDate = date('d-m-Y H:i:s');
        $newDate = date('d-m-Y', strtotime($currentDate . ' + ' . $numberOfDays . ' days'));
        return $newDate;
    }

    function subscriber_plan(Request $request)
    {
        $customer_id = $request->get('customer_id');
        $plans = DB::table('membership_subscriptions')
            ->join('membership_plans', 'membership_subscriptions.plan_id', '=', 'membership_plans.id')
            ->where('membership_subscriptions.customer_id', $customer_id)
            ->where('membership_subscriptions.expired', 0)
            ->get();
        return response()->json(['plans' => $plans]);
    }

    public function updateExpiryStatus()
    {
        $date = Carbon::today();
        $existing = DB::table('membership_subscriptions')->where('expired', 0)->get();

        foreach ($existing as $subs) {
            $targetDate = Carbon::createFromFormat('d-m-Y', $subs->ending_date);
            if ($targetDate->lte($date)) {
                DB::table('membership_subscriptions')
                    ->where('id', $subs->id)
                    ->update(['expired' => 1]);
            }
        }

        return "Statuses Updated Successfully";
    }
    function setDefaultSignUpPlan($plan_id)
    {

        DB::table('membership_plans')->where('id', $plan_id)->update(['signup' => 1]);

        DB::table('membership_plans')->where('id', '!=', $plan_id)->update(['signup' => 0]);

        return response()->json(['message' => 'Changes have been saved successfully.']);
    }

    public function removeSubscriber(Request $request)
    {
        $subscriber_id = $request->get('subscriber_id');
        $plan_id = $request->get('plan_id');
        try {
            DB::table('membership_subscriptions')
            ->where('customer_id', $subscriber_id)
            ->where('plan_id', $plan_id)
            ->delete();


            return response()->json(['message' => 'Subscriber removed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to remove subscriber.'], 500);
        }
    }
}
