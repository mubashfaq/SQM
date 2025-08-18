<?php

namespace App\Http\Controllers\Website;

use App\General\GeneralSettingsClass;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Website\EmailController;
use App\Http\Controllers\Website\Traits\EmailTrait;
use App\Models\GeneralSettings;
use App\Models\NewsLetterSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{ use EmailTrait;
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        $user = Auth::user();
        $oldPassword = $request->old_password;
        $newPassword = $request->password;
        
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

        if (Hash::check($oldPassword, $user->password)) {
            if ($oldPassword !== $newPassword) {
                $user->password = Hash::make($newPassword);
                $user->save();

                $emailController = new EmailController();
                $paramsAdmin = [
                    'subject' => 'Updated Login Details',
                    'to' => $user->email,
                    'blade' => 'change-profile-password',
                    'data' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'password' => $newPassword,
                        'url' => url('/'),
                        'logo' => $logo,
                        'instagram_link' => $instagram_link,
                        'facebook_link' => $facebook_link,
                        'twitter_link' => $twitter_link,
                        'youtube_link' => $youtube_link,
                        'admin_email' => $admin_email,
                    ],
                ];
                $emailSentAdmin = $emailController->SendInstantEmail($paramsAdmin);

                return redirect()->back()->with([
                    'success_status' => 'success',
                    'message' => 'Password updated successfully.',
                ], 200);
            } else {
                return redirect()->back()->with([
                    'error_status' => 'error',
                    'message' => 'New password cannot be same as old password.',
                ], 422);
            }
        } else {
            return redirect()->back()->with([
                'error_status' => 'error',
                'message' => 'Invalid old password.',
            ], 422);
        }
    }
    public function updatePersonalDetails(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
            'phone' => 'required',
        ]);
        $user = Auth::user();
        $user->name = $request->first_name . " " . $request->last_name;
        $user->phone = $request->phone;

        $metaDescriptions = $request->only([
            '_address',
        ]);

        $UserMeta = [];
        foreach ($metaDescriptions as $option => $value) {
            if ($value !== null) {
                $UserMeta[] = [
                    'user_id' => $user->id,
                    'option' => $option,
                    'value' => $value,
                ];
            }
        }
        DB::table('user_metas')->where('user_id',  $user->id)->delete();
        # Insert product meta descriptions in bulk
        DB::table('user_metas')->insert($UserMeta);

        if ($user->update()) {
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('AdminAssets/Users/'), $imageName);
                $user->profile_image = '/AdminAssets/Users/' . $imageName;
                $user->update();

                // Update user session with the new profile image
                $request->session()->put('user.profile_image', $user->profile_image);

                return redirect()->back()->with(['message' => 'Profile updated successfully.']);
            }
            return redirect()->back()->with(['message' => 'Profile updated successfully.']);
        }
        return redirect()->back()->with([
            'status' => 'error',
            'message' => 'An Error occured, please try again!',
        ], 422);
    }

    public function register(Request $request)
    {
        $sanitizedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users'),
            ],
            'password' => 'required|min:8',
            'phone' => 'required|string',
            'business_name' => 'nullable|string|max:255',
            'business_email' => 'nullable|email',
            'business_phone' => 'nullable|string',
            'business_street_address' => 'nullable|string|max:255',
            'area_address' => 'nullable',
            'terms' => 'required',
        ]);

        $sanitizedData['profile_image'] = '/user.jpg';
        $sanitizedData['role_id'] = 5;
        $sanitizedData['name'] = $request->name;
        $sanitizedData['email'] = $request->email;
        $sanitizedData['email_verified_at'] = now();
        $sanitizedData['active'] = 1;
        $sanitizedData['password'] = Hash::make($sanitizedData['password']);
        $sanitizedData['phone'] = $request->phone;

        $user = User::create($sanitizedData);
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

        $metaDescriptions = $request->only([
            'business_name',
            'business_email',
            'business_phone',
            'business_street_address',
            'area_address',
        ]);

        $UserMeta = [];
        foreach ($metaDescriptions as $option => $value) {
            if ($value !== null) {
                $UserMeta[] = [
                    'user_id' => $user->id,
                    'option' => $option,
                    'value' => $value,
                ];
            }
        }
        DB::table('user_metas')->insert($UserMeta);

        if ($user) {
            $params = [
                'subject' => 'Account Created Successfully | ' . config('app.website_name'),
                'to' => $request->email,
                'template' => 'Website.Emails.new-account',
                'data' => [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'url' => url('/'),
                    'logo' => $logo,
                    'instagram_link' => $instagram_link,
                    'facebook_link' => $facebook_link,
                    'twitter_link' => $twitter_link,
                    'youtube_link' => $youtube_link,
                    'admin_email' => $admin_email,
                ],
            ];

            $emailSentUser = $this->SendEmail($params);

            if ($request->subscribe_newsletter == "on") {
                $subscription = NewsLetterSubscription::where('email', $request->email)->first();
                if (!$subscription) {
                    NewsLetterSubscription::create([
                        'user_id' => $user->id,
                        'email' => $request->email,
                        'state' => $request->business_street_address,
                        'business_type' => $request->area_address,
                    ]);

                    $emailController = new EmailController();
                    $paramsAdmin = [
                        'subject' => 'News Letter Subscription',
                        'to' => $request->email,
                        'blade' => 'Website.Emails.news-letter-subscription',
                        'data' => [
                            'name' => $request->first_name . " " . $request->last_name,
                            'url' => url('/'),
                            'logo' => $logo,
                            'instagram_link' => $instagram_link,
                            'facebook_link' => $facebook_link,
                            'twitter_link' => $twitter_link,
                            'youtube_link' => $youtube_link,
                            'admin_email' => $admin_email,
                        ],
                    ];
                    $emailSentUser_subscription = $this->SendEmail($paramsAdmin);
                }
            }
            
            $creds = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            if (Auth::attempt($creds)) {
                $request->session()->regenerate();
                return redirect()->route('shop')->with(['success' => 'Welcome to the Sydney Quality Meats']);
            } else {
                return redirect()->route('Website.GeneralPages.Login')->with(['message' => 'Signup successfully, Please Login to your account.']);
            }
        } else {
            return redirect()->back()->withErrors('An Error Occurred, During creating user account.');
        }
    }
    public function update_business_details(Request $request)
    {
        $user = Auth::user();
        $metaDescriptions = $request->only([
            '_b_name',
            '_b_email',
            '_b_number',
            '_b_street_address',
            '_b_area_address',
            '_b_type',
            '_other_b_type',
        ]);

        DB::table('user_metas')
            ->where('user_id', $user->id)
            ->whereNotIn('option', ['_address'])
            ->delete();

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
        DB::table('user_metas')->insert($userMeta);
        return redirect()->back()->with(['message' => 'Business Details Updated Successfully']);
    }

    public function subscribe_newsletter(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email|unique:news_letter_subscriptions,email',
            'state' => 'required|string',
            'business_type' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first() ?? Auth::id();
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

        if ($validator) {
            $subscribed = NewsLetterSubscription::where('email', $request->email)->first();

            if (!$subscribed) {
                NewsLetterSubscription::create([
                    'user_id' => $user->id ?? 0,
                    'email' => $request->email,
                    'state' => $request->state,
                    'business_type' => $request->business_type,
                ]);
                $emailController = new EmailController();
                $paramsAdmin = [
                    'subject' => 'News Letter Subscription',
                    'to' => $request->email,
                    'blade' => 'news-letter-subscription',
                    'data' => [
                        'url' => url('/'),
                        'logo' => $logo,
                        'instagram_link' => $instagram_link,
                        'facebook_link' => $facebook_link,
                        'twitter_link' => $twitter_link,
                        'youtube_link' => $youtube_link,
                        'admin_email' => $admin_email,
                    ],
                ];
                $emailSentAdmin = $emailController->SendInstantEmail($paramsAdmin);

                return response()->json(['success' => true, 'message' => 'You have subscribed to our newsletter successfully.']);
            } else {
                return response()->json(['success' => true, 'message' => 'You are already subscribed to the newsletter']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to subscribe to the newsletter. Please try again.']);
        }
    }
}
