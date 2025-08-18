<?php

namespace App\Http\Controllers\API\Traits;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;
use App\Models\GeneralSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\Traits\EmailTrait;

trait UserTrait 
{
    use ValidationTrait, EmailTrait;
    public function registerUser(Request $request)
    {
        $validator = $this->userValidationRequest($request);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $message = reset($errors)[0]; // Get the first error message
            return $this->error($message, 422);
        }

        $sanitizedData = $validator->validated();


        if (isset($request->created_by)) {
            $sanitizedData['created_by'] = $request->created_by;
            $sanitizedData['email_verified_at'] = now();
            $sanitizedData['active'] = 1;
        }

        $sanitizedData['password'] = Hash::make($sanitizedData['password']);

        $user = User::create($sanitizedData);
        if (!$user) {
            return $this->error('An Error Occurred, During creating user account.', 500);
        }
        $token = $user->createToken('authToken')->plainTextToken;

        $metaDescriptions = $request->only([
            '_b_name',
            '_b_email',
            '_b_number',
            '_b_street_address',
            '_b_area_address',
        ]);

        $userMeta = [];
        foreach ($metaDescriptions as $option => $value) {
            if ($value !== null) {
                $userMeta[] = [
                    'user_id' => $user->id,
                    'option' => $option,
                    'value' => $value,
                ];
            }
        }

        # Insert user meta descriptions in bulk
        DB::table('user_metas')->insert($userMeta);


        $data = GeneralSettings::where('option', '_header_logo')->orWhere('option', '_b_email')->orWhere('option', '_instagram_link')->orWhere('option', '_facebook_link')->orWhere('option', '_twitter_link')->orWhere('option', '_youtube_link')->get()->toArray();

        $header_logo_index = array_search('_header_logo', array_column($data, 'option'));
        $logo = $data[$header_logo_index]['value'];
        
