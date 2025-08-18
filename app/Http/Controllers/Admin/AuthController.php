<?php

namespace App\Http\Controllers\Admin;

use App\General\GeneralSettingsClass;
use App\Http\Controllers\API\Traits\UserTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Admin\EmailController;

class AuthController extends Controller
{
    use UserTrait;
  public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Check if the user is active
        if ($user->active == 0) {
            Auth::logout();  // Log the user out if they're inactive
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact support.',
            ]);
        }

        // Check if user role is not allowed to login
        if ($user->role_id == 5) {
            Auth::logout();  // Log the user out if they have restricted role
            return back()->withErrors([
                'email' => 'Access denied. You do not have permission to login.',
            ]);
        }

        $request->session()->regenerate();

        // Get user permissions
        $arr = DB::table('role_permissions')->where('role_id', $user->role_id)->get();
        $permission = [];
        $key = 0;
        foreach ($arr as $a) {
            $permission[$key] = $a->permission;
            $key++;
        }

        $request->session()->put('permissions', $permission);

        return redirect()->intended('/admin');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}

    public function logout()
    {
        Auth::logout();
        return redirect('/admin/login');
    }

    public function register(Request $request)
    {
        // Validation logic here...

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);
    
            $user = User::where('email', $request->email)->exists();
            $userDetail = User::where('email', $request->email)->first();
    
            if ($user && $userDetail->role != 5) {
                $logo = GeneralSettingsClass::getRequiredValue('_header_logo');
    
                // Delete previous password reset request if exists for this email
                DB::table('password_resets')->where('email', $request->email)->delete();
    
                // Delete any previous OTP requests for this email
            DB::table('password_resets')->where('email', $request->email)->delete();
    
            // Generate a 6-digit OTP
            $otp = rand(100000, 999999);
            // Set OTP expiration time (default to 10 minutes, or set in .env as PASSWORD_OTP_EXPIRY)
            $expiration = now()->addMinutes(env('PASSWORD_OTP_EXPIRY', 10));
    
            // Store OTP details in the password_resets table (ensure your table has an 'otp' column)
            DB::table('password_resets')->insert([
                'email'      => $request->email,
                'token'        => $otp,
                'created_at' => now(),
                'expires_at' => $expiration,
            ]);

               
                $emailSent = $this->resetPasswordLink($otp, $userDetail);
                // Store the email in session correctly
                session(['admin_reset_email' => $request->email]);
                session()->flash('status', 'An Reset Password Email has been sent to your Email.');
                if ($emailSent) {
                    return response()->json([
                        'status'  => 200,
                        'message' => 'An Reset Password Email has been sent to your Email.',
                    ]);
                } else {
                    return response()->json([
                        'status'  => 500,
                        'message' => 'SMTP is not Working, Try Later!',
                    ]);
                }
            } else {
                return response()->json([
                    'status'  => 404,
                    'message' => 'Account with the entered email does not exsit.',
                ]);
            }
        } catch (Exception $ex) {
            return response()->json([
                'status'  => 500,
                'message' => 'An Error Occured, Try Again!',
                'error'   => $ex,
            ]);
        }
    }
    public function resendOtp(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->exists();
        $userDetail = User::where('email', $request->email)->first();

        if ($user && $userDetail->role != 5) {
            // Delete previous password reset request if exists for this email
            DB::table('password_resets')->where('email', $request->email)->delete();

            // Generate a new 6-digit OTP
            $otp = rand(100000, 999999);
            // Set OTP expiration time (default to 10 minutes, or set in .env as PASSWORD_OTP_EXPIRY)
            $expiration = now()->addMinutes(env('PASSWORD_OTP_EXPIRY', 10));

            // Store new OTP details in the password_resets table
            DB::table('password_resets')->insert([
                'email'      => $request->email,
                'token'      => $otp,
                'created_at' => now(),
                'expires_at' => $expiration,
            ]);

            // Send OTP to the email
            $emailSent = $this->resetPasswordLink($otp, $userDetail);

            if ($emailSent) {
                return response()->json([
                    'status' => 200,
                    'message' => 'A new OTP has been sent to your email.'
                ]);
            } else {
                return response()->json([
                    'status'  => 500,
                    'message' => 'SMTP is not working, try again later!',
                ]);
            }
        } else {
            return response()->json([
                'status'  => 404,
                'message' => 'Account with the entered email does not exist.',
            ]);
        }
    } catch (Exception $ex) {
        return response()->json([
            'status'  => 500,
            'message' => 'An error occurred, try again!',
            'error'   => $ex->getMessage(),
        ]);
    }
}

    

    public function showOtpForm()
    {
        return view('Admin.Auth.otp-match');
    }


    public function matchOtp(Request $request)
    {
        try {
            // Validate the OTP input
            $request->validate([
                'otp' => 'required|numeric',
            ]);
    
            // Retrieve the email from session (set when sending the OTP)
            $email = session('admin_reset_email');
            if (!$email) {
                return redirect()->back()->withErrors(['Session expired or unauthorized. Please request a new OTP.']);
            }
    
            // Retrieve the OTP record for the email
            $passwordReset = DB::table('password_resets')->where('email', $email)->first();
            if (!$passwordReset) {
                return redirect()->back()->withErrors(['No OTP request found for this email.']);
            }
    
            // Check if the OTP has expired
            if (now()->greaterThan($passwordReset->expires_at)) {
                // Remove expired OTP record
                DB::table('password_resets')->where('email', $email)->delete();
                return redirect()->back()->withErrors(['OTP has expired, please request a new one.']);
            }
    
            // Compare the entered OTP with the stored OTP token
            if ($request->otp != $passwordReset->token) {
                return redirect()->back()->withErrors(['Invalid OTP provided.']);
            }
    
            // OTP is verified successfully.
            // Delete the OTP record for one-time use
            DB::table('password_resets')->where('email', $email)->delete();
    
            // Redirect to the Reset Password page (route name: Admin.Auth.ResetPassword) with a success message
            return redirect()->route('Admin.Auth.ResetPassword')->with('status', 'OTP verified successfully.');
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors(['An error occurred while verifying the OTP. Please try again.']);
        }
    }
    

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);
    
        // Retrieve the email from the session
        $email = session('admin_reset_email');
        if (!$email) {
            return redirect()->back()->withErrors(['No password reset session found.']);
        }
    
        // Retrieve the user by email
        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->back()->withErrors(['User not found.']);
        }
    
        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();
    
        // Delete the token from the password_resets table
        DB::table('password_resets')->where('email', $email)->delete();
    
        // Optional: Send the password reset confirmation email
        $logo = GeneralSettingsClass::getRequiredValue('_header_logo');
        $params = [
            'subject'  => 'Password Reset Successfully | ' . config('app.website_name'),
            'to'       => $user->email,
            'template' => 'Admin.Emails.update-password',
            'data'     => [
                'name'     => $user->name,
                'url'      => route('Admin.Login'),
                'email'    => $email,
                'password' => $request->password,
                'img_url'  => url('/'),
                'logo'     => $logo,
            ],
        ];
    
        // Attempt to send email, if required
        $this->SendEmail($params);
    
        // Redirect to the login route with a success message
    return response()->json(['message' => 'Password updated successfully.','status'=>200]);
    }
    
    
}
