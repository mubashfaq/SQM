<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\API\Traits\ValidationTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProfileController extends Controller{
    use ValidationTrait;


    public function index(Request $request)
    {
        if (!Auth::check()) {
            return $this->message('Please login first', 401);
        }
        $user = Auth::user()->load('userMeta');

        return $this->success($user, 'User profile', 200);
    }
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return $this->error(['message' => 'Please login first'], 401);
        }
        $user = Auth::user();
        $validator = $this->userProfileValidationRequest($request);
        if ($validator->fails()) {
            return $this->handleValidationFailure($validator);
        }
        if ($request->filled('old_password') && !$this->checkOldPassword($request->old_password, $user->password)) {
            return $this->message('Old password does not match', 422);
        }
        DB::beginTransaction();
        try {
            $this->updateUserProfile($request, $user);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(['message' => 'Failed to update profile. Please try again.'], 500);
        }
        return $this->success($user->load('userMeta'));
    }

}
