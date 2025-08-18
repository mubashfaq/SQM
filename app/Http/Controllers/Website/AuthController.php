<?php

namespace App\Http\Controllers\Website;

use App\General\GeneralSettingsClass;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Website\EmailController;
use App\Http\Controllers\Website\Traits\EmailTrait;
use App\Models\User;
use App\Models\GeneralSettings;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AuthController extends Controller
{    use EmailTrait;
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ], [
        'email.required' => 'The email field is required.',
        'email.email' => 'Please enter a valid email address.',
        'password.required' => 'The password field is required.',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Check if the user is active
        if ($user->active == 0) {
            Auth::logout();  // Log the user out if they're inactive
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact support.',
            ])->withInput();
        }

        $request->session()->regenerate();
        return redirect()->route('shop')->with('success', 'You are successfully logged in!');
    }

    return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->withInput();
}

    

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the session to remove all session data
        $request->session()->invalidate();

        // Regenerate the CSRF token to prevent potential security issues
        $request->session()->regenerateToken();

        return redirect('/sign-in')->with('success', 'You are successfully logged out!');
    }

    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);
            session()->put('user_email', $request->email);

            $user = User::where('email', $request->email)->exists();
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

            if ($user) {
                // Delete previous password reset request if exists for this email
                DB::table('password_resets')->where('email', $request->email)->delete();

                $user_details = User::where('email', $request->email)->first();
                $token = Str::random(60);
                $expiration = now()->addMinutes(env('PASSWORD_LINK_TIME'));

                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => now(),
                    'expires_at' => $expiration,
                ]);
                $resetUrl = URL::signedRoute('Website.ResetPassword', ['token' => $token]);
                $params = [
                    'subject' => 'Reset Password Email | ' . config('app.website_name'),
                    'to' => $user_details->email,
                    'template' => 'Website.Emails.reset-password',
                    'data' => [
                        'name' => $user_details->name,
                        'url' => $resetUrl,
                        'logo' => $logo,
                        'instagram_link' => $instagram_link,
                        'facebook_link' => $facebook_link,
                        'twitter_link' => $twitter_link,
                        'youtube_link' => $youtube_link,
                        'admin_email' => $admin_email,
                    ],
                ];

                
            $emailSent = $this->SendEmail($params);
                if ($emailSent) {
                    return redirect()->route('Website.GeneralPages.Login')->with(['status' => 200, 'message' => 'An Reset Password Email has been sent to your Email.']);
                } else {
                    return redirect()->back()->with(['status' => 500, 'message' => 'SMTP is not Working, Try Later!']);
                }
            } else {
                return redirect()->back()->with(['status' => 404, 'message' => 'Account with the entered does not exist.']);
            }
        } catch (Exception $ex) {
            return response()->json([
                'status' => 500,
                'message' => 'An Error Occured, Try Again!',
                'error' => $ex,
            ]);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        // Retrieve the email stored in the session
        $email = session()->get('user_email');

        if (!$email) {
            return redirect()->back()->with([
                'status' => 404,
                'message' => 'Session expired or invalid. Please try again.'
            ]);
        }

        // Find the user based on the session email
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with([
                'status' => 404,
                'message' => 'User not found.'
            ]);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        // Remove any password reset tokens for this email
        DB::table('password_resets')->where('email', $email)->delete();

        // Optionally, clear the stored email from the session
        session()->forget('user_email');

        return redirect()->route('Website.GeneralPages.Login')->with([
            'status' => 200,
            'message' => 'Password Updated Successfully. You can now log in to your account.'
        ]);
    }

}
