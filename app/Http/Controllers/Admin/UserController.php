<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\API\Traits\EmailTrait;
use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use App\Models\User;
use App\Models\UserMeta;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Validation\Rules\Password; // Ensure you have this import

class UserController extends Controller
{
    use EmailTrait;
    public function index()
    {
        $users = User::where('role_id', '!=', 5)->latest()->get();
        $roles = DB::table('roles')->get();
        return view('Admin.Users.index', compact('users', 'roles'));
    }

    public function edit(User $User)
    {
        $User->load('userMeta');
        $roles = DB::table('roles')->get();
        return view('Admin.Users.edit', compact('User', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $sanitizedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'phone' => 'required|string|max:15',
            'role_id' => 'required|integer',
        ]);

        $sanitizedData['profile_image'] = '/user.jpg';

        if (isset($request->created_by)) {
            $sanitizedData['created_by'] = $request->created_by;
            $sanitizedData['email_verified_at'] = now();
            $sanitizedData['active'] = 1;
        }

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found.',
            ]);
        }
        $user->update($sanitizedData);
        $metaDescriptions = $request->only([
            'business_name',
            'business_email',
            'business_phone',
            'business_street_address',
            'area_address',
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

        # Delete previous product meta descriptions
        DB::table('user_metas')->where('user_id', $user->id)->delete();
        DB::table('user_metas')->insert($userMeta);

        $data = GeneralSettings::where('option', '_header_logo')->orWhere('option', '_b_email')->orWhere('option', '_instagram_link')->orWhere('option', '_facebook_link')->orWhere('option', '_twitter_link')->orWhere('option', '_youtube_link')->get()->toArray();

        $header_logo_index = array_search('_header_logo', array_column($data, 'option'));
        $logo = $data[$header_logo_index]['value'];

        if ($user) {

            $route = route('Admin.Login');
            if ($user->role_id == 5) {
                $route = url('/') . '/sign-in';
            } else {
                $route = route('Admin.Login');
            }

            if (isset($request->confirmation_email_sending)) {
                $params = [
                    'subject' => 'Account Login Details | Sydney Quality Meats',
                    'to' => $user->email,
                    'template' => 'Admin.Emails.user-update-details',
                    'data' => [
                        'user' => $user,
                        'userMeta' => $userMeta,
                        'url' => $route,
                        'image_url' => url('/'),
                        'logo' => $logo
                    ]
                ];

                $emailSent = $this->sendEmail($params);


                if ($emailSent) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Account Updated successfully, An Email has been sent to user email for account information.',
                    ]);
                } else {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Account Upated successfully, An Error Occurred while sending the email.',
                    ]);
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Account Updated successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'An Error Occurred, During Updated user account.',
            ]);
        }
    }

    public function customers()
    {
        $users = User::all();              // 👈 now includes user #1
        $customers = $users->where('role_id', 5);
        $roles = DB::table('roles')->get();

        return view('Admin.Users.customer', compact('users', 'customers', 'roles'));
    }


    public function create()
    {
        $roles = DB::table('roles')->get();
        return view('Admin.Users.create', compact('roles'));
    }

    public function show(User $user)
    {
        $users = User::all();
        $roles = DB::table('roles')->get();
        $UserMeta = UserMeta::where('user_id', $user->id)->get();
        return response()->json([
            'status' => 200,
            'user_details' => $user,
            'roles' => $roles,
            'users' => $users,
            'user_meta' => $UserMeta,
        ]);
    }

    public function store(Request $request)
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
            'role_id' => 'required|integer',
        ]);

        $sanitizedData['profile_image'] = '/user.jpg';
        if (isset($request->created_by)) {
            $sanitizedData['created_by'] = $request->created_by;
            $sanitizedData['email_verified_at'] = now();
            $sanitizedData['active'] = 1;
        }

        $sanitizedData['password'] = Hash::make($sanitizedData['password']);

        $user = User::create($sanitizedData);
        $metaDescriptions = $request->only([
            'business_name',
            'business_email',
            'business_phone',
            'business_street_address',
            'area_address',
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
        $customers = User::where('role_id', 5)->where('active', 1)->get();

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
            $route = route('Admin.Login');
            if ($user->role_id == 5) {
                $route = url('/') . '/sign-in';
            } else {
                $route = route('Admin.Login');
            }

            if (isset($request->confirmation_email_sending)) {
                $params = [
                    'subject' => 'Account Login Details | Sydney Quality Meats',
                    'to' => $request->email,
                    'template' => 'Admin.Emails.account-login-details',
                    'data' => [
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => $request->password,
                        'url' => $route,
                        'image_url' => url('/'),
                        'logo' => $logo
                    ]
                ];

                $emailSent = $this->sendEmail($params);
                if ($emailSent) {
                    return response()->json([
                        'status' => 200,
                        'customers' => $customers,
                        'message' => 'Account created successfully, An Email has been sent to user email for account information.',
                    ]);
                } else {
                    return response()->json([
                        'status' => 500,
                        'customers' => $customers,
                        'message' => 'Account created successfully, An Error Occurred while sending the email.',
                    ]);
                }
            }

            return response()->json([
                'status' => 200,
                'customers' => $customers,
                'message' => 'Account created successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'An Error Occurred, During creating user account.',
            ]);
        }
    }

    public function destroy(User $user)
    {
        $redirectRoute = ($user->role_id == 5) ? 'Customer.List' : 'users.index';

        $user->delete();

        return redirect()->route($redirectRoute);
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'active' => 'required',
        ]);

        $user->active = $request->active;
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'User status updated successfully.',
        ]);
    }




    public function showUserProfile()
    {
        $user = auth()->user();
        return view('Admin.Users.profile')->with(['user' => $user]);
    }


    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $updateType = $request->input('update_type', '');

        // Validate general inputs if any are required
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:15',
        ]);

        // Handle password update
        if ($updateType === 'password') {
            $request->validate([
                'old_password' => [
                    'required',
                    function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            $fail('The current password is incorrect.');
                        }
                    }
                ],
                'new_password' => [
                    'required',
                    'confirmed',
                    'min:8', // Minimum length of 8 characters
                    'regex:/[a-z]/', // Requires at least one lowercase letter
                    'regex:/[A-Z]/', // Requires at least one uppercase letter
                    'regex:/[0-9]/', // Requires at least one number
                    'regex:/[@$!%*?&]/', // Requires at least one special character
                ],
            ]);

            // Update the user's password
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->back()->with('success', 'Password updated successfully.');
        }

        // Check if the profile image is being uploaded
        if ($request->hasFile('profile_image')) {
            $request->validate([
                'profile_image' => ['required', 'image', 'max:2048'], // 2MB max size
            ]);

            // Create directory inside the public folder if it doesn't exist
            $profileImagePath = public_path('profile_images');
            if (!File::exists($profileImagePath)) {
                File::makeDirectory($profileImagePath, 0755, true); // Create directory with permissions
            }

            // Delete old image if exists
            if ($user->profile_image && File::exists(public_path('profile_images/' . $user->profile_image))) {
                File::delete(public_path('profile_images/' . $user->profile_image)); // Delete the old image
            }

            // Store new image in the public folder
            $imageName = time() . '_' . $user->id . '.' . $request->profile_image->extension();
            $request->profile_image->move($profileImagePath, $imageName); // Move image to the directory

            // Update user's profile image
            $user->update([
                'profile_image' => 'profile_images/' . $imageName,
            ]);

            return redirect()->back()->with('success', 'Profile image updated successfully.');
        }

        // Handle other updates (e.g., name, email, phone)
        $user->update([
            'name' => $request->input('name', $user->name),
            'email' => $request->input('email', $user->email),
            'phone' => $request->input('phone', $user->phone),
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
