<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Traits\ValidationTrait;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
      use ValidationTrait;

      public function getUser()
       {
         $user = Auth::guard('sanctum')->user();
         if (!$user) {
         return $this->error('User not authenticated', 401); // Handle unauthenticated case
         }
         $user = User::find($user->id);
         return $this->success($user);
       }


    public function login(Request $request)
    {
      return $this->loginUser($request);
    }

    public function register(Request $request)
    {
        return $this->registerUser($request);
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return $this->message('Please login first', 401);
        }

        $request->user()->tokens()->delete();
        return $this->message('Logged out successfully', 200);
    }

    public function checkUserEmail(Request $request)
    {
        $validator = $this->emailValidationRequest($request);
        if ($validator->fails()) {
           return $this->handleValidationFailure($validator);
        }
        $user = $this->getUserByEmail($request);
        if (!$user) {
            return $this->message('User does not exist', 404);
        }
        $otp = $this->generateAndStoreOtp($user);
        $this->sendOtpEmail($user, $otp);
        return $this->message( 'OTP sent to email :' .' ' .$user->email);
    }

    public function confirmOtp(Request $request)
    {
        $validator = $this->emailOtpValidationRequest($request);
        if ($validator->fails()) {
            return $this->handleValidationFailure($validator);
        }
        $user = $this->getUserByEmail($request);
        if (!$user) {
            return $this->message('User does not exist', 404);
        }
        if (!$this->isOtpValid($request->otp, $user)) {
            return $this->message('OTP is invalid or expired', 400);
        }
        return $this->message('success');
    }
    public function updatePassword(Request $request)
    {
        $validator = $this->passwordValidationRequest($request);
        if ($validator->fails()) {
            return $this->handleValidationFailure($validator);
        }
        $user = $this->verifyOtpAndGetUser($request);
        if (!$user) {
            return $this->message('Unauthorized access', 401);
        }
        if ($this->validateAndUpdatePassword($request, $user) !== true) {
            return $this->message('Failed to update password', 400);
        }

        return $this->message('Password updated successfully');
    }

}
