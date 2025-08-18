<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role_id',
        'profile_image',
        'email_verified_at',
        'active',
        'created_by'
        // Add other fields here
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $with = ['shippingAddresses'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_created_at' => 'datetime',

    ];

    protected $appends = ['user_membership_plans', 'signup_plans'];


    public function userMeta()
    {
        return $this->hasMany(UserMeta::class, 'user_id');
    }

    public function shippingAddresses(){
        return $this->hasMany(UserAddress::class, 'user_id');
    }
    public function orders()
    {
        return $this->hasMany(order::class);
    }
    public function subscriptions()
    {
        return $this->hasMany(MembershipSubscription::class, 'customer_id', 'id');
    }

    public function getUserMembershipPlansAttribute()
{
    if (Auth::guard('sanctum')->check()) {
        return $this->subscriptions()
            ->where('expired', 0)
            ->whereHas('plan', function ($query) {
                $query->where('signup', 0); // Exclude plans with signup = 1
            })
            ->with('plan')
            ->get()
            ->pluck('plan')
            ->filter()
            ->values();
    }
    return null;
}

public function getSignupPlansAttribute()
{
    if (Auth::guard('sanctum')->check()) {
        return MembershipPlan::where('signup', 1)
            ->get()
            ->toArray();
    }
    return null;
}

}
