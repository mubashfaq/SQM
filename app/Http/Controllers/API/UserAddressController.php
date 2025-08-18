<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GeneralSettings;


class UserAddressController extends Controller
{
    public function index()
    {
        return $this->success(Auth::user());
    }

    public function store(Request $request)
    {
        $validator = $this->userAddressValidationRequest($request);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $message = reset($errors)[0]; // Get the first error message
            return $this->error($message, 422);
        }
        $userAddress = UserAddress::firstOrCreate($validator->validated());
        if (!$userAddress) {
            return $this->error('Failed to create user address', 500);
        }
        return $this->success($userAddress);
    }

    public function update(Request $request, UserAddress $userAddress)
    {

        $validator = $this->userUpdateAddressValidationRequest($request);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $message = reset($errors)[0]; // Get the first error message
            return $this->error($message, 422);
        }
        $data = $userAddress->update($validator->validated());
        if ($data === false) {
            return $this->error('Failed to update user address', 500);
        }
        return $this->success($data,'data updated successfully');

    }
    public function destroy(UserAddress $userAddress)
    {
        $data = $userAddress->delete();
        if ($data === false) {
            return $this->error('Failed to delete user address', 500);
        }
        return $this->success($data ,'data deleted successfully');

    }
    
    public function socialMediaLinks()
    {
        $links = GeneralSettings::whereIn('option', ['_instagram_link', '_facebook_link', '_youtube_link', '_twitter_link', '_linke_din'])->pluck('value', 'option')->toArray();
        return $this->success($links);        
    }

}