        if ($user) {
            $this->assignSignupPlanToCustomer($user->id);

            $route = route('Admin.Login');
            if ($user->role_id == 5) {
                $route = url('/') . '/sign-in';
            } else {
                $route = route('Admin.Login');
            }

            // if (isset($request->confirmation_email_sending)) {
                $params = [
                    'subject' => 'Account Login Details | Sydney Quality Meat',
                    'to' => $request->email,
                    'blade' => 'account-login-details',
                    'msg' => view('Admin.Emails.account-login-details', [
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => $request->password,
                        'url' => $route,
                        'image_url' => url('/'),
                        'logo' => $logo
                    ])->render(),
                ];

                $emailSent = $this->SendInstantEmail($params);
                // \Log::info
                if ($emailSent) {
                    return $this->withToken($user, $token, 'Account created successfully, An Email has been sent to user email for account information.');
                } else {
                    return $this->withToken($user, $token, 'Account created successfully, An Error Occurred while sending the email.');
                }
            // }
            return $this->withToken($user, $token, 'Account created successfully.', 200);
        } else {
            return $this->error('An Error Occurred, During creating user account.', 500);
        }
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (!Auth::attempt($credentials)) {
            return $this->error('Invalid credentials', 401);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;
        $subscriptionDetails = $this->getSubscriptionDetails($user->id);
        return response()->json([
            'user' => $user,
            'token' => $token,
            'subscription' => $subscriptionDetails,
        ]);
    }
    public function authUser()
    {
        return auth()->user();
    }
    public function newsLetter($model, Request $request)
    {
        $validator = $this->newLetterValidationRequest($request);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $message = reset($errors)[0]; // Get the first error message
            return $this->error($message, 422);
        }
        $newLetter = $model->firstOrCreate($validator->validated());
        if (!$newLetter) {
            return $this->error('Failed to create user address', 500);
        }
        $params =
            [
                'subject' => 'Subscribe NewsLetter | Sydney Quality Meat',
                'to' => $request->email,
                'msg' => view('Admin.Emails.news-letter')->render(),
            ];
        $this->SendInstantEmail($params);
        return $this->success($newLetter, 'new letter created successfully');
    }
    public function resetPasswordLink($otp, $details)
    {
        // $params = [
        //     'subject' => 'resetPassword Link | Sydney Quality Meat',
        //     'to' => $details->email,
        //     'msg' => view('Admin.Emails.reset-password', ['resetUrl' => $resetUrl, 'details' => $details])->render(),
        // ];

        $params = [
            'subject' => 'Reset Password Link | Sydney Quality Meat',
            'to' => $details->email,
            'template' => 'Admin.Emails.reset-password',
            'data' => [
                'otp' => $otp,
                'details' => $details
            ],
        ];
        return  $this->SendEmail($params);
    }

    protected function checkOldPassword($oldPassword, $currentPassword)
    {
        return Hash::check($oldPassword, $currentPassword);
    }

    protected function handleProfileImageUpload(Request $request, $user)
    {
        $file = $request->file('profile_image');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('user-images'), $fileName);
        $user->profile_image = $fileName;
    }

    protected function updateUserProfile(Request $request, $user)
    {
        $user->fill($request->except(['password', 'old_password']));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $metaDescriptions = $request->only([
            '_b_name',
            '_b_email',
            '_b_number',
            '_b_street_address',
            '_b_area_address',
        ]);

        foreach ($metaDescriptions as $option => $value) {
            if ($value !== null) {
                $existingMeta = UserMeta::where('user_id', $user->id)
                    ->where('option', $option)
                    ->first();

                if ($existingMeta) {
                    $existingMeta->update(['value' => $value]);
                } else {
                    UserMeta::create([
                        'user_id' => $user->id,
                        'option' => $option,
                        'value' => $value,
                    ]);
                }
            }
        }
    }

    protected function getUserByEmail(Request $request)
    {
        return User::where('email', $request->email)
            ->first();
    }

    protected function generateAndStoreOtp($user)
    {
        $otp = $this->generateOtp();
        $user->otp = Hash::make($otp);
        $user->otp_created_at = Carbon::now();
        $user->save();
        return $otp;
    }

    protected function generateOtp()
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    protected function sendOtpEmail($user, $otp)
    {
        $params =
            [
                'subject' => 'Reset Password OTP | Sydney Quality Meat',
                'to' => $user->email,
                'msg' => view('Admin.Emails.send-otp', ['name' => $user->name, 'otp' => $otp])->render(),
            ];
        return $this->SendInstantEmail($params);
    }

    protected function isOtpValid($otp, $user)
    {
        return Hash::check($otp, $user->otp) && $user->otp_created_at->diffInMinutes(Carbon::now()) <= 10;
    }

    protected function verifyOtpAndGetUser(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$this->isOtpValid($request->otp, $user)) {
            return null;
        }
        return $user;
    }

    protected function validateAndUpdatePassword(Request $request, $user)
    {
        $validator = $this->passwordValidationRequest($request);
        if ($validator->fails()) {
            return $this->handleValidationFailure($validator);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return true;
    }

    protected function assignSignupPlanToCustomer($customerId)
    {
        $plans = DB::table('membership_plans')->where('signup', 1)->get();

        if ($plans->isEmpty()) {
            return false;
        }
        $currentDate = date('d-m-Y H:i:s');
        foreach ($plans as $plan) {
            $expiration = '';
            if ($plan->duration == 'Un-Limited') {
                $expiration = $this->addDaysToCurrentDate(999999);
            } else {
                $expiration = $this->addDaysToCurrentDate($plan->duration);
            }
            DB::table('membership_subscriptions')->insert([
                'plan_id' => $plan->id,
                'customer_id' => $customerId,
                'created_by' => 0,
                'starting_date' => $currentDate,
                'ending_date' => $expiration,
                'expired' => 0,
            ]);
        }
        return true;
    }

    protected function addDaysToCurrentDate($numberOfDays)
    {
        $currentDate = date('d-m-Y H:i:s');
        $newDate = date('d-m-Y', strtotime($currentDate . ' + ' . $numberOfDays . ' days'));
        return $newDate;
    }
    protected function getSubscriptionDetails($userId)
    {
        $userSubscriptions = DB::table('membership_subscriptions')
            ->join('membership_plans', 'membership_subscriptions.plan_id', '=', 'membership_plans.id')
            ->where('membership_subscriptions.expired', 0)
            ->where('membership_subscriptions.customer_id', $userId)
            ->select('membership_subscriptions.*', 'membership_plans.*');
        $signupPlans = DB::table('membership_plans')
            ->leftJoin('membership_subscriptions', function ($join) use ($userId) {
                $join->on('membership_subscriptions.plan_id', '=', 'membership_plans.id')
                    ->where('membership_subscriptions.customer_id', $userId);
            })
            ->where('membership_plans.signup', 1)
            ->whereNull('membership_subscriptions.id')
            ->select('membership_plans.*', 'membership_subscriptions.*');

        $subscriptionPlans = $userSubscriptions->union($signupPlans)->distinct()->get();

        return $subscriptionPlans;
    }
}
